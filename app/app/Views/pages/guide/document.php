<?= $this->extend('pages/default') ?>

<?= $this->section('page_title') ?>
    Guides - Documents
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="relative isolate overflow-hidden bg-gradient-to-b from-indigo-100/20" x-data="app()" x-cloak>
   <div class="mx-auto max-w-7xl px-6 py-4 xl:py-20 lg:px-8">
      <div class="mx-auto max-w-2xl xl:mx-0 grid grid-cols-2 gap-4 xl:max-w-none">
        <!-- Titre -->
        <div class="max-w-xl lg:mt-0 col-span-2">
            <h1 class="text-4xl xl:text-4xl font-bold leading-normal xl:leading-relaxed mb-2">Téléchargement de document</h1>
        </div>

         <!-- Tableau des documents -->
         <div class="lg:mt-0 px-4  col-span-2 sm:px-6 lg:px-8">
            <div class="flow-root">
               <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                  <div class="inline-block min-w-full py-2 align-middle">
                     <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                           <tr>
                              <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                 Documents
                              </th>
                              <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">
                                 <span class="sr-only">Télécharger</span>
                              </th>
                           </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($files as $file) : ?>
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-normal text-gray-500 sm:pl-0">
                                        <?= $file->name ?>
                                    </td>
                                    <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                        <a href="/guide/document/<?=$file->id?>" download="<?= $file->name?>" class="text-indigo-600 hover:text-indigo-900">Télécharger</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   function app() {
       return {

       };
   }
</script>

<?= $this->endSection() ?>