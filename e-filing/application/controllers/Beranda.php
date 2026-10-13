<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Beranda extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->guard();
    }

    public function index()
    {
        // admin: semua dokumen; user: hanya tupoksinya; user tanpa tupoksi: 0
        if (is_admin()) {
            $cond = '1=1';
            $bind = array();
        } else {
            $t = current_user('id_tupoksi');
            $cond = 'id_tupoksi = ?';
            $bind = array($t ? (int)$t : -1);
        }
        // gabungkan 4 hitungan dalam satu round-trip DB
        $row = $this->db->query(
            "SELECT
                (SELECT COUNT(*) FROM dokumen WHERE $cond) AS total_surat,
                (SELECT COUNT(*) FROM jenis_surat) AS total_jenis,
                (SELECT COUNT(*) FROM tupoksi) AS total_tupoksi,
                (SELECT COUNT(*) FROM users) AS total_user",
            $bind
        )->row_array();

        $this->render('beranda', array(
            'title'        => 'Beranda',
            'total_surat'  => (int)$row['total_surat'],
            'total_jenis'  => (int)$row['total_jenis'],
            'total_tupoksi' => (int)$row['total_tupoksi'],
            'total_user'   => (int)$row['total_user'],
        ));
    }
}
