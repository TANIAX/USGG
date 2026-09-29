<?php

namespace App\Controllers;

use App\Helpers\AuditHelper;
use App\Helpers\LogHelper;
use DateTime;
use Exception;
use App\Helpers\ImageHelper;
use App\Libraries\ListQuery;
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
        $list = ListQuery::fromRequest($this->request, ['periode' => ['', 'passes']]);
        $builder = $this->eventRepository->adminQuery($list->filter('periode') !== 'passes');
        $list->search($builder, ['event.title', 'event.location', [$this->eventRepository, 'sectionNameCondition']]);

        return $this->listResponse('pages/admin/agenda/index', [], $list->paginate($builder, null, [$this->eventRepository, 'withSections']));
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

        $image = $this->processImage();
        if (isset($image['error'])) {
            $this->session->setFlashdata('errors', [$image['error']]);
            return redirect()->to(base_url('/admin/agenda/create'))->withInput();
        }
        if ($image['replace'])
            $data['image'] = $image['image'];

        $data['user_id'] = SessionHelper::getUserConnected()->getId();
        $this->eventRepository->create($data, $sectionIds);

        AuditHelper::log('created', 'Événement', $data['title'], '/admin/agenda');
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

        $image = $this->processImage();
        if (isset($image['error'])) {
            $this->session->setFlashdata('errors', [$image['error']]);
            return redirect()->to(base_url('/admin/agenda/edit/' . $event->id))->withInput();
        }
        if ($image['replace'])
            $data['image'] = $image['image'];

        // The author of the event stays the person who created it
        $this->eventRepository->updateWithSections($event->id, $data, $sectionIds);

        if ($image['replace'])
            EventRepository::deleteImageFiles($event->image);

        AuditHelper::log('updated', 'Événement', $data['title'], '/admin/agenda/edit/' . $event->id);
        $this->session->setFlashdata('success', 'L\'événement « ' . $data['title'] . ' » a été modifié.');
        return redirect()->to(base_url('/admin/agenda'));
    }

    public function delete($id)
    {
        $event = $this->eventRepository->getWithSections((int) $id);

        if ($event === null) {
            return $this->redirectWithErrors('/admin/agenda', 'L\'événement n\'existe pas ou a déjà été supprimé.');
        }

        $this->eventRepository->delete($event->id, false);
        AuditHelper::log('deleted', 'Événement', $event->title);
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
            'imageUrl' => $event->image_url ?? null,
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
            return $this->redirectWithErrors('/admin/agenda', 'L\'événement n\'existe pas ou a été supprimé.');
        }

        if (new DateTime($event->end_at) < new DateTime()) {
            return $this->redirectWithErrors('/admin/agenda?periode=passes', ['L\'événement « ' . $event->title . ' » est terminé, il ne peut plus être modifié.']);
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
     * Stores the image sent with the form, or removes the current one ("remove_image").
     * The image is re-encoded (EXIF / GPS data removed) in two sizes: full (1600px) and card (800px).
     *
     * @return array ['replace' => false] if the image does not change, ['replace' => true, 'image' => ?string] otherwise,
     *               ['error' => string] if the file can not be used
     */
    private function processImage()
    {
        $file = $this->request->getFile('image');

        if ($file !== null && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            if (!$file->isValid())
                return ['error' => $file->getError() === UPLOAD_ERR_INI_SIZE ? 'L\'image est trop lourde pour le serveur.' : 'L\'image n\'a pas pu être envoyée.'];

            if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], true))
                return ['error' => 'Format d\'image non accepté (JPEG, PNG, WebP ou GIF).'];

            try {
                $source = ImageHelper::open($file->getTempName());
                $name = ImageHelper::randomName();
                ImageHelper::saveJpeg(ImageHelper::fit($source, 1600), EventRepository::getImagePath($name));
                ImageHelper::saveJpeg(ImageHelper::fit($source, 800), EventRepository::getImagePath($name, true));
            } catch (Exception $exception) {
                LogHelper::exception('Image d\'événement non enregistrée', $exception);
                if (isset($name))
                    EventRepository::deleteImageFiles($name);
                return ['error' => $exception->getMessage()];
            }

            return ['replace' => true, 'image' => $name];
        }

        if ($this->request->getPost('remove_image') === '1')
            return ['replace' => true, 'image' => null];

        return ['replace' => false];
    }

    /**
     * Encodes data to be safely printed inside a <script> tag.
     */
}
