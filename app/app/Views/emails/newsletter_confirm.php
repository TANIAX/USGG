<?= view('emails/layout_start', ['subject' => $subject]) ?>
<p>Bonjour,</p>
<p>Vous avez demandé à recevoir les actualités des Guides et Scouts de Gosselies. Pour confirmer votre inscription, cliquez sur ce lien :</p>
<p><a href="<?= esc($link, 'attr') ?>" style="display:inline-block;background:#4f46e5;color:#ffffff;padding:10px 16px;border-radius:6px;text-decoration:none;font-weight:bold;">Confirmer mon inscription</a></p>
<p style="font-size:13px;color:#6b7280;">Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet e-mail : vous ne serez pas inscrit(e).</p>
<?= view('emails/layout_end') ?>
