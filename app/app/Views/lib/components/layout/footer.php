<footer class="bg-white w-full" aria-labelledby="footer-heading">
  <h2 id="footer-heading" class="sr-only">Footer</h2>
  <div class="mx-auto max-w-7xl px-6 pb-8 pt-16 sm:pt-24 lg:px-8 lg:pt-32">
    <div class="xl:grid xl:grid-cols-3 xl:gap-8">
      <div class="space-y-8">
        <img class="h-12" src="<?= base_url('assets/img/logo.png') ?>" alt="Unité scoute et guide de gosselies">
        <p class="text-sm leading-6 text-gray-600">Unité scoute et guide de gosselies.</p>
        <div class="flex space-x-6">
          <a onclick="facebookRedirect(false)" class="text-gray-400 cursor-pointer hover:text-gray-500">
            <span class="sr-only">Facebook des scouts</span>
            <svg class="h-6 w-6" fill="#4A8FFF" viewBox="0 0 24 24" aria-hidden="true">
              <path fill-rule="evenodd"
                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                clip-rule="evenodd" />
            </svg>
          </a>
          <a onclick="facebookRedirect(true)" class="text-gray-400 cursor-pointer hover:text-gray-500">
            <span class="sr-only">Facebook des guides</span>
            <svg class="h-6 w-6" fill="deeppink" viewBox="0 0 24 24" aria-hidden="true">
              <path fill-rule="evenodd"
                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                clip-rule="evenodd" />
            </svg>
          </a>
        </div>
      </div>
      <div class="mt-16 grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">

        <div class="md:grid md:grid-cols-2 md:gap-8">
          <div>
            <h3 class="text-sm font-semibold leading-6 text-gray-900">Scouts</h3>
            <ul role="list" class="mt-6 space-y-4">
              <li>
                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Présentation</a>
              </li>
              <li>
                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Sections</a>
              </li>
              <li>
                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Staff d'unité</a>
              </li>
              <li>
                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Documents</a>
              </li>
              <li>
                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Galerie photos</a>
              </li>
            </ul>
          </div>
          <div class="mt-10 md:mt-0">
            <h3 class="text-sm font-semibold leading-6 text-gray-900">Guide</h3>
            <ul role="list" class="mt-6 space-y-4">
              <li>
                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Présentation</a>
              </li>
              <li>
                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Sections</a>
              </li>
              <li>
                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Staff d'unité</a>
              </li>
              <li>
                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Documents</a>
              </li>
              <li>
                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Galerie photos</a>
              </li>
            </ul>
          </div>
        </div>

        <div class="md:grid md:grid-cols-2 md:gap-8">
          <div>
            <h3 class="text-sm font-semibold leading-6 text-gray-900">ASBL</h3>
            <ul role="list" class="mt-6 space-y-4">
              <li>
                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Présentation</a>
              </li>
              <li>
                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Évenements</a>
              </li>
              <li class="!mb-28 !pb-2 md:mt-0">
                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Contact</a>
              </li>

            </ul>
          </div>
          <div class="mt-10 md:mt-0">
            <h3 class="text-sm font-semibold leading-6 text-gray-900">En pratique</h3>
            <ul role="list" class="mt-6 space-y-4">
              <li>
                <a href="/en-pratique/inscription" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Inscription</a>
              </li>
              <li>
                <a href="/en-pratique/cotisation" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Cotisation</a>
              </li>
              <li>
                <a href="/en-pratique/agenda" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Agenda</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-16 border-t border-gray-900/10 pt-8 sm:mt-20 lg:mt-24">
      <p class="text-xs leading-5 text-gray-500">&copy;
        <?= date("Y"); ?> U.S.G.G., Inc. Tous droits réservés.
      </p>
    </div>
  </div>
</footer>

<script>

  function facebookRedirect(guide = false) {
    if (guide) {
      window.location = "fb://profile/100064866093169";
      setTimeout(function () { window.location = "https://www.facebook.com/GuidesGosselies"; }, 25);
    } else {
      window.location = "fb://profile/100057723397233";
      setTimeout(function () { window.location = "https://www.facebook.com/LesScoutsDeGosselieste003"; }, 25);
    }
  }

</script>