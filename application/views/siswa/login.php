<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('umum/header');
?>
  <center>
  <div class="container">
    <div class="section">
      <img src="./assets/img/<?=get_konfig('LOGO')?>" alt="">
      <h3 class="grey-text">Login Siswa</h3>
    </div>
    <div class="card grey lighten-4 padding-r-4 padding-l-4 width-4">
      <div class="section">
        <form class="col s12" method="post" action="<?=site_url('?c=auth&m=submit_login_siswa')?>">
            <div class="row">
              <div class="col s12">
            </div>
          </div>

          <div class="row">
            <div class="input-field col s12">
              <input type="text" name="nisn" id="nisn" class="validate" class="validate" required="">
              <label for="nisn" class="">Masukkan NISN</label>
            </div>
          </div>
          
          <div class="row">
            <div class="input-field col s12">
              <input class="validate" type="password" name="password" id="password" class="validate" class="validate" required="">
              <label for="password" class="">Masukkan Password</label>
            </div>         
          </div>
          <?=$this->session->pesan?>
          <p>&nbsp;</p>
          
          <div class="row">
            <button type="submit" name="btn_login" class="col s12 btn btn-large waves-effect blue">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="height-1"></div>
  </center>

  <footer class="page-footer" style="padding-top: 0; padding-left: 0;">
    <div class="footer-copyright">
      <div class="container center-align" style="margin:0; width: 100%; max-width: 100%">
        &copy; <a class="orange-text text-lighten-3" href="#" target="_blank">SMK Mamba'ul Jadid</a> - 2018
      </div>
    </div>
  </footer>

  <!--  Scripts-->
  <script src="./assets/js/jquery-3.3.1.min.js"></script>
  <script src="./assets/js/materialize.min.js"></script>

  </body>
</html>