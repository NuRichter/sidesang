<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Helper aplikasi E-Arsip Desa Bulukandang
|--------------------------------------------------------------------------
*/

if (!function_exists('logged_in')) {
    function logged_in()
    {
        return get_instance()->session->userdata('is_login') === true;
    }
}

if (!function_exists('current_user')) {
    function current_user($key = null)
    {
        $ci = get_instance();
        $u = array(
            'id_user'    => $ci->session->userdata('id_user'),
            'nama'       => $ci->session->userdata('nama'),
            'email'      => $ci->session->userdata('email'),
            'akses'      => $ci->session->userdata('akses'),
            'id_tupoksi' => $ci->session->userdata('id_tupoksi'),
            'tupoksi'    => $ci->session->userdata('tupoksi'),
            'foto'       => $ci->session->userdata('foto'),
        );
        return $key === null ? $u : (isset($u[$key]) ? $u[$key] : null);
    }
}

if (!function_exists('is_admin')) {
    function is_admin()
    {
        return current_user('akses') === 'admin';
    }
}

if (!function_exists('is_jeki')) {
    function is_jeki()
    {
        return current_user('akses') === 'jeki';
    }
}

if (!function_exists('hari_ini')) {
    function hari_ini($ts = null)
    {
        $hari = array('Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu');
        $d = date('l', $ts === null ? time() : $ts);
        return isset($hari[$d]) ? $hari[$d] : $d;
    }
}

if (!function_exists('tgl_id')) {
    function tgl_id($date)
    {
        if (empty($date) || $date === '0000-00-00') return '-';
        $bln = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $ts = strtotime($date);
        if ($ts === false) return $date;
        return date('d', $ts) . ' ' . $bln[(int)date('n', $ts)] . ' ' . date('Y', $ts);
    }
}

if (!function_exists('human_size')) {
    function human_size($bytes)
    {
        $bytes = (int)$bytes;
        if ($bytes <= 0) return '0 Byte';
        if ($bytes < 1024) return $bytes . ' Byte';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 2) . ' MB';
    }
}

if (!function_exists('avatar_url')) {
    // foto user disimpan di DB dan disajikan lewat users/foto/{id}
    function avatar_url($id_user, $foto = null)
    {
        if (!empty($foto) && !empty($id_user)) {
            return site_url('users/foto/' . (int)$id_user);
        }
        return base_url('assets/dist/img/user-default.png');
    }
}

if (!function_exists('h')) {
    // escape output HTML
    function h($v)
    {
        return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
    }
}
