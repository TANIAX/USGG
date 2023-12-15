<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>Agenda<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mx-auto max-w-7xl" x-data="app()" x-init="getDates();" x-cloak>
    <h2 class="text-base font-semibold leading-6 text-gray-900">Prochains évenements</h2>
    <div class="lg:grid lg:grid-cols-12 lg:gap-x-16">
        <div class="mt-10 text-center lg:col-start-8 lg:col-end-13 lg:row-start-1 lg:mt-9 xl:col-start-9">
            <div class="flex items-center text-gray-900">
                <button type="button" @click="moveCurrentDateToPreviousMonth()" class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Mois précédent</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div class="flex-auto text-sm font-semibold uppercase" x-text="getCurrentDateMonthText()"></div>
                <button @click="moveCurrentDateToNextMonth()" type="button" class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Prochain mois</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="mt-6 grid grid-cols-7 text-xs leading-6 text-gray-500">
                <div>L</div>
                <div>M</div>
                <div>M</div>
                <div>J</div>
                <div>V</div>
                <div>S</div>
                <div>D</div>
            </div>
            <div class="isolate mt-2 grid grid-cols-7 gap-px rounded-lg bg-gray-200 text-sm shadow ring-1 ring-gray-200">     
            <template x-for="(date, index) in dates">
                <button
                    type="button"
                    class="py-1.5 hover:bg-gray-100 focus:z-10"
                    x-bind:class="[
                        index == 0 ? 'rounded-tl-lg ' : ' ',
                        index + 1  == dates.length ? 'rounded-br-lg ' : ' ',
                        date.isCurrentMonth ? 'bg-white ' : 'bg-gray-50 ',
                        date.isToday ? 'text-indigo-600 ' : 'text-gray-400 ',
                        index + 1 == 7 ? 'rounded-tr-lg ' : ' ',
                        index + 1 == (7 * 4 + 1) ? 'rounded-bl-lg ' : ' ',
                    ]"
                    @click="selectDate($event)"
                >
                <time x-bind:datetime="date.date" class="mx-auto flex h-7 w-7 items-center justify-center rounded-full" x-text="date.day"></time>
                </button>
            </template>
            </div>
            <button type="button" class="mt-8 w-full rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add event</button>
        </div>
        <ol class="mt-4 divide-y divide-gray-100 text-sm leading-6 lg:col-span-7 xl:col-span-8">
            <li class="relative flex space-x-6 py-6 xl:static">
                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="h-14 w-14 flex-none rounded-full">
                <div class="flex-auto">
                    <h3 class="pr-10 font-semibold text-gray-900 xl:pr-0">Leslie Alexander</h3>
                    <dl class="mt-2 flex flex-col text-gray-500 xl:flex-row">
                        <div class="flex items-start space-x-3">
                            <dt class="mt-0.5">
                                <span class="sr-only">Date</span>
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" />
                                </svg>
                            </dt>
                            <dd><time datetime="2022-01-10T17:00">January 10th, 2022 at 5:00 PM</time></dd>
                        </div>
                        <div class="mt-2 flex items-start space-x-3 xl:ml-3.5 xl:mt-0 xl:border-l xl:border-gray-400 xl:border-opacity-50 xl:pl-3.5">
                            <dt class="mt-0.5">
                                <span class="sr-only">Location</span>
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" />
                                </svg>
                            </dt>
                            <dd>Starbucks</dd>
                        </div>
                    </dl>
                </div>
            </li>

            <!-- More meetings... -->
        </ol>
    </div>
</div>
<script>
    function app() {
        return {
            selectedDate: null,
            currentDate : null,
            dates : [],
            errors: [],

            setSelectedDate(date) {
                this.currentDate = date;
            },

            getSelectedDate() {
                return this.currentDate;
            },

            getCurrentDateMonthText() {
                return this.currentDate.toLocaleString('default', { month: 'long' });
            },

            moveCurrentDateToPreviousMonth() {
                this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                this.getDates(this.currentDate);
            },

            moveCurrentDateToNextMonth() {
                this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                this.getDates(this.currentDate);
            },
            


            
            selectDate(date) {
                console.log("Date selected : ", date);
            },

            getDates(date = null) {
                //#region Variable declaration
                let month;
                let year;
                let url;
                //#endregion

                //#region  Initialize variables
                if (date == null) {
                    month = new Date().getMonth() + 1;
                    year = new Date().getFullYear();
                } else {
                    month = date.getMonth() + 1;
                    year = date.getFullYear();
                }
                this.currentDate = new Date(year, month - 1, 1);
                url = '<?= base_url() ?>/api/v1/agenda/dates/' + year + '/' + month;
                //#endregion



                fetch(url, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    }).then(response => response.json())
                    .then(json => {
                        if (json.success) 
                            this.dates = json.data;
                        else
                            this.errors = json.messages;
                    })
                    .catch((error) => {
                        this.errors = ["Une erreur est survenue"];
                    })
            }
        };
    }
</script>
<?= $this->endSection() ?>