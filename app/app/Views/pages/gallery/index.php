<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
    Guides et scoutes de Gosselies - Galerie photos
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
    $tabs = ['' => 'Toutes les unités', 'guide' => 'Guides', 'scout' => 'Scouts'];
?>
<div class="mx-auto max-w-7xl px-8 mt-8 mb-16">
    <h1 class="text-4xl xl:text-4xl font-bold leading-normal xl:leading-relaxed mb-2">GALERIE PHOTOS</h1>

    <nav class="mt-6 flex flex-wrap gap-2" aria-label="Unité">
        <?php foreach ($tabs as $code => $label): ?>
            <a href="/galerie<?= $code ? '/' . $code : '' ?>"
                class="rounded-full px-3 py-1 text-sm font-medium ring-1 ring-inset <?= ($branch ?? '') === $code ? 'bg-indigo-600 text-white ring-indigo-600' : 'bg-white text-gray-700 ring-gray-300 hover:bg-gray-50' ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <?php if ($hasHiddenPhotos): ?>
        <p class="mt-6 rounded-md bg-indigo-50 px-4 py-3 text-sm text-indigo-800">
            Certaines photos sont réservées aux membres.
            <a href="/auth/login" class="font-semibold underline hover:text-indigo-600">Connectez-vous</a> pour les voir.
        </p>
    <?php endif; ?>

    <?php if ($albums): ?>
        <ul role="list" class="mt-8 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($albums as $album): ?>
                <li>
                    <a href="/galerie/album/<?= $album->id ?>" class="group block">
                        <div class="aspect-[4/3] overflow-hidden rounded-lg bg-gray-100">
                            <!-- Photos of the cover shown in turn -->
                            <div class="relative h-full w-full transition duration-300 group-hover:scale-105" x-data="coverCarousel(<?= json_encode($album->cover_ids) ?>)">
                                <template x-for="(id, index) in ids" :key="id">
                                    <template x-if="loaded.includes(index)">
                                        <img :src="url(id)" alt="" loading="lazy"
                                            class="absolute inset-0 h-full w-full object-cover transition-opacity duration-1000"
                                            :class="index === current ? 'opacity-100' : 'opacity-0'">
                                    </template>
                                </template>
                                <!-- Position in the carousel -->
                                <div x-show="ids.length > 1" class="absolute inset-x-0 bottom-2 flex justify-center gap-1.5" aria-hidden="true">
                                    <template x-for="(id, index) in ids" :key="id">
                                        <span class="h-1.5 w-1.5 rounded-full shadow transition-colors" :class="index === current ? 'bg-white' : 'bg-white/50'"></span>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 flex items-start justify-between gap-x-3">
                            <h2 class="font-semibold text-gray-900 group-hover:text-indigo-600"><?= esc($album->title) ?></h2>
                            <span class="flex-none rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">
                                <?= $album->branch === 'GUIDE' ? 'Guides' : 'Scouts' ?>
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            <?= $album->photo_count ?> photo<?= $album->photo_count > 1 ? 's' : '' ?>
                            <?php if ($album->album_date): ?>
                                · <time datetime="<?= esc($album->album_date) ?>" data-date="<?= esc($album->album_date) ?>"><?= esc($album->album_date) ?></time>
                            <?php endif; ?>
                        </p>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="py-16 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            <p class="mt-2 text-sm text-gray-500">Aucun album pour le moment.</p>
            <?php if (!$connected): ?>
                <p class="mt-1 text-sm text-gray-500">Certains albums sont peut-être réservés aux membres : <a href="/auth/login" class="font-semibold text-indigo-600 hover:text-indigo-500">se connecter</a>.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    // Dates displayed in french (e.g. "12 août 2026")
    document.querySelectorAll('time[data-date]').forEach(element => {
        element.textContent = parseEventDate(element.dataset.date).toLocaleDateString('fr-BE', { day: 'numeric', month: 'long', year: 'numeric' });
    });
</script>
<?= $this->endSection() ?>
