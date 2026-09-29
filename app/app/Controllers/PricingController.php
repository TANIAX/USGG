<?php

namespace App\Controllers;

use App\Helpers\AuditHelper;
use App\Repositories\PricingRepository;

/**
 * Membership fees shown on the page "En pratique > Cotisation" (super admin, ASBL admin).
 */
class PricingController extends BaseController
{
    private PricingRepository $pricingRepository;

    public function __construct()
    {
        $this->pricingRepository = service('repository', 'Pricing');
        helper('form');
    }

    public function edit()
    {
        return view('pages/admin/pricing/form', ['pricing' => $this->pricingRepository->getPricing()]);
    }

    public function update()
    {
        $post = array_map(fn($value) => is_string($value) ? trim(str_replace(',', '.', $value)) : $value, $this->request->getPost());
        $amount = 'required|decimal|greater_than_equal_to[0]|less_than[1000]';
        $rules = [
            'tier_1' => $amount, 'tier_2' => $amount, 'tier_3' => $amount,
            'reduction' => 'required|decimal|greater_than_equal_to[0]|less_than[1000]',
            'iban' => 'required|max_length[40]|regex_match[/^[A-Z]{2}[0-9]{2}[A-Z0-9 ]{8,}$/]',
            'payment_deadline' => 'required|max_length[100]',
        ];
        $invalidAmount = ['required' => 'Chaque montant est obligatoire.', 'decimal' => 'Les montants doivent être des nombres (ex. 48 ou 47,50).',
            'greater_than_equal_to' => 'Les montants ne peuvent pas être négatifs.', 'less_than' => 'Un montant semble trop élevé.'];
        $messages = [
            'tier_1' => $invalidAmount, 'tier_2' => $invalidAmount, 'tier_3' => $invalidAmount, 'reduction' => $invalidAmount,
            'iban' => ['required' => 'Le numéro de compte est obligatoire.', 'max_length' => 'Le numéro de compte est trop long.', 'regex_match' => 'Le numéro de compte doit être un IBAN (ex. BE84 7320 5580 4959).'],
            'payment_deadline' => ['required' => 'La date limite de paiement est obligatoire.', 'max_length' => 'La date limite est trop longue.'],
        ];
        $post['iban'] = strtoupper($post['iban'] ?? '');
        if (!$this->validateData($post, $rules, $messages))
            return $this->redirectWithErrors('/admin/cotisations', array_unique(array_values($this->validator->getErrors())))->withInput();

        $data = [
            'tier_1' => (float) $post['tier_1'], 'tier_2' => (float) $post['tier_2'], 'tier_3' => (float) $post['tier_3'],
            'reduction' => (float) $post['reduction'],
            'iban' => preg_replace('/\s+/', ' ', $post['iban']),
            'payment_deadline' => $post['payment_deadline'],
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $pricing = $this->pricingRepository->getPricing();
        if ($pricing)
            $this->db()->table('pricing')->where('id', $pricing->id)->update($data);
        else
            $this->db()->table('pricing')->insert($data);

        log_message('notice', 'Tarifs des cotisations modifiés : {tiers} € (réduction {reduction} €)', ['tiers' => $data['tier_1'] . ' / ' . $data['tier_2'] . ' / ' . $data['tier_3'], 'reduction' => $data['reduction']]);
        AuditHelper::log('updated', 'Cotisations', $data['tier_1'] . ' / ' . $data['tier_2'] . ' / ' . $data['tier_3'] . ' € (réduction ' . $data['reduction'] . ' €)', '/admin/cotisations');
        $this->session->setFlashdata('success', 'Les tarifs ont été enregistrés : ils sont affichés sur la page Cotisation.');
        return redirect()->to(base_url('/admin/cotisations'));
    }

    private function db()
    {
        return \Config\Database::connect();
    }
}
