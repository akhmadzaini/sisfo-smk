<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('umum/header');
?>

<script src="./assets/plugins/vue/vue.js"></script>

<main>
  <div class="container">

    <div class="row">
      <div class="col s12 m10 offset-m1">
        <h3 class="header">Aktivitas Akademik Siswa</h3>

        <div class="row">
          <div class="input-field col l12">
            <input type="text" readonly value="<?=$ta_str?>">
            <label>Tahun Akademik</label>
          </div>
        </div>

        <div class="row">
          <div class="input-field col l12">
            <input type="text" readonly value="<?=$jurusan?>">
            <label>Jurusan</label>
          </div>
        </div>

        <a href="?d=admin/data&c=ta&ta=<?=$ta?>" class="btn blue wave-effect">
          <i class="left material-icons">crop_rotate</i>  lihat jurusan lain
        </a>

        <table class="responsive-table highlight">
          <thead>
            <tr>
              <th>NISN</th>
              <th>Nama</th>
              <th>Status</th>
              <th>Berkas Akademik</th>
              <th>Info Administrasi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($siswa as $r):?>
              <tr>
                <td><?=$r->nisn?></td>
                <td><?=$r->nama?></td>
                <td>
                  <?php $checked = ($r->status == 1) ? 'checked' : '';?>
                  <div class="switch">
                    <label>
                      terkunci
                      <input type="checkbox" <?=$checked?> data-nisn="<?=$r->nisn?>" class="chk-status">
                      <span class="lever"></span>
                      terbuka
                    </label>
                  </div>
                </td>
                <td>
                  <div id="dokumen-<?=$r->nisn?>"></div>
                </td>
                <td>
                  <a href="javascript:void(0);" class="btn-flat btn-info" data-nisn="<?=$r->nisn?>">
                    <i class="material-icons">open_in_new</i>
                  </a>
                  <div id="info-peserta-<?=$r->nisn?>" style="display: none;">
                    <textarea name="info"><?=$r->info_administrasi?></textarea>
                    <button class="btn-simpan-info btn blue wave-effect" data-nisn="<?=$r->nisn?>">simpan</button>
                  </div>
                </td>
              </tr>
            <?php endforeach?>
          </tbody>
        </table>

      </div>
    </div>
  </div>
</main>

<!-- modal Editor dokumen -->
<div id="modal-editor-dokumen" class="modal">
  <form action="<?=site_url('?d=admin/data&c=ta&m=submit_dokumen')?>" method="post" enctype="multipart/form-data" id="frm-editor-dokumen">
    <input type="hidden" name="ta" value="">
    <input type="hidden" name="nisn" value="">
    <div class="modal-content">
      <h4>Dokumen Baru</h4>

      <div class="row">
        <div class="input-field col s12">
          <input type="text" class="validate" name="nama" required="" id="nama-dokumen">
          <label class="active" for="nama-dokumen">Nama dokumen</label>
        </div>
      </div>   

      <div class = "row">
        <div class="input-field col s12">
            <label>Berkas unggahan</label>
            <div class = "file-field input-field">

              <div class = "btn pink">
                <span>Pilih berkas unggahan</span>
                <input type = "file" name="berkas"/>
              </div>
              
              <div class = "file-path-wrapper">
                <input class = "file-path validate" type = "text"
                placeholder = "Unggah berkas" />
              </div>

            </div>
        </div>           
      </div>

    </div>
    <div class="modal-footer">
      <button type="submit" class="btn blue waves-effect">simpan</button>
      <button type="button" class="modal-close waves-effect btn-flat">batal</button>
    </div>
  </form>
</div>

