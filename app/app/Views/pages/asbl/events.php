<?= $this->extend('pages/default') ?>

<?= $this->section('page_title') ?>
   Guides et scoutes de Gosselies - Événements
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-6 py-12 lg:px-8 xl:py-20">
   <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Événements de l'unité</h1>

   <div class="mt-8 max-w-3xl text-lg text-gray-600">
      <p>
         Tout au long de l’année, l’ASBL organise des événements ouverts aux familles, aux anciens et aux amis de l’unité :
         soupers, ventes, fête d’unité… Ce sont des moments de rencontre, mais aussi une aide précieuse pour financer
         les camps, le matériel et l’entretien des locaux.
      </p>
      <p class="mt-4">Merci à toutes celles et ceux qui y participent et qui donnent un coup de main !</p>
   </div>

   <h2 class="mt-12 text-2xl font-semibold text-gray-900">Prochains événements</h2>
   <?php if ($events): ?>
      <ul role="list" class="mt-6 divide-y divide-gray-100 rounded-2xl bg-white shadow ring-1 ring-gray-200">
         <?php foreach ($events as $event): ?>
            <li>
               <a href="/en-pratique/agenda?event=<?= $event->id ?>" class="flex items-center justify-between gap-x-6 px-6 py-5 hover:bg-gray-50">
                  <div class="min-w-0">
                     <p class="font-semibold text-gray-900"><?= esc($event->title) ?></p>
                     <p class="mt-1 text-sm text-gray-500">
                        <time data-event='<?= esc(json_encode(['start_at' => $event->start_at, 'end_at' => $event->end_at, 'all_day' => $event->all_day]), 'attr') ?>'><?= esc($event->start_at) ?></time>
                        <?php if ($event->location): ?> · <?= esc($event->location) ?><?php endif; ?>
                     </p>
                  </div>
                  <span class="flex-none text-sm font-semibold text-indigo-600">Détails</span>
               </a>
            </li>
         <?php endforeach; ?>
      </ul>
   <?php else: ?>
      <?= component('notice', ['tone' => 'muted', 'class' => 'mt-6', 'message' => 'Aucun événement de l’unité n’est annoncé pour le moment. Les dates seront publiées dans l’agenda.']) ?>
   <?php endif; ?>

   <?= component('button', ['label' => 'Voir tout l\'agenda', 'href' => '/en-pratique/agenda', 'class' => 'mt-8']) ?>
</div>

<script>
   // Dates displayed as in the agenda (e.g. "Samedi 17 octobre 2026, de 18:00 à 23:00")
   document.querySelectorAll('time[data-event]').forEach(element => {
      element.textContent = formatEventPeriod(JSON.parse(element.dataset.event));
   });
</script>
<?= $this->endSection() ?>
