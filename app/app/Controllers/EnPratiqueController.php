<?php

namespace App\Controllers;

use App\Entities\Pricing;
use App\Controllers\BaseController;
use App\Repositories\BaseRepository;
use App\Repositories\PricingRepository;


class EnPratiqueController extends BaseController
{
    private PricingRepository $pricingRepository;

    public function __construct()
    {
        $this->pricingRepository = service('repository', 'Pricing');
    }

    public function cotisation()
    {
        $pricing = $this->pricingRepository->getPricing(BaseRepository::RESULT_AS_CUSTOM, Pricing::class);

        return view('pages/en_pratique/cotisation', [
            'pricing' => $pricing
        ]);
    }

    public function agenda()
    {
        return view('pages/en_pratique/agenda');
    }
}
