<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->guard(false); // login, bukan jeki
    }

    public function index()
    {
        $u = $this->m_user->find_by_id(current_user('id_user'));
        if (!$u) return $this->kill_session();
        $this->render('profil', array('title' => 'Profil', 'u' => $u, 'tupoksi' => current_user('tupoksi')));
    }

    private function upload_foto(&$err)
    {
        $err = null;
        if (empty($_FILES['foto']['name']) || !is_uploaded_file($_FILES['foto']['tmp_name'])) return false;
        $dir = rtrim(str_replace('\\', '/', sys_get_temp_dir()), '/') . '/earsip_foto';
        if (!is_dir($dir)) @mkdir($dir, 0777, true);
        $this->load->library('upload', array('upload_path' => $dir, 'allowed_types' => 'jpg|jpeg|png', 'max_size' => 1500, 'encrypt_name' => true));
        if (!$this->upload->do_upload('foto')) {
            $err = strip_tags($this->upload->display_errors('', ''));
            return false;
        }
        $d = $this->upload->data();
        $mime = $d['file_type'];
        if (function_exists('finfo_open')) {
            $f = finfo_open(FILEINFO_MIME_TYPE);
            $real = finfo_file($f, $d['full_path']);
            finfo_close($f);
            if ($real) $mime = $real;
        }
        if (!in_array($mime, array('image/jpeg', 'image/png'), true)) {
            @unlink($d['full_path']);
            $err = 'Foto harus JPG atau PNG.';
            return false;
        }
        $bytes = file_get_contents($d['full_path']);
        @unlink($d['full_path']);
        return array('name' => preg_replace('/[^A-Za-z0-9._-]/', '_', $_FILES['foto']['name']), 'mime' => $mime, 'bytes' => $bytes);
    }

    public function update()
    {
        $id = (int)current_user('id_user');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('email', 'Email/Username', 'required|trim|max_length[150]');
        if ($this->form_validation->run() === false) {
            $this->json(array('status' => false, 'text' => strip_tags(validation_errors())));
        }
        $email = $this->input->post('email', true);
        if (strpos($email, '@') !== false && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(array('status' => false, 'text' => 'Format email tidak valid.'));
        }
        if ($this->m_user->email_exists($email, $id)) {
            $this->json(array('status' => false, 'text' => 'Email/username sudah dipakai user lain.'));
        }
        $foto = $this->upload_foto($err);
        if ($err) $this->json(array('status' => false, 'text' => $err));

        // Catatan: akses & tupoksi TIDAK dapat diubah sendiri oleh user.
        $data = array('nama' => $this->input->post('nama', true), 'email' => $email, 'updated_at' => date('Y-m-d H:i:s'));
        if ($foto) $data['foto'] = $foto['name'];
        $this->m_user->update($id, $data);
        if ($foto) $this->m_user->save_foto($id, $foto['mime'], $foto['bytes']);

        $this->session->set_userdata(array('nama' => $data['nama'], 'email' => $email, 'foto' => $foto ? $foto['name'] : current_user('foto')));
        $this->json(array('status' => true, 'text' => 'Profil berhasil diperbarui.'));
    }

    public function password()
    {
        $id = (int)current_user('id_user');
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('konfirmasi', 'Konfirmasi Password', 'required|matches[password_baru]');
        if ($this->form_validation->run() === false) {
            $this->json(array('status' => false, 'text' => strip_tags(validation_errors())));
        }
        $u = $this->m_user->find_by_id($id);
        if (!$u || !password_verify($this->input->post('password_lama', false), $u['password'])) {
            $this->json(array('status' => false, 'text' => 'Password lama tidak sesuai.'));
        }
        $this->m_user->update($id, array('password' => password_hash($this->input->post('password_baru', false), PASSWORD_BCRYPT), 'updated_at' => date('Y-m-d H:i:s')));
        // amankan sesi: regenerasi id session
        $this->session->sess_regenerate(true);
        $this->json(array('status' => true, 'text' => 'Password berhasil diubah. Gunakan password baru saat login berikutnya.'));
    }
}
