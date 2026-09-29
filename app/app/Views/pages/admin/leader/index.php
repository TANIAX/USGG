<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Responsables de section
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <!-- Title -->
      <div class="sm:flex justify-start sm:items-center mt-12 mb-8 border-b py-4">
         <div class="sm:flex-auto">
            <h1 class="font-semibold text-4xl leading-tight text-gray-900">Responsables de section</h1>
            <p class="mt-1 text-sm text-gray-500">Ils sont présentés sur la page d'accueil, dans l'ordre des sections puis dans l'ordre choisi ici.</p>
         </div>
         <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none flex gap-x-3">
            <a href="/#responsables" target="_blank"
               class="block rounded-md bg-white px-3 py-2 text-center text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
               Voir l'accueil
            </a>
            <a href="/admin/responsables/create"
               class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
               Ajouter un responsable
            </a>
         </div>
      </div>

      <?= $this->include('pages/admin/messages') ?>
      <p x-show="error" x-text="error" class="mb-4 text-sm text-red-600"></p>

      <div class="space-y-10 pb-16">
         <template x-for="section in sections" :key="section.id">
            <section>
               <h2 class="flex items-center gap-x-3 text-lg font-semibold text-gray-900">
                  <img :src="baseUrl + section.logo" alt="" class="h-8 w-8 rounded-full bg-white object-contain ring-1 ring-gray-200">
                  <span x-text="section.name"></span>
                  <span class="text-sm font-normal text-gray-400" x-text="`(${leadersOf(section).length})`"></span>
               </h2>
               <ul role="list" class="mt-3 divide-y divide-gray-100 rounded-lg bg-white shadow-sm ring-1 ring-gray-200" x-show="leadersOf(section).length > 0">
                  <template x-for="(leader, index) in leadersOf(section)" :key="leader.id">
                     <li class="flex flex-wrap items-center gap-x-4 gap-y-2 px-4 py-3">
                        <img :src="leader.picture_url || baseUrl + 'assets/img/question-mark.jpg'" alt="" class="h-12 w-12 flex-none rounded-full bg-gray-100 object-cover">
                        <div class="min-w-0 flex-auto">
                           <p class="font-semibold text-gray-900">
                              <span x-text="leader.display_name"></span>
                              <span class="font-normal text-gray-500" x-show="leader.totem" x-text="`(${leader.firstname} ${leader.name})`"></span>
                              <span class="font-normal text-gray-500" x-show="!leader.totem" x-text="leader.name"></span>
                           </p>
                           <p class="truncate text-sm text-gray-500"><span x-text="leader.function || 'Fonction non précisée'"></span> · <span x-text="leader.email"></span></p>
                        </div>
                        <div class="flex items-center gap-x-1">
                           <button type="button" @click="move(leader, 'up')" :disabled="index === 0 || busy" title="Monter"
                              class="rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700 disabled:opacity-30">
                              <span class="sr-only">Monter</span>
                              <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd" /></svg>
                           </button>
                           <button type="button" @click="move(leader, 'down')" :disabled="index === leadersOf(section).length - 1 || busy" title="Descendre"
                              class="rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700 disabled:opacity-30">
                              <span class="sr-only">Descendre</span>
                              <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                           </button>
                           <a :href="`/admin/responsables/edit/${leader.id}`" class="ml-2 text-sm font-medium text-indigo-600 hover:text-indigo-900">Modifier</a>
                           <form method="POST" :action="`/admin/responsables/delete/${leader.id}`" class="inline"
                              @submit="if (!confirm(`Retirer ${leader.display_name} des responsables des ${section.name} ? Son compte est conservé.`)) $event.preventDefault()">
                              <button type="submit" class="ml-3 text-sm font-medium text-red-600 hover:text-red-900">Retirer</button>
                           </form>
                        </div>
                     </li>
                  </template>
               </ul>
               <p x-show="leadersOf(section).length === 0" class="mt-3 text-sm text-gray-400">Aucun responsable.</p>
            </section>
         </template>
      </div>
   </div>
</div>

<script>
   function app() {
      return {
         baseUrl: '<?= rtrim(base_url(), '/') ?>/',
         leaders: <?= $leaders ?>,
         sections: <?= $sections ?>,
         busy: false,
         error: '',

         leadersOf(section) {
            return this.leaders.filter(leader => leader.section_id === section.id);
         },

         async move(leader, direction) {
            this.busy = true;
            this.error = '';
            try {
               const data = new FormData();
               data.append('direction', direction);
               const response = await fetch(`/admin/responsables/move/${leader.id}`, { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
               const json = await response.json();
               if (!response.ok || !json.success)
                  throw new Error();
               this.leaders = json.leaders;
            } catch (error) {
               this.error = 'L\'ordre n\'a pas pu être modifié. Rechargez la page et réessayez.';
            } finally {
               this.busy = false;
            }
         },
      }
   }
</script>
<?= $this->endSection() ?>
