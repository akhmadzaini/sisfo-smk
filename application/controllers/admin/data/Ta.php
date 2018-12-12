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

  function detail() {
    $ta = $this->input->get('ta');
    $angkatan = $this->input->get('angkatan');
    $jurusan_kode = $this->input->get('jurusan_kode');

    $data['ta'] = $ta;

    $sql = "SELECT a.siswa_nisn AS nisn, b.nama, a.`status` , a.info_administrasi 
    FROM siswa_akademik a
    LEFT JOIN siswa b ON a.siswa_nisn = b.nisn 
    WHERE a.tahun_akademik_kode = '$ta'
    AND b.angkatan = '$angkatan'
    AND b.jurusan_kode = '$jurusan_kode'
    ORDER BY b.nama";

    // 1. data siswa yang tercatat pada tahun akademik
    $data['siswa'] = $this->db->query($sql)->result();

    // 2. detail keterangan tahun akademik
    $data['ta_str'] = $this->db_common->get_ta($ta);
    
    // 3. detail jurusan
    $data['jurusan'] = $this->db_common->get_jurusan($jurusan_kode);

    $this->load->view('admin/data/ta/detail', $data);

  }

  function change_status() {
    $ta = $this->input->post('ta');
    $nisn = $this->input->post('nisn');
    $status = $this->input->post('status');

    $status_str = ($status == 'true') ? '1' : '2';
    $sql = "UPDATE siswa_akademik SET status = $status_str
            WHERE tahun_akademik_kode = '$ta' AND siswa_nisn = '$nisn'";
    $this->db->query($sql);
    json_output(200, ['pesan' => 'ok']);
  }

  function change_info() {
    $ta = $this->input->post('ta');
    $nisn = $this->input->post('nisn');
    $info = $this->input->post('info');
    $sql = "UPDATE siswa_akademik SET info_administrasi = '$info'
            WHERE tahun_akademik_kode = '$ta' AND siswa_nisn = '$nisn'";
    $this->db->query($sql);
    json_output(200, ['pesan' => 'ok']);
  }

}