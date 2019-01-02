<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('umum/header');
?>

<script src="./assets/plugins/vue/vue.js"></script>

<main>
  <div class="container">

    <div class="row">
      <div class="col s12 m10 offset-m1">
        <h3 class="header">Data Tahun Akademik</h3>
        <form method="get">
          <input type="hidden" name="d" value="<?=$this->input->get('d')?>">
          <input type="hidden" name="c" value="<?=$this->input->get('c')?>">
          <div class="row">
            <div class="input-field col l12">
              <?=$this->html_gen->combo_ta()?>
            </div>
          </div>

          <div class="row">
            <div class="col l12">
              <button class="btn blue waves-effect waves-light" type="submit"><i class="material-icons left">refresh</i> lihat data</button>
              <button id="btn-ta-baru" class="btn blue waves-effect waves-light" type="button"><i class="material-icons left">add</i> tahun akademik baru</button>
              <?php if($this->input->get('ta') != ''):?>
              <button id="btn-aktif" class="btn blue waves-effect waves-light" type="button"><i class="material-icons left">done_all</i> tambah siswa aktif</button>
              <?php endif?>
            </div>
          </div>
        </form>
      </div>
    </div>

    <?php if($ta != ''):?>
    <div class="row" id="vueTabelDetail">
      <div class="col s12 m10 offset-m1">
        <table class="responsive-table highlight">
          <thead>
            <tr>
              <th>Angkatan</th>
              <th>Jurusan</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="baris, key in angkatan_jurusan">
              <td>{{baris.angkatan}}</td>
              <td>{{baris.jurusan}}</td>
              <td>
                <span :id="'<?=$ta?>-' + baris.angkatan + '-' + baris.jurusan_kode"></span>{{muatDetail(<?=$ta?>, baris.angkatan, baris.jurusan_kode)}}
                <a :href="'?d=admin/data&c=ta&m=detail&ta=<?=$ta?>&angkatan='+ baris.angkatan + '&jurusan_kode=' + baris.jurusan_kode" class="btn-flat">
                  <i class="material-icons">open_in_new</i>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif?>

    <!-- modal tambah TA -->
    <div id="modal-editor-ta" class="modal">
      <form action="<?=site_url('?d=admin/data&c=ta&m=baru')?>" method="post">
        <div class="modal-content">
          <h4>Sunting Data Siswa</h4>

          <div class="row">
            <div class="input-field col s12">
              <input type="text" class="validate" name="tahun" id="tahun" required="" maxlength="4">
              <label for="tahun" class="active">Tahun akademik</label>
              <span class="helper-text small">contoh : 2018</span>
            </div>
          </div>   

          <div class="row">
            <div class="input-field col l12">
              <?=$this->html_gen->combo_bagian_ta()?>
            </div>
          </div>           

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn blue waves-effect">simpan</button>
          <button type="button" class="modal-close waves-effect btn-flat">batal</button>
        </div>
      </form>
    </div>

    <!-- modal tambah siswa -->
    <div id="modal-aktif" class="modal">
      <form action="<?=site_url('?d=admin/data&c=ta&m=tambah_aktif')?>" method="post">
        <div class="modal-content">
          <h4>Tambah siswa aktif</h4>
          <input type="hidden" name="ta" value="<?=$this->input->get('ta')?>">
          <div class="row">
            <div class="input-field col l12">
              <?=$this->html_gen->combo_jurusan()?>
            </div>
          </div>           
          
          <div class="row">
            <div class="input-field col s12">
              <?=$this->html_gen->combo_angkatan()?>
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
</main>

<?php
$this->load->view('umum/footer');
?>

<script>
  <?php if($ta != ''):?>
  var vueTabelDetail = new Vue({
    el: "#vueTabelDetail",
    data: {
      angkatan_jurusan: <?=json_encode($angkatan_jurusan)?>,
    },
    methods: {
      muatDetail: function(ta, angkatan, jurusan_kode){ 
        const url = "?d=admin/data&c=ta&m=get_detail_angkatan_jurusan";
        const data = {
          ta: ta,
          angkatan: angkatan,
          jurusan_kode: jurusan_kode
        };
        const selector = '#' + ta + '-' + angkatan + '-' + jurusan_kode;
        $(selector).html('sedang mengambil data...');
        $.post(url, data, function(hasil) {
          var tampilan = '<div class="white-text chip green">'+ hasil.terbuka +' terbuka</div> \
            <div class="white-text chip red">'+ hasil.terkunci +' terkunci</div>';
          $(selector).html(tampilan);
        });
      }
    }
  });
  <?php endif?>

  $(function() {

    $('#btn-ta-baru').on('click', function() {
      $('#modal-editor-ta').modal('open');
    });

    $('#btn-aktif').on('click', function() {
      $('#modal-aktif').modal('open');
    });
  });
</script>