<?php
/**
 * Messages of the previous action (flashdata "errors" and "success"). Props: class (default "mb-6").
 */
$errors = session()->getFlashdata('errors');
$success = session()->getFlashdata('success');
$class = $class ?: 'mb-6';
?>
<?php if ($errors): ?>
   <?= component('alert', ['type' => 'error', 'messages' => $errors, 'class' => $class]) ?>
<?php endif; ?>
<?php if ($success): ?>
   <?= component('alert', ['type' => 'success', 'message' => $success, 'class' => $class]) ?>
<?php endif; ?>
