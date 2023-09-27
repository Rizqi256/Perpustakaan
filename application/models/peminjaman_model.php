<?php

class Peminjaman_model extends CI_Model
{
    public function create_peminjaman($data)
    {
        $this->db->insert('peminjaman', $data);
        return $this->db->insert_id(); // Return the ID of the inserted row
    }

    public function create_peminjaman_detail($data)
    {
        $this->db->insert('detail', $data);
    }

    public function get_all_users()
    {
        return $this->db->get('user')->result();
    }

    public function get_all_buku()
    {
        return $this->db->get('buku')->result();
    }

    public function get_user_by_id($id_user)
    {
        return $this->db->get_where('user', array('id_user' => $id_user))->row();
    }

    public function get_buku_by_id($id_buku)
    {
        return $this->db->get_where('buku', array('id_buku' => $id_buku))->row();
    }

    public function update_peminjaman($id_peminjaman, $data)
    {
        $this->db->where('id_peminjaman', $id_peminjaman);
        $this->db->update('peminjaman', $data);
    }

    public function get_detail_peminjaman_by_id($id_peminjaman)
    {
        return $this->db->get_where('detail', array('id_peminjaman' => $id_peminjaman))->result();
    }

    public function get_peminjaman_by_id($id_peminjaman)
    {
        return $this->db->get_where('peminjaman', array('id_peminjaman' => $id_peminjaman))->row();
    }

    public function get_all_peminjaman()
    {
        return $this->db->get('peminjaman')->result();
    }

    public function update_buku_status($id_buku, $status)
    {
        $data = array(
            'status' => $status
        );

        $this->db->where('id_buku', $id_buku);
        $this->db->update('buku', $data);
    }

    public function kembalikan_buku($id_peminjaman)
    {
        // Get the details associated with the peminjaman
        $detail_peminjaman = $this->get_detail_peminjaman_by_id($id_peminjaman);

        foreach ($detail_peminjaman as $detail) {
            $this->update_buku_status($detail->id_buku, 'Available');
        }

        // Update status peminjaman menjadi "Kembali"
        $data = array(
            'status' => 'Kembali'
        );
        $this->update_peminjaman($id_peminjaman, $data);

        redirect('peminjaman/daftar');
    }


    public function delete_peminjaman($id_peminjaman)
    {
        // Get the details associated with the peminjaman
        $detail_peminjaman = $this->get_detail_peminjaman_by_id($id_peminjaman);

        // Update status of books to 'Available' and remove the details
        foreach ($detail_peminjaman as $detail) {
            $this->update_buku_status($detail->id_buku, 'Available');
            $this->db->delete('detail', array('id_peminjaman' => $id_peminjaman, 'id_buku' => $detail->id_buku));
        }

        // Delete the peminjaman record
        $this->db->delete('peminjaman', array('id_peminjaman' => $id_peminjaman));
    }
}
