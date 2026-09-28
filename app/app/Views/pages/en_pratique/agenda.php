<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
    Guides et scoutes de Gosselies - Agenda
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mx-auto max-w-7xl px-8 mt-8" x-data="app()" x-cloak>
    <div
    class="absolute inset-y-0 right-1/2 -z-10 -mr-96 w-[200%] origin-top-right skew-x-[-30deg] bg-white shadow-xl shadow-indigo-600/10 ring-1 ring-indigo-50 sm:-mr-80 lg:-mr-96 hidden lg:block "
    aria-hidden="true"></div>
    <h1 class="text-4xl xl:text-4xl font-bold leading-normal xl:leading-relaxed mb-2">AGENDA DES ACTIVITÉS</h1>
    <h2 class="font-semibold leading-6 text-gray-900 text-xl md:text-2xl mt-4 md:mt-12">Prochains événements</h2>
    <div class="lg:grid lg:grid-cols-12 lg:gap-x-16">
        <div class="mt-10 text-center lg:col-start-8 lg:col-end-13 lg:row-start-1 lg:mt-9 xl:col-start-9">
            <div class="flex items-center text-gray-900">
                <button type="button" @click="moveCurrentDateTo(-1)"
                    class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Mois précédent</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <div class="flex-auto text-sm font-semibold uppercase" x-text="currentDateString"></div>
                <button type="button" @click="moveCurrentDateTo(1)"
                    class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Mois suivant</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                            clip-rule="evenodd" />
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
                <template x-for="(day, index) in days" :key="day.key">
                    <button type="button" @click="selectDate(day)" :id="day.key"
                        class="relative py-1.5 hover:bg-gray-100 focus:z-10"
                        :class="{
                            'bg-white': day.isCurrentMonth,
                            'bg-gray-50': !day.isCurrentMonth,
                            'font-semibold': day.isToday || isSelected(day),
                            'text-white': isSelected(day),
                            'text-indigo-600': !isSelected(day) && day.isToday,
                            'text-gray-900': !isSelected(day) && !day.isToday && day.isCurrentMonth,
                            'text-gray-400': !isSelected(day) && !day.isToday && !day.isCurrentMonth,
                            'rounded-tl-lg': index === 0,
                            'rounded-tr-lg': index === 6,
                            'rounded-bl-lg': index === days.length - 7,
                            'rounded-br-lg': index === days.length - 1,
                        }">
                        <time :datetime="day.key" x-text="day.day"
                            class="mx-auto flex h-7 w-7 items-center justify-center rounded-full"
                            :class="{
                                'bg-indigo-600': isSelected(day) && day.isToday,
                                'bg-gray-900': isSelected(day) && !day.isToday,
                            }"></time>
                    </button>
                </template>
            </div>

            <!-- If any event -->
            <div class="mt-2 flex">
                <div class="flex w-full bg-white shadow-lg rounded-r-lg rounded-l-sm overflow-hidden">
                    <div class="w-2 bg-indigo-600"></div>
                    <div class="flex items-start">
                        <div class="mx-3">
                            <p class="text-gray-600">Exemple d'évenements du jour.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events list -->
        <ol class="mt-4 divide-y divide-gray-100 text-sm leading-6 lg:col-span-7 xl:col-span-8">
            <li class="relative flex space-x-6 py-6 xl:static">
                <div class="flex-auto">
                    <h3 class="pr-10 font-semibold text-gray-900 xl:pr-0">CU Compo Staff Section</h3>
                    <dl class="mt-2 flex flex-col text-gray-500 xl:flex-row">
                        <div class="flex items-start space-x-3">
                            <dt class="mt-0.5">
                                <span class="sr-only">Date</span>
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z"
                                        clip-rule="evenodd" />
                                </svg>
                            </dt>
                            <dd><time datetime="2022-01-10T17:00">Le 10 janvier 2022 à 17:00</time></dd>
                        </div>
                        <div
                            class="mt-2 flex items-start space-x-3 xl:ml-3.5 xl:mt-0 xl:border-l xl:border-gray-400 xl:border-opacity-50 xl:pl-3.5">
                            <dt class="mt-0.5">
                                <span class="sr-only">Emplacement</span>
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </dt>
                            <dd>Quelque part</dd>
                        </div>
                    </dl>
                </div>
            </li>

            <li class="relative flex space-x-6 py-6 xl:static">
                <div class="flex-auto">
                    <h3 class="pr-10 font-semibold text-gray-900 xl:pr-0">Montée scoute</h3>
                    <dl class="mt-2 flex flex-col text-gray-500 xl:flex-row">
                        <div class="flex items-start space-x-3">
                            <dt class="mt-0.5">
                                <span class="sr-only">Date</span>
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z"
                                        clip-rule="evenodd" />
                                </svg>
                            </dt>
                            <dd><time datetime="2022-01-10T17:00">Le 23 janvier 2022 à 18:00</time></dd>
                        </div>
                        <div
                            class="mt-2 flex items-start space-x-3 xl:ml-3.5 xl:mt-0 xl:border-l xl:border-gray-400 xl:border-opacity-50 xl:pl-3.5">
                            <dt class="mt-0.5">
                                <span class="sr-only">Emplacement</span>
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </dt>
                            <dd>Quelque part</dd>
                        </div>
                    </dl>
                </div>
            </li>

            <li class="relative flex space-x-6 py-6 xl:static">
                <div class="flex-auto">
                    <h3 class="pr-10 font-semibold text-gray-900 xl:pr-0">Apero d’unité</h3>
                    <dl class="mt-2 flex flex-col text-gray-500 xl:flex-row">
                        <div class="flex items-start space-x-3">
                            <dt class="mt-0.5">
                                <span class="sr-only">Date</span>
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z"
                                        clip-rule="evenodd" />
                                </svg>
                            </dt>
                            <dd><time datetime="2022-01-10T17:00">Le 24 janvier 2022 à 17:00</time></dd>
                        </div>
                        <div
                            class="mt-2 flex items-start space-x-3 xl:ml-3.5 xl:mt-0 xl:border-l xl:border-gray-400 xl:border-opacity-50 xl:pl-3.5">
                            <dt class="mt-0.5">
                                <span class="sr-only">Emplacement</span>
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </dt>
                            <dd>Quelque part</dd>
                        </div>
                    </dl>
                </div>
            </li>
        </ol>
    </div>
</div>
<script>
    function app() {
        return {
            // Displayed month (first day of the month, local time)
            currentYear: new Date().getFullYear(),
            currentMonth: new Date().getMonth(),
            selectedKey: null,
            days: [],

            init() {
                this.refresh();
            },

            get currentDateString() {
                return new Date(this.currentYear, this.currentMonth, 1).toLocaleDateString('fr-BE', {
                    month: 'long',
                    year: 'numeric',
                });
            },

            // Move from one or several months without going through Date.setMonth(),
            // which skips a month when the current day does not exist in the target month (e.g. 31/01 -> 03/03).
            moveCurrentDateTo(value) {
                const target = new Date(this.currentYear, this.currentMonth + value, 1);
                this.currentYear = target.getFullYear();
                this.currentMonth = target.getMonth();
                this.refresh();
            },

            refresh() {
                this.days = createCalendar(this.currentYear, this.currentMonth).flat();
            },

            selectDate(day) {
                this.selectedKey = this.selectedKey === day.key ? null : day.key;
            },

            isSelected(day) {
                return this.selectedKey === day.key;
            },
        };
    }
</script>
<?= $this->endSection() ?>