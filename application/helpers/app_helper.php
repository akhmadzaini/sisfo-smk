<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function alihkan_laman($laman){
  redirect(site_url($laman));
}

// Menghasilkan output json
function json_output($statusHeader,$response){
  $ci =& get_instance();
  $ci->output->set_content_type('application/json');
  $ci->output->set_status_header($statusHeader);
  // $ci->output->set_output(json_encode($response));
  echo json_encode($response);
}

// Ambil data konfigurasi
function get_konfig($id){
  $CI =& get_instance();
  $sql = "SELECT value
          FROM konfig
          WHERE id = '$id'";
  $r = $CI->db->query($sql)->row();
  return $r->value;
}

// String unik dengan panjang tertentu
function string_acak($pjg) {
  $char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  $char = str_shuffle($char);
  for($i = 0, $rand = '', $l = strlen($char) - 1; $i < $pjg; $i ++) {
      $rand .= $char{mt_rand(0, $l)};
  }
  return $rand;
}

// ambil data jurusan
function get_jurusan(){
  $CI =& get_instance();
  $sql = "SELECT kode, nama
          FROM jurusan
          ORDER BY kode";
  return $CI->db->query($sql)->result();
}

// ambil data jurusan
function get_angkatan(){
  $CI =& get_instance();
  $sql = "SELECT DISTINCT angkatan
          FROM siswa
          WHERE angkatan IS NOT NULL
          ORDER BY angkatan";
  return $CI->db->query($sql)->result();
}