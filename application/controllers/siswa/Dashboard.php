<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH .  'controllers/siswa/Home_siswa.php';

class Dashboard extends Home_siswa {
  function index(){
    $this->load->view('siswa/dashboard');
  }
}