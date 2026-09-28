<?= view('emails/layout_start', ['subject' => $subject]) ?>
<p>Bonjour <?= esc($name) ?>,</p>
<p>Un compte a été créé pour vous sur le site des Guides et Scouts de Gosselies, en tant que responsable de section.</p>
<p>Vos identifiants :</p>
<table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 16px;font-size:15px;">
  <tr><td style="padding:4px 12px 4px 0;color:#6b7280;">E-mail</td><td style="padding:4px 0;font-weight:bold;"><?= esc($email) ?></td></tr>
  <tr><td style="padding:4px 12px 4px 0;color:#6b7280;">Mot de passe</td><td style="padding:4px 0;font-weight:bold;font-family:monospace;font-size:16px;"><?= esc($password) ?></td></tr>
</table>
<p style="text-align:center;margin:28px 0;">
  <a href="<?= esc($loginLink, 'attr') ?>" style="display:inline-block;background:#6366f1;color:#ffffff;text-decoration:none;font-weight:bold;padding:12px 24px;border-radius:8px;">Se connecter</a>
</p>
<p>Pour votre sécurité, choisissez votre propre mot de passe dès maintenant avec « Mot de passe oublié » :<br>
  <a href="<?= esc($forgotLink, 'attr') ?>"><?= esc($forgotLink) ?></a></p>
<?= view('emails/layout_end') ?>
