<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tupoksi extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->guard();
        $this->load->model('M_tupoksi', 'm_tupoksi');
    }

    public function index()
    {
        $this->render('tupoksi', array('title' => 'Tupoksi', 'plugins' => array('datatables'), 'rows' => $this->m_tupoksi->all()));
    }

    public function data()
    {
        $post = $this->input->post(null, true);
        $rows = $this->m_tupoksi->dt_data($post);
        $no = (int)$post['start'];
        $data = array();
        foreach ($rows as $r) {
            $no++;
            $aksi = '<button class="btn btn-sm btn-success btn-edit" data-id="' . $r['id_tupoksi'] . '"><i class="fa fa-edit"></i> Edit</button> '
                . '<button class="btn btn-sm btn-danger btn-hapus" data-id="' . $r['id_tupoksi'] . '"><i class="fa fa-trash"></i> Delete</button>';
            $data[] = array(
                '<div class="text-center">' . $no . '</div>',
                h($r['nama_tupoksi']),
                '<div class="text-center">' . $aksi . '</div>',
            );
        }
        $this->json(array(
            'draw' => (int)$post['draw'],
            'recordsTotal' => $this->m_tupoksi->dt_count_all(),
            'recordsFiltered' => $this->m_tupoksi->dt_count_filtered($post),
            'data' => $data,
        ));
    }

    public function get($id)
    {
        $row = $this->m_tupoksi->get($id);
        if (!$row) $this->json(array('status' => false), 404);
        $this->json(array('status' => true, 'data' => $row));
    }

    public function simpan()
    {
        $id = (int)$this->input->post('id_tupoksi', true);
        $this->form_validation->set_rules('nama_tupoksi', 'Nama Tupoksi', 'required|trim|max_length[100]');
        if ($this->form_validation->run() === false) {
            $this->json(array('status' => false, 'text' => strip_tags(validation_errors())));
        }
        $nama = $this->input->post('nama_tupoksi', true);
        if ($this->m_tupoksi->exists($nama, $id ?: null)) {
            $this->json(array('status' => false, 'text' => 'Tupoksi "' . h($nama) . '" sudah ada.'));
        }
        $payload = array('nama_tupoksi' => $nama);
        if ($id) {
            $this->m_tupoksi->update($id, $payload);
            $this->json(array('status' => true, 'text' => 'Tupoksi berhasil diperbarui.'));
        }
        $this->m_tupoksi->create($payload);
        $this->json(array('status' => true, 'text' => 'Tupoksi berhasil ditambahkan.'));
    }

    public function hapus($id)
    {
        $row = $this->m_tupoksi->get($id);
        if (!$row) $this->json(array('status' => false, 'text' => 'Data tidak ditemukan.'), 404);
        $use = $this->m_tupoksi->usage($id);
        if ($use['total'] > 0) {
            $this->json(array('status' => false, 'text' => 'Tidak dapat menghapus: tupoksi masih dipakai ' . $use['users'] . ' user dan ' . $use['dokumen'] . ' dokumen.'));
        }
        $this->m_tupoksi->delete($id);
        $this->json(array('status' => true, 'text' => 'Tupoksi berhasil dihapus.'));
    }
}
