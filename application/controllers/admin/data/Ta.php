<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH .  'controllers/admin/Home_admin.php';

class Ta extends Home_admin {

  function index(){
    $data['ta'] = $this->input->get('ta');
    if($data['ta'] != ''){
      $sql = "SELECT DISTINCT b.angkatan, c.kode AS jurusan_kode, c.nama AS jurusan
      FROM siswa_akademik a
      LEFT JOIN siswa b ON a.siswa_nisn = b.nisn
      LEFT JOIN jurusan c on b.jurusan_kode = c.kode 
      WHERE a.tahun_akademik_kode = '$data[ta]'
      ORDER BY b.angkatan, c.nama";
      $data['angkatan_jurusan'] = $this->db->query($sql)->result();
    }
    $this->load->view('admin/data/ta/index', $data);
  }

  function baru() {
    $tahun = $this->input->post('tahun');
    $bagian = $this->input->post('bagian');
    $kode = $tahun . $bagian;

    // 1. cek apakah tahun akademik sudah ada
    $this->db->where('kode', $kode);
    $jml = $this->db->count_all_results('tahun_akademik');

    // 2. jika tahun akademik belum ada, maka tambahkan
    if($jml == 0){
      $this->db->set('kode', $kode);
      $this->db->set('tahun', $tahun);
      $this->db->set('bagian', $bagian);
      $this->db->insert('tahun_akademik');

      // 3. tambahkan siswa aktif kedalam tahun akademik
      $this->db->where('status', 1);
      $this->db->select('nisn');
      $data = $this->db->get('siswa')->result();
      $this->db->trans_start();
      foreach($data as $r){
        $this->db->set('tahun_akademik_kode', $kode);
        $this->db->set('siswa_nisn', $r->nisn);
        $this->db->set('status', 1);
        $this->db->insert('siswa_akademik');
      }
      $this->db->trans_complete();
    }

    alihkan_laman('?d=admin/data&c=ta&ta=' . $kode);
  }

  function get_detail_angkatan_jurusan(){
    $ta = $this->input->post('ta');
    $angkatan = $this->input->post('angkatan');
    $jurusan_kode = $this->input->post('jurusan_kode');

    $sql = "SELECT COUNT(*) AS jml
    FROM siswa_akademik a
    LEFT JOIN siswa b ON a.siswa_nisn = b.nisn
    LEFT JOIN jurusan c on b.jurusan_kode = c.kode 
    WHERE a.tahun_akademik_kode = '$ta'
    AND b.angkatan = '$angkatan'
    AND b.jurusan_kode = '$jurusan_kode' ";

    $terbuka = $this->db->query($sql . "AND a.`status` = 1")->row()->jml;
    $terkunci = $this->db->query($sql . "AND a.`status` = 2")->row()->jml;

    json_output(200, ['terbuka' => $terbuka, 'terkunci' => $terkunci]);
  }

  function detail() {
    $ta = $this->input->get('ta');
    $angkatan = $this->input->get('angkatan');
    $jurusan_kode = $this->input->get('jurusan_kode');

    $data['ta'] = $ta;

    $sql = "SELECT a.siswa_nisn AS nisn, b.nama, a.`status` , a.info_administrasi 
    FROM siswa_akademik a
    LEFT JOIN siswa b ON a.siswa_nisn = b.nisn 
    WHERE a.tahun_akademik_kode = '$ta'
    AND b.angkatan = '$angkatan'
    AND b.jurusan_kode = '$jurusan_kode'
    ORDER BY b.nama";

    // 1. data siswa yang tercatat pada tahun akademik
    $data['siswa'] = $this->db->query($sql)->result();

    // 2. detail keterangan tahun akademik
    $data['ta_str'] = $this->db_common->get_ta($ta);
    
    // 3. detail jurusan
    $data['jurusan'] = $this->db_common->get_jurusan($jurusan_kode);

    $this->load->view('admin/data/ta/detail', $data);

  }

  function change_status() {
    $ta = $this->input->post('ta');
    $nisn = $this->input->post('nisn');
    $status = $this->input->post('status');

    $status_str = ($status == 'true') ? '1' : '2';
    $sql = "UPDATE siswa_akademik SET status = $status_str
            WHERE tahun_akademik_kode = '$ta' AND siswa_nisn = '$nisn'";
    $this->db->query($sql);
    json_output(200, ['pesan' => 'ok']);
  }

