<?php
/**
 * Buttons at the bottom of a form: "Annuler" (link) and the submit button. Props:
 *  - cancel: url of the cancel link
 *  - submit: label of the submit button (default "Enregistrer")
 *  - submit_attrs: attributes of the submit button (:disabled...)
 */
?>
<div class="<?= classes('flex justify-end gap-x-3 border-t pt-6', $class) ?>">
   <?= component('button', ['label' => 'Annuler', 'variant' => 'secondary', 'href' => $cancel]) ?>
   <?= component('button', ['label' => $submit ?? 'Enregistrer', 'type' => 'submit', 'attrs' => $submit_attrs ?? []]) ?>
</div>
