<?php use App\Helpers\RegistrationHelper; ?>
<?= view('emails/layout_start', ['subject' => $subject]) ?>
<p>Bonjour <?= esc($request->parent_name) ?>,</p>
<p>Nous avons bien reçu votre demande d'inscription pour <strong><?= esc($request->firstname . ' ' . $request->name) ?></strong><?= $request->section_name ? ' (section ' . esc($request->section_name) . ')' : '' ?>.</p>
<p>Elle relève de la <strong>phase <?= RegistrationHelper::phase($request->relation) ?></strong> des inscriptions. Les demandes sont traitées par ordre d'arrivée pour chaque phase :
  nous vous recontacterons pour vous proposer une réunion d'essai ou, si la section est complète, pour vous placer en liste d'attente.</p>
<p>Vous n'avez rien d'autre à faire pour le moment. Pour toute question, répondez à l'adresse contact@gsgosselies.be.</p>
<p>À bientôt,<br>Les Guides et Scouts de Gosselies</p>
<?= view('emails/layout_end') ?>
