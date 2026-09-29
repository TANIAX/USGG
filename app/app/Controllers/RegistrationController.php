<?php

namespace App\Controllers;

use App\Helpers\GalleryHelper;
use App\Helpers\SessionHelper;
use App\Helpers\SectionChoices;
use App\Helpers\RegistrationHelper;
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
        return view('pages/admin/registration/index', [
            'requests' => $this->toJson(array_map([$this, 'forList'], $this->registrationRepository->getForAdmin($this->branches()))),
            'statuses' => RegistrationHelper::STATUSES,
            'sections' => SectionChoices::grouped(),
        ]);
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
        $manageable = array_column($this->registrationRepository->getForAdmin($this->branches()), 'id');
        $ids = array_values(array_intersect($manageable, array_map('intval', (array) $this->request->getPost('ids'))));

        if ($action === 'delete') {
            $this->registrationRepository->deleteRequests($ids);
        } else {
            foreach ($ids as $id) {
                $this->registrationRepository->updateRequest($id, ['status' => $status, 'updated_by' => SessionHelper::getUserConnected()->getId()]);
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'ids' => $ids,
            'requests' => array_map([$this, 'forList'], $this->registrationRepository->getForAdmin($this->branches())),
        ]);
    }

    public function delete($id)
    {
        $request = $this->getManageable((int) $id);
        if ($request === null)
            return $this->notFound();

        $this->registrationRepository->deleteRequests([$request->id]);
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
