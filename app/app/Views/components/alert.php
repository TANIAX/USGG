<?php
/**
 * Alert box. Props:
 *  - type: "error" (default) or "success"
 *  - messages: list of messages (error: bulleted list) or message: a single message
 *  - title: title of an error alert (default "Erreur détectée" / "Erreurs détectées")
 *  - dismissible: close button (default true)
 */
$type = $type ?? 'error';
$messages = array_values((array) ($messages ?? ($message ?? [])));
$dismissible = $dismissible ?? true;
$root = ['class' => classes($type === 'error' ? 'rounded-md bg-red-50 p-4' : 'rounded-md bg-green-50 p-4', $class)]
   + ($dismissible ? ['x-data' => '{ open: true }', 'x-show' => 'open'] : []) + $attrs;
?>
<?php if ($type === 'error'): ?>
   <div<?= attrs($root) ?> role="alert">
      <div class="flex">
         <?php if ($dismissible): ?>
            <button type="button" @click="open = false" class="flex-shrink-0 self-start">
               <span class="sr-only">Fermer</span>
               <?= component('icon', ['name' => 'x-circle', 'class' => 'h-5 w-5 text-red-400']) ?>
            </button>
         <?php else: ?>
            <?= component('icon', ['name' => 'x-circle', 'class' => 'h-5 w-5 flex-shrink-0 text-red-400']) ?>
         <?php endif; ?>
         <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800"><?= esc($title ?? (count($messages) > 1 ? 'Erreurs détectées' : 'Erreur détectée')) ?></h3>
            <ul role="list" class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
               <?php foreach ($messages as $item): ?>
                  <li><?= esc($item) ?></li>
               <?php endforeach; ?>
            </ul>
         </div>
      </div>
   </div>
<?php else: ?>
   <div<?= attrs($root) ?> role="status">
      <div class="flex items-center">
         <?= component('icon', ['name' => 'check-circle', 'class' => 'h-5 w-5 flex-shrink-0 text-green-400']) ?>
         <p class="ml-3 flex-auto text-sm font-medium text-green-800"><?= esc(implode(' ', $messages)) ?></p>
         <?php if ($dismissible): ?>
            <button type="button" @click="open = false" class="ml-3 text-green-500 hover:text-green-700">
               <span class="sr-only">Fermer</span>
               <?= component('icon', ['name' => 'x-mark-mini', 'class' => 'h-5 w-5']) ?>
            </button>
         <?php endif; ?>
      </div>
   </div>
<?php endif; ?>
