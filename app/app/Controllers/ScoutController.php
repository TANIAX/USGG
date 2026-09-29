<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Repositories\FileRepository;

/**
 * Pages of the scout unit: presentation of the sections, staff and documents.
 */
class ScoutController extends BaseController
{
    use PublicDocumentsTrait;

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
        return $this->documentsPage(FileRepository::FILE_TYPE_SCOUTE, 'Scouts', 'l\'unité scoute', '/scout/document');
    }

    /**
     * Downloads a scout document.
     */
    public function document(int $id)
    {
        return $this->downloadDocument($id, FileRepository::FILE_TYPE_SCOUTE, '/scout/document');
    }
}
