<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

  public function index(){
    if($this->session->akses == 'admin'){
      alihkan_laman('?d=admin&c=dashboard');
    }else if($this->session->akses == 'siswa'){
      alihkan_laman('?d=siswa&c=dashboard');
    }else{
      alihkan_laman('?c=auth&m=login');
    }
  }

	public function login(){
    if(isset($this->session->login) and $this->session->akses=='siswa'){
      alihkan_laman('?d=siswa&c=dashboard');
    }else{
      $this->load->view('siswa/login');
    }
  }

  public function login_admin(){
    if(isset($this->session->login) and $this->session->akses=='admin'){
      alihkan_laman('?d=admin&c=dashboard');
    }else{
      $this->load->view('admin/login');
    }
  }

  public function submit_login_siswa(){
    $post = $this->input->post();
    $sql = "SELECT nama FROM siswa 
            WHERE nisn = '$post[nisn]' 
            AND password = '$post[password]'";
    $r = $this->db->query($sql)->row();
    if(empty($r)){
      $this->session->pesan = '
        <div class="red-text padding-t-2">
          Login gagal !!!
        </div>
      ';
      $this->session->mark_as_flash('pesan');
      alihkan_laman('c=auth&m=login');
    }else{
      $this->session->login = $post['nisn'];
      $this->session->nama = $r->nama;
      $this->session->akses = 'siswa';
      alihkan_laman('d=siswa&c=dashboard');
    }
  }
  
  public function submit_login_admin(){
    $post = $this->input->post();
    $sql = "SELECT nama FROM admin 
            WHERE login = '$post[login]' 
            AND password = MD5('$post[password]')";
    $r = $this->db->query($sql)->row();
    if(empty($r)){
      $this->session->pesan = '
        <div class="red-text padding-t-2">
          Login gagal !!!
        </div>
      ';
      $this->session->mark_as_flash('pesan');
      alihkan_laman('c=auth&m=login_admin');
    }else{
      $this->session->login = $post['login'];
      $this->session->nama = $r->nama;
      $this->session->akses = 'admin';
      alihkan_laman('d=admin&c=dashboard');
    }
  }

  public function logout() {
    $akses = $this->session->akses;
    $this->session->sess_destroy();
    $this->session->pesan = '
        <div class="green-text padding-t-2">
          Anda telah keluar dari sistem
        </div>
      ';
    $this->session->mark_as_flash('pesan');
    if($akses == 'siswa'){
      alihkan_laman('c=auth&m=login');
    }else{
      alihkan_laman('c=auth&m=login_admin');
    }
  }
  
}