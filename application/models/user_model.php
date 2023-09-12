<?php
class User_model extends CI_Model
{

    public function get_user_profile()
    {
        return $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row();
    }

    public function get_all_users()
    {
        return $this->db->get('user')->result();
    }

    public function update_profile($id_user, $data)
    {
        $this->db->where('username', $id_user);
        $this->db->update('user', $data);
    }

    public function delete_user($id_user)
    {
        $this->db->where('id_user', $id_user);
        $this->db->delete('user');
    }
}
