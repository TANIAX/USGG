<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Repositories\FileRepository;
use App\Repositories\BaseRepository;
use App\Entities\File;
use App\Helpers\FileHelper;


class GuideController extends BaseController
{
    private FileRepository $fileRepository;

    public function __construct()
    {
        $this->fileRepository = new FileRepository();
    }

    /**
     * Retrieves the index page for the GuideController.
     *
     * @return void
     */
    public function index()
    {
        return view('pages/guide/index');
    }

    /**
     * Retrieves the documents associated with the guide.
     *
     * @return array The array of documents.
     */
    public function documents()
    {
        $files = $this->fileRepository->getAllFor(FileRepository::FILE_TYPE_GUIDE, BaseRepository::RESULT_AS_CUSTOM, File::class) ?? [];
        return view('pages/unit/documents', [
            'files' => $files,
            'unitName' => 'Guides',
            'unitLabel' => 'l\'unité guide',
            'documentUrl' => '/guide/document',
        ]);
    }

    /**
     * Presentation of the staff of the guide unit.
     */
    public function staff()
    {
        // Leaders managed in admin/responsables (the "Unité" section is the unit staff)
        return view('pages/unit/staff', [
            'branch' => 'guide',
            'leaders' => service('repository', 'SectionLeader')->getLeaders(),
        ]);
    }

    /**
     * Downloads a document.
     *
     * @param int $id The ID of the guide.
     */
    public function document(int $id)
    {
        $file = $this->fileRepository->getById($id, BaseRepository::RESULT_AS_CUSTOM, File::class);
        //Only the guide documents are downloaded from this page
        if ($file === null || $file->file_type !== FileRepository::FILE_TYPE_GUIDE) {
            return redirect()->to(base_url('/guide/document'));
        }

        //Depending on the OS, the path may need to be modified
        $path = ROOTPATH . 'public' . DIRECTORY_SEPARATOR . FileHelper::DOCUMENTS_DIRECTORY . $file->path . DIRECTORY_SEPARATOR . $file->name;
        if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
            $path = str_replace('/', '\\', $path);
        }

        if (!file_exists($path)) {
            return redirect()->to(base_url('/guide/document'));
        }

        return $this->response->download($path, null);
    }
}
