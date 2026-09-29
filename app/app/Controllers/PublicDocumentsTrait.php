<?php

namespace App\Controllers;

use App\Helpers\DocumentHelper;
use App\Repositories\FileRepository;

/**
 * Public list and download of the documents of a unit (guide / scout): only the active documents.
 * Used by GuideController and ScoutController.
 */
trait PublicDocumentsTrait
{
    /**
     * @param string $fileType FileRepository::FILE_TYPE_*
     */
    private function documentsPage(string $fileType, string $unitName, string $unitLabel, string $documentUrl)
    {
        return view('pages/unit/documents', [
            'files' => (new FileRepository())->getPublicFor($fileType),
            'unitName' => $unitName,
            'unitLabel' => $unitLabel,
            'documentUrl' => $documentUrl,
        ]);
    }

    /**
     * Downloads an active document of the given type, otherwise back to the list.
     */
    private function downloadDocument(int $id, string $fileType, string $documentUrl)
    {
        $document = (new FileRepository())->getDocument($id);
        if ($document === null || $document->file_type !== $fileType || !$document->is_active)
            return redirect()->to(base_url($documentUrl));

        $path = DocumentHelper::getPath($document);
        if (!is_file($path))
            return redirect()->to(base_url($documentUrl));

        return $this->response->download($path, null)->setFileName($document->name);
    }
}
