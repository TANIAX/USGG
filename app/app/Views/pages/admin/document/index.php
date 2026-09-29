<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Documents
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <?php component_open('page_header', ['title' => 'Documents', 'subtitle' => 'Un document inactif n\'est pas visible par le public : il reste disponible ici.']) ?>
         <?= component('button', ['label' => 'Ajouter un document', 'href' => '/admin/document/create', 'size' => 'sm', 'block' => true]) ?>
      <?= component_close() ?>

      <?= component('flash') ?>
      <div x-show="actionErrors.length" class="mb-4 rounded-md bg-red-50 p-3 text-sm text-red-700">
         <template x-for="error in actionErrors"><p x-text="error"></p></template>
      </div>

      <!-- Filters -->
      <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
         <div class="flex flex-wrap gap-2 text-sm">
            <?php if (count($types) > 1): ?>
               <?= component('chip', ['label' => 'Toutes les unités', 'active_alpine' => "type === ''", 'attrs' => ['@click' => "type = ''"]]) ?>
               <?php foreach ($types as $code => $label): ?>
                  <?= component('chip', ['label' => $label, 'active_alpine' => "type === '$code'", 'attrs' => ['@click' => "type = '$code'"]]) ?>
               <?php endforeach; ?>
               <span class="mx-1 border-l border-gray-300"></span>
            <?php endif; ?>
            <?php foreach (['' => 'Tous', 'active' => 'Actifs', 'inactive' => 'Inactifs'] as $code => $label): ?>
               <?= component('chip', ['tone' => 'dark', 'active_alpine' => "status === '$code'", 'attrs' => ['@click' => "status = '$code'",
                  'x-text' => "'$label (' + (counts['$code'] ?? 0) + ')'"], 'label' => $label]) ?>
            <?php endforeach; ?>
         </div>
         <?= component('search', ['placeholder' => 'Rechercher un document', 'class' => 'lg:w-80']) ?>
      </div>

      <!-- Table -->
      <div id="list-top" x-show="items.length > 0" class="-mx-4 scroll-mt-8 transition-opacity sm:-mx-0" :class="listLoading ? 'opacity-60' : ''">
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
               <template x-for="file in items" :key="file.id">
                  <tr :class="selected.includes(file.id) ? 'bg-indigo-50/50' : ''">
                     <td class="px-2">
                        <input type="checkbox" :checked="selected.includes(file.id)" @click="toggle(file)" :aria-label="`Sélectionner ${file.name}`"
                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                     </td>
                     <td class="max-w-0 py-4 pr-3 text-sm sm:max-w-none">
                        <p class="break-words font-medium text-gray-900" :class="file.is_active ? '' : 'text-gray-500'" x-text="file.name"></p>
                        <p class="mt-1 text-xs text-gray-500">
                           <span class="rounded bg-gray-100 px-1.5 py-0.5 font-medium uppercase" x-text="file.extension || '?'"></span>
                           <span x-show="file.size" x-text="formatFileSize(file.size)"></span>
                           <span class="md:hidden" x-text="'· ' + (types[file.file_type] || file.file_type)"></span>
                        </p>
                     </td>
                     <td class="hidden px-3 py-4 text-sm text-gray-500 md:table-cell" x-text="types[file.file_type] || file.file_type"></td>
                     <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell" x-text="formatDate(file.created_at, 'short')"></td>
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

      <?= component('pagination', ['noun' => ['document', 'documents'], 'class' => 'mt-6 mb-16']) ?>
      <?= component('empty_state', ['icon' => 'document', 'title_alpine' => "counts[''] || search ? 'Aucun document ne correspond aux filtres' : 'Aucun document'", 'attrs' => ['x-show' => 'items.length === 0']]) ?>

      <!-- Actions on the selection -->
      <?= component('selection_bar', ['noun' => ['document sélectionné', 'documents sélectionnés'], 'busy' => 'busy', 'actions' => [
         ['label' => 'Activer', 'click' => "run('activate', selected)", 'tone' => 'green'],
         ['label' => 'Désactiver', 'click' => "run('deactivate', selected)", 'tone' => 'amber'],
         ['label' => 'Supprimer', 'click' => 'removeSelected()', 'tone' => 'red'],
      ]]) ?>
   </div>
</div>

<script>
   function app() {
      return listApp('/admin/document', <?= $list ?>, {
         types: <?= js_data($types) ?>,
         selected: [],
         selectedNames: {},
         busy: false,
         actionErrors: [],

         // The selection is kept when changing of page (names kept for the confirmation)
         get allSelected() {
            return this.items.length > 0 && this.items.every(file => this.selected.includes(file.id));
         },

         toggle(file) {
            this.selectedNames[file.id] = file.name;
            this.selected = this.selected.includes(file.id) ? this.selected.filter(id => id !== file.id) : [...this.selected, file.id];
         },

         toggleAll() {
            const ids = this.items.map(file => file.id);
            this.items.forEach(file => this.selectedNames[file.id] = file.name);
            this.selected = this.allSelected ? this.selected.filter(id => !ids.includes(id)) : [...new Set([...this.selected, ...ids])];
         },

         removeSelected() {
            this.remove(this.selected.map(id => ({ id: id, name: this.selectedNames[id] || '' })));
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
               const json = await requestJson('/admin/document/bulk', { action: action, ids: ids });
               this.actionErrors = json.errors;
               this.selected = this.selected.filter(id => !json.ids.includes(id));
               await this.loadList();
            } catch (error) {
               this.actionErrors = [error.message || 'L\'action n\'a pas pu être effectuée. Rechargez la page et réessayez.'];
            } finally {
               this.busy = false;
            }
         },
      });
   }
</script>
<?= $this->endSection() ?>
