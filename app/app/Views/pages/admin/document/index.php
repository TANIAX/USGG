<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Documents
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <!-- Title -->
      <div class="sm:flex justify-start sm:items-center mt-12 mb-12 border-b py-4">
         <div class="sm:flex-auto">
            <h1 class="font-semibold text-4xl leading-6 text-gray-900">Documents</h1>
         </div>
      </div>

      <!-- Search bar & file type selection -->
      <div class="mb-8 grid grid-cols-4 gap-4">
         <!-- search -->
         <div class="col-span-2 md:col-span-3">
            <label for="search" class="block text-sm font-medium leading-6 text-gray-900">Recherche rapide</label>
            <div class="relative mt-2 flex items-center">
               <input type="text" name="search" accesskey="K" x-model="search" x-on:keyup="searchFiles()"
                  id="search"
                  class="block w-full rounded-md border-0 pl-4 py-1.5 pr-14 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
               <div class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5">
                  <kbd class="inline-flex items-center rounded border border-gray-200 px-1 font-sans text-xs text-gray-400"
                     x-text="shortcut"></kbd>
               </div>
            </div>
         </div>
         <!-- file type -->
         <div x-data="{isOpen:false}" class="col-span-2 md:col-span-1">
            <label for="combobox" class="block text-sm font-medium leading-6 text-gray-900">Document pour : </label>
            <div class="relative mt-2">
               <input @click="isOpen = !isOpen" x-model="currentFileType" id="combobox" type="text"
                  class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                  role="combobox" aria-controls="options" aria-expanded="false">
               <button type="button" @click="isOpen = !isOpen"
                  class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                  <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                     <path fill-rule="evenodd"
                        d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                        clip-rule="evenodd" />
                  </svg>
               </button>
               <ul x-show="isOpen"
                  class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                  id="options" role="listbox">
                  <template x-for="type in file_type">
                     <li @click="selectFileType(type); isOpen = false"
                        class="relative cursor-pointer select-none py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white"
                        :class="type.selected ? 'bg-indigo-600 text-white' : ''" id="option-0" role="option"
                        tabindex="-1">
                        <span class="block truncate" x-text="type"></span>
                     </li>
                  </template>
               </ul>
            </div>
         </div>
      </div>

      <!-- Responsive Table -->
      <div x-show="filteredData.length > 0" class="-mx-4 mt-8 sm:-mx-0">
         <div class="relative">
            <!-- delete all button - only appears if one is selected  -->
            <div x-show="rowSelected()"
               class="absolute top-0 left-14 flex h-12 items-center space-x-3 bg-white sm:left-12">
               <button type="button"
                  @click="deleteSelected(filteredData.filter((file) => file.selected))"
                  class="inline-flex items-center rounded bg-white px-2 py-1 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">
                  Supprimer tout
               </button>
            </div>

            <table class="min-w-full divide-y divide-gray-300">
               <thead>
                  <tr>
                     <th scope="col" class="relative px-7 sm:w-12 sm:px-6">
                        <input type="checkbox" @click="masterCheckbox()"
                           class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                     </th>
                     <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Nom</th>
                     <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                        Date de création
                     </th>
                     <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                        Type de document
                     </th>
                     <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                        <span class="sr-only">Supprimer</span>
                     </th>
                  </tr>
               </thead>
               <tbody class="divide-y divide-gray-200 bg-white">
                  <template x-for="file in filteredData">
                     <tr :class="file.selected ? 'bg-gray-50' : ''">
                        <td class="relative px-7 sm:w-12 sm:px-6">
                           <div x-show="file.selected" class="absolute inset-y-0 left-0 w-0.5 bg-indigo-600"></div>
                           <input type="checkbox" @click="file.selected = !file.selected"
                              x-bind:checked="file.selected"
                              class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                        </td>
                        <td class="w-full max-w-0 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-0">
                           <span x-text="file.name"></span>
                           <!-- Mobile-only information -->
                           <dl class="font-normal lg:hidden">
                              <dt class="sr-only">Date de création</dt>
                              <dd class="mt-1 truncate text-gray-700" x-text="formatDate(file.created_at.date,'DD/MM/YYYY')"></dd>
                              <dt class="sr-only sm:hidden">Type de document</dt>
                              <dd class="mt-1 truncate text-gray-500 sm:hidden" x-text="file.file_type"></dd>
                           </dl>
                        </td>
                        <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell" x-text="formatDate(file.created_at.date,'DD/MM/YYYY')"></td>
                        <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell" x-text="file.file_type"></td>
                        <td class="py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                           <button type="button" @click="deleteSelected([file])" 
                              class="text-red-600 hover:text-indigo-900">Supprimer</button>
                        </td>
                     </tr>
                  </template>
               </tbody>
            </table>
            <!-- Add button -->
            <div class="sm:flex justify-end sm:items-center mt-8">
                     <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                        <a href="/admin/document/create">
                        <button type="button"
                           class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Ajouter
                        document</button>
                        </a>
                     </div>
                  </div>
         </div>
      </div>

      <!-- Empty state -->
      <div x-show="filteredData.length == 0">
         <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
               <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">Aucun document</h3>
            <p class="mt-1 text-sm text-gray-500">Commencez par ajouter un document.</p>
            <div class="mt-6">
               <a href="/admin/document/create">
                  <button type="button"
                     class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                     <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                     </svg>
                     Créer un document
                  </button>
               </a>
            </div>
         </div>
      </div>
   </div>
</div>

<script src="<?= base_url('assets/js/moment.js')?>"></script>
<script>
   function app() {
       return {
           search: '',
           data: <?= $files ?>,
           file_type: <?= $file_types ?>,
           currentFileType: 'Tous',
           filteredData: [],
           allChecked: false,
           shortcut: 'K',
           init() {
               this.filteredData = this.data;
               this.determineShortcut();
           },
           rowSelected() {
               return this.data.filter((file) => file.selected).length > 0;
           },
           masterCheckbox() {
               this.allChecked = !this.allChecked;
               this.data.forEach((file) => file.selected = this.allChecked);
           },
           formatDate(date, format) {
               return moment(date).format(format);
           },
           searchFiles() {
               this.filteredData = this.data.filter((file) => file.name.toLowerCase().includes(this.search.toLowerCase()));
           },
           determineShortcut() {
               switch (navigator.platform) {
                   case 'Win32':
                   case 'Win64':
                       this.shortcut = 'ALT + ' + this.shortcut;
                       break;
                   case 'MacIntel':
                   case 'MacPPC':
                       this.shortcut = '⌘' + this.shortcut;
                       break;
               }
           },
   
           selectFileType(type) {
               if(type === 'TOUS') {
                   this.filteredData = this.data;
                   this.currentFileType = 'Tous';
               }else{
                   this.currentFileType = type;
                   this.filteredData = this.data.filter((file) => file.file_type === type);
               }
           },
   
           deleteSelected(files){
               const confirmString = files.length === 1 ? `Êtes-vous sûr de vouloir supprimer le document "${files[0].name}" ?` : `Êtes-vous sûr de vouloir supprimer les ${files.length} documents sélectionnés ?`;
               if(confirm(confirmString)){
                   window.location.href = '/admin/document/delete/' + files.map((file) => file.id).join(',');
               }
           }
       }
   }
</script>
<?= $this->endSection() ?>