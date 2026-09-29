<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Historique
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <?= component('page_header', ['title' => 'Historique', 'subtitle' => 'Les ' . $limit . ' dernières actions des administrateurs : créations, modifications, suppressions et envois.']) ?>

      <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end">
         <div class="lg:w-56">
            <label for="subject" class="block text-sm font-medium leading-6 text-gray-900">Élément</label>
            <?= component('input', ['type' => 'select', 'id' => 'subject', 'class' => 'mt-2', 'options' => ['' => 'Tous'] + array_combine($subjects, $subjects), 'attrs' => ['x-model' => 'subject']]) ?>
         </div>
         <div class="lg:w-56">
            <label for="user" class="block text-sm font-medium leading-6 text-gray-900">Personne</label>
            <select id="user" x-model="user" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
               <option value="">Tout le monde</option>
               <template x-for="name in users" :key="name"><option :value="name" x-text="name"></option></template>
            </select>
         </div>
         <?= component('search', ['placeholder' => 'Rechercher', 'class' => 'lg:w-80']) ?>
      </div>

      <ol role="list" class="mb-16 divide-y divide-gray-100 rounded-lg bg-white shadow-sm ring-1 ring-gray-200" x-show="filtered.length > 0">
         <template x-for="entry in filtered.slice(0, shown)" :key="entry.id">
            <li class="flex flex-wrap items-start gap-x-3 gap-y-1 px-4 py-3 text-sm">
               <span class="w-28 flex-none text-xs leading-5 text-gray-500" x-text="formatDate(entry.time, 'short') + ' ' + entry.time.substring(11, 16)"></span>
               <?= component('badge', ['color' => 'none', 'class' => 'flex-none', 'attrs' => [':class' => 'actionClass(entry.action)', 'x-text' => 'actions[entry.action] || entry.action']]) ?>
               <span class="min-w-0 flex-auto">
                  <span class="font-medium text-gray-900" x-text="entry.subject + ' : '"></span>
                  <a x-show="entry.url" :href="entry.url" class="text-indigo-600 hover:text-indigo-500" x-text="entry.label"></a>
                  <span x-show="!entry.url" class="text-gray-700" x-text="entry.label"></span>
               </span>
               <span class="flex-none text-gray-500" x-text="entry.user"></span>
            </li>
         </template>
      </ol>
      <div class="-mt-12 mb-16 text-center" x-show="filtered.length > shown">
         <?= component('button', ['variant' => 'secondary', 'size' => 'sm', 'attrs' => ['@click' => 'shown += 100', 'x-text' => '`Afficher plus (${filtered.length - shown} restantes)`']]) ?>
      </div>
      <?= component('empty_state', ['icon' => 'presentation', 'class' => 'mb-16', 'title_alpine' => "entries.length ? 'Aucune action ne correspond aux filtres' : 'Aucune action enregistrée pour le moment'", 'attrs' => ['x-show' => 'filtered.length === 0']]) ?>
   </div>
</div>

<script>
   function app() {
      return {
         entries: <?= $entries ?>,
         actions: <?= js_data($actions) ?>,
         subject: '',
         user: '',
         search: '',
         shown: 100,

         get users() {
            return [...new Set(this.entries.map(entry => entry.user))].sort();
         },

         get filtered() {
            const search = this.search.trim().toLowerCase();
            return this.entries.filter(entry => (!this.subject || entry.subject === this.subject) && (!this.user || entry.user === this.user)
               && (!search || [entry.label, entry.subject, entry.user].some(text => text.toLowerCase().includes(search))));
         },

         actionClass(action) {
            return { created: 'bg-green-50 text-green-700', updated: 'bg-indigo-50 text-indigo-700', deleted: 'bg-red-50 text-red-700', sent: 'bg-purple-50 text-purple-700' }[action] || 'bg-gray-100 text-gray-600';
         },
      }
   }
</script>
<?= $this->endSection() ?>
