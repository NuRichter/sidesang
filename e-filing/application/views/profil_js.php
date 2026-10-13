<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>
$(function () {
    $('#foto').on('change', function () {
        var n = this.files.length ? this.files[0].name : 'Pilih foto (JPG/PNG)';
        $(this).next('.custom-file-label').text(n);
    });
    $('#formProfil').on('submit', function (e) {
        e.preventDefault();
        var fd = new FormData(this); fd.append(CSRF.name, CSRF.hash);
        $.ajax({url: BASE + 'profil/update', type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json'})
            .done(function (r) {
                if (r.status) { Swal.fire({icon: 'success', title: 'Sukses', text: r.text, timer: 1200, showConfirmButton: false}).then(function () { location.reload(); }); }
                else Swal.fire('Gagal', r.text, 'warning');
            }).fail(function () { Swal.fire('Error', 'Terjadi kesalahan.', 'error'); });
    });
    $('#formPassword').on('submit', function (e) {
        e.preventDefault();
        $.post(BASE + 'profil/password', $(this).serialize(), function (r) {
            if (r.status) { $('#formPassword')[0].reset(); Swal.fire('Berhasil', r.text, 'success'); }
            else Swal.fire('Gagal', r.text, 'warning');
        }, 'json').fail(function () { Swal.fire('Error', 'Terjadi kesalahan.', 'error'); });
    });
});
</script>
