<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Agenda
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <?php component_open('page_header', ['title' => 'Agenda']) ?>
         <div class="flex gap-x-3">
            <?= component('button', ['label' => 'Voir l\'agenda public', 'href' => '/en-pratique/agenda', 'variant' => 'secondary', 'size' => 'sm', 'block' => true, 'attrs' => ['target' => '_blank']]) ?>
            <?= component('button', ['label' => 'Ajouter un événement', 'href' => '/admin/agenda/create', 'size' => 'sm', 'block' => true]) ?>
         </div>
      <?= component_close() ?>

      <?= component('flash') ?>

      <!-- Period & search -->
      <div class="mb-6 sm:flex sm:items-end sm:justify-between gap-4">
         <nav class="flex gap-x-2" aria-label="Période">
            <?php foreach (['' => 'À venir', 'passes' => 'Passés'] as $code => $label): ?>
               <button type="button" @click="periode = '<?= $code ?>'" :aria-pressed="(periode === '<?= $code ?>').toString()"
                  class="rounded-md px-3 py-2 text-sm font-medium" :class="periode === '<?= $code ?>' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700'"><?= $label ?></button>
            <?php endforeach; ?>
         </nav>
         <?= component('search', ['label' => 'Recherche rapide', 'placeholder' => 'Rechercher (titre, lieu, section)', 'class' => 'mt-4 sm:mt-0 sm:w-80']) ?>
      </div>

      <p x-show="periode === 'passes'" class="mb-4 text-sm text-gray-500">Les événements terminés ne peuvent plus être modifiés, mais ils peuvent être supprimés.</p>

      <!-- Table -->
      <div id="list-top" x-show="items.length > 0" class="-mx-4 scroll-mt-8 transition-opacity sm:-mx-0" :class="listLoading ? 'opacity-60' : ''">
         <table class="min-w-full divide-y divide-gray-300">
            <thead>
               <tr>
                  <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Événement</th>
                  <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">Date</th>
                  <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">Sections</th>
                  <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0"><span class="sr-only">Actions</span></th>
               </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
               <template x-for="event in items" :key="event.id">
                  <tr>
                     <td class="w-full max-w-0 py-4 pl-4 pr-3 text-sm sm:w-auto sm:max-w-none sm:pl-0">
                        <p class="font-medium text-gray-900" x-text="event.title"></p>
                        <p class="mt-1 text-gray-500" x-show="event.location" x-text="event.location"></p>
                        <p class="mt-1 text-xs text-gray-400" x-text="'Par ' + event.author.name + (event.image_url ? ' · avec image' : '')"></p>
                        <!-- Mobile-only information -->
                        <dl class="font-normal lg:hidden">
                           <dt class="sr-only">Date</dt>
                           <dd class="mt-1 text-gray-700" x-text="formatEventPeriod(event)"></dd>
                           <dt class="sr-only sm:hidden">Sections</dt>
                           <dd class="mt-1 text-gray-500 sm:hidden" x-text="event.sections.map(section => section.name).join(', ')"></dd>
                        </dl>
                     </td>
                     <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell" x-text="formatEventPeriod(event)"></td>
                     <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                        <div class="flex flex-wrap gap-1.5">
                           <template x-for="section in event.sections" :key="section.id">
                              <?= component('section_tag', ['alpine' => 'section']) ?>
                           </template>
                        </div>
                     </td>
                     <td class="py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0 whitespace-nowrap">
                        <a :href="`/en-pratique/agenda?event=${event.id}`" target="_blank" class="text-gray-600 hover:text-gray-900">Voir</a>
                        <a x-show="periode !== 'passes'" :href="`/admin/agenda/edit/${event.id}`" class="ml-4 text-indigo-600 hover:text-indigo-900">Modifier</a>
                        <form method="POST" :action="`/admin/agenda/delete/${event.id}`" class="inline"
                           @submit="if (!confirm(`Êtes-vous sûr de vouloir supprimer l'événement « ${event.title} » ?`)) $event.preventDefault()">
                           <button type="submit" class="ml-4 text-red-600 hover:text-red-900">Supprimer</button>
                        </form>
                     </td>
                  </tr>
               </template>
            </tbody>
         </table>
      </div>

      <?= component('pagination', ['noun' => ['événement', 'événements'], 'class' => 'mt-6 mb-16']) ?>
      <?php component_open('empty_state', ['icon' => 'calendar', 'title_alpine' => "search ? 'Aucun événement ne correspond à la recherche' : (periode === 'passes' ? 'Aucun événement passé' : 'Aucun événement à venir')", 'attrs' => ['x-show' => 'items.length === 0']]) ?>
         <div class="mt-6" x-show="periode !== 'passes'"><?= component('button', ['label' => 'Créer un événement', 'href' => '/admin/agenda/create', 'size' => 'sm']) ?></div>
      <?= component_close() ?>
   </div>
</div>

<script>
   function app() {
      return listApp('/admin/agenda', <?= $list ?>);
   }
</script>
<?= $this->endSection() ?>
