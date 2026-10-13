<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-earsip-red">
            <div class="inner">
                <h3><?= (int)$total_surat ?></h3>
                <p>Total Surat</p>
            </div>
            <div class="icon"><i class="fas fa-file-pdf"></i></div>
            <a href="<?= site_url('dokumen') ?>" class="small-box-footer">View Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-earsip-cyan">
            <div class="inner">
                <h3><?= (int)$total_jenis ?></h3>
                <p>Jenis Surat</p>
            </div>
            <div class="icon"><i class="fas fa-bookmark"></i></div>
            <a href="<?= site_url('jenis-surat') ?>" class="small-box-footer">View Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-earsip-orange">
            <div class="inner">
                <h3><?= (int)$total_tupoksi ?></h3>
                <p>Tupoksi</p>
            </div>
            <div class="icon"><i class="fas fa-sitemap"></i></div>
            <a href="<?= site_url('tupoksi') ?>" class="small-box-footer">View Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-earsip-green">
            <div class="inner">
                <h3><?= (int)$total_user ?></h3>
                <p>User</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <?php if ($_user['akses'] === 'admin'): ?>
                <a href="<?= site_url('users') ?>" class="small-box-footer">View Detail <i class="fas fa-arrow-circle-right"></i></a>
            <?php else: ?>
                <span class="small-box-footer">&nbsp;</span>
            <?php endif; ?>
        </div>
    </div>
</div>
