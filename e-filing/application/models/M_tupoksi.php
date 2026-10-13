<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_tupoksi extends CI_Model
{
    private $table = 'tupoksi';

    public function all()
    {
        return $this->db->order_by('nama_tupoksi', 'asc')->get($this->table)->result_array();
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, array('id_tupoksi' => (int)$id))->row_array();
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    public function exists($nama, $except_id = null)
    {
        $this->db->where('nama_tupoksi', $nama);
        if ($except_id) $this->db->where('id_tupoksi !=', (int)$except_id);
        return $this->db->count_all_results($this->table) > 0;
    }

    public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id_tupoksi', (int)$id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id_tupoksi', (int)$id)->delete($this->table);
    }

    /** Berapa user/dokumen yang masih memakai tupoksi ini. */
    public function usage($id)
    {
        $u = (int)$this->db->where('id_tupoksi', (int)$id)->count_all_results('users');
        $d = (int)$this->db->where('id_tupoksi', (int)$id)->count_all_results('dokumen');
        return array('users' => $u, 'dokumen' => $d, 'total' => $u + $d);
    }

    // ---------------- DataTables ----------------
    private function dt_query($search)
    {
        $this->db->from($this->table);
        if (!empty($search)) $this->db->like('nama_tupoksi', $search);
    }

    public function dt_data($post)
    {
        $this->dt_query(isset($post['search']['value']) ? $post['search']['value'] : '');
        if (isset($post['order'][0]) && (int)$post['order'][0]['column'] === 1) {
            $this->db->order_by('nama_tupoksi', $post['order'][0]['dir'] === 'asc' ? 'asc' : 'desc');
        } else {
            $this->db->order_by('nama_tupoksi', 'asc');
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
