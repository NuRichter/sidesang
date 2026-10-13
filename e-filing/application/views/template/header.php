<?php defined('BASEPATH') or exit('No direct script access allowed');
$akses = $_user['akses'];
$active = isset($_view) ? $_view : '';
function navcls($view, $current)
{
    return strpos($current, $view) === 0 ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-name" content="<?= $this->security->get_csrf_token_name() ?>">
    <meta name="csrf-hash" content="<?= $this->security->get_csrf_hash() ?>">
    <title>E-Arsip | <?= h($_title) ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/brand/favicon-32x32.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/earsip.css') ?>">
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-dark earsip-navbar">
            <div class="container-fluid">
                <a href="<?= site_url('beranda') ?>" class="navbar-brand"><b>E-Arsip</b></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navMenu">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a href="<?= site_url('beranda') ?>" class="nav-link <?= navcls('beranda', $active) ?>">Beranda</a></li>
                        <li class="nav-item"><a href="<?= site_url('jenis-surat') ?>" class="nav-link <?= navcls('jenis_surat', $active) ?>">Jenis Surat</a></li>
                        <li class="nav-item"><a href="<?= site_url('tupoksi') ?>" class="nav-link <?= navcls('tupoksi', $active) ?>">Tupoksi</a></li>
                        <li class="nav-item"><a href="<?= site_url('dokumen') ?>" class="nav-link <?= navcls('dokumen', $active) ?>">Data Dokumen</a></li>
                        <?php if ($akses === 'admin'): ?>
                            <li class="nav-item"><a href="<?= site_url('users') ?>" class="nav-link <?= navcls('users', $active) ?>">Data User</a></li>
                        <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item d-none d-sm-flex align-items-center"><span class="nav-link" id="jam"></span></li>
                        <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
                            <img src="<?= avatar_url($_user['id_user'], $_user['foto']) ?>" class="earsip-avatar mr-2" alt="foto">
                            <span><?= h($_user['nama']) ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <span class="dropdown-item-text text-muted small"><?= h($_user['email']) ?> &middot; <?= h(ucfirst($_user['akses'])) ?></span>
                            <div class="dropdown-divider"></div>
                            <a href="<?= site_url('profil') ?>" class="dropdown-item"><i class="fa fa-user mr-2"></i> Profil</a>
                            <a href="<?= site_url('logout') ?>" class="dropdown-item"><i class="fa fa-sign-out-alt mr-2"></i> Logout</a>
                        </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-1">
                        <div class="col-sm-6"><h1 class="m-0 h3"><?= h($_title) ?></h1></div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right small">
                                <li class="breadcrumb-item"><a href="<?= site_url('beranda') ?>"><i class="fa fa-home"></i> E-Arsip</a></li>
                                <li class="breadcrumb-item active"><?= h($_title) ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <?php if ($this->session->flashdata('msg')): ?>
                        <div class="alert alert-<?= $this->session->flashdata('msg_type') ?: 'info' ?> alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?= h($this->session->flashdata('msg')) ?>
                        </div>
                    <?php endif; ?>
