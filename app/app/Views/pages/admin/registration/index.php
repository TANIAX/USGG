<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Demandes d'inscription
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <?php component_open('page_header', ['title' => 'Demandes d\'inscription', 'subtitle' => 'Demandes envoyées depuis la page Inscription, les plus récentes en premier. Phase 1 : frère, sœur ou enfant d\'ancien membre ; phase 2 : les autres familles.']) ?>
         <?= component('button', ['variant' => 'secondary', 'size' => 'sm', 'block' => true, 'label' => 'Exporter (CSV)', 'attrs' => ['@click' => 'exportCsv()', ':disabled' => 'filteredRequests.length === 0']]) ?>
      <?= component_close() ?>

      <?= component('flash') ?>
      <p x-show="actionError" x-text="actionError" class="mb-4 text-sm text-red-600"></p>

      <!-- Filters -->
      <div class="mb-6 space-y-4">
         <div class="flex flex-wrap gap-2 text-sm">
            <?= component('chip', ['active_alpine' => "status === ''", 'attrs' => ['@click' => "status = ''", 'x-text' => '`Toutes (${requests.length})`']]) ?>
            <?php foreach ($statuses as $code => [$label]): ?>
               <?= component('chip', ['active_alpine' => "status === '$code'", 'attrs' => ['@click' => "status = '$code'", 'x-text' => "`" . addslashes($label) . " (\${requests.filter(r => r.status === '$code').length})`"]]) ?>
            <?php endforeach; ?>
         </div>
         <div class="flex flex-col gap-4 sm:flex-row">
            <div class="sm:w-64">
               <label for="section-filter" class="sr-only">Section</label>
               <?= component('input', ['type' => 'select', 'id' => 'section-filter', 'options' => ['' => 'Toutes les sections', 'none' => 'Sans préférence'] + $sections, 'attrs' => ['x-model' => 'section']]) ?>
            </div>
            <?= component('search', ['placeholder' => 'Enfant, parent, e-mail, localité...', 'class' => 'sm:w-80']) ?>
         </div>
      </div>

      <ul role="list" class="mb-16 divide-y divide-gray-100 rounded-lg bg-white shadow-sm ring-1 ring-gray-200" x-show="filteredRequests.length > 0">
         <template x-for="request in filteredRequests" :key="request.id">
            <li class="flex items-start gap-x-3 px-4 py-4" :class="selected.includes(request.id) ? 'bg-indigo-50/50' : ''">
               <input type="checkbox" :checked="selected.includes(request.id)" @click="toggle(request)" :aria-label="`Sélectionner ${request.child}`"
                  class="mt-1 h-4 w-4 flex-none rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
               <div class="min-w-0 flex-auto">
                  <div class="flex flex-wrap items-center gap-2">
                     <a :href="`/admin/inscriptions/${request.id}`" class="font-semibold text-gray-900 hover:text-indigo-600" x-text="request.child"></a>
                     <span class="text-sm text-gray-500" x-text="`${request.age} ans`"></span>
                     <?= component('badge', ['color' => 'outline', 'dot_alpine' => "(request.color || '#9ca3af')", 'slot' => '<span x-text="request.section || \'Sans préférence\'"></span>']) ?>
                     <?= component('badge', ['color' => 'gray', 'shape' => 'tag', 'attrs' => ['x-text' => '`Phase ${request.phase}`']]) ?>
                  </div>
                  <p class="mt-1 text-sm text-gray-600">
                     <span x-text="request.parent"></span> ·
                     <a :href="`mailto:${request.email}`" class="text-indigo-600 hover:text-indigo-500" x-text="request.email"></a> ·
                     <a :href="`tel:${request.phone}`" class="text-indigo-600 hover:text-indigo-500" x-text="request.phone"></a>
                  </p>
                  <p class="mt-1 text-xs text-gray-500">
                     <span x-text="'Reçue le ' + formatDate(request.created_at, 'short') + ' · ' + request.city"></span>
                     <span x-show="request.note" class="italic" x-text="' · Note : ' + request.note"></span>
                  </p>
               </div>
               <div class="flex flex-none flex-col items-end gap-2">
                  <?= component('badge', ['color' => 'none', 'attrs' => [':class' => 'statusClass(request.status)', 'x-text' => 'statuses[request.status][0]']]) ?>
                  <a :href="`/admin/inscriptions/${request.id}`" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Traiter</a>
               </div>
            </li>
         </template>
      </ul>
      <?= component('empty_state', ['icon' => 'users', 'class' => 'mb-16', 'title_alpine' => "requests.length ? 'Aucune demande ne correspond aux filtres' : 'Aucune demande d\\'inscription pour le moment'", 'attrs' => ['x-show' => 'filteredRequests.length === 0']]) ?>

      <?= component('selection_bar', ['noun' => ['demande sélectionnée', 'demandes sélectionnées'], 'busy' => 'busy', 'actions' => [
         ['label' => 'Liste d\'attente', 'click' => "run('status', 'waiting')", 'tone' => 'amber'],
         ['label' => 'Clôturer', 'click' => "run('status', 'refused')", 'tone' => 'green'],
         ['label' => 'Supprimer', 'click' => 'remove()', 'tone' => 'red'],
      ]]) ?>
   </div>
