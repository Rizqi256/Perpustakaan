<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
    }

    protected function initialize_pagination($base_url, $total_rows, $per_page)
    {
        $config = array();
        $config["base_url"] = base_url() . $base_url;
        $config["total_rows"] = $total_rows;
        $config["per_page"] = $per_page;

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }
}
