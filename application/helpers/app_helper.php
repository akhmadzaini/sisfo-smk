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