<?php

namespace App\Controllers;

use App\Helpers\AuditHelper;
use App\Helpers\GalleryHelper;
use App\Helpers\DocumentHelper;
use App\Controllers\BaseController;
use App\Repositories\FileRepository;

/**
 * Administration of the documents (downloads of the guide / scout pages).
 * Same rights as the gallery: guide admins manage the guide documents, scout admins the scout documents,
 * the super admin both. An inactive document is hidden from the public.
 */
class DocumentController extends BaseController
{
    private FileRepository $fileRepository;

    public function __construct()
    {
        $this->fileRepository = new FileRepository();
        helper('form');
    }

    public function index()
    {
        return view('pages/admin/document/index', [
            'files' => $this->toJson($this->fileRepository->getForAdmin($this->manageableTypes())),
            'types' => $this->typeChoices(),
        ]);
    }

    public function create()
    {
        return $this->form();
    }

    public function edit($id)
    {
        $document = $this->getManageableDocument((int) $id);
        if ($document === null)
            return $this->denied();

        return $this->form($document);
    }

    public function store()
    {
        $data = $this->validateDocument(true);
        if ($data === null)
            return redirect()->to(base_url('/admin/document/create'))->withInput();

        $file = $this->request->getFile('file');
        $extension = strtolower($file->getClientExtension());
        // Read before moving the file (the temporary file does not exist anymore after)
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        $storedName = DocumentHelper::newStoredName($extension);
        if (!$this->storeUploadedFile($file, $storedName))
            return redirect()->to(base_url('/admin/document/create'))->withInput();

        $this->fileRepository->create([
            'name' => $data['name'] . '.' . $extension,
            'path' => strtolower($data['file_type']),
            'file_type' => $data['file_type'],
            'is_active' => $data['is_active'],
            'stored_name' => $storedName,
            'mime_type' => $mimeType,
            'size' => $size,
        ]);

        AuditHelper::log('created', 'Document', $data['name'] . ($data['is_active'] ? '' : ' (inactif)'), '/admin/document');
        $this->session->setFlashdata('success', 'Le document « ' . $data['name'] . ' » a été ajouté' . ($data['is_active'] ? '.' : ' (inactif : il n\'est pas visible par le public).'));
        return redirect()->to(base_url('/admin/document'));
    }

    public function update($id)
    {
        $document = $this->getManageableDocument((int) $id);
        if ($document === null)
            return $this->denied();

        $data = $this->validateDocument(false, $document->id);
        if ($data === null)
            return redirect()->to(base_url('/admin/document/edit/' . $document->id))->withInput();

        $update = ['file_type' => $data['file_type'], 'is_active' => $data['is_active']];
        $extension = $document->extension;

        // New file (optional)
        $file = $this->request->getFile('file');
        if ($file !== null && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            $extension = strtolower($file->getClientExtension());
            $mimeType = $file->getMimeType();
            $size = $file->getSize();
            $storedName = DocumentHelper::newStoredName($extension);
            if (!$this->storeUploadedFile($file, $storedName))
                return redirect()->to(base_url('/admin/document/edit/' . $document->id))->withInput();

            DocumentHelper::deleteFile($document);
            $update += ['stored_name' => $storedName, 'mime_type' => $mimeType, 'size' => $size];
        } else if (!$data['is_active'] && empty($document->stored_name)) {
            // An old document stored in the public folder leaves it when it is deactivated
            $update['stored_name'] = $this->moveToPrivate($document);
            if ($update['stored_name'] === null)
                return redirect()->to(base_url('/admin/document/edit/' . $document->id))->withInput();
        }

        $update['name'] = $data['name'] . ($extension ? '.' . $extension : '');
        $this->fileRepository->updateDocument($document->id, $update);

        AuditHelper::log('updated', 'Document', $data['name'] . ($data['is_active'] ? '' : ' (inactif)'), '/admin/document/edit/' . $document->id);
        $this->session->setFlashdata('success', 'Le document « ' . $data['name'] . ' » a été modifié.');
        return redirect()->to(base_url('/admin/document'));
    }

    /**
     * Downloads a document, active or not (to check it from the administration).
     */
    public function download($id)
    {
        $document = $this->getManageableDocument((int) $id);
        if ($document === null || !is_file(DocumentHelper::getPath($document)))
            return $this->denied('Le fichier de ce document est introuvable.');

        return $this->response->download(DocumentHelper::getPath($document), null)->setFileName($document->name);
    }

    /**
     * Action on one or several documents (called in javascript): "activate", "deactivate" or "delete".
     */
    public function bulk()
    {
        $action = $this->request->getPost('action');
        if (!in_array($action, ['activate', 'deactivate', 'delete'], true))
            return $this->jsonError(400, 'Action inconnue.');

        // Only the documents the user can manage are taken into account, whatever ids are sent
        $ids = array_map('intval', (array) $this->request->getPost('ids'));
        $documents = array_filter($this->fileRepository->getDocuments($ids), fn($document) => in_array($document->file_type, $this->manageableTypes(), true));

        $done = [];
        $errors = [];
        foreach ($documents as $document) {
            if ($action === 'delete') {
                DocumentHelper::deleteFile($document);
                $this->fileRepository->deleteDocument($document->id);
            } else if ($action === 'activate') {
                $this->fileRepository->updateDocument($document->id, ['is_active' => true]);
            } else {
                $update = ['is_active' => false];
                if (empty($document->stored_name)) {
                    $update['stored_name'] = DocumentHelper::moveToPrivateStorage($document);
                    if ($update['stored_name'] === null) {
                        $errors[] = 'Le fichier de « ' . $document->name . ' » est introuvable : le document n\'a pas été désactivé.';
                        continue;
                    }
                }
                $this->fileRepository->updateDocument($document->id, $update);
            }
            $done[] = $document->id;
            AuditHelper::log($action === 'delete' ? 'deleted' : 'updated', 'Document', $document->name . ['delete' => '', 'activate' => ' (activé)', 'deactivate' => ' (désactivé)'][$action]);
        }

        return $this->response->setJSON([
            'success' => true,
            'ids' => $done,
            'errors' => $errors,
            'files' => $this->fileRepository->getForAdmin($this->manageableTypes()),
        ]);
    }

