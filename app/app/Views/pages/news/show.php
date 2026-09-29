<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
    Guides et scoutes de Gosselies - <?= esc($event->title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
    $isPast = strtotime($event->end_at) < time();
    $firstSection = $event->sections[0] ?? null;
    $registration = preg_match('#^https?://#i', (string) $event->registration_url) ? $event->registration_url : null;
?>
<article class="mx-auto max-w-3xl px-6 py-10 lg:py-16" x-data="{ event: <?= esc($eventJson, 'attr') ?> }">
    <a href="/#actualites" class="text-sm font-medium text-gray-500 hover:text-gray-700">&larr; Toutes les actualités</a>

    <!-- Image, or the logo of the section on its colour -->
    <div class="mt-6 aspect-[16/9] overflow-hidden rounded-2xl bg-gray-100 ring-1 ring-gray-900/10">
        <?php if ($event->image_url): ?>
            <img src="<?= esc($event->image_url, 'attr') ?>" alt="" class="h-full w-full object-cover">
        <?php elseif ($firstSection): ?>
            <div class="flex h-full items-center justify-center" style="background-color: <?= esc($firstSection->color, 'attr') ?>1a">
                <img src="<?= base_url($firstSection->logo) ?>" alt="" class="h-2/3 w-auto rounded-2xl bg-white object-contain p-2 shadow">
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-6 flex flex-wrap items-center gap-2">
        <?php foreach ($event->sections as $section): ?>
            <?= component('section_tag', ['section' => $section]) ?>
        <?php endforeach; ?>
        <?php if ($isPast): ?>
            <?= component('badge', ['label' => 'Terminé', 'shape' => 'tag']) ?>
        <?php endif; ?>
    </div>

    <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"><?= esc($event->title) ?></h1>

    <dl class="mt-6 space-y-3 text-gray-700">
        <?= component('detail', ['icon' => 'calendar', 'label' => 'Date', 'slot' => '<span x-text="formatEventPeriod(event)">' . esc($event->start_at) . '</span>']) ?>
        <?php if ($event->location): ?>
            <?php component_open('detail', ['icon' => 'map-pin', 'label' => 'Lieu']) ?>
                <?= esc($event->location) ?>
                <a href="https://www.google.com/maps/search/?api=1&query=<?= rawurlencode($event->location) ?>" target="_blank" rel="noopener" class="ml-1 text-indigo-600 hover:text-indigo-500">(voir sur la carte)</a>
            <?= component_close() ?>
        <?php endif; ?>
    </dl>

    <?php if ($event->description): ?>
        <div class="mt-8 whitespace-pre-line text-lg leading-8 text-gray-700"><?= esc($event->description) ?></div>
    <?php else: ?>
        <p class="mt-8 text-gray-500">Plus d'informations suivront. N'hésitez pas à <a href="/contact" class="link">nous contacter</a> en cas de question.</p>
    <?php endif; ?>

    <div class="mt-10 flex flex-wrap items-center justify-between gap-6 border-t pt-6">
        <!-- Author -->
        <div class="flex items-center gap-x-3">
            <?php if ($event->author['picture_url']): ?>
                <img src="<?= esc($event->author['picture_url'], 'attr') ?>" alt="" class="h-10 w-10 rounded-full bg-gray-100 object-cover">
            <?php else: ?>
                <img src="<?= base_url('assets/img/logo-unite.png') ?>" alt="" class="h-10 w-10 rounded-full bg-white object-contain p-1 ring-1 ring-gray-200">
            <?php endif; ?>
            <div class="text-sm">
                <p class="text-gray-500">Publié par</p>
                <p class="font-semibold text-gray-900"><?= esc($event->author['name']) ?></p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <?= component('button', ['label' => 'Voir dans l\'agenda', 'href' => '/en-pratique/agenda?event=' . $event->id, 'variant' => 'secondary']) ?>
            <?php if ($registration && !$isPast): ?>
                <?= component('button', ['label' => 'S\'inscrire', 'href' => $registration, 'attrs' => ['target' => '_blank', 'rel' => 'noopener']]) ?>
            <?php endif; ?>
        </div>
    </div>
</article>
<?= $this->endSection() ?>
