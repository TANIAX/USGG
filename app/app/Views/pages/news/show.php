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
            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-gray-50 px-3 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-200">
                <span class="h-1.5 w-1.5 rounded-full" style="background-color: <?= esc($section->color, 'attr') ?>"></span>
                <?= esc($section->name) ?>
            </span>
        <?php endforeach; ?>
        <?php if ($isPast): ?>
            <span class="rounded bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500">Terminé</span>
        <?php endif; ?>
    </div>

    <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"><?= esc($event->title) ?></h1>

    <dl class="mt-6 space-y-3 text-gray-700">
        <div class="flex items-start gap-x-3">
            <dt class="mt-0.5"><span class="sr-only">Date</span>
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" /></svg>
            </dt>
            <dd x-text="formatEventPeriod(event)"><?= esc($event->start_at) ?></dd>
        </div>
        <?php if ($event->location): ?>
            <div class="flex items-start gap-x-3">
                <dt class="mt-0.5"><span class="sr-only">Lieu</span>
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" /></svg>
                </dt>
                <dd>
                    <?= esc($event->location) ?>
                    <a href="https://www.google.com/maps/search/?api=1&query=<?= rawurlencode($event->location) ?>" target="_blank" rel="noopener" class="ml-1 text-indigo-600 hover:text-indigo-500">(voir sur la carte)</a>
                </dd>
            </div>
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
            <a href="/en-pratique/agenda?event=<?= $event->id ?>" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Voir dans l'agenda</a>
            <?php if ($registration && !$isPast): ?>
                <a href="<?= esc($registration, 'attr') ?>" target="_blank" rel="noopener" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">S'inscrire</a>
            <?php endif; ?>
        </div>
    </div>
</article>
<?= $this->endSection() ?>
