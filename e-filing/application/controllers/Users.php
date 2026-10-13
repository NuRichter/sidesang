<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->guard(false); // wajib login, bukan jeki
        $this->load->model('M_tupoksi', 'm_tupoksi');
    }

    private function admin_gate()
    {
        if (!is_admin()) {
            $this->deny();
        }
    }

    public function index()
    {
        $this->admin_gate();
        $this->render('users/list', array('title' => 'Data User'));
    }

    public function data()
    {
        $this->admin_gate();
        $post = $this->input->post(null, true);
        $rows = $this->m_user->dt_data($post);
        $no = (int)$post['start'];
        $data = array();
        foreach ($rows as $r) {
            $no++;
            $foto = '<img src="' . avatar_url($r['id_user'], $r['foto']) . '" class="earsip-avatar">';
            $aksi = '<a href="' . site_url('users/edit/' . $r['id_user']) . '" class="btn btn-sm btn-success" title="Edit"><i class="fa fa-edit"></i></a> '
                . '<button class="btn btn-sm btn-warning btn-reset" data-id="' . $r['id_user'] . '" title="Reset Password"><i class="fa fa-key"></i></button> '
                . '<button class="btn btn-sm btn-danger btn-hapus" data-id="' . $r['id_user'] . '" data-nama="' . h($r['nama']) . '" title="Hapus"><i class="fa fa-trash"></i></button>';
            $data[] = array(
                '<div class="text-center">' . $no . '</div>',
                h($r['nama']),
                h($r['email']),
                '<span class="text-muted">&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;</span>',
                '<span class="badge badge-' . ($r['akses'] === 'admin' ? 'primary' : ($r['akses'] === 'jeki' ? 'dark' : 'secondary')) . '">' . h(ucfirst($r['akses'])) . '</span>',
                h($r['nama_tupoksi']) ?: '-',
                '<div class="text-center">' . $foto . '</div>',
                '<div class="text-center text-nowrap">' . $aksi . '</div>',
            );
        }
        $this->json(array(
            'draw' => (int)$post['draw'],
            'recordsTotal' => $this->m_user->dt_count_all(),
            'recordsFiltered' => $this->m_user->dt_count_filtered($post),
            'data' => $data,
        ));
    }

    public function tambah()
    {
        $this->admin_gate();
        $this->render('users/form', array('title' => 'Tambah User', 'mode' => 'tambah', 'tupoksi' => $this->m_tupoksi->all(), 'u' => null));
    }

    public function edit($id)
    {
        $this->admin_gate();
        $u = $this->m_user->find_by_id($id);
        if (!$u) show_404();
        $this->render('users/form', array('title' => 'Edit User', 'mode' => 'edit', 'tupoksi' => $this->m_tupoksi->all(), 'u' => $u));
    }

    private function upload_foto(&$err, $id = null)
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
            $err = 'Foto harus berupa JPG atau PNG.';
            return false;
        }
        $bytes = file_get_contents($d['full_path']);
        @unlink($d['full_path']);
        return array('name' => preg_replace('/[^A-Za-z0-9._-]/', '_', $_FILES['foto']['name']), 'mime' => $mime, 'bytes' => $bytes);
    }

    private function rules($is_edit, $id = null)
    {
        $this->form_validation->set_rules('nama', 'Nama User', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('email', 'Email/Username', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('akses', 'Akses', 'required|in_list[admin,user]');
        if (!$is_edit) {
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        } else {
            if ($this->input->post('password', false) !== '') {
                $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
            }
        }
        return $this->form_validation->run();
    }

    public function simpan()
    {
        $this->admin_gate();
        if ($this->rules(false) === false) {
            $this->json(array('status' => false, 'text' => strip_tags(validation_errors())));
        }
        $email = $this->input->post('email', true);
        if (strpos($email, '@') !== false && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(array('status' => false, 'text' => 'Format email tidak valid.'));
        }
        if ($this->m_user->email_exists($email)) {
            $this->json(array('status' => false, 'text' => 'Email/username sudah terpakai.'));
        }
        $id_tupoksi = (int)$this->input->post('id_tupoksi', true) ?: null;
        if ($id_tupoksi && !$this->m_tupoksi->get($id_tupoksi)) {
            $this->json(array('status' => false, 'text' => 'Tupoksi tidak valid.'));
        }
        $foto = $this->upload_foto($err);
        if ($err) $this->json(array('status' => false, 'text' => $err));

        $data = array(
            'nama' => $this->input->post('nama', true),
            'email' => $email,
            'password' => password_hash($this->input->post('password', false), PASSWORD_BCRYPT),
            'akses' => $this->input->post('akses', true),
            'id_tupoksi' => $id_tupoksi,
            'foto' => $foto ? $foto['name'] : null,
        );
        $id = $this->m_user->create($data);
        if ($foto) $this->m_user->save_foto($id, $foto['mime'], $foto['bytes']);
        $this->json(array('status' => true, 'text' => 'User berhasil ditambahkan.', 'redirect' => site_url('users')));
    }

    public function update()
    {
        $this->admin_gate();
        $id = (int)$this->input->post('id_user', true);
        $u = $this->m_user->find_by_id($id);
        if (!$u) $this->json(array('status' => false, 'text' => 'User tidak ditemukan.'), 404);
        if ($this->rules(true, $id) === false) {
            $this->json(array('status' => false, 'text' => strip_tags(validation_errors())));
        }
        $email = $this->input->post('email', true);
        if (strpos($email, '@') !== false && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(array('status' => false, 'text' => 'Format email tidak valid.'));
        }
        if ($this->m_user->email_exists($email, $id)) {
            $this->json(array('status' => false, 'text' => 'Email/username sudah terpakai user lain.'));
        }
        $akses = $this->input->post('akses', true);
        // cegah menurunkan admin terakhir
        if ($u['akses'] === 'admin' && $akses !== 'admin' && $this->m_user->count_admin() <= 1) {
            $this->json(array('status' => false, 'text' => 'Tidak dapat mengubah akses: ini admin terakhir.'));
        }
        $id_tupoksi = (int)$this->input->post('id_tupoksi', true) ?: null;
        if ($id_tupoksi && !$this->m_tupoksi->get($id_tupoksi)) {
            $this->json(array('status' => false, 'text' => 'Tupoksi tidak valid.'));
        }
        $foto = $this->upload_foto($err);
        if ($err) $this->json(array('status' => false, 'text' => $err));

        $data = array(
            'nama' => $this->input->post('nama', true),
            'email' => $email,
            'akses' => $akses,
            'id_tupoksi' => $id_tupoksi,
            'updated_at' => date('Y-m-d H:i:s'),
        );
        if ($this->input->post('password', false) !== '') {
            $data['password'] = password_hash($this->input->post('password', false), PASSWORD_BCRYPT);
        }
        if ($foto) $data['foto'] = $foto['name'];
        $this->m_user->update($id, $data);
        if ($foto) $this->m_user->save_foto($id, $foto['mime'], $foto['bytes']);
        $this->json(array('status' => true, 'text' => 'User berhasil diperbarui.', 'redirect' => site_url('users')));
    }

    public function reset($id)
    {
        $this->admin_gate();
        $u = $this->m_user->find_by_id($id);
        if (!$u) $this->json(array('status' => false, 'text' => 'User tidak ditemukan.'), 404);
        $this->form_validation->set_rules('password', 'Password Baru', 'required|min_length[6]');
        if ($this->form_validation->run() === false) {
            $this->json(array('status' => false, 'text' => strip_tags(validation_errors())));
        }
        $this->m_user->update($id, array('password' => password_hash($this->input->post('password', false), PASSWORD_BCRYPT), 'updated_at' => date('Y-m-d H:i:s')));
        $this->json(array('status' => true, 'text' => 'Password user berhasil direset.'));
    }

    public function hapus($id)
    {
        $this->admin_gate();
        $u = $this->m_user->find_by_id($id);
        if (!$u) $this->json(array('status' => false, 'text' => 'User tidak ditemukan.'), 404);
        if ((int)$id === (int)current_user('id_user')) {
            $this->json(array('status' => false, 'text' => 'Tidak dapat menghapus akun sendiri.'));
        }
        if ($u['akses'] === 'admin' && $this->m_user->count_admin() <= 1) {
            $this->json(array('status' => false, 'text' => 'Tidak dapat menghapus admin terakhir.'));
        }
        $this->m_user->delete($id);
        $this->json(array('status' => true, 'text' => 'User berhasil dihapus.'));
    }

    /** Sajikan foto user dari DB. Admin: semua; user biasa: hanya miliknya. */
    public function foto($id)
    {
        if (!logged_in()) return $this->bounce();
        if (!is_admin() && (int)$id !== (int)current_user('id_user')) {
            return $this->deny();
        }
        $f = $this->m_user->get_foto($id);
        if (!$f) {
            redirect(base_url('assets/dist/img/user-default.png'));
            return;
        }
        header('Content-Type: ' . $f['mime']);
        header('Content-Length: ' . strlen($f['isi']));
        header('Cache-Control: private, max-age=300');
        echo $f['isi'];
        exit;
    }
}
