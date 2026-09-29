<?php

namespace App\Controllers;

use App\Helpers\FormGuard;
use App\Helpers\MailHelper;
use App\Controllers\BaseController;
use App\Repositories\EventRepository;
use App\Controllers\API\V1\NewsController;
use App\Controllers\API\V1\LeadersController;
use App\Repositories\SectionLeaderRepository;

/**
 * This class represents the Home Controller that handles the landing page and othet stuff.
 * It retrieves the section leaders and the first upcoming events of the agenda (news)
 * and passes them to the welcome_message view.
 *
 * @author   Guillaume cornez
 */
class HomeController extends BaseController
{
    private SectionLeaderRepository $leaderRepository;
    private EventRepository $eventRepository;

    public function __construct()
    {
        $this->leaderRepository = service('Repository', 'SectionLeader');
        $this->eventRepository = service('Repository', 'Event');
    }

    /**
     * 
     * This method returns the landing page
     *
     * @author Guillaume cornez
     */
    public function index()
    {
        // Section leaders, managed in admin/responsables
        // The first ones, the next ones are loaded by pages (api/v1/responsables)
        $leaders = $this->leaderRepository->getPublicPage(0, LeadersController::PAGE_SIZE);
        // News = upcoming events of the agenda, the next ones are loaded 3 by 3 (api/v1/actualites)
        $news = $this->eventRepository->getUpcoming(0, NewsController::PAGE_SIZE);

        $contents = service('repository', 'Content');

        return view('pages/welcome_message', [
            'leaders' => $this->toJson($leaders),
            // FAQ and testimonials, managed in admin/contenus
            'questions' => $contents->getItems('faq', true),
            'testimonials' => $contents->getItems('temoignage', true),
            'news' => $this->toJson(['items' => $news['events'], 'has_more' => $news['has_more']]),
        ]);
    }

    public function contact()
    {
        helper('form');
        return view('pages/contact', ['contactEmail' => config('Site')->contactEmail]);
    }

    /**
     * Message of the contact form: saved (admin/messages) and sent by e-mail to the unit, the answer going to the sender.
     */
    public function sendContact()
    {
        $guard = FormGuard::check('contact');
        if ($guard === 'spam')
            return $this->contactSent();
        if ($guard !== null)
            return $this->redirectWithErrors('/contact#formulaire', $guard)->withInput();

        $post = array_map(fn($value) => is_string($value) ? trim($value) : $value, $this->request->getPost());
        $rules = [
            'fullname' => 'required|max_length[150]',
            'email' => 'required|valid_email|max_length[255]',
            'phone' => 'permit_empty|max_length[30]',
            'message' => 'required|min_length[10]|max_length[5000]',
        ];
        $messages = [
            'fullname' => ['required' => 'Votre nom est obligatoire.', 'max_length' => 'Votre nom est trop long.'],
            'email' => ['required' => 'Votre adresse e-mail est obligatoire.', 'valid_email' => 'L\'adresse e-mail n\'est pas valide.', 'max_length' => 'L\'adresse e-mail est trop longue.'],
            'phone' => ['max_length' => 'Le numéro de téléphone est trop long.'],
            'message' => ['required' => 'Le message est vide.', 'min_length' => 'Le message est trop court.', 'max_length' => 'Le message est trop long (5000 caractères maximum).'],
        ];
        if (!$this->validateData($post, $rules, $messages))
            return $this->redirectWithErrors('/contact#formulaire', array_values($this->validator->getErrors()))->withInput();

        $repository = service('repository', 'ContactMessage');
        $contact = (object) ['name' => $post['fullname'], 'email' => strtolower($post['email']), 'phone' => ($post['phone'] ?? '') ?: null, 'message' => $post['message']];
        $repository->create((array) $contact);

        MailHelper::send(config('Site')->contactEmail, 'Message du site : ' . $contact->name, 'emails/contact_message',
            ['contact' => $contact, 'link' => base_url('admin/messages')], $contact->email);

        return $this->contactSent();
    }

    private function contactSent()
    {
        $this->session->setFlashdata('success', 'Merci, votre message a bien été envoyé. Nous vous répondrons dès que possible.');
        return redirect()->to(base_url('/contact#formulaire'));
    }
}
