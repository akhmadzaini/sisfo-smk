<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class html_gen{
  function combo_ta() {
    $CI =& get_instance();
    $sql = "SELECT kode, tahun, bagian
            FROM tahun_akademik
            ORDER BY kode DESC";
    $ta = $CI->db->query($sql)->result();

    $options = '';
    $bagian = array('1' => 'ganjil' , '2' => 'genap');
    foreach($ta as $r){
      $selected = ($CI->input->get('ta') == $r->kode) ? 'selected' : '';
      $options .= '<option value="'. $r->kode .'" '. $selected .'>'. $r->tahun . ' - ' . $bagian[$r->bagian] .'</option>';
    }

    return '<select name="ta" id="ta" required="" class="validate">
              <option value="" disabled selected>pilih tahun akademik</option>
              '. $options .'
            </select>
            <label for="ta">Angkatan</label>';
  }
  
  function combo_bagian_ta() {
    return '<select name="bagian" id="bagian" class="validate" required="">
              <option value="" disabled selected>pilih tahapan</option>
              <option value="1" >ganjil</option>
              <option value="2" >genap</option>
            </select>
            <label for="bagian">Tahapan</label>';
  }

  function combo_jurusan() {
    $CI =& get_instance();
    $sql = "SELECT kode, nama FROM jurusan";
    $jurusan = $CI->db->query($sql)->result();
    $add = '';
    foreach($jurusan as $r){
      $add .= '<option value="'.$r->kode.'">'.$r->nama.'</option>';
    }
    return '<select name="jurusan" id="jurusan" class="validate" required="">
              <option value="" disabled selected>pilih jurusan</option>
              '.$add.'
            </select>
            <label for="jurusan">Jurusan</label>';
  }
 
  function combo_angkatan() {
    $CI =& get_instance();
    $sql = "SELECT DISTINCT angkatan FROM siswa";
    $jurusan = $CI->db->query($sql)->result();
    $add = '';
    foreach($jurusan as $r){
      $add .= '<option value="'.$r->angkatan.'">'.$r->angkatan.'</option>';
    }
    return '<select name="angkatan" id="angkatan" class="validate" required="">
              <option value="" disabled selected>pilih angkatan</option>
              '.$add.'
            </select>
            <label for="angkatan">Angkatan</label>';
  }
  
}