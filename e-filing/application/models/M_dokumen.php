<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_dokumen extends CI_Model
{
    private $table = 'dokumen';

    /** Ambil satu dokumen beserta relasi (tanpa isi berkas). */
    public function get($id)
    {
        return $this->db
            ->select('d.*, j.nama_jenis, t.nama_tupoksi, u.nama AS nama_user')
            ->from($this->table . ' d')
            ->join('jenis_surat j', 'j.id_jenis = d.id_jenis', 'left')
            ->join('tupoksi t', 't.id_tupoksi = d.id_tupoksi', 'left')
            ->join('users u', 'u.id_user = d.id_user', 'left')
            ->where('d.id_dokumen', (int)$id)
            ->get()->row_array();
    }

    /**
     * Apakah $user boleh mengakses dokumen (baca/preview/download/dll).
     * Admin: semua. User: hanya dokumen dengan tupoksi yang sama.
     */
    public function can_access($dok, $user)
    {
        if (empty($dok)) return false;
        if ($user['akses'] === 'admin') return true;
        // user tanpa tupoksi tidak boleh mengakses dokumen apa pun
        if (empty($user['id_tupoksi']) || empty($dok['id_tupoksi'])) return false;
        return (int)$dok['id_tupoksi'] === (int)$user['id_tupoksi'];
    }

    public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id_dokumen', (int)$id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id_dokumen', (int)$id)->delete($this->table); // berkas ikut terhapus (FK cascade)
    }

    /** Total dokumen sesuai hak akses (untuk kartu Beranda). */
    public function count_access($tupoksi_filter = null)
    {
        if ($tupoksi_filter !== null) $this->db->where('id_tupoksi', (int)$tupoksi_filter);
        return $this->db->count_all_results($this->table);
    }

    // ---------------- berkas (disimpan di DB) ----------------
    public function get_berkas($id_dokumen)
    {
        return $this->db->get_where('dokumen_berkas', array('id_dokumen' => (int)$id_dokumen))->row_array();
    }

    public function save_berkas($id_dokumen, $mime, $size, $bytes)
    {
        $exist = $this->get_berkas($id_dokumen);
        $data = array('mime' => $mime, 'ukuran' => $size, 'isi' => $bytes);
        if ($exist) {
            $this->db->where('id_dokumen', (int)$id_dokumen)->update('dokumen_berkas', $data);
        } else {
            $data['id_dokumen'] = (int)$id_dokumen;
            $this->db->insert('dokumen_berkas', $data);
        }
    }

    // ---------------- DataTables (dengan filter akses) ----------------
    private $dt_cols = array('d.nomor_dokumen', 'd.nama_dokumen', 'd.catatan', 'j.nama_jenis', 'u.nama', 't.nama_tupoksi');

    private function dt_query($search, $tupoksi_filter, $extra = array())
    {
        $this->db->from($this->table . ' d')
            ->join('jenis_surat j', 'j.id_jenis = d.id_jenis', 'left')
            ->join('tupoksi t', 't.id_tupoksi = d.id_tupoksi', 'left')
            ->join('users u', 'u.id_user = d.id_user', 'left');
        if ($tupoksi_filter !== null) $this->db->where('d.id_tupoksi', (int)$tupoksi_filter);
        if (!empty($extra['id_jenis'])) $this->db->where('d.id_jenis', (int)$extra['id_jenis']);
        if (!empty($extra['from'])) $this->db->where('d.tanggal_surat >=', $extra['from']);
        if (!empty($extra['to'])) $this->db->where('d.tanggal_surat <=', $extra['to']);
        if (!empty($search)) {
            $this->db->group_start();
            foreach ($this->dt_cols as $i => $c) {
                $i === 0 ? $this->db->like($c, $search) : $this->db->or_like($c, $search);
            }
            $this->db->group_end();
        }
    }

    public function dt_data($post, $tupoksi_filter = null, $extra = array())
    {
        $this->db->select('d.id_dokumen, d.nomor_dokumen, d.nama_dokumen, d.catatan, d.tanggal_surat, d.updated_at, d.file_name, d.file_mime, j.nama_jenis, u.nama AS nama_user, t.nama_tupoksi');
        $this->dt_query(isset($post['search']['value']) ? $post['search']['value'] : '', $tupoksi_filter, $extra);
        $map = array(1 => 'd.nomor_dokumen', 2 => 'd.nama_dokumen', 3 => 'd.catatan', 4 => 'j.nama_jenis', 5 => 'd.tanggal_surat', 6 => 'd.updated_at', 7 => 'u.nama', 8 => 't.nama_tupoksi');
        if (isset($post['order'][0]) && isset($map[(int)$post['order'][0]['column']])) {
            $this->db->order_by($map[(int)$post['order'][0]['column']], $post['order'][0]['dir'] === 'asc' ? 'asc' : 'desc');
        } else {
            $this->db->order_by('d.created_at', 'desc');
        }
        if (isset($post['length']) && (int)$post['length'] > -1) {
            $this->db->limit((int)$post['length'], (int)$post['start']);
        }
        return $this->db->get()->result_array();
    }

    public function dt_count_filtered($post, $tupoksi_filter = null, $extra = array())
    {
        $this->dt_query(isset($post['search']['value']) ? $post['search']['value'] : '', $tupoksi_filter, $extra);
        return $this->db->count_all_results();
    }

    public function dt_count_all($tupoksi_filter = null)
    {
        if ($tupoksi_filter !== null) $this->db->where('id_tupoksi', (int)$tupoksi_filter);
        return $this->db->count_all_results($this->table);
    }
}
