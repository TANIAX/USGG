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
            <?= component('chip', ['label' => $label, 'href' => '/galerie' . ($code ? '/' . $code : ''), 'active' => ($branch ?? '') === $code]) ?>
        <?php endforeach; ?>
    </nav>

    <?php if ($hasHiddenPhotos): ?>
        <?php component_open('notice', ['class' => 'mt-6']) ?>
            Certaines photos sont réservées aux membres.
            <a href="/auth/login" class="font-semibold underline hover:text-indigo-600">Connectez-vous</a> pour les voir.
        <?= component_close() ?>
    <?php endif; ?>

    <?php if ($albums): ?>
        <ul role="list" class="mt-8 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($albums as $album): ?>
                <li>
                    <a href="/galerie/album/<?= $album->id ?>" class="group block">
                        <div class="aspect-[4/3] overflow-hidden rounded-lg bg-gray-100">
                            <!-- Photos of the cover shown in turn -->
                            <?= component('cover_carousel', ['ids' => $album->cover_ids, 'class' => 'transition duration-300 group-hover:scale-105']) ?>
                        </div>
                        <div class="mt-3 flex items-start justify-between gap-x-3">
                            <h2 class="font-semibold text-gray-900 group-hover:text-indigo-600"><?= esc($album->title) ?></h2>
                            <?= component('badge', ['label' => $album->branch === 'GUIDE' ? 'Guides' : 'Scouts', 'class' => 'flex-none']) ?>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            <?= $album->photo_count ?> photo<?= $album->photo_count > 1 ? 's' : '' ?>
                            <?php if ($album->album_date): ?>
                                · <time datetime="<?= esc($album->album_date, 'attr') ?>" x-data x-text="formatDate('<?= esc($album->album_date, 'js') ?>')"><?= esc($album->album_date) ?></time>
                            <?php endif; ?>
                        </p>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?= component('pagination', ['pagination' => $pagination, 'noun' => ['album', 'albums'], 'class' => 'mt-12',
            'url' => fn(int $page) => base_url('galerie' . ($branch ? '/' . $branch : '')) . ($page > 1 ? '?page=' . $page : '')]) ?>
    <?php else: ?>
        <?php component_open('empty_state', ['icon' => 'photo', 'text' => 'Aucun album pour le moment.', 'class' => 'py-16']) ?>
            <?php if (!$connected): ?>
                <p class="mt-1 text-sm text-gray-500">Certains albums sont peut-être réservés aux membres : <a href="/auth/login" class="font-semibold text-indigo-600 hover:text-indigo-500">se connecter</a>.</p>
            <?php endif; ?>
        <?= component_close() ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