<?php
$this->load->view('umum/footer');
?>
<script src="./assets/plugins/jquery-form/jquery.form.js"></script>
<script>

  var muatDokumen = function(ta, nisn) {
    const selector = '#' + 'dokumen-' + nisn;
    const url = '?d=admin/data&c=ta&m=get_dokumen';
    const data= {
      ta: ta,
      nisn: nisn,
    };
    $(selector).html('sedang memuat...');
    $.post(url, data, function(hasil) {
      var layout_add = '<a class="btn-dokumen-baru btn-flat col l12" data-ta="'+ ta +'" data-nisn="'+ nisn +'"> \
      <i class="left material-icons">add</i> baru</a>';
      $.each(hasil.dokumen, function(key, val){
        layout_add += '<div class="chip" data-ta="'+ ta +'" data-nisn="'+ nisn +'" data-nama="'+ val.nama +'">\
                        <a href="https://drive.google.com/file/d/'+ val.file_id +'/view?usp=sharing" target="_blank">' + val.nama + '</a><i class="close material-icons">close</i></div>';
      });
      const layout = '<a class="btn-toggle-dokumen btn blue col l12" href="javascript:void(0);" \
                      data-target="dd-dokumen-'+ nisn +'"> <i class="left material-icons">get_app</i> '+ hasil.dokumen.length +' dokumen</a> \
                      <div id="dd-dokumen-' + nisn + '" style="display: none;">' + layout_add + '</div>';
      $(selector).html(layout);    

    });
  }


  $(function() {
    $(document).on('change', '.chk-status', function() {
      var data = {
        ta: '<?=$ta?>',
        nisn: $(this).data('nisn'),
        status: $(this).prop('checked'),
      }
      var url = '?d=admin/data&c=ta&m=change_status';
      $('body').waitMe();
      $.post(url, data, function(hasil) {
        if(hasil.pesan == 'ok') {
          $('body').waitMe('hide');
        }
      });
    });

    $(document).on('click', '.btn-info', function() {
      const nisn = $(this).data('nisn');
      $('#info-peserta-' + nisn).toggle();
    });

    $(document).on('click', '.btn-simpan-info', function() {
      const data = {
        ta: '<?=$ta?>',
        nisn : $(this).data('nisn'),
        info : $(this).parent().find('textarea').val()
      }
      const url = '?d=admin/data&c=ta&m=change_info';
      $('body').waitMe();
      $.post(url, data, function() {
        $('body').waitMe('hide');
      });
    });

    $(document).on('click', '.btn-dokumen-baru', function() {
      $('#modal-editor-dokumen').modal('open');
      $('#modal-editor-dokumen [name="ta"]').val($(this).data('ta'));
      $('#modal-editor-dokumen [name="nisn"]').val($(this).data('nisn'));
    });

    $(document).on('click', '.chip .close', function (e) {
      const data = {
        ta : $(this).parent().data('ta'),
        nisn : $(this).parent().data('nisn'),
        nama : $(this).parent().data('nama')
      };
      const url = '?d=admin/data&c=ta&m=delete_dokumen';
      $('body').waitMe();
      $.post(url, data, function() {
        muatDokumen(data.ta, data.nisn);
        $('body').waitMe('hide');
      })
    });

    $(document).on('click', '.btn-toggle-dokumen', function () {
      const target = '#' + $(this).data('target');
      $(target).toggle();
    });

    $('#frm-editor-dokumen').ajaxForm({
      dataType: 'json',
      beforeSend: function() {
        $('body').waitMe();
      },
      complete: function(xhr) {
        const hasil= xhr.responseJSON;
        if(hasil.pesan != 'ok'){
          swal({
            title: 'gagal',
            text: 'gagal mengunggah berkas ==> ' + hasil.error,
            icon: 'error'
          });
        }
        $('body').waitMe('hide');        
        $('#modal-editor-dokumen').modal('close');
        $('#frm-editor-dokumen').trigger('reset');
        // segarkan daftar file
        const ta = $('#frm-editor-dokumen [name="ta"]').val();
        const nisn = $('#frm-editor-dokumen [name="nisn"]').val();
        muatDokumen(ta, nisn);
      }
    });
    
    <?php foreach($siswa as $r):?>
      muatDokumen('<?=$ta?>', '<?=$r->nisn?>') 
    <?php endforeach?>

    

  });
</script>