    private function form($document = null)
    {
        return view('pages/admin/document/form', [
            'document' => $document,
            'types' => $this->typeChoices(),
            'extensions' => DocumentHelper::EXTENSIONS,
            'maxSizeMb' => DocumentHelper::MAX_SIZE_KB / 1024,
        ]);
    }

    /**
     * @param int $currentId Document being edited (excluded from the check of unique names)
     * @return array|null ['name' => string (without extension), 'file_type' => string, 'is_active' => bool], null if invalid
     */
    private function validateDocument(bool $fileRequired, int $currentId = 0)
    {
        $types = $this->manageableTypes();
        $post = $this->request->getPost();
        // Only the super admin chooses the unit
        if (count($types) === 1)
            $post['file_type'] = $types[0];
        $post['name'] = DocumentHelper::cleanName((string) ($post['name'] ?? ''));

        $rules = [
            'name' => ['rules' => 'required|min_length[2]|max_length[200]'],
            'file_type' => ['rules' => 'required|in_list[' . implode(',', $types) . ']'],
        ];
        $messages = [
            'name' => ['required' => 'Le nom du document est obligatoire.', 'min_length' => 'Le nom du document est trop court.', 'max_length' => 'Le nom du document est trop long.'],
            'file_type' => ['required' => 'Choisissez l\'unité du document.', 'in_list' => 'Vous ne pouvez pas publier de document pour cette unité.'],
        ];

        $file = $this->request->getFile('file');
        $hasFile = $file !== null && $file->getError() !== UPLOAD_ERR_NO_FILE;
        $errors = [];
        if (!$this->validateData($post, $rules, $messages))
            $errors = array_values($this->validator->getErrors());

        if ($fileRequired && !$hasFile) {
            $errors[] = 'Choisissez le fichier à publier.';
        } else if ($hasFile) {
            if (!$file->isValid())
                $errors[] = $file->getError() === UPLOAD_ERR_INI_SIZE ? 'Le fichier est trop lourd pour le serveur.' : 'Le fichier n\'a pas pu être envoyé.';
            else if (!in_array(strtolower($file->getClientExtension()), DocumentHelper::EXTENSIONS, true))
                $errors[] = 'Format non accepté (' . implode(', ', DocumentHelper::EXTENSIONS) . ').';
            else if ($file->getSize() > DocumentHelper::MAX_SIZE_KB * 1024)
                $errors[] = 'Le fichier dépasse ' . (DocumentHelper::MAX_SIZE_KB / 1024) . ' Mo.';
        }

        // The name must be unique for the unit (it is the name of the downloaded file)
        if (!$errors) {
            foreach ($this->fileRepository->getForAdmin([$post['file_type']]) as $other) {
                if (strcasecmp(pathinfo($other->name, PATHINFO_FILENAME), $post['name']) === 0 && $other->id !== $currentId)
                    $errors[] = 'Un document porte déjà ce nom pour cette unité.';
            }
        }

        if ($errors) {
            $this->session->setFlashdata('errors', array_unique($errors));
            return null;
        }

        return [
            'name' => $post['name'],
            'file_type' => $post['file_type'],
            'is_active' => ($post['is_active'] ?? '') === '1',
        ];
    }

    private function storeUploadedFile($file, string $storedName)
    {
        if (!is_dir(DocumentHelper::privateDirectory()))
            mkdir(DocumentHelper::privateDirectory(), 0775, true);

        if ($file->move(DocumentHelper::privateDirectory(), $storedName))
            return true;

        $this->session->setFlashdata('errors', ['Le fichier n\'a pas pu être enregistré sur le serveur.']);
        return false;
    }

    private function moveToPrivate($document)
    {
        $storedName = DocumentHelper::moveToPrivateStorage($document);
        if ($storedName === null)
            $this->session->setFlashdata('errors', ['Le fichier de ce document est introuvable : il n\'a pas pu être désactivé.']);
        return $storedName;
    }

    private function getManageableDocument(int $id)
    {
        $document = $this->fileRepository->getDocument($id);
        return $document && in_array($document->file_type, $this->manageableTypes(), true) ? $document : null;
    }

    /**
     * Document types the user can manage (same values as the gallery branches: GUIDE / SCOUTE).
     */
    private function manageableTypes()
    {
        return GalleryHelper::getManageableBranches();
    }

    private function typeChoices()
    {
        return array_intersect_key(GalleryHelper::BRANCHES, array_flip($this->manageableTypes()));
    }

    private function denied(string $message = 'Ce document n\'existe pas ou vous n\'avez pas le droit de le gérer.')
    {
        return $this->redirectWithErrors('/admin/document', $message);
    }
}
