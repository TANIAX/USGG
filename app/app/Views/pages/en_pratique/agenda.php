<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
    Guides et scoutes de Gosselies - Agenda
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mx-auto max-w-7xl px-8 mt-8" x-data="app()" x-cloak
    x-init="getDates();datesChanged();$watch('dates', () => datesChanged() )">
    <div
    class="absolute inset-y-0 right-1/2 -z-10 -mr-96 w-[200%] origin-top-right skew-x-[-30deg] bg-white shadow-xl shadow-indigo-600/10 ring-1 ring-indigo-50 sm:-mr-80 lg:-mr-96 hidden lg:block "
    aria-hidden="true"></div>
    <h1 class="text-4xl xl:text-4xl font-bold leading-normal xl:leading-relaxed mb-2">AGENDA DES ACTIVITÉS</h1>
    <h2 class="font-semibold leading-6 text-gray-900 text-xl md:text-2xl mt-4 md:mt-12">Prochains évenements</h2>
    <div class="lg:grid lg:grid-cols-12 lg:gap-x-16">
        <div class="mt-10 text-center lg:col-start-8 lg:col-end-13 lg:row-start-1 lg:mt-9 xl:col-start-9">
            <div class="flex items-center text-gray-900">
                <button type="button" @click="moveCurrentDateToPrevious()"
                    class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Mois précédent</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <div class="flex-auto text-sm font-semibold uppercase" x-text="currentDateString">

                </div>
                <button @click="moveCurrentDateToNext();currentDate=currentDate" type="button"
                    class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Prochain mois</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="mt-6 grid grid-cols-7 text-xs leading-6 text-gray-500">
                <div>D</div>
                <div>L</div>
                <div>M</div>
                <div>M</div>
                <div>J</div>
                <div>V</div>
                <div>S</div>
            </div>

            <!-- Dates are inserted with javascript -->
            <div class="isolate mt-2 grid grid-cols-7 gap-px rounded-lg bg-gray-200 text-sm shadow ring-1 ring-gray-200"
                id="dates-calendar">
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
            currentDate: new Date(),
            selectedDate: new Date(),
            currentDateString: '',
            dates: [],
            errors: [],

            moveCurrentDateToPrevious() {
                this.moveCurrentDateTo(-1);
            },
            moveCurrentDateToNext() {
                this.moveCurrentDateTo(1);
            },
            moveCurrentDateTo(value) {
                this.currentDate.setMonth(this.currentDate.getMonth() + value);
                this.currentDateString = this.getCurrentDateText();
                this.getDates(this.currentDate);
            },
            getCurrentDateText() {
                return this.currentDate.toLocaleDateString(undefined, {
                    month: "long",
                    year: "numeric",
                });
            },

            getDates(date = new Date()) {
                //#region Variable declaration
                let month;
                let year;
                //#endregion

                month = date.getMonth();
                year = date.getFullYear();
                this.currentDateString = this.getCurrentDateText();

                this.dates = createCalendar(year, month, this.currentDate.getMonth());
                return this.dates;
            },

            selectDate(element) {
                this.selectedDate = new Date(event.target.getAttribute('datetime'));

                //Search for the selected date in the dates array
                for (let i = 0; i < this.dates.length; i++) {
                    for (let j = 0; j < this.dates[i].length; j++) {
                        if (this.dates[i][j].dateString == this.selectedDate.toISOString()) {
                            this.dates[i][j].isSelected = true;
                        } else {
                            this.dates[i][j].isSelected = false;
                        }
                    }
                }
            },

            //Triggered when the currentDate has changed - We absolutely need to do this in pure JS because AlpineJS does not recreate the DOM elements when the data has changed.
            //for some reason class persist on the button even if the data has changed so we need to remove all the children in pure js and re-add them manually.
            //Really not optimal but it works.
            datesChanged() {
                const datesCalendar = document.getElementById('dates-calendar');

                //Remove all the children of the datesCalendar
                while (datesCalendar.firstChild) {
                    datesCalendar.removeChild(datesCalendar.firstChild);
                }

                for (let i = 0; i < this.dates.length; i++) {
                    for (let j = 0; j < this.dates[i].length; j++) {
                        //Add the new children
                        const button = document.createElement('button');
                        button.setAttribute('type', 'button');
                        button.setAttribute('class', 'py-1.5 hover:bg-gray-100 focus:z-10');
                        button.setAttribute('x-on:click', 'selectDate');
                        button.setAttribute('id', this.dates[i][j].dateString.split('T')[0]);

                        //Conditionnal class
                        if (i == 0 && j == 0) //First button
                            button.classList.add('rounded-tl-lg');
                        if (i == 0 && j == this.dates[i].length - 1) //Last button of the first row
                            button.classList.add('rounded-tr-lg');
                        if (i == this.dates.length - 1 && j == 0) //First button of the last row
                            button.classList.add('rounded-bl-lg');
                        if (i == this.dates.length - 1 && j == this.dates[i].length - 1) //Last button of the last row
                            button.classList.add('rounded-br-lg');

                        if (this.dates[i][j].isCurrentMonth)  //current month = white background else gray background
                            button.classList.add('bg-white');
                        else
                            button.classList.add('bg-gray-50');


                        if (this.dates[i][j].isToday) //Today = indigo text else gray text
                            button.classList.add('text-indigo-600', 'font-semibold');
                        else
                            button.classList.add('text-gray-400');

                        if (this.dates[i][j].isSelected && this.dates[i][j].isToday) //If the date is selected and is today font-semibold and text-white
                            button.classList.add('font-semibold', 'text-white');

                        if (this.dates[i][j].isSelected)
                            button.classList.add('text-white');
                        else if (!this.dates[i][j].isSelected && this.dates[i][j].isToday)
                            button.classList.add('text-indigo-600');
                        else if (!this.dates[i][j].isSelected && !this.dates[i][j].isToday && this.dates[i][j].isCurrentMonth)
                            button.classList.add('text-gray-900');
                        else if (!this.dates[i][j].isSelected && !this.dates[i][j].isToday && !this.dates[i][j].isCurrentMonth)
                            button.classList.add('text-gray-400');

                        if (this.dates[i][j].isToday && this.dates[i][j].isSelected)
                            button.classList.add('!text-white');





                        const time = document.createElement('time');
                        time.setAttribute('datetime', this.dates[i][j].dateString);
                        time.innerText = this.dates[i][j].day;
                        time.setAttribute('class', 'mx-auto flex h-7 w-7 items-center justify-center rounded-full');

                        const container = document.createElement('div');
                        container.setAttribute('class', 'flex ml-1 absolute -mt-1.5');

                        const circle = document.createElement('div');
                        circle.setAttribute('class', 'h-2 w-2 rounded-full bg-indigo-600');



                        //Conditionnal class
                        if (this.dates[i][j].isSelected && this.dates[i][j].isToday)
                            time.classList.add('bg-indigo-600');
                        if (this.dates[i][j].isSelected && !this.dates[i][j].isToday)
                            time.classList.add('bg-gray-900');



                        //Append the time to the button
                        button.appendChild(time);

                        //Example of how to add a circle to a date
                        if (this.dates[i][j].isToday) {
                            //Append the circle to the container
                            container.appendChild(circle);
                            //Append the circle to the time
                            button.appendChild(container);
                        }

                        //Append the button to the datesCalendar
                        datesCalendar.appendChild(button);

                    }
                }
            }
        };
    }
</script>
<?= $this->endSection() ?>