<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>
$(function () {
    $('#tbl').DataTable({
        order: [[1, 'asc']],
        columnDefs: [{targets: [0, 3], orderable: false}],
        language: {emptyTable: 'Belum ada data', zeroRecords: 'Tidak ditemukan'}
    });
    function openModal(t) { $('.modal-title').text(t); $('#modalForm').modal('show'); }
    $('#btnTambah').on('click', function () { $('#formData')[0].reset(); $('#id_jenis').val(''); openModal('Tambah Jenis Surat'); });
    $('#tbl tbody').on('click', '.btn-edit', function () {
        $.getJSON(BASE + 'jenis-surat/get/' + $(this).data('id'), function (r) {
            if (!r.status) return toast('error', 'Data tidak ditemukan');
            $('#id_jenis').val(r.data.id_jenis); $('#nama_jenis').val(r.data.nama_jenis); $('#keterangan').val(r.data.keterangan || '');
            openModal('Edit Jenis Surat');
        });
    });
    $('#formData').on('submit', function (e) {
        e.preventDefault();
        $.post(BASE + 'jenis-surat/simpan', $(this).serialize(), function (r) {
            if (r.status) { toast('success', r.text); setTimeout(function () { location.reload(); }, 600); }
            else Swal.fire('Gagal', r.text, 'warning');
        }, 'json').fail(function () { Swal.fire('Error', 'Terjadi kesalahan.', 'error'); });
    });
    $('#tbl tbody').on('click', '.btn-hapus', function () {
        var id = $(this).data('id');
        Swal.fire({title: 'Hapus jenis surat?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal', confirmButtonColor: '#d33'})
            .then(function (res) {
                if (!res.isConfirmed) return;
                $.post(BASE + 'jenis-surat/hapus/' + id, {}, function (r) {
                    if (r.status) { toast('success', r.text); setTimeout(function () { location.reload(); }, 600); } else Swal.fire('Gagal', r.text, 'warning');
                }, 'json');
            });
    });
});
</script>
