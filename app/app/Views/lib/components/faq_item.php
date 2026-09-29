<?php
/**
 * Question of a FAQ, opened on click. Props: id (unique), question, and the answer in the slot (html) or answer (escaped).
 */
?>
<div x-data="{ open: false }" class="pt-6">
   <dt>
      <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-controls="<?= esc($id, 'attr') ?>" class="flex w-full items-start justify-between text-left text-gray-900">
         <span class="text-base font-semibold leading-7"><?= esc($question) ?></span>
         <span class="ml-6 flex h-7 items-center">
            <?= component('icon', ['name' => 'plus', 'class' => 'h-6 w-6', 'attrs' => ['x-show' => '!open']]) ?>
            <?= component('icon', ['name' => 'minus', 'class' => 'h-6 w-6', 'attrs' => ['x-show' => 'open']]) ?>
         </span>
      </button>
   </dt>
   <dd id="<?= esc($id, 'attr') ?>" x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
      x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300"
      x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90" class="mt-2 pr-12">
      <p class="text-base leading-7 text-gray-600"><?= $slot !== '' ? $slot : esc($answer ?? '') ?></p>
   </dd>
</div>
