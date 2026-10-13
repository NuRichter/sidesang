<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jenis_surat extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->guard();
        $this->load->model('M_jenis', 'm_jenis');
    }

    public function index()
    {
        $this->render('jenis_surat', array('title' => 'Jenis Surat'));
    }

    public function data()
    {
        $post = $this->input->post(null, true);
        $rows = $this->m_jenis->dt_data($post);
        $no = (int)$post['start'];
        $data = array();
        foreach ($rows as $r) {
            $no++;
            $aksi = '<button class="btn btn-sm btn-success btn-edit" data-id="' . $r['id_jenis'] . '"><i class="fa fa-edit"></i> Edit</button> '
                . '<button class="btn btn-sm btn-danger btn-hapus" data-id="' . $r['id_jenis'] . '"><i class="fa fa-trash"></i> Delete</button>';
            $data[] = array(
                '<div class="text-center">' . $no . '</div>',
                h($r['nama_jenis']),
                h($r['keterangan']),
                '<div class="text-center">' . $aksi . '</div>',
            );
        }
        $this->json(array(
            'draw' => (int)$post['draw'],
            'recordsTotal' => $this->m_jenis->dt_count_all(),
            'recordsFiltered' => $this->m_jenis->dt_count_filtered($post),
            'data' => $data,
        ));
    }

    public function get($id)
    {
        $row = $this->m_jenis->get($id);
        if (!$row) $this->json(array('status' => false), 404);
        $this->json(array('status' => true, 'data' => $row));
    }

    public function simpan()
    {
        $id = (int)$this->input->post('id_jenis', true);
        $this->form_validation->set_rules('nama_jenis', 'Nama Jenis Surat', 'required|trim|max_length[100]');
        if ($this->form_validation->run() === false) {
            $this->json(array('status' => false, 'text' => strip_tags(validation_errors())));
        }
        $nama = $this->input->post('nama_jenis', true);
        if ($this->m_jenis->exists($nama, $id ?: null)) {
            $this->json(array('status' => false, 'text' => 'Jenis surat "' . h($nama) . '" sudah ada.'));
        }
        $payload = array('nama_jenis' => $nama, 'keterangan' => $this->input->post('keterangan', true) ?: null);
        if ($id) {
            $this->m_jenis->update($id, $payload);
            $this->json(array('status' => true, 'text' => 'Jenis surat berhasil diperbarui.'));
        }
        $this->m_jenis->create($payload);
        $this->json(array('status' => true, 'text' => 'Jenis surat berhasil ditambahkan.'));
    }

    public function hapus($id)
    {
        $row = $this->m_jenis->get($id);
        if (!$row) $this->json(array('status' => false, 'text' => 'Data tidak ditemukan.'), 404);
        if ($this->m_jenis->usage($id) > 0) {
            $this->json(array('status' => false, 'text' => 'Tidak dapat menghapus: jenis surat masih digunakan oleh dokumen.'));
        }
        $this->m_jenis->delete($id);
        $this->json(array('status' => true, 'text' => 'Jenis surat berhasil dihapus.'));
    }
}
