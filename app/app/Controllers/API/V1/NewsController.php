<?php

namespace App\Controllers\API\V1;

use CodeIgniter\API\ResponseTrait;
use App\Repositories\EventRepository;

/**
 * News of the home page: they are the upcoming events of the agenda.
 */
class NewsController extends ApiController
{
    use ResponseTrait;

    public const PAGE_SIZE = 3;
    private const MAX_PAGE_SIZE = 12;

    private EventRepository $eventRepository;

    public function __construct()
    {
        parent::__construct();
        $this->eventRepository = service('repository', 'Event');
    }

    /**
     * GET api/v1/actualites?offset=0&limit=3
     * Returns {items: [...], has_more: bool}
     */
    public function index()
    {
        $offset = filter_var($this->request->getGet('offset') ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
        $limit = filter_var($this->request->getGet('limit') ?? self::PAGE_SIZE, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => self::MAX_PAGE_SIZE]]);

        if ($offset === false || $limit === false)
            return $this->error(400, 'Les paramètres offset (≥ 0) et limit (1 à ' . self::MAX_PAGE_SIZE . ') sont invalides.');

        $page = $this->eventRepository->getUpcoming($offset, $limit);

        return $this->success((object) ['items' => $page['events'], 'has_more' => $page['has_more']]);
    }
}
