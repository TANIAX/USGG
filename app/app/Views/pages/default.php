<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= $this->renderSection('page_title', true) ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- The site only has a light theme: prevents the browsers (Chrome, Samsung Internet...) from darkening it automatically -->
    <meta name="color-scheme" content="only light">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/animate.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/output.css') ?>">

    <script defer src="<?= base_url('assets/js/alpine-3.13.2.min.js')?>"></script>
    <script src="<?= base_url('assets/js/script.js')?>"></script>
</head>

<body class="bg-white text-gray-900">
    <?= $this->include('lib/components/layout/header.php') ?>
    <main id="main">
        <?= $this->renderSection('content') ?>
    </main>
    <?= $this->include('lib/components/layout/footer.php') ?>

</body>

</html>