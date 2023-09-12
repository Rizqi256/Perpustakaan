<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Peminjaman extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Peminjaman_model', 'peminjaman_model');
        $this->load->model('Buku_model', 'buku_model');
    }

    public function index()
    {
        $data['data_users'] = $this->peminjaman_model->get_all_users();
        $data['data_buku'] = $this->peminjaman_model->get_all_buku(); // Mendapatkan data buku dari model

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('peminjaman/index', $data); // Passing the user data to the view
        $this->load->view('templates/footer');
    }


    public function pinjam_buku()
    {
        $id_user = $this->input->post('id_user'); // Change 'nama' to 'id_user'
        $id_buku_array = $this->input->post('id_buku');

        $tanggal_peminjaman = date('Y-m-d');
        $tanggal_pengembalian = date('Y-m-d', strtotime('+7 days', strtotime($tanggal_peminjaman)));

        // Create the peminjaman entry
        $peminjaman_data = array(
            'id_user' => $id_user,
            'tanggal_peminjaman' => $tanggal_peminjaman,
            'tanggal_pengembalian' => $tanggal_pengembalian,
            'status' => 'Pinjam'
        );

        $peminjaman_id = $this->peminjaman_model->create_peminjaman($peminjaman_data);

        foreach ($id_buku_array as $id_buku) {
            // Create the detail entry using the peminjaman ID
            $data_detail = array(
                'id_peminjaman' => $peminjaman_id,
                'id_buku' => $id_buku
            );
            $this->peminjaman_model->create_peminjaman_detail($data_detail);

            // Update status buku
            $this->peminjaman_model->update_buku_status($id_buku, 'Pinjam');
        }

        redirect('peminjaman/daftar');
    }

    public function kembalikan_buku($id_peminjaman)
    {
        $this->peminjaman_model->kembalikan_buku($id_peminjaman);
        redirect('peminjaman/daftar');
    }

    public function daftar()
    {
        $status = $this->input->get('status'); // Mendapatkan status dari permintaan filter

        $data_peminjaman = $this->peminjaman_model->get_all_peminjaman();


        if ($status === '') {
            // Tampilkan semua data
            $data_peminjaman = $this->peminjaman_model->get_all_peminjaman();
        } else {
            // Lakukan filter berdasarkan status
            $data_peminjaman;
        }

        // Filter data berdasarkan status yang dipilih
        if ($status === 'Pinjam') {
            $data_peminjaman = array_filter($data_peminjaman, function ($peminjaman) {
                return $peminjaman->status === 'Pinjam';
            });
        } elseif ($status === 'Kembali') {
            $data_peminjaman = array_filter($data_peminjaman, function ($peminjaman) {
                return $peminjaman->status === 'Kembali';
            });
        }

        foreach ($data_peminjaman as &$peminjaman) {
            $user = $this->peminjaman_model->get_user_by_id($peminjaman->id_user);
            $peminjaman->user_name = $user->nama;

            $detail_peminjaman = $this->peminjaman_model->get_detail_peminjaman_by_id($peminjaman->id_peminjaman);

            $buku_names = [];
            foreach ($detail_peminjaman as $detail) {
                $buku = $this->peminjaman_model->get_buku_by_id($detail->id_buku);
                $buku_names[] = $buku->nama_buku;
            }

            $peminjaman->nama_buku = implode(', ', $buku_names);
        }

        $data['data_peminjaman'] = $data_peminjaman;

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('peminjaman/daftar', $data);
        $this->load->view('templates/footer');
    }

    public function hapus_peminjaman($id_peminjaman)
    {
        $peminjaman = $this->peminjaman_model->get_peminjaman_by_id($id_peminjaman);

        if ($peminjaman->status === 'Kembali') {
            // Hapus data peminjaman jika statusnya "Kembali"
            $this->peminjaman_model->delete_peminjaman($id_peminjaman);
            redirect('peminjaman/daftar');
        } else {
            redirect('peminjaman/daftar');
        }
    }
}
