<?php

namespace App\Controllers;

use App\Helpers\AuditHelper;
use App\Helpers\GalleryHelper;
use App\Helpers\SessionHelper;
use App\Helpers\SectionChoices;
use App\Helpers\RegistrationHelper;
use App\Libraries\ListQuery;
use App\Repositories\RegistrationRepository;

/**
 * Registration requests (administrators of the units): the guide admins see the requests for the guide sections,
 * the scout admins the scout sections, the super admin all. A request without section is seen by everybody.
 */
class RegistrationController extends BaseController
{
    private RegistrationRepository $registrationRepository;

    public function __construct()
    {
        $this->registrationRepository = service('repository', 'Registration');
        helper('form');
    }

    public function index()
    {
        $list = $this->listQuery();
        if ($this->request->getGet('format') === 'csv')
            return $this->exportCsv($list);

        $builder = $this->filteredQuery($list);
        $result = $list->paginate($builder, fn($request) => $this->forList($request), [$this->registrationRepository, 'castRows']);
        $result['counts'] = $this->registrationRepository->countByStatus($this->branches());

        return $this->listResponse('pages/admin/registration/index', [
            'statuses' => RegistrationHelper::STATUSES,
            'sections' => SectionChoices::grouped(),
        ], $result);
    }

    public function show($id)
    {
        $request = $this->getManageable((int) $id);
        if ($request === null)
            return $this->notFound();

        return view('pages/admin/registration/show', [
            'request' => $request,
            'statuses' => RegistrationHelper::STATUSES,
            'sections' => SectionChoices::grouped(),
        ]);
    }

    public function update($id)
    {
        $request = $this->getManageable((int) $id);
        if ($request === null)
            return $this->notFound();

        $post = $this->request->getPost();
        $rules = [
            'status' => 'required|in_list[' . implode(',', array_keys(RegistrationHelper::STATUSES)) . ']',
            'section_id' => 'permit_empty|in_list[' . implode(',', $this->manageableSectionIds()) . ']',
            'note' => 'permit_empty|max_length[5000]',
        ];
        $messages = [
            'status' => ['required' => 'Choisissez le statut.', 'in_list' => 'Le statut choisi n\'existe pas.'],
            'section_id' => ['in_list' => 'Vous ne pouvez pas attribuer cette section.'],
            'note' => ['max_length' => 'La note est trop longue.'],
        ];
        if (!$this->validateData($post, $rules, $messages))
            return $this->redirectWithErrors('/admin/inscriptions/' . $request->id, array_values($this->validator->getErrors()))->withInput();

        $this->registrationRepository->updateRequest($request->id, [
            'status' => $post['status'],
            'section_id' => ($post['section_id'] ?? '') !== '' ? (int) $post['section_id'] : null,
            'note' => trim($post['note'] ?? '') ?: null,
            'updated_by' => SessionHelper::getUserConnected()->getId(),
        ]);

        AuditHelper::log('updated', 'Inscription', $request->firstname . ' ' . $request->name . ' : ' . \App\Helpers\RegistrationHelper::statusLabel($post['status']), '/admin/inscriptions/' . $request->id);
        $this->session->setFlashdata('success', 'La demande de ' . $request->firstname . ' ' . $request->name . ' a été mise à jour.');
        return redirect()->to(base_url('/admin/inscriptions'));
    }

    /**
     * Action on several requests (javascript): "status" (with "status") or "delete".
     */
    public function bulk()
    {
        $action = $this->request->getPost('action');
        $status = $this->request->getPost('status');
        if (!in_array($action, ['status', 'delete'], true) || ($action === 'status' && !isset(RegistrationHelper::STATUSES[$status])))
            return $this->jsonError(400, 'Action inconnue.');

        // Only the requests the user can manage are taken into account, whatever ids are sent
        $ids = $this->registrationRepository->filterManageable($this->branches(), array_map('intval', (array) $this->request->getPost('ids')));

        if ($action === 'delete') {
            $this->registrationRepository->deleteRequests($ids);
        } else {
            foreach ($ids as $id) {
                $this->registrationRepository->updateRequest($id, ['status' => $status, 'updated_by' => SessionHelper::getUserConnected()->getId()]);
            }
        }

        if ($ids)
            AuditHelper::log($action === 'delete' ? 'deleted' : 'updated', 'Inscription', count($ids) . ' demande(s)' . ($action === 'delete' ? '' : ' : ' . RegistrationHelper::statusLabel($status)), '/admin/inscriptions');

        // The page reloads the list itself (listApp)
        return $this->response->setJSON(['success' => true, 'ids' => $ids]);
    }

