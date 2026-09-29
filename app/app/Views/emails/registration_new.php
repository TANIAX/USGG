<?php use App\Helpers\RegistrationHelper; ?>
<?= view('emails/layout_start', ['subject' => $subject]) ?>
<p>Une nouvelle demande d'inscription a été envoyée depuis le site.</p>
<table role="presentation" cellpadding="4" cellspacing="0" style="font-size:14px;">
  <tr><td style="color:#6b7280;">Enfant</td><td><strong><?= esc($request->firstname . ' ' . $request->name) ?></strong><?= $request->totem ? ' (' . esc($request->totem) . ')' : '' ?></td></tr>
  <tr><td style="color:#6b7280;">Âge</td><td><?= RegistrationHelper::age($request->birthdate) ?> ans (né(e) le <?= date('d/m/Y', strtotime($request->birthdate)) ?>)</td></tr>
  <tr><td style="color:#6b7280;">Section</td><td><?= esc($request->section_name ?: 'Pas de préférence') ?></td></tr>
  <tr><td style="color:#6b7280;">Phase</td><td><?= RegistrationHelper::phase($request->relation) ?> — <?= esc(RegistrationHelper::RELATIONS[$request->relation][0] ?? '') ?></td></tr>
  <tr><td style="color:#6b7280;">Parent</td><td><?= esc($request->parent_name) ?>, <?= esc($request->parent_email) ?>, <?= esc($request->parent_phone) ?></td></tr>
</table>
<?php if ($request->remark): ?>
  <p style="white-space:pre-line;"><em><?= esc($request->remark) ?></em></p>
<?php endif; ?>
<p><a href="<?= esc($link, 'attr') ?>" style="display:inline-block;background:#4f46e5;color:#ffffff;padding:10px 16px;border-radius:6px;text-decoration:none;font-weight:bold;">Voir la demande</a></p>
<?= view('emails/layout_end') ?>
