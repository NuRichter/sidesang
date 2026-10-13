<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Arsip Desa Bulukandang | Login</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/brand/favicon-32x32.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/earsip.css') ?>">
</head>

<body class="earsip-login-body">
    <div class="earsip-login-box">
        <div class="earsip-login-title"><b>E-Arsip</b> Desa<br>Bulukandang</div>
        <div class="earsip-login-card">
            <h5>Silahkan Login</h5>
            <?php if ($this->session->flashdata('login_error')): ?>
                <div class="alert alert-danger py-2"><?= h($this->session->flashdata('login_error')) ?></div>
            <?php endif; ?>
            <?= form_open('login/proses') ?>
            <div class="input-group mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
                <div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>
            </div>
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary px-4" style="background-color:#3c8dbc;border-color:#367fa9">Login</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>
