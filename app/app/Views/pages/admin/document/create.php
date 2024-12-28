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
      <div class="form">
         <!-- Errors -->
         <?php if (session()->getFlashdata('errors') != null ): ?>
                    <div class="rounded-md bg-red-50 p-2 mb-4" x-show="errors">
                        <div class="flex">
                            <div @click="errors = false" class="flex-shrink-0 cursor-pointer">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Erreur
                                    <?= count(session()->getFlashdata('errors')) > 1 ? 's' : '' ?> détectée
                                    <?= count(session()->getFlashdata('errors')) > 1 ? 's' : '' ?>
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul role="list" class="list-disc space-y-1 pl-5">
                                        <?php foreach (session()->getFlashdata('errors') as $error => $message): ?>
                                            <li>
                                                <?= $message ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

         <form method="POST" action="<?= base_url('admin/document/upload') ?>" method="post" enctype="multipart/form-data">
            <!-- Name -->
            <div class="form-group mb-4">
               <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Nom du document</label>
               <div class="relative mt-2 rounded-md shadow-sm">
                  <input type="text" name="name" id="name"
                     class="block w-full rounded-md border-0 py-1.5 ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6"
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
                     <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
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

            <!-- Enhanced File Upload -->
            <div class="col-span-full" x-data="{ dragOver: false }">
               <label for="file" class="block text-sm/6 font-medium text-gray-900">Fichier</label>
               <div class="mt-2 relative"
                  @dragover.prevent="dragOver = true"
                  @dragleave.prevent="dragOver = false"
                  @drop.prevent="dragOver = false; files = $event.dataTransfer.files; $refs.fileInput.files = $event.dataTransfer.files">

                  <div class="flex justify-center rounded-lg border-2 border-dashed transition-colors duration-200"
                     :class="{'border-gray-900/25': !dragOver, 'border-indigo-500 bg-indigo-50': dragOver}">

                     <div class="text-center px-6 py-10">
                        <div :class="{'hidden': files !== null}">
                              <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
                                 <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clip-rule="evenodd" />
                              </svg>
                              <div class="mt-4 flex text-sm/6 text-gray-600 justify-center">
                                 <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                    <span>Uploader un fichier</span>
                                    <input id="file-upload" name="file"  x-ref="fileInput" type="file" class="sr-only" x-ref="fileInput" @change="files = $event.target.files" required>
                                 </label>
                                 <p class="pl-1 hidden sm:block">ou glisser-déposer</p>
                              </div>
                              <p class="text-xs text-gray-600 mt-2">PDF, DOC, DOCX jusqu'à 10MB</p>
                        </div>

                        <template x-if="files !== null">
                           <div class="space-y-2">
                              <template x-for="(file, index) in Array.from(files)" :key="index">
                                 <div class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                    <div class="flex items-center space-x-2">
                                       <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                          <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"></path>
                                       </svg>
                                       <span class="text-sm text-gray-700" x-text="file.name"></span>
                                       <span class="text-xs text-gray-500" x-text="(file.size/1024/1024).toFixed(2) + ' MB'"></span>
                                    </div>
                                    <button type="button" @click="files = null"
                                       class="text-red-500 hover:text-red-700">
                                       <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                       </svg>
                                    </button>
                                 </div>
                              </template>
                           </div>
                        </template>
                     </div>
                  </div>
               </div>
            </div>

            <div class="flex justify-end p-1 mt-4">
               <button type="submit"
                  class="flex max-w-xs flex-1 items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50 sm:w-full">
                  Enregistrer
               </button>
            </div>
         </form>
      </div>
   </div>
</div>

<script>
   function app() {
      return {
         file_types: <?= $file_types ?>,
         files: null,
         errors: <?= session()->getFlashdata('errors') != null ? 'true' : 'false' ?>,

         selectType(type) {
            this.file_types.forEach(t => t.selected = false);
            type.selected = true;
            document.getElementById('combobox').value = type;
         }  
      }
   }
</script>

<?= $this->endSection() ?>