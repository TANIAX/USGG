<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
    Guides et scoutes de Gosselies - Agenda
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php ob_start(); ?>
                    <li>
                        <button type="button" @click="openEvent(event)"
                            class="group flex w-full items-start gap-x-4 py-5 text-left"
                            :class="isPast(event) ? 'opacity-60' : ''">
                            <!-- Section colors -->
                            <div class="flex w-1.5 self-stretch flex-col overflow-hidden rounded-full">
                                <template x-for="section in event.sections" :key="section.id">
                                    <span class="flex-1" :style="`background-color: ${section.color}`"></span>
                                </template>
                            </div>
                            <div class="flex-auto">
                                <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600">
                                    <span x-text="event.title"></span>
                                    <?= component('badge', ['label' => 'Terminé', 'shape' => 'tag', 'class' => 'ml-2', 'attrs' => ['x-show' => 'isPast(event)']]) ?>
                                </h3>
                                <dl class="mt-2 flex flex-col text-gray-500 xl:flex-row">
                                    <?= component('detail', ['icon' => 'calendar', 'label' => 'Date', 'slot' => '<time :datetime="event.start_at" x-text="formatEventPeriod(event)"></time>']) ?>
                                    <?= component('detail', ['icon' => 'map-pin', 'label' => 'Lieu', 'slot' => '<span x-text="event.location"></span>', 'class' => 'mt-2 xl:ml-3.5 xl:mt-0 xl:border-l xl:border-gray-400 xl:border-opacity-50 xl:pl-3.5', 'attrs' => ['x-show' => 'event.location']]) ?>
                                </dl>
                                <div class="mt-2 flex flex-wrap gap-1.5">
                                    <template x-for="section in event.sections" :key="section.id">
                                        <?= component('section_tag', ['alpine' => 'section']) ?>
                                    </template>
                                </div>
                            </div>
                            <?= component('icon', ['name' => 'chevron-right', 'class' => 'mt-1 h-5 w-5 flex-none text-gray-300 group-hover:text-indigo-600']) ?>
                        </button>
                    </li>
<?php $eventRow = ob_get_clean(); // Row of an event (Alpine "event"), in the list of the month and of the year ?>

