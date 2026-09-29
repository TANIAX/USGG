<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Journal
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php
   $periodLabels = ['7j' => '7 derniers jours', '30j' => '30 derniers jours', '90j' => '90 derniers jours'];
   $formatSize = fn(int $bytes) => $bytes > 1048576 ? number_format($bytes / 1048576, 1, ',', '') . ' Mo' : max(1, round($bytes / 1024)) . ' Ko';
   $levels = ['' => 'Tout', 'errors' => 'Erreurs', 'warning' => 'Avertissements', 'notice' => 'Notices'];
?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <?php component_open('page_header', ['title' => 'Journal', 'subtitle' => 'Erreurs et événements à surveiller, conservés 90 jours. La page d\'erreur affichée aux visiteurs donne une référence à rechercher ici.']) ?>
         <?php if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $period)): ?>
            <?= component('button', ['label' => 'Télécharger le fichier', 'href' => '/admin/logs/download/' . $period, 'variant' => 'secondary', 'size' => 'sm', 'block' => true]) ?>
         <?php endif; ?>
      <?= component_close() ?>

      <?= component('flash') ?>

      <!-- Period, level and search -->
      <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
         <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="sm:w-64">
               <label for="period" class="block text-sm font-medium leading-6 text-gray-900">Période</label>
               <select id="period" @change="window.location = '/admin/logs?periode=' + $event.target.value"
                  class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                  <?php foreach ($periods as $code): ?>
                     <option value="<?= $code ?>" <?= $period === $code ? 'selected' : '' ?>><?= $periodLabels[$code] ?></option>
                  <?php endforeach; ?>
                  <?php foreach ($days as $day): ?>
                     <option value="<?= $day['date'] ?>" <?= $period === $day['date'] ? 'selected' : '' ?>><?= date('d/m/Y', strtotime($day['date'])) ?> (<?= $formatSize($day['size']) ?>)</option>
                  <?php endforeach; ?>
               </select>
            </div>
            <div class="flex flex-wrap gap-2">
               <?php foreach ($levels as $code => $label): ?>
                  <?= component('chip', ['active_alpine' => "level === '$code'", 'attrs' => ['@click' => "level = '$code'", 'x-text' => "'$label (' + countOf('$code') + ')'"]]) ?>
               <?php endforeach; ?>
            </div>
         </div>
         <?= component('search', ['placeholder' => 'Message, page, référence...', 'class' => 'lg:w-80']) ?>
      </div>

      <?php if ($truncated): ?>
         <?= component('notice', ['class' => 'mb-6', 'message' => 'Seules les ' . \App\Libraries\LogReader::MAX_ENTRIES . ' entrées les plus récentes de la période sont affichées : choisissez un jour pour voir les autres.']) ?>
      <?php endif; ?>

      <!-- The most frequent problems of the period -->
      <section x-show="groups.length > 0" class="mb-10">
         <h2 class="text-lg font-semibold text-gray-900">Problèmes les plus fréquents</h2>
         <ul role="list" class="mt-3 divide-y divide-gray-100 rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
            <template x-for="group in groups.slice(0, 8)" :key="group.key">
               <li>
                  <button type="button" @click="search = group.search" class="flex w-full items-start gap-x-3 px-4 py-3 text-left hover:bg-gray-50">
                     <span class="mt-0.5 w-9 flex-none text-right text-sm font-semibold text-gray-900 sm:w-12" x-text="'× ' + group.count"></span>
                     <span class="min-w-0 flex-auto">
                        <span class="break-words text-sm text-gray-900 line-clamp-2" x-text="group.title"></span>
                        <span class="block text-xs text-gray-500" x-text="'Dernière fois : ' + formatTime(group.last)"></span>
                     </span>
                     <?= component('badge', ['color' => 'none', 'class' => 'flex-none', 'attrs' => [':class' => 'levelClass(group.level)', 'x-text' => 'levelLabel(group.level)']]) ?>
                  </button>
               </li>
            </template>
         </ul>
      </section>

      <!-- Entries -->
      <h2 class="text-lg font-semibold text-gray-900" x-text="`${filteredEntries.length} entrée${filteredEntries.length > 1 ? 's' : ''}`"></h2>
      <ul role="list" class="mt-3 mb-16 divide-y divide-gray-100 rounded-lg bg-white shadow-sm ring-1 ring-gray-200" x-show="filteredEntries.length > 0">
         <template x-for="(entry, index) in filteredEntries.slice(0, shown)" :key="index">
            <li>
               <details class="group">
                  <summary class="flex cursor-pointer list-none items-start gap-x-3 px-4 py-3 hover:bg-gray-50">
                     <span class="w-16 flex-none text-xs leading-5 text-gray-500 sm:w-24" x-text="formatTime(entry.time)"></span>
                     <span class="min-w-0 flex-auto">
                        <span class="break-words text-sm text-gray-900 line-clamp-3 group-open:line-clamp-none group-open:font-medium" x-text="firstLine(entry.message)"></span>
                        <span class="block truncate text-xs text-gray-500" x-show="entry.url" x-text="entry.method + ' ' + entry.url"></span>
                     </span>
                     <?= component('badge', ['color' => 'none', 'class' => 'flex-none', 'attrs' => [':class' => 'levelClass(entry.level)', 'x-text' => 'levelLabel(entry.level)']]) ?>
                  </summary>
                  <div class="space-y-3 border-t border-gray-100 bg-gray-50 px-4 py-3 text-sm">
                     <pre class="max-h-96 overflow-auto whitespace-pre-wrap break-words rounded bg-white p-3 text-xs text-gray-800 ring-1 ring-gray-200" x-text="entry.message"></pre>
                     <dl class="grid grid-cols-1 gap-x-6 gap-y-1 text-xs text-gray-600 sm:grid-cols-2">
                        <div x-show="entry.request"><dt class="inline font-medium text-gray-900">Référence :</dt> <dd class="inline font-mono" x-text="entry.request"></dd></div>
                        <div x-show="entry.url"><dt class="inline font-medium text-gray-900">Page :</dt> <dd class="inline break-all" x-text="entry.method + ' ' + entry.url"></dd></div>
                        <div>
                           <dt class="inline font-medium text-gray-900">Compte :</dt>
                           <dd class="inline">
                              <a x-show="entry.user" :href="`/admin/utilisateurs/edit/${entry.user}`" class="text-indigo-600 hover:text-indigo-500" x-text="'n° ' + entry.user"></a>
                              <span x-show="!entry.user" x-text="entry.legacy ? 'non enregistré (ancien format)' : 'visiteur non connecté'"></span>
                           </dd>
                        </div>
                        <div x-show="entry.ip"><dt class="inline font-medium text-gray-900">Adresse IP :</dt> <dd class="inline" x-text="entry.ip"></dd></div>
                        <div x-show="entry.agent" class="sm:col-span-2"><dt class="inline font-medium text-gray-900">Navigateur :</dt> <dd class="inline break-words" x-text="entry.agent"></dd></div>
                     </dl>
                  </div>
               </details>
            </li>
         </template>
      </ul>
      <div class="-mt-12 mb-16 text-center" x-show="filteredEntries.length > shown">
         <?= component('button', ['variant' => 'secondary', 'size' => 'sm', 'attrs' => ['@click' => 'shown += 100', 'x-text' => '`Afficher plus (${filteredEntries.length - shown} restantes)`']]) ?>
      </div>
      <?= component('empty_state', ['icon' => 'document', 'title_alpine' => "entries.length ? 'Aucune entrée ne correspond aux filtres' : 'Rien à signaler sur cette période'", 'class' => 'mb-16', 'attrs' => ['x-show' => 'filteredEntries.length === 0']]) ?>
   </div>
