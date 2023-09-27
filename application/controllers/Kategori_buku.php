<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori_buku extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Buku_model');
        $this->load->model('Kategori_buku_model');
        $this->load->model('My_model');
        $this->load->library('pagination');
    }

    public function index()
    {
        $config = array();
        $config["base_url"] = base_url() . "kategori_buku/index";
        $config["total_rows"] = $this->My_model->count_all_records('kategori_buku'); // Ganti 'kategori_buku' dengan nama tabel yang sesuai
        $config["per_page"] = 2; // Sesuaikan dengan jumlah item per halaman yang diinginkan
        $config["uri_segment"] = 3;
        $config['use_page_numbers'] = TRUE;

        $pagination_links = $this->pagination->initialize($config);;

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

        $data['data_kategori_buku'] = $this->My_model->get_paginated_data('kategori_buku', $config["per_page"], ($page - 1) * $config["per_page"]); // Ganti 'kategori_buku' dengan nama tabel yang sesuai

        // Load view dengan data paginasi
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('kategori_buku/index', array('data_kategori_buku' => $data['data_kategori_buku'], 'pagination_links' => $pagination_links));
        $this->load->view('templates/footer');
    }

    public function create()
    {
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $namaKategori = $this->input->post("nama_kategori");

            // Validasi input
            if (empty($namaKategori)) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Isi semua data</div>');
                redirect('kategori_buku/index');
            } else {
                // Insert data ke dalam tabel kategori_buku
                $data = array(
                    'nama_kategori' => $namaKategori
                );
                $this->Kategori_buku_model->insert_kategori_buku($data);

                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan</div>');
                redirect('kategori_buku/index');
            }
        }
    }

    public function edit($id_kategori_buku)
    {
        $data['data_kategori_buku'] = $this->Kategori_buku_model->get_kategori_buku_by_id($id_kategori_buku);

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('kategori_buku/edit', $data);
        $this->load->view('templates/footer');
    }

    public function update($id_kategori_buku)
    {
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $namaKategori = $this->input->post("nama_kategori");

            // Validasi input
            if (empty($namaKategori)) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Isi semua data</div>');
                redirect('kategori_buku/index');
            } else {
                // Update data di tabel kategori_buku
                $data = array(
                    'nama_kategori' => $namaKategori
                );
                $this->Kategori_buku_model->update_kategori_buku($id_kategori_buku, $data);

                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diperbarui</div>');
                redirect('kategori_buku/index');
            }
        }
    }

    public function delete($id_kategori_buku)
    {
        $data['data_kategori_buku'] = $this->Kategori_buku_model->get_kategori_buku_by_id($id_kategori_buku);

        $this->Kategori_buku_model->delete_kategori_buku($id_kategori_buku);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus</div>');
        redirect('kategori_buku/index');
    }
}
