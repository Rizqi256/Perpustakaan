<?php

class Peminjaman_model extends CI_Model
{

    public function create_peminjaman($data)
    {
        $this->db->insert('peminjaman', $data);
        return $this->db->insert_id();
    }

    public function update_peminjaman($id_peminjaman, $data)
    {
        $this->db->where('id_peminjaman', $id_peminjaman);
        $this->db->update('peminjaman', $data);
    }

    public function get_peminjaman_by_id($id_peminjaman)
    {
        return $this->db->get_where('peminjaman', array('id_peminjaman' => $id_peminjaman))->row();
    }

    public function get_all_peminjaman()
    {
        return $this->db->get('peminjaman')->result();
    }

    public function delete_peminjaman($id_peminjaman)
    {
        $peminjaman = $this->db->get_where('peminjaman', array('id_peminjaman' => $id_peminjaman))->row();

        if ($peminjaman) {

            $this->db->delete('peminjaman', array('id_peminjaman' => $id_peminjaman));

            return true;
        } else {
            return false;
        }
    }
}
