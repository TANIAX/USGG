<?php

namespace App\Controllers;

use App\Helpers\AuditHelper;
use App\Repositories\UserTypeRepository;

/**
 * Functions of the staff (chef d'unité, animateur...), used by the accounts and the section leaders (super admin).
 */
class UserTypeController extends BaseController
{
    private UserTypeRepository $typeRepository;

    public function __construct()
    {
        $this->typeRepository = service('repository', 'UserType');
    }

    public function index()
    {
        return view('pages/admin/user_type/index', ['types' => $this->typeRepository->getAllWithUsage()]);
    }

    /**
     * New function, or new name of a function
     */
    public function save($id = null)
    {
        $type = $id !== null ? $this->find((int) $id) : null;
        if ($id !== null && $type === null)
            return $this->notFound();

        $name = trim(preg_replace('/\s+/', ' ', (string) $this->request->getPost('name')));
        if ($name === '' || mb_strlen($name) > 100)
            return $this->redirectWithErrors('/admin/fonctions', 'Le nom de la fonction est obligatoire (100 caractères maximum).');
        if ($this->typeRepository->findByName($name, $type->id ?? null))
            return $this->redirectWithErrors('/admin/fonctions', 'La fonction « ' . $name . ' » existe déjà : utilisez « Fusionner » pour regrouper deux fonctions.');

        if ($type) {
            $this->typeRepository->rename($type->id, $name);
            AuditHelper::log('updated', 'Fonction', $type->name . ' → ' . $name, '/admin/fonctions');
            $this->session->setFlashdata('success', 'La fonction « ' . $type->name . ' » s\'appelle maintenant « ' . $name . ' ».');
        } else {
            $this->typeRepository->create($name);
            AuditHelper::log('created', 'Fonction', $name, '/admin/fonctions');
            $this->session->setFlashdata('success', 'La fonction « ' . $name . ' » a été ajoutée.');
        }
        return redirect()->to(base_url('/admin/fonctions'));
    }

    /**
     * The accounts and leaders of the function move to another function, then it is deleted (duplicates).
     */
    public function merge($id)
    {
        $type = $this->find((int) $id);
        $target = $this->find((int) $this->request->getPost('target'));
        if ($type === null || $target === null || $type->id === $target->id)
            return $this->redirectWithErrors('/admin/fonctions', 'Choisissez une autre fonction dans laquelle fusionner.');

        $this->typeRepository->merge($type->id, $target->id);
        AuditHelper::log('deleted', 'Fonction', $type->name . ' (fusionnée dans ' . $target->name . ')', '/admin/fonctions');
        $this->session->setFlashdata('success', 'La fonction « ' . $type->name . ' » a été fusionnée dans « ' . $target->name . ' » (' . ($type->users + $type->leaders) . ' fiche(s) déplacée(s)).');
        return redirect()->to(base_url('/admin/fonctions'));
    }

    public function delete($id)
    {
        $type = $this->find((int) $id);
        if ($type === null)
            return $this->notFound();
        if ($type->users + $type->leaders > 0)
            return $this->redirectWithErrors('/admin/fonctions', 'La fonction « ' . $type->name . ' » est utilisée : fusionnez-la dans une autre fonction plutôt que de la supprimer.');

        $this->typeRepository->remove($type->id);
        AuditHelper::log('deleted', 'Fonction', $type->name);
        $this->session->setFlashdata('success', 'La fonction « ' . $type->name . ' » a été supprimée.');
        return redirect()->to(base_url('/admin/fonctions'));
    }

    private function find(int $id)
    {
        foreach ($this->typeRepository->getAllWithUsage() as $type) {
            if ($type->id === $id)
                return $type;
        }
        return null;
    }

    private function notFound()
    {
        return $this->redirectWithErrors('/admin/fonctions', 'Cette fonction n\'existe pas.');
    }
}
