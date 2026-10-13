<?php
defined('BASEPATH') or exit('No direct script access allowed');
$is_pdf = (strpos((string)$dok['file_mime'], 'pdf') !== false);
$is_img = (strpos((string)$dok['file_mime'], 'image') !== false);
$preview = site_url('dokumen/preview/' . $dok['id_dokumen']);
?>
<div class="card card-earsip">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title">Detail Dokumen</h3>
        <div class="ml-auto">
            <?php if (!empty($dok['file_name'])): ?>
                <a href="<?= site_url('dokumen/download/' . $dok['id_dokumen']) ?>" class="btn btn-light btn-sm"><i class="fa fa-download"></i> Download</a>
                <button id="btnPrint" class="btn btn-light btn-sm"><i class="fa fa-print"></i> Print</button>
            <?php endif; ?>
            <a href="<?= site_url('dokumen') ?>" class="btn btn-light btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm meta-table mb-0">
                    <tr><td class="meta-label">No Document</td><td>: <?= h($dok['nomor_dokumen']) ?></td></tr>
                    <tr><td class="meta-label">Nama Document</td><td>: <?= h($dok['nama_dokumen']) ?></td></tr>
                    <tr><td class="meta-label">Catatan</td><td>: <?= h($dok['catatan']) ?: '-' ?></td></tr>
                    <tr><td class="meta-label">Deskripsi</td><td>: <?= h($dok['deskripsi']) ?: '-' ?></td></tr>
                    <tr><td class="meta-label">Jenis Surat</td><td>: <?= h($dok['nama_jenis']) ?></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm meta-table mb-0">
                    <tr><td class="meta-label">Tanggal Surat</td><td>: <?= tgl_id($dok['tanggal_surat']) ?></td></tr>
                    <tr><td class="meta-label">Tanggal Upload</td><td>: <?= tgl_id($dok['created_at']) ?></td></tr>
                    <tr><td class="meta-label">Tanggal Update</td><td>: <?= tgl_id($dok['updated_at']) ?></td></tr>
                    <tr><td class="meta-label">Ukuran File</td><td>: <?= $dok['file_size'] ? human_size($dok['file_size']) : '-' ?></td></tr>
                    <tr><td class="meta-label">Tupoksi</td><td>: <?= h($dok['nama_tupoksi']) ?: '-' ?></td></tr>
                    <tr><td class="meta-label">User</td><td>: <?= h($dok['nama_user']) ?: '-' ?></td></tr>
                </table>
            </div>
        </div>
        <hr>
        <?php if (empty($dok['file_name'])): ?>
            <div class="alert alert-warning mb-0"><i class="fa fa-info-circle"></i> Dokumen ini belum memiliki berkas yang diunggah.</div>
        <?php elseif ($is_pdf): ?>
            <iframe id="viewer" class="pdf-frame" src="<?= $preview ?>" title="Dokumen PDF"></iframe>
        <?php elseif ($is_img): ?>
            <div class="text-center border rounded p-2" style="background:#f3f6f9">
                <img id="viewer" src="<?= $preview ?>" class="img-fluid" style="max-height:75vh" alt="<?= h($dok['nama_dokumen']) ?>">
            </div>
        <?php else: ?>
            <div class="alert alert-info mb-0">Pratinjau tidak tersedia untuk tipe berkas ini. Silakan <a href="<?= site_url('dokumen/download/' . $dok['id_dokumen']) ?>">unduh</a>.</div>
        <?php endif; ?>
    </div>
</div>
<input type="hidden" id="previewUrl" value="<?= $preview ?>">
<input type="hidden" id="isPdf" value="<?= $is_pdf ? 1 : 0 ?>">
