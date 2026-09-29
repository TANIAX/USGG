<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
    Guides et scoutes de Gosselies - Nouveau mot de passe
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if ($token === null): ?>
    <?php component_open('auth_card', ['title' => 'Lien invalide ou expiré', 'text' => 'Ce lien n\'est plus valable : il a déjà été utilisé, il a expiré ou un lien plus récent a été demandé.']) ?>
        <?= component('button', ['label' => 'Demander un nouveau lien', 'href' => '/auth/mot-de-passe-oublie', 'size' => 'lg', 'block' => true, 'class' => 'mt-6']) ?>
    <?= component_close() ?>
<?php else: ?>
    <?php component_open('auth_card', ['title' => 'Nouveau mot de passe', 'text' => 'Compte : <span class="font-medium text-gray-900">' . esc($email) . '</span>']) ?>
        <?= component('flash', ['class' => 'mt-6']) ?>

        <form action="<?= base_url('auth/reinitialiser/' . $token) ?>" method="post" x-data="{ password: '', confirmation: '', show: false }" class="mt-6 space-y-4">
            <!-- Helps the password managers to link the new password to the account -->
            <input type="text" name="username" value="<?= esc($email, 'attr') ?>" autocomplete="username" class="hidden" readonly>

            <?php component_open('field', ['label' => 'Nouveau mot de passe', 'for' => 'password']) ?>
                <?= component('input', ['type' => 'password', 'name' => 'password', 'required' => true, 'class' => 'mt-2',
                    'attrs' => [':type' => "show ? 'text' : 'password'", 'x-model' => 'password', 'autofocus' => true, 'minlength' => $minLength, 'maxlength' => $maxLength, 'autocomplete' => 'new-password']]) ?>
                <p class="mt-2 text-sm" :class="password.length > 0 && password.length < <?= $minLength ?> ? 'text-red-600' : 'text-gray-500'">Entre <?= $minLength ?> et <?= $maxLength ?> caractères.</p>
            <?= component_close() ?>

            <?php component_open('field', ['label' => 'Confirmez le mot de passe', 'for' => 'password_confirmation']) ?>
                <?= component('input', ['type' => 'password', 'name' => 'password_confirmation', 'required' => true, 'class' => 'mt-2',
                    'attrs' => [':type' => "show ? 'text' : 'password'", 'x-model' => 'confirmation', 'minlength' => $minLength, 'maxlength' => $maxLength, 'autocomplete' => 'new-password']]) ?>
                <p x-show="confirmation.length > 0 && confirmation !== password" class="mt-2 text-sm text-red-600">Les deux mots de passe ne sont pas identiques.</p>
            <?= component_close() ?>

            <?= component('checkbox', ['label' => 'Afficher les mots de passe', 'attrs' => ['x-model' => 'show']]) ?>

            <?= component('button', ['label' => 'Enregistrer le mot de passe', 'type' => 'submit', 'size' => 'lg', 'class' => 'mt-2 w-full']) ?>
        </form>
    <?= component_close() ?>
<?php endif; ?>
<?= $this->endSection() ?>
