<?php

namespace App\Controllers;

use App\Helpers\AuditHelper;
use App\Helpers\SessionHelper;
use App\Libraries\PageText;
use App\Repositories\PageRepository;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Pages written by the super admins: charter of the unit (/charte) and privacy policy (/confidentialite).
 * The text is a simple Markdown (App\Libraries\PageText), edited in admin/pages.
 */
class PageController extends BaseController
{
    /**
     * Public address of each page
     */
    public const URLS = ['charte' => '/charte', 'confidentialite' => '/confidentialite'];

    private const CONTENT_MAX_LENGTH = 60000;

    private PageRepository $pageRepository;

    public function __construct()
    {
        $this->pageRepository = service('repository', 'Page');
        helper('form');
    }

    public function show(string $slug)
    {
        $page = $this->pageRepository->findBySlug($slug);
        if ($page === null)
            throw PageNotFoundException::forPageNotFound();

        return view('pages/page/show', [
            'page' => $page,
            'html' => PageText::toHtml($page->content),
            'headings' => PageText::headings($page->content),
        ]);
    }

    // ---- Administration (super admin)

    public function index()
    {
        return view('pages/admin/page/index', ['pages' => $this->pageRepository->getAllForAdmin(), 'urls' => self::URLS]);
    }

    public function edit(string $slug)
    {
        $page = $this->pageRepository->findBySlug($slug);
        if ($page === null)
            return $this->redirectWithErrors('/admin/pages', 'Cette page n\'existe pas.');

        return view('pages/admin/page/form', ['page' => $page, 'url' => self::URLS[$slug] ?? null]);
    }

    public function update(string $slug)
    {
        $page = $this->pageRepository->findBySlug($slug);
        if ($page === null)
            return $this->redirectWithErrors('/admin/pages', 'Cette page n\'existe pas.');

        $title = trim((string) $this->request->getPost('title'));
        $content = str_replace("\r\n", "\n", trim((string) $this->request->getPost('content')));
        $errors = [];
        if ($title === '' || mb_strlen($title) > 150)
            $errors[] = 'Le titre est obligatoire (150 caractères maximum).';
        if ($content === '')
            $errors[] = 'Le texte de la page est obligatoire.';
        elseif (mb_strlen($content) > self::CONTENT_MAX_LENGTH)
            $errors[] = 'Le texte est trop long.';
        if ($errors)
            return $this->redirectWithErrors('/admin/pages/' . $slug, $errors)->withInput();

        $this->pageRepository->updatePage($slug, ['title' => $title, 'content' => $content, 'updated_by' => SessionHelper::getUserConnected()->getId()]);
        AuditHelper::log('updated', 'Page', $title, '/admin/pages/' . $slug);
        $this->session->setFlashdata('success', 'La page « ' . $title . ' » a été enregistrée.');
        return redirect()->to(base_url('/admin/pages'));
    }

    /**
     * Rendering of a text being written (preview of the form, javascript)
     */
    public function preview()
    {
        $content = mb_substr((string) $this->request->getPost('content'), 0, self::CONTENT_MAX_LENGTH);
        return $this->response->setJSON(['success' => true, 'html' => PageText::toHtml($content)]);
    }
}
