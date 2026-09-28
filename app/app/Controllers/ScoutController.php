<?php

namespace App\Controllers;

use App\Entities\File;
use App\Helpers\FileHelper;
use App\Controllers\BaseController;
use App\Repositories\BaseRepository;
use App\Repositories\FileRepository;

/**
 * Pages of the scout unit: presentation of the sections, staff and documents.
 */
class ScoutController extends BaseController
{
    private FileRepository $fileRepository;

    public function __construct()
    {
        $this->fileRepository = new FileRepository();
    }

    public function index()
    {
        return view('pages/scout/index');
    }

    public function staff()
    {
        // Leaders managed in admin/responsables (the "Unité" section is the unit staff)
        return view('pages/unit/staff', [
            'branch' => 'scout',
            'leaders' => service('repository', 'SectionLeader')->getLeaders(),
        ]);
    }

    public function documents()
    {
        $files = $this->fileRepository->getAllFor(FileRepository::FILE_TYPE_SCOUTE, BaseRepository::RESULT_AS_CUSTOM, File::class) ?? [];

        return view('pages/unit/documents', [
            'files' => $files,
            'unitName' => 'Scouts',
            'unitLabel' => 'l\'unité scoute',
            'documentUrl' => '/scout/document',
        ]);
    }

    /**
     * Downloads a scout document.
     */
    public function document(int $id)
    {
        $file = $this->fileRepository->getById($id, BaseRepository::RESULT_AS_CUSTOM, File::class);
        if ($file === null || $file->file_type !== FileRepository::FILE_TYPE_SCOUTE)
            return redirect()->to(base_url('/scout/document'));

        $path = ROOTPATH . 'public' . DIRECTORY_SEPARATOR . FileHelper::DOCUMENTS_DIRECTORY . $file->path . DIRECTORY_SEPARATOR . $file->name;
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            $path = str_replace('/', '\\', $path);

        if (!file_exists($path))
            return redirect()->to(base_url('/scout/document'));

        return $this->response->download($path, null);
    }
}
