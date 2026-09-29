<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Demandes d'inscription
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <?php component_open('page_header', ['title' => 'Demandes d\'inscription', 'subtitle' => 'Demandes envoyées depuis la page Inscription, les plus récentes en premier. Phase 1 : frère, sœur ou enfant d\'ancien membre ; phase 2 : les autres familles.']) ?>
         <?= component('button', ['variant' => 'secondary', 'size' => 'sm', 'block' => true, 'label' => 'Exporter (CSV)', 'href' => '#', 'attrs' => [':href' => 'exportUrl', ':class' => "pagination.total === 0 ? 'pointer-events-none opacity-50' : ''"]]) ?>
      <?= component_close() ?>

      <?= component('flash') ?>
      <p x-show="actionError" x-text="actionError" class="mb-4 text-sm text-red-600"></p>

      <!-- Filters -->
      <div class="mb-6 space-y-4">
         <div class="flex flex-wrap gap-2 text-sm">
            <?= component('chip', ['active_alpine' => "status === ''", 'attrs' => ['@click' => "status = ''", 'x-text' => '`Toutes (${totalCount})`']]) ?>
            <?php foreach ($statuses as $code => [$label]): ?>
               <?= component('chip', ['active_alpine' => "status === '$code'", 'attrs' => ['@click' => "status = '$code'", 'x-text' => "`" . addslashes($label) . " (\${counts['$code'] || 0})`"]]) ?>
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

      <ul id="list-top" role="list" class="scroll-mt-8 divide-y divide-gray-100 rounded-lg bg-white shadow-sm ring-1 ring-gray-200 transition-opacity" :class="listLoading ? 'opacity-60' : ''" x-show="items.length > 0">
         <template x-for="request in items" :key="request.id">
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
      <?= component('pagination', ['noun' => ['demande', 'demandes'], 'class' => 'mt-6 mb-16']) ?>
      <?= component('empty_state', ['icon' => 'users', 'class' => 'mb-16', 'title_alpine' => "totalCount ? 'Aucune demande ne correspond aux filtres' : 'Aucune demande d\\'inscription pour le moment'", 'attrs' => ['x-show' => 'items.length === 0']]) ?>

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
      return listApp('/admin/inscriptions', <?= $list ?>, {
         statuses: <?= js_data($statuses) ?>,
         selected: [],
         busy: false,
         actionError: '',

         get totalCount() {
            return Object.values(this.counts).reduce((sum, count) => sum + count, 0);
         },

         // Spreadsheet of all the requests matching the filters (made by the server)
         get exportUrl() {
            const params = this.listParams(1);
            params.set('format', 'csv');
            return `/admin/inscriptions?${params}`;
         },

         afterLoad() {
            this.selected = this.selected.filter(id => this.items.some(request => request.id === id));
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
               await requestJson('/admin/inscriptions/bulk', { action: action, status: status, ids: this.selected });
               this.selected = [];
               await this.loadList();
            } catch (error) {
               this.actionError = error.message || 'L\'action n\'a pas pu être effectuée. Rechargez la page et réessayez.';
            } finally {
               this.busy = false;
            }
         },
      });
   }
</script>
<?= $this->endSection() ?>