  function change_info() {
    $ta = $this->input->post('ta');
    $nisn = $this->input->post('nisn');
    $info = $this->input->post('info');
    $sql = "UPDATE siswa_akademik SET info_administrasi = '$info'
            WHERE tahun_akademik_kode = '$ta' AND siswa_nisn = '$nisn'";
    $this->db->query($sql);
    json_output(200, ['pesan' => 'ok']);
  }

  function get_dokumen() {
    $ta = $this->input->post('ta');
    $nisn = $this->input->post('nisn');
    $sql = "SELECT nama, url, file_id 
            FROM berkas_akademik 
            WHERE tahun_akademik_kode= '$ta'
            AND siswa_nisn = '$nisn'";
    $dokumen = $this->db->query($sql)->result();
    json_output(200, ['dokumen' => $dokumen]);
  }

  function submit_dokumen() {
    $ta = $this->input->post('ta');
    $nisn = $this->input->post('nisn');
    $nama_berkas = strtolower($this->input->post('nama'));
    if(!empty($_FILES['berkas']['name'])){

      $this->load->library('gdrive');

			$config['upload_path']    = './public';
			$config['allowed_types']  = 'pdf';
			$config['file_name']      = $ta . '_' . $nisn . '_' . $nama_berkas;
			$config['overwrite']      = true;

			$this->load->library('upload', $config);

			// Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
			$this->upload->initialize($config);
			$do_upload = $this->upload->do_upload('berkas');
			if($do_upload){
        $data_upload = $this->upload->data();
        $this->hapus_gdrive($ta, $nisn, $nama_berkas);
        
        // unggah ke gdrive
        $file_id = $this->gdrive->unggah_share($data_upload['file_name']);
        
        $sql = "REPLACE INTO berkas_akademik (tahun_akademik_kode, siswa_nisn, nama, file_id)
                VALUES ('$ta', '$nisn', '$nama_berkas', '$file_id')";
        $this->db->query($sql);
        
        // hapus sisa berkas
        unlink($data_upload['full_path']);

        // kembalikan hasil
        $hasil = array('pesan' => 'ok');
			}else{
        $hasil = array('pesan' => 'gagal', 'error' => $this->upload->display_errors(), 'request' => $request_data);
      }
      json_output(200, $hasil);
    }
  }

  function delete_dokumen() {
    $ta = $this->input->post('ta');
    $nisn = $this->input->post('nisn');
    $nama = $this->input->post('nama');

    $this->hapus_gdrive($ta, $nisn, $nama);

    $sql = "DELETE FROM berkas_akademik
            WHERE tahun_akademik_kode = '$ta'
            AND siswa_nisn ='$nisn' AND nama = '$nama'";
    $this->db->query($sql);
  }

  function hapus_gdrive($ta, $nisn, $berkas) {
    $sql = "SELECT file_id
            FROM berkas_akademik
            WHERE tahun_akademik_kode = '$ta' AND siswa_nisn = '$nisn' AND nama='$berkas'";
    $q = $this->db->query($sql);
    if($q->num_rows() > 0) {
      $file_id = $q->row()->file_id;
      $this->load->library('gdrive');
      $this->gdrive->hapus($file_id);
    }
  }

  function tambah_aktif(){
    $jurusan = $this->input->post('jurusan');
    $angkatan = $this->input->post('angkatan');
    $ta = $this->input->post('ta');

    // 1. tambahkan siswa aktif kedalam tahun akademik
    $this->db->where('status', 1);
    $this->db->where('jurusan_kode', $jurusan);
    $this->db->where('angkatan', $angkatan);

    $this->db->select('nisn');
    $data = $this->db->get('siswa')->result();
    $this->db->trans_start();
    foreach($data as $r){

      $this->db->where('tahun_akademik_kode', $ta);
      $this->db->where('siswa_nisn', $r->nisn);
      $jml = $this->db->count_all_results('siswa_akademik');
      if($jml == 0){
        $this->db->set('tahun_akademik_kode', $ta);
        $this->db->set('siswa_nisn', $r->nisn);
        $this->db->set('status', 1);
        $this->db->insert('siswa_akademik');
      }
    }
    $this->db->trans_complete();
    alihkan_laman('?d=admin/data&c=ta&ta=' . $ta);
  }

}