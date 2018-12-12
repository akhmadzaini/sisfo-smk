<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class db_common{

  function get_ta($ta){
    $CI =& get_instance();
    $sql = "SELECT tahun, bagian FROM tahun_akademik WHERE kode='$ta'";
    $bagian = ['1' => 'ganjil', '2' => 'genap'];
    $r = $CI->db->query($sql)->row();
    return $r->tahun . ' - ' . $bagian[$r->bagian];
  }

  function get_jurusan($kode){
    $CI =& get_instance();
    $sql = "SELECT nama FROM jurusan WHERE kode='$kode'";
    return $CI->db->query($sql)->row()->nama;
  }

}