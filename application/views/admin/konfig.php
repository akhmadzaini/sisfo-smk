<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('umum/header');
?>

<main>
  <div class="container">
    <div class="row">
      <div class="col s12 m8 offset-m1 xl7 offset-xl1">
        <h3 class="header">Pengaturan Sistem</h3>
        
        <form id="frm-konfig" action="<?=site_url('?d=admin&c=konfig&m=submit_edit')?>" enctype="multipart/form-data" method="POST">
          <div class="row">
            <div class="input-field col s12">
              <input id="nama_inst" type="text" class="validate" name="nama_inst" required="" value="<?=get_konfig('NAMA_INST')?>">
              <label for="nama_inst" class="active">Nama Institusi</label>
            </div>
          </div>
          
          <div class = "row">
            <label>Logo Institusi</label>
            <div class = "file-field input-field">
              <div class = "btn pink">
                <span>Buka Logo</span>
                <input type = "file" name="logo"/>
              </div>
              
              <div class = "file-path-wrapper">
                <input class = "file-path validate" type = "text"
                placeholder = "Upload file" />
              </div>
              <span class="helper-text" data-error="wrong" data-success="right">Biarkan kosong jika tak ingin berganti logo</span>
            </div>
          </div>
          
          <div class="row">
            <button type="submit" class="btn blue">Simpan</button>
          </div>
        </form>
        
      </div>
    </div>
  </div>
</main>

<?php
$this->load->view('umum/footer');
?>
<script src="./assets/plugins/jquery-form/jquery.form.js"></script>

<script>
  $(function() {
    $('#frm-konfig').ajaxForm({
      beforeSend: function() {
        $('main').waitMe();
      },
      uploadProgress: function(event, position, total, percentComplete) {
        var percentVal = percentComplete + '%';
        $('main').waitMe({
          text: 'Proses unggah logo : ' + percentVal
        });
      },
      success: function() {
        return;        
      },
      complete: function(xhr) {
        $('main').waitMe('hide');
        if(xhr.responseJSON.pesan == 'ok'){
          $(".logo_institusi  ").attr("src", "./assets/img/logo.png?" + new Date().getTime() );
          swal({
            text: 'Konfigurasi telah tersimpan ',
            icon: 'success'
          });
        }else{
          swal({
            text: 'Gagal melakukan konfigurasi, pesan : ' + xhr.responseJSON.error,
            icon: 'error'
          });
        }
      }
    });
  });
</script>