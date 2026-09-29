<?= $this->extend('pages/default') ?>

<?= $this->section('page_title') ?>
    <?= esc($unitName) ?> - Documents
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="relative isolate overflow-hidden bg-gradient-to-b from-indigo-100/20">
   <div class="mx-auto max-w-7xl px-6 py-8 xl:py-20 lg:px-8">
      <h1 class="text-4xl font-bold leading-normal xl:leading-relaxed mb-2">Téléchargement de documents</h1>
      <p class="max-w-2xl text-gray-600">
         Documents utiles pour <?= esc($unitLabel) ?> : fiches à compléter,
         autorisations et informations pratiques. En cas de question, <a href="/contact" class="link">contactez-nous</a>.
      </p>

      <?php if ($files): ?>
         <table class="mt-8 min-w-full divide-y divide-gray-300">
            <thead>
               <tr>
                  <th scope="col" class="py-3.5 pr-3 text-left text-sm font-semibold text-gray-900">Documents</th>
                  <th scope="col" class="py-3.5 pl-3 text-right text-sm font-semibold text-gray-900"><span class="sr-only">Télécharger</span></th>
               </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
               <?php foreach ($files as $file) : ?>
                  <tr>
                     <td class="py-4 pr-3 text-sm text-gray-600 break-words"><?= esc($file->name) ?></td>
                     <td class="whitespace-nowrap py-4 pl-3 text-right text-sm font-medium">
                        <a href="<?= esc($documentUrl) ?>/<?= $file->id ?>" download="<?= esc($file->name) ?>" class="text-indigo-600 hover:text-indigo-900">Télécharger</a>
                     </td>
                  </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      <?php else: ?>
         <?= component('notice', ['tone' => 'muted', 'class' => 'mt-8', 'message' => 'Aucun document n\'est disponible pour le moment.']) ?>
      <?php endif; ?>
   </div>
</div>

<?= $this->endSection() ?>
