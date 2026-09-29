<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Messages
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <?= component('page_header', ['title' => 'Messages', 'subtitle' => 'Messages du formulaire de contact. Ils sont aussi envoyés à ' . $contactEmail . ' : répondre à l\'e-mail répond directement à la personne.']) ?>

      <?= component('flash') ?>
      <p x-show="actionError" x-text="actionError" class="mb-4 text-sm text-red-600"></p>

      <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
         <div class="flex flex-wrap gap-2 text-sm">
            <?php foreach (['todo' => 'À traiter', 'unread' => 'Non lus', 'handled' => 'Traités', '' => 'Tous'] as $code => $label): ?>
               <?= component('chip', ['active_alpine' => "filter === '$code'", 'attrs' => ['@click' => "filter = '$code'", 'x-text' => "'$label (' + (counts['$code'] ?? 0) + ')'"]]) ?>
            <?php endforeach; ?>
         </div>
         <?= component('search', ['placeholder' => 'Nom, e-mail, message...', 'class' => 'lg:w-80']) ?>
      </div>

      <ul id="list-top" role="list" class="scroll-mt-8 space-y-4 transition-opacity" :class="listLoading ? 'opacity-60' : ''" x-show="items.length > 0">
         <template x-for="message in items" :key="message.id">
            <li class="rounded-lg bg-white shadow-sm ring-1" :class="message.is_read ? 'ring-gray-200' : 'ring-indigo-300'">
               <details @toggle="if ($el.open && !message.is_read) run('read', [message.id])">
                  <summary class="flex cursor-pointer list-none items-start gap-x-3 px-4 py-3">
                     <span class="mt-2 h-2 w-2 flex-none rounded-full" :class="message.is_read ? 'bg-transparent' : 'bg-indigo-600'" aria-hidden="true"></span>
                     <span class="min-w-0 flex-auto">
                        <span class="block text-sm text-gray-900" :class="message.is_read ? '' : 'font-semibold'" x-text="message.name"></span>
                        <span class="text-sm text-gray-500 line-clamp-2" x-text="message.message"></span>
                     </span>
                     <span class="flex flex-none flex-col items-end gap-1">
                        <span class="text-xs text-gray-500" x-text="formatDate(message.created_at, 'short')"></span>
                        <?= component('badge', ['label' => 'Traité', 'color' => 'green', 'attrs' => ['x-show' => 'message.is_handled']]) ?>
                     </span>
                  </summary>
                  <div class="border-t border-gray-100 px-4 py-4 sm:pl-9">
                     <p class="whitespace-pre-line text-sm text-gray-800" x-text="message.message"></p>
                     <p class="mt-3 text-sm text-gray-600">
                        <a :href="`mailto:${message.email}?subject=${encodeURIComponent('Re: votre message aux Guides et Scouts de Gosselies')}`" class="text-indigo-600 hover:text-indigo-500" x-text="message.email"></a>
                        <template x-if="message.phone"><span> · <a :href="`tel:${message.phone}`" class="text-indigo-600 hover:text-indigo-500" x-text="message.phone"></a></span></template>
                     </p>
                     <div class="mt-4 flex flex-wrap gap-2">
                        <?= component('button', ['label' => 'Répondre', 'size' => 'sm', 'href' => '#', 'attrs' => [':href' => "`mailto:\${message.email}?subject=\${encodeURIComponent('Re: votre message aux Guides et Scouts de Gosselies')}`"]]) ?>
                        <?= component('button', ['variant' => 'secondary', 'size' => 'sm', 'attrs' => ['@click' => "run(message.is_handled ? 'unhandled' : 'handled', [message.id])", ':disabled' => 'busy', 'x-text' => "message.is_handled ? 'Remettre à traiter' : 'Marquer comme traité'"]]) ?>
                        <?= component('button', ['variant' => 'secondary', 'size' => 'sm', 'label' => 'Marquer non lu', 'attrs' => ['@click' => "run('unread', [message.id])", ':disabled' => 'busy']]) ?>
                        <?= component('button', ['variant' => 'secondary', 'size' => 'sm', 'label' => 'Supprimer', 'class' => '!text-red-600', 'attrs' => ['@click' => 'remove(message)', ':disabled' => 'busy']]) ?>
                     </div>
                  </div>
               </details>
            </li>
         </template>
      </ul>
      <?= component('pagination', ['noun' => ['message', 'messages'], 'class' => 'mt-6 mb-16']) ?>
      <?= component('empty_state', ['icon' => 'envelope', 'class' => 'mb-16', 'title_alpine' => "counts[''] ? 'Aucun message ne correspond aux filtres' : 'Aucun message pour le moment'", 'attrs' => ['x-show' => 'items.length === 0']]) ?>
   </div>
</div>

<script>
   function app() {
      return listApp('/admin/messages', <?= $list ?>, {
         busy: false,
         actionError: '',

         remove(message) {
            if (confirm(`Supprimer définitivement le message de ${message.name} ?`))
               this.run('delete', [message.id]);
         },

         async run(action, ids) {
            this.busy = true;
            this.actionError = '';
            try {
               const json = await requestJson('/admin/messages/bulk', { action: action, ids: ids });
               this.counts = json.counts;
               // A deletion changes the pages; the other actions only change the message, which stays visible until the next page
               if (action === 'delete')
                  await this.loadList();
               else
                  this.items.filter(message => json.ids.includes(message.id)).forEach(message => Object.assign(message, json.changes));
            } catch (error) {
               this.actionError = error.message || 'L\'action n\'a pas pu être effectuée. Rechargez la page et réessayez.';
            } finally {
               this.busy = false;
            }
         },
      });
   }
</script>
<?= $this->endSection() ?>
