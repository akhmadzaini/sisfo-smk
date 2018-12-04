<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('umum/header');
?>

<main>
  <div class="container">
    <div class="row">
      <div class="col s12 m8 offset-m1">
        <h3 class="header">Profil akun saya</h3>
        
        <form id="frm-profil">
            <div class="row">
              <div class="input-field col s12">
                <input id="nama_lengkap" type="text" class="validate" name="nama_lengkap" required="" value="<?=$profil->nama?>">
                <label for="nama_lengkap" class="active">Nama Lengkap</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <input id="password" type="password" class="validate" name="password">
                <label for="password" class="">Password</label>
                <span class="helper-text" data-error="wrong" data-success="right">Biarkan kosong jika tak ingin berganti password</span>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <input id="password2" type="password" class="validate">
                <label for="password2" class="">Konfirmasi Password</label>
                <span class="helper-text" data-error="wrong" data-success="right">Ketik ulang password</span>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <input id="email" type="email" class="validate" value="<?=$profil->email?>" name="email">
                <label for="email" class="">Email</label>
              </div>
            </div>

            <button class="btn waves-effect waves-light blue btn-simpan" type="button" name="action">Simpan
            </button>
        
          </form>

      </div>    
    </div>
  </div>
</main>

<?php
$this->load->view('umum/footer');
?>

<script>
  $(function () {
    $(document).on('click', '.btn-simpan', function() {

      if(!formIsValid()){
        return;
      }

      swal({
          title: "Anda yakin ?",
          text: "Anda akan mengubah profil diri anda ?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
      })
      .then((isEdited) => {
        console.log('edited :' + isEdited);
        if(isEdited){
          console.log('diedit');
          simpanForm();
        }
      });

    });

    const formIsValid = function() {
      if($('#nama_lengkap').val() == ''){
        swal({
          title: "Data Kurang",
          text: "Nama lengkap wajib diisi",
          icon: "warning",
        });
        return false;
      }

      var p1 = $('#password').val();
      var p2 = $('#password2').val();
      if(p1 != p2){
        swal({
            title: "Data Kurang",
            text: "Password dan konfirmasi password tidak sama",
            icon: "warning",
        });
        return false;
      }
      return true;
    }

    const simpanForm = function() {
      var data = $('#frm-profil').serialize();
      var url = '<?=site_url('?d=admin&c=profile&m=submit_edit')?>';
      $('main').waitMe();
      $.post(url, data, function(hasil) {
        if(hasil.pesan == 'ok'){
          $('main').waitMe('hide');
          swal('Profil telah tersimpan', {
            icon: "success",
          });
        }
      });
    }

  });
</script>