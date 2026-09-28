<?= view('emails/layout_start', ['subject' => $subject]) ?>
<p>Bonjour <?= esc($totem) ?>,</p>
<p>Le mot de passe de votre compte sur le site des Guides et Scouts de Gosselies vient d'être modifié.</p>
<p>Si c'est bien vous, vous n'avez rien à faire.</p>
<p>Si vous n'êtes pas à l'origine de ce changement, demandez immédiatement un nouveau mot de passe ici :<br>
  <a href="<?= esc($forgotLink, 'attr') ?>"><?= esc($forgotLink) ?></a><br>
  et prévenez-nous à l'adresse contact@gsgosselies.be.</p>
<?= view('emails/layout_end') ?>
