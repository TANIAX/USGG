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
        <?php component_open('notice', ['class' => 'mt-6']) ?>
            <?= $hiddenCount ?> photo<?= $hiddenCount > 1 ? 's sont réservées' : ' est réservée' ?> aux membres.
            <a href="/auth/login" class="font-semibold underline hover:text-indigo-600">Connectez-vous</a> pour <?= $hiddenCount > 1 ? 'les' : 'la' ?> voir.
        <?= component_close() ?>
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
                    <?= component('badge', ['label' => 'Membres', 'color' => 'overlay', 'shape' => 'tag', 'class' => 'pointer-events-none absolute left-1.5 top-1.5', 'attrs' => ['x-show' => '!photo.is_public']]) ?>
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
            <?= component('icon_button', ['icon' => 'x-mark', 'icon_class' => 'h-7 w-7', 'label' => 'Fermer', 'class' => 'rounded p-1 hover:bg-white/10', 'attrs' => ['@click' => 'close()']]) ?>
        </div>
        <div class="relative flex flex-auto items-center justify-center overflow-hidden px-2 pb-6" @click.self="close()">
            <template x-if="current !== null">
                <img :src="`${baseUrl}galerie/photo/${photos[current].id}`" :alt="`Photo ${current + 1}`"
                    class="max-h-full max-w-full select-none object-contain">
            </template>
            <?= component('icon_button', ['icon' => 'chevron-left', 'icon_class' => 'h-6 w-6', 'label' => 'Photo précédente', 'class' => 'absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-black/40 p-2 text-white hover:bg-black/70',
                'attrs' => ['@click' => 'previous()', 'x-show' => 'photos.length > 1']]) ?>
            <?= component('icon_button', ['icon' => 'chevron-right', 'icon_class' => 'h-6 w-6', 'label' => 'Photo suivante', 'class' => 'absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-black/40 p-2 text-white hover:bg-black/70',
                'attrs' => ['@click' => 'next()', 'x-show' => 'photos.length > 1']]) ?>
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
        };
    }
</script>
<?= $this->endSection() ?>
