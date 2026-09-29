<?php

namespace App\Controllers;

use Exception;
use App\Helpers\SessionHelper;
use App\Helpers\GalleryHelper;
use App\Controllers\BaseController;
use App\Repositories\AlbumRepository;
use App\Repositories\PhotoRepository;

/**
 * Administration of the photo gallery.
 * Guide admins manage the guide albums, scout admins the scout albums, the super admin chooses.
 */
class AlbumController extends BaseController
{
    private const MAX_UPLOAD_KB = 20480;

    private AlbumRepository $albumRepository;
    private PhotoRepository $photoRepository;

    public function __construct()
    {
        $this->albumRepository = service('repository', 'Album');
        $this->photoRepository = service('repository', 'Photo');
    }

    public function index()
    {
        $albums = $this->albumRepository->getAlbums(GalleryHelper::getManageableBranches(), true, false);

        return view('pages/admin/gallery/index', [
            'albums' => $this->toJson($albums),
            'branches' => $this->getBranchChoices(),
        ]);
    }

    public function create()
    {
        return view('pages/admin/gallery/create', [
            'branches' => $this->getBranchChoices(),
        ]);
    }

    public function store()
    {
        $data = $this->validateAlbum();
        if ($data === null)
            return redirect()->to(base_url('/admin/galerie/create'))->withInput();

        $data['user_id'] = SessionHelper::getUserConnected()->getId();
        $id = $this->albumRepository->create($data);

        $this->session->setFlashdata('success', 'L\'album « ' . $data['title'] . ' » a été créé, vous pouvez maintenant y ajouter des photos.');
        return redirect()->to(base_url('/admin/galerie/album/' . $id));
    }

    /**
     * Album page: information, upload and management of the photos.
     */
    public function album($id)
    {
        $album = $this->getManageableAlbum((int) $id);
        if ($album === null)
            return $this->denied();

        return view('pages/admin/gallery/album', [
            'album' => $album,
            'photos' => json_encode($this->photoRepository->getForAlbum($album->id, true)),
            'branches' => $this->getBranchChoices(),
            'maxUploadKb' => self::MAX_UPLOAD_KB,
        ]);
    }

    public function update($id)
    {
        $album = $this->getManageableAlbum((int) $id);
        if ($album === null)
            return $this->denied();

        $data = $this->validateAlbum();
        if ($data !== null) {
            $this->albumRepository->updateAlbum($album->id, $data);
            $this->session->setFlashdata('success', 'L\'album a été modifié.');
        }

        return redirect()->to(base_url('/admin/galerie/album/' . $album->id))->withInput();
    }

    public function delete($id)
    {
        $album = $this->getManageableAlbum((int) $id);
        if ($album === null)
            return $this->denied();

        //The files are deleted: a deleted photo must not stay reachable
        $this->albumRepository->deleteAlbum($album->id);
        GalleryHelper::deleteAlbumDirectory($album->id);

        $this->session->setFlashdata('success', 'L\'album « ' . $album->title . ' » et ses photos ont été supprimés.');
        return redirect()->to(base_url('/admin/galerie'));
    }

    /**
     * Applies an action to several photos of an album (called in javascript):
     * "public" / "private" (visibility) or "delete".
     */
    public function photos($id)
    {
        $album = $this->getManageableAlbum((int) $id);
        if ($album === null)
            return $this->jsonError(403, 'Vous ne pouvez pas modifier cet album.');

        $action = $this->request->getPost('action');
        if (!in_array($action, ['public', 'private', 'delete'], true))
            return $this->jsonError(400, 'Action inconnue.');

        //Only the photos of this album are kept, whatever ids are sent
        $ids = array_map('intval', (array) $this->request->getPost('ids'));
        $photos = $this->photoRepository->getInAlbum($album->id, $ids);
        $photoIds = array_map(fn($photo) => (int) $photo->id, $photos);

        if ($action === 'delete') {
            $this->photoRepository->deleteIds($photoIds);
            foreach ($photos as $photo) {
                GalleryHelper::deletePhotoFiles($album->id, $photo->filename);
            }
        } else {
            $this->photoRepository->setVisibilityForIds($photoIds, $action === 'public');
        }

        return $this->response->setJSON(['success' => true, 'ids' => $photoIds]);
    }

