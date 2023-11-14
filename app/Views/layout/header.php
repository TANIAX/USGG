<header>
  <div class="flex items-center gap-x-6 bg-blue-800 px-6 py-2.5 sm:px-3.5 sm:before:flex-1">
    <div class="flex flex-1 justify-end md:px-12">
      <!-- Login -->
      <a href="/auth/login" class="px-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="w-4 h-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
        </svg>
      </a>

       <break class="border border-white" style="height: 16px;"></break>
      
       <!-- Contact -->
      <a href="/contact" class="text-white hover:text-gray-200 px-2 uppercase text-xs tracking-widest sofia font-bold">contact</a>

    </div>
  </div>
</header>

<?= $this->include('layout\menu.php') ?>