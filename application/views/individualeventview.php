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

      <!-- Individual G.E -->
      <div class="card-body bcolor">

        <!-- Breadcrumb -->
        <div class="card-header breadcrumb_header">
          <a class="btn btn-icon mr-2 breadcrumb_header_left app_heading_bc" href="<?php echo base_url(); ?>events?mode=<?php echo $old_par_mode; ?>&status=<?php echo $old_par_status; ?>&type=<?php echo $old_par_type; ?>&search=<?php echo $old_par_search; ?>"><i class="fa fa-arrow-left" ></i></a>
          <b class="app_color"><?php echo $event['name']; ?></b>
					<button class="btn btn-icon mr-2 breadcrumb_header_right app_heading_bc" data-toggle="modal" data-target="#add_iee_modal"><i class="fa fa-plus"></i></button>
				</div>
        <div class="pb5"></div>
        <!-- Breadcrumb -->

        <!-- Load event expenses -->
        <div class="card-body ie_card_body">

          <!-- Tabs -->
          <div class="pb41">
            <button id="ie_overview" class="tablink <?php if($tab == "overview") echo "tab_active"; ?>" onclick="load_iee_overview('<?php echo $event['uuid']; ?>');"><b>OVERVIEW</b></button>
            <button id="ie_details" class="tablink <?php if($tab == "details") echo "tab_active"; ?>" onclick="load_iee_details('<?php echo $event['uuid']; ?>');"><b>DETAILS</b></button>
          </div>
          <!-- Tabs -->

          <!-- Individual event Response -->
          <div id="individual_event_response">
            <?php include_once('events/expenses/individual/overview.php'); ?>
          </div>
          <!-- Individual event Response -->

        </div>
        <!-- Load event expenses -->

      </div>
      <!-- Individual Expenses -->

    </div>
    <!-- Center Content -->

  </div>
  <!-- Main Content -->

  <!-- Add Individual Event Expenses -->
  <?php include_once('events/expenses/individual/add.php'); ?>
  <!-- Add Individual Event Expenses -->

  <!-- Scripts  -->
  <?php include_once("includes/footerscripts.php"); ?>
  <!-- Scripts  -->

</body>
</html>