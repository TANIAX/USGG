<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Responsables de section
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <?php component_open('page_header', ['title' => 'Responsables de section', 'subtitle' => 'Ils sont présentés sur la page d\'accueil, dans l\'ordre des sections puis dans l\'ordre choisi ici.']) ?>
         <div class="flex gap-x-3">
            <?= component('button', ['label' => 'Voir l\'accueil', 'href' => '/#responsables', 'variant' => 'secondary', 'size' => 'sm', 'block' => true, 'attrs' => ['target' => '_blank']]) ?>
            <?= component('button', ['label' => 'Ajouter un responsable', 'href' => '/admin/responsables/create', 'size' => 'sm', 'block' => true]) ?>
         </div>
      <?= component_close() ?>

      <?= component('flash') ?>
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
                           <?= component('icon_button', ['icon' => 'chevron-up', 'label' => 'Monter', 'attrs' => ['@click' => "move(leader, 'up')", ':disabled' => 'index === 0 || busy']]) ?>
                           <?= component('icon_button', ['icon' => 'chevron-down', 'label' => 'Descendre', 'attrs' => ['@click' => "move(leader, 'down')", ':disabled' => 'index === leadersOf(section).length - 1 || busy']]) ?>
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
               const json = await requestJson(`/admin/responsables/move/${leader.id}`, { direction: direction });
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
