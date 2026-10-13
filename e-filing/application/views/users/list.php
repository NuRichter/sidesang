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
                <tbody>
                    <?php $no = 0;
                    foreach ($rows as $r): $no++;
                        $badge = $r['akses'] === 'admin' ? 'primary' : ($r['akses'] === 'jeki' ? 'dark' : 'secondary'); ?>
                        <tr>
                            <td class="text-center"><?= $no ?></td>
                            <td><?= h($r['nama']) ?></td>
                            <td><?= h($r['email']) ?></td>
                            <td><span class="text-muted">&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;</span></td>
                            <td><span class="badge badge-<?= $badge ?>"><?= h(ucfirst($r['akses'])) ?></span></td>
                            <td><?= h($r['nama_tupoksi']) ?: '-' ?></td>
                            <td class="text-center"><img src="<?= avatar_url($r['id_user'], $r['foto']) ?>" class="earsip-avatar"></td>
                            <td class="text-center text-nowrap">
                                <a href="<?= site_url('users/edit/' . $r['id_user']) ?>" class="btn btn-sm btn-success" title="Edit"><i class="fa fa-edit"></i></a>
                                <button class="btn btn-sm btn-warning btn-reset" data-id="<?= (int)$r['id_user'] ?>" title="Reset Password"><i class="fa fa-key"></i></button>
                                <button class="btn btn-sm btn-danger btn-hapus" data-id="<?= (int)$r['id_user'] ?>" data-nama="<?= h($r['nama']) ?>" title="Hapus"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
