<?php

namespace App\Controllers;

use DateTime;
use App\Helpers\SessionHelper;
use App\Controllers\BaseController;
use App\Repositories\EventRepository;
use App\Repositories\SectionRepository;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * Administration of the agenda (events).
 * Only the events that are not finished yet can be modified.
 */
class AgendaController extends BaseController
{
    private const TIME_REGEX = '/^([01][0-9]|2[0-3]):[0-5][0-9]$/';

    private EventRepository $eventRepository;
    private SectionRepository $sectionRepository;

    public function __construct()
    {
        $this->eventRepository = service('repository', 'Event');
        $this->sectionRepository = service('repository', 'Section');
        helper('form');
    }

    /**
     * Lists the upcoming events, or the past ones (?periode=passes).
     */
    public function index()
    {
        $past = $this->request->getGet('periode') === 'passes';

        return view('pages/admin/agenda/index', [
            'past' => $past,
            'events' => $this->toJson($this->eventRepository->getByPeriod(!$past)),
        ]);
    }

    public function create()
    {
        return $this->form();
    }

    public function store()
    {
        $event = $this->validateEvent();
        if ($event === null)
            return redirect()->to(base_url('/admin/agenda/create'))->withInput();

        [$data, $sectionIds] = $event;
        $data['user_id'] = SessionHelper::getUserConnected()->getId();
        $this->eventRepository->create($data, $sectionIds);

        $this->session->setFlashdata('success', 'L\'événement « ' . $data['title'] . ' » a été créé.');
        return redirect()->to(base_url('/admin/agenda'));
    }

    public function edit($id)
    {
        $event = $this->getEditableEvent((int) $id);
        if ($event instanceof RedirectResponse)
            return $event;

        return $this->form($event);
    }

    public function update($id)
    {
        $event = $this->getEditableEvent((int) $id);
        if ($event instanceof RedirectResponse)
            return $event;

        $validated = $this->validateEvent();
        if ($validated === null)
            return redirect()->to(base_url('/admin/agenda/edit/' . $event->id))->withInput();

        [$data, $sectionIds] = $validated;
        $this->eventRepository->updateWithSections($event->id, $data, $sectionIds);

        $this->session->setFlashdata('success', 'L\'événement « ' . $data['title'] . ' » a été modifié.');
        return redirect()->to(base_url('/admin/agenda'));
    }

    public function delete($id)
    {
        $event = $this->eventRepository->getWithSections((int) $id);

        if ($event === null) {
            $this->session->setFlashdata('errors', ['L\'événement n\'existe pas ou a déjà été supprimé.']);
            return redirect()->to(base_url('/admin/agenda'));
        }

        $this->eventRepository->delete($event->id, false);
        $this->session->setFlashdata('success', 'L\'événement « ' . $event->title . ' » a été supprimé.');

        //Back to the list the event was displayed in
        $past = new DateTime($event->end_at) < new DateTime();
        return redirect()->to(base_url('/admin/agenda' . ($past ? '?periode=passes' : '')));
    }

    /**
     * Displays the creation/edition form. The values come from the previous submission if any (validation errors), otherwise from the event.
     *
     * @param object|null $event
     */
    private function form($event = null)
    {
        $start = $event ? new DateTime($event->start_at) : null;
        $end = $event ? new DateTime($event->end_at) : null;

        // The values are escaped when printed (x-model / json), so the old input is retrieved without escaping
        $old = fn(string $key, $default = '') => old($key, $default, false);
        $submitted = old('title', null, false) !== null;

        $values = [
            'title' => $old('title', $event->title ?? ''),
            'description' => $old('description', $event->description ?? ''),
            'location' => $old('location', $event->location ?? ''),
            'registration_url' => $old('registration_url', $event->registration_url ?? ''),
            'all_day' => $submitted ? $old('all_day') === '1' : (bool) ($event->all_day ?? false),
            'start_date' => $old('start_date', $start ? $start->format('Y-m-d') : ''),
            'start_time' => $old('start_time', $start && !$event->all_day ? $start->format('H:i') : ''),
            'end_date' => $old('end_date', $end ? $end->format('Y-m-d') : ''),
            'end_time' => $old('end_time', $end && !$event->all_day ? $end->format('H:i') : ''),
            'sections' => array_map('intval', (array) ($submitted ? $old('sections', []) : ($event ? array_column($event->sections, 'id') : []))),
        ];

        return view('pages/admin/agenda/form', [
            'event' => $event,
            'values' => $this->toJson($values),
            'sections' => $this->toJson($this->sectionRepository->getAllOrdered()),
        ]);
    }

