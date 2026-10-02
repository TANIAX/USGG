<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
    Guides et scoutes de Gosselies - Connexion
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="flex">
    <div class="flex justify-center w-screen h-screen md:h-1/2 lg:m-24">

        <!-- Logo -->
        <div
            class="hidden lg:block w-96 shadow-xl <?= !isset($errors) ? 'animate__animated animate__fadeInLeft animate__slow' : '' ?>">
            <img id="logo" class="bg-center h-full rounded-l-lg " src="<?= base_url('assets/img/login-cover.jpg') ?>"
                alt="login cover">
        </div>

        <!-- Form -->
        <div class="bg-white w-full shadow-xl lg:w-96 p-8 md:p-12 lg:p-4 flex justify-center border-0 lg:border-2 lg:rounded-r-lg <?= !isset($errors) ? 'animate__animated animate__fadeInUp animate__slow' : '' ?>">
            <div class="w-full h-100">
                <!-- Errors -->
                <?php if (isset($errors)): ?>
                    <?= component('alert', ['messages' => $errors]) ?>
                <?php endif; ?>

                <!-- Message after a password change -->
                <?= component('flash', ['class' => 'mt-0']) ?>

                <h1 class="text-xl md:text-2xl font-bold leading-tight mt-6">Connexion</h1>
                <form class="mt-6 space-y-4" action="/auth/login" method="post">
                    <?= component('field', ['label' => 'Adresse e-mail', 'name' => 'email', 'type' => 'email', 'required' => true, 'placeholder' => 'exemple@gmail.com',
                        'attrs' => ['autofocus' => true, 'autocomplete' => 'username']]) ?>

                    <div>
                        <?= component('field', ['label' => 'Mot de passe', 'name' => 'password', 'type' => 'password', 'required' => true,
                            'attrs' => ['minlength' => 8, 'maxlength' => 32, 'autocomplete' => 'current-password']]) ?>
                        <div class="text-right mt-2">
                            <a href="/auth/mot-de-passe-oublie" class="text-sm font-semibold text-gray-700 hover:text-blue-700 focus:text-blue-700">Mot de passe oublié ?</a>
                        </div>
                    </div>

                    <?= component('button', ['label' => 'Se connecter', 'type' => 'submit', 'size' => 'lg', 'class' => 'mt-2 w-full']) ?>
                </form>

                <?php // Connexion Google désactivée temporairement (token OAuth expiré) : passer à true pour la réactiver ?>
                <?php if (false && !empty($authUrl)): ?>
                <hr class="my-6 border-gray-300 w-full">

                <button type="button"
                    class="w-full block bg-white hover:bg-gray-100 focus:bg-gray-100 text-gray-900 font-semibold rounded-lg px-4 py-3 border border-gray-300">
                    <div class="flex items-center justify-center">
                        <a href="<?= $authUrl ?>" class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                class="w-6 h-6" viewBox="0 0 48 48">
                                <defs>
                                    <path id="a"
                                        d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z">
                                    </path>
                                </defs>
                                <clipPath id="b">
                                    <use xlink:href="#a" overflow="visible"></use>
                                </clipPath>
                                <path clip-path="url(#b)" fill="#FBBC05" d="M0 37V11l17 13z"></path>
                                <path clip-path="url(#b)" fill="#EA4335" d="M0 11l17 13 7-6.1L48 14V0H0z"></path>
                                <path clip-path="url(#b)" fill="#34A853" d="M0 37l30-23 7.9 1L48 0v48H0z"></path>
                                <path clip-path="url(#b)" fill="#4285F4" d="M48 48L17 24l-4-3 35-10z"></path>
                            </svg>
                            <span class="ml-4">Se connecter avec Google</span>
                        </a>
                    </div>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

</section>
<?= $this->endSection() ?>