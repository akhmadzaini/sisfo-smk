<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('umum/header');
?>

<main>
  <div class="container">
    <div class="row">
      <div class="col s12 m10 offset-m1 xl10 offset-xl1">
        <h3 class="header">Rekap akademik 4 semester terakhir</h3>
        <ul class="collapsible">
          <?php foreach($ta as $r):?>
            <li>
              <div class="collapsible-header"><i class="material-icons">filter_drama</i><?=$r->keterangan?></div>
              <div class="collapsible-body">
                <table>
                  <thead>
                    <tr>
                      <th>Jurusan</th>
                      <th>Angkatan</th>
                      <th>Rincian</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($jurusan as $j):?>
                      <?php foreach($angkatan as $a):?>
                        <tr>
                          <td><?=$j->nama?></td>
                          <td><?=$a->angkatan?></td>
                          <td id="rincian-<?=$r->kode?>-<?=$j->kode?>-<?=$a->angkatan?>">
                            <div class="progress">
                                <div class="indeterminate"></div>
                            </div>                            
                          </td>
                        </tr>
                      <?php endforeach?>
                    <?php endforeach?>
                  </tbody>
                </table>
              </div>
            </li>
          <?php endforeach?>
        </ul>
      </div>    
    </div>
  </div>
</main>

<?php
$this->load->view('umum/footer');
?>

<script>
  var get_rincian = function(ta, jurusan, angkatan){
    var url = '?d=admin&c=dashboard&m=muat_rincian';
    var data = {
      ta: ta,
      jurusan: jurusan,
      angkatan: angkatan
    }
    $.post(url, data, function(hasil) {
      console.log(hasil);
      var selector = '#rincian-'+ ta + '-' + jurusan + '-' + angkatan;
      var txt_terbuka = '<div class="white-text chip green">'+ hasil.terbuka + ' terbuka</div>';
      var txt_terkunci = '<div class="white-text chip red">'+ hasil.terkunci+ ' terkunci</div>';
      var txt_berkas = '<div class="chip">'+ hasil.jml_berkas+ ' berkas</div>';
      var link_detail = '<a href="?d=admin/data&c=ta&m=detail&ta='+ ta +'&angkatan='+ angkatan +'&jurusan_kode='+ jurusan +
                        '" class="btn-flat btn-info tooltipped" data-position="top" data-tooltip="Lihat rincian"> \
                          <i class="material-icons">open_in_new</i> \
                        </a>';
      var txt =  txt_terbuka + txt_terkunci + txt_berkas + link_detail;
      $(selector).html(txt);
      
      // aktifkan tooltip
      $('.tooltipped').tooltip();
    });
  }
  $(function() {

    <?php foreach($ta as $r):?>
      <?php foreach($jurusan as $j):?>
        <?php foreach($angkatan as $a):?>
          get_rincian('<?=$r->kode?>', '<?=$j->kode?>', '<?=$a->angkatan?>');
        <?php endforeach?>
      <?php endforeach?>
    <?php endforeach?>


  });
</script>