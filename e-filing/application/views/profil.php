<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-lg-6">
        <div class="card card-earsip">
            <div class="card-header"><h3 class="card-title">Profil Saya</h3></div>
            <form id="formProfil" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="<?= avatar_url($u['id_user'], $u['foto']) ?>" class="img-thumbnail" style="width:110px;height:110px;object-fit:cover">
                    </div>
                    <div class="form-group">
                        <label>Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama" maxlength="100" value="<?= h($u['nama']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email / Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="email" maxlength="150" value="<?= h($u['email']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Akses</label>
                        <input type="text" class="form-control" value="<?= h(ucfirst($u['akses'])) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tupoksi</label>
                        <input type="text" class="form-control" value="<?= h($tupoksi) ?: '-' ?>" readonly>
                        <small class="text-muted">Akses dan tupoksi hanya dapat diubah oleh Admin.</small>
                    </div>
                    <div class="form-group">
                        <label>Ganti Foto</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="foto" name="foto" accept=".jpg,.jpeg,.png">
                            <label class="custom-file-label" for="foto">Pilih foto (JPG/PNG)</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="background-color:#3c8dbc;border-color:#367fa9"><i class="fa fa-save"></i> Simpan Profil</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-earsip">
            <div class="card-header"><h3 class="card-title">Ganti Password</h3></div>
            <form id="formPassword">
                <div class="card-body">
                    <div class="form-group">
                        <label>Password Lama <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password_lama" required>
                    </div>
                    <div class="form-group">
                        <label>Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password_baru" minlength="6" required>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="konfirmasi" minlength="6" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="background-color:#3c8dbc;border-color:#367fa9"><i class="fa fa-key"></i> Ubah Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
