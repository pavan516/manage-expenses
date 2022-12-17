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

      <!-- Friends -->
      <div class="card-body bcolor">

				<!-- Menus -->
				<div class="card-header breadcrumb_header_1">
					<!-- Load friends -->
					<button class="btn btn-blue btn-icon mr-2 breadcrumb_header_left" onclick="load_friends_view();" id="friends"><b><i class="fas fa-user-friends"></i></b></button>
					<!-- Load friends -->
					<!-- Load followers -->
					<button class="btn btn-blue btn-icon mr-2 friends_breadcrumb_header_left1" onclick="load_followers_view();" id="followers"><b><i class="fa fa-users"></i></b></button>
					<!-- Load followers -->
					<!-- Load friend requests -->
					<button class="btn btn-blue btn-icon mr-2 friends_breadcrumb_header_right1" onclick="load_friend_requests();" id="requests"><b><i class="fa fa-user-times"></i></b></button>
					<!-- Load friend requests -->
					<!-- Load search -->
					<button class="btn btn-blue btn-icon mr-2 breadcrumb_header_right" onclick="load_search_view();" id="search"><b><i class="fa fa-user-plus"></i></b></button>
					<!-- Load search -->
				</div>
				<div class="pb5"></div>
				<!-- Menus -->

				<!-- Response -->
				<div id="friends_response"></div>
				<!-- Response -->

      </div>
      <!-- Friends -->

    </div>
    <!-- Center Content -->

  </div>
  <!-- Main Content -->

  <!-- Scripts  -->
  <?php include_once("includes/footerscripts.php"); ?>
  <!-- Scripts  -->

	<!-- Load Required Functons -->
	<script>
	$(document).ready(function (e) {
		var tab = "<?php echo $tab; ?>";
		/** Load required functions */
		if(tab == "followers") {
			/** loads followers view */
			load_followers_view("<?php echo $search; ?>");
		} else if(tab == "requests") {
			/** loads friend requests */
			load_friend_requests("<?php echo $search; ?>");
		} else if(tab == "search") {
			/** loads search view */
			load_search_view("<?php echo $search; ?>");
		} else {
			/** loads friends view */
			load_friends_view("<?php echo $search; ?>");
		}
	});
	</script>
	<!-- Load Required Functons -->

</body>
</html>