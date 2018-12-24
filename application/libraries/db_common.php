<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class db_common{

  function get_ta($ta = FALSE, $jml = 4){
    $CI =& get_instance();
    $bagian = ['1' => 'ganjil', '2' => 'genap'];
    if($ta != FALSE){
      // jika yang diambil bukan seluruh ta , maka dianggap mengambil keterangan ta saja
      $sql = "SELECT tahun, bagian FROM tahun_akademik WHERE kode='$ta'";
      $r = $CI->db->query($sql)->row();
      return $r->tahun . ' - ' . $bagian[$r->bagian];
    }else{
      $sql = "SELECT * FROM tahun_akademik ORDER BY kode DESC LIMIT 0, $jml";
      $ta = $CI->db->query($sql)->result();
      foreach($ta as $k => $v){
        $ta[$k]->keterangan = $v->tahun . ' - ' . $bagian[$v->bagian];
      }
      return $ta;
    }
  }

  function get_jurusan($kode = FALSE){
    $CI =& get_instance();
    if($kode != FALSE){
      $sql = "SELECT nama FROM jurusan WHERE kode='$kode'";
      return $CI->db->query($sql)->row()->nama;
    }else{
      $sql = "SELECT * FROM jurusan ORDER BY kode";
      return $CI->db->query($sql)->result();
    }
  }

  function get_angkatan(){
    $CI =& get_instance();
    $sql = "SELECT DISTINCT angkatan FROM siswa ORDER BY angkatan";
    return $CI->db->query($sql)->result();
  }


}