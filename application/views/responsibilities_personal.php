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

      <!-- Personal Responsibilities -->
      <div class="card-body bcolor">

        <!-- Breadcrumb -->
        <div class="card-header breadcrumb_header">
          <button class="btn btn-icon mr-2 breadcrumb_header_left app_heading_bc" onclick="personal_responsibilities_list();" id="personal_responsibilities_list"><i class="fa fa-eye"></i></button>
          <b class="app_color fs15">SET MONTHLY RESPONSIBILITIES</b>
					<button class="btn btn-icon mr-2 breadcrumb_header_right app_heading_bc" onclick="personal_responsibilities_add();" id="personal_responsibilities_add"><i class="fa fa-plus"></i></button>
				</div>
        <div class="pb5"></div>
        <!-- Breadcrumb -->

        <!-- Response -->
        <div id="personal_responsibilities_response"></div>
        <!-- Response -->

      </div>
      <!-- Personal Responsibilities -->

    </div>
    <!-- Center Content -->

  </div>
  <!-- Main Content -->

  <!-- Scripts  -->
  <?php include_once("includes/footerscripts.php"); ?>
  <!-- Scripts  -->

  <!-- Calculate the html page height -->
  <script>
    /** Load default function */
    personal_responsibilities_list();
  </script>
  <!-- Calculate the html page height -->

</body>
</html>