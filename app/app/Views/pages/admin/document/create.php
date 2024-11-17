<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Création de document
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <!-- Title -->
      <div class="sm:flex justify-start sm:items-center mt-12 mb-12 border-b py-4">
         <div class="sm:flex-auto">
            <h1 class="font-semibold text-4xl leading-6 text-gray-900">Création d'un document</h1>
         </div>
      </div>
      <!-- Form -->
      <div class="p-6">
         <form method="POST" action="<?= base_url('admin/document/upload')?>" method="post" enctype="multipart/form-data">

            <!-- Name -->
            <div class="form-group mb-4">
               <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Nom du document</label>
               <div class="relative mt-2 rounded-md shadow-sm">
                  <input type="text" name="name" id="name"
                     class="block w-full rounded-md border-0 py-1.5 ring-1 ring-inset focus:ring-2 focus:ring-inset  sm:text-sm sm:leading-6"
                     placeholder="Cotisation 20XX-20XX" maxlength="255" minlength="3" required>
               </div>
            </div>

            <!-- File type -->
            <div x-data="{isOpen:false}" class="mb-4">
               <label for="file_type" class="block text-sm font-medium leading-6 text-gray-900">Type de fichier</label>
               <div class="relative mt-2">
                  <input @click="isOpen = !isOpen" id="combobox" type="text" name="file_type"
                     class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-12 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6"
                     role="combobox" aria-controls="options" aria-expanded="false"
                     required>
                  <button type="button" @click="isOpen = !isOpen"
                     class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                     <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                           d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                           clip-rule="evenodd" />
                     </svg>
                  </button>
                  <ul x-show="isOpen"
                     class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                     id="options" role="listbox">
                     <template x-for="type in file_types">
                        <li @click="selectType(type);isOpen = false"
                           class="relative cursor-pointer select-none py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white"
                           :class="type.selected ? 'bg-indigo-600 text-white' : ''" id="option-0"
                           role="option" tabindex="-1">
                           <span class="block truncate" x-text="type"></span>
                        </li>
                     </template>
                  </ul>
               </div>
            </div>

            <!-- File -->
            <div class="col-span-full">
               <label for="file" class="block text-sm/6 font-medium text-gray-900">Fichier</label>
               <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                  <div class="text-center">
                     <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clip-rule="evenodd" />
                     </svg>
                     <div class="mt-4 flex text-sm/6 text-gray-600">
                        <label for="file" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                        <span>Uploader un fichier</span>
                        <input id="file" name="file" type="file" hidden required>
                        </label>
                        <p class="pl-1">drag and drop</p>
                     </div>
                     <p class="text-xs/5 text-gray-600">PDF, DOC, DOCX Jusqu'à 10MB</p>
                  </div>
               </div>
            </div>
            <div class="flex justify-end p-1 mt-4">
               <button type="submit" class="flex max-w-xs flex-1 items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50 sm:w-full">Enregistrer</button>
            </div>
         </form>
      </div>
   </div>
</div>

<script>
   function app() {
       return {
        file_types: <?= $file_types ?>,
   
        selectType(type) {
            this.file_types.forEach(t => t.selected = false);
            type.selected = true;
            document.getElementById('combobox').value = type;
        }
       }
   }
</script>
<?= $this->endSection() ?>