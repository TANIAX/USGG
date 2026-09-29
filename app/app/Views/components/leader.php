<?php
/**
 * A section leader (photo, name, function). Props:
 *  - leader: object with display_name, function, picture_url (and section_name, section_color for show_section)
 *  - variant: card (default: big centred photo) or row (small photo on the left)
 *  - show_section: shows the section of the leader (card)
 */
$variant = $variant ?? 'card';
$picture = $leader->picture_url ?? base_url('assets/img/question-mark.jpg');
?>
<?php if ($variant === 'row'): ?>
   <li class="<?= classes('flex items-center gap-x-3', $class) ?>">
      <img src="<?= esc($picture, 'attr') ?>" alt="" class="h-10 w-10 flex-none rounded-full bg-gray-100 object-cover">
      <div class="min-w-0">
         <p class="truncate text-sm font-semibold text-gray-900"><?= esc($leader->display_name) ?></p>
         <p class="truncate text-xs text-gray-500"><?= esc($leader->function ?? '') ?></p>
      </div>
   </li>
<?php else: ?>
   <li class="<?= classes('text-center', $class) ?>">
      <img src="<?= esc($picture, 'attr') ?>" alt="" class="mx-auto h-24 w-24 rounded-full bg-gray-100 object-cover">
      <h3 class="mt-4 text-base font-semibold leading-7 tracking-tight text-gray-900"><?= esc($leader->display_name) ?></h3>
      <p class="text-sm font-semibold leading-6 text-indigo-600"><?= esc($leader->function ?? '') ?></p>
      <?php if (!empty($show_section)): ?>
         <p class="mt-1 inline-flex items-center gap-x-1.5 text-xs text-gray-500">
            <span class="h-1.5 w-1.5 rounded-full" style="background-color: <?= esc($leader->section_color, 'attr') ?>"></span>
            <?= esc($leader->section_name) ?>
         </p>
      <?php endif; ?>
   </li>
<?php endif; ?>
