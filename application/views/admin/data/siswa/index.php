<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('umum/header');
?>

<script src="./assets/plugins/vue/vue.js"></script>

<main>
  <div class="container">
    <div class="row">
      <div class="col s12 m10 offset-m1">
        <h3 class="header">Data Induk siswa</h3>

        <form action="" id="frm-filter" method="post">
         
          <div class="input-field col m6">
            <select name="angkatan">
              <option value="" disabled selected>pilih angkatan</option>
              <?php foreach(get_angkatan() as $r):?>
                <option value="<?=$r->angkatan?>"><?=$r->angkatan?></option>
              <?php endforeach?>
            </select>
            <label>Angkatan</label>
          </div>

          <div class="input-field col m6">
            <select name="jurusan" require="">
              <option value="" disabled>pilih jurusan</option>
              <?php foreach(get_jurusan() as $r):?>
                <option value="<?=$r->kode?>"><?=$r->nama?></option>
              <?php endforeach?>
            </select>
            <label>Jurusan</label>
          </div>

          <div class="col m12">
            <button type="submit" class="btn blue"><i class="material-icons left">refresh</i> tampilkan data</button>
            <a class="waves-effect waves-light btn modal-trigger blue" href="#modal-unggah-excel"><i class="material-icons left">import_export</i> impor dari excel</a>
            <a class="waves-effect waves-light btn modal-trigger grey" href="./unduhan/template_induk_siswa.xlsx" target="_blank"><i class="material-icons left">cloud_download</i> unduh template excel</a>
          </div>

          <!-- Modal Editor Siswa -->
          <div id="modal-editor-siswa" class="modal">
            <div class="modal-content">
              <h4>siswa Baru</h4>
              <form action="<?=site_url('?d=admin/data&c=siswa&m=submit_editor')?>" method="post">

                <div class="row">
                  <div class="input-field col s12">
                    <input id="nisn" type="text" class="validate" name="nisn" required="" :value="nisn">
                    <label for="nisn" class="active">NISN (Nomor Induk Siswa)</label>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12">
                    <input id="nama" type="text" class="validate" name="nisn" required="" :value="nama">
                    <label for="nama" class="active">Nama Lengkap</label>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12">
                    <?php $jurusan = get_jurusan()?>
                    <select name="jurusan_kode" id="jurusan_kode">
                      <?php foreach($jurusan as $r):?>
                        <option value="<?=$r->kode?>"><?=$r->nama?></option>
                      <?php endforeach?>
                    </select>
                    <label for="jurusan_kode" class="active">Jurusan</label>
                  </div>
                </div>

              </form>
            </div>
            <div class="modal-footer">
              <a href="#!" class="modal-close waves-effect waves-green btn-flat">Selesai</a>
            </div>
          </div>
          <!-- Modal eksporexcel -->

          <div id="modal-unggah-excel" class="modal">
            <form id="frm-unggah-excel" action="?d=admin/data&c=siswa&m=submit_excel" enctype="multipart/form-data" method="POST">

              <div class="modal-content">
                <h4>unggah data excel</h4>

                <div class="row">
                  <div class="input-field col s12">
                    <input id="angkatan" type="text" class="validate" name="angkatan" required="">
                    <label for="angkatan" class="active">Angkatan</label>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12">
                    <?php $jurusan = get_jurusan()?>
                    <select name="jurusan_kode" id="jurusan_kode">
                      <?php foreach($jurusan as $r):?>
                        <option value="<?=$r->kode?>"><?=$r->nama?></option>
                      <?php endforeach?>
                    </select>
                    <label for="jurusan_kode" class="active">Jurusan</label>
                  </div>
                </div>

                <div class="row">
                  <div class = "file-field input-field">
                    <div class = "btn pink">
                      <span>buka template excel</span>
                      <input type = "file" name="excel"  required="" class="validate"/>
                    </div>
                    
                    <div class = "file-path-wrapper">
                      <input class = "file-path validate" type = "text" placeholder = "pilih berkas excel yang akan diunggah" />
                    </div>
                    <span class="helper-text red-text" data-error="wrong" data-success="right">Pastikan berkas excel yang diunggah telah mengikuti template resmi</span>
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                <button type="submit" class="btn blue waves-effect">unggah</button>
                <button type="button" class="modal-close waves-effect btn-flat">Batal</button>
              </div>

            </form>
          </div>

        </form>
      </div>
    </div>
  </div>
</main>


<?php
$this->load->view('umum/footer');
?>
<script>
  var vueEditorSiswa = new Vue({
    el: '#modal-editor-siswa',
    data: {
      nisn: 'Tes Vue',
      nama: 'Kosong',
      angkatan: '2018',
    }
  })

  $(function() {
    $('[name="angkatan"]').val("<?=$this->input->get('angkatan')?>");
    $('[name="jurusan"]').val("<?=$this->input->get('jurusan_kode')?>");
    $('select').formSelect();
  });
</script>