<?php use App\Helpers\DateHelper; ?>
<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
Guides et scoutes de Gosselies - Newsletter
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="app()" x-cloak>
   <div class="px-4 sm:px-6 lg:px-8">
      <?= component('page_header', ['title' => 'Newsletter', 'subtitle' => 'Les visiteurs s\'inscrivent depuis l\'accueil et confirment par e-mail. Chaque envoi contient un lien de désinscription.']) ?>

      <?= component('flash') ?>

      <div class="grid grid-cols-1 gap-10 pb-16 lg:grid-cols-2">
         <!-- Sending -->
         <section>
            <h2 class="text-lg font-semibold text-gray-900">Envoyer les prochaines activités</h2>
            <form method="GET" action="<?= base_url('admin/newsletter') ?>" class="mt-3">
               <label for="periode" class="block text-sm font-medium leading-6 text-gray-900">Période</label>
               <?= component('input', ['type' => 'select', 'name' => 'periode', 'id' => 'periode', 'options' => $periods, 'value' => $period, 'class' => 'mt-2', 'attrs' => ['onchange' => 'this.form.submit()']]) ?>
            </form>

            <div class="mt-4 rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
               <?php if ($events): ?>
                  <ul role="list" class="divide-y divide-gray-100">
                     <?php foreach ($events as $event): ?>
                        <li class="px-4 py-3">
                           <p class="text-sm font-medium text-gray-900"><?= esc($event->title) ?></p>
                           <p class="text-xs text-gray-500"><?= esc(DateHelper::frenchPeriod($event)) ?></p>
                        </li>
                     <?php endforeach; ?>
                  </ul>
               <?php else: ?>
                  <p class="px-4 py-6 text-center text-sm text-gray-500">Aucun événement dans l'agenda sur cette période.</p>
               <?php endif; ?>
            </div>

            <form method="POST" action="<?= base_url('admin/newsletter/send') ?>" class="mt-6 space-y-4"
               onsubmit="return confirm('Envoyer la newsletter à <?= $recipientCount ?> abonné(s) ?')">
               <input type="hidden" name="period" value="<?= $period ?>">
               <?= component('field', ['label' => 'Objet de l\'e-mail', 'name' => 'subject', 'value' => 'Les prochaines activités des Guides et Scouts de Gosselies', 'attrs' => ['maxlength' => 200]]) ?>
               <?= component('field', ['label' => 'Introduction', 'name' => 'introduction', 'type' => 'textarea', 'rows' => 3, 'placeholder' => 'Bonjour, voici les prochaines activités de l\'unité...',
                  'help' => 'Facultatif : texte affiché avant la liste des activités.', 'attrs' => ['maxlength' => 2000]]) ?>
               <?= component('button', ['label' => 'Envoyer à ' . $recipientCount . ' abonné' . ($recipientCount > 1 ? 's' : ''), 'type' => 'submit',
                  'attrs' => ['disabled' => !$events || !$recipientCount]]) ?>
            </form>

            <?php if ($sendings): ?>
               <h3 class="mt-10 text-sm font-semibold text-gray-900">Derniers envois</h3>
               <ul role="list" class="mt-2 space-y-1 text-sm text-gray-600">
                  <?php foreach ($sendings as $sending): ?>
                     <li><?= date('d/m/Y H:i', strtotime($sending->created_at)) ?> — <?= esc($sending->subject) ?> : <?= (int) $sending->event_count ?> activité(s), <?= (int) $sending->recipient_count - (int) $sending->failed_count ?> / <?= (int) $sending->recipient_count ?> envoyé(s)<?= ($sending->totem ?: $sending->firstname) ? ' par ' . esc($sending->totem ?: $sending->firstname) : '' ?></li>
                  <?php endforeach; ?>
               </ul>
            <?php endif; ?>
         </section>

         <!-- Subscribers -->
         <section>
            <h2 class="text-lg font-semibold text-gray-900">Abonnés</h2>
            <div class="mt-3 flex flex-wrap gap-2 text-sm">
               <?php foreach (['active' => 'Confirmés', 'pending' => 'En attente', 'unsubscribed' => 'Désinscrits', '' => 'Tous'] as $code => $label): ?>
                  <?= component('chip', ['active_alpine' => "status === '$code'", 'attrs' => ['@click' => "status = '$code'", 'x-text' => "'$label (' + (counts['$code'] ?? 0) + ')'"]]) ?>
               <?php endforeach; ?>
            </div>
            <?= component('search', ['placeholder' => 'Adresse e-mail', 'class' => 'mt-3']) ?>
            <ul id="list-top" role="list" class="mt-3 scroll-mt-8 divide-y divide-gray-100 rounded-lg bg-white shadow-sm ring-1 ring-gray-200 transition-opacity" :class="listLoading ? 'opacity-60' : ''" x-show="items.length > 0">
               <template x-for="subscriber in items" :key="subscriber.id">
                  <li class="flex items-center gap-x-3 px-4 py-2">
                     <div class="min-w-0 flex-auto">
                        <p class="truncate text-sm text-gray-900" x-text="subscriber.email"></p>
                        <p class="text-xs text-gray-500" x-text="label(subscriber)"></p>
                     </div>
                     <form method="POST" :action="`/admin/newsletter/delete/${subscriber.id}`" @submit="if (!confirm(`Supprimer ${subscriber.email} de la liste ?`)) $event.preventDefault()">
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-900">Supprimer</button>
                     </form>
                  </li>
               </template>
            </ul>
            <?= component('pagination', ['noun' => ['adresse', 'adresses'], 'class' => 'mt-4']) ?>
            <p x-show="items.length === 0" class="mt-3 rounded-lg bg-gray-50 px-4 py-6 text-center text-sm text-gray-500">Aucune adresse.</p>
            <div class="mt-3">
               <?= component('button', ['label' => 'Exporter les abonnés confirmés (CSV)', 'variant' => 'secondary', 'size' => 'sm', 'href' => base_url('admin/newsletter?format=csv')]) ?>
            </div>
         </section>
      </div>
   </div>
</div>

<script>
   function app() {
      return listApp('/admin/newsletter', <?= $list ?>, {
         label(subscriber) {
            if (subscriber.status === 'unsubscribed')
               return 'Désinscrit le ' + formatDate(subscriber.unsubscribed_at, 'short');
            if (subscriber.status === 'pending')
               return 'Inscrit le ' + formatDate(subscriber.created_at, 'short') + ', pas encore confirmé';
            return 'Confirmé le ' + formatDate(subscriber.confirmed_at, 'short');
         },
      });
   }
</script>
<?= $this->endSection() ?>
