<footer class="page-footer" style="padding-top: 0">
    <div class="footer-copyright">
      <div class="container">
        &copy; <a class="orange-text text-lighten-3" href="#" target="_blank">SMK Mamba'ul Jadid</a> - 2018
      </div>
    </div>
  </footer>
  
  
  <!--  Scripts-->
  <script src="./assets/js/jquery-3.3.1.min.js"></script>
  <script src="./assets/js/materialize.min.js"></script>
  <script src="./assets/plugins/sweet-alert/sweetalert.min.js"></script>
  <script src="./assets/plugins/waitme/waitMe.min.js"></script>
  
  
  
  <div class="sidenav-overlay"></div>
  <div class="drag-target"></div>
  <script>
    $(function() {
      $('.collapsible').collapsible();
      $('.sidenav').sidenav();
      $('.modal').modal();
      $('select').formSelect();
      $('.tooltipped').tooltip();
      $('.chips').chips();
      // $('.dropdown-trigger').dropdown();

      // simple hack for select validation
      $("select[required]").css({
        display: 'inline',
        position: 'absolute',
        float: 'left',
        padding: 0,
        margin: 0,
        border: '1px solid rgba(255,255,255,0)',
        height: 0, 
        width: 0,
        top: '2em',
        left: '3em',
        opacity: 0
      })

      $(document).on('click', '.btn-keluar', function () {
        swal({
          title: "Keluar ",
          text: "Anda akan keluar dari sistem, anda yakin ?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((keluar) => {
          if (keluar) {
            document.location.href = '<?=site_url('?c=auth&m=logout')?>';
          }
        });
      });

    })
  </script>
</body>
</html>