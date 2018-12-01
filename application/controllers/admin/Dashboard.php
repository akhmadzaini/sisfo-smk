<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH .  'controllers/admin/Home_admin.php';

class Dashboard extends Home_admin {
  function index(){
    $this->load->view('admin/dashboard');
  }
}