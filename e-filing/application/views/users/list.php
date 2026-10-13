<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="card card-earsip">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title">Data User</h3>
        <a href="<?= site_url('users/tambah') ?>" class="btn btn-light btn-sm ml-auto"><i class="fa fa-plus"></i> Tambah</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tbl" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th class="text-center" style="width:50px">No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Akses</th>
                        <th>Tupoksi</th>
                        <th class="text-center">Foto</th>
                        <th class="text-center" style="width:150px">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
