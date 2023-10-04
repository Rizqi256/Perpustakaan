<?php
class Chart_model extends CI_Model

{
    public function get_peminjaman_last_7_days()
    {
        $end_date = date('Y-m-d'); // Tanggal hari ini
        $start_date = date('Y-m-d', strtotime('-7 days', strtotime($end_date))); // Tanggal 7 hari yang lalu

        $this->db->select('DATE(tanggal_peminjaman) as tanggal, COUNT(id_peminjaman) as jumlah_peminjaman');
        $this->db->from('peminjaman');
        $this->db->where('tanggal_peminjaman >=', $start_date);
        $this->db->where('tanggal_peminjaman <=', $end_date);
        $this->db->group_by('tanggal');
        $this->db->order_by('tanggal', 'ASC');

        $query = $this->db->get();

        return $query->result_array();
    }
}
