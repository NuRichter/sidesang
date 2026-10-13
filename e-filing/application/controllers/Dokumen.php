<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dokumen extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->guard();
        $this->load->model(array('M_dokumen' => 'm_dokumen', 'M_jenis' => 'm_jenis'));
    }

    private function access_filter()
    {
        // admin melihat semua; user hanya tupoksinya
        return is_admin() ? null : current_user('id_tupoksi');
    }

    public function index()
    {
        $this->render('dokumen/list', array(
            'title' => 'Data Dokumen',
            'jenis' => $this->m_jenis->all(),
        ));
    }

    public function data()
    {
        $post = $this->input->post(null, true);
        $filter = $this->access_filter();
        $extra = array(
            'id_jenis' => $this->input->post('f_jenis', true),
            'from' => $this->input->post('f_from', true),
            'to' => $this->input->post('f_to', true),
        );
        $rows = $this->m_dokumen->dt_data($post, $filter, $extra);
        $no = (int)$post['start'];
        $data = array();
        foreach ($rows as $r) {
            $no++;
            $id = $r['id_dokumen'];
            $file = '-';
            if (!empty($r['file_name'])) {
                $ikon = (strpos((string)$r['file_mime'], 'pdf') !== false) ? 'fa-file-pdf text-danger' : 'fa-file-image text-primary';
                $file = '<a href="' . site_url('dokumen/view/' . $id) . '" title="' . h($r['file_name']) . '"><i class="fa ' . $ikon . '"></i></a>';
            }
            $aksi = '<a href="' . site_url('dokumen/view/' . $id) . '" class="btn btn-sm btn-info" title="Lihat"><i class="fa fa-eye"></i></a> '
                . '<a href="' . site_url('dokumen/edit/' . $id) . '" class="btn btn-sm btn-success" title="Edit"><i class="fa fa-edit"></i></a> '
                . '<button class="btn btn-sm btn-danger btn-hapus" data-id="' . $id . '" title="Hapus"><i class="fa fa-trash"></i></button>';
            $data[] = array(
                '<div class="text-center">' . $no . '</div>',
                h($r['nomor_dokumen']),
                h($r['nama_dokumen']),
                h($r['catatan']),
                h($r['nama_jenis']),
                tgl_id($r['tanggal_surat']),
                tgl_id($r['updated_at']),
                h($r['nama_user']),
                h($r['nama_tupoksi']),
                '<div class="text-center">' . $file . '</div>',
                '<div class="text-center text-nowrap">' . $aksi . '</div>',
            );
        }
        $this->json(array(
            'draw' => (int)$post['draw'],
            'recordsTotal' => $this->m_dokumen->dt_count_all($filter),
            'recordsFiltered' => $this->m_dokumen->dt_count_filtered($post, $filter, $extra),
            'data' => $data,
        ));
    }

    public function tambah()
    {
        $this->render('dokumen/form', array(
            'title' => 'Tambah Dokumen',
            'mode' => 'tambah',
            'jenis' => $this->m_jenis->all(),
            'dok' => null,
        ));
    }

    public function edit($id)
    {
        $dok = $this->m_dokumen->get($id);
        if (!$dok) show_404();
        if (!$this->m_dokumen->can_access($dok, current_user())) return $this->deny();
        $this->render('dokumen/form', array(
            'title' => 'Edit Dokumen',
            'mode' => 'edit',
            'jenis' => $this->m_jenis->all(),
            'dok' => $dok,
        ));
    }

    private function validasi()
    {
        $this->form_validation->set_rules('nomor_dokumen', 'Nomor Dokumen', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('id_jenis', 'Jenis Surat', 'required|integer');
        $this->form_validation->set_rules('nama_dokumen', 'Nama Dokumen', 'required|trim|max_length[200]');
        $this->form_validation->set_rules('tanggal_dokumen', 'Tanggal Dokumen', 'required');
        return $this->form_validation->run();
    }

    private function parse_tgl($v)
    {
        $v = trim((string)$v);
        if (preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $v, $m)) return $m[3] . '-' . $m[2] . '-' . $m[1];
        if (preg_match('#^\d{4}-\d{2}-\d{2}$#', $v)) return $v;
        return null;
    }

    /** Upload berkas ke temp lalu kembalikan info + bytes. NULL bila tidak ada file. */
    private function ambil_berkas(&$err)
    {
        $err = null;
        if (empty($_FILES['file']['name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) return null;

        $dir = rtrim(str_replace('\\', '/', sys_get_temp_dir()), '/') . '/earsip_upload';
        if (!is_dir($dir)) @mkdir($dir, 0777, true);
        $cfg = array(
            'upload_path' => $dir,
            'allowed_types' => 'pdf|jpg|jpeg|png',
            'max_size' => 4000, // KB (batas body Vercel ~4,5MB)
            'encrypt_name' => true,
        );
        $this->load->library('upload', $cfg);
        if (!$this->upload->do_upload('file')) {
            $err = strip_tags($this->upload->display_errors('', ''));
            return null;
        }
        $d = $this->upload->data();
        // validasi MIME sebenarnya
        $allowed_mime = array('application/pdf', 'image/jpeg', 'image/png');
        $mime = $d['file_type'];
        if (function_exists('finfo_open')) {
            $f = finfo_open(FILEINFO_MIME_TYPE);
            $real = finfo_file($f, $d['full_path']);
            finfo_close($f);
            if ($real) $mime = $real;
        }
        if (!in_array($mime, $allowed_mime, true)) {
            @unlink($d['full_path']);
            $err = 'Tipe berkas tidak diizinkan.';
            return null;
        }
        $bytes = file_get_contents($d['full_path']);
        @unlink($d['full_path']);
        // nama bersih untuk ditampilkan/diunduh (gunakan nama asli yang dibersihkan)
        $safe = preg_replace('/[^A-Za-z0-9._-]/', '_', $_FILES['file']['name']);
        return array('file_name' => $safe, 'mime' => $mime, 'size' => strlen($bytes), 'bytes' => $bytes);
    }

    public function simpan()
    {
        if ($this->validasi() === false) {
            $this->json(array('status' => false, 'text' => strip_tags(validation_errors())));
        }
        $tgl = $this->parse_tgl($this->input->post('tanggal_dokumen', true));
        if (!$tgl) $this->json(array('status' => false, 'text' => 'Format tanggal tidak valid.'));

        $berkas = $this->ambil_berkas($err);
        if ($err) $this->json(array('status' => false, 'text' => $err));

        $data = array(
            'nomor_dokumen' => $this->input->post('nomor_dokumen', true),
            'nama_dokumen' => $this->input->post('nama_dokumen', true),
            'catatan' => $this->input->post('catatan', true) ?: null,
            'deskripsi' => $this->input->post('deskripsi', true) ?: null,
            'id_jenis' => (int)$this->input->post('id_jenis', true),
            'tanggal_surat' => $tgl,
            'id_user' => (int)current_user('id_user'),
            'id_tupoksi' => current_user('id_tupoksi') ? (int)current_user('id_tupoksi') : null,
        );
        if ($berkas) {
            $data['file_name'] = $berkas['file_name'];
            $data['file_mime'] = $berkas['mime'];
            $data['file_size'] = $berkas['size'];
            $data['path_folder'] = 'db';
        }
        $id = $this->m_dokumen->create($data);
        if ($berkas) $this->m_dokumen->save_berkas($id, $berkas['mime'], $berkas['size'], $berkas['bytes']);

        $this->json(array('status' => true, 'text' => 'Dokumen berhasil disimpan.', 'redirect' => site_url('dokumen')));
    }

    public function update()
    {
        $id = (int)$this->input->post('id_dokumen', true);
        $dok = $this->m_dokumen->get($id);
        if (!$dok) $this->json(array('status' => false, 'text' => 'Dokumen tidak ditemukan.'), 404);
        if (!$this->m_dokumen->can_access($dok, current_user())) return $this->deny();
        if ($this->validasi() === false) {
            $this->json(array('status' => false, 'text' => strip_tags(validation_errors())));
        }
        $tgl = $this->parse_tgl($this->input->post('tanggal_dokumen', true));
        if (!$tgl) $this->json(array('status' => false, 'text' => 'Format tanggal tidak valid.'));

        $berkas = $this->ambil_berkas($err);
        if ($err) $this->json(array('status' => false, 'text' => $err));

        $data = array(
            'nomor_dokumen' => $this->input->post('nomor_dokumen', true),
            'nama_dokumen' => $this->input->post('nama_dokumen', true),
            'catatan' => $this->input->post('catatan', true) ?: null,
            'deskripsi' => $this->input->post('deskripsi', true) ?: null,
            'id_jenis' => (int)$this->input->post('id_jenis', true),
            'tanggal_surat' => $tgl,
            'updated_at' => date('Y-m-d H:i:s'),
        );
        if ($berkas) {
            $data['file_name'] = $berkas['file_name'];
            $data['file_mime'] = $berkas['mime'];
            $data['file_size'] = $berkas['size'];
            $data['path_folder'] = 'db';
        }
        $this->m_dokumen->update($id, $data);
        if ($berkas) $this->m_dokumen->save_berkas($id, $berkas['mime'], $berkas['size'], $berkas['bytes']);

        $this->json(array('status' => true, 'text' => 'Dokumen berhasil diperbarui.', 'redirect' => site_url('dokumen')));
    }

    public function view($id)
    {
        $dok = $this->m_dokumen->get($id);
        if (!$dok) show_404();
        if (!$this->m_dokumen->can_access($dok, current_user())) return $this->deny();
        $this->render('dokumen/view', array('title' => 'View Dokumen', 'dok' => $dok));
    }

    private function kirim_berkas($id, $inline)
    {
        $dok = $this->m_dokumen->get($id);
        if (!$dok) show_404();
        if (!$this->m_dokumen->can_access($dok, current_user())) return $this->deny();
        $b = $this->m_dokumen->get_berkas($id);
        if (!$b) show_404();
        $disp = $inline ? 'inline' : 'attachment';
        $nama = $dok['file_name'] ?: ('dokumen-' . $id);
        header('Content-Type: ' . $b['mime']);
        header('Content-Length: ' . strlen($b['isi']));
        header('Content-Disposition: ' . $disp . '; filename="' . $nama . '"');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, max-age=0, no-store');
        echo $b['isi'];
        exit;
    }

    public function preview($id)
    {
        $this->kirim_berkas($id, true);
    }

    public function download($id)
    {
        $this->kirim_berkas($id, false);
    }

    public function hapus($id)
    {
        $dok = $this->m_dokumen->get($id);
        if (!$dok) $this->json(array('status' => false, 'text' => 'Dokumen tidak ditemukan.'), 404);
        if (!$this->m_dokumen->can_access($dok, current_user())) return $this->deny();
        $this->m_dokumen->delete($id);
        $this->json(array('status' => true, 'text' => 'Dokumen berhasil dihapus.'));
    }
}
