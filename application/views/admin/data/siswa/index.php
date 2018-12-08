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

        <form action="?d=admin/data&c=siswa&m=browse_siswa" id="frm-filter" method="post">
         
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

          <div class="row">
            <div class="col m12">
              <button type="submit" class="btn blue"><i class="material-icons left">refresh</i> tampilkan data</button>
              <a class="waves-effect waves-light btn modal-trigger blue" href="#modal-unggah-excel"><i class="material-icons left">import_export</i> impor dari excel</a>
              <a class="waves-effect waves-light btn modal-trigger grey" href="./unduhan/template_induk_siswa.xlsx" target="_blank"><i class="material-icons left">cloud_download</i> unduh template excel</a>
            </div>
          </div>
        </form>
        
          <div class="row" id="tabel-siswa">
            <div class="col m12">
              <table class="responsive-table highlight">
                <thead>
                  <tr>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Jurusan</th>
                    <th>Angkatan</th>
                    <th>Tindakan</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="baris, idx in dataSiswa">
                    <td>{{baris.nisn}}</td>
                    <td>
                      {{baris.nama}}
                      <div v-if="(baris.status == 2)" class="white-text chip green">lulus ({{baris.tahun_lulus}})</div>
                      <div v-if="(baris.status == 3)" class="white-text chip red">keluar ({{baris.tahun_keluar}})</div>
                    </td>
                    <td>{{baris.jurusan}}</td>
                    <td>{{baris.angkatan}}</td>
                    <td>
                      <a href="javascript:void(0)" class="tooltipped" @click="editSiswa(idx, baris)"><i class="tiny material-icons left" data-position="top" data-tooltip="sunting siswa">edit</i></a>
                      <a href="javascript:void(0)" class="tooltipped" @click="hapusSiswa(baris.nisn)"><i class="tiny material-icons left" data-position="top" data-tooltip="hapus siswa">delete</i></a>
                      <a href="javascript:void(0)" class="tooltipped" @click="suntingStatusSiswa(baris)"><i class="tiny material-icons left" data-position="top" data-tooltip="status">compare_arrows</i></a>
                    </td>                    
                  </tr>
                </tbody>
              </table>
            </div>
          </div>


          <!-- Modal Editor Siswa -->
          <div id="modal-editor-siswa" class="modal">
            <form action="<?=site_url('?d=admin/data&c=siswa&m=submit_edit')?>" method="post" id="frm-editor-siswa">
              <div class="modal-content">
                <h4>Sunting Data Siswa</h4>

                <div class="row">
                  <div class="input-field col s12">
                    <input id="nisn" type="text" class="validate" name="nisn" required="" v-model="nisn" placeholder="NISN (Nomor Induk Siswa)">
                    <label for="nisn" class="active">NISN (Nomor Induk Siswa)</label>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12">
                    <input id="nama" type="text" class="validate" name="nama" required="" v-model="nama" placeholder="Nama lengkap">
                    <label for="nama" class="active">Nama Lengkap</label>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12">
                    <input id="password" type="text" class="validate" name="password" required="" v-model="password" placeholder="Password">
                    <label for="password" class="active">Password</label>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12">
                    <input id="angkatan" type="text" class="validate" name="angkatan" required="" v-model="angkatan" placeholder="Angkatan">
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

              </div>
              <div class="modal-footer">
                <button type="submit" class="btn blue waves-effect">simpan</button>
                <button type="button" class="modal-close waves-effect btn-flat">batal</button>
              </div>
            </form>
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

          <!-- Modal edit status siswa -->
          <div id="modal-status-siswa" class="modal">
            <form action="<?=site_url('?d=admin/data&c=siswa&m=submit_status')?>" method="post" id="frm-status-siswa">
              
              <div class="modal-content">
                <h4>Sunting Status Siswa</h4>

                <div class="row">
                  <div class="input-field col s12">
                    <input id="nisn" type="text" class="validate" name="nisn" readonly="true" required="" v-model="nisn" placeholder="NISN (Nomor Induk Siswa)">
                    <label for="nisn" class="active">NISN (Nomor Induk Siswa)</label>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12">
                    <input id="nama" type="text" class="validate" name="nama" readonly="true" required="" v-model="nama" placeholder="Nama lengkap">
                    <label for="nama" class="active">Nama Lengkap</label>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col l4">
                    <select name="status" v-model="status">
                      <option value="1">Aktif</option>
                      <option value="2">Lulus</option>
                      <option value="3">Keluar</option>
                    </select>
                    <label for="nama" class="active">Status</label>
                  </div>
                  <div class="input-field col l8">
                    <input type="text" name="tahun" :disabled="(status == 1)" :required="(status != 1)" placeholder="Tahun">
                    <label class="active">Tahun</label>
                  </div>
                </div>

              </div>

              <div class="modal-footer">
                <button type="submit" class="btn blue waves-effect">simpan</button>
                <button type="button" class="modal-close waves-effect btn-flat">batal</button>
              </div>
            </form>
          </div>

      </div>
    </div>
  </div>