</div>

<script>
   const LOG_LEVELS = {
      emergency: ['Urgence', 'errors'], alert: ['Alerte', 'errors'], critical: ['Critique', 'errors'], error: ['Erreur', 'errors'],
      warning: ['Avertissement', 'warning'], notice: ['Notice', 'notice'], info: ['Info', 'info'], debug: ['Debug', 'info'],
   };

   function app() {
      return {
         entries: <?= $entries ?>,
         level: '',
         search: '',
         shown: 100,

         init() {
            // Search given in the url (e.g. ?q=a1b2c3d4, reference of an error page)
            this.search = new URLSearchParams(window.location.search).get('q') || '';
            this.$watch('level', () => this.shown = 100);
            this.$watch('search', () => this.shown = 100);
         },

         categoryOf(level) {
            return (LOG_LEVELS[level] || ['', 'info'])[1];
         },

         countOf(category) {
            return category ? this.entries.filter(entry => this.categoryOf(entry.level) === category).length : this.entries.length;
         },

         get filteredEntries() {
            const search = this.search.trim().toLowerCase();
            return this.entries.filter(entry =>
               (!this.level || this.categoryOf(entry.level) === this.level)
               && (!search || [entry.message, entry.url || '', entry.request || '', String(entry.user || '')].some(text => text.toLowerCase().includes(search))));
         },

         // Same problem = same level and same first line, the numbers (ids, durations...) being ignored
         get groups() {
            const groups = {};
            this.entries.filter(entry => !this.level || this.categoryOf(entry.level) === this.level).forEach(entry => {
               const title = this.firstLine(entry.message);
               const key = entry.level + '|' + title.replace(/\d+([.,]\d+)?/g, '#');
               groups[key] ??= { key: key, level: entry.level, title: title, search: title.replace(/\d.*$/, '').trim() || title, count: 0, last: entry.time };
               groups[key].count++;
            });
            return Object.values(groups).filter(group => group.count > 1).sort((a, b) => b.count - a.count);
         },

         firstLine(message) {
            return (message || '').split('\n')[0];
         },

         levelLabel(level) {
            return (LOG_LEVELS[level] || [level])[0];
         },

         levelClass(level) {
            return { errors: 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/10', warning: 'bg-amber-50 text-amber-800 ring-1 ring-inset ring-amber-600/20' }[this.categoryOf(level)]
               || 'bg-gray-100 text-gray-600';
         },

         formatTime(time) {
            return parseEventDate(time).toLocaleDateString('fr-BE', { day: 'numeric', month: 'short' }) + ' ' + time.substring(11, 19);
         },
      }
   }
</script>
<?= $this->endSection() ?>