</div>

<script>
   const STATUS_CLASSES = {
      indigo: 'bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-200', purple: 'bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-200',
      amber: 'bg-amber-50 text-amber-800 ring-1 ring-inset ring-amber-600/20', outline: 'text-gray-700 ring-1 ring-inset ring-gray-200',
      green: 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20', gray: 'bg-gray-100 text-gray-600',
   };

   function app() {
      return {
         requests: <?= $requests ?>,
         statuses: <?= js_data($statuses) ?>,
         status: '',
         section: '',
         search: '',
         selected: [],
         busy: false,
         actionError: '',

         get filteredRequests() {
            const search = this.search.trim().toLowerCase();
            return this.requests.filter(request =>
               (!this.status || request.status === this.status)
               && (!this.section || (this.section === 'none' ? !request.section_id : String(request.section_id) === this.section))
               && (!search || [request.child, request.totem || '', request.parent, request.email, request.city].some(text => text.toLowerCase().includes(search))));
         },

         statusClass(status) {
            return STATUS_CLASSES[(this.statuses[status] || [])[1]] || STATUS_CLASSES.gray;
         },

         toggle(request) {
            this.selected = this.selected.includes(request.id) ? this.selected.filter(id => id !== request.id) : [...this.selected, request.id];
         },

         remove() {
            if (confirm(`Supprimer définitivement ${this.selected.length > 1 ? 'ces ' + this.selected.length + ' demandes' : 'cette demande'} ? Les données de la famille seront effacées.`))
               this.run('delete');
         },

         async run(action, status = '') {
            this.busy = true;
            this.actionError = '';
            try {
               const json = await requestJson('/admin/inscriptions/bulk', { action: action, status: status, ids: this.selected });
               this.requests = json.requests;
               this.selected = [];
            } catch (error) {
               this.actionError = error.message || 'L\'action n\'a pas pu être effectuée. Rechargez la page et réessayez.';
            } finally {
               this.busy = false;
            }
         },

         // Spreadsheet of the displayed requests (Excel reads the ";" and the UTF-8 mark)
         exportCsv() {
            const columns = [['child', 'Enfant'], ['age', 'Âge'], ['section', 'Section'], ['phase', 'Phase'], ['status', 'Statut'], ['parent', 'Parent'], ['email', 'E-mail'], ['phone', 'Téléphone'], ['city', 'Localité'], ['created_at', 'Reçue le'], ['note', 'Note']];
            const cell = value => `"${String(value ?? '').replace(/"/g, '""')}"`;
            const rows = this.filteredRequests.map(request => columns.map(([key]) => cell(key === 'status' ? this.statuses[request.status][0] : request[key])).join(';'));
            const csv = '﻿' + [columns.map(([, label]) => cell(label)).join(';'), ...rows].join('\r\n');
            const link = document.createElement('a');
            link.href = URL.createObjectURL(new Blob([csv], { type: 'text/csv;charset=utf-8' }));
            link.download = `demandes-inscription-${new Date().toISOString().slice(0, 10)}.csv`;
            link.click();
         },
      }
   }
</script>
<?= $this->endSection() ?>
