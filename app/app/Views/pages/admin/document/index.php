<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Documents
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <!-- Title -->
      <div class="sm:flex justify-start sm:items-center mt-12 mb-8 border-b py-4">
         <div class="sm:flex-auto">
            <h1 class="font-semibold text-4xl leading-tight text-gray-900">Documents</h1>
            <p class="mt-1 text-sm text-gray-500">Un document inactif n'est pas visible par le public : il reste disponible ici.</p>
         </div>
         <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="/admin/document/create" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
               Ajouter un document
            </a>
         </div>
      </div>

      <?= $this->include('pages/admin/messages') ?>
      <div x-show="actionErrors.length" class="mb-4 rounded-md bg-red-50 p-3 text-sm text-red-700">
         <template x-for="error in actionErrors"><p x-text="error"></p></template>
      </div>

      <!-- Filters -->
      <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
         <div class="flex flex-wrap gap-2 text-sm">
            <template x-if="Object.keys(types).length > 1">
               <div class="flex flex-wrap gap-2">
                  <button type="button" @click="type = ''" class="rounded-full px-3 py-1 font-medium ring-1 ring-inset" :class="type === '' ? 'bg-indigo-600 text-white ring-indigo-600' : 'bg-white text-gray-700 ring-gray-300'">Toutes les unités</button>
                  <template x-for="(label, code) in types" :key="code">
                     <button type="button" @click="type = code" class="rounded-full px-3 py-1 font-medium ring-1 ring-inset" :class="type === code ? 'bg-indigo-600 text-white ring-indigo-600' : 'bg-white text-gray-700 ring-gray-300'" x-text="label"></button>
                  </template>
                  <span class="mx-1 border-l border-gray-300"></span>
               </div>
            </template>
            <template x-for="option in [['', 'Tous'], ['active', 'Actifs'], ['inactive', 'Inactifs']]" :key="option[0]">
               <button type="button" @click="status = option[0]" class="rounded-full px-3 py-1 font-medium ring-1 ring-inset"
                  :class="status === option[0] ? 'bg-gray-900 text-white ring-gray-900' : 'bg-white text-gray-700 ring-gray-300'"
                  x-text="option[1] + (option[0] ? ` (${files.filter(f => f.is_active === (option[0] === 'active')).length})` : '')"></button>
            </template>
         </div>
         <div class="lg:w-80">
            <label for="search" class="sr-only">Recherche</label>
            <input type="text" id="search" x-model="search" placeholder="Rechercher un document"
               class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
         </div>
      </div>

      <!-- Table -->
      <div x-show="filteredFiles.length > 0" class="-mx-4 sm:-mx-0">
         <table class="min-w-full divide-y divide-gray-300">
            <thead>
               <tr>
                  <th scope="col" class="w-10 px-2">
                     <input type="checkbox" :checked="allSelected" @click="toggleAll()" aria-label="Tout sélectionner"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                  </th>
                  <th scope="col" class="py-3.5 pr-3 text-left text-sm font-semibold text-gray-900">Document</th>
                  <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 md:table-cell">Unité</th>
                  <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">Ajouté le</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Visibilité</th>
                  <th scope="col" class="py-3.5 pl-3"><span class="sr-only">Actions</span></th>
               </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
               <template x-for="file in filteredFiles" :key="file.id">
                  <tr :class="selected.includes(file.id) ? 'bg-indigo-50/50' : ''">
                     <td class="px-2">
                        <input type="checkbox" :checked="selected.includes(file.id)" @click="toggle(file)" :aria-label="`Sélectionner ${file.name}`"
                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                     </td>
                     <td class="max-w-0 py-4 pr-3 text-sm sm:max-w-none">
                        <p class="break-words font-medium text-gray-900" :class="file.is_active ? '' : 'text-gray-500'" x-text="file.name"></p>
                        <p class="mt-1 text-xs text-gray-500">
                           <span class="rounded bg-gray-100 px-1.5 py-0.5 font-medium uppercase" x-text="file.extension || '?'"></span>
                           <span x-show="file.size" x-text="formatSize(file.size)"></span>
                           <span class="md:hidden" x-text="'· ' + (types[file.file_type] || file.file_type)"></span>
                        </p>
                     </td>
                     <td class="hidden px-3 py-4 text-sm text-gray-500 md:table-cell" x-text="types[file.file_type] || file.file_type"></td>
                     <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell" x-text="formatDate(file.created_at)"></td>
                     <td class="px-3 py-4 text-sm">
                        <button type="button" @click="run(file.is_active ? 'deactivate' : 'activate', [file.id])" :disabled="busy"
                           class="inline-flex items-center gap-x-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset"
                           :class="file.is_active ? 'bg-green-50 text-green-700 ring-green-600/20 hover:bg-green-100' : 'bg-gray-100 text-gray-600 ring-gray-300 hover:bg-gray-200'"
                           :title="file.is_active ? 'Visible par le public : cliquer pour le masquer' : 'Masqué au public : cliquer pour le publier'">
                           <span class="h-1.5 w-1.5 rounded-full" :class="file.is_active ? 'bg-green-500' : 'bg-gray-400'"></span>
                           <span x-text="file.is_active ? 'Actif' : 'Inactif'"></span>
                        </button>
                     </td>
                     <td class="whitespace-nowrap py-4 pl-3 text-right text-sm font-medium">
                        <a :href="`/admin/document/download/${file.id}`" class="text-gray-600 hover:text-gray-900">Télécharger</a>
                        <a :href="`/admin/document/edit/${file.id}`" class="ml-4 text-indigo-600 hover:text-indigo-900">Modifier</a>
                        <button type="button" @click="remove([file])" :disabled="busy" class="ml-4 text-red-600 hover:text-red-900">Supprimer</button>
                     </td>
                  </tr>
               </template>
            </tbody>
         </table>
      </div>

      <!-- Empty state -->
      <div x-show="filteredFiles.length === 0" class="py-10 text-center">
         <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
         </svg>
         <h3 class="mt-2 text-sm font-semibold text-gray-900" x-text="files.length ? 'Aucun document ne correspond aux filtres' : 'Aucun document'"></h3>
      </div>

      <!-- Actions on the selection -->
      <div x-show="selected.length > 0" x-transition class="fixed inset-x-0 bottom-4 z-40 flex justify-center px-4">
         <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 rounded-lg bg-gray-900 px-4 py-3 text-sm text-white shadow-xl">
            <span class="font-semibold" x-text="`${selected.length} document${selected.length > 1 ? 's' : ''} sélectionné${selected.length > 1 ? 's' : ''}`"></span>
            <button type="button" @click="run('activate', selected)" :disabled="busy" class="font-medium text-green-300 hover:text-green-200">Activer</button>
            <button type="button" @click="run('deactivate', selected)" :disabled="busy" class="font-medium text-amber-300 hover:text-amber-200">Désactiver</button>
            <button type="button" @click="remove(files.filter(f => selected.includes(f.id)))" :disabled="busy" class="font-medium text-red-300 hover:text-red-200">Supprimer</button>
            <button type="button" @click="selected = []" class="text-gray-400 hover:text-white">Annuler</button>
         </div>
      </div>
   </div>
