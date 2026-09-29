<?php

namespace App\Controllers\API\V1;

use CodeIgniter\API\ResponseTrait;
use App\Repositories\SectionLeaderRepository;

/**
 * Section leaders shown on the home page, loaded by pages ("Voir plus de responsables").
 */
class LeadersController extends ApiController
{
    use ResponseTrait;

    public const PAGE_SIZE = 12;
    private const MAX_PAGE_SIZE = 48;

    private SectionLeaderRepository $leaderRepository;

    public function __construct()
    {
        parent::__construct();
        $this->leaderRepository = service('repository', 'SectionLeader');
    }

    /**
     * GET api/v1/responsables?offset=0&limit=12
     * Returns {items: [...], has_more: bool} (no e-mail or phone: only what the site shows)
     */
    public function index()
    {
        $offset = filter_var($this->request->getGet('offset') ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
        $limit = filter_var($this->request->getGet('limit') ?? self::PAGE_SIZE, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => self::MAX_PAGE_SIZE]]);

        if ($offset === false || $limit === false)
            return $this->error(400, 'Les paramètres offset (≥ 0) et limit (1 à ' . self::MAX_PAGE_SIZE . ') sont invalides.');

        return $this->success((object) $this->leaderRepository->getPublicPage($offset, $limit));
    }
}
