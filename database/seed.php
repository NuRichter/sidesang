<?php
/*
|--------------------------------------------------------------------------
| Seeder E-Arsip Desa Bulukandang
|--------------------------------------------------------------------------
| Mengisi data awal + akun uji dengan password bcrypt (password_hash).
| Jalankan secara EKSPLISIT:  php database/seed.php --yes
| Membaca kredensial DB dari environment (DB_HOST, DB_PORT, DB_USER,
| DB_PASS, DB_NAME, DB_SSL) seperti aplikasi.
|
| CATATAN KEAMANAN: akun uji (admin/user/jeki) hanya untuk pengembangan &
| demo. Ganti password-nya sebelum penggunaan nyata.
*/

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Seeder hanya boleh dijalankan dari CLI.\n");
}
if (!in_array('--yes', $argv, true)) {
    exit("Tambahkan flag --yes untuk menjalankan seeder (akan menghapus & mengisi ulang data).\n");
}

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = (int)(getenv('DB_PORT') ?: 3306);
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$name = getenv('DB_NAME') ?: 'db_earsip';
$ssl  = in_array(strtolower((string)getenv('DB_SSL')), ['1', 'true', 'yes', 'on'], true);

mysqli_report(MYSQLI_REPORT_OFF);
$mysqli = mysqli_init();
if ($ssl) {
    $mysqli->ssl_set(null, null, null, null, null);
    $ok = @$mysqli->real_connect($host, $user, $pass, $name, $port, null, MYSQLI_CLIENT_SSL);
} else {
    $ok = @$mysqli->real_connect($host, $user, $pass, $name, $port);
}
if (!$ok) {
    exit("Gagal koneksi DB: " . mysqli_connect_error() . "\n");
}
$mysqli->set_charset('utf8mb4');

function run($db, $sql)
{
    if (!$db->query($sql)) {
        fwrite(STDERR, "SQL error: " . $db->error . "\n  " . substr($sql, 0, 120) . "\n");
        exit(1);
    }
}
function esc($db, $v)
{
    return $v === null ? 'NULL' : "'" . $db->real_escape_string($v) . "'";
}

echo "Mengosongkan tabel...\n";
run($mysqli, "SET FOREIGN_KEY_CHECKS=0");
foreach (['dokumen_berkas', 'dokumen', 'users', 'jenis_surat', 'tupoksi', 'ci_sessions'] as $t) {
    run($mysqli, "TRUNCATE TABLE `$t`");
}
run($mysqli, "SET FOREIGN_KEY_CHECKS=1");

// ---------------- tupoksi ----------------
$tupoksi = [
    'Kepala Desa', 'Sekretaris Desa', 'Kaur Keuangan', 'Kaur Umum & Perencanaan',
    'Kasi Pemerintahan', 'Kasi Kesejahteraan', 'Kasi Pelayanan',
    'Kepala Dusun Kandangan Krajan', 'Kepala Dusun Bulu Krajan',
    'Kepala Dusun Tegalan Kandangan', 'Kepala Dusun Tegalan Bulu',
    'Operator SIKS-NG', 'Operator e-PKH',
];
$tid = [];
foreach ($tupoksi as $t) {
    run($mysqli, "INSERT INTO tupoksi (nama_tupoksi) VALUES (" . esc($mysqli, $t) . ")");
    $tid[$t] = $mysqli->insert_id;
}
echo "Tupoksi: " . count($tid) . "\n";

// ---------------- jenis surat ----------------
$jenis = [
    ['LKPJ', 'Laporan Keterangan Pertanggungjawaban'],
    ['Surat Kematian', 'Surat keterangan kematian'],
    ['Surat Pengantar', 'Surat pengantar warga'],
    ['Surat Keterangan Tidak Mampu', 'SKTM'],
    ['Surat Keterangan Usaha', 'SKU'],
    ['Surat Izin', 'Surat izin kegiatan'],
    ['Undangan', 'Surat undangan'],
    ['Surat Keputusan', 'SK Kepala Desa'],
];
$jid = [];
foreach ($jenis as $j) {
    run($mysqli, "INSERT INTO jenis_surat (nama_jenis, keterangan) VALUES (" . esc($mysqli, $j[0]) . "," . esc($mysqli, $j[1]) . ")");
    $jid[$j[0]] = $mysqli->insert_id;
}
echo "Jenis surat: " . count($jid) . "\n";

// ---------------- users ----------------
$h = fn($p) => password_hash($p, PASSWORD_BCRYPT);
$users = [
    ['Administrator', 'admin', $h('admin'), 'admin', $tid['Kepala Desa']],
    ['Operator Desa', 'user', $h('user'), 'user', $tid['Operator SIKS-NG']],
    ['Jeki', 'cepetlulus', $h('janganmagerjek'), 'jeki', null],
    ['Nia Rismala', 'nia@desa.id', $h('user12345'), 'user', $tid['Kasi Pelayanan']],
    ['Ahmad Fauzi', 'fauzi@desa.id', $h('user12345'), 'user', $tid['Kasi Pemerintahan']],
];
$uid = [];
foreach ($users as $u) {
    run($mysqli, "INSERT INTO users (nama, email, password, akses, id_tupoksi) VALUES ("
        . esc($mysqli, $u[0]) . "," . esc($mysqli, $u[1]) . "," . esc($mysqli, $u[2]) . ","
        . esc($mysqli, $u[3]) . "," . ($u[4] === null ? 'NULL' : (int)$u[4]) . ")");
    $uid[$u[1]] = $mysqli->insert_id;
}
echo "Users: " . count($uid) . " (admin/admin, user/user, cepetlulus/janganmagerjek)\n";

// ---------------- dokumen ----------------
$dok = [
    ['001/BLK/2026', 'Surat Keterangan Tidak Mampu - Budi Santoso', 'Pengajuan beasiswa', 'Permohonan SKTM untuk keperluan sekolah', 'Surat Keterangan Tidak Mampu', '2026-01-12', 'user', 'Operator SIKS-NG'],
    ['002/BLK/2026', 'Surat Pengantar KK - Siti Aminah', 'Pengantar kartu keluarga', 'Pengantar pembuatan KK baru', 'Surat Pengantar', '2026-02-03', 'user', 'Operator SIKS-NG'],
    ['003/BLK/2026', 'Undangan Musyawarah Desa', 'Rapat perangkat', 'Undangan Musdes penetapan APBDes', 'Undangan', '2026-02-20', 'admin', 'Kasi Pelayanan'],
    ['004/BLK/2026', 'LKPJ Kepala Desa Tahun 2025', 'Laporan tahunan', 'Laporan keterangan pertanggungjawaban kepala desa', 'LKPJ', '2026-03-01', 'admin', 'Kepala Desa'],
];
foreach ($dok as $d) {
    run($mysqli, "INSERT INTO dokumen (nomor_dokumen, nama_dokumen, catatan, deskripsi, id_jenis, tanggal_surat, id_user, id_tupoksi) VALUES ("
        . esc($mysqli, $d[0]) . "," . esc($mysqli, $d[1]) . "," . esc($mysqli, $d[2]) . "," . esc($mysqli, $d[3]) . ","
        . (int)$jid[$d[4]] . "," . esc($mysqli, $d[5]) . "," . (int)$uid[$d[6]] . "," . (int)$tid[$d[7]] . ")");
}
echo "Dokumen: " . count($dok) . "\n";

echo "Selesai.\n";
$mysqli->close();
