<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_jenis extends CI_Model
{
    private $table = 'jenis_surat';

    public function all()
    {
        return $this->db->order_by('nama_jenis', 'asc')->get($this->table)->result_array();
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, array('id_jenis' => (int)$id))->row_array();
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    public function exists($nama, $except_id = null)
    {
        $this->db->where('nama_jenis', $nama);
        if ($except_id) $this->db->where('id_jenis !=', (int)$except_id);
        return $this->db->count_all_results($this->table) > 0;
    }

    public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id_jenis', (int)$id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id_jenis', (int)$id)->delete($this->table);
    }

    public function usage($id)
    {
        return (int)$this->db->where('id_jenis', (int)$id)->count_all_results('dokumen');
    }

    // ---------------- DataTables ----------------
    private $dt_cols = array('nama_jenis', 'keterangan');

    private function dt_query($search)
    {
        $this->db->from($this->table);
        if (!empty($search)) {
            $this->db->group_start()->like('nama_jenis', $search)->or_like('keterangan', $search)->group_end();
        }
    }

    public function dt_data($post)
    {
        $this->dt_query(isset($post['search']['value']) ? $post['search']['value'] : '');
        $map = array(1 => 'nama_jenis', 2 => 'keterangan');
        if (isset($post['order'][0]) && isset($map[(int)$post['order'][0]['column']])) {
            $this->db->order_by($map[(int)$post['order'][0]['column']], $post['order'][0]['dir'] === 'asc' ? 'asc' : 'desc');
        } else {
            $this->db->order_by('nama_jenis', 'asc');
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
