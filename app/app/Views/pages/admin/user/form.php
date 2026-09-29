<?php
   // Values: previous submission (validation error), otherwise the account being edited
   $submitted = old('firstname') !== null;
   $value = fn(string $field) => old($field, null, false) ?? (string) ($user->$field ?? '');
   $userRoles = $submitted ? (array) (old('roles', null, false) ?? []) : ($user->roles ?? []);
   $active = $submitted ? old('active') === '1' : ($user->exists ?? true);
?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - <?= $user ? 'Modifier un compte' : 'Créer un compte' ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
   <div class="px-4 sm:px-6 lg:px-8">
      <?= component('page_header', ['title' => $user ? $user->display_name : 'Créer un compte', 'subtitle' => $user->email ?? null, 'back' => ['/admin/utilisateurs', 'Tous les utilisateurs']]) ?>

      <?= component('flash') ?>

      <form method="POST" class="max-w-3xl space-y-8 pb-10" action="<?= $user ? base_url('admin/utilisateurs/update/' . $user->id) : base_url('admin/utilisateurs/store') ?>">
         <?php if (!$user): ?>
            <?= component('field', ['label' => 'Adresse e-mail', 'name' => 'email', 'type' => 'email', 'required' => true, 'value' => old('email', null, false),
               'help' => 'La personne recevra un e-mail pour choisir son mot de passe (lien valable 7 jours).', 'attrs' => ['maxlength' => 255]]) ?>
         <?php endif; ?>

         <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <?= component('field', ['label' => 'Prénom', 'name' => 'firstname', 'required' => true, 'value' => $value('firstname'), 'attrs' => ['maxlength' => 100]]) ?>
            <?= component('field', ['label' => 'Nom', 'name' => 'name', 'required' => true, 'value' => $value('name'), 'attrs' => ['maxlength' => 100]]) ?>
            <?= component('field', ['label' => 'Totem', 'name' => 'totem', 'value' => $value('totem'), 'attrs' => ['maxlength' => 100]]) ?>
         </div>

         <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <?= component('field', ['label' => 'Fonction dans l\'unité', 'name' => 'user_type_id', 'type' => 'select', 'required' => true, 'placeholder' => 'Choisir...',
               'options' => array_column($userTypes, 'name', 'id'), 'value' => $value('user_type_id')]) ?>
            <?= component('field', ['label' => 'Téléphone', 'name' => 'phone', 'type' => 'tel', 'value' => $value('phone'), 'attrs' => ['maxlength' => 30]]) ?>
         </div>

         <!-- Roles -->
         <fieldset>
            <legend class="text-sm font-medium leading-6 text-gray-900">Rôles d'administration</legend>
            <p class="text-sm text-gray-500">Sans rôle, le compte peut seulement voir les photos réservées aux membres.</p>
            <div class="mt-3 space-y-3">
               <?php foreach ($roles as $code => $role): ?>
                  <?php $locked = $isCurrentUser && $code === 'super_admin' && in_array('super_admin', $user->roles, true); ?>
                  <?= component('checkbox', ['variant' => 'card', 'name' => 'roles[]', 'value' => $code, 'checked' => in_array($code, $userRoles, true), 'locked' => $locked,
                     'label' => $role['label'], 'description' => $role['description'], 'note' => $locked ? 'Vous ne pouvez pas retirer votre propre rôle de super administrateur.' : null]) ?>
               <?php endforeach; ?>
            </div>
         </fieldset>

         <?php if ($user): ?>
            <?= component('checkbox', ['variant' => 'panel', 'name' => 'active', 'checked' => $active, 'locked' => $isCurrentUser, 'label' => 'Compte actif',
               'description' => $isCurrentUser ? 'Vous ne pouvez pas désactiver votre propre compte.' : 'Un compte désactivé ne peut plus se connecter (il est déconnecté immédiatement). Rien n\'est supprimé.']) ?>
         <?php endif; ?>

         <?= component('form_actions', ['cancel' => '/admin/utilisateurs', 'submit' => $user ? 'Enregistrer' : 'Créer le compte']) ?>
      </form>

      <?php if ($user && $user->exists): ?>
         <form method="POST" action="<?= base_url('admin/utilisateurs/invite/' . $user->id) ?>" class="mb-16 max-w-3xl rounded-lg p-4 ring-1 ring-inset ring-gray-200"
            onsubmit="return confirm('Envoyer à <?= esc($user->email, 'js') ?> un lien pour choisir un mot de passe ?')">
            <p class="text-sm font-medium text-gray-900">Lien de connexion</p>
            <p class="mt-1 text-sm text-gray-500">Envoie un e-mail avec un lien pour choisir un (nouveau) mot de passe, valable 7 jours. Utile si l'e-mail de création n'a pas été reçu.</p>
            <?= component('button', ['label' => 'Envoyer un lien', 'type' => 'submit', 'variant' => 'secondary', 'size' => 'sm', 'class' => 'mt-3']) ?>
         </form>
      <?php endif; ?>
   </div>
</div>
<?= $this->endSection() ?>
