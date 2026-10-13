<?php
defined('BASEPATH') or exit('No direct script access allowed');
$is_edit = ($mode === 'edit');
$tgl = ($is_edit && !empty($dok['tanggal_surat']) && $dok['tanggal_surat'] !== '0000-00-00') ? date('d/m/Y', strtotime($dok['tanggal_surat'])) : date('d/m/Y');
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-earsip">
            <div class="card-header">
                <h3 class="card-title"><?= $is_edit ? 'Edit Dokumen' : 'Tambah Dokumen' ?></h3>
            </div>
            <form id="formDok" enctype="multipart/form-data">
                <div class="card-body">
                    <?php if ($is_edit): ?><input type="hidden" name="id_dokumen" value="<?= (int)$dok['id_dokumen'] ?>"><?php endif; ?>
                    <div class="form-group">
                        <label>Nomor Dokumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nomor_dokumen" maxlength="100" value="<?= $is_edit ? h($dok['nomor_dokumen']) : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Jenis Surat <span class="text-danger">*</span></label>
                        <select name="id_jenis" class="form-control select2" required>
                            <option value="">--Pilih Jenis Surat--</option>
                            <?php foreach ($jenis as $j): ?>
                                <option value="<?= (int)$j['id_jenis'] ?>" <?= ($is_edit && (int)$dok['id_jenis'] === (int)$j['id_jenis']) ? 'selected' : '' ?>><?= h($j['nama_jenis']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_dokumen" maxlength="200" placeholder="Nama Dokumen" value="<?= $is_edit ? h($dok['nama_dokumen']) : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Dokumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="tanggal_dokumen" name="tanggal_dokumen" value="<?= $tgl ?>" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea class="form-control" name="catatan" rows="2"><?= $is_edit ? h($dok['catatan']) : '' ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3"><?= $is_edit ? h($dok['deskripsi']) : '' ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Upload Dokumen <?= $is_edit ? '' : '<span class="text-danger"></span>' ?></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="file" name="file" accept=".pdf,.jpg,.jpeg,.png">
                            <label class="custom-file-label" for="file">Pilih berkas (PDF/JPG/PNG, maks 4MB)</label>
                        </div>
                        <?php if ($is_edit && !empty($dok['file_name'])): ?>
                            <small class="text-muted">Berkas saat ini: <b><?= h($dok['file_name']) ?></b>. Biarkan kosong jika tidak mengganti.</small>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="background-color:#3c8dbc;border-color:#367fa9"><i class="fa fa-save"></i> Simpan</button>
                    <a href="<?= site_url('dokumen') ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
<input type="hidden" id="formMode" value="<?= $mode ?>">
