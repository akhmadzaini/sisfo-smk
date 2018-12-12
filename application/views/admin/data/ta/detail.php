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
                  <div class="chip">
                    <a href="">rapor</a>
                    <i class="close material-icons">close</i>
                  </div>
                  <a href="" class="btn-flat"><i class="material-icons">add</i></a>
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

<?php
$this->load->view('umum/footer');
?>

<script>
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
  });
</script>