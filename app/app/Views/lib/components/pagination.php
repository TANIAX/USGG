<?php
/**
 * Pages of a list. Two modes:
 *  - Alpine (default): list of listApp() in script.js (pagination, pageLinks, goToPage(), setPerPage()). Props: noun ([singular, plural]), sizes (choice of the number per page, default ListQuery::PER_PAGE, [] to hide it).
 *  - server: 'pagination' => ['page', 'pages', 'total', 'from', 'to'] and 'url' (fn(int $page): string), links rendered by the server.
 */
use App\Libraries\ListQuery;

$noun = $noun ?? ['élément', 'éléments'];
$sizes = $sizes ?? ListQuery::PER_PAGE;
$link = 'relative inline-flex min-w-[2.25rem] items-center justify-center rounded-md px-2.5 py-1.5 text-sm font-medium ring-1 ring-inset';
$off = 'bg-white text-gray-700 ring-gray-300 hover:bg-gray-50';
$on = 'z-10 bg-indigo-600 text-white ring-indigo-600';
$disabled = 'cursor-not-allowed bg-white text-gray-300 ring-gray-200';
?>
<?php if (isset($pagination)): ?>
   <?php if ($pagination['pages'] > 1): ?>
      <nav class="<?= classes('flex flex-col items-center gap-3 sm:flex-row sm:justify-between', $class) ?>" aria-label="Pages">
         <p class="text-sm text-gray-600"><?= $pagination['from'] ?>–<?= $pagination['to'] ?> sur <?= $pagination['total'] ?> <?= esc($pagination['total'] > 1 ? $noun[1] : $noun[0]) ?></p>
         <div class="flex flex-wrap items-center gap-1.5">
            <?php if ($pagination['page'] > 1): ?>
               <a href="<?= esc($url($pagination['page'] - 1), 'attr') ?>" rel="prev" class="<?= "$link $off" ?>" aria-label="Page précédente"><?= component('icon', ['name' => 'chevron-left']) ?></a>
            <?php endif; ?>
            <?php foreach (ListQuery::pageNumbers($pagination['page'], $pagination['pages']) as $number): ?>
               <?php if ($number === null): ?>
                  <span class="px-1 text-gray-400">…</span>
               <?php else: ?>
                  <a href="<?= esc($url($number), 'attr') ?>" class="<?= $link . ' ' . ($number === $pagination['page'] ? $on : $off) ?>"<?= $number === $pagination['page'] ? ' aria-current="page"' : '' ?>><?= $number ?></a>
               <?php endif; ?>
            <?php endforeach; ?>
            <?php if ($pagination['page'] < $pagination['pages']): ?>
               <a href="<?= esc($url($pagination['page'] + 1), 'attr') ?>" rel="next" class="<?= "$link $off" ?>" aria-label="Page suivante"><?= component('icon', ['name' => 'chevron-right']) ?></a>
            <?php endif; ?>
         </div>
      </nav>
   <?php endif; ?>
<?php else: ?>
   <nav x-show="pagination.total > 0" class="<?= classes('flex flex-col items-center gap-3 sm:flex-row sm:justify-between', $class) ?>" aria-label="Pages">
      <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-600">
         <p x-text="`${pagination.from}–${pagination.to} sur ${pagination.total} ${pagination.total > 1 ? '<?= esc($noun[1], 'js') ?>' : '<?= esc($noun[0], 'js') ?>'}`"></p>
         <?php if ($sizes): ?>
            <label class="flex items-center gap-x-2">
               <span>Par page</span>
               <select :value="pagination.per_page" @change="setPerPage($event.target.value)" class="rounded-md border-0 py-1 pl-2 pr-8 text-sm text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600">
                  <?php foreach ($sizes as $size): ?><option value="<?= $size ?>"><?= $size ?></option><?php endforeach; ?>
               </select>
            </label>
         <?php endif; ?>
         <span x-show="listLoading" class="text-gray-400">Chargement…</span>
      </div>
      <div x-show="pagination.pages > 1" class="flex flex-wrap items-center gap-1.5">
         <button type="button" @click="goToPage(pagination.page - 1)" :disabled="pagination.page === 1" aria-label="Page précédente"
            class="<?= $link ?>" :class="pagination.page === 1 ? '<?= $disabled ?>' : '<?= $off ?>'"><?= component('icon', ['name' => 'chevron-left']) ?></button>
         <template x-for="(number, index) in pageLinks" :key="index + '-' + number">
            <span class="contents">
               <span x-show="number === null" class="px-1 text-gray-400">…</span>
               <button type="button" x-show="number !== null" @click="goToPage(number)" x-text="number" :aria-current="number === pagination.page ? 'page' : null"
                  class="<?= $link ?>" :class="number === pagination.page ? '<?= $on ?>' : '<?= $off ?>'"></button>
            </span>
         </template>
         <button type="button" @click="goToPage(pagination.page + 1)" :disabled="pagination.page === pagination.pages" aria-label="Page suivante"
            class="<?= $link ?>" :class="pagination.page === pagination.pages ? '<?= $disabled ?>' : '<?= $off ?>'"><?= component('icon', ['name' => 'chevron-right']) ?></button>
      </div>
   </nav>
   <p x-show="listError" x-text="listError" class="mt-3 text-sm text-red-600"></p>
<?php endif; ?>
