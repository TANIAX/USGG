<?php
   // Values: previous submission (validation error), otherwise the leader being edited
   $value = fn(string $field, $default = '') => old($field) ?? esc((string) ($leader->$field ?? $default));
   $inputClass = 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6';
?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - <?= $leader ? 'Modifier un responsable' : 'Ajouter un responsable' ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="leaderForm()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <div class="mt-12 mb-8 border-b py-4">
         <a href="/admin/responsables" class="text-sm font-medium text-gray-500 hover:text-gray-700">&larr; Tous les responsables</a>
         <h1 class="mt-2 font-semibold text-4xl leading-tight text-gray-900"><?= $leader ? 'Modifier un responsable' : 'Ajouter un responsable' ?></h1>
      </div>

      <?= $this->include('pages/admin/messages') ?>

      <form method="POST" enctype="multipart/form-data" class="max-w-3xl space-y-6 pb-16" @submit="submitting = true"
         action="<?= $leader ? base_url('admin/responsables/update/' . $leader->id) : base_url('admin/responsables/store') ?>">

         <!-- E-mail: identifies the account -->
         <div>
            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Adresse e-mail <?php if (!$leader): ?><span class="text-red-600">*</span><?php endif; ?></label>
            <?php if ($leader): ?>
               <p class="mt-2 text-sm text-gray-700"><?= esc($leader->email) ?></p>
               <p class="mt-1 text-sm text-gray-500">L'adresse identifie le compte, elle ne peut pas être modifiée ici.</p>
            <?php else: ?>
               <input type="email" name="email" id="email" value="<?= old('email') ?>" required maxlength="255" autocomplete="off"
                  x-model="email" @change="lookup()" @blur="lookup()" placeholder="prenom.nom@example.org" class="<?= $inputClass ?>">
               <p class="mt-2 text-sm" :class="account && account.exists ? 'text-green-700' : 'text-gray-500'" x-text="accountMessage"></p>
            <?php endif; ?>
         </div>

         <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
               <label for="section_id" class="block text-sm font-medium leading-6 text-gray-900">Section <span class="text-red-600">*</span></label>
               <select name="section_id" id="section_id" required class="<?= $inputClass ?>">
                  <option value="">Choisir...</option>
                  <?php foreach ($sections as $section): ?>
                     <option value="<?= $section->id ?>" <?= (string) $value('section_id') === (string) $section->id ? 'selected' : '' ?>><?= esc($section->name) ?></option>
                  <?php endforeach; ?>
               </select>
               <p class="mt-1 text-xs text-gray-500">« Unité » : staff d'unité.</p>
            </div>
            <div>
               <label for="user_type_id" class="block text-sm font-medium leading-6 text-gray-900">Fonction <span class="text-red-600">*</span></label>
               <select name="user_type_id" id="user_type_id" required x-ref="userType" class="<?= $inputClass ?>">
                  <option value="">Choisir...</option>
                  <?php foreach ($userTypes as $type): ?>
                     <option value="<?= $type->id ?>" <?= (string) $value('user_type_id') === (string) $type->id ? 'selected' : '' ?>><?= esc($type->name) ?></option>
                  <?php endforeach; ?>
               </select>
            </div>
         </div>

         <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
               <label for="firstname" class="block text-sm font-medium leading-6 text-gray-900">Prénom <span class="text-red-600">*</span></label>
               <input type="text" name="firstname" id="firstname" x-ref="firstname" value="<?= $value('firstname') ?>" required maxlength="100" class="<?= $inputClass ?>">
            </div>
            <div>
               <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nom <span class="text-red-600">*</span></label>
               <input type="text" name="name" id="name" x-ref="name" value="<?= $value('name') ?>" required maxlength="100" class="<?= $inputClass ?>">
            </div>
            <div>
               <label for="totem" class="block text-sm font-medium leading-6 text-gray-900">Totem</label>
               <input type="text" name="totem" id="totem" x-ref="totem" value="<?= $value('totem') ?>" maxlength="100" class="<?= $inputClass ?>">
            </div>
         </div>
         <p class="-mt-3 text-xs text-gray-500">Sur la page d'accueil, le totem est affiché ; à défaut, le prénom.</p>

         <div class="sm:w-1/3">
            <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Téléphone</label>
            <input type="tel" name="phone" id="phone" x-ref="phone" value="<?= $value('phone') ?>" maxlength="30" class="<?= $inputClass ?>">
            <p class="mt-1 text-xs text-gray-500">Non affiché publiquement.</p>
         </div>

         <!-- Portrait -->
         <div>
            <span class="block text-sm font-medium leading-6 text-gray-900">Photo</span>
            <div class="mt-2 flex items-center gap-x-4">
               <img :src="picture || '<?= base_url('assets/img/question-mark.jpg') ?>'" alt="" class="h-20 w-20 rounded-full bg-gray-100 object-cover ring-1 ring-gray-200">
               <label class="cursor-pointer rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                  <span x-text="picture ? 'Changer la photo' : 'Choisir une photo'"></span>
                  <input type="file" name="picture" accept="image/*" class="sr-only" @change="choosePicture($event.target)">
               </label>
               <span class="text-sm text-gray-500" x-show="reducing">Préparation…</span>
            </div>
            <p class="mt-1 text-xs text-gray-500">Facultatif. La photo est recadrée en carré.</p>
         </div>

         <div class="flex justify-end gap-x-3 border-t pt-6">
            <a href="/admin/responsables" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Annuler</a>
            <button type="submit" :disabled="reducing || submitting" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 disabled:opacity-50">Enregistrer</button>
         </div>
      </form>
   </div>
</div>

<script>
   function leaderForm() {
      return {
         email: <?= json_encode(old('email', '', false) ?? '', JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
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
