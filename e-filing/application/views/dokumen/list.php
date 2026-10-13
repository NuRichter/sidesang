<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="card card-earsip">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title">Data Dokumen</h3>
        <a href="<?= site_url('dokumen/tambah') ?>" class="btn btn-light btn-sm ml-auto"><i class="fa fa-plus"></i> Tambah</a>
    </div>
    <div class="card-body">
        <div class="form-row mb-3">
            <div class="col-md-4 mb-2">
                <label class="small mb-1">Filter Jenis Surat</label>
                <select id="f_jenis" class="form-control form-control-sm">
                    <option value="">-- Semua Jenis --</option>
                    <?php foreach ($jenis as $j): ?>
                        <option value="<?= (int)$j['id_jenis'] ?>"><?= h($j['nama_jenis']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="small mb-1">Tanggal Dari</label>
                <input type="date" id="f_from" class="form-control form-control-sm">
            </div>
            <div class="col-md-3 mb-2">
                <label class="small mb-1">Tanggal Sampai</label>
                <input type="date" id="f_to" class="form-control form-control-sm">
            </div>
            <div class="col-md-2 mb-2 d-flex align-items-end">
                <button id="btnReset" class="btn btn-sm btn-outline-secondary btn-block">Reset Filter</button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="tbl" class="table table-bordered table-striped table-hover w-100" style="font-size:.9rem">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nomor Dokumen</th>
                        <th>Nama Dokumen</th>
                        <th>Catatan</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal Surat</th>
                        <th>Update</th>
                        <th>User</th>
                        <th>Tupoksi</th>
                        <th class="text-center">File</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
