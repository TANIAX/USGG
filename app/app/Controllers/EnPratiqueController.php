<?php

namespace App\Controllers;

use App\Entities\Pricing;
use App\Helpers\FormGuard;
use App\Helpers\MailHelper;
use App\Helpers\SectionChoices;
use App\Helpers\RegistrationHelper;
use App\Controllers\BaseController;
use App\Repositories\BaseRepository;
use App\Repositories\PricingRepository;
use App\Repositories\SectionRepository;


class EnPratiqueController extends BaseController
{
    private PricingRepository $pricingRepository;
    private SectionRepository $sectionRepository;

    public function __construct()
    {
        $this->pricingRepository = service('repository', 'Pricing');
        $this->sectionRepository = service('repository', 'Section');
    }

    public function cotisation()
    {
        $pricing = $this->pricingRepository->getPricing(BaseRepository::RESULT_AS_CUSTOM, Pricing::class);

        return view('pages/en_pratique/cotisation', [
            'pricing' => $pricing,
            // Payment (account, deadline), managed in admin/cotisations
            'payment' => $this->pricingRepository->getPricing(),
        ]);
    }

    public function agenda()
    {
        $sections = $this->sectionRepository->getAllOrdered();

        return view('pages/en_pratique/agenda', [
            'sections' => $this->toJson($sections)
        ]);
    }

    public function inscription()
    {
        helper('form');
        return view('pages/en_pratique/inscription', [
            'sections' => SectionChoices::grouped(),
            'relations' => RegistrationHelper::RELATIONS,
        ]);
    }

    /**
     * Registration request sent by a family: saved for the administrators of the unit, confirmed by e-mail.
     */
    public function submitRegistration()
    {
        $guard = FormGuard::check('inscription');
        if ($guard === 'spam')
            return $this->registrationSent();
        if ($guard !== null)
            return $this->redirectWithErrors('/en-pratique/inscription#demande', $guard)->withInput();

        $post = array_map(fn($value) => is_string($value) ? trim($value) : $value, $this->request->getPost());
        $rules = [
            'firstname' => 'required|max_length[100]',
            'name' => 'required|max_length[100]',
            'totem' => 'permit_empty|max_length[100]',
            'birthdate' => 'required|valid_date[Y-m-d]',
            'section_id' => 'permit_empty|in_list[' . implode(',', SectionChoices::ids()) . ']',
            'relation' => 'required|in_list[' . implode(',', array_keys(RegistrationHelper::RELATIONS)) . ']',
            'street' => 'required|max_length[150]',
            'number' => 'required|max_length[20]',
            'zip_code' => 'required|regex_match[/^[0-9]{4}[0-9]?$/]',
            'city' => 'required|max_length[100]',
            'parent_name' => 'required|max_length[150]',
            'parent_email' => 'required|valid_email|max_length[255]',
            'parent_phone' => 'required|min_length[8]|max_length[30]',
            'remark' => 'permit_empty|max_length[2000]',
            'consent' => 'required',
        ];
        $messages = [
            'firstname' => ['required' => 'Le prénom de l\'enfant est obligatoire.', 'max_length' => 'Le prénom est trop long.'],
            'name' => ['required' => 'Le nom de l\'enfant est obligatoire.', 'max_length' => 'Le nom est trop long.'],
            'totem' => ['max_length' => 'Le totem est trop long.'],
            'birthdate' => ['required' => 'La date de naissance est obligatoire.', 'valid_date' => 'La date de naissance n\'est pas valide.'],
            'section_id' => ['in_list' => 'La section choisie n\'existe pas.'],
            'relation' => ['required' => 'Indiquez si l\'enfant a déjà un lien avec l\'unité.', 'in_list' => 'Indiquez si l\'enfant a déjà un lien avec l\'unité.'],
            'street' => ['required' => 'La rue est obligatoire.', 'max_length' => 'La rue est trop longue.'],
            'number' => ['required' => 'Le numéro est obligatoire.', 'max_length' => 'Le numéro est trop long.'],
            'zip_code' => ['required' => 'Le code postal est obligatoire.', 'regex_match' => 'Le code postal n\'est pas valide.'],
            'city' => ['required' => 'La localité est obligatoire.', 'max_length' => 'La localité est trop longue.'],
            'parent_name' => ['required' => 'Le nom du parent est obligatoire.', 'max_length' => 'Le nom du parent est trop long.'],
            'parent_email' => ['required' => 'L\'adresse e-mail du parent est obligatoire.', 'valid_email' => 'L\'adresse e-mail n\'est pas valide.', 'max_length' => 'L\'adresse e-mail est trop longue.'],
            'parent_phone' => ['required' => 'Le téléphone du parent est obligatoire.', 'min_length' => 'Le numéro de téléphone est trop court.', 'max_length' => 'Le numéro de téléphone est trop long.'],
            'remark' => ['max_length' => 'La remarque est trop longue (2000 caractères maximum).'],
            'consent' => ['required' => 'Merci d\'accepter l\'utilisation des données pour traiter la demande.'],
        ];

        $errors = [];
        if (!$this->validateData($post, $rules, $messages))
            $errors = array_values($this->validator->getErrors());
        else {
            $age = RegistrationHelper::age($post['birthdate']);
            if ($age < 4 || $age > 18)
                $errors[] = 'Les sections accueillent les enfants de 5 à 18 ans : vérifiez la date de naissance.';
        }
        if ($errors)
            return $this->redirectWithErrors('/en-pratique/inscription#demande', $errors)->withInput();

        $data = array_intersect_key($post, array_flip(['firstname', 'name', 'totem', 'birthdate', 'section_id', 'relation', 'street', 'number', 'zip_code', 'city', 'parent_name', 'parent_email', 'parent_phone', 'remark']));
        $data['section_id'] = $data['section_id'] !== '' ? (int) $data['section_id'] : null;
        $data['totem'] = $data['totem'] ?: null;
        $data['remark'] = $data['remark'] ?: null;
        $data['parent_email'] = strtolower($data['parent_email']);

        $repository = service('repository', 'Registration');
        $request = $repository->get($repository->create($data));

        MailHelper::send($request->parent_email, 'Demande d\'inscription reçue', 'emails/registration_received', ['request' => $request]);
        $this->notifyAdministrators($request);

        return $this->registrationSent();
    }

    private function registrationSent()
    {
        $this->session->setFlashdata('success', 'Votre demande a bien été envoyée. Un e-mail de confirmation vous a été envoyé ; nous vous recontacterons prochainement.');
        return redirect()->to(base_url('/en-pratique/inscription#demande'));
    }

    /**
     * E-mail to the administrators of the unit of the section (both units when no section was chosen).
     */
    private function notifyAdministrators($request): void
    {
        $roles = ['GUIDE' => ['guide_admin'], 'SCOUTE' => ['scout_admin']][$request->section_branch] ?? ['guide_admin', 'scout_admin'];
        $recipients = service('repository', 'User')->emailsWithRoles($roles) ?: [config('Site')->contactEmail];
        foreach ($recipients as $email) {
            MailHelper::send($email, 'Nouvelle demande d\'inscription : ' . $request->firstname . ' ' . $request->name, 'emails/registration_new', [
                'request' => $request,
                'link' => base_url('admin/inscriptions/' . $request->id),
            ]);
        }
    }
}
