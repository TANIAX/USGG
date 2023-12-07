<?php

namespace App\Repositories;


use App\Repositories\BaseRepository;

/**
 * PricingRepository class represents a repository for the pricing model.
 * It implements the IRepository interface and extends the BaseRepository class.
 * It provides methods to interact with the pricing table in the database.
 *
 * @author Guillaume Cornez
 */
class PricingRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('pricing');
    }

    /**
     * Get the pricing.
     *
     * @param int $result_type The type of result to return. Default is self::RESULT_AS_OBJECT.
     * @param string|null $result_class The class to use for the result. Default is null.
     * @return mixed The result of the query.
     */
    public function getPricing($result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $data = $this->getAll($result_type, $result_class);

        if ($data)
            return $data[0]; // There is only one pricing in the database

        return null;
    }

}
