<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= $this->renderSection('page_title', true) ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/animate.css') ?>">

    <script src="<?= base_url('assets/js/tailwind.js')?>"></script>
    <script defer src="<?= base_url('assets/js/alpine-3.13.2.js')?>"></script>
</head>

<body>
    <?= $this->include('layout\header.php') ?>
    <?= $this->include('layout\menu.php') ?>

    <?= $this->renderSection('content') ?>
    <?= $this->include('layout\footer.php') ?>

</body>

</html>