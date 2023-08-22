<?php
class User_model extends CI_Model
{
    public function get_all_users()
    {
        return $this->db->get('user')->result();
    }
    public function delete_user($id_user)
    {
        $this->db->where('id_user', $id_user);
        $this->db->delete('user');
    }
}
