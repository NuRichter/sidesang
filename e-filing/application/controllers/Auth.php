<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_user', 'm_user');
    }

    public function index()
    {
        if (logged_in()) {
            redirect(site_url(current_user('akses') === 'jeki' ? 'jeki' : 'beranda'));
        }
        $this->load->view('login');
    }

    public function proses()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('login_error', 'Username dan password wajib diisi.');
            redirect(site_url('login'));
            return;
        }

        $email = $this->input->post('username', true);
        $password = (string)$this->input->post('password', false);
        $user = $this->m_user->verify($email, $password);

        if (!$user) {
            $this->session->set_flashdata('login_error', 'Username atau password tidak sesuai.');
            redirect(site_url('login'));
            return;
        }

        $tupoksi = null;
        if (!empty($user['id_tupoksi'])) {
            $this->load->model('M_tupoksi', 'm_tupoksi');
            $t = $this->m_tupoksi->get($user['id_tupoksi']);
            $tupoksi = $t ? $t['nama_tupoksi'] : null;
        }

        // regenerasi session id untuk mencegah session fixation
        $this->session->sess_regenerate(true);
        $this->session->set_userdata(array(
            'id_user'    => $user['id_user'],
            'nama'       => $user['nama'],
            'email'      => $user['email'],
            'akses'      => $user['akses'],
            'id_tupoksi' => $user['id_tupoksi'],
            'tupoksi'    => $tupoksi,
            'foto'       => $user['foto'],
            'is_login'   => true,
        ));

        redirect(site_url($user['akses'] === 'jeki' ? 'jeki' : 'beranda'));
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(site_url('login'));
    }
}