<div class="mx-auto max-w-7xl px-8 mt-8" x-data="app()" x-cloak @keydown.escape.window="closeEvent()">
    <div
    class="absolute inset-y-0 right-1/2 -z-10 -mr-96 w-[200%] origin-top-right skew-x-[-30deg] bg-white shadow-xl shadow-indigo-600/10 ring-1 ring-indigo-50 sm:-mr-80 lg:-mr-96 hidden lg:block "
    aria-hidden="true"></div>
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-4xl xl:text-4xl font-bold leading-normal xl:leading-relaxed mb-2">AGENDA DES ACTIVITÉS</h1>
        <!-- Month (calendar) or whole year (list by month) -->
        <div class="inline-flex rounded-md shadow-sm" role="group" aria-label="Affichage">
            <?php foreach (['month' => 'Mois', 'year' => 'Année'] as $mode => $label): ?>
                <button type="button" @click="setMode('<?= $mode ?>')" :aria-pressed="(mode === '<?= $mode ?>').toString()"
                    class="px-4 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 <?= $mode === 'month' ? 'rounded-l-md' : '-ml-px rounded-r-md' ?>"
                    :class="mode === '<?= $mode ?>' ? 'z-10 bg-indigo-600 text-white ring-indigo-600' : 'bg-white text-gray-700 hover:bg-gray-50'"><?= $label ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Sections filter (also used as a legend for the colors of the calendar) -->
    <div class="mt-6">
        <p class="text-sm text-gray-500 mb-2">Afficher les activités de :</p>
        <div class="flex flex-wrap gap-2">
            <?= component('chip', ['label' => 'Toutes les sections', 'active_alpine' => 'selectedSections.length === 0', 'attrs' => ['@click' => 'selectedSections = []']]) ?>
            <template x-for="section in sections" :key="section.id">
                <button type="button" @click="toggleSection(section)"
                    class="flex items-center gap-x-2 rounded-full bg-white py-1 pl-1 pr-3 text-sm font-medium text-gray-700 ring-1 ring-inset hover:bg-gray-50"
                    :class="isSectionSelected(section) ? 'ring-2' : 'ring-gray-300'"
                    :style="isSectionSelected(section) ? `--tw-ring-color: ${section.color}` : ''">
                    <img x-show="section.logo" class="h-7 w-7 rounded-full bg-white object-contain p-0.5 ring-1 ring-gray-200" :src="assetUrl(section.logo)" :alt="section.name" @error="$el.style.display = 'none'">
                    <span class="h-2 w-2 rounded-full" :style="`background-color: ${section.color}`"></span>
                    <span x-text="section.name"></span>
                </button>
            </template>
        </div>
    </div>

    <div class="lg:grid lg:grid-cols-12 lg:gap-x-16">
        <!-- Year: the 12 months with their number of activities (a click opens the month) -->
        <div x-show="mode === 'year'" class="mt-10 lg:sticky lg:top-8 lg:col-start-8 lg:col-end-13 lg:row-start-1 lg:mt-9 lg:self-start xl:col-start-9">
            <div class="flex items-center text-gray-900">
                <?= component('icon_button', ['icon' => 'chevron-left', 'label' => 'Année précédente', 'class' => '-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500', 'attrs' => ['@click' => 'moveYear(-1)']]) ?>
                <div class="flex-auto text-center text-sm font-semibold" x-text="currentYear"></div>
                <?= component('icon_button', ['icon' => 'chevron-right', 'label' => 'Année suivante', 'class' => '-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500', 'attrs' => ['@click' => 'moveYear(1)']]) ?>
            </div>
            <div class="mt-6 grid grid-cols-3 gap-2">
                <template x-for="month in yearMonths" :key="month.index">
                    <button type="button" @click="openMonth(month.index)" class="rounded-lg bg-white p-2 text-left ring-1 ring-gray-200 hover:ring-indigo-400"
                        :class="month.isCurrent ? 'ring-2 ring-indigo-600' : ''" :aria-label="`${month.name} : ${month.events.length} activité(s)`">
                        <span class="block text-xs font-semibold uppercase text-gray-700" x-text="month.shortName"></span>
                        <span class="mt-1 block text-lg font-semibold" :class="month.events.length ? 'text-gray-900' : 'text-gray-300'" x-text="month.events.length"></span>
                        <span class="mt-1 flex h-1.5 gap-0.5">
                            <template x-for="color in month.colors.slice(0, 5)" :key="color"><span class="h-1.5 w-1.5 rounded-full" :style="`background-color: ${color}`"></span></template>
                        </span>
                    </button>
                </template>
            </div>
            <p x-show="loading" class="mt-2 text-xs text-gray-500">Chargement des activités…</p>
            <p x-show="loadingError" class="mt-2 text-xs text-red-600">Impossible de charger les activités. Réessayez plus tard.</p>
        </div>

        <!-- Calendar -->
        <div x-show="mode === 'month'" class="mt-10 text-center lg:col-start-8 lg:col-end-13 lg:row-start-1 lg:mt-9 xl:col-start-9">
            <div class="flex items-center text-gray-900">
                <?= component('icon_button', ['icon' => 'chevron-left', 'label' => 'Mois précédent', 'class' => '-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500', 'attrs' => ['@click' => 'moveCurrentDateTo(-1)']]) ?>
                <div class="flex-auto text-sm font-semibold uppercase" x-text="currentDateString"></div>
                <?= component('icon_button', ['icon' => 'chevron-right', 'label' => 'Mois suivant', 'class' => '-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500', 'attrs' => ['@click' => 'moveCurrentDateTo(1)']]) ?>
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
                        class="relative pt-1.5 pb-2.5 hover:bg-gray-100 focus:z-10"
                        :aria-label="dayLabel(day)"
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
                        <!-- One dot per section having an activity this day -->
                        <span class="absolute inset-x-0 bottom-1 flex justify-center gap-0.5" aria-hidden="true">
                            <template x-for="color in dayColors(day).slice(0, 4)" :key="color">
                                <span class="h-1.5 w-1.5 rounded-full" :style="`background-color: ${color}`"></span>
                            </template>
                        </span>
                    </button>
                </template>
            </div>

            <p x-show="loading" class="mt-2 text-xs text-gray-500">Chargement des activités…</p>
            <p x-show="loadingError" class="mt-2 text-xs text-red-600">Impossible de charger les activités. Réessayez plus tard.</p>
        </div>

        <!-- Events list of the year, by month -->
        <div x-show="mode === 'year'" class="mt-10 lg:col-span-7 xl:col-span-8 lg:mt-9">
            <h2 class="font-semibold leading-6 text-gray-900 text-xl md:text-2xl" x-text="`Activités de ${currentYear}`"></h2>
            <template x-for="month in yearMonths.filter(month => month.events.length)" :key="month.index">
                <section class="mt-8">
                    <h3 class="sticky top-0 z-10 -mx-2 flex items-baseline justify-between bg-white/90 px-2 py-2 text-sm font-semibold uppercase tracking-wide text-gray-500 backdrop-blur">
                        <span x-text="month.name"></span>
                        <span class="text-xs font-normal normal-case" x-text="`${month.events.length} activité${month.events.length > 1 ? 's' : ''}`"></span>
                    </h3>
                    <ol class="divide-y divide-gray-100 text-sm leading-6">
                        <template x-for="event in month.events" :key="event.id">
                            <?= $eventRow ?>
                        </template>
                    </ol>
                </section>
            </template>
            <div x-show="!loading && yearMonths.every(month => month.events.length === 0)" class="py-10 text-center">
                <?= component('icon', ['name' => 'calendar', 'class' => 'mx-auto h-12 w-12 text-gray-300']) ?>
                <p class="mt-2 text-sm text-gray-500">Aucune activité prévue cette année.</p>
            </div>
        </div>

        <!-- Events list -->
        <div x-show="mode === 'month'" class="mt-10 lg:col-span-7 xl:col-span-8 lg:mt-9">
            <div class="flex items-center justify-between gap-x-4">
                <h2 class="font-semibold leading-6 text-gray-900 text-xl md:text-2xl" x-text="listTitle"></h2>
                <button type="button" x-show="selectedKey" @click="selectedKey = null"
                    class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 whitespace-nowrap">
                    Voir tout le mois
                </button>
            </div>

            <ol class="mt-4 divide-y divide-gray-100 text-sm leading-6">
                <template x-for="event in listedEvents" :key="event.id">
                    <?= $eventRow ?>
                </template>
            </ol>

            <!-- Empty state -->
            <div x-show="!loading && listedEvents.length === 0" class="py-10 text-center">
                <?= component('icon', ['name' => 'calendar', 'class' => 'mx-auto h-12 w-12 text-gray-300']) ?>
                <p class="mt-2 text-sm text-gray-500" x-text="selectedKey ? 'Aucune activité prévue ce jour-là.' : 'Aucune activité prévue ce mois-ci.'"></p>
            </div>
        </div>
    </div>

    <!-- Event details -->
    <div x-show="openedEvent" class="relative z-50" role="dialog" aria-modal="true" aria-labelledby="event-title">
        <div x-show="openedEvent" x-transition.opacity class="fixed inset-0 bg-gray-500/75"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 sm:items-center" @click.self="closeEvent()">
                <template x-if="openedEvent">
                    <div x-transition class="relative w-full max-w-lg overflow-hidden rounded-lg bg-white text-left shadow-xl">
                        <div class="flex h-2">
                            <template x-for="section in openedEvent.sections" :key="section.id">
                                <span class="flex-1" :style="`background-color: ${section.color}`"></span>
                            </template>
                        </div>
                        <img x-show="openedEvent.image_url" :src="openedEvent.image_url" alt="" class="aspect-[16/9] w-full object-cover">
                        <div class="px-6 pb-6 pt-5">
                            <div class="flex items-start justify-between gap-x-4">
                                <h3 id="event-title" class="text-xl font-semibold text-gray-900" x-text="openedEvent.title"></h3>
                                <?= component('icon_button', ['icon' => 'x-mark', 'icon_class' => 'h-6 w-6', 'label' => 'Fermer', 'class' => 'rounded-md text-gray-400 hover:text-gray-500', 'attrs' => ['@click' => 'closeEvent()']]) ?>
                            </div>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <template x-for="section in openedEvent.sections" :key="section.id">
                                    <span class="inline-flex items-center gap-x-2 rounded-full py-0.5 pl-0.5 pr-2.5 text-sm font-medium text-gray-700 ring-1 ring-inset ring-gray-200">
                                        <img x-show="section.logo" class="h-7 w-7 rounded-full bg-white object-contain p-0.5 ring-1 ring-gray-200" :src="assetUrl(section.logo)" alt="" @error="$el.style.display = 'none'">
                                        <span x-text="section.name"></span>
                                    </span>
                                </template>
                            </div>

                            <dl class="mt-5 space-y-3 text-sm text-gray-700">
                                <?= component('detail', ['icon' => 'calendar', 'label' => 'Date', 'slot' => '<span x-text="formatEventPeriod(openedEvent)"></span>']) ?>
                                <?php component_open('detail', ['icon' => 'map-pin', 'label' => 'Lieu', 'attrs' => ['x-show' => 'openedEvent.location']]) ?>
                                    <span x-text="openedEvent.location"></span>
                                    <a :href="'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(openedEvent.location || '')"
                                        target="_blank" rel="noopener" class="ml-1 text-indigo-600 hover:text-indigo-500">(voir sur la carte)</a>
                                <?= component_close() ?>
                            </dl>

                            <p x-show="openedEvent.description" class="mt-5 whitespace-pre-line text-sm leading-6 text-gray-600"
                                x-text="openedEvent.description"></p>
                            <p class="mt-5 text-xs text-gray-500" x-show="openedEvent.author" x-text="'Publié par ' + (openedEvent.author ? openedEvent.author.name : '')"></p>
                            <a :href="`/actualites/${openedEvent.id}`" class="mt-2 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-500">Voir la page de l'événement</a>

                            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                                <?= component('button', ['label' => 'Fermer', 'variant' => 'secondary', 'size' => 'sm', 'attrs' => ['@click' => 'closeEvent()']]) ?>
                                <?= component('button', ['label' => 'S\'inscrire', 'size' => 'sm', 'attrs' => ['x-show' => 'registrationUrl(openedEvent) && !isPast(openedEvent)', ':href' => 'registrationUrl(openedEvent)', 'target' => '_blank', 'rel' => 'noopener'], 'href' => '#']) ?>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