</div>

<script>
   function app() {
      return {
         files: <?= $files ?>,
         types: <?= json_encode($types, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
         type: '',
         status: '',
         search: '',
         selected: [],
         busy: false,
         actionErrors: [],

         get filteredFiles() {
            const search = this.search.trim().toLowerCase();
            return this.files.filter(file =>
               (!this.type || file.file_type === this.type)
               && (!this.status || file.is_active === (this.status === 'active'))
               && (!search || file.name.toLowerCase().includes(search)));
         },

         get allSelected() {
            return this.filteredFiles.length > 0 && this.filteredFiles.every(file => this.selected.includes(file.id));
         },

         toggle(file) {
            this.selected = this.selected.includes(file.id) ? this.selected.filter(id => id !== file.id) : [...this.selected, file.id];
         },

         toggleAll() {
            const ids = this.filteredFiles.map(file => file.id);
            this.selected = this.allSelected ? this.selected.filter(id => !ids.includes(id)) : [...new Set([...this.selected, ...ids])];
         },

         remove(files) {
            const message = files.length > 1 ? `Supprimer définitivement ces ${files.length} documents ?` : `Supprimer définitivement le document « ${files[0].name} » ?`;
            if (confirm(message))
               this.run('delete', files.map(file => file.id));
         },

         async run(action, ids) {
            this.busy = true;
            this.actionErrors = [];
            try {
               const data = new FormData();
               data.append('action', action);
               ids.forEach(id => data.append('ids[]', id));
               const response = await fetch('/admin/document/bulk', { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
               const json = await response.json();
               if (!response.ok || !json.success)
                  throw new Error(json.message);
               this.files = json.files;
               this.actionErrors = json.errors;
               this.selected = this.selected.filter(id => !json.ids.includes(id) && this.files.some(file => file.id === id));
            } catch (error) {
               this.actionErrors = [error.message || 'L\'action n\'a pas pu être effectuée. Rechargez la page et réessayez.'];
            } finally {
               this.busy = false;
            }
         },

         formatSize(bytes) {
            return bytes > 1048576 ? (bytes / 1048576).toFixed(1).replace('.', ',') + ' Mo' : Math.max(1, Math.round(bytes / 1024)) + ' Ko';
         },

         formatDate(date) {
            return date ? parseEventDate(date).toLocaleDateString('fr-BE', { day: 'numeric', month: 'short', year: 'numeric' }) : '';
         },
      }
   }
</script>
<?= $this->endSection() ?>
