<?= view('emails/layout_start', ['subject' => $subject]) ?>
<p>Nouveau message envoyé depuis le formulaire de contact du site. Répondez directement à cet e-mail pour répondre à la personne.</p>
<table role="presentation" cellpadding="4" cellspacing="0" style="font-size:14px;">
  <tr><td style="color:#6b7280;">De</td><td><strong><?= esc($contact->name) ?></strong> — <?= esc($contact->email) ?></td></tr>
  <?php if ($contact->phone): ?><tr><td style="color:#6b7280;">Téléphone</td><td><?= esc($contact->phone) ?></td></tr><?php endif; ?>
</table>
<p style="white-space:pre-line;border-left:3px solid #e5e7eb;padding-left:12px;"><?= esc($contact->message) ?></p>
<p><a href="<?= esc($link, 'attr') ?>">Voir tous les messages sur le site</a></p>
<?= view('emails/layout_end') ?>
