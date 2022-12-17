<!DOCTYPE html>
<html lang="en">

  <!-- Head  -->
  <?php include_once("includes/head.php"); ?>
  <!-- Head  -->

<!-- Body -->
<body class="nav-fixed">

	<!-- Top Navbar -->
	<?php include_once("includes/topnavbar.php"); ?>
	<!-- Top Navbar -->

	<!-- Main Content -->
	<div id="layoutSidenav">

		<!-- Sidebar -->
		<?php include_once("includes/sidebar.php"); ?>
		<!-- Sidebar -->

    <!-- Center Content -->
    <div id="layoutSidenav_content">

      <!-- Event not found -->
      <div class="card-body bcolor">

        <!-- Breadcrumb -->
        <div class="card-header breadcrumb_header">
          <a class="btn btn-blue btn-icon mr-2 breadcrumb_header_left" href="<?php echo base_url(); ?>events?mode=<?php echo $old_par_mode; ?>&status=<?php echo $old_par_status; ?>&type=<?php echo $old_par_type; ?>&search=<?php echo $old_par_search; ?>"><i class="fa fa-arrow-left" ></i></a>
          <b class="app_color">EVENT NOT FOUND</b>
        </div>
        <div class="pb5"></div>
        <!-- Breadcrumb -->

      </div>
      <!-- Event not found -->

    </div>
    <!-- Center Content -->

  </div>
  <!-- Main Content -->

  <!-- Scripts  -->
  <?php include_once("includes/footerscripts.php"); ?>
  <!-- Scripts  -->

</body>
</html>