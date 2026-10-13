<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>
$(function () {
    var tbl = $('#tbl').DataTable({
        processing: true, serverSide: true, order: [[1, 'asc']],
        ajax: {url: BASE + 'users/data', type: 'POST', data: function (d) { d[CSRF.name] = CSRF.hash; }},
        columns: [
            {data: 0, orderable: false}, {data: 1}, {data: 2},
            {data: 3, orderable: false, searchable: false},
            {data: 4}, {data: 5},
            {data: 6, orderable: false, searchable: false},
            {data: 7, orderable: false, searchable: false}
        ],
        language: {emptyTable: 'Belum ada user', zeroRecords: 'User tidak ditemukan', processing: 'Memuat...'}
    });

    $('#tbl tbody').on('click', '.btn-reset', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Reset Password', input: 'password', inputLabel: 'Password baru (min 6 karakter)',
            inputAttributes: {minlength: 6}, showCancelButton: true, confirmButtonText: 'Simpan', cancelButtonText: 'Batal'
        }).then(function (res) {
            if (!res.isConfirmed || !res.value) return;
            $.post(BASE + 'users/reset/' + id, {password: res.value}, function (r) {
                if (r.status) toast('success', r.text); else Swal.fire('Gagal', r.text, 'warning');
            }, 'json');
        });
    });

    $('#tbl tbody').on('click', '.btn-hapus', function () {
        var id = $(this).data('id'), nama = $(this).data('nama');
        Swal.fire({title: 'Hapus user?', text: nama, icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal', confirmButtonColor: '#d33'})
            .then(function (res) {
                if (!res.isConfirmed) return;
                $.post(BASE + 'users/hapus/' + id, {}, function (r) {
                    if (r.status) { tbl.ajax.reload(null, false); toast('success', r.text); } else Swal.fire('Gagal', r.text, 'warning');
                }, 'json');
            });
    });
});
</script>
