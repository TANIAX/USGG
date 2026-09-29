<?php

namespace App\Controllers;

use App\Entities\Pricing;
use App\Controllers\BaseController;
use App\Repositories\BaseRepository;
use App\Repositories\PricingRepository;
use App\Repositories\SectionRepository;


class EnPratiqueController extends BaseController
{
    private PricingRepository $pricingRepository;
    private SectionRepository $sectionRepository;

    public function __construct()
    {
        $this->pricingRepository = service('repository', 'Pricing');
        $this->sectionRepository = service('repository', 'Section');
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
        $sections = $this->sectionRepository->getAllOrdered();

        return view('pages/en_pratique/agenda', [
            'sections' => $this->toJson($sections)
        ]);
    }

    public function inscription()
    {
        return view('pages/en_pratique/inscription');
    }
}
