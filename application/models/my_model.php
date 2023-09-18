<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    public function get_paginated_data($table, $limit, $offset)
    {
        $this->db->limit($limit, $offset);
        return $this->db->get($table)->result();
    }

    public function count_all_records($table)
    {
        return $this->db->count_all($table);
    }
}
