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

        <!-- Two Factor Authentication -->
        <div class="row">
          <div class="col-lg-4"></div>
          <div class="col-lg-4">
            <div class="card mb-4">
              <div class="card-header hbcolor">Two Factor Authentication</div>
                <div class="card-body"><br>

                  <!-- Error Message -->
                  <?php if($this->session->flashdata('error')) {
                    echo '<div class="col-md-12 alert alert-danger">';
                    echo '<strong>'.$this->session->flashdata('error').'</strong>';
                    echo '</div><br>';
                  }?>
                  <!-- Error Message -->
                  <!-- Success Message -->
                  <?php if($this->session->flashdata('success')) {
                    echo '<div class="col-md-12 alert alert-success">';
                    echo '<strong>'.$this->session->flashdata('success').'</strong>';
                    echo '</div><br>';
                  }?>
                  <!-- Success Message -->

                  <!-- Message -->
                  <ul>
                    <li>NOTE: You are valid to access this feature until you logout your session!</li>
                  </ul>
                  <p class="center">Please verify by entering your 4 digit pin. To access <b class="app_color"><?php echo $name; ?></b></p>
                  <!-- Message -->

                  <!-- Authentication form-->
                  <form action="<?php echo base_url(); ?>auth/twofactorauthentication" method="post">

                    <!-- Url & code -->
                    <input type="hidden" id="url" name="url" value="<?php echo $url; ?>" />
                    <input type="hidden" id="code" name="code" value="<?php echo $code; ?>" />
                    <!-- Url & code -->

                    <!-- Pin -->
                    <div class="form-row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="small mb-1" for="password">Two Factor Authentication Pin (4 digit)</label>
                          <input type="password" name="password" id="password" class="form-control py-4" placeholder="****" required>
                        </div>
                      </div>
                    </div>
                    <!-- Pin-->

                    <!-- Submit Button -->
                    <div class="form-group mt-4 mb-0 center">
                      <input type="submit" name="submit" id="submit" value="Verify" class="btn btn-primary">
                    </div><br>
                    <!-- Submit Button -->

                  </form>
                  <!-- Authentication form-->

                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4"></div>
        </div>
        <!-- Two Factor Authentication -->

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