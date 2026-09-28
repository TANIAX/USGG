<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Agenda
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <!-- Title -->
      <div class="sm:flex justify-start sm:items-center mt-12 mb-8 border-b py-4">
         <div class="sm:flex-auto">
            <h1 class="font-semibold text-4xl leading-6 text-gray-900">Agenda</h1>
         </div>
         <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none flex gap-x-3">
            <a href="/en-pratique/agenda" target="_blank"
               class="block rounded-md bg-white px-3 py-2 text-center text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
               Voir l'agenda public
            </a>
            <a href="/admin/agenda/create"
               class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
               Ajouter un événement
            </a>
         </div>
      </div>

      <?= $this->include('pages/admin/messages') ?>

      <!-- Period & search -->
      <div class="mb-6 sm:flex sm:items-end sm:justify-between gap-4">
         <nav class="flex gap-x-2" aria-label="Période">
            <a href="/admin/agenda"
               class="rounded-md px-3 py-2 text-sm font-medium <?= $past ? 'text-gray-500 hover:text-gray-700' : 'bg-indigo-100 text-indigo-700' ?>">
               À venir
            </a>
            <a href="/admin/agenda?periode=passes"
               class="rounded-md px-3 py-2 text-sm font-medium <?= $past ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700' ?>">
               Passés
            </a>
         </nav>
         <div class="mt-4 sm:mt-0 sm:w-80">
            <label for="search" class="sr-only">Recherche rapide</label>
            <input type="text" id="search" x-model="search" placeholder="Rechercher (titre, lieu, section)"
               class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
         </div>
      </div>

      <?php if ($past): ?>
         <p class="mb-4 text-sm text-gray-500">Les événements terminés ne peuvent plus être modifiés, mais ils peuvent être supprimés.</p>
      <?php endif; ?>

      <!-- Table -->
      <div x-show="filteredEvents.length > 0" class="-mx-4 sm:-mx-0">
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
               <template x-for="event in filteredEvents" :key="event.id">
                  <tr>
                     <td class="w-full max-w-0 py-4 pl-4 pr-3 text-sm sm:w-auto sm:max-w-none sm:pl-0">
                        <p class="font-medium text-gray-900" x-text="event.title"></p>
                        <p class="mt-1 text-gray-500" x-show="event.location" x-text="event.location"></p>
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
                              <span class="inline-flex items-center gap-x-1.5 rounded-full px-2 py-0.5 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-200">
                                 <span class="h-1.5 w-1.5 rounded-full" :style="`background-color: ${section.color}`"></span>
                                 <span x-text="section.name"></span>
                              </span>
                           </template>
                        </div>
                     </td>
                     <td class="py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0 whitespace-nowrap">
                        <a :href="`/en-pratique/agenda?event=${event.id}`" target="_blank" class="text-gray-600 hover:text-gray-900">Voir</a>
                        <?php if (!$past): ?>
                           <a :href="`/admin/agenda/edit/${event.id}`" class="ml-4 text-indigo-600 hover:text-indigo-900">Modifier</a>
                        <?php endif; ?>
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

      <!-- Empty state -->
      <div x-show="filteredEvents.length == 0" class="py-10 text-center">
         <svg class="mx-auto h-12 w-12 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd"
               d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z"
               clip-rule="evenodd" />
         </svg>
         <h3 class="mt-2 text-sm font-semibold text-gray-900" x-text="search ? 'Aucun événement ne correspond à la recherche' : '<?= $past ? 'Aucun événement passé' : 'Aucun événement à venir' ?>'"></h3>
         <?php if (!$past): ?>
            <div class="mt-6">
               <a href="/admin/agenda/create"
                  class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                  Créer un événement
               </a>
            </div>
         <?php endif; ?>
      </div>
   </div>
</div>

<script>
   function app() {
      return {
         events: <?= $events ?>,
         search: '',

         get filteredEvents() {
            const search = this.search.trim().toLowerCase();
            if (!search)
               return this.events;
            return this.events.filter(event => [event.title, event.location || '', ...event.sections.map(section => section.name)]
               .some(text => text.toLowerCase().includes(search)));
         },
      }
   }
</script>
<?= $this->endSection() ?>
