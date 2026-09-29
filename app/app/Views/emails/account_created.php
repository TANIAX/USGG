<?= view('emails/layout_start', ['subject' => $subject]) ?>
<p>Bonjour <?= esc($name) ?>,</p>
<p>Un compte a été créé pour vous sur le site des Guides et Scouts de Gosselies<?= !empty($reason) ? ', ' . esc($reason) : '' ?>.</p>
<p>Votre identifiant est votre adresse e-mail : <strong><?= esc($email) ?></strong></p>
<p>Pour activer votre compte, choisissez votre mot de passe (le lien est valable <?= (int) $days ?> jours et ne peut servir qu'une fois) :</p>
<p style="text-align:center;margin:28px 0;">
  <a href="<?= esc($link, 'attr') ?>" style="display:inline-block;background:#6366f1;color:#ffffff;text-decoration:none;font-weight:bold;padding:12px 24px;border-radius:8px;">Choisir mon mot de passe</a>
</p>
<p style="font-size:13px;color:#4b5563;">Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br><span style="word-break:break-all;"><?= esc($link) ?></span></p>
<p>Si le lien a expiré, demandez-en un nouveau avec « Mot de passe oublié » :<br><a href="<?= esc($forgotLink, 'attr') ?>"><?= esc($forgotLink) ?></a></p>
<?= view('emails/layout_end') ?>
