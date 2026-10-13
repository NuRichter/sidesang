<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">Hari ini <?= hari_ini() ?></div>
            <strong>Copyright &copy; <?= date('Y') ?> <a href="<?= site_url('beranda') ?>">Desa Bulukandang</a>.</strong>
            Sistem Arsip Digital (SIDESANG).
        </footer>
    </div>

<?php $plugins = isset($plugins) ? (array)$plugins : array(); ?>
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
    <?php if (in_array('datatables', $plugins)): ?>
        <script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
        <script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
    <?php endif; ?>
    <?php if (in_array('select2', $plugins)): ?>
        <script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>
    <?php endif; ?>
    <?php if (in_array('datepicker', $plugins)): ?>
        <script src="<?= base_url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') ?>"></script>
    <?php endif; ?>
    <script>
        // CSRF untuk semua request AJAX
        var CSRF = {name: $('meta[name=csrf-name]').attr('content'), hash: $('meta[name=csrf-hash]').attr('content')};
        $.ajaxSetup({
            beforeSend: function (xhr, settings) {
                if (settings.type && settings.type.toUpperCase() === 'POST' && typeof settings.data === 'string' && settings.data.indexOf(CSRF.name) === -1) {
                    settings.data += (settings.data ? '&' : '') + CSRF.name + '=' + encodeURIComponent(CSRF.hash);
                }
            }
        });
        // perbarui hash CSRF dari response (regenerate dimatikan, tapi aman)
        function refreshCsrf(h) { if (h) { CSRF.hash = h; $('meta[name=csrf-hash]').attr('content', h); } }

        // jam realtime
        function tikJam() {
            var d = new Date();
            var p = function (n) { return (n < 10 ? '0' : '') + n; };
            $('#jam').text(p(d.getHours()) + ':' + p(d.getMinutes()) + ':' + p(d.getSeconds()));
        }
        setInterval(tikJam, 1000); tikJam();

        var BASE = '<?= site_url() ?>';
        function toast(icon, title) {
            Swal.fire({toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, icon: icon, title: title});
        }
    </script>
    <?php
    $js_partial = APPPATH . 'views/' . $_view . '_js.php';
    if (is_file($js_partial)) {
        $this->load->view($_view . '_js');
    }
    ?>
</body>

</html>
