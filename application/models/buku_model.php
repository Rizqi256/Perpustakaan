<?php
class Buku_model extends CI_Model
{

    public function insert_buku($data)
    {
        $this->db->insert('buku', $data);
        return $this->db->insert_id(); // Mengembalikan ID buku yang baru saja ditambahkan
    }

    public function get_image_filename($id_buku)
    {
        $this->db->select('foto');
        $this->db->from('buku');
        $this->db->where('id_buku', $id_buku);
        $result = $this->db->get()->row();

        return $result ? $result->foto : null;
    }
    public function get_buku_by_id($id_buku)
    {
        $this->db->select('buku.*, buku.id_buku AS id_buku');
        $this->db->from('buku');
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
        $this->db->limit($limit, $offset); // Menggunakan nomor halaman, bukan offset
        return $this->db->get()->result();
    }

    public function search_buku($keyword)
    {
        $this->db->select('buku.*, kategori_buku.nama_kategori AS nama_kategori');
        $this->db->from('buku');
        $this->db->join('kategori_buku', 'buku.id_kategori_buku = kategori_buku.id_kategori_buku', 'left');
        $this->db->where('buku.nama_buku LIKE', "%$keyword%", 'OR');
        $this->db->or_where('kategori_buku.nama_kategori LIKE', "%$keyword%");
        $this->db->or_where('buku.id_buku LIKE', "%$keyword%");
        return $this->db->get()->result();
    }
    public function insert_komentar($data)
    {
        $this->db->insert('komentar', $data);
        return $this->db->insert_id(); // Mengembalikan ID komentar yang baru saja ditambahkan
    }
    public function get_komentar()
    {
        $this->db->select('komentar.*, user.nama AS nama');
        $this->db->from('komentar');
        $this->db->join('user', 'user.id_user = komentar.id_user');
        $query = $this->db->get();
        return $query->result();
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
