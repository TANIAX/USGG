<?php
   // Values: previous submission (validation error), otherwise the account being edited
   $submitted = old('firstname') !== null;
   $value = fn(string $field) => old($field) ?? esc((string) ($user->$field ?? ''));
   $userRoles = $submitted ? (array) (old('roles', null, false) ?? []) : ($user->roles ?? []);
   $active = $submitted ? old('active') === '1' : ($user->exists ?? true);
   $inputClass = 'mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6';
?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - <?= $user ? 'Modifier un compte' : 'Créer un compte' ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
   <div class="px-4 sm:px-6 lg:px-8">
      <div class="mt-12 mb-8 border-b py-4">
         <a href="/admin/utilisateurs" class="text-sm font-medium text-gray-500 hover:text-gray-700">&larr; Tous les utilisateurs</a>
         <h1 class="mt-2 font-semibold text-4xl leading-tight text-gray-900"><?= $user ? esc($user->display_name) : 'Créer un compte' ?></h1>
         <?php if ($user): ?><p class="mt-1 text-sm text-gray-500"><?= esc($user->email) ?></p><?php endif; ?>
      </div>

      <?= $this->include('pages/admin/messages') ?>

      <form method="POST" class="max-w-3xl space-y-8 pb-10" action="<?= $user ? base_url('admin/utilisateurs/update/' . $user->id) : base_url('admin/utilisateurs/store') ?>">
         <?php if (!$user): ?>
            <div>
               <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Adresse e-mail <span class="text-red-600">*</span></label>
               <input type="email" name="email" id="email" value="<?= old('email') ?>" required maxlength="255" class="<?= $inputClass ?>">
               <p class="mt-1 text-xs text-gray-500">La personne recevra un e-mail pour choisir son mot de passe (lien valable 7 jours).</p>
            </div>
         <?php endif; ?>

         <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
               <label for="firstname" class="block text-sm font-medium leading-6 text-gray-900">Prénom <span class="text-red-600">*</span></label>
               <input type="text" name="firstname" id="firstname" value="<?= $value('firstname') ?>" required maxlength="100" class="<?= $inputClass ?>">
            </div>
            <div>
               <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nom <span class="text-red-600">*</span></label>
               <input type="text" name="name" id="name" value="<?= $value('name') ?>" required maxlength="100" class="<?= $inputClass ?>">
            </div>
            <div>
               <label for="totem" class="block text-sm font-medium leading-6 text-gray-900">Totem</label>
               <input type="text" name="totem" id="totem" value="<?= $value('totem') ?>" maxlength="100" class="<?= $inputClass ?>">
            </div>
         </div>

         <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
               <label for="user_type_id" class="block text-sm font-medium leading-6 text-gray-900">Fonction dans l'unité <span class="text-red-600">*</span></label>
               <select name="user_type_id" id="user_type_id" required class="<?= $inputClass ?>">
                  <option value="">Choisir...</option>
                  <?php foreach ($userTypes as $type): ?>
                     <option value="<?= $type->id ?>" <?= (string) $value('user_type_id') === (string) $type->id ? 'selected' : '' ?>><?= esc($type->name) ?></option>
                  <?php endforeach; ?>
               </select>
            </div>
            <div>
               <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Téléphone</label>
               <input type="tel" name="phone" id="phone" value="<?= $value('phone') ?>" maxlength="30" class="<?= $inputClass ?>">
            </div>
         </div>

         <!-- Roles -->
         <fieldset>
            <legend class="text-sm font-medium leading-6 text-gray-900">Rôles d'administration</legend>
            <p class="text-sm text-gray-500">Sans rôle, le compte peut seulement voir les photos réservées aux membres.</p>
            <div class="mt-3 space-y-3">
               <?php foreach ($roles as $code => $role): ?>
                  <?php $locked = $isCurrentUser && $code === 'super_admin' && in_array('super_admin', $user->roles, true); ?>
                  <label class="flex items-start gap-x-3 rounded-lg p-3 ring-1 ring-inset ring-gray-200 hover:bg-gray-50">
                     <input type="checkbox" name="roles[]" value="<?= esc($code) ?>" <?= in_array($code, $userRoles, true) ? 'checked' : '' ?>
                        <?= $locked ? 'onclick="return false" aria-disabled="true"' : '' ?>
                        class="mt-0.5 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                     <span>
                        <span class="block text-sm font-medium text-gray-900"><?= esc($role['label']) ?></span>
                        <span class="block text-sm text-gray-500"><?= esc($role['description']) ?></span>
                        <?php if ($locked): ?><span class="mt-1 block text-xs text-amber-700">Vous ne pouvez pas retirer votre propre rôle de super administrateur.</span><?php endif; ?>
                     </span>
                  </label>
               <?php endforeach; ?>
            </div>
         </fieldset>

         <?php if ($user): ?>
            <div class="rounded-lg bg-gray-50 p-4">
               <label class="flex items-start gap-x-3">
                  <input type="checkbox" name="active" value="1" <?= $active ? 'checked' : '' ?> <?= $isCurrentUser ? 'onclick="return false" aria-disabled="true"' : '' ?>
                     class="mt-0.5 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                  <span>
                     <span class="block text-sm font-medium text-gray-900">Compte actif</span>
                     <span class="block text-sm text-gray-500">
                        <?= $isCurrentUser ? 'Vous ne pouvez pas désactiver votre propre compte.' : 'Un compte désactivé ne peut plus se connecter (il est déconnecté immédiatement). Rien n\'est supprimé.' ?>
                     </span>
                  </span>
               </label>
            </div>
         <?php endif; ?>

         <div class="flex justify-end gap-x-3 border-t pt-6">
            <a href="/admin/utilisateurs" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Annuler</a>
            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"><?= $user ? 'Enregistrer' : 'Créer le compte' ?></button>
         </div>
      </form>

      <?php if ($user && $user->exists): ?>
         <form method="POST" action="<?= base_url('admin/utilisateurs/invite/' . $user->id) ?>" class="mb-16 max-w-3xl rounded-lg p-4 ring-1 ring-inset ring-gray-200"
            onsubmit="return confirm('Envoyer à <?= esc($user->email, 'js') ?> un lien pour choisir un mot de passe ?')">
            <p class="text-sm font-medium text-gray-900">Lien de connexion</p>
            <p class="mt-1 text-sm text-gray-500">Envoie un e-mail avec un lien pour choisir un (nouveau) mot de passe, valable 7 jours. Utile si l'e-mail de création n'a pas été reçu.</p>
            <button type="submit" class="mt-3 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Envoyer un lien</button>
         </form>
      <?php endif; ?>
   </div>
</div>
<?= $this->endSection() ?>
