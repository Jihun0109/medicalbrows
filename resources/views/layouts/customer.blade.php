<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>
  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="layout-top-nav layout-navbar-fixed" style="height:auto;">
  <div class="wrapper">
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
      <div class="container"><a href="index3.html" class="navbar-brand">
          <img src="{{ asset('img/logo.png') }}" alt="" class="brand-image" style="opacity: 0.8;">
          <span class="brand-text font-weight-light"></span>
        </a>
        {{-- <button type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler order-1">
          <span class="navbar-toggler-icon"></span>
        </button> --}}
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
          <li class="nav-item">
            <a data-widget="control-sidebar" data-slide="true" href="#" class="nav-link">
              <i class="fas fa-th-large"></i>
            </a>
          </li>
        </ul>
      </div>
    </nav>
    <div class="content-wrapper" style="min-height: 763.359px;">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container">
          <div class="row mb-2">
            <div class="col-sm-12 d-flex flex-column">
              <div class="d-flex justify-content-center">Clinic</div>
              <div class="d-flex justify-content-center"><h3 class="m-0 text-dark">メディカルブロ一のご予約</h3></div>
            </div>
            {{-- <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Layout</a></li>
                <li class="breadcrumb-item active">Top Navigation</li>
              </ol>
            </div> --}}
          </div>
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content" style="background-color:white;">
        <div class="container" id="app">
          <router-view></router-view>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
  </div>
</body>

</html>