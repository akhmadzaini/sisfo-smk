<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH .  'controllers/admin/Home_admin.php';

class Konfig extends Home_admin {
  public function index() {
    $this->load->view('admin/konfig');
  }

  public function submit_edit() {
    $post = $this->input->post();
    
    // Simpan nama institusi
    $this->__modif_config('NAMA_INST', $post['nama_inst']);

    $hasil = array('pesan' => 'ok');

    // Simpan logo jika tidak kosong
    if(!empty($_FILES['logo']['name'])){		
			$config['upload_path']    = './assets/img';
			$config['allowed_types']  = 'png';
			$config['max_width']      = 150;
      $config['max_height']     = 150;
			$config['file_name']      = 'logo';
			$config['overwrite']      = true;

			$this->load->library('upload', $config);

			// Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
			$this->upload->initialize($config);
			$do_upload = $this->upload->do_upload('logo');
			if($do_upload){
				$data_upload = $this->upload->data();
				$this->__modif_config('LOGO', $data_upload['file_name']);
			}else{
        $hasil = array('pesan' => 'gagal upload logo', 'error' => $this->upload->display_errors());
			}
    }

    json_output(200, $hasil);
  }

  private function __modif_config($id, $val){
    $sql = "UPDATE konfig 
            SET value = ". $this->db->escape($val) ."
            WHERE id = '$id'";
    $this->db->query($sql);
  }
}