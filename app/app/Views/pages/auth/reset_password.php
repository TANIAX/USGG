<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
    Guides et scoutes de Gosselies - Nouveau mot de passe
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="flex justify-center px-4 py-12 sm:py-20">
    <div class="w-full max-w-md rounded-lg bg-white p-8 shadow-xl ring-1 ring-gray-200">
        <?php if ($token === null): ?>
            <h1 class="text-2xl font-bold leading-tight text-gray-900">Lien invalide ou expiré</h1>
            <p class="mt-2 text-sm text-gray-600">
                Ce lien n'est plus valable : il a déjà été utilisé, il a expiré
                ou un lien plus récent a été demandé.
            </p>
            <a href="/auth/mot-de-passe-oublie" class="mt-6 block w-full rounded-lg bg-indigo-500 px-4 py-3 text-center font-semibold text-white hover:bg-indigo-400">
                Demander un nouveau lien
            </a>
        <?php else: ?>
            <h1 class="text-2xl font-bold leading-tight text-gray-900">Nouveau mot de passe</h1>
            <p class="mt-2 text-sm text-gray-600">Compte : <span class="font-medium text-gray-900"><?= esc($email) ?></span></p>

            <div class="mt-6">
                <?= $this->include('pages/admin/messages') ?>
            </div>

            <form action="<?= base_url('auth/reinitialiser/' . $token) ?>" method="post" x-data="{ password: '', confirmation: '', show: false }">
                <!-- Helps the password managers to link the new password to the account -->
                <input type="text" name="username" value="<?= esc($email) ?>" autocomplete="username" class="hidden" readonly>

                <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                <input :type="show ? 'text' : 'password'" name="password" id="password" x-model="password" required autofocus
                    minlength="<?= $minLength ?>" maxlength="<?= $maxLength ?>" autocomplete="new-password"
                    class="mt-1 block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                <p class="mt-1 text-xs" :class="password.length > 0 && password.length < <?= $minLength ?> ? 'text-red-600' : 'text-gray-500'">
                    Entre <?= $minLength ?> et <?= $maxLength ?> caractères.
                </p>

                <label for="password_confirmation" class="mt-4 block text-sm font-medium text-gray-700">Confirmez le mot de passe</label>
                <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" x-model="confirmation" required
                    minlength="<?= $minLength ?>" maxlength="<?= $maxLength ?>" autocomplete="new-password"
                    class="mt-1 block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                <p x-show="confirmation.length > 0 && confirmation !== password" class="mt-1 text-xs text-red-600">Les deux mots de passe ne sont pas identiques.</p>

                <label class="mt-4 inline-flex items-center gap-x-2 text-sm text-gray-700">
                    <input type="checkbox" x-model="show" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                    Afficher les mots de passe
                </label>

                <button type="submit" class="mt-6 block w-full rounded-lg bg-indigo-500 px-4 py-3 font-semibold text-white hover:bg-indigo-400 focus:bg-indigo-400">
                    Enregistrer le mot de passe
                </button>
            </form>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