    public function delete($id)
    {
        $request = $this->getManageable((int) $id);
        if ($request === null)
            return $this->notFound();

        $this->registrationRepository->deleteRequests([$request->id]);
        AuditHelper::log('deleted', 'Inscription', $request->firstname . ' ' . $request->name);
        $this->session->setFlashdata('success', 'La demande de ' . $request->firstname . ' ' . $request->name . ' a été supprimée.');
        return redirect()->to(base_url('/admin/inscriptions'));
    }

    /**
     * Data of a request for the list (age and phase computed)
     */
    private function forList($request)
    {
        return [
            'id' => $request->id,
            'child' => $request->firstname . ' ' . $request->name,
            'totem' => $request->totem,
            'age' => RegistrationHelper::age($request->birthdate),
            'section_id' => $request->section_id,
            'section' => $request->section_name,
            'color' => $request->section_color,
            'phase' => RegistrationHelper::phase($request->relation),
            'parent' => $request->parent_name,
            'email' => $request->parent_email,
            'phone' => $request->parent_phone,
            'city' => $request->city,
            'status' => $request->status,
            'note' => $request->note,
            'created_at' => $request->created_at,
        ];
    }

    /**
     * Filters of the list, read in the url
     */
    private function listQuery(): ListQuery
    {
        return ListQuery::fromRequest($this->request, [
            'status' => array_merge([''], array_keys(RegistrationHelper::STATUSES)),
            'section' => array_merge(['', 'none'], SectionChoices::ids()),
        ]);
    }

    private function filteredQuery(ListQuery $list)
    {
        $builder = $this->registrationRepository->adminQuery($this->branches(), $list->filter('status'), $list->filter('section'));
        $list->search($builder, ['registration.firstname', 'registration.name', 'registration.totem', 'registration.parent_name', 'registration.parent_email', 'registration.city']);
        return $builder;
    }

    /**
     * Spreadsheet of all the requests matching the filters (not only the displayed page)
     */
    private function exportCsv(ListQuery $list)
    {
        $rows = [['Enfant', 'Âge', 'Section', 'Phase', 'Statut', 'Parent', 'E-mail', 'Téléphone', 'Localité', 'Reçue le', 'Note']];
        foreach ($this->registrationRepository->castRows($this->filteredQuery($list)->get()->getResultObject()) as $row) {
            $request = $this->forList($row);
            $rows[] = [
                $request['child'], $request['age'], $request['section'], $request['phase'], RegistrationHelper::statusLabel($request['status']),
                $request['parent'], $request['email'], $request['phone'], $request['city'], $request['created_at'], $request['note'],
            ];
        }
        return $this->csvResponse('demandes-inscription-' . date('Y-m-d') . '.csv', $rows);
    }

    private function getManageable(int $id)
    {
        $request = $this->registrationRepository->get($id);
        if ($request === null || ($request->section_branch !== null && !in_array($request->section_branch, $this->branches(), true)))
            return null;
        return $request;
    }

    private function branches(): array
    {
        return GalleryHelper::getManageableBranches();
    }

    private function manageableSectionIds(): array
    {
        $ids = [];
        foreach (service('repository', 'Section')->getAllOrdered() as $section) {
            if (in_array($section->branch, $this->branches(), true))
                $ids[] = (int) $section->id;
        }
        return $ids;
    }

    private function notFound()
    {
        return $this->redirectWithErrors('/admin/inscriptions', 'Cette demande n\'existe pas ou vous n\'avez pas le droit de la gérer.');
    }
}
