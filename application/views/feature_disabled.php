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
      <div class="card-body bcolor webkit_box">

        <!-- Feature Disabled Status -->
        <div class="row">
          <div class="col-lg-4"></div>
          <div class="col-lg-4">
            <div class="card mb-4">
              <div class="card-header hbcolor">FEATURE STATUS</div>
                <div class="card-body">

                  <!-- Message -->
                  <p class="center">Please enable this feature to access<br><b class="app_color"><?php echo $name; ?></b>.</p>
                  <!-- Message -->

                  <!-- Message -->
                  <ul>
                    <li>GO TO <a href="<?php echo base_url()."settings"; ?>" class="app_color">SETTINGS</a></li>
                    <li>GO TO ENABLE/DISABLE FEATURES TAB</li>
                    <li>ENABLE FEATURE <b><?php echo $name; ?></b></li>
                  </ul>
                  <!-- Message -->

                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4"></div>
        </div>
        <!-- Feature Disabled Status -->

      </div>
    </div>
    <!-- Center Content -->

  </div>
  <!-- Main Content -->

  <!-- Scripts  -->
  <?php include_once("includes/footerscripts.php"); ?>
  <!-- Scripts  -->

</body>
</html>