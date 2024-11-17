<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
    Guides et scoutes de Gosselies - Articles
<?= $this->endSection() ?>

<?= $this->section('content') ?>


<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Title -->
        <div class="sm:flex justify-start sm:items-center mt-12 mb-12 border-b py-4">
            <div class="sm:flex-auto">
                <h1 class="font-semibold text-4xl leading-6 text-gray-900">Articles</h1>
            </div>
        </div>

        <!-- Search bar & year selection -->
        <div class="mb-8 grid grid-cols-4 gap-4">
            <!-- search -->
            <div class="col-span-2 md:col-span-3">
                <label for="search" class="block text-sm font-medium leading-6 text-gray-900">Recherche rapide</label>
                <div class="relative mt-2 flex items-center">
                    <input type="text" name="search" accesskey="K" x-model="search" x-on:keyup="searchNews()"
                        id="search"
                        class="block w-full rounded-md border-0 pl-4 py-1.5 pr-14 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    <div class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5">
                        <kbd class="inline-flex items-center rounded border border-gray-200 px-1 font-sans text-xs text-gray-400"
                            x-text="shortcut"></kbd>
                    </div>
                </div>
            </div>

            <!-- year -->
            <div x-data="{isOpen:false}" class="col-span-2 md:col-span-1">
                <label for="combobox" class="block text-sm font-medium leading-6 text-gray-900">Année</label>
                <div class="relative mt-2">
                    <input @click="isOpen = !isOpen" x-model="currentYear" id="combobox" type="text"
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
                        <template x-for="year in years">
                            <li @click="selectYear(year)"
                                class="relative cursor-pointer select-none py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white"
                                :class="year.selected ? 'bg-indigo-600 text-white' : ''" id="option-0" role="option"
                                tabindex="-1">
                                <span class="block truncate" x-text="year.id"></span>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>

        <!-- List -->
        <div x-show="filteredData.length > 0" class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="relative">

                        <!-- delete all button - only appears if one is selected  -->
                        <div x-show="rowSelected()"
                            class="absolute top-0 left-14 flex h-12 items-center space-x-3 bg-white sm:left-12">
                            <button type="button"
                                class="inline-flex items-center rounded bg-white px-2 py-1 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">Supprimer
                                tout</button>
                        </div>

                        <table class="min-w-full table-fixed divide-y divide-gray-300">
                            <thead>
                                <tr>
                                    <!-- master checkbox -->
                                    <th scope="col" class="relative px-7 sm:w-12 sm:px-6">
                                        <input type="checkbox" @click="masterCheckbox()"
                                            class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                    </th>
                                    <th scope="col"
                                        class="w-[8rem] py-3.5 pr-3 text-left text-sm font-semibold text-gray-900">
                                        Date de création</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Titre</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Categorie</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-3">
                                        <span class="sr-only">Modifier</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <template x-for="news in filteredData">
                                    <tr :class="news.selected ? 'bg-gray-50' : ''">
                                        <td class="relative px-7 sm:w-12 sm:px-6">
                                            <div x-show="news.selected"
                                                class="absolute inset-y-0 left-0 w-0.5 bg-indigo-600"></div>
                                            <input type="checkbox" @click="news.selected = !news.selected"
                                                x-bind:checked="news.selected"
                                                class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                        </td>
                                        <td class="whitespace-nowrap py-4 pr-3 text-sm font-medium"
                                            :class="news.selected ? 'text-indigo-600' : 'text-gray-500'"
                                            x-text="formatDate(news.created_at.date,'DD/MM/YYYY')"></td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                            x-text="news.title"></td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                            x-text="news.category.name"></td>
                                        <td
                                            class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <!-- Add button -->
                        <div class="sm:flex justify-end sm:items-center mt-8">
                            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                <a href="/admin/news/create">
                                    <button type="button"
                                        class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Ajouter
                                        article</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="filteredData.length == 0">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />

                </svg>
                
                <h3 class="mt-2 text-sm font-semibold text-gray-900">Aucun article</h3>
                <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouvel article.</p>
                <div class="mt-6">
                    <a href="/admin/article/create">
                    <button type="button"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                                d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        Créer un article
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
            data: <?= $news ?>,
            years: <?= $years ?>,
            currentYear: new Date().getFullYear(),
            filteredData: [],
            allChecked: false,
            shortcut: 'K',
            init() {
                this.filteredData = this.data;
                this.currentYear = window.location.href.slice(-4).match(/^\d+$/) ? window.location.href.slice(-4) : new Date().getFullYear();//is year is defined in url then set it as current year
                this.determineShortcut();
            },
            rowSelected() {
                return this.data.filter((news) => news.selected).length > 0;
            },
            masterCheckbox() {
                this.allChecked = !this.allChecked;
                this.data.forEach((news) => news.selected = this.allChecked);
            },
            formatDate(date, format) {
                return moment(date).format(format);
            },
            searchNews() {
                this.filteredData = this.data.filter((news) => news.title.toLowerCase().includes(this.search.toLowerCase()));
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

            selectYear(selectedYear) {
                //Get current url and replace year
                let url = window.location.href;
                //if last 4 characters are numbers
                if (url.slice(-4).match(/^\d+$/)) {
                    url = url.slice(0, -4);
                    url += selectedYear.id;
                } else {
                    url += '/' + selectedYear.id;
                }
                //Update url
                window.location.href = url;
            }
        }
    }
</script>


<?= $this->endSection() ?>