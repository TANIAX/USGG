<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - <?= $event ? 'Modification' : 'Création' ?> d'un événement
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <?= component('page_header', ['title' => $event ? 'Modifier l\'événement' : 'Nouvel événement']) ?>

      <?= component('flash') ?>

      <form method="POST" action="<?= $event ? base_url('admin/agenda/update/' . $event->id) : base_url('admin/agenda/store') ?>"
         enctype="multipart/form-data" class="max-w-3xl space-y-8" @submit="submitting = true">

         <?= component('field', ['label' => 'Titre', 'name' => 'title', 'required' => true, 'placeholder' => 'Réunion, week-end, souper...', 'attrs' => ['x-model' => 'values.title', 'maxlength' => 255]]) ?>

         <!-- Sections -->
         <fieldset>
            <legend class="block text-sm font-medium leading-6 text-gray-900">Sections concernées <span class="text-red-600">*</span></legend>
            <div class="mt-1 flex flex-wrap gap-x-4 text-sm">
               <button type="button" @click="selectBranch('GUIDE')" class="font-semibold text-indigo-600 hover:text-indigo-500">Toutes les sections guides</button>
               <button type="button" @click="selectBranch('SCOUTE')" class="font-semibold text-indigo-600 hover:text-indigo-500">Toutes les sections scoutes</button>
               <button type="button" @click="values.sections = []" class="font-semibold text-gray-500 hover:text-gray-700">Aucune</button>
            </div>
            <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-3">
               <template x-for="section in sections" :key="section.id">
                  <label class="flex cursor-pointer items-center gap-x-3 rounded-lg bg-white p-3 ring-1 ring-inset hover:bg-gray-50"
                     :class="values.sections.includes(section.id) ? 'ring-2' : 'ring-gray-300'"
                     :style="values.sections.includes(section.id) ? `--tw-ring-color: ${section.color}` : ''">
                     <input type="checkbox" name="sections[]" :value="section.id" x-model.number="values.sections"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                     <img x-show="section.logo" class="h-9 w-9 rounded-full bg-white object-contain p-0.5 ring-1 ring-gray-200" :src="assetUrl(section.logo)" alt="" @error="$el.style.display = 'none'">
                     <span class="text-sm font-medium text-gray-900" x-text="section.name"></span>
                     <span class="ml-auto h-2.5 w-2.5 rounded-full" :style="`background-color: ${section.color}`"></span>
                  </label>
               </template>
            </div>
            <p class="mt-2 text-sm text-gray-500">« Unité » : événement qui concerne toute l'unité.</p>
         </fieldset>

         <!-- Dates -->
         <fieldset>
            <legend class="block text-sm font-medium leading-6 text-gray-900">Date et heure <span class="text-red-600">*</span></legend>
            <?= component('checkbox', ['label' => 'Toute la journée', 'name' => 'all_day', 'class' => 'mt-2', 'attrs' => ['x-model' => 'values.all_day']]) ?>

            <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
               <div>
                  <p class="text-sm text-gray-500">Début</p>
                  <div class="mt-1 flex gap-x-2">
                     <?= component('input', ['type' => 'date', 'name' => 'start_date', 'id' => false, 'required' => true, 'attrs' => ['x-model' => 'values.start_date', '@change' => 'syncEndDate()', 'aria-label' => 'Date de début']]) ?>
                     <?= component('input', ['type' => 'time', 'name' => 'start_time', 'id' => false, 'width' => 'w-32', 'attrs' => ['x-model' => 'values.start_time', 'x-show' => '!values.all_day', ':required' => '!values.all_day', 'aria-label' => 'Heure de début']]) ?>
                  </div>
               </div>
               <div>
                  <p class="text-sm text-gray-500">Fin</p>
                  <div class="mt-1 flex gap-x-2">
                     <?= component('input', ['type' => 'date', 'name' => 'end_date', 'id' => false, 'attrs' => ['x-model' => 'values.end_date', ':min' => 'values.start_date', 'aria-label' => 'Date de fin']]) ?>
                     <?= component('input', ['type' => 'time', 'name' => 'end_time', 'id' => false, 'width' => 'w-32', 'attrs' => ['x-model' => 'values.end_time', 'x-show' => '!values.all_day', 'aria-label' => 'Heure de fin']]) ?>
                  </div>
               </div>
            </div>
            <p class="mt-2 text-sm text-gray-500">La fin est facultative : sans date de fin, l'événement se termine le même jour.</p>
            <p class="mt-1 text-sm font-medium text-gray-700" x-show="preview" x-text="'Aperçu : ' + preview"></p>
         </fieldset>

         <?= component('field', ['label' => 'Lieu', 'name' => 'location', 'placeholder' => 'Local de Gosselies, adresse...', 'attrs' => ['x-model' => 'values.location', 'maxlength' => 255]]) ?>

         <?= component('field', ['label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 6, 'placeholder' => 'Programme, matériel à prévoir, prix...',
            'help' => 'Les retours à la ligne sont conservés.', 'attrs' => ['x-model' => 'values.description', 'maxlength' => 5000]]) ?>

         <?= component('field', ['label' => 'Lien d\'inscription', 'name' => 'registration_url', 'type' => 'url', 'placeholder' => 'https://...', 'attrs' => ['x-model' => 'values.registration_url', 'maxlength' => 255],
            'help' => 'Facultatif. Un bouton « S\'inscrire » sera affiché tant que l\'événement n\'est pas terminé. Si chaque section a son propre formulaire, laissez ce champ vide et indiquez les liens dans la description.']) ?>

         <!-- Image (shown in the news of the home page and in the agenda) -->
         <div>
            <span class="block text-sm font-medium leading-6 text-gray-900">Image</span>
            <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-start">
               <div class="aspect-[16/9] w-full max-w-xs overflow-hidden rounded-lg bg-gray-100 ring-1 ring-gray-200">
                  <img x-show="imagePreview && !removeImage" :src="imagePreview" alt="" class="h-full w-full object-cover">
                  <div x-show="!imagePreview || removeImage" class="flex h-full items-center justify-center px-4 text-center text-sm text-gray-400">
                     Sans image, le logo des sections est affiché.
                  </div>
               </div>
               <div class="space-y-3 text-sm">
                  <?= component('file_button', ['name' => 'image', 'label_alpine' => "imagePreview && !removeImage ? 'Changer l\\'image' : 'Choisir une image'", 'attrs' => ['@change' => 'chooseImage($event.target)']]) ?>
                  <?= component('checkbox', ['label' => 'Retirer l\'image actuelle', 'name' => 'remove_image', 'class' => 'flex', 'attrs' => ['x-model' => 'removeImage']]) ?>
                  <p class="text-gray-500" x-show="reducing">Préparation de l'image…</p>
                  <p class="text-gray-500">Facultatif. Format paysage conseillé (16/9). La photo est réduite avant l'envoi.</p>
               </div>
            </div>
         </div>

         <?= component('form_actions', ['cancel' => '/admin/agenda', 'submit_attrs' => [':disabled' => 'reducing || submitting']]) ?>
      </form>
   </div>
