<?php
/**
 * Search field of a list filtered by Alpine. Props: model (x-model variable, default "search"), placeholder, label (read by screen readers).
 */
?>
<div class="<?= $class ?: '' ?>">
   <label for="<?= esc($id ?? 'search', 'attr') ?>" class="sr-only"><?= esc($label ?? 'Recherche') ?></label>
   <?= component('input', ['type' => 'search', 'id' => $id ?? 'search', 'placeholder' => $placeholder ?? 'Rechercher', 'attrs' => ['x-model' => $model ?? 'search', 'autocomplete' => 'off'] + $attrs]) ?>
</div>
