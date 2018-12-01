<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_siswa extends CI_Controller {
  function __construct(){
    parent::__construct();
    if (($this->session->akses != 'siswa') or empty($this->session->login)) {
      $this->session->pesan = '
        <div class="red-text padding-t-2">
          Anda harus login sebagai siswa terlebih dahulu !!!
        </div>
      ';
      $this->session->mark_as_flash('pesan');
      alihkan_laman('?c=auth&m=login');
    }
  }
}