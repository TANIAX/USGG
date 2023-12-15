<?php
use App\Helpers\SessionHelper;

?>

<header
  x-data="{ open: false, hide_menu: ((window.innerWidth > 0) ? window.innerWidth : screen.width) > 768 ? false : true}"
  @resize.window=" width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
                        if (width > 768) {
                          open = false
                          hide_menu = false
                        } else {
                          hide_menu = true
                        }">
  <div class="flex gap-x-6 bg-blue-400 px-6 py-2.5 sm:px-3.5 sm:before:flex-1" style="background-color: #03497A;">
    <div class="sm:w-full cursor-pointer md:hidden" @click="open = !open">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white"
        class="w-6 h-6" x-bind:class="open ? 'hidden' : ''">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
      </svg>


      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white"
        class="w-6 h-6" x-bind:class="open ? '' : 'hidden'">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>

    </div>


    <div class="flex flex-1 items-center justify-end md:px-12">
      <!-- Login -->
      <?php if (!SessionHelper::isUserConnected()): ?>
        <a href="/auth/login" class="px-2">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white"
            class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
          </svg>
        </a>
      <?php else: ?>
        <a href="/auth/logout"
          class="text-white hover:text-gray-200 px-2 uppercase text-xs tracking-widest sofia font-bold">
          <?= SessionHelper::getUserConnected()->totem ?>
        </a>
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
</header>