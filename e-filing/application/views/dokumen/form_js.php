<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>
$(function () {
    $('.select2').select2({theme: 'bootstrap4', width: '100%'});
    $('#tanggal_dokumen').datepicker({format: 'dd/mm/yyyy', autoclose: true, todayHighlight: true});
    $('#file').on('change', function () {
        var n = this.files.length ? this.files[0].name : 'Pilih berkas (PDF/JPG/PNG, maks 4MB)';
        $(this).next('.custom-file-label').text(n);
    });

    var mode = $('#formMode').val();
    var url = BASE + (mode === 'edit' ? 'dokumen/update' : 'dokumen/simpan');

    $('#formDok').on('submit', function (e) {
        e.preventDefault();
        var fd = new FormData(this);
        fd.append(CSRF.name, CSRF.hash);
        var $btn = $(this).find('button[type=submit]').prop('disabled', true);
        $.ajax({
            url: url, type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json'
        }).done(function (r) {
            if (r.status) {
                Swal.fire({icon: 'success', title: 'Sukses', text: r.text, timer: 1200, showConfirmButton: false})
                    .then(function () { window.location = r.redirect || (BASE + 'dokumen'); });
            } else { Swal.fire('Gagal', r.text, 'warning'); $btn.prop('disabled', false); }
        }).fail(function (xhr) {
            Swal.fire('Error', (xhr.status === 403 ? 'Akses ditolak / sesi berakhir.' : 'Terjadi kesalahan.'), 'error');
            $btn.prop('disabled', false);
        });
    });
});
</script>
