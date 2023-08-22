<?php
class Kategori_buku_model extends CI_Model
{
    public function get_all_kategori_buku()
    {
        return $this->db->get('kategori_buku')->result();
    }

    public function insert_kategori_buku($data)
    {
        $this->db->insert('kategori_buku', $data);
    }

    public function get_kategori_buku_by_id($id_kategori_buku)
    {
        $this->db->where('id_kategori_buku', $id_kategori_buku);
        return $this->db->get('kategori_buku')->row();
    }

    public function update_kategori_buku($id_kategori_buku, $data)
    {
        $this->db->where('id_kategori_buku', $id_kategori_buku);
        $this->db->update('kategori_buku', $data);
    }

    public function delete_kategori_buku($id_kategori_buku)
    {
        $this->db->where('id_kategori_buku', $id_kategori_buku);
        $this->db->delete('kategori_buku');
    }
}