</main>


<?php
$this->load->view('umum/footer');
?>
<script src="./assets/plugins/jquery-form/jquery.form.js"></script>
<script>
  var vueEditorSiswa = new Vue({
    el: '#modal-editor-siswa',
    data: {
      nisn: null,
      nama: null,
      angkatan: null,
      jurusan_kode: null,
    }
  })

  var vueTabelSiswa = new Vue({
    el: '#tabel-siswa',
    data: {
      dataSiswa: []
    },
    methods: {
      editSiswa: function(idx, siswa) {
        vueEditorSiswa.nisn = siswa.nisn;
        $('#modal-editor-siswa [name="nisn"]').prop('readonly', true);
        vueEditorSiswa.nama = siswa.nama;
        vueEditorSiswa.angkatan = siswa.angkatan;
        vueEditorSiswa.password = siswa.password;
        $('#modal-editor-siswa [name="jurusan_kode"]').val(siswa.jurusan_kode);
        $('select').formSelect();
        $('#modal-editor-siswa').modal('open');
      },
      hapusSiswa: function(nisn){
        swal({
          title: "Anda yakin ?",
          text: "Anda akan menghapus siswa ini ?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((deleted) => {
          if(deleted){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                var hasil = JSON.parse(this.responseText);
                $('body').waitMe('hide');
                if(hasil.terhapus > 0){
                  swal({
                    title: "sukses",
                    text: "Data telah terhapus",
                    icon: "success"
                  });
                  $('#frm-filter').submit();
                }
              }else{
                $('body').waitMe();
              }
            };
            xhttp.open("POST", "?d=admin/data&c=siswa&m=hapus", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("nisn=" + nisn);
          }
        });
      },
      suntingStatusSiswa: function(siswa) {
        vueStatusSiswa.nisn = siswa.nisn;
        vueStatusSiswa.nama = siswa.nama;       
        $('#modal-status-siswa').modal('open');
      }
    } 
  });

  var vueStatusSiswa = new Vue({
    el: '#modal-status-siswa',
    data: {
      nisn: 'kosong',
      nama: 'kosong',
      status: 1,
    },
  });

  $(function() {
    $('[name="angkatan"]').val("<?=$this->input->get('angkatan')?>");
    $('[name="jurusan"]').val("<?=$this->input->get('jurusan_kode')?>");
    $('select').formSelect();
    $('#frm-filter').ajaxForm({
      dataType: 'json',
      beforeSubmit: function(){
        $('body').waitMe();
      },
      complete: function(xhr) {
        vueTabelSiswa.dataSiswa = xhr.responseJSON.data;
        $('body').waitMe('hide');
      }
    })
    $('#frm-filter').submit();

    // form ajax untuk editor siswa
    $('#frm-editor-siswa').ajaxForm({
      dataType: 'json',
      beforeSubmit: function(){
        $('body').waitMe();
      },
      complete: function(xhr) {
        $('body').waitMe('hide');
        $('#frm-filter').submit();
        $('#modal-editor-siswa').modal('close');
      }
    });

    $('#frm-status-siswa').ajaxForm({
      dataType: 'json',
      beforeSubmit: function(){
        $('body').waitMe();
      },
      complete: function(xhr) {
        console.log(xhr.responseJSON);
        $('body').waitMe('hide');
        $('#frm-filter').submit();
        $('#modal-status-siswa').modal('close');
      }
    });

  });
</script>