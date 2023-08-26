<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Peminjaman extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Peminjaman_model', 'peminjaman_model');
    }

    public function index()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('peminjaman/index');
        $this->load->view('templates/footer');
    }

    public function pinjam_buku()
    {
        // Mendapatkan data dari form peminjaman
        $data = array(
            'id_user' => $this->input->post('id_user'),
            'id_buku' => $this->input->post('id_buku'),
            'tanggal_peminjaman' => date('Y-m-d H:i:s'),
            'tanggal_pengembalian' => $this->input->post('tanggal_pengembalian'), // Sesuaikan dengan input di form
            'status' => 'Pinjam'
        );

        // Simpan data peminjaman ke dalam database
        $peminjaman_id = $this->peminjaman_model->create_peminjaman($data);

        redirect('dashboard/index');
    }

    public function kembalikan_buku($id_peminjaman)
    {
        $data = array(
            'status' => 'Kembali'
        );

        // Update status peminjaman
        $this->peminjaman_model->update_peminjaman($id_peminjaman, $data);
        $this->peminjaman_model->delete_peminjaman($id_peminjaman, $data);

        // Redirect to the list of peminjaman
        redirect('peminjaman/daftar'); // Assuming 'peminjaman' is the correct controller name
    }

    public function daftar()
    {
        // Mengambil semua data peminjaman dari model
        $data['data_peminjaman'] = $this->peminjaman_model->get_all_peminjaman();

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('peminjaman/daftar', $data);
        $this->load->view('templates/footer');
    }
}
