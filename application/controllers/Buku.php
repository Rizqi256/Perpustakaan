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
        $data['data_buku'] = $this->Buku_model->get_buku_paginated($config["per_page"], ($page - 1) * $config["per_page"]);
        $data['data_kategori_buku'] = $this->Kategori_buku_model->get_all_kategori_buku();

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
                // Mengunggah gambar
                $config['upload_path'] = 'image/buku'; // Sesuaikan dengan path folder 
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = 2048; // Maksimal ukuran gambar (dalam KB)

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('userfile')) {
                    $image_data = $this->upload->data();
                    $foto = $image_data['file_name'];

                    // Set status buku ke "Kembali"
                    $status = 'Kembali';

                    // Insert data buku ke database
                    $data = array(
                        'id_kategori_buku' => $idKategoriBuku,
                        'nama_buku' => $judulBuku,
                        'foto' => $foto, // Menyimpan nama file gambar
                        'status' => $status // Status buku menjadi "Kembali"
                    );

                    $this->Buku_model->insert_buku($data);

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan</div>');
                    redirect('buku/index');
                } else {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $error['error'] . '</div>');
                    redirect('buku/index');
                }
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
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Isi semua data</div>');
                redirect('buku/index');
            } else {
                // Cek apakah file telah upload
                if (!empty($_FILES['userfile']['name'])) {
                    // Mengunggah gambar
                    $config['upload_path'] = 'image/buku'; // Sesuaikan dengan path folder
                    $config['allowed_types'] = 'jpg|jpeg|png|gif';
                    $config['max_size'] = 2048; // Maksimal ukuran gambar (dalam KB)
                    $config['file_name'] = 'buku_' . time(); // Nama file gambar akan disimpan dengan format "buku_tanggalwaktu"

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('userfile')) {
                        $image_data = $this->upload->data();
                        $foto = $image_data['file_name'];

                        // Update data di tabel buku
                        $data = array(
                            'id_kategori_buku' => $idKategoriBuku,
                            'nama_buku' => $judulBuku,
                            'foto' => $foto // Menyimpan nama file gambar
                        );

                        $this->Buku_model->update_buku($id_buku, $data);

                        // Delete the old photo if it exists
                        $old_photo = $this->Buku_model->get_image_filename($id_buku);
                        if ($old_photo && file_exists('image/buku/' . $old_photo)) {
                            unlink('image/buku/' . $old_photo);
                        }

                        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diperbarui</div>');
                        redirect('buku/index');
                    } else {
                        $error = array('error' => $this->upload->display_errors());
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $error['error'] . '</div>');
                        redirect('buku/index');
                    }
                } else {
                    // If no new photo is uploaded, update the data without changing the photo
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
    }

    public function komentar()
    {
        $id_user = $this->session->userdata('id_user');
        $data['id_user'] = $id_user;
        $data['data_komentar'] = $this->Buku_model->get_komentar();

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('buku/komentar', $data);
        $this->load->view('templates/footer');
    }

    public function tambah_komentar()
    {
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $id_user = $this->session->userdata('id_user');
            $isi_komentar = $this->input->post('isi_komentar');

            // Validasi input
            if (empty($isi_komentar)) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Isi komentar tidak boleh kosong</div>');
                redirect('buku/komentar');
            } else {
                // Simpan komentar ke database
                $data = array(
                    'id_user' => $id_user,
                    'isi_komentar' => $isi_komentar,
                    'tanggal_komentar' => date('Y-m-d H:i:s') // Gunakan format yang sesuai
                );

                $this->Buku_model->insert_komentar($data); // Pastikan Anda memiliki model yang sesuai untuk komentar
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Komentar berhasil ditambahkan</div>');
                redirect('buku/komentar');
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
