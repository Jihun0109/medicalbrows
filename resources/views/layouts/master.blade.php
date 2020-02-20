<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title> {{ config('app.name', 'Medical Brows') }} </title>

  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="hold-transition layout-fixed layout-navbar-fixed" id="body">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        {{-- <a class="nav-link" data-toggle="control-sidebar" href="#"><i class="fas fa-bars"></i></a> --}}
        
      </li>      
    </ul>

    <!-- SEARCH FORM -->
    <!-- <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form> -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
      @guest
      @else
      <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        ログアウト
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
      @endguest
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4 " >
    <!-- Brand Logo -->
    <a href="#" class="brand-link  d-flex justify-content-center">
        <!-- <img src="{{ asset('img/logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8"> -->
    <span class="brand-text font-weight-light ">{{ config('app.name', 'Medical Brows') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex flex-row-reverse">
        <div class="image ml-3">
        <!-- <img src="{{asset('img/admin.png')}}" class="img-circle elevation-2" alt="User Image"> -->
        </div>
        <div class="info">
        <a href="#" class="d-block float-right">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

            <li class="nav-item clickable">
                <!-- <router-link to="/reservations" class="nav-link"> -->
                <a href="/admin/reservations"  class="nav-link">
                  <i class="nav-icon fas fa-calendar-alt"></i>
                  <p>予約登録 . 変更</p>
                </a>
              </li>
            @can('isAdmin')
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                設定
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                {{-- <li class="nav-item"><a href="/admin/settings-clinic" class="nav-link"><p>クリニック管理</p></a></li>
                <li class="nav-item"><a href="/admin/settings-rank" class="nav-link"><p>ランク管理</p></a></li>
                <li class="nav-item"><a href="/admin/settings-staff" class="nav-link"><p>スタッフ管理</p></a></li>
                <li class="nav-item"><a href="/admin/settings-staff-rank" class="nav-link"><p>スタッフ ランク管理</p></a></li>
                <li class="nav-item"><a href="/admin/settings-menu" class="nav-link"><p>メニュー管理</p></a></li>
                <li class="nav-item"><a href="/admin/settings-tax" class="nav-link"><p>税率管理</p></a></li>
                <li class="nav-item"><a href="/admin/settings-shift" class="nav-link"><p>シフト管理</p></a></li>
                <li class="nav-item"><a href="/admin/settings-rank-schedule" class="nav-link"><p>ランクスケジュール管理</p></a></li>
                <li class="nav-item"><a href="/admin/settings-account" class="nav-link"><p>アカウント管理</p></a></li> --}}
                <li class="nav-item clickable"><router-link to="/admin/settings-clinic" class="nav-link"><p>クリニック管理</p></a></li>
                <li class="nav-item clickable"><router-link to="/admin/settings-rank" class="nav-link"><p>ランク管理</p></a></li>
                <li class="nav-item clickable"><router-link to="/admin/settings-staff" class="nav-link"><p>スタッフ管理</p></a></li>
                <li class="nav-item clickable"><router-link to="/admin/settings-staff-rank" class="nav-link"><p>スタッフ ランク管理</p></a></li>
                <li class="nav-item clickable"><router-link to="/admin/settings-menu" class="nav-link"><p>メニュー管理</p></a></li>
                <li class="nav-item clickable"><router-link to="/admin/settings-tax" class="nav-link"><p>税率管理</p></a></li>
                <li class="nav-item clickable"><router-link to="/admin/settings-shift" class="nav-link"><p>シフト管理</p></a></li>
                <li class="nav-item clickable"><router-link to="/admin/settings-rank-schedule" class="nav-link"><p>ランクスケジュール管理</p></a></li>
                <li class="nav-item clickable"><router-link to="/admin/settings-account" class="nav-link"><p>アカウント管理</p></a></li>
                <li class="nav-item clickable"><router-link to="/admin/settings-operable-part" class="nav-link"><p>施術可能部位管理</p></a></li>
                {{-- <li class="nav-item"><router-link to="/admin/settings-order" class="nav-link"><p>order 管理</p></a></li> --}}
            </ul>
          </li>
          <li class="nav-item clickable">
            <!-- <router-link to="/admin/logs" class="nav-link"> -->
            <a href="/admin/logs"  class="nav-link">
              <i class="nav-icon fas fa-file-code"></i>
              <p>
                ログ管理
              </p>
            </a>
          </li>
          @endcan
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid" id="content">
        <router-view></router-view>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->
  <footer class="main-footer d-flex justify-content-center">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2020 <a href="/">medicalbrows.com</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->


<!-- REQUIRED SCRIPTS -->
<script src="{{ asset('js/app.js') }}" defer></script>

@auth
<script>
  window.user = @json(auth()->user());
</script>
@endauth
</body>
</html>
