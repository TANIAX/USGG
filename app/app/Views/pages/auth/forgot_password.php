<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
    Guides et scoutes de Gosselies - Mot de passe oublié
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php component_open('auth_card', ['title' => 'Mot de passe oublié', 'text' => 'Indiquez l\'adresse e-mail de votre compte : nous vous enverrons un lien pour choisir un nouveau mot de passe.']) ?>
    <?= component('flash', ['class' => 'mt-6']) ?>

    <form action="<?= base_url('auth/mot-de-passe-oublie') ?>" method="post" class="mt-6">
        <?= component('field', ['label' => 'Adresse e-mail', 'name' => 'email', 'type' => 'email', 'required' => true, 'value' => old('email', null, false), 'placeholder' => 'exemple@gmail.com',
            'attrs' => ['autofocus' => true, 'autocomplete' => 'email', 'maxlength' => 255]]) ?>
        <?= component('button', ['label' => 'Envoyer le lien', 'type' => 'submit', 'size' => 'lg', 'class' => 'mt-6 w-full']) ?>
    </form>

    <p class="mt-6 text-center text-sm">
        <a href="/auth/login" class="font-semibold text-gray-700 hover:text-blue-700">&larr; Retour à la connexion</a>
    </p>
<?= component_close() ?>
<?= $this->endSection() ?>
