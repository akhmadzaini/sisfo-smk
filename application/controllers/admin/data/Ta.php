<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH .  'controllers/admin/Home_admin.php';

class Ta extends Home_admin {
  function index(){
    $data['ta'] = $this->input->get('ta');
    if($data['ta'] != ''){
      $sql = "SELECT DISTINCT b.angkatan, c.kode AS jurusan_kode, c.nama AS jurusan
      FROM siswa_akademik a
      LEFT JOIN siswa b ON a.siswa_nisn = b.nisn
      LEFT JOIN jurusan c on b.jurusan_kode = c.kode 
      WHERE a.tahun_akademik_kode = '$data[ta]'
      ORDER BY b.angkatan, c.nama";
      $data['angkatan_jurusan'] = $this->db->query($sql)->result();
    }
    $this->load->view('admin/data/ta/index', $data);
  }

  function baru() {
    $tahun = $this->input->post('tahun');
    $bagian = $this->input->post('bagian');
    $kode = $tahun . $bagian;

    // 1. cek apakah tahun akademik sudah ada
    $this->db->where('kode', $kode);
    $jml = $this->db->count_all_results('tahun_akademik');

    // 2. jika tahun akademik belum ada, maka tambahkan
    if($jml == 0){
      $this->db->set('kode', $kode);
      $this->db->set('tahun', $tahun);
      $this->db->set('bagian', $bagian);
      $this->db->insert('tahun_akademik');

      // 3. tambahkan siswa aktif kedalam tahun akademik
      $this->db->where('status', 1);
      $this->db->select('nisn');
      $data = $this->db->get('siswa')->result();
      $this->db->trans_start();
      foreach($data as $r){
        $this->db->set('tahun_akademik_kode', $kode);
        $this->db->set('siswa_nisn', $r->nisn);
        $this->db->set('status', 1);
        $this->db->insert('siswa_akademik');
      }
      $this->db->trans_complete();
    }

    alihkan_laman('?d=admin/data&c=ta&ta=' . $kode);
  }

  function get_detail_angkatan_jurusan(){
    // sleep(3);
    $ta = $this->input->post('ta');
    $angkatan = $this->input->post('angkatan');
    $jurusan_kode = $this->input->post('jurusan_kode');

    $sql = "SELECT COUNT(*) AS jml
    FROM siswa_akademik a
    LEFT JOIN siswa b ON a.siswa_nisn = b.nisn
    LEFT JOIN jurusan c on b.jurusan_kode = c.kode 
    WHERE a.tahun_akademik_kode = '$ta'
    AND b.angkatan = '$angkatan'
    AND b.jurusan_kode = '$jurusan_kode' ";

    $terbuka = $this->db->query($sql . "AND a.`status` = 1")->row()->jml;
    $terkunci = $this->db->query($sql . "AND a.`status` = 2")->row()->jml;

    json_output(200, ['terbuka' => $terbuka, 'terkunci' => $terkunci]);
  }
}