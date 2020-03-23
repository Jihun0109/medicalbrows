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

<body class="layout-top-nav layout-navbar-fixed  layout-footer-fixed" style="height:auto;">
  <div class="wrapper">
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
      <div class="container"><a href="/" class="navbar-brand">
          <img src="{{ asset('img/logo.png') }}" alt="" class="brand-image" style="opacity: 0.8;">
          <span class="brand-text font-weight-light"></span>
        </a>
        {{-- <button type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler order-1">
          <span class="navbar-toggler-icon"></span>
        </button> --}}
        <ul class="navbar-nav navbar-no-expand ml-auto" style="flex-direction: row !important;">
          {{-- <li class="nav-item">
            <a href="/admin" class="nav-link">管理者ログイン</a>
          </li> --}}
          <li class="nav-item">
            <a data-widget="control-sidebar" data-slide="true" href="#" class="nav-link">
              <i class="fas fa-th-large"></i>
            </a>
          </li>
        </ul>
      </div>
    </nav>
    <div class="content-wrapper  d-flex flex-column">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container">
          <div class="row mb-2" > 
            <div class="col-sm-12 d-flex flex-column" style="position: relative;"> 
              <a id="btnCancelOrder" class="btn btn-primary order-cancelbtn" style="color:white;">キャンセルはこちら</a>             
              <div class="d-flex justify-content-center">Clinic</div>
              <div class="d-flex justify-content-center">
                <h3 class="m-0 text-dark">メディカルブロ一のご予約</h3>
              </div>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->     

      <!-- Main content -->
      <div class="content" style="background-color:white;">
        <div class="container pb-5" id="app">
          <router-view></router-view>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
        <div class="d-flex flex-column pt-3 pb-5" style="background-color:#f4f6f9;">
          <div class="container">
            <div class="d-flex justify-content-center">
              <small class="font-weight-lighter">全国共通ダイヤル</small>
              <h4 class="pl-2">0570-078-889</h4>
            </div>
            <div><span><small>［受付時間］平日・土日祝 9：00～20：00</small></span></div>
            <div><span><small>［診療時間］受付クリニックにより異なります。日・祝受付可能なクリニックもございます。</small></span></div>
          </div>
        </div>
      </div>
      <footer class="main-footer">
        <div class="container d-flex justify-content-center pb-2"><span><small>Copyrgiht &copy; Omotesando Medical
              Clinic All Right Reserved.</small></span></div>
        <div class="container d-flex justify-content-center" style="padding:0px;">
          <div class="col-12 btn-group" style="padding:0px;">
            <button type="button" class="col-6 btn btn-secondary btn-flat d-flex justify-content-center"
              style="font-weight:bold;white-space:normal;">
              <div class="d-flex flex-column"><span>ご予約お問い合わせ</span><i class="fas fa-envelope"></i></div>
            </button>
            <button type="button" class="col-6 btn btn-secondary btn-flat d-flex justify-content-center"
              style="font-weight:bold;white-space:normal;">
              <div class="d-flex flex-column"><span>お電話</span><i class="fas fa-phone"></i></div>
            </button>
            <button type="button" class="btn bg-gradient-default btn-flat" style="width:80px;background-color: #aaa;"><i
                class="fas fa-caret-up"></i></button>
          </div>
        </div>
    </div>
    </footer>
    <!-- /.content -->

  </div>

  </div>
</body>

</html>