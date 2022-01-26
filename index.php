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

  <title>asset gateway</title>

  <link href="dist/css/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
  <link href="dist/css/redmond/jquery-ui.css" rel="stylesheet"/>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="dist/css/ionicons.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <!-- bootgrid -->
  <link rel="stylesheet" href="plugins/bootgrid/jquery.bootgrid.css">
  <!-- \jsgrid -->
  <link rel="stylesheet" href="plugins/jsgrid/jsgrid.css">
  <link rel="stylesheet" href="plugins/jsgrid/jsgrid-theme.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins//toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="dist/css/fontgoogle.css">
  <!-- select2 -->
  <link rel="stylesheet" href="dist/css/select2.css">
  <link rel="stylesheet" href="dist/css/loading-clock.css">
  
  <!-- DataTable -->
  <link rel="stylesheet" type="text/css" href="dist/DataTables/datatables.min.css"/>
 
  
  <style>

  /* cursor pointer */
.cursor-pointer {
  cursor: pointer;
}
  </style>
    <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/jquery-ui/jquery-ui.js"></script> 
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- DataTables -->
  <script src="plugins//datatables/jquery.dataTables.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
  <!-- Toastr -->
  <script src="plugins/toastr/toastr.min.js"></script>
  <!-- select2 App -->
  <script src="dist/js/select2.full.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <script type="text/javascript">
    $(window).keydown(function(event){
      if(event.keyCode == 13) {
        event.preventDefault();
        return false;
      }
    });
  </script>
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper"  >

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container" style="height:30px;">
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container">
        <?php
        $m = isset($_GET['m']) ? $_GET['m']: "";

        if(empty($m)){
          include "view/a1.php";
        } else {
          if (file_exists("view/".$m.".php")) {
              include "view/".$m.".php";
          } else {
              include "view/404.php";
          }
          // include $m.".php";
        }
        ?>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
<!--   <aside class="control-sidebar control-sidebar-dark">
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside> -->
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<div class="loading-clock"></div>

</body>
</html>
