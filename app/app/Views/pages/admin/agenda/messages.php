<?php $errors = session()->getFlashdata('errors'); ?>
<?php $success = session()->getFlashdata('success'); ?>

<?php if ($errors): ?>
   <div class="rounded-md bg-red-50 p-4 mb-6" x-data="{ open: true }" x-show="open">
      <div class="flex">
         <button type="button" @click="open = false" class="flex-shrink-0 self-start">
            <span class="sr-only">Fermer</span>
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
               <path fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                  clip-rule="evenodd" />
            </svg>
         </button>
         <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800"><?= count($errors) > 1 ? 'Erreurs détectées' : 'Erreur détectée' ?></h3>
            <ul role="list" class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
               <?php foreach ($errors as $message): ?>
                  <li><?= esc($message) ?></li>
               <?php endforeach; ?>
            </ul>
         </div>
      </div>
   </div>
<?php endif; ?>

<?php if ($success): ?>
   <div class="rounded-md bg-green-50 p-4 mb-6" x-data="{ open: true }" x-show="open">
      <div class="flex items-center">
         <svg class="h-5 w-5 flex-shrink-0 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
         </svg>
         <p class="ml-3 flex-auto text-sm font-medium text-green-800"><?= esc($success) ?></p>
         <button type="button" @click="open = false" class="ml-3 text-green-500 hover:text-green-700">
            <span class="sr-only">Fermer</span>
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
               <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
            </svg>
         </button>
      </div>
   </div>
<?php endif; ?>
