<?php use App\Helpers\AuditHelper; ?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Administration
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
   <div class="px-4 sm:px-6 lg:px-8">
      <?= component('page_header', ['title' => 'Tableau de bord', 'subtitle' => 'Bonjour ' . ($user->getTotem() ?: $user->getFirstname()) . ', voici ce qui attend et les outils auxquels vous avez accès.']) ?>

      <?= component('flash') ?>

      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3" data-dashboard-cards>
         <?php foreach ($cards as $card): ?>
            <a href="<?= base_url($card['href']) ?>" class="group rounded-lg bg-white p-5 shadow-sm ring-1 <?= !empty($card['highlight']) ? 'ring-indigo-300' : 'ring-gray-200' ?> hover:ring-indigo-500">
               <div class="flex items-center gap-x-3">
                  <?= component('icon', ['name' => $card['icon'], 'class' => 'h-6 w-6 text-indigo-600']) ?>
                  <p class="text-sm font-medium text-gray-600"><?= esc($card['label']) ?></p>
               </div>
               <p class="mt-3 text-3xl font-semibold tracking-tight text-gray-900" data-value><?= esc((string) $card['value']) ?></p>
               <?php if ($card['items']): ?>
                  <ul class="mt-3 space-y-1 text-sm text-gray-500">
                     <?php foreach ($card['items'] as $item): ?><li class="truncate"><?= esc($item) ?></li><?php endforeach; ?>
                  </ul>
               <?php endif; ?>
            </a>
         <?php endforeach; ?>
      </div>

      <div class="mt-12 grid grid-cols-1 gap-12 pb-16 <?= $history ? 'lg:grid-cols-3' : '' ?>">
         <div class="<?= $history ? 'lg:col-span-2' : '' ?> space-y-10">
            <?php foreach ($groups as $group => $links): ?>
               <section>
                  <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500"><?= esc($group) ?></h2>
                  <ul role="list" class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                     <?php foreach ($links as $link): ?>
                        <li>
                           <a href="<?= base_url($link['href']) ?>" class="flex items-start gap-x-3 rounded-lg p-3 ring-1 ring-gray-200 hover:bg-gray-50">
                              <?= component('icon', ['name' => $link['icon'], 'class' => 'mt-0.5 h-5 w-5 flex-none text-gray-400']) ?>
                              <span>
                                 <span class="block font-medium text-gray-900"><?= esc($link['label']) ?></span>
                                 <span class="block text-sm text-gray-500"><?= esc($link['description']) ?></span>
                              </span>
                           </a>
                        </li>
                     <?php endforeach; ?>
                  </ul>
               </section>
            <?php endforeach; ?>
         </div>

         <?php if ($history): ?>
            <section>
               <div class="flex items-baseline justify-between">
                  <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Dernières actions</h2>
                  <a href="<?= base_url('admin/historique') ?>" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Tout voir</a>
               </div>
               <ol role="list" class="mt-3 divide-y divide-gray-100 text-sm">
                  <?php foreach ($history as $entry): ?>
                     <li class="py-2">
                        <p class="text-gray-900"><?= esc(AuditHelper::ACTIONS[$entry->action] ?? $entry->action) ?> · <span class="font-medium"><?= esc($entry->subject) ?></span> : <?= esc($entry->label) ?></p>
                        <p class="text-xs text-gray-500"><?= esc($entry->totem ?: ($entry->firstname ?? 'Compte supprimé')) ?> · <?= esc(\App\Helpers\DateHelper::french($entry->created_at, true)) ?></p>
                     </li>
                  <?php endforeach; ?>
               </ol>
            </section>
         <?php endif; ?>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
