<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Galerie photos
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <?php component_open('page_header', ['title' => 'Galerie photos', 'subtitle' => 'Vous gérez les albums : ' . implode(' et ', array_map('strtolower', $branches)) . '.']) ?>
         <div class="flex gap-x-3">
            <?= component('button', ['label' => 'Voir la galerie', 'href' => '/galerie', 'variant' => 'secondary', 'size' => 'sm', 'block' => true, 'attrs' => ['target' => '_blank']]) ?>
            <?= component('button', ['label' => 'Nouvel album', 'href' => '/admin/galerie/create', 'size' => 'sm', 'block' => true]) ?>
         </div>
      <?= component_close() ?>

      <?= component('flash') ?>

      <div x-show="pagination.total > 0 || search">
         <?= component('search', ['label' => 'Recherche rapide', 'placeholder' => 'Rechercher un album', 'class' => 'mb-6 sm:w-80']) ?>
      </div>

      <ul id="list-top" role="list" class="grid scroll-mt-8 grid-cols-1 gap-6 transition-opacity sm:grid-cols-2 lg:grid-cols-3" :class="listLoading ? 'opacity-60' : ''">
         <template x-for="album in items" :key="album.id">
            <li class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-200">
               <a :href="`/admin/galerie/album/${album.id}`" class="block group">
                  <div class="aspect-[4/3] bg-gray-100">
                     <?= component('cover_carousel', ['ids_alpine' => 'album.cover_ids', 'class' => 'group-hover:opacity-90', 'attrs' => ['x-show' => 'album.cover_ids.length > 0']]) ?>
                     <div x-show="album.cover_ids.length === 0" class="flex h-full items-center justify-center text-sm text-gray-400">Aucune photo</div>
                  </div>
                  <div class="p-4">
                     <div class="flex items-center justify-between gap-x-2">
                        <h2 class="truncate font-semibold text-gray-900 group-hover:text-indigo-600" x-text="album.title"></h2>
                        <?= component('badge', ['class' => 'flex-none', 'attrs' => ['x-text' => 'branchLabel(album.branch)']]) ?>
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

      <?= component('pagination', ['noun' => ['album', 'albums'], 'class' => 'mt-8 mb-16']) ?>
      <?php component_open('empty_state', ['icon' => 'photo', 'title_alpine' => "search ? 'Aucun album ne correspond à la recherche' : 'Aucun album'", 'attrs' => ['x-show' => 'items.length === 0']]) ?>
         <div class="mt-6" x-show="!search"><?= component('button', ['label' => 'Créer un album', 'href' => '/admin/galerie/create', 'size' => 'sm']) ?></div>
      <?= component_close() ?>
   </div>
</div>

<script>
   function app() {
      return listApp('/admin/galerie', <?= $list ?>, {
         branchLabel(branch) {
            return branch === 'GUIDE' ? 'Guides' : 'Scouts';
         },

         photoCount(count) {
            return count + ' photo' + (count > 1 ? 's' : '');
         },
      });
   }
</script>
<?= $this->endSection() ?>
