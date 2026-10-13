<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Beranda extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->guard();
        $this->load->model(array('M_dokumen' => 'm_dokumen', 'M_jenis' => 'm_jenis', 'M_tupoksi' => 'm_tupoksi'));
    }

    public function index()
    {
        $filter = is_admin() ? null : current_user('id_tupoksi');
        $data = array(
            'title'        => 'Beranda',
            'total_surat'  => $this->m_dokumen->count_access($filter),
            'total_jenis'  => $this->m_jenis->count_all(),
            'total_tupoksi' => $this->m_tupoksi->count_all(),
            'total_user'   => $this->m_user->count_all(),
        );
        $this->render('beranda', $data);
    }
}
