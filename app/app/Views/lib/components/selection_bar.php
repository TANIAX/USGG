<?php
/**
 * Bar of the actions on the selected items of a list, fixed at the bottom of the screen (Alpine). Props:
 *  - selected: javascript array of the selection (default "selected"), emptied by "Annuler"
 *  - noun: [singular, plural] of the items, e.g. ['document sélectionné', 'documents sélectionnés']
 *  - actions: list of ['label' => ..., 'click' => javascript, 'tone' => green|amber|red]
 *  - busy: javascript expression disabling the actions (optional)
 */
$selected = $selected ?? 'selected';
$tones = ['green' => 'text-green-300 hover:text-green-200', 'amber' => 'text-amber-300 hover:text-amber-200', 'red' => 'text-red-300 hover:text-red-200'];
?>
<div x-show="<?= esc($selected, 'attr') ?>.length > 0" x-transition class="fixed inset-x-0 bottom-4 z-40 flex justify-center px-4">
   <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 rounded-lg bg-gray-900 px-4 py-3 text-sm text-white shadow-xl">
      <span class="font-semibold" x-text="<?= esc("`\${{$selected}.length} \${{$selected}.length > 1 ? '" . addslashes($noun[1]) . "' : '" . addslashes($noun[0]) . "'}`", 'attr') ?>"></span>
      <?php foreach ($actions as $action): ?>
         <button type="button"<?= attrs(['@click' => $action['click'], ':disabled' => $busy ?? null, 'class' => 'font-medium ' . $tones[$action['tone']]]) ?>><?= esc($action['label']) ?></button>
      <?php endforeach; ?>
      <button type="button" @click="<?= esc($selected, 'attr') ?> = []" class="text-gray-400 hover:text-white">Annuler</button>
   </div>
</div>
