<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
    Guides et scoutes de Gosselies - Connexion
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="flex" x-data="{ errors : <?= isset($errors) ? 'true' : 'false'  ?> }">
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
                    <div class="rounded-md bg-red-50 p-2" x-show="errors">
                        <div class="flex">
                            <div @click="errors = false" class="flex-shrink-0 cursor-pointer">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Erreur
                                    <?= count($errors) > 1 ? 's' : '' ?> détectée
                                    <?= count($errors) > 1 ? 's' : '' ?>
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul role="list" class="list-disc space-y-1 pl-5">
                                        <?php foreach ($errors as $error): ?>
                                            <li>
                                                <?= $error ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <h1 class="text-xl md:text-2xl font-bold leading-tight mt-6">Connexion</h1>
                <form class="mt-6" action="/auth/login" method="post">
                    <div>
                        <label class="block text-gray-700">Email</label>
                        <input type="email" name="email" id="email" title="email" placeholder="exemple@gmail.com"
                            class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            autofocus="" autocomplete="" required="">
                    </div>

                    <div class="mt-4">
                        <label class="block text-gray-700">Mot de passe</label>
                        <input type="password" name="password" id="password" title="mot de passe" minlength="8"
                            maxlength="32"
                            class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            required="">
                    </div>

                    <div class="text-right mt-2">
                        <a href="#"
                            class="text-sm font-semibold text-gray-700 hover:text-blue-700 focus:text-blue-700">Mot de
                            passe oublié?</a>
                    </div>

                    <button type="submit"
                        class="w-full block bg-indigo-500 hover:bg-indigo-400 focus:bg-indigo-400 text-white font-semibold rounded-lg px-4 py-3 mt-6">Se
                        connecter</button>
                </form>

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
            </div>
        </div>
    </div>

</section>
<?= $this->endSection() ?>