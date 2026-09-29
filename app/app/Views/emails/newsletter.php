<?php use App\Helpers\DateHelper; ?>
<?= view('emails/layout_start', ['subject' => $subject]) ?>
<?php if ($introduction !== ''): ?>
  <p style="white-space:pre-line;"><?= esc($introduction) ?></p>
<?php else: ?>
  <p>Bonjour,</p>
  <p>Voici les prochaines activités des Guides et Scouts de Gosselies.</p>
<?php endif; ?>
<?php foreach ($events as $event): ?>
  <div style="margin:16px 0;padding:12px 16px;border-left:4px solid <?= esc($event->sections[0]->color ?? '#4f46e5', 'attr') ?>;background:#f9fafb;border-radius:4px;">
    <p style="margin:0;font-weight:bold;font-size:16px;"><?= esc($event->title) ?></p>
    <p style="margin:4px 0 0;color:#374151;"><?= esc(DateHelper::frenchPeriod($event)) ?></p>
    <?php if ($event->location): ?><p style="margin:2px 0 0;color:#6b7280;"><?= esc($event->location) ?></p><?php endif; ?>
    <p style="margin:2px 0 0;color:#6b7280;font-size:13px;"><?= esc(implode(', ', array_map(fn($section) => $section->name, $event->sections))) ?></p>
    <p style="margin:8px 0 0;"><a href="<?= esc(base_url('actualites/' . $event->id), 'attr') ?>">Voir le détail</a></p>
  </div>
<?php endforeach; ?>
<p><a href="<?= esc(base_url('en-pratique/agenda'), 'attr') ?>">Tout l'agenda</a></p>
<p style="font-size:12px;color:#6b7280;margin-top:24px;">Vous recevez cet e-mail car vous êtes inscrit(e) à la newsletter du site.
  <a href="<?= esc($unsubscribeLink, 'attr') ?>" style="color:#6b7280;">Se désinscrire</a></p>
<?= view('emails/layout_end') ?>
