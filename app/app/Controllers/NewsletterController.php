<?php

namespace App\Controllers;

use App\Helpers\FormGuard;
use App\Helpers\MailHelper;
use App\Helpers\AuditHelper;
use App\Helpers\SessionHelper;
use App\Libraries\ListQuery;
use App\Repositories\NewsletterRepository;

/**
 * Newsletter: subscription from the home page (confirmed by e-mail), unsubscription link in every e-mail,
 * sending of the upcoming events of the agenda (super admin, ASBL admin).
 */
class NewsletterController extends BaseController
{
    /**
     * Periods of the agenda that can be sent (days)
     */
    public const PERIODS = [14 => 'les 2 prochaines semaines', 31 => 'le mois à venir', 62 => 'les 2 prochains mois'];

    private NewsletterRepository $newsletterRepository;

    public function __construct()
    {
        $this->newsletterRepository = service('repository', 'Newsletter');
        helper('form');
    }

    // ---- Public

    public function subscribe()
    {
        $guard = FormGuard::check('newsletter');
        if ($guard === 'spam')
            return $this->backHome('success', 'Merci ! Un e-mail vous a été envoyé pour confirmer votre inscription.');
        if ($guard !== null)
            return $this->backHome('errors', [$guard]);

        $email = strtolower(trim((string) $this->request->getPost('email')));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255)
            return $this->backHome('errors', ['L\'adresse e-mail n\'est pas valide.']);

        $existing = $this->newsletterRepository->findByEmail($email);
        if ($existing && $existing->confirmed_at && !$existing->unsubscribed_at)
            return $this->backHome('success', 'Cette adresse est déjà inscrite à la newsletter.');

        $token = $this->newsletterRepository->subscribe($email);
        $sent = MailHelper::send($email, 'Confirmez votre inscription à la newsletter', 'emails/newsletter_confirm', ['link' => base_url('newsletter/confirmer/' . $token)]);

