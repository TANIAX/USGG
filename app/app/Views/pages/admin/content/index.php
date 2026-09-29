<?php
   use App\Controllers\ContentController;

   // Buttons of an item: move, show / hide, edit, delete
   $actions = function (string $type, $item, int $index, int $count) {
      $url = base_url('admin/contenus/' . $type . '/' . $item->id);
      $form = fn(string $action, string $content, string $class = '', string $confirm = '') =>
         '<form method="POST" action="' . $url . '/' . $action . '"' . ($confirm ? ' onsubmit="return confirm(\'' . esc($confirm, 'js') . '\')"' : '') . '>' . $content . '</form>';
      $html = $form('up', component('icon_button', ['icon' => 'chevron-up', 'label' => 'Monter', 'attrs' => ['type' => 'submit', 'disabled' => $index === 0]]))
         . $form('down', component('icon_button', ['icon' => 'chevron-down', 'label' => 'Descendre', 'attrs' => ['type' => 'submit', 'disabled' => $index === $count - 1]]))
         . $form('toggle', '<button type="submit" class="ml-2 text-sm font-medium text-gray-600 hover:text-gray-900">' . ($item->is_active ? 'Masquer' : 'Afficher') . '</button>')
         . '<a href="' . base_url('admin/contenus/' . $type . '/edit/' . $item->id) . '" class="ml-3 text-sm font-medium text-indigo-600 hover:text-indigo-900">Modifier</a>'
         . $form('delete', '<button type="submit" class="ml-3 text-sm font-medium text-red-600 hover:text-red-900">Supprimer</button>', '', 'Supprimer définitivement ?');
      return '<div class="flex flex-none items-center">' . $html . '</div>';
   };
?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - FAQ et témoignages
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
   <div class="px-4 sm:px-6 lg:px-8">
      <?php component_open('page_header', ['title' => 'FAQ et témoignages', 'subtitle' => 'Contenus de la page d\'accueil, affichés dans l\'ordre de cette page. Un contenu masqué est conservé mais n\'apparaît pas sur le site.']) ?>
         <?= component('button', ['label' => 'Voir l\'accueil', 'href' => '/#faq', 'variant' => 'secondary', 'size' => 'sm', 'block' => true, 'attrs' => ['target' => '_blank']]) ?>
      <?= component_close() ?>

      <?= component('flash') ?>

      <section id="faq" class="scroll-mt-8">
         <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Questions fréquentes</h2>
            <?= component('button', ['label' => 'Ajouter une question', 'href' => '/admin/contenus/faq/create', 'size' => 'sm']) ?>
         </div>
         <ul role="list" class="mt-3 divide-y divide-gray-100 rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
            <?php foreach ($questions as $index => $question): ?>
               <li class="flex flex-wrap items-start gap-x-4 gap-y-2 px-4 py-3 <?= $question->is_active ? '' : 'bg-gray-50' ?>">
                  <div class="min-w-0 flex-auto">
                     <p class="font-medium <?= $question->is_active ? 'text-gray-900' : 'text-gray-400' ?>">
                        <?= esc($question->question) ?>
                        <?php if (!$question->is_active): ?><?= component('badge', ['label' => 'masquée', 'color' => 'gray-dark', 'shape' => 'tag', 'class' => 'ml-1']) ?><?php endif; ?>
                     </p>
                     <p class="mt-1 text-sm text-gray-500 line-clamp-2"><?= esc($question->answer) ?></p>
                  </div>
                  <?= $actions('faq', $question, $index, count($questions)) ?>
               </li>
            <?php endforeach; ?>
            <?php if (!$questions): ?><li class="px-4 py-6 text-center text-sm text-gray-500">Aucune question : la FAQ n'est pas affichée sur l'accueil.</li><?php endif; ?>
         </ul>
      </section>

      <section id="temoignage" class="mt-12 pb-16 scroll-mt-8">
         <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Témoignages</h2>
            <?= component('button', ['label' => 'Ajouter un témoignage', 'href' => '/admin/contenus/temoignage/create', 'size' => 'sm']) ?>
         </div>
         <p class="mt-1 text-sm text-gray-500">Le premier témoignage est mis en avant sur l'accueil.</p>
         <ul role="list" class="mt-3 divide-y divide-gray-100 rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
            <?php foreach ($testimonials as $index => $testimonial): ?>
               <li class="flex flex-wrap items-start gap-x-4 gap-y-2 px-4 py-3 <?= $testimonial->is_active ? '' : 'bg-gray-50' ?>">
                  <?php $picture = ContentController::pictureUrl($testimonial->picture); ?>
                  <?php if ($picture): ?>
                     <img src="<?= esc($picture, 'attr') ?>" alt="" class="h-10 w-10 flex-none rounded-full bg-gray-100 object-cover">
                  <?php else: ?>
                     <span class="flex h-10 w-10 flex-none items-center justify-center rounded-full bg-indigo-50 font-semibold text-indigo-700"><?= esc(mb_strtoupper(mb_substr($testimonial->name, 0, 1))) ?></span>
                  <?php endif; ?>
                  <div class="min-w-0 flex-auto">
                     <p class="font-medium <?= $testimonial->is_active ? 'text-gray-900' : 'text-gray-400' ?>">
                        <?= esc($testimonial->name) ?><?= $testimonial->totem ? ' <span class="font-normal text-gray-500">@' . esc($testimonial->totem) . '</span>' : '' ?>
                        <?php if ($index === 0 && $testimonial->is_active): ?><?= component('badge', ['label' => 'mis en avant', 'color' => 'indigo', 'shape' => 'tag', 'class' => 'ml-1']) ?><?php endif; ?>
                        <?php if (!$testimonial->is_active): ?><?= component('badge', ['label' => 'masqué', 'color' => 'gray-dark', 'shape' => 'tag', 'class' => 'ml-1']) ?><?php endif; ?>
                     </p>
                     <p class="mt-1 text-sm text-gray-500 line-clamp-2">“<?= esc($testimonial->quote) ?>”</p>
                  </div>
                  <?= $actions('temoignage', $testimonial, $index, count($testimonials)) ?>
               </li>
            <?php endforeach; ?>
            <?php if (!$testimonials): ?><li class="px-4 py-6 text-center text-sm text-gray-500">Aucun témoignage : la rubrique n'est pas affichée sur l'accueil.</li><?php endif; ?>
         </ul>
      </section>
   </div>
</div>
<?= $this->endSection() ?>
