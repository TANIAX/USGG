<?php
use App\Helpers\SessionHelper;
use App\Helpers\NavigationHelper;

?>

<!-- 768px = "md" breakpoint of Tailwind: the desktop menu is shown from 768px, like the CSS (md:block / md:hidden) -->
<header
  x-data="{ open: false, hide_menu: ((window.innerWidth > 0) ? window.innerWidth : screen.width) >= 768 ? false : true}"
  x-effect="document.body.style.overflow = open ? 'hidden' : ''"
  @keydown.escape.window="open = false"
  @resize.window=" width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
                        if (width >= 768) {
                          open = false
                          hide_menu = false
                        } else {
                          hide_menu = true
                        }">
  <div class="flex gap-x-6 bg-blue-400 px-6 py-2.5 sm:px-3.5 sm:before:flex-1" style="background-color: #03497A;">
    <button type="button" class="sm:w-full cursor-pointer text-left md:hidden" @click="open = !open"
      :aria-expanded="open.toString()" :aria-label="open ? 'Fermer le menu' : 'Ouvrir le menu'">
      <?= component('icon', ['name' => 'bars', 'class' => 'w-6 h-6 text-white', 'attrs' => ['x-bind:class' => "open ? 'hidden' : ''"]]) ?>
      <?= component('icon', ['name' => 'x-mark', 'class' => 'w-6 h-6 text-white', 'attrs' => ['x-bind:class' => "open ? '' : 'hidden'"]]) ?>
    </button>


    <div class="flex flex-1 items-center justify-end md:px-12">
      <!-- Login -->
      <?php if (!SessionHelper::isUserConnected()): ?>
        <a href="/auth/login" class="px-2" aria-label="Se connecter">
          <?= component('icon', ['name' => 'user', 'class' => 'w-4 h-4 text-white']) ?>
        </a>
      <?php else: ?>
        <div x-data="{ open: false }" class="relative">
          <button
            @click="open = true"
            class="flex items-center text-white hover:text-gray-200 px-2 uppercase text-xs tracking-widest sofia font-bold"
            type="button">
            <span class="mr-1"><?= esc(SessionHelper::getUserConnected()->getTotem() ?: SessionHelper::getUserConnected()->getFirstname()) ?></span>
            <?= component('icon', ['name' => 'caret-down', 'class' => 'fill-current h-4 w-4']) ?>
          </button>
          <ul
            x-show="open"
            @click.away="open = false"
            class="bg-white text-gray-700 rounded shadow-lg absolute py-2 min-w-24 mt-1 right-0 z-[99]">
            <?php $roles = SessionHelper::getUserConnected()->getRolesAsStrings(); ?>
            <?php if (array_intersect($roles, NavigationHelper::ADMIN_ROLES)): ?>
              <li><a href="/admin" class="block hover:bg-gray-200 py-2 px-4 font-medium whitespace-nowrap">Tableau de bord</a></li>
              <?php foreach (NavigationHelper::adminLinks($roles) as $link): ?>
                <?php if (!empty($link['menu'])): ?>
                  <li><a href="<?= $link['href'] ?>" class="block hover:bg-gray-200 py-2 px-4 font-medium whitespace-nowrap"><?= $link['label'] ?></a></li>
                <?php endif ?>
              <?php endforeach ?>
              <li class="my-1 border-t border-gray-100"></li>
            <?php endif ?>
            <li><a href="/mon-compte" class="block hover:bg-gray-200 py-2 px-4 font-medium whitespace-nowrap">Mon compte</a></li>
            <li>
              <a href="/auth/logout" class="block hover:bg-gray-200 py-2 px-4 font-medium whitespace-nowrap">
                Déconnexion
              </a>
            </li>
          </ul>
        </div>
      <?php endif ?>

      <break class="border border-white" style="height: 16px;"></break>

      <!-- Contact -->
      <a href="/contact"
        class="text-white hover:text-gray-200 px-2 uppercase text-xs tracking-widest sofia font-bold">contact</a>

    </div>
  </div>
  <template x-if="!open && !hide_menu">
    <?= $this->include('lib/components/layout/menu.php') ?>
  </template>

  <template x-if="open">
    <?= $this->include('lib/components/layout/menu_mobile.php') ?>
  </template>
  <!-- Covers the page behind the mobile menu: a tap next to the menu closes it -->
  <div x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 z-40 bg-gray-900/50 md:hidden" aria-hidden="true"></div>
</header>