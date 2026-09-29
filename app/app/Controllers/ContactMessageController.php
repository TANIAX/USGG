<?php

namespace App\Controllers;

use App\Helpers\AuditHelper;
use App\Repositories\ContactMessageRepository;

/**
 * Messages of the contact form (super admin, ASBL admin). They are also sent by e-mail to the address of the unit.
 */
class ContactMessageController extends BaseController
{
    private ContactMessageRepository $messageRepository;

    public function __construct()
    {
        $this->messageRepository = service('repository', 'ContactMessage');
    }

    public function index()
    {
        return view('pages/admin/message/index', [
            'messages' => $this->toJson($this->messageRepository->getAllRecent()),
            'contactEmail' => config('Site')->contactEmail,
        ]);
    }

    /**
     * Action on messages (javascript): read, unread, handled, unhandled, delete.
     */
    public function bulk()
    {
        $actions = [
            'read' => ['is_read' => true],
            'unread' => ['is_read' => false, 'is_handled' => false],
            'handled' => ['is_read' => true, 'is_handled' => true],
            'unhandled' => ['is_handled' => false],
        ];
        $action = $this->request->getPost('action');
        if (!isset($actions[$action]) && $action !== 'delete')
            return $this->jsonError(400, 'Action inconnue.');

        $ids = array_map('intval', (array) $this->request->getPost('ids'));
        if ($action === 'delete') {
            $this->messageRepository->deleteMessages($ids);
            AuditHelper::log('deleted', 'Message', count($ids) . ' message(s) de contact');
        }
        else {
            $this->messageRepository->updateMessages($ids, $actions[$action]);
        }

        return $this->response->setJSON(['success' => true, 'messages' => $this->messageRepository->getAllRecent()]);
    }
}
