<?php
defined('BASEPATH') or exit('No direct script access allowed');
$is_edit = ($mode === 'edit');
?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-earsip">
            <div class="card-header"><h3 class="card-title"><?= $is_edit ? 'Edit User' : 'Tambah User' ?></h3></div>
            <form id="formUser" enctype="multipart/form-data">
                <div class="card-body">
                    <?php if ($is_edit): ?><input type="hidden" name="id_user" value="<?= (int)$u['id_user'] ?>"><?php endif; ?>
                    <div class="form-group">
                        <label>Nama User <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama" maxlength="100" value="<?= $is_edit ? h($u['nama']) : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email / Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="email" maxlength="150" placeholder="E-Mail" value="<?= $is_edit ? h($u['email']) : '' ?>" required>
                        <small class="text-muted">Dipakai untuk login. Gunakan email yang valid (atau username unik).</small>
                    </div>
                    <div class="form-group">
                        <label>Password <?= $is_edit ? '' : '<span class="text-danger">*</span>' ?></label>
                        <input type="password" class="form-control" name="password" minlength="6" <?= $is_edit ? '' : 'required' ?> placeholder="<?= $is_edit ? 'Kosongkan jika tidak diubah' : 'Minimal 6 karakter' ?>">
                    </div>
                    <div class="form-group">
                        <label>Akses <span class="text-danger">*</span></label>
                        <select name="akses" class="form-control select2" required>
                            <option value="">--Pilih Akses--</option>
                            <option value="admin" <?= ($is_edit && $u['akses'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="user" <?= ($is_edit && $u['akses'] === 'user') ? 'selected' : '' ?>>User</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tupoksi</label>
                        <select name="id_tupoksi" class="form-control select2">
                            <option value="">--Pilih Tupoksi--</option>
                            <?php foreach ($tupoksi as $t): ?>
                                <option value="<?= (int)$t['id_tupoksi'] ?>" <?= ($is_edit && (int)$u['id_tupoksi'] === (int)$t['id_tupoksi']) ? 'selected' : '' ?>><?= h($t['nama_tupoksi']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Foto User</label>
                        <?php if ($is_edit && !empty($u['foto'])): ?>
                            <div class="mb-2"><img src="<?= avatar_url($u['id_user'], $u['foto']) ?>" class="img-thumbnail" style="width:72px;height:72px;object-fit:cover"></div>
                        <?php endif; ?>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="foto" name="foto" accept=".jpg,.jpeg,.png">
                            <label class="custom-file-label" for="foto">Pilih foto (JPG/PNG, maks 1,5MB)</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="background-color:#3c8dbc;border-color:#367fa9"><i class="fa fa-save"></i> Simpan</button>
                    <a href="<?= site_url('users') ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
<input type="hidden" id="formMode" value="<?= $mode ?>">
