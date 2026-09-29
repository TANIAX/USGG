<?php
   // Values: previous submission (validation error), otherwise the leader being edited
   $value = fn(string $field, $default = '') => old($field, null, false) ?? (string) ($leader->$field ?? $default);
?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - <?= $leader ? 'Modifier un responsable' : 'Ajouter un responsable' ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="leaderForm()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <?= component('page_header', ['title' => $leader ? 'Modifier un responsable' : 'Ajouter un responsable', 'back' => ['/admin/responsables', 'Tous les responsables']]) ?>

      <?= component('flash') ?>

      <form method="POST" enctype="multipart/form-data" class="max-w-3xl space-y-6 pb-16" @submit="submitting = true"
         action="<?= $leader ? base_url('admin/responsables/update/' . $leader->id) : base_url('admin/responsables/store') ?>">

         <!-- E-mail: identifies the account -->
         <?php if ($leader): ?>
            <div>
               <span class="block text-sm font-medium leading-6 text-gray-900">Adresse e-mail</span>
               <p class="mt-2 text-sm text-gray-700"><?= esc($leader->email) ?></p>
               <p class="mt-1 text-sm text-gray-500">L'adresse identifie le compte, elle ne peut pas être modifiée ici.</p>
            </div>
         <?php else: ?>
            <?php component_open('field', ['label' => 'Adresse e-mail', 'required' => true, 'for' => 'email']) ?>
               <?= component('input', ['type' => 'email', 'name' => 'email', 'value' => old('email', null, false), 'required' => true, 'placeholder' => 'prenom.nom@example.org', 'class' => 'mt-2',
                  'attrs' => ['maxlength' => 255, 'autocomplete' => 'off', 'x-model' => 'email', '@change' => 'lookup()', '@blur' => 'lookup()']]) ?>
               <p class="mt-2 text-sm" :class="account && account.exists ? 'text-green-700' : 'text-gray-500'" x-text="accountMessage"></p>
            <?= component_close() ?>
         <?php endif; ?>

         <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <?= component('field', ['label' => 'Section', 'name' => 'section_id', 'type' => 'select', 'required' => true, 'placeholder' => 'Choisir...',
               'options' => array_column($sections, 'name', 'id'), 'value' => $value('section_id'), 'help' => '« Unité » : staff d\'unité.']) ?>
            <?= component('field', ['label' => 'Fonction', 'name' => 'user_type_id', 'type' => 'select', 'required' => true, 'placeholder' => 'Choisir...',
               'options' => array_column($userTypes, 'name', 'id'), 'value' => $value('user_type_id'), 'attrs' => ['x-ref' => 'userType']]) ?>
         </div>

         <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <?= component('field', ['label' => 'Prénom', 'name' => 'firstname', 'required' => true, 'value' => $value('firstname'), 'attrs' => ['maxlength' => 100, 'x-ref' => 'firstname']]) ?>
            <?= component('field', ['label' => 'Nom', 'name' => 'name', 'required' => true, 'value' => $value('name'), 'attrs' => ['maxlength' => 100, 'x-ref' => 'name']]) ?>
            <?= component('field', ['label' => 'Totem', 'name' => 'totem', 'value' => $value('totem'), 'attrs' => ['maxlength' => 100, 'x-ref' => 'totem']]) ?>
         </div>
         <p class="-mt-3 text-sm text-gray-500">Sur la page d'accueil, le totem est affiché ; à défaut, le prénom.</p>

         <?= component('field', ['label' => 'Téléphone', 'name' => 'phone', 'type' => 'tel', 'value' => $value('phone'), 'help' => 'Non affiché publiquement.', 'class' => 'sm:w-1/3',
            'attrs' => ['maxlength' => 30, 'x-ref' => 'phone']]) ?>

         <!-- Portrait -->
         <div>
            <span class="block text-sm font-medium leading-6 text-gray-900">Photo</span>
            <div class="mt-2 flex items-center gap-x-4">
               <img :src="picture || '<?= base_url('assets/img/question-mark.jpg') ?>'" alt="" class="h-20 w-20 rounded-full bg-gray-100 object-cover ring-1 ring-gray-200">
               <?= component('file_button', ['name' => 'picture', 'label_alpine' => "picture ? 'Changer la photo' : 'Choisir une photo'", 'attrs' => ['@change' => 'choosePicture($event.target)']]) ?>
               <span class="text-sm text-gray-500" x-show="reducing">Préparation…</span>
            </div>
            <p class="mt-2 text-sm text-gray-500">Facultatif. La photo est recadrée en carré.</p>
         </div>

         <?= component('form_actions', ['cancel' => '/admin/responsables', 'submit_attrs' => [':disabled' => 'reducing || submitting']]) ?>
      </form>
   </div>
</div>

<script>
   function leaderForm() {
      return {
         email: <?= js_data(old('email', '', false) ?? '') ?>,
         account: null,
         lastLookup: null,
         picture: <?= json_encode($leader->picture_url ?? null) ?>,
         reducing: false,
         submitting: false,

         init() {
            if (this.email)
               this.lookup();
         },

         get accountMessage() {
            if (!this.account)
               return 'Si aucun compte n\'existe pour cette adresse, il sera créé et la personne recevra un e-mail pour choisir son mot de passe.';
            if (!this.account.exists)
               return 'Aucun compte pour cette adresse : il sera créé et la personne recevra un e-mail pour choisir son mot de passe.';
            return this.account.active
               ? 'Compte existant : il sera lié, ses informations ont été reprises ci-dessous.'
               : 'Compte existant mais désactivé : il sera réactivé et lié.';
         },

         // Fills in the form with the existing account of the address
         async lookup() {
            const email = this.email.trim();
            if (!email || email === this.lastLookup || !/^\S+@\S+\.\S+$/.test(email))
               return;
            this.lastLookup = email;
            try {
               const response = await fetch(`/admin/responsables/compte?email=${encodeURIComponent(email)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
               this.account = await response.json();
            } catch (error) {
               this.account = null;
               return;
            }
            if (!this.account.exists)
               return;
            // Only the empty fields are filled in, what was typed is kept
            ['firstname', 'name', 'totem', 'phone'].forEach(field => {
               if (!this.$refs[field].value && this.account[field])
                  this.$refs[field].value = this.account[field];
            });
            if (!this.$refs.userType.value && this.account.user_type_id)
               this.$refs.userType.value = this.account.user_type_id;
            if (!this.picture && this.account.picture_url)
               this.picture = this.account.picture_url;
         },

         async choosePicture(input) {
            if (!input.files.length)
               return;
            this.reducing = true;
            const file = await reduceInputPhoto(input, 800);
            this.reducing = false;
            if (file)
               this.picture = URL.createObjectURL(file);
         },
      }
   }
</script>
<?= $this->endSection() ?>
