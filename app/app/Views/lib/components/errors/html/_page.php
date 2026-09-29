<?php
/**
 * Common layout of the error pages (standalone: they must work even when the application fails).
 * Variables: $code, $title, $text, $reference (optional)
 */
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="color-scheme" content="only light">
    <meta name="robots" content="noindex">
    <title><?= htmlspecialchars($title) ?> - Guides et scouts de Gosselies</title>
    <style>
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f8fafc; color: #111827;
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
        main { max-width: 32rem; margin: 1rem; padding: 2.5rem 2rem; background: #fff; border-radius: 0.75rem; text-align: center;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, .08); border: 1px solid #e5e7eb; }
        img { height: 4rem; }
        .code { margin: 1.5rem 0 0; font-size: .875rem; font-weight: 600; color: #4f46e5; }
        h1 { margin: .5rem 0 0; font-size: 1.75rem; }
        p { margin: 1rem 0 0; color: #4b5563; line-height: 1.6; }
        .reference { font-size: .875rem; }
        .reference code { padding: .125rem .375rem; background: #f3f4f6; border-radius: .25rem; color: #111827; }
        a.button { display: inline-block; margin-top: 2rem; padding: .625rem 1.25rem; border-radius: .375rem; background: #4f46e5; color: #fff;
            font-weight: 600; font-size: .875rem; text-decoration: none; }
        a.button:hover { background: #6366f1; }
    </style>
</head>
<body>
    <main>
        <img src="/assets/img/logo.png" alt="Guides et scouts de Gosselies">
        <p class="code"><?= htmlspecialchars((string) $code) ?></p>
        <h1><?= htmlspecialchars($title) ?></h1>
        <p><?= htmlspecialchars($text) ?></p>
        <?php if (!empty($reference)): ?>
            <p class="reference">Si le problème persiste, contactez l'unité en indiquant la référence <code><?= htmlspecialchars($reference) ?></code>.</p>
        <?php endif; ?>
        <a class="button" href="/">Retour à l'accueil</a>
    </main>
</body>
</html>
