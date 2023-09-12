<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        if ($this->session->userdata('username')) {
            redirect('user');
        }

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/auth_header');
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['username' => $username])->row();

        if ($user && password_verify($password, $user->password)) {
            $data = [
                'id_user' => $user->id_user,
                'email' => $user->email,
                'username' => $user->username,
                'nama' => $user->nama
            ];
            $this->session->set_userdata($data);
            redirect('dashboard/index');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Invalid credentials</div>');
            redirect('auth');
        }
    }

    public function register()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('username', 'User Name', 'required|trim|is_unique[user.username]');
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[6]|matches[password2]', [
            'matches' => "Password doesn't match",
            'min_length' => "Password must be at least 6 characters"
        ]);
        $this->form_validation->set_rules('password2', 'Confirm Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Register';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/register');
            $this->load->view('templates/auth_footer');
        } else {
            $data = [
                'username' => $this->input->post('username'),
                'nama' => $this->input->post('nama'),
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'email' => $this->input->post('email')
            ];

            $this->db->insert('user', $data);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Registration success. Please login.</div>');
            redirect('auth');
        }
    }


    public function forgot()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/auth_header');
            $this->load->view('auth/forgot');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email');
            $user = $this->db->get_where('user', ['email' => $email])->row();

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $this->_send_reset_email($user, $token);
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password reset link sent</div>');

                // Save token and user email in session
                $this->session->set_userdata('reset_token', $token);
                $this->session->set_userdata('reset_email', $email);

                redirect('auth/forgot');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email not registered</div>');
                redirect('auth/forgot');
            }
        }
    }


    private function _send_reset_email($user, $token)
    {
        include_once('PHPMailer/src/Exception.php');
        include_once('PHPMailer/src/PHPMailer.php');
        include_once('PHPMailer/src/SMTP.php');

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->Username = 'rizqi.27560@gmail.com';
            $mail->Password = 'unmeabkuooggmbzr';
            $mail->SMTPSecure = 'tls';

            $mail->setFrom('rizqi.27560@gmail.com', 'Rizqi');
            $mail->addAddress($user->email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset';
            $mail->Body = "Click <a href='" . base_url("auth/reset_password/$token") . "'>here</a> to reset your password.";

            $mail->send();

            // Save token and user email in user_token table
            $this->db->insert('user_token', [
                'email' => $user->email,
                'token' => $token,
                'date_created' => time()
            ]);
        } catch (Exception $e) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email could not be sent. Please try again later.</div>');
            redirect('auth/forgot');
        }
    }

    public function reset_password()
    {
        $token = $this->session->userdata('reset_token');
        $email = $this->session->userdata('reset_email');

        if (!$token || !$email) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Session expired or invalid token</div>');
            redirect('auth/forgot');
        }

        $this->form_validation->set_rules('password1', 'New Password', 'trim|required|matches[password2]');
        $this->form_validation->set_rules('password2', 'Repeat Password', 'trim|required|matches[password1]');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/auth_header');
            $this->load->view('auth/new_password');
            $this->load->view('templates/auth_footer');
        } else {
            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);

            $this->db->set('password', $password);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->unset_userdata('reset_token');
            $this->session->unset_userdata('reset_email');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password reset successfully. Please login with your new password.</div>');
            redirect('auth');
        }
    }
    public function logout()
    {
        $this->session->unset_userdata('id_user');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('nama');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You have been logged out</div>');
        redirect('auth');
    }
}
