<?php
defined('BASEPATH') or exit('No direct script access allowed');


class User extends CI_Controller
{
    public function index()
    {
        $this->load->model('User_model');
        $data['data_user'] = $this->User_model->get_all_users();

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }

    public function delete($id_user)
    {
        $this->load->model('User_model');
        $this->User_model->delete_user($id_user);

        $this->session->set_flashdata('success', 'User deleted successfully.');
        redirect('user/index');
    }
}
