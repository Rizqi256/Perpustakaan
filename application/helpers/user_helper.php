<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('load_user_data')) {
    function load_user_data()
    {
        $CI = &get_instance();

        $CI->load->model('User_model');
        $user_data = $CI->User_model->get_user_profile();

        return $user_data;
    }
}
