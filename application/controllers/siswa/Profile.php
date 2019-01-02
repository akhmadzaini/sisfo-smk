<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH .  'controllers/siswa/Home_siswa.php';

class Profile extends Home_siswa {

  function index() {
    $this->load->view('siswa/profile');
  }

  function submit() {
    $nisn = $this->session->login;
    $nama = $this->input->post('nama');
    $password = $this->input->post('password');
    
    $this->db->where('nisn', $nisn);
    $this->db->set('nama', $nama);
    $this->db->set('password', $password);

    $this->db->update('siswa');

    json_output(200, ['pesan'=>'ok']);

  }
}