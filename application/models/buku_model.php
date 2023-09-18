<?php
class Buku_model extends CI_Model
{
    public function get_all_buku()
    {
        $this->db->select('buku.*, kategori_buku.nama_kategori AS nama_kategori');
        $this->db->from('buku');
        $this->db->join('kategori_buku', 'buku.id_kategori_buku = kategori_buku.id_kategori_buku', 'left');
        return $this->db->get()->result();
    }
    public function insert_buku($data)
    {
        $this->db->insert('buku', $data);
    }
    public function get_buku_by_id($id_buku)
    {
        $this->db->select('buku.*, kategori_buku.nama_kategori AS nama_kategori');
        $this->db->from('buku');
        $this->db->join('kategori_buku', 'buku.id_kategori_buku = kategori_buku.id_kategori_buku', 'left');
        $this->db->where('buku.id_buku', $id_buku);
        return $this->db->get()->row();

        $query = $this->db->get_where('buku', array('id_buku' => $id_buku));

        if ($query->num_rows() > 0) {
            $buku = $query->row();

            $buku->status = $this->get_buku_status($id_buku);

            return $buku;
        }
    }
    public function get_buku_status($id_buku)
    {
        $query = $this->db->get_where('peminjaman', array('id_buku' => $id_buku, 'status' => 'Pinjam'));

        if ($query->num_rows() > 0) {
            return 'Pinjam';
        } else {
            return 'Tersedia';
        }
    }

    public function get_buku_by_category($id_kategori_buku)
    {
        $this->db->select('buku.*, kategori_buku.nama_kategori AS nama_kategori');
        $this->db->from('buku');
        $this->db->join('kategori_buku', 'buku.id_kategori_buku = kategori_buku.id_kategori_buku', 'left');

        // Filter berdasarkan ID kategori
        if (!empty($id_kategori_buku)) {
            $this->db->where('buku.id_kategori_buku', $id_kategori_buku);
        }

        return $this->db->get()->result();
    }

    public function get_total_records()
    {
        return $this->db->count_all('buku');
    }

    public function get_buku_paginated($limit, $offset)
    {
        $this->db->select('buku.*, kategori_buku.nama_kategori AS nama_kategori');
        $this->db->from('buku');
        $this->db->join('kategori_buku', 'buku.id_kategori_buku = kategori_buku.id_kategori_buku', 'left');
        $this->db->limit($limit, $offset - 1); // Menggunakan nomor halaman, bukan offset
        return $this->db->get()->result();
    }

    public function search_buku($keyword)
    {
        $this->db->select('buku.*, kategori_buku.nama_kategori AS nama_kategori');
        $this->db->from('buku');
        $this->db->join('kategori_buku', 'buku.id_kategori_buku = kategori_buku.id_kategori_buku', 'left');
        $this->db->where('buku.nama_buku LIKE', "%$keyword%", 'OR');
        $this->db->or_where('kategori_buku.nama_kategori LIKE', "%$keyword%");
        return $this->db->get()->result();
    }


    public function delete_buku($id_buku)
    {
        $this->db->where('id_buku', $id_buku);
        $this->db->delete('buku');
    }
    public function update_buku($id_buku, $data)
    {
        $this->db->where('id_buku', $id_buku);
        $this->db->update('buku', $data);
    }
}
