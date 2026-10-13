<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| MY_Controller - base controller E-Arsip
|--------------------------------------------------------------------------
| Menyediakan penjagaan autentikasi & otorisasi di sisi backend. Role
| diverifikasi ulang dari database pada setiap request, tidak sekadar
| mempercayai nilai di session/URL.
*/
class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_user', 'm_user');
    }

    /**
     * Penjaga halaman internal.
     * @param bool $admin  true = hanya admin yang boleh.
     */
    protected function guard($admin = false)
    {
        if (!logged_in()) {
            return $this->bounce();
        }
        // verifikasi ulang akun & role dari DB (data ringan)
        $db = $this->m_user->find_auth(current_user('id_user'));
        if (!$db || $db['akses'] !== current_user('akses') || $db['email'] !== current_user('email')) {
            return $this->kill_session();
        }
        // segarkan tupoksi/foto di session bila admin mengubahnya
        if ($db['id_tupoksi'] != current_user('id_tupoksi') || $db['nama_tupoksi'] != current_user('tupoksi') || $db['foto'] != current_user('foto')) {
            $this->session->set_userdata(array(
                'id_tupoksi' => $db['id_tupoksi'],
                'tupoksi' => $db['nama_tupoksi'],
                'foto' => $db['foto'],
                'nama' => $db['nama'],
            ));
        }
        // akun jeki hanya boleh di tab khusus
        if ($db['akses'] === 'jeki') {
            return $this->deny();
        }
        if ($admin && $db['akses'] !== 'admin') {
            return $this->deny();
        }
    }

    /** Penjaga khusus tab Jeki. */
    protected function guard_jeki()
    {
        if (!logged_in()) {
            return $this->bounce();
        }
        $db = $this->m_user->find_by_id(current_user('id_user'));
        if (!$db || $db['email'] !== current_user('email') || $db['akses'] !== current_user('akses')) {
            return $this->kill_session();
        }
        if ($db['akses'] !== 'jeki') {
            return $this->deny();
        }
    }

    protected function bounce()
    {
        if ($this->input->is_ajax_request()) {
            $this->json(array('status' => false, 'auth' => false, 'text' => 'Sesi berakhir, silakan login kembali.'), 401);
        }
        redirect(site_url('login'));
        exit;
    }

    protected function kill_session()
    {
        $this->session->sess_destroy();
        return $this->bounce();
    }

    protected function deny()
    {
        if ($this->input->is_ajax_request()) {
            $this->json(array('status' => false, 'text' => 'Anda tidak memiliki akses untuk tindakan ini.'), 403);
        }
        show_error('Anda tidak memiliki akses ke halaman ini.', 403, 'Akses Ditolak');
        exit;
    }

    protected function json($data, $code = 200)
    {
        $this->output->set_status_header($code);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data);
        exit;
    }

    /** Render halaman internal (template navbar + konten + footer). */
    protected function render($view, $data = array())
    {
        $data['_user']  = current_user();
        $data['_title'] = isset($data['title']) ? $data['title'] : 'E-Arsip';
        $data['_view']  = $view;
        $this->load->view('template/header', $data);
        $this->load->view($view, $data);
        $this->load->view('template/footer', $data);
    }
}
