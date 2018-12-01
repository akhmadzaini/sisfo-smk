<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH .  'controllers/admin/Home_admin.php';

class Profile extends Home_admin {
  public function index() {
    $login = $this->session->login;
    $sql = "SELECT * FROM admin 
            WHERE login = '$login'";
    $r = $this->db->query($sql)->row();
    $this->load->view('admin/profile', array('profil' => $r));
  }

  public function submit_edit() {
    $post = $this->input->post();
    $login = $this->session->login;
    $add_sql = '';
    if($post['password'] !== ''){
      $add_sql = ",password = MD5('$post[password]')";
    }
    $sql = "UPDATE admin SET
            nama = '$post[nama_lengkap]',
            email = '$post[email]' 
            $add_sql
            WHERE login = '$login'";
    $this->db->query($sql);
    json_output(200, array('pesan' => 'ok'));
  }
}