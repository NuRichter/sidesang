<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>
$(function () {
    var tbl = $('#tbl').DataTable({
        processing: true, serverSide: true, order: [[6, 'desc']], scrollX: true,
        ajax: {
            url: BASE + 'dokumen/data', type: 'POST',
            data: function (d) {
                d[CSRF.name] = CSRF.hash;
                d.f_jenis = $('#f_jenis').val();
                d.f_from = $('#f_from').val();
                d.f_to = $('#f_to').val();
            }
        },
        columns: [
            {data: 0, orderable: false, className: 'text-center'},
            {data: 1}, {data: 2}, {data: 3}, {data: 4}, {data: 5}, {data: 6}, {data: 7}, {data: 8},
            {data: 9, orderable: false, searchable: false, className: 'text-center'},
            {data: 10, orderable: false, searchable: false, className: 'text-center'}
        ],
        language: {emptyTable: 'Belum ada dokumen', zeroRecords: 'Dokumen tidak ditemukan', processing: 'Memuat...'}
    });

    $('#f_jenis, #f_from, #f_to').on('change', function () { tbl.ajax.reload(); });
    $('#btnReset').on('click', function () { $('#f_jenis').val(''); $('#f_from').val(''); $('#f_to').val(''); tbl.ajax.reload(); });

    $('#tbl tbody').on('click', '.btn-hapus', function () {
        var id = $(this).data('id');
        Swal.fire({title: 'Hapus dokumen?', text: 'Berkas arsip juga ikut terhapus.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal', confirmButtonColor: '#d33'})
            .then(function (res) {
                if (!res.isConfirmed) return;
                $.post(BASE + 'dokumen/hapus/' + id, {}, function (r) {
                    if (r.status) { tbl.ajax.reload(null, false); toast('success', r.text); } else Swal.fire('Gagal', r.text, 'warning');
                }, 'json');
            });
    });
});
</script>
