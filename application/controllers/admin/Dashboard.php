<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH .  'controllers/admin/Home_admin.php';

class Dashboard extends Home_admin {
  function index(){
    $this->load->library('db_common');
    $data['ta'] = $this->db_common->get_ta();
    $data['jurusan'] = $this->db_common->get_jurusan();
    $data['angkatan'] = $this->db_common->get_angkatan();
    $this->load->view('admin/dashboard', $data);
  }

  function muat_rincian() {
    $ta = $this->input->post('ta');
    $jurusan = $this->input->post('jurusan');
    $angkatan = $this->input->post('angkatan');
    $terbuka = $this->__get_rincian($ta, $jurusan, $angkatan, 1);
    $terkunci = $this->__get_rincian($ta, $jurusan, $angkatan, 2);
    $jml_berkas = $this->__get_jml_berkas($ta, $jurusan, $angkatan);
    json_output(200, ['terbuka' => $terbuka, 'terkunci' => $terkunci, 'jml_berkas' => $jml_berkas]);
  }

  private function __get_rincian($ta, $jurusan, $angkatan, $status){
    $sql = "SELECT COUNT(a.`status`) jml
    FROM siswa_akademik a 
    LEFT JOIN siswa b ON a.siswa_nisn = b.nisn
    WHERE a.`status`= $status
    AND b.jurusan_kode = '$jurusan'
    AND b.angkatan = '$angkatan'
    AND a.tahun_akademik_kode = '$ta'";
    return $this->db->query($sql)->row()->jml;
  }

  private function __get_jml_berkas($ta, $jurusan, $angkatan) {
    $sql = "SELECT COUNT(*) jml
    FROM berkas_akademik a 
    LEFT JOIN siswa b ON a.siswa_nisn = b.nisn
    WHERE b.jurusan_kode = '$jurusan'
    AND b.angkatan = '$angkatan'
    AND a.tahun_akademik_kode = '$ta'";
    return $this->db->query($sql)->row()->jml;
  }
  
}