</div>

<script>
   function app() {
      return {
         baseUrl: '<?= rtrim(base_url(), '/') ?>/',
         sections: <?= $sections ?>,
         values: <?= $values ?>,
         imagePreview: <?= json_encode($imageUrl ?? null) ?>,
         hasCurrentImage: <?= !empty($imageUrl) ? 'true' : 'false' ?>,
         removeImage: false,
         reducing: false,
         submitting: false,

         // The photo is reduced in the browser (script.js) before being sent with the form
         async chooseImage(input) {
            if (!input.files.length)
               return;
            this.reducing = true;
            const file = await reduceInputPhoto(input, 1600);
            this.reducing = false;
            this.removeImage = false;
            if (file)
               this.imagePreview = URL.createObjectURL(file);
         },

         // Readable summary of the dates, with the same format as the public agenda
         get preview() {
            const values = this.values;
            if (!values.start_date || (!values.all_day && !values.start_time))
               return '';
            const endDate = values.end_date || values.start_date;
            const event = values.all_day
               ? { start_at: values.start_date, end_at: endDate, all_day: true }
               : { start_at: `${values.start_date} ${values.start_time}`, end_at: `${endDate} ${values.end_time || values.start_time}`, all_day: false };
            if (event.end_at < event.start_at)
               return 'la fin est avant le début';
            return formatEventPeriod(event);
         },

         // Keeps the end date after the start date
         syncEndDate() {
            if (this.values.end_date && this.values.end_date < this.values.start_date)
               this.values.end_date = this.values.start_date;
         },

         selectBranch(branch) {
            this.sections.filter(section => section.branch === branch).forEach(section => {
               if (!this.values.sections.includes(section.id))
                  this.values.sections.push(section.id);
            });
         },

         assetUrl(path) {
            return this.baseUrl + path;
         },
      }
   }
</script>

<?= $this->endSection() ?>
