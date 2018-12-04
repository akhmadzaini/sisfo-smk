<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';
require_once APPPATH .  'controllers/admin/Home_admin.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class Siswa extends Home_admin {
  public function index() {    
    $this->load->view('admin/data/siswa/index');
  }

  public function submit_excel(){
    // header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    ini_set('max_execution_time', 0);
    // unggah excel
    if(!empty($_FILES['excel']['name'])){		
			$config['upload_path']    = './public';
			$config['allowed_types']  = 'xlsx';
			$config['file_name']      = string_acak(10);
			$config['overwrite']      = true;

			$this->load->library('upload', $config);

			// Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
			$this->upload->initialize($config);
      $do_upload = $this->upload->do_upload('excel');
			if($do_upload){
        $data_upload = $this->upload->data();
        $inputFileName = $data_upload['full_path'];
        $spreadsheet = IOFactory::load($inputFileName);
        $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        if($this->__header_excel_ok($data)){
          $this->__simpan_data($data);
        }else{
          echo '<p>Header (judul kolom) excel tidak valid, pastikan kolom-kolom excel telah diformat sesuai 
                <a href="./unduhan/template_induk_siswa.xlsx" target="_blank">format resmi sistem.</a></p>';
          echo '<a href="?d=admin/data&c=siswa">kembali ke laman sebelumnya</a>';
        }
        unlink($inputFileName);
			}else{
        echo $this->upload->display_errors();
        echo "<br>";
        echo '<a href="?d=admin/data&c=siswa">kembali ke laman sebelumnya</a>';
			}
    }

  }

  private function __header_excel_ok($data){
    $header_resmi = ['A' => 'NISN',
                    'B' => 'NAMA',
                    'C' => 'PASSWORD'];
    $beda = array_diff($data[1], $header_resmi);
    return count($beda) == 0;
  }

  private function __simpan_data($data){
    echo '<div id="progress" style="width:500px;border:1px solid #ccc;"></div>';
    echo '<div id="link-balik"></div>';
    $jml_error = 0;
    $jurusan_kode = $this->input->post('jurusan_kode');
    $angkatan = $this->input->post('angkatan');
    foreach(range(2, count($data)) as $idx){
      if ($this->db->conn_id->ping() === FALSE){
        sleep(1);
        $this->db->reconnect();
      }
      $db_debug = $this->db->db_debug; //save setting
      $this->db->db_debug = FALSE; //disable debugging for queries

      $this->db->set('nisn', $data[$idx]['A']);
      $this->db->set('nama', $data[$idx]['B']);
      $this->db->set('password', $data[$idx]['C']);
      $this->db->set('jurusan_kode', $jurusan_kode);
      $this->db->set('angkatan', $angkatan);
      $this->db->insert('siswa');
      $percent = 500 * $idx / count($data);
      echo '<script language="javascript">
        document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
      </script>';
      $error = $this->db->error();
      if($error['code'] != 0){
        echo '<span style="color: red">Gagal memproses : ' . $data[$idx]['B'] . '</span> ==> '. $error['message'] .'<br>';
        $jml_error ++;
      }      
      $this->db->db_debug = $db_debug; //restore setting
      flush();
      ob_flush();
      usleep(1);
    }
    if($jml_error > 0){
      echo '<script language="javascript">
        document.getElementById("link-balik").innerHTML="<a href=\"?d=admin/data&c=siswa\">kembali ke laman sebelumnya</a>";
      </script>';
    }else{
      echo '<script language="javascript">
        document.location.href="?d=admin/data&c=siswa&angkatan=' . $angkatan . '&jurusan_kode=' . $jurusan_kode .
      '";</script>';
    }
  }
}