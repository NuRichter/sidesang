<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_user extends CI_Model
{
    private $table = 'users';

    public function find_by_email($email)
    {
        return $this->db->get_where($this->table, array('email' => $email))->row_array();
    }

    public function find_by_id($id)
    {
        return $this->db->get_where($this->table, array('id_user' => (int)$id))->row_array();
    }

    /** Data ringan untuk verifikasi auth tiap request (tanpa kolom password/BLOB). */
    public function find_auth($id)
    {
        return $this->db
            ->select('u.id_user, u.nama, u.email, u.akses, u.id_tupoksi, u.foto, t.nama_tupoksi')
            ->from($this->table . ' u')
            ->join('tupoksi t', 't.id_tupoksi = u.id_tupoksi', 'left')
            ->where('u.id_user', (int)$id)
            ->get()->row_array();
    }

    /** Verifikasi kredensial. Return row user bila cocok, FALSE bila tidak. */
    public function verify($email, $password)
    {
        $u = $this->find_by_email($email);
        if (!$u) return false;
        if (!password_verify($password, $u['password'])) return false;
        return $u;
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    public function count_admin()
    {
        return (int)$this->db->where('akses', 'admin')->count_all_results($this->table);
    }

    public function email_exists($email, $except_id = null)
    {
        $this->db->where('email', $email);
        if ($except_id) $this->db->where('id_user !=', (int)$except_id);
        return $this->db->count_all_results($this->table) > 0;
    }

    public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id_user', (int)$id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id_user', (int)$id)->delete($this->table);
    }

    /** Semua user + nama tupoksi (untuk tabel client-side, tanpa password/BLOB). */
    public function all_with_tupoksi()
    {
        return $this->db
            ->select('u.id_user, u.nama, u.email, u.akses, u.foto, t.nama_tupoksi')
            ->from($this->table . ' u')
            ->join('tupoksi t', 't.id_tupoksi = u.id_tupoksi', 'left')
            ->order_by('u.nama', 'asc')
            ->get()->result_array();
    }

    // ---------------- foto (disimpan di DB) ----------------
    public function get_foto($id)
    {
        return $this->db->get_where('user_foto', array('id_user' => (int)$id))->row_array();
    }

    public function save_foto($id, $mime, $bytes)
    {
        $exist = $this->get_foto($id);
        if ($exist) {
            $this->db->where('id_user', (int)$id)->update('user_foto', array('mime' => $mime, 'isi' => $bytes));
        } else {
            $this->db->insert('user_foto', array('id_user' => (int)$id, 'mime' => $mime, 'isi' => $bytes));
        }
    }

    // ---------------- DataTables (Data User) ----------------
    private $dt_cols = array('u.nama', 'u.email', 'u.akses', 't.nama_tupoksi');

    private function dt_query($search)
    {
        $this->db->from($this->table . ' u')
            ->join('tupoksi t', 't.id_tupoksi = u.id_tupoksi', 'left');
        if (!empty($search)) {
            $this->db->group_start();
            foreach ($this->dt_cols as $i => $c) {
                $i === 0 ? $this->db->like($c, $search) : $this->db->or_like($c, $search);
            }
            $this->db->group_end();
        }
    }

    public function dt_data($post)
    {
        $this->db->select('u.id_user, u.nama, u.email, u.akses, u.foto, t.nama_tupoksi');
        $this->dt_query(isset($post['search']['value']) ? $post['search']['value'] : '');
        if (isset($post['order'][0])) {
            $idx = (int)$post['order'][0]['column'];
            $map = array(1 => 'u.nama', 2 => 'u.email', 4 => 'u.akses', 5 => 't.nama_tupoksi');
            if (isset($map[$idx])) $this->db->order_by($map[$idx], $post['order'][0]['dir'] === 'asc' ? 'asc' : 'desc');
        } else {
            $this->db->order_by('u.nama', 'asc');
        }
        if (isset($post['length']) && (int)$post['length'] > -1) {
            $this->db->limit((int)$post['length'], (int)$post['start']);
        }
        return $this->db->get()->result_array();
    }

    public function dt_count_filtered($post)
    {
        $this->dt_query(isset($post['search']['value']) ? $post['search']['value'] : '');
        return $this->db->count_all_results();
    }

    public function dt_count_all()
    {
        return $this->db->count_all($this->table);
    }
}
