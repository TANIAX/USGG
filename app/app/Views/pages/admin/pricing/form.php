<?php
   // Values: previous submission (validation error), otherwise the current fees
   $value = function (string $field, string $default = '') use ($pricing) {
      $current = $pricing->$field ?? null;
      if (is_numeric($current))
         $current = rtrim(rtrim(number_format((float) $current, 2, ',', ''), '0'), ',');
      return old($field, null, false) ?? ($current ?? $default);
   };
   $amount = ['type' => 'text', 'required' => true, 'attrs' => ['inputmode' => 'decimal', 'maxlength' => 8]];
?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Cotisations
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
   <div class="px-4 sm:px-6 lg:px-8">
      <?php component_open('page_header', ['title' => 'Cotisations', 'subtitle' => 'Montants et modalités de paiement affichés sur la page « Cotisation ».'
         . (!empty($pricing->updated_at) ? ' Dernière modification le ' . date('d/m/Y', strtotime($pricing->updated_at)) . '.' : '')]) ?>
         <?= component('button', ['label' => 'Voir la page', 'href' => '/en-pratique/cotisation', 'variant' => 'secondary', 'size' => 'sm', 'block' => true, 'attrs' => ['target' => '_blank']]) ?>
      <?= component_close() ?>

      <?= component('flash') ?>

      <form method="POST" action="<?= base_url('admin/cotisations') ?>" class="max-w-3xl space-y-8 pb-16" novalidate>
         <fieldset>
            <legend class="text-base font-semibold text-gray-900">Montant par personne (en €)</legend>
            <p class="mt-1 text-sm text-gray-500">Selon le nombre de personnes inscrites dans un même foyer.</p>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
               <?= component('field', ['label' => '1 personne', 'name' => 'tier_1', 'value' => $value('tier_1')] + $amount) ?>
               <?= component('field', ['label' => '2 personnes', 'name' => 'tier_2', 'value' => $value('tier_2')] + $amount) ?>
               <?= component('field', ['label' => '3 personnes ou plus', 'name' => 'tier_3', 'value' => $value('tier_3')] + $amount) ?>
            </div>
         </fieldset>

         <?= component('field', ['label' => 'Réduction brevet d\'animateur (en €)', 'name' => 'reduction', 'value' => $value('reduction'), 'class' => 'sm:w-1/3',
            'help' => 'Déduite pour les foyers disposant d\'un brevet d\'animateur.'] + $amount) ?>

         <fieldset>
            <legend class="text-base font-semibold text-gray-900">Paiement</legend>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
               <?= component('field', ['label' => 'Numéro de compte (IBAN)', 'name' => 'iban', 'required' => true, 'value' => $value('iban'), 'placeholder' => 'BE00 0000 0000 0000', 'attrs' => ['maxlength' => 40]]) ?>
               <?= component('field', ['label' => 'Date limite de paiement', 'name' => 'payment_deadline', 'required' => true, 'value' => $value('payment_deadline'), 'placeholder' => '31 octobre',
                  'help' => 'Affichée : « … pour le <em>31 octobre</em> au plus tard ».', 'attrs' => ['maxlength' => 100]]) ?>
            </div>
         </fieldset>

         <?= component('form_actions', ['cancel' => '/admin/cotisations']) ?>
      </form>
   </div>
</div>
<?= $this->endSection() ?>
