<?php use App\Helpers\RegistrationHelper; ?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Demande d'inscription
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php
   $status = old('status', null, false) ?? $request->status;
   $sectionId = old('section_id', null, false) ?? $request->section_id;
   $note = old('note', null, false) ?? $request->note;
   $details = [
      'Date de naissance' => date('d/m/Y', strtotime($request->birthdate)) . ' (' . RegistrationHelper::age($request->birthdate) . ' ans)',
      'Totem' => $request->totem ?: '—',
      'Lien avec l\'unité' => (RegistrationHelper::RELATIONS[$request->relation][0] ?? $request->relation) . ' — phase ' . RegistrationHelper::phase($request->relation),
      'Adresse' => $request->street . ' ' . $request->number . ', ' . $request->zip_code . ' ' . $request->city,
      'Parent' => $request->parent_name,
   ];
   $updated = $request->updated_at ? ' · mise à jour le ' . date('d/m/Y', strtotime($request->updated_at)) . ($request->updated_by_name ? ' par ' . $request->updated_by_name : '') : '';
?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
   <div class="px-4 sm:px-6 lg:px-8">
      <?= component('page_header', ['title' => $request->firstname . ' ' . $request->name, 'back' => ['/admin/inscriptions', 'Toutes les demandes'],
         'subtitle' => 'Demande reçue le ' . date('d/m/Y à H:i', strtotime($request->created_at)) . $updated]) ?>

      <?= component('flash') ?>

      <div class="grid max-w-5xl grid-cols-1 gap-10 pb-16 lg:grid-cols-2">
         <section>
            <h2 class="text-lg font-semibold text-gray-900">Demande</h2>
            <dl class="mt-3 divide-y divide-gray-100 text-sm">
               <?php foreach ($details as $label => $value): ?>
                  <div class="grid grid-cols-3 gap-4 py-2"><dt class="font-medium text-gray-900"><?= esc($label) ?></dt><dd class="col-span-2 text-gray-700"><?= esc($value) ?></dd></div>
               <?php endforeach; ?>
               <div class="grid grid-cols-3 gap-4 py-2"><dt class="font-medium text-gray-900">E-mail</dt><dd class="col-span-2 break-all"><a href="mailto:<?= esc($request->parent_email, 'attr') ?>" class="text-indigo-600 hover:text-indigo-500"><?= esc($request->parent_email) ?></a></dd></div>
               <div class="grid grid-cols-3 gap-4 py-2"><dt class="font-medium text-gray-900">Téléphone</dt><dd class="col-span-2"><a href="tel:<?= esc($request->parent_phone, 'attr') ?>" class="text-indigo-600 hover:text-indigo-500"><?= esc($request->parent_phone) ?></a></dd></div>
               <?php if ($request->remark): ?>
                  <div class="py-2"><dt class="font-medium text-gray-900">Remarque de la famille</dt><dd class="mt-1 whitespace-pre-line text-gray-700"><?= esc($request->remark) ?></dd></div>
               <?php endif; ?>
            </dl>
         </section>

         <section>
            <h2 class="text-lg font-semibold text-gray-900">Suivi</h2>
            <form method="POST" action="<?= base_url('admin/inscriptions/' . $request->id) ?>" class="mt-3 space-y-6">
               <?= component('field', ['label' => 'Statut', 'name' => 'status', 'type' => 'select', 'required' => true, 'value' => $status, 'options' => array_map(fn($definition) => $definition[0], $statuses)]) ?>
               <?= component('field', ['label' => 'Section', 'name' => 'section_id', 'type' => 'select', 'value' => $sectionId, 'options' => $sections, 'placeholder' => 'Pas de préférence']) ?>
               <?= component('field', ['label' => 'Note interne', 'name' => 'note', 'type' => 'textarea', 'rows' => 4, 'value' => $note,
                  'help' => 'Visible uniquement par les administrateurs : date de l\'essai, échanges avec la famille...', 'attrs' => ['maxlength' => 5000]]) ?>
               <?= component('form_actions', ['cancel' => '/admin/inscriptions']) ?>
            </form>

            <form method="POST" action="<?= base_url('admin/inscriptions/' . $request->id . '/delete') ?>" class="mt-10 rounded-md bg-red-50 p-4"
               onsubmit="return confirm('Supprimer définitivement cette demande ? Les données de la famille seront effacées.')">
               <p class="text-sm text-red-800">Supprimer la demande efface les données de la famille (par exemple à l'ouverture d'une nouvelle campagne d'inscription).</p>
               <?= component('button', ['label' => 'Supprimer la demande', 'type' => 'submit', 'variant' => 'danger', 'size' => 'sm', 'class' => 'mt-3']) ?>
            </form>
         </section>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
