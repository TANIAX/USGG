<?php
   $value = fn(string $field) => old($field, null, false) ?? (string) ($user->$field ?? '');
?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Mon compte
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
   <div class="px-4 sm:px-6 lg:px-8">
      <?= component('page_header', ['title' => 'Mon compte', 'subtitle' => $user->email . ($roles ? ' · ' . implode(', ', array_column($roles, 'label')) : '')]) ?>

      <?= component('flash') ?>

      <div class="grid max-w-5xl grid-cols-1 gap-12 pb-16 lg:grid-cols-2">
         <section>
            <h2 class="text-lg font-semibold text-gray-900">Profil</h2>
            <form method="POST" enctype="multipart/form-data" action="<?= base_url('mon-compte/profil') ?>" class="mt-4 space-y-6" x-data="{ picture: <?= esc(js_data($pictureUrl), 'attr') ?> }">
               <div class="flex flex-wrap items-center gap-4">
                  <img :src="picture || '<?= base_url('assets/img/question-mark.jpg') ?>'" alt="" class="h-20 w-20 rounded-full bg-gray-100 object-cover ring-1 ring-gray-200">
                  <?= component('file_button', ['name' => 'picture', 'label_alpine' => "picture ? 'Changer la photo' : 'Ajouter une photo'", 'attrs' => ['@change' => 'if ($event.target.files.length) picture = URL.createObjectURL($event.target.files[0])']]) ?>
                  <?php if ($pictureUrl): ?>
                     <?= component('checkbox', ['name' => 'remove_picture', 'label' => 'Retirer la photo', 'attrs' => ['@change' => 'if ($event.target.checked) picture = null']]) ?>
                  <?php endif; ?>
               </div>
               <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                  <?= component('field', ['label' => 'Prénom', 'name' => 'firstname', 'required' => true, 'value' => $value('firstname'), 'attrs' => ['maxlength' => 100, 'autocomplete' => 'given-name']]) ?>
                  <?= component('field', ['label' => 'Nom', 'name' => 'name', 'required' => true, 'value' => $value('name'), 'attrs' => ['maxlength' => 100, 'autocomplete' => 'family-name']]) ?>
                  <?= component('field', ['label' => 'Totem', 'name' => 'totem', 'value' => $value('totem'), 'attrs' => ['maxlength' => 100]]) ?>
                  <?= component('field', ['label' => 'Téléphone', 'name' => 'phone', 'type' => 'tel', 'value' => $value('phone'), 'attrs' => ['maxlength' => 30, 'autocomplete' => 'tel']]) ?>
               </div>
               <p class="text-sm text-gray-500">Si vous êtes responsable de section, votre totem (ou votre prénom) et votre photo sont affichés sur le site. Pour changer d'adresse e-mail, contactez un administrateur.</p>
               <div class="flex justify-end border-t pt-6"><?= component('button', ['label' => 'Enregistrer le profil', 'type' => 'submit']) ?></div>
            </form>
         </section>

         <section id="mot-de-passe" class="scroll-mt-8">
            <h2 class="text-lg font-semibold text-gray-900">Mot de passe</h2>
            <form method="POST" action="<?= base_url('mon-compte/mot-de-passe') ?>" class="mt-4 space-y-6" x-data="{ show: false }">
               <input type="text" name="username" value="<?= esc($user->email, 'attr') ?>" autocomplete="username" class="hidden" readonly>
               <?= component('field', ['label' => 'Mot de passe actuel', 'name' => 'current_password', 'type' => 'password', 'required' => true, 'attrs' => [':type' => "show ? 'text' : 'password'", 'autocomplete' => 'current-password']]) ?>
               <?= component('field', ['label' => 'Nouveau mot de passe', 'name' => 'password', 'type' => 'password', 'required' => true, 'help' => 'Entre ' . $minLength . ' et ' . $maxLength . ' caractères.',
                  'attrs' => [':type' => "show ? 'text' : 'password'", 'minlength' => $minLength, 'maxlength' => $maxLength, 'autocomplete' => 'new-password']]) ?>
               <?= component('field', ['label' => 'Confirmez le nouveau mot de passe', 'name' => 'password_confirmation', 'type' => 'password', 'required' => true,
                  'attrs' => [':type' => "show ? 'text' : 'password'", 'minlength' => $minLength, 'maxlength' => $maxLength, 'autocomplete' => 'new-password']]) ?>
               <?= component('checkbox', ['label' => 'Afficher les mots de passe', 'attrs' => ['x-model' => 'show']]) ?>
               <div class="flex justify-end border-t pt-6"><?= component('button', ['label' => 'Changer le mot de passe', 'type' => 'submit']) ?></div>
            </form>
         </section>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
