<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Peminjaman extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Peminjaman_model', 'peminjaman_model');
        $this->load->model('Buku_model', 'buku_model');
        $this->load->model('My_model');
        $this->load->library('pagination');
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
        $config = array();
        $config["base_url"] = base_url() . "peminjaman/daftar"; // Sesuaikan dengan URL Anda
        $config["total_rows"] = $this->My_model->count_all_records('peminjaman'); // Ganti dengan metode yang sesuai di model Anda
        $config["per_page"] = 2; // Sesuaikan dengan jumlah item per halaman yang diinginkan
        $config["uri_segment"] = 3;
        $config['use_page_numbers'] = TRUE;

        // Inisialisasi pagination
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

        $data_peminjaman = $this->My_model->get_paginated_data('peminjaman', $config["per_page"], ($page - 1) * $config["per_page"]); // Ganti 'kategori_buku' dengan nama tabel yang sesuai

        $data['data_peminjaman'] = $data_peminjaman;

        // Filter data berdasarkan status yang dipilih
        $status = $this->input->get('status');
        if (!empty($status)) {
            $data_peminjaman = array_filter($data_peminjaman, function ($peminjaman) use ($status) {
                return $peminjaman->status === $status;
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
        $this->load->view('peminjaman/daftar', array('data_peminjaman' => $data['data_peminjaman'], 'pagination_links' => $this->pagination->create_links()));
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

    public function hapus_peminjaman_multiple()
    {
        $selected_items = $this->input->post('selected_items');

        if (!empty($selected_items)) {
            foreach ($selected_items as $id_peminjaman) {
                $peminjaman = $this->peminjaman_model->get_peminjaman_by_id($id_peminjaman);

                if ($peminjaman->status !== 'Pinjam') {
                    $this->peminjaman_model->delete_peminjaman($id_peminjaman);
                }
            }
        }

        redirect('peminjaman/daftar');
    }
}
