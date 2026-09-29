<?php
// Error page shown in production (the details are in the logs, under the same reference: /admin/logs)
$code = 'Erreur';
$title = 'Une erreur est survenue';
$text = 'La page n\'a pas pu être affichée. L\'erreur a été enregistrée ; réessayez dans quelques instants.';
$reference = \App\Libraries\RequestContext::id();
include __DIR__ . '/_page.php';
