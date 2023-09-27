<?php
defined('BASEPATH') or exit('No direct script access allowed');


class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('My_model');
        $this->load->library('pagination');
    }

    public function index()
    {
        $config = array();
        $config["base_url"] = base_url() . "user/index"; // Sesuaikan dengan URL Anda
        $config["total_rows"] = $this->My_model->count_all_records('user'); // Ganti 'kategori_buku' dengan nama tabel yang sesuai
        $config["per_page"] = 2; // Sesuaikan dengan jumlah item per halaman yang diinginkan
        $config["uri_segment"] = 3;
        $config['use_page_numbers'] = TRUE;

        $pagination_links = $this->pagination->initialize($config);;

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;

        $data_user =  $this->My_model->get_paginated_data('user', $config["per_page"], ($page - 1) * $config["per_page"]); // Ganti 'kategori_buku' dengan nama tabel yang sesuai

        $data['data_user'] = $data_user;

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('user/index', array('data_user' => $data['data_user'], 'pagination_links' => $pagination_links));
        $this->load->view('templates/footer');
    }

    public function search()
    {
        $keyword = $this->input->post('keyword'); // Ambil kata kunci dari form pencarian

        // Panggil model atau method di model Anda untuk melakukan pencarian
        $data['data_user'] = $this->User_model->search_users($keyword);

        // Set variabel $searched menjadi true untuk menampilkan tombol "Back"
        $data['searched'] = true;

        // Load view yang menampilkan hasil pencarian
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }


    public function profile()
    {
        $data['data_user'] = $this->User_model->get_all_users();

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('user/profile', $data);
        $this->load->view('templates/footer');
    }

    public function update_profile()
    {
        $id_user = $this->session->userdata('username');
        $nama = $this->input->post('nama');
        $password = $this->input->post('password');

        // Validasi input
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[3]', [
            'min_length' => "Password must be at least 3 characters"
        ]);

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Please fill out all required fields</div>');
            redirect('user/profile');
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $data = array(
                'nama' => $nama,
                'password' => $hashed_password
            );

            $this->User_model->update_profile($id_user, $data);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Profile updated successfully.</div>');
            redirect('user/profile');
        }
    }

    public function delete($id_user)
    {
        $this->User_model->delete_user($id_user);

        $this->session->set_flashdata('success', 'User deleted successfully.');
        redirect('user/index');
    }
}
