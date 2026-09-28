<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
    Guides et scoutes de Gosselies - <?= esc($album->title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-8 mt-8 mb-16" x-data="app()" x-cloak
    @keydown.escape.window="close()" @keydown.arrow-left.window="previous()" @keydown.arrow-right.window="next()">
    <a href="/galerie/<?= esc($branchUrl) ?>" class="text-sm font-medium text-gray-500 hover:text-gray-700">
        &larr; Galerie <?= $album->branch === 'GUIDE' ? 'des guides' : 'des scouts' ?>
    </a>
    <h1 class="mt-2 text-3xl md:text-4xl font-bold leading-tight"><?= esc($album->title) ?></h1>
    <p class="mt-2 text-sm text-gray-500">
        <span x-text="photos.length + ' photo' + (photos.length > 1 ? 's' : '')"></span>
        <?php if ($album->album_date): ?>
            · <span x-text="formatDate('<?= esc($album->album_date, 'js') ?>')"></span>
        <?php endif; ?>
    </p>
    <?php if ($album->description): ?>
        <p class="mt-4 max-w-3xl whitespace-pre-line text-gray-700"><?= esc($album->description) ?></p>
    <?php endif; ?>

    <?php if ($hiddenCount > 0): ?>
        <p class="mt-6 rounded-md bg-indigo-50 px-4 py-3 text-sm text-indigo-800">
            <?= $hiddenCount ?> photo<?= $hiddenCount > 1 ? 's sont réservées' : ' est réservée' ?> aux membres.
            <a href="/auth/login" class="font-semibold underline hover:text-indigo-600">Connectez-vous</a> pour <?= $hiddenCount > 1 ? 'les' : 'la' ?> voir.
        </p>
    <?php endif; ?>

    <!-- Grid -->
    <ul role="list" class="mt-8 grid grid-cols-2 gap-2 sm:grid-cols-3 sm:gap-3 lg:grid-cols-4">
        <template x-for="(photo, index) in photos" :key="photo.id">
            <li class="relative">
                <button type="button" @click="open(index)" class="group block w-full overflow-hidden rounded-md bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600">
                    <img :src="`${baseUrl}galerie/photo/${photo.id}/miniature`" :alt="`Photo ${index + 1}`" loading="lazy"
                        class="aspect-square w-full object-cover transition duration-300 group-hover:scale-105">
                </button>
                <?php if ($connected): ?>
                    <span x-show="!photo.is_public" class="pointer-events-none absolute left-1.5 top-1.5 rounded bg-black/60 px-1.5 py-0.5 text-xs text-white">Membres</span>
                <?php endif; ?>
            </li>
        </template>
    </ul>

    <!-- Viewer (moved to <body> so that it is displayed above the header of the site) -->
    <template x-teleport="body">
    <div x-show="current !== null" x-transition.opacity class="fixed inset-0 z-[100] flex flex-col bg-black" role="dialog" aria-modal="true" aria-label="Visionneuse de photos"
        @touchstart="touchStart($event)" @touchend="touchEnd($event)">
        <div class="flex items-center justify-between px-4 py-3 text-sm text-white">
            <span x-text="current !== null ? `${current + 1} / ${photos.length}` : ''"></span>
            <button type="button" @click="close()" class="rounded p-1 hover:bg-white/10">
                <span class="sr-only">Fermer</span>
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="relative flex flex-auto items-center justify-center overflow-hidden px-2 pb-6" @click.self="close()">
            <template x-if="current !== null">
                <img :src="`${baseUrl}galerie/photo/${photos[current].id}`" :alt="`Photo ${current + 1}`"
                    class="max-h-full max-w-full select-none object-contain">
            </template>
            <button type="button" @click="previous()" x-show="photos.length > 1"
                class="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-black/40 p-2 text-white hover:bg-black/70">
                <span class="sr-only">Photo précédente</span>
                <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                </svg>
            </button>
            <button type="button" @click="next()" x-show="photos.length > 1"
                class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-black/40 p-2 text-white hover:bg-black/70">
                <span class="sr-only">Photo suivante</span>
                <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
    </template>
</div>

<script>
    function app() {
        return {
            baseUrl: '<?= rtrim(base_url(), '/') ?>/',
            photos: <?= $photos ?>,
            current: null,
            touchX: null,

            open(index) {
                this.current = index;
                document.body.style.overflow = 'hidden';
                this.preload();
            },

            close() {
                this.current = null;
                document.body.style.overflow = '';
            },

            previous() {
                if (this.current === null) return;
                this.current = (this.current - 1 + this.photos.length) % this.photos.length;
                this.preload();
            },

            next() {
                if (this.current === null) return;
                this.current = (this.current + 1) % this.photos.length;
                this.preload();
            },

            // Loads the next photo in advance so that it appears immediately
            preload() {
                const next = this.photos[(this.current + 1) % this.photos.length];
                if (next)
                    new Image().src = `${this.baseUrl}galerie/photo/${next.id}`;
            },

            touchStart(event) {
                this.touchX = event.changedTouches[0].clientX;
            },

            touchEnd(event) {
                if (this.touchX === null) return;
                const distance = event.changedTouches[0].clientX - this.touchX;
                this.touchX = null;
                if (Math.abs(distance) > 50)
                    distance > 0 ? this.previous() : this.next();
            },

            formatDate(date) {
                return parseEventDate(date).toLocaleDateString('fr-BE', { day: 'numeric', month: 'long', year: 'numeric' });
            },
        };
    }
</script>
<?= $this->endSection() ?>
