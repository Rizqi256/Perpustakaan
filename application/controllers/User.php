<?php
defined('BASEPATH') or exit('No direct script access allowed');


class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index()
    {
        $data['data_user'] = $this->User_model->get_all_users();

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
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

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