    /**
     * Adds one photo to an album (called in javascript, one request per photo).
     */
    public function upload($id)
    {
        $album = $this->getManageableAlbum((int) $id);
        if ($album === null)
            return $this->jsonError(403, 'Vous ne pouvez pas ajouter de photos à cet album.');

        $userId = SessionHelper::getUserConnected()->getId();
        //Releases the session lock: otherwise the photos sent in parallel are processed one after the other
        session_write_close();

        $file = $this->request->getFile('photo');
        if ($file === null || !$file->isValid())
            return $this->jsonError(400, $file && $file->getError() === UPLOAD_ERR_INI_SIZE
                ? 'La photo est trop lourde pour le serveur.'
                : 'La photo n\'a pas pu être envoyée.');

        $rules = ['photo' => [
            'is_image[photo]',
            'mime_in[photo,image/jpeg,image/png,image/webp,image/gif]',
            'max_size[photo,' . self::MAX_UPLOAD_KB . ']',
        ]];
        $messages = ['photo' => [
            'is_image' => 'Le fichier n\'est pas une image.',
            'mime_in' => 'Format non accepté (JPEG, PNG, WebP ou GIF).',
            'max_size' => 'La photo dépasse ' . (self::MAX_UPLOAD_KB / 1024) . ' Mo.',
        ]];

        if (!$this->validate($rules, $messages))
            return $this->jsonError(400, implode(' ', $this->validator->getErrors()));

        try {
            $stored = GalleryHelper::storePhoto($file->getTempName(), $album->id);
        } catch (Exception $exception) {
            return $this->jsonError(400, $exception->getMessage());
        }

        $isPublic = $this->request->getPost('is_public') !== '0';
        $photoId = $this->photoRepository->add($stored + [
            'album_id' => $album->id,
            'is_public' => $isPublic,
            'user_id' => $userId,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'photo' => ['id' => $photoId, 'width' => $stored['width'], 'height' => $stored['height'], 'is_public' => $isPublic],
        ]);
    }

    /**
     * Changes the visibility of one photo (called in javascript).
     */
    public function photoVisibility($id)
    {
        $photo = $this->getManageablePhoto((int) $id);
        if ($photo === null)
            return $this->jsonError(403, 'Vous ne pouvez pas modifier cette photo.');

        $isPublic = $this->request->getPost('is_public') === '1';
        $this->photoRepository->setVisibility($photo->id, $isPublic);

        return $this->response->setJSON(['success' => true, 'is_public' => $isPublic]);
    }

    /**
     * Deletes one photo and its files (called in javascript).
     */
    public function deletePhoto($id)
    {
        $photo = $this->getManageablePhoto((int) $id);
        if ($photo === null)
            return $this->jsonError(403, 'Vous ne pouvez pas supprimer cette photo.');

        $this->photoRepository->deletePhoto($photo->id);
        GalleryHelper::deletePhotoFiles($photo->album_id, $photo->filename);

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Validates the submitted album. The branch must be one the user can manage.
     *
     * @return array|null The album data, or null if invalid (errors stored in the flashdata)
     */
    private function validateAlbum()
    {
        $branches = GalleryHelper::getManageableBranches();
        $post = $this->request->getPost();

        //Only the super admin chooses the branch
        if (count($branches) === 1)
            $post['branch'] = $branches[0];

        $rules = [
            'title' => 'required|max_length[255]',
            'branch' => 'required|in_list[' . implode(',', $branches) . ']',
            'album_date' => 'permit_empty|valid_date[Y-m-d]',
            'description' => 'permit_empty|max_length[5000]',
        ];
        $messages = [
            'title' => ['required' => 'Le titre est obligatoire.', 'max_length' => 'Le titre ne peut pas dépasser 255 caractères.'],
            'branch' => ['required' => 'Choisissez si l\'album est pour les guides ou les scouts.', 'in_list' => 'Vous ne pouvez pas publier d\'album pour cette unité.'],
            'album_date' => ['valid_date' => 'La date est invalide.'],
            'description' => ['max_length' => 'La description ne peut pas dépasser 5000 caractères.'],
        ];

        if (!$this->validateData($post, $rules, $messages)) {
            $this->session->setFlashdata('errors', $this->validator->getErrors());
            return null;
        }

        return [
            'title' => trim($post['title']),
            'branch' => $post['branch'],
            'album_date' => ($post['album_date'] ?? '') ?: null,
            'description' => trim($post['description'] ?? '') ?: null,
        ];
    }

    /**
     * Returns the album if it exists and belongs to a branch the user can manage.
     */
    private function getManageableAlbum(int $id)
    {
        $album = $this->albumRepository->getAlbum($id);
        return $album && GalleryHelper::canManageBranch($album->branch) ? $album : null;
    }

    private function getManageablePhoto(int $id)
    {
        $photo = $this->photoRepository->getPhoto($id);
        return $photo && GalleryHelper::canManageBranch($photo->branch) ? $photo : null;
    }

    /**
     * Branches the user can choose from ([code => label]).
     */
    private function getBranchChoices()
    {
        return array_intersect_key(GalleryHelper::BRANCHES, array_flip(GalleryHelper::getManageableBranches()));
    }

    private function denied()
    {
        return $this->redirectWithErrors('/admin/galerie', 'Cet album n\'existe pas ou vous n\'avez pas le droit de le gérer.');
    }
}