        return $sent
            ? $this->backHome('success', 'Merci ! Un e-mail vous a été envoyé pour confirmer votre inscription.')
            : $this->backHome('errors', ['L\'e-mail de confirmation n\'a pas pu être envoyé. Réessayez plus tard.']);
    }

    public function confirm($token)
    {
        $subscriber = $this->newsletterRepository->findByToken((string) $token);
        if ($subscriber === null)
            return $this->result('Lien invalide', 'Ce lien de confirmation n\'est pas valable. Inscrivez-vous à nouveau depuis la page d\'accueil.');

        $this->newsletterRepository->confirm((int) $subscriber->id);
        return $this->result('Inscription confirmée', 'Merci ! Vous recevrez les prochaines actualités de l\'unité à l\'adresse ' . $subscriber->email . '.');
    }

    public function unsubscribe($token)
    {
        $subscriber = $this->newsletterRepository->findByToken((string) $token);
        if ($subscriber === null)
            return $this->result('Lien invalide', 'Ce lien de désinscription n\'est pas valable, ou l\'adresse a déjà été supprimée.');

        $this->newsletterRepository->unsubscribe((int) $subscriber->id);
        return $this->result('Désinscription effectuée', 'L\'adresse ' . $subscriber->email . ' ne recevra plus la newsletter.');
    }

    // ---- Administration

    public function index()
    {
        $period = (int) ($this->request->getGet('periode') ?? 31);
        $period = isset(self::PERIODS[$period]) ? $period : 31;

        if ($this->request->getGet('format') === 'csv')
            return $this->exportCsv();

        $list = ListQuery::fromRequest($this->request, ['status' => ['active', 'pending', 'unsubscribed', '']]);
        $builder = $this->newsletterRepository->adminQuery($list->filter('status'));
        $list->search($builder, ['email']);
        $result = $list->paginate($builder, [NewsletterRepository::class, 'cast']);
        $result['counts'] = $this->newsletterRepository->countByStatus();

        return $this->listResponse('pages/admin/newsletter/index', [
            'recipientCount' => $result['counts']['active'],
            'events' => $this->upcomingEvents($period),
            'period' => $period,
            'periods' => self::PERIODS,
            'sendings' => $this->newsletterRepository->getSendings(),
        ], $result);
    }

    /**
     * Spreadsheet of the confirmed subscribers
     */
    private function exportCsv()
    {
        $emails = array_column($this->newsletterRepository->adminQuery('active')->get()->getResultArray(), 'email');
        return $this->csvResponse('newsletter-abonnes.csv', array_merge([['E-mail']], array_map(fn($email) => [$email], $emails)));
    }

    /**
     * Sends the upcoming events of the period to the confirmed subscribers (one e-mail each, with its unsubscription link).
     */
    public function send()
    {
        $period = (int) $this->request->getPost('period');
        if (!isset(self::PERIODS[$period]))
            return $this->redirectWithErrors('/admin/newsletter', 'Période inconnue.');

        $events = $this->upcomingEvents($period);
        $recipients = $this->newsletterRepository->getRecipients();
        if (!$events)
            return $this->redirectWithErrors('/admin/newsletter?periode=' . $period, 'Aucun événement à annoncer sur cette période.');
        if (!$recipients)
            return $this->redirectWithErrors('/admin/newsletter?periode=' . $period, 'Aucun abonné confirmé.');

        $subject = trim((string) $this->request->getPost('subject')) ?: 'Les prochaines activités des Guides et Scouts de Gosselies';
        $introduction = trim((string) $this->request->getPost('introduction'));
        set_time_limit(0);

        $failed = 0;
        foreach ($recipients as $recipient) {
            $sent = MailHelper::send($recipient->email, mb_substr($subject, 0, 200), 'emails/newsletter', [
                'events' => $events,
                'introduction' => $introduction,
                'unsubscribeLink' => base_url('newsletter/desinscription/' . $recipient->token),
            ]);
            $failed += $sent ? 0 : 1;
        }

        $this->newsletterRepository->addSending([
            'subject' => mb_substr($subject, 0, 255),
            'event_count' => count($events),
            'recipient_count' => count($recipients),
            'failed_count' => $failed,
            'sent_by' => SessionHelper::getUserConnected()->getId(),
        ]);
        AuditHelper::log('sent', 'Newsletter', $subject . ' (' . count($recipients) . ' abonné' . (count($recipients) > 1 ? 's' : '') . ')', '/admin/newsletter');

        $message = 'La newsletter a été envoyée à ' . (count($recipients) - $failed) . ' abonné' . (count($recipients) - $failed > 1 ? 's' : '') . '.';
        if ($failed) {
            log_message('error', 'Newsletter : {failed} e-mail(s) sur {total} non envoyé(s)', ['failed' => $failed, 'total' => count($recipients)]);
            return $this->redirectWithErrors('/admin/newsletter', $message . ' ' . $failed . ' e-mail(s) n\'ont pas pu être envoyés : voir le journal.');
        }
        $this->session->setFlashdata('success', $message);
        return redirect()->to(base_url('/admin/newsletter'));
    }

    public function delete($id)
    {
        $this->newsletterRepository->remove((int) $id);
        $this->session->setFlashdata('success', 'L\'adresse a été supprimée de la liste.');
        return redirect()->to(base_url('/admin/newsletter'));
    }

    /**
     * Events starting in the coming days
     */
    private function upcomingEvents(int $days): array
    {
        $limit = date('Y-m-d 23:59:59', strtotime('+' . $days . ' days'));
        $events = service('repository', 'Event')->getUpcoming(0, 50)['events'];
        return array_values(array_filter($events, fn($event) => $event->start_at <= $limit));
    }

    private function backHome(string $type, $message)
    {
        $this->session->setFlashdata($type, $message);
        return redirect()->to(base_url('/#newsletter'));
    }

    private function result(string $title, string $text)
    {
        return view('pages/newsletter_result', ['title' => $title, 'text' => $text]);
    }
}
