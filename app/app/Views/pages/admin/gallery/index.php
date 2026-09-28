<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Galerie photos
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <!-- Title -->
      <div class="sm:flex justify-start sm:items-center mt-12 mb-8 border-b py-4">
         <div class="sm:flex-auto">
            <h1 class="font-semibold text-4xl leading-tight text-gray-900">Galerie photos</h1>
            <p class="mt-1 text-sm text-gray-500">Vous gérez les albums : <?= esc(implode(' et ', array_map('strtolower', $branches))) ?>.</p>
         </div>
         <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none flex gap-x-3">
            <a href="/galerie" target="_blank"
               class="block rounded-md bg-white px-3 py-2 text-center text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
               Voir la galerie
            </a>
            <a href="/admin/galerie/create"
               class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
               Nouvel album
            </a>
         </div>
      </div>

      <?= $this->include('pages/admin/messages') ?>

      <div class="mb-6 sm:w-80" x-show="albums.length > 0">
         <label for="search" class="sr-only">Recherche rapide</label>
         <input type="text" id="search" x-model="search" placeholder="Rechercher un album"
            class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
      </div>

      <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
         <template x-for="album in filteredAlbums" :key="album.id">
            <li class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
               <a :href="`/admin/galerie/album/${album.id}`" class="block group">
                  <div class="aspect-[4/3] bg-gray-100">
                     <div x-show="album.cover_ids.length > 0" class="relative h-full w-full group-hover:opacity-90" x-data="coverCarousel(album.cover_ids)">
                        <template x-for="(id, index) in ids" :key="id">
                            <template x-if="loaded.includes(index)">
                                <img :src="url(id)" alt="" loading="lazy"
                                    class="absolute inset-0 h-full w-full object-cover transition-opacity duration-1000"
                                    :class="index === current ? 'opacity-100' : 'opacity-0'">
                            </template>
                        </template>
                        <!-- Position in the carousel -->
                        <div x-show="ids.length > 1" class="absolute inset-x-0 bottom-2 flex justify-center gap-1.5" aria-hidden="true">
                            <template x-for="(id, index) in ids" :key="id">
                                <span class="h-1.5 w-1.5 rounded-full shadow transition-colors" :class="index === current ? 'bg-white' : 'bg-white/50'"></span>
                            </template>
                        </div>
                     </div>
                     <div x-show="album.cover_ids.length === 0" class="flex h-full items-center justify-center text-sm text-gray-400">Aucune photo</div>
                  </div>
                  <div class="p-4">
                     <div class="flex items-center justify-between gap-x-2">
                        <h2 class="truncate font-semibold text-gray-900 group-hover:text-indigo-600" x-text="album.title"></h2>
                        <span class="flex-none rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600" x-text="branchLabel(album.branch)"></span>
                     </div>
                     <p class="mt-1 text-sm text-gray-500">
                        <span x-text="photoCount(album.photo_count)"></span>
                        <span x-show="album.private_count > 0" x-text="` · ${album.private_count} privée${album.private_count > 1 ? 's' : ''}`"></span>
                        <span x-show="album.album_date" x-text="album.album_date ? ' · ' + formatDate(album.album_date) : ''"></span>
                     </p>
                  </div>
               </a>
            </li>
         </template>
      </ul>

      <!-- Empty state -->
      <div x-show="filteredAlbums.length === 0" class="py-10 text-center">
         <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
         </svg>
         <h3 class="mt-2 text-sm font-semibold text-gray-900" x-text="search ? 'Aucun album ne correspond à la recherche' : 'Aucun album'"></h3>
         <div class="mt-6" x-show="!search">
            <a href="/admin/galerie/create" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
               Créer un album
            </a>
         </div>
      </div>
   </div>
</div>

<script>
   function app() {
      return {
         albums: <?= $albums ?>,
         search: '',

         get filteredAlbums() {
            const search = this.search.trim().toLowerCase();
            return search ? this.albums.filter(album => album.title.toLowerCase().includes(search)) : this.albums;
         },

         branchLabel(branch) {
            return branch === 'GUIDE' ? 'Guides' : 'Scouts';
         },

         photoCount(count) {
            return count + ' photo' + (count > 1 ? 's' : '');
         },

         formatDate(date) {
            return parseEventDate(date).toLocaleDateString('fr-BE', { day: 'numeric', month: 'long', year: 'numeric' });
         },
      }
   }
</script>
<?= $this->endSection() ?>
