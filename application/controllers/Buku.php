<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Buku extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Buku_model');
        $this->load->model('Kategori_buku_model');
        $this->load->library('pagination');
    }

    public function index()
    {
        $config = array();
        $config["base_url"] = base_url() . "buku/index";
        $config["total_rows"] = $this->Buku_model->get_total_records();
        $config["per_page"] = 2;
        $config["uri_segment"] = 3;
        $config['use_page_numbers'] = TRUE;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1; // Mendapatkan nomor halaman dari URI

        $data['data_buku'] = $this->Buku_model->get_buku_paginated($config["per_page"], $page);

        // Load view dengan data paginasi
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('buku/index', $data);
        $this->load->view('templates/footer');
    }

    public function search()
    {
        $keyword = $this->input->post('keyword'); // Ambil kata kunci dari form pencarian

        // Panggil model atau method di model Anda untuk melakukan pencarian
        $data['data_buku'] = $this->Buku_model->search_buku($keyword);

        // Set variabel $searched menjadi true untuk menampilkan tombol "Back"
        $data['searched'] = true;

        // Load view yang menampilkan hasil pencarian
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('buku/index', $data);
        $this->load->view('templates/footer');
    }


    public function create()
    {
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $judulBuku = $this->input->post("judul_buku");
            $idKategoriBuku = $this->input->post("id_kategori_buku");

            // Validasi input
            if (empty($judulBuku) || empty($idKategoriBuku)) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Isi semua data</div>');
                redirect('buku/index');
            } else {
                // Insert data ke dalam tabel buku
                $data = array(
                    'id_kategori_buku' => $idKategoriBuku,
                    'nama_buku' => $judulBuku
                );
                $this->Buku_model->insert_buku($data);

                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan</div>');
                redirect('buku/index');
            }
        }
    }

    public function edit($id_buku)
    {
        $data['data_buku'] = $this->Buku_model->get_buku_by_id($id_buku);
        $data['data_kategori_buku'] = $this->Kategori_buku_model->get_all_kategori_buku();

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('buku/edit', $data);
        $this->load->view('templates/footer');
    }

    public function update($id_buku)
    {
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $judulBuku = $this->input->post("judul_buku");
            $idKategoriBuku = $this->input->post("id_kategori_buku");

            // Validasi input
            if (empty($judulBuku) || empty($idKategoriBuku)) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Isi semua data</div>');
                redirect('buku/index');
            } else {
                // Update data di tabel buku
                $data = array(
                    'id_kategori_buku' => $idKategoriBuku,
                    'nama_buku' => $judulBuku
                );
                $this->Buku_model->update_buku($id_buku, $data);

                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diperbarui</div>');
                redirect('buku/index');
            }
        }
    }

    public function filter()
    {
        // Dapatkan ID kategori yang dipilih dari form
        $id_kategori_buku = $this->input->get("id_kategori_buku");

        // Dapatkan semua buku dengan ID kategori yang dipilih
        $data['data_buku'] = $this->Buku_model->get_buku_by_category($id_kategori_buku);

        // Dapatkan semua kategori untuk dropdown filter
        $data['data_kategori_buku'] = $this->Kategori_buku_model->get_all_kategori_buku();

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('buku/index', $data);
        $this->load->view('templates/footer');
    }



    public function delete($id_buku)
    {
        $this->Buku_model->delete_buku($id_buku);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus</div>');
        redirect('buku/index');
    }
}
