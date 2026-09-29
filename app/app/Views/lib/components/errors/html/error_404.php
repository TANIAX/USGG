<?php
$code = 'Erreur 404';
$title = 'Page introuvable';
$text = ENVIRONMENT !== 'production' ? ($message ?? '') : 'Cette page n\'existe pas ou n\'existe plus. Le lien que vous avez suivi est peut-être incorrect.';
include __DIR__ . '/_page.php';
