<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\HTTP\IncomingRequest;

/**
 * Page of a list of the administration, read in the url: ?page=2&per_page=50&search=...&status=...
 * The filtering, the search and the pagination are done by the database: only one page is sent to the browser.
 *
 * $list = ListQuery::fromRequest($this->request, ['status' => ['', 'new', ...]]);
 * $list->search($builder, ['firstname', 'name']);
 * return $this->listResponse('view', $data, $list->paginate($builder, fn($row) => ...));
 */
class ListQuery
{
    /**
     * Number of items per page that can be chosen, and the default one
     */
    public const PER_PAGE = [5, 10, 25, 50];
    public const DEFAULT_PER_PAGE = 25;

    /**
     * Maximum length of the search, and number of words taken into account
     */
    private const SEARCH_MAX_LENGTH = 100;
    private const SEARCH_MAX_WORDS = 5;

    public int $page;
    public int $perPage;
    public string $search;

    /**
     * @var array [name => value] of the filters (always one of the allowed values)
     */
    private array $filters = [];

    /**
     * @var array [name => default value] of the filters, and the default number per page
     */
    private array $defaults = [];

    /**
     * @param array $filters [name => allowed values (the first one is the default)]
     * @param array $perPage the sizes of page that can be chosen (default: DEFAULT_PER_PAGE if it is one of them, otherwise the first one)
     */
    public static function fromRequest(IncomingRequest $request, array $filters = [], array $perPage = self::PER_PAGE): self
    {
        $list = new self();
        $list->page = max(1, (int) $request->getGet('page'));
        $size = (int) $request->getGet('per_page');
        $default = in_array(self::DEFAULT_PER_PAGE, $perPage, true) ? self::DEFAULT_PER_PAGE : $perPage[0];
        $list->perPage = in_array($size, $perPage, true) ? $size : $default;
        $list->defaults = ['search' => '', 'per_page' => $default];
        $list->search = mb_substr(trim((string) $request->getGet('search')), 0, self::SEARCH_MAX_LENGTH);

        foreach ($filters as $name => $allowed) {
            // Missing: the default value; "?status=" (empty) is a value, e.g. "all" when the default is another one
            $value = $request->getGet($name);
            $list->filters[$name] = $value !== null && in_array((string) $value, array_map('strval', $allowed), true) ? (string) $value : (string) $allowed[0];
            $list->defaults[$name] = (string) $allowed[0];
        }

        return $list;
    }

    /**
     * Value of a filter (one of its allowed values).
     */
    public function filter(string $name): string
    {
        return $this->filters[$name] ?? '';
    }

    /**
     * Keeps the rows where each word of the search is found in one of the fields (not case sensitive).
     *
     * @param array $fields columns searched, or callables fn(string $word): string giving an SQL condition (escaped)
     */
    public function search(BaseBuilder $builder, array $fields): void
    {
        $words = array_slice(preg_split('/\s+/u', $this->search, -1, PREG_SPLIT_NO_EMPTY), 0, self::SEARCH_MAX_WORDS);
        foreach ($words as $word) {
            $builder->groupStart();
            foreach ($fields as $field) {
                // A callable gives an SQL condition for the word (e.g. a subquery), already escaped
                if (is_callable($field))
                    $builder->orWhere($field($word), null, false);
                else
                    $builder->orLike($field, $word, 'both', null, true);
            }
            $builder->groupEnd();
        }
    }

    /**
     * Reads the page of the query (the filters, the search and the order must already be applied).
     * If the page does not exist anymore (e.g. after a deletion), the last page is returned.
     *
     * @param callable|null $map transforms the rows (objects) of the page
     * @param callable|null $prepare transforms the whole page at once (e.g. to add related data with one query)
     * @return array ['success', 'items', 'pagination' => [page, per_page, total, pages, from, to], 'filters' (current values), 'defaults']
     */
    public function paginate(BaseBuilder $builder, ?callable $map = null, ?callable $prepare = null): array
    {
        $total = $builder->countAllResults(false);
        $pages = max(1, (int) ceil($total / $this->perPage));
        $page = min($this->page, $pages);

        $rows = $builder->limit($this->perPage, ($page - 1) * $this->perPage)->get()->getResultObject();
        if ($prepare)
            $rows = $prepare($rows);
        $items = $map ? array_map($map, $rows) : $rows;

        return [
            'success' => true,
            'items' => array_values($items),
            'pagination' => [
                'page' => $page,
                'per_page' => $this->perPage,
                'total' => $total,
                'pages' => $pages,
                'from' => $total ? ($page - 1) * $this->perPage + 1 : 0,
                'to' => min($total, $page * $this->perPage),
            ],
            'filters' => ['search' => $this->search] + $this->filters,
            'defaults' => $this->defaults,
        ];
    }

    /**
     * Page numbers to display around the current page, with null for "…": [1, null, 4, 5, 6, null, 12]
     * (same rule as pageLinks() of script.js).
     */
    public static function pageNumbers(int $page, int $pages): array
    {
        $numbers = [];
        foreach (range(1, $pages) as $number) {
            if ($number === 1 || $number === $pages || abs($number - $page) <= 1)
                $numbers[] = $number;
            elseif (end($numbers) !== null)
                $numbers[] = null;
        }
        return $numbers;
    }
}
