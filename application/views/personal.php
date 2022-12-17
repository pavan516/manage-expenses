<!DOCTYPE html>
<html lang="en">

  <!-- Load common head part -->
  <?php include_once("includes/head.php"); ?>
  <!-- Load common head part -->

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

    <!-- Loaded page -->
    <?php $page = $page ?? $this->input->get('page') ?? "monthly"; ?>
    <!-- Loaded page -->

    <!-- Center Content -->
    <div id="layoutSidenav_content">

      <!-- Personal -->
      <div class="card-body bcolor">

        <!-- Breadcrumb -->
        <div class="card-header breadcrumb_header">

					<!-- Overview Modal -->
          <button class="btn btn-icon mr-2 breadcrumb_header_left" onclick="load_overview();" id="overview"><i class="fa fa-eye"></i></button>
          <!-- Overview Modal -->

					<!-- Title -->
					<b class="app_color hsize">PERSONAL EXPENSES</b>
					<!-- Title -->

					<!-- Add personal expenses -->
					<button class="btn btn-icon mr-2 breadcrumb_header_right" onclick="load_add();" id="add"><i class="fa fa-plus"></i></button>
					<!-- Add personal expenses -->

				</div>
        <div class="pb5"></div>
        <!-- Breadcrumb -->

        <!-- Menus -->
        <div class="card-header personal_menus">

					<!-- Day to day -->
          <button class="btn btn-icon mr-2 personal_menu_1" onclick="load_daytoday();" id="daytoday"><b>D</b></button>
          <!-- Day to day -->

					<!-- Monthly -->
          <button class="btn btn-icon mr-2 personal_menu_2" onclick="load_monthly();" id="monthly"><b>M</b></button>
          <!-- Monthly -->

					<!-- Yearly -->
          <button class="btn btn-icon mr-2 personal_menu_3" onclick="load_yearly();" id="yearly"><b>Y</b></button>
          <!-- Yearly -->

					<!-- Responsibilities -->
          <button class="btn btn-icon mr-2 personal_menu_4" onclick="load_responsibilities();" id="responsibilities"><b><i class="fa fa-tasks"></i></b></button>
          <!-- Responsibilities -->

					<!-- Add personal expenses -->
					<button class="btn btn-icon mr-2 personal_menu_5" onclick="load_custom();" id="custom"><b><i class="fa fa-search"></i></b></button>
					<!-- Add personal expenses -->

				</div>
        <div class="pb5"></div>
        <!-- Menus -->

        <!-- Response -->
        <div id="personal_expenses_response"></div>
        <!-- Response -->

      </div>
      <!-- Personal -->

    </div>
    <!-- Center Content -->

  </div>
  <!-- Main Content -->

  <!-- Scripts  -->
  <?php include_once("includes/footerscripts.php"); ?>
  <!-- Scripts  -->

  <!-- Custom Scripts - Get Profile -->
  <script type="text/javascript">
  $(document).ready(function()
  {
    /** By default load monthly expenses */
    load_monthly();
  });
  </script>
  <!-- Custom Scripts - Get Profile -->

</body>
</html>