<script>
    function app() {
        return {
            apiUrl: '<?= base_url('api/v1/agenda') ?>',
            baseUrl: '<?= rtrim(base_url(), '/') ?>/',
            sections: <?= $sections ?>,
            selectedSections: [],
            // Displayed month (local time)
            currentYear: new Date().getFullYear(),
            currentMonth: new Date().getMonth(),
            selectedKey: null,
            // "month" (calendar) or "year" (all the activities of the year, by month): ?vue=annee&annee=2026
            mode: 'month',
            days: [],
            events: [],
            loading: false,
            loadingError: false,
            openedEvent: null,
            requestId: 0,

            init() {
                const params = new URLSearchParams(window.location.search);
                if (params.get('vue') === 'annee') {
                    this.mode = 'year';
                    const year = parseInt(params.get('annee'));
                    if (year >= 2000 && year <= 2100)
                        this.currentYear = year;
                }
                this.refresh();
                this.openEventFromUrl();
            },

            setMode(mode) {
                if (this.mode === mode)
                    return;
                this.mode = mode;
                this.selectedKey = null;
                this.refresh();
            },

            moveYear(value) {
                this.currentYear += value;
                this.refresh();
            },

            // From the year: opens the calendar of a month
            openMonth(month) {
                this.mode = 'month';
                this.goToMonth(this.currentYear, month);
            },

            // The 12 months of the year with their activities (by month of start) and the colours of their sections
            get yearMonths() {
                const today = new Date();
                return Array.from({ length: 12 }, (_, index) => {
                    const first = new Date(this.currentYear, index, 1);
                    const events = this.filteredEvents.filter(event => {
                        const start = parseEventDate(event.start_at);
                        // An activity started the year before is shown in January
                        return (start.getFullYear() < this.currentYear ? 0 : start.getMonth()) === index;
                    });
                    return {
                        index: index,
                        name: first.toLocaleDateString('fr-BE', { month: 'long' }),
                        shortName: first.toLocaleDateString('fr-BE', { month: 'short' }),
                        isCurrent: today.getFullYear() === this.currentYear && today.getMonth() === index,
                        events: events,
                        colors: [...new Set(events.flatMap(event => event.sections.map(section => section.color)))],
                    };
                });
            },

            get currentDateString() {
                return new Date(this.currentYear, this.currentMonth, 1).toLocaleDateString('fr-BE', {
                    month: 'long',
                    year: 'numeric',
                });
            },

            get listTitle() {
                if (this.selectedKey) {
                    const date = parseEventDate(this.selectedKey);
                    return 'Activités du ' + date.toLocaleDateString('fr-BE', { weekday: 'long', day: 'numeric', month: 'long' });
                }
                return 'Activités de ' + this.currentDateString;
            },

            // Events matching the sections filter
            get filteredEvents() {
                if (this.selectedSections.length === 0)
                    return this.events;
                return this.events.filter(event => event.sections.some(section => this.selectedSections.includes(section.id)));
            },

            // Events of the selected day, or of the displayed month
            get listedEvents() {
                if (this.selectedKey)
                    return this.filteredEvents.filter(event => event.dayKeys.includes(this.selectedKey));

                const monthStart = toDateKey(new Date(this.currentYear, this.currentMonth, 1));
                const monthEnd = toDateKey(new Date(this.currentYear, this.currentMonth + 1, 0));
                return this.filteredEvents.filter(event =>
                    event.start_at.substring(0, 10) <= monthEnd && event.end_at.substring(0, 10) >= monthStart);
            },

            // Colors of the sections having an activity for each day of the grid ({YYYY-MM-DD: [colors]})
            get colorsByDay() {
                const colors = {};
                this.filteredEvents.forEach(event => {
                    event.dayKeys.forEach(key => {
                        colors[key] = colors[key] || [];
                        event.sections.forEach(section => {
                            if (!colors[key].includes(section.color))
                                colors[key].push(section.color);
                        });
                    });
                });
                return colors;
            },

            // Move from one or several months without going through Date.setMonth(),
            // which skips a month when the current day does not exist in the target month (e.g. 31/01 -> 03/03).
            moveCurrentDateTo(value) {
                const target = new Date(this.currentYear, this.currentMonth + value, 1);
                this.goToMonth(target.getFullYear(), target.getMonth());
            },

            goToMonth(year, month) {
                this.currentYear = year;
                this.currentMonth = month;
                this.selectedKey = null;
                this.refresh();
            },

            refresh() {
                this.days = createCalendar(this.currentYear, this.currentMonth).flat();
                this.updateUrl();
                this.loadEvents();
            },

            // The view is kept in the url (reload, link)
            updateUrl() {
                const url = new URL(window.location);
                url.searchParams.delete('vue');
                url.searchParams.delete('annee');
                if (this.mode === 'year') {
                    url.searchParams.set('vue', 'annee');
                    url.searchParams.set('annee', this.currentYear);
                }
                window.history.replaceState({}, '', url);
            },

            // Loads the events of the whole grid (including the days of the previous/next month), or of the whole year
            async loadEvents() {
                const requestId = ++this.requestId;
                const start = this.mode === 'year' ? `${this.currentYear}-01-01` : this.days[0].key;
                const end = this.mode === 'year' ? `${this.currentYear}-12-31` : this.days[this.days.length - 1].key;
                this.loading = true;
                this.loadingError = false;

                try {
                    const json = await requestJson(`${this.apiUrl}?start=${start}&end=${end}`);
                    // Ignore the response if the user already moved to another month
                    if (requestId === this.requestId)
                        this.events = (json.data || []).map(withDayKeys);
                } catch (error) {
                    if (requestId === this.requestId) {
                        this.events = [];
                        this.loadingError = true;
                    }
                } finally {
                    if (requestId === this.requestId)
                        this.loading = false;
                }
            },

            // Opens the event given in the url (?event=12), so that an event can be shared with a link
            async openEventFromUrl() {
                const id = parseInt(new URLSearchParams(window.location.search).get('event'));
                if (!id)
                    return;

                try {
                    const json = await requestJson(`${this.apiUrl}/${id}`);
                    const start = parseEventDate(json.data.start_at);
                    if (this.mode === 'year') {
                        if (start.getFullYear() !== this.currentYear) {
                            this.currentYear = start.getFullYear();
                            this.refresh();
                        }
                    } else {
                        this.goToMonth(start.getFullYear(), start.getMonth());
                    }
                    this.openEvent(withDayKeys(json.data));
                } catch (error) {
                    // The agenda stays usable without the event
                }
            },

            openEvent(event) {
                this.openedEvent = event;
                const url = new URL(window.location);
                url.searchParams.set('event', event.id);
                window.history.replaceState({}, '', url);
            },

            closeEvent() {
                if (!this.openedEvent)
                    return;
                this.openedEvent = null;
                const url = new URL(window.location);
                url.searchParams.delete('event');
                window.history.replaceState({}, '', url);
            },

            selectDate(day) {
                this.selectedKey = this.selectedKey === day.key ? null : day.key;
            },

            isSelected(day) {
                return this.selectedKey === day.key;
            },

            dayColors(day) {
                return this.colorsByDay[day.key] || [];
            },

            dayLabel(day) {
                const count = this.filteredEvents.filter(event => event.dayKeys.includes(day.key)).length;
                return parseEventDate(day.key).toLocaleDateString('fr-BE', { day: 'numeric', month: 'long' })
                    + (count ? ` (${count} activité${count > 1 ? 's' : ''})` : '');
            },

            toggleSection(section) {
                if (this.isSectionSelected(section))
                    this.selectedSections = this.selectedSections.filter(id => id !== section.id);
                else
                    this.selectedSections.push(section.id);
            },

            isSectionSelected(section) {
                return this.selectedSections.includes(section.id);
            },

            isPast(event) {
                return parseEventDate(event.end_at) < new Date();
            },

            // Only http(s) links are displayed
            registrationUrl(event) {
                return /^https?:\/\//i.test(event.registration_url || '') ? event.registration_url : null;
            },

            assetUrl(path) {
                return this.baseUrl + path;
            },
        };
    }

    // Adds to an event the list of the days (YYYY-MM-DD) it covers
    function withDayKeys(event) {
        const dayKeys = [];
        const current = parseEventDate(event.start_at.substring(0, 10));
        const last = event.end_at.substring(0, 10);
        while (toDateKey(current) <= last) {
            dayKeys.push(toDateKey(current));
            current.setDate(current.getDate() + 1);
        }
        return { ...event, dayKeys };
    }
</script>
<?= $this->endSection() ?>
