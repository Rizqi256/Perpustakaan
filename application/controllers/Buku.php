<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Buku extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Buku_model');
        $this->load->model('Kategori_buku_model');
    }

    public function index()
    {
        $data['data_buku'] = $this->Buku_model->get_all_buku();
        $data['data_kategori_buku'] = $this->Kategori_buku_model->get_all_kategori_buku();

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
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

    public function delete($id_buku)
    {
        $this->Buku_model->delete_buku($id_buku);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus</div>');
        redirect('buku/index');
    }
}
