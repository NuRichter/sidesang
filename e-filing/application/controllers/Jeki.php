<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| Tab khusus untuk akun Jeki (akses level 'jeki'). Hanya bisa diakses oleh
| akun dengan akses 'jeki' yang diverifikasi dari database. Admin & user
| biasa ditolak, URL langsung tidak bisa menembus.
*/
class Jeki extends MY_Controller
{
    public function index()
    {
        $this->guard_jeki();
        $this->load->view('jeki', array('nama' => current_user('nama')));
    }
}