    /**
     * Gets an event that can still be modified, or a redirection with an error.
     *
     * @param int $id
     * @return object|\CodeIgniter\HTTP\RedirectResponse
     */
    private function getEditableEvent(int $id)
    {
        $event = $this->eventRepository->getWithSections($id);

        if ($event === null) {
            $this->session->setFlashdata('errors', ['L\'événement n\'existe pas ou a été supprimé.']);
            return redirect()->to(base_url('/admin/agenda'));
        }

        if (new DateTime($event->end_at) < new DateTime()) {
            $this->session->setFlashdata('errors', ['L\'événement « ' . $event->title . ' » est terminé, il ne peut plus être modifié.']);
            return redirect()->to(base_url('/admin/agenda?periode=passes'));
        }

        return $event;
    }

    /**
     * Validates the submitted event.
     *
     * @return array|null [event data, section ids] or null if the event is invalid (the errors are stored in the flashdata)
     */
    private function validateEvent()
    {
        $allDay = $this->request->getPost('all_day') === '1';
        $post = $this->request->getPost();
        $post['sections'] = (array) ($post['sections'] ?? []);

        $rules = [
            'title' => ['label' => 'Titre', 'rules' => 'required|max_length[255]'],
            'sections' => ['label' => 'Sections', 'rules' => 'required'],
            'start_date' => ['label' => 'Date de début', 'rules' => 'required|valid_date[Y-m-d]'],
            'end_date' => ['label' => 'Date de fin', 'rules' => 'permit_empty|valid_date[Y-m-d]'],
            // Array syntax because the regex contains a "|" (rule separator)
            'start_time' => ['label' => 'Heure de début', 'rules' => [$allDay ? 'permit_empty' : 'required', 'regex_match[' . self::TIME_REGEX . ']']],
            'end_time' => ['label' => 'Heure de fin', 'rules' => ['permit_empty', 'regex_match[' . self::TIME_REGEX . ']']],
            'location' => ['label' => 'Lieu', 'rules' => 'permit_empty|max_length[255]'],
            'description' => ['label' => 'Description', 'rules' => 'permit_empty|max_length[5000]'],
            'registration_url' => ['label' => 'Lien d\'inscription', 'rules' => 'permit_empty|valid_url_strict|max_length[255]'],
        ];

        $messages = [
            'title' => ['required' => 'Le titre est obligatoire.', 'max_length' => 'Le titre ne peut pas dépasser 255 caractères.'],
            'sections' => ['required' => 'Sélectionnez au moins une section.'],
            'sections.*' => ['in_list' => 'Une des sections sélectionnées n\'existe pas.'],
            'start_date' => ['required' => 'La date de début est obligatoire.', 'valid_date' => 'La date de début est invalide.'],
            'end_date' => ['valid_date' => 'La date de fin est invalide.'],
            'start_time' => ['required' => 'L\'heure de début est obligatoire (ou cochez « Toute la journée »).', 'regex_match' => 'L\'heure de début est invalide.'],
            'end_time' => ['regex_match' => 'L\'heure de fin est invalide.'],
            'location' => ['max_length' => 'Le lieu ne peut pas dépasser 255 caractères.'],
            'description' => ['max_length' => 'La description ne peut pas dépasser 5000 caractères.'],
            'registration_url' => ['valid_url_strict' => 'Le lien d\'inscription doit être une adresse web complète (https://...).', 'max_length' => 'Le lien d\'inscription ne peut pas dépasser 255 caractères.'],
        ];

        //Each selected section must exist (checked only if at least one section is selected, otherwise "required" already fails)
        if ($post['sections'])
            $rules['sections.*'] = ['label' => 'Sections', 'rules' => 'in_list[' . implode(',', $this->sectionRepository->getAllIds()) . ']'];

        if (!$this->validateData($post, $rules, $messages)) {
            $this->session->setFlashdata('errors', $this->validator->getErrors());
            return null;
        }

        $startDate = $post['start_date'];
        $endDate = ($post['end_date'] ?? '') ?: $startDate;

        if ($allDay) {
            $startAt = $startDate . ' 00:00:00';
            $endAt = $endDate . ' 23:59:59';
        } else {
            $startAt = $startDate . ' ' . $post['start_time'] . ':00';
            $endAt = $endDate . ' ' . (($post['end_time'] ?? '') ?: $post['start_time']) . ':00';
        }

        $errors = [];
        if ($endAt < $startAt)
            $errors[] = 'La fin de l\'événement doit être après son début.';
        else if (new DateTime($endAt) < new DateTime())
            $errors[] = 'L\'événement est déjà terminé : seuls les événements à venir peuvent être créés ou modifiés.';

        if ($errors) {
            $this->session->setFlashdata('errors', $errors);
            return null;
        }

        $data = [
            'title' => trim($post['title']),
            'description' => trim($post['description'] ?? '') ?: null,
            'location' => trim($post['location'] ?? '') ?: null,
            'registration_url' => trim($post['registration_url'] ?? '') ?: null,
            'start_at' => $startAt,
            'end_at' => $endAt,
            'all_day' => $allDay,
        ];

        return [$data, array_map('intval', $post['sections'])];
    }

    /**
     * Encodes data to be safely printed inside a <script> tag.
     */
    private function toJson($data)
    {
        return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }
}
