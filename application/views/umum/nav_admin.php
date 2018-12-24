  <!-- Menu khusus admin -->
  <ul id="nav-mobile" class="sidenav sidenav-fixed collapsible collapsible-accordion">
    <li><center><img src="assets/img/<?=get_konfig('LOGO')?>?x=<?=string_acak(10)?>" alt="" class="logo_institusi"></center></li>
    <li><a href="?d=admin&c=dashboard" class=" waves-effect waves-green">Beranda</a></li>
    <li>
      <a href="#" class="collapsible-header waves-effect waves-green" tabindex="0">Data</a>
      <div class="collapsible-body">
        <ul>
          <li><a href="?d=admin/data&c=siswa">- Siswa</a></li>
          <li><a href="?d=admin/data&c=ta">- Tahun Akademik</a></li>
        </ul>
      </div>
    </li>
    <li><a href="<?=site_url('?d=admin&c=konfig')?>" class="waves-effect waves-green" tabindex="0">Pengaturan Sistem</a></li>
    <li><a href="<?=site_url('?d=admin&c=profile')?>" class="waves-effect waves-green" tabindex="0">Akun Saya</a></li>
    <li><a href="#" class="waves-effect waves-green btn-keluar" tabindex="0">Keluar</a></li>
  </ul>

</header>