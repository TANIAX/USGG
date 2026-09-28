<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
    Guides et scoutes de Gosselies - Mot de passe oublié
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="flex justify-center px-4 py-12 sm:py-20">
    <div class="w-full max-w-md rounded-lg bg-white p-8 shadow-xl ring-1 ring-gray-200">
        <h1 class="text-2xl font-bold leading-tight text-gray-900">Mot de passe oublié</h1>
        <p class="mt-2 text-sm text-gray-600">
            Indiquez l'adresse e-mail de votre compte : nous vous enverrons un lien pour choisir un nouveau mot de passe.
        </p>

        <div class="mt-6">
            <?= $this->include('pages/admin/messages') ?>
        </div>

        <form action="<?= base_url('auth/mot-de-passe-oublie') ?>" method="post">
            <label for="email" class="block text-sm font-medium text-gray-700">Adresse e-mail</label>
            <input type="email" name="email" id="email" value="<?= old('email') ?>" required autofocus autocomplete="email" maxlength="255"
                placeholder="exemple@gmail.com"
                class="mt-1 block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

            <button type="submit" class="mt-6 block w-full rounded-lg bg-indigo-500 px-4 py-3 font-semibold text-white hover:bg-indigo-400 focus:bg-indigo-400">
                Envoyer le lien
            </button>
        </form>

        <p class="mt-6 text-center text-sm">
            <a href="/auth/login" class="font-semibold text-gray-700 hover:text-blue-700">&larr; Retour à la connexion</a>
        </p>
    </div>
</section>
<?= $this->endSection() ?>
