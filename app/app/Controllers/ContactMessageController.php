<?php

namespace App\Controllers;

use App\Helpers\AuditHelper;
use App\Libraries\ListQuery;
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
        $list = ListQuery::fromRequest($this->request, ['filter' => ['todo', 'unread', 'handled', '']]);
        $builder = $this->messageRepository->adminQuery($list->filter('filter'));
        $list->search($builder, ['name', 'email', 'message']);
        $result = $list->paginate($builder, [ContactMessageRepository::class, 'cast']);
        $result['counts'] = $this->messageRepository->countByFilter();

        return $this->listResponse('pages/admin/message/index', ['contactEmail' => config('Site')->contactEmail], $result);
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

        // New state of the messages (the page updates them without reloading the list, e.g. a message opened in "Non lus")
        return $this->response->setJSON(['success' => true, 'ids' => $ids, 'changes' => $actions[$action] ?? null, 'counts' => $this->messageRepository->countByFilter()]);
    }
}
