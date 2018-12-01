<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="./assets/css/icon.css">
  <!-- <link rel="stylesheet" href="./assets/css/materialize.min.css"> -->
  <link rel="stylesheet" href="./assets/css/ghpages-materialize.css">
  <link rel="stylesheet" href="./assets/css/tampilan.css">
  <link rel="stylesheet" href="./assets/plugins/waitme/waitMe.min.css">
  <title>Desain - Index</title>
</head>
<body>      
  <header>

    <?php if($this->session->akses !== null): ?>
    <nav class="top-nav">
      <div class="container">
        <div class="nav-wrapper">
          <div class="row">
            <div class="col s12 m10 offset-m1">
              <h1 class="header hide-on-small-only">Sisfo Akademik Sekolah</h1>
            </div>
          </div>
        </div>
      </div>
    </nav>
    <div class="container">
      <a href="#" data-target="nav-mobile" class="top-nav sidenav-trigger full hide-on-large-only"><i class="material-icons">menu</i></a>
    </div>
    <?php endif?>
  
    <?php $this->load->view('umum/nav_' . $this->session->akses)?>