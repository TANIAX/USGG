<?= $this->extend('pages/default') ?>
<?= $this->section('page_title') ?>
    Guides et scoutes de Gosselies - Newsletter
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php component_open('auth_card', ['title' => $title, 'text' => esc($text)]) ?>
    <?= component('button', ['label' => 'Retour à l\'accueil', 'href' => '/', 'size' => 'lg', 'block' => true, 'class' => 'mt-6']) ?>
<?= component_close() ?>
<?= $this->endSection() ?>
