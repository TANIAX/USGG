<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Repositories\FileRepository;


class GuideController extends BaseController
{
    use PublicDocumentsTrait;

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
        return $this->documentsPage(FileRepository::FILE_TYPE_GUIDE, 'Guides', 'l\'unité guide', '/guide/document');
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
        return $this->downloadDocument($id, FileRepository::FILE_TYPE_GUIDE, '/guide/document');
    }
}
