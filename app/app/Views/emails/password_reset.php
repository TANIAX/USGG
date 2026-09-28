<?= view('emails/layout_start', ['subject' => $subject]) ?>
<p>Bonjour <?= esc($totem) ?>,</p>
<p>Une demande de réinitialisation du mot de passe de votre compte a été faite sur le site des Guides et Scouts de Gosselies.</p>
<p>Pour choisir un nouveau mot de passe, cliquez sur le bouton ci-dessous (le lien est valable <?= (int) $lifetime ?> minutes et ne peut servir qu'une fois) :</p>
<p style="text-align:center;margin:28px 0;">
  <a href="<?= esc($link, 'attr') ?>" style="display:inline-block;background:#6366f1;color:#ffffff;text-decoration:none;font-weight:bold;padding:12px 24px;border-radius:8px;">Choisir un nouveau mot de passe</a>
</p>
<p style="font-size:13px;color:#4b5563;">Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br><span style="word-break:break-all;"><?= esc($link) ?></span></p>
<p>Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet e-mail : votre mot de passe actuel reste inchangé.</p>
<?= view('emails/layout_end') ?>
