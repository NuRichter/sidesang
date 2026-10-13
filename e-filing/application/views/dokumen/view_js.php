<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>
$(function () {
    $('#btnPrint').on('click', function () {
        var url = $('#previewUrl').val();
        var isPdf = $('#isPdf').val() === '1';
        if (isPdf) {
            var f = document.getElementById('viewer');
            try { f.contentWindow.focus(); f.contentWindow.print(); return; } catch (e) {}
            window.open(url, '_blank');
        } else {
            var w = window.open('', '_blank');
            w.document.write('<img src="' + url + '" style="max-width:100%" onload="window.focus();window.print();">');
            w.document.close();
        }
    });
});
</script>
