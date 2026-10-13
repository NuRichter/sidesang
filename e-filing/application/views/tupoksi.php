<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="card card-earsip">
    <div class="card-header">
        <h3 class="card-title">Data Tupoksi</h3>
    </div>
    <div class="card-body">
        <button class="btn btn-primary mb-3" id="btnTambah" style="background-color:#3c8dbc;border-color:#367fa9"><i class="fa fa-plus"></i> Tambah</button>
        <div class="table-responsive">
            <table id="tbl" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th class="text-center" style="width:60px">No</th>
                        <th>Nama Tupoksi</th>
                        <th class="text-center" style="width:180px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 0;
                    foreach ($rows as $r): $no++; ?>
                        <tr>
                            <td class="text-center"><?= $no ?></td>
                            <td><?= h($r['nama_tupoksi']) ?></td>
                            <td class="text-center text-nowrap">
                                <button class="btn btn-sm btn-success btn-edit" data-id="<?= (int)$r['id_tupoksi'] ?>"><i class="fa fa-edit"></i> Edit</button>
                                <button class="btn btn-sm btn-danger btn-hapus" data-id="<?= (int)$r['id_tupoksi'] ?>"><i class="fa fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalForm" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header earsip-navbar text-white">
                <h5 class="modal-title">Form Tupoksi</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formData">
                <div class="modal-body">
                    <input type="hidden" name="id_tupoksi" id="id_tupoksi">
                    <div class="form-group">
                        <label>Nama Tupoksi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_tupoksi" id="nama_tupoksi" maxlength="100" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" style="background-color:#3c8dbc;border-color:#367fa9">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
