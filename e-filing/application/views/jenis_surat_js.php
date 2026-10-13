<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>
$(function () {
    var tbl = $('#tbl').DataTable({
        processing: true, serverSide: true, order: [[1, 'asc']],
        ajax: {
            url: BASE + 'jenis-surat/data', type: 'POST',
            data: function (d) { d[CSRF.name] = CSRF.hash; }
        },
        columns: [
            {data: 0, orderable: false}, {data: 1}, {data: 2}, {data: 3, orderable: false, searchable: false}
        ],
        language: {emptyTable: 'Belum ada data', zeroRecords: 'Tidak ditemukan', processing: 'Memuat...'}
    });

    function openModal(title) { $('.modal-title').text(title); $('#modalForm').modal('show'); }

    $('#btnTambah').on('click', function () {
        $('#formData')[0].reset(); $('#id_jenis').val(''); openModal('Tambah Jenis Surat');
    });

    $('#tbl tbody').on('click', '.btn-edit', function () {
        var id = $(this).data('id');
        $.getJSON(BASE + 'jenis-surat/get/' + id, function (r) {
            if (!r.status) return toast('error', 'Data tidak ditemukan');
            $('#id_jenis').val(r.data.id_jenis);
            $('#nama_jenis').val(r.data.nama_jenis);
            $('#keterangan').val(r.data.keterangan || '');
            openModal('Edit Jenis Surat');
        });
    });

    $('#formData').on('submit', function (e) {
        e.preventDefault();
        $.post(BASE + 'jenis-surat/simpan', $(this).serialize(), function (r) {
            if (r.status) { $('#modalForm').modal('hide'); tbl.ajax.reload(null, false); toast('success', r.text); }
            else { Swal.fire('Gagal', r.text, 'warning'); }
        }, 'json').fail(function () { Swal.fire('Error', 'Terjadi kesalahan.', 'error'); });
    });

    $('#tbl tbody').on('click', '.btn-hapus', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus jenis surat?', icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal', confirmButtonColor: '#d33'
        }).then(function (res) {
            if (!res.isConfirmed) return;
            $.post(BASE + 'jenis-surat/hapus/' + id, {}, function (r) {
                if (r.status) { tbl.ajax.reload(null, false); toast('success', r.text); }
                else { Swal.fire('Gagal', r.text, 'warning'); }
            }, 'json');
        });
    });
});
</script>
