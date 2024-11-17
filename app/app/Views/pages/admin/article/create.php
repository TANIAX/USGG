<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>Article
<?= $this->endSection() ?>

<?= $this->section('content') ?>


<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Title -->
        <div class="sm:flex justify-start sm:items-center mt-12 mb-12 border-b py-4">
            <div class="sm:flex-auto">
                <h1 class="font-semibold text-4xl leading-6 text-gray-900">Article</h1>
            </div>
        </div>

        <!-- Form -->
        <div class="p-6">
            <form method="POST" action="action.php">

                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Titre</label>
                    <div class="relative mt-2 rounded-md shadow-sm">
                        <input type="text" name="title" id="title"
                            class="block w-full rounded-md border-0 py-1.5 ring-1 ring-inset focus:ring-2 focus:ring-inset  sm:text-sm sm:leading-6"
                            :class="!formData.title.isValid && formData.title.touched ? 'text-red-900 ring-red-300 placeholder:text-red-300 focus:ring-red-500 pr-10' : 'text-gray-900 ring-gray-300 placeholder:text-gray-400 focus:ring-indigo-600'"
                            placeholder="Vente de lasagne" maxlength="100" x-model="formData.title.value" required>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg x-show="!formData.title.isValid && formData.title.touched" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                            </div>
                    </div>
                    <p x-show="!formData.title.isValid" class="mt-2 text-sm text-red-600" x-text="formData.title.errorMessage"></p>
                </div>

                <!-- Category -->
                <div x-data="{isOpen:false}" class="mb-4">
                    <label for="category" class="block text-sm font-medium leading-6 text-gray-900">Catégorie</label>
                    <div class="relative mt-2">
                        <input @click="isOpen = !isOpen" id="combobox" type="text"
                            class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-12 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6"
                            :class="formData.category_id.isValid && formData.category_id.tocuhed ? 'text-red-900 ring-red-300 placeholder:text-red-300 focus:ring-red-500 pr-10' : 'text-gray-900 ring-gray-300 placeholder:text-gray-400 focus:ring-indigo-600'"
                            role="combobox" aria-controls="options" aria-expanded="false"
                            x-model="categorySelected.name" required>
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
                            <template x-for="category in categories">
                                <li @click="selectCategory(category);isOpen = false"
                                    class="relative cursor-pointer select-none py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white"
                                    :class="category.selected ? 'bg-indigo-600 text-white' : ''" id="option-0"
                                    role="option" tabindex="-1">
                                    <span class="block truncate" x-text="category.name"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                <!-- Content -->
                <div x-data="{isOpen:false}" class="mb-4">
                    <label for="category" class="block text-sm font-medium leading-6 text-gray-900">Catégorie</label>
                    <div class="relative mt-2">
                        <textarea class="mb-8" id="editor" required>
                            
                        </textarea>
                    </div>
                </div>

                <!-- Picture -->
                <div class="mb-4">
                    <label for="cover-photo" class="block text-sm font-medium leading-6 text-gray-900">Photo de couverture</label>
                    <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="mt-4 flex text-sm leading-6 text-gray-600">
                                <label for="file-upload"
                                    class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                    <span>Uploadez un fichier</span>
                                    <input id="file-upload" name="file-upload" type="file" class="sr-only">
                                </label>
                                <p class="pl-1">Utilisez plutôt le drag and drop !</p>
                            </div>
                            <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF up to 5MB</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end p-1">
                    <button type="submit" class="flex max-w-xs flex-1 items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50 sm:w-full">Enregistrer</button>
                </div>
        </div>
    </div>


    </form>
</div>
</div>
</div>

<script src="<?= base_url('assets/js/ckeditor/ckeditor.js') ?>"></script>
<script>
    CKEDITOR.replace("editor", {
        extraPlugins: 'upperlower',
        toolbar: [
            { name: 'document', items: ['Source', 'ExportPdf', 'Preview', 'Print', '-', 'Templates'] },
            { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
            { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] },
            '/',
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] },
            { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
            { name: 'insert', items: ['Image', 'Table', 'HorizontalRule'] },
            '/',
            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
            { name: 'tools', items: ['Maximize', 'ShowBlocks'] },
            { name: 'about', items: ['About'] }
        ],
        filebrowserUploadUrl: "/admin/news/upload"
    });
</script>
<script>
    function app() {
        return {
            categories: <?= $categories ?>,
            categorySelected: {id:0,name: ''},
            formData: {
                title: {
                    value: '',
                    isValid: false,
                    touched: false,
                    errorMessage: ''
                },
                content: {
                    value: '',
                    isValid: false,
                    touched: false,
                    errorMessage: ''
                },
                picture: {
                    value: '',
                    isValid: false,
                    touched: false,
                    errorMessage: ''
                },
                category_id: {
                    value: '',
                    isValid: false,
                    touched: false,
                    errorMessage: ''
                },
            },

            selectCategory(category) {
                this.categories.forEach(c => {
                    c.selected = false;
                });
                category.selected = true;
                this.categorySelected = Object.assign({}, category); //remove reference
                this.formData.category_id.value = category.id;
                this.formData.category_id.isValid = true;
            },

            validateForm() {
                let isValid = true;
                
                //Title validation
                if (this.formData.title.length < 5) {
                    isValid = false;
                }
                return true;
            }

            

        }
    }
</script>


<?= $this->endSection() ?>