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

      <!-- User Profile -->
      <div class="card-body bcolor">
        <div class="card">

          <!-- Tabs -->
          <div class="card-header hbcolor">
            <ul class="nav nav-tabs card-header-tabs" id="cardTab" role="tablist">
              <li class="nav-item"><a class="nav-link active" id="overview-tab" href="#overview" data-toggle="tab" role="tab" aria-controls="overview" aria-selected="true">PROFILE</a></li>
              <li class="nav-item"><a class="nav-link" id="personal-tab" href="#personal" data-toggle="tab" role="tab" aria-controls="personal" aria-selected="false">PERSONAL</a></li>
              <li class="nav-item"><a class="nav-link" id="settings-tab" href="#settings" data-toggle="tab" role="tab" aria-controls="settings" aria-selected="false">SETTINGS</a></li>
            </ul>
          </div>
          <!-- Tabs -->

          <!-- Tab Contents -->
          <div class="card-body border_radius_0">
            <div class="tab-content" id="cardTabContent">

              <!-- Tab1 -->
              <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                <!-- Display Profile -->
                <div id="profile_profile"></div>
                <!-- Display Profile -->
              </div>
              <!-- Tab1 -->

              <!-- Tab2 -->
              <div class="tab-pane fade" id="personal" role="tabpanel" aria-labelledby="personal-tab">

                <!-- Add Monthly Responsibilities -->
                <div class="card card-collapsable">

                  <!-- Heading -->
                  <a class="card-header collapsed hbcolor" href="#collapseCardExample" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseCardExample">Add Monthly Responsibilities
                    <div class="card-collapsable-arrow">
                      <i class="fas fa-chevron-down"></i>
                    </div>
                  </a>
                  <!-- Heading -->

                  <!-- Card Body -->
                  <div class="collapse" id="collapseCardExample">
                    <div class="card-body">

                      <!-- Profile Personal -->
                      <div id="profile_personal_personal_add"></div>
                      <!-- Profile Personal -->

                    </div>
                  </div>
                  <!-- Card Body -->

                </div>
                <div class="pb5"></div>
                <!-- Add Monthly Responsibilities -->

                <!-- Show Monthly Responsibilities -->
                <div class="card card-collapsable">

                  <!-- Heading -->
                  <a class="card-header hbcolor" href="#showparams" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="showparams">Monthly Responsibilities
                    <div class="card-collapsable-arrow">
                      <i class="fas fa-chevron-down"></i>
                    </div>
                  </a>
                  <!-- Heading -->

                  <!-- Card Body -->
                  <div class="collapse show" id="showparams">
                    <div class="card-body">

                      <!-- Profile Personal -->
                      <div id="profile_personal_personal_list"></div>
                      <!-- Profile Personal -->

                      </div>
                  </div>
                  <!-- Card Body -->

                </div>
                <!-- Show Monthly Responsibilities -->

              </div>
              <!-- Tab2 -->

              <!-- Tab3 -->
              <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                <!-- Profile settings -->
                <div id="profile_settings"></div>
                <!-- Profile settings -->
              </div>
              <!-- Tab3 -->

            </div>
          </div>
          <!-- Tab Contents -->

        </div>
      </div>
      <!-- User Profile -->

    </div>
    <!-- Center Content -->

  </div>
  <!-- Main Content -->

  <!-- Custom Scripts - Get Profile -->
  <script type="text/javascript">
  $(document).ready(function()
  {
    /** Load required methods */
    profile_profile();
    profile_personal_personal_add();
    profile_personal_personal_list();
    profile_settings();
  });
  </script>
  <!-- Custom Scripts - Get Profile -->

  <!-- Scripts  -->
  <?php include_once("includes/footerscripts.php"); ?>
  <!-- Scripts  -->

</body>
</html>