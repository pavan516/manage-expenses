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
    <div id="layoutSidenav_content" class="bcolor">

			<!-- Card -->
			<div class="card bcolor pt5">

				<!-- Heading -->
				<div class="card-header hbcolor"><i class="fa fa-bell"></i> NOTIFICATIONS
					<?php $marl_all_as_read = false; ?>
					<?php if(!empty($data)) {
					 	foreach($data as $item) {
							if($item['status'] == 0) {
								$marl_all_as_read = true;
								break;
							}
						}
					} ?>
					<?php if($marl_all_as_read) { ?>
						<a class="noti_mark_all" href="javascript:void(0);" onclick="return updateNotifications();">mark all as read</a>
					<?php } else if(!empty($data)) { ?>
						<a class="noti_mark_all" href="javascript:void(0);" onclick="return updateNotifications('delete_all');">remove all</a>
					<?php } ?>
				</div>
				<!-- Heading -->

				<!-- Notifications -->
				<div class="card-body noti_card_body" id="notifications_height">

					<!-- notifications -->
					<?php $count = 1; ?>
					<?php if(!empty($data)) { ?>
  					<?php foreach($data as $item) { ?>

							<!-- User Info -->
							<div class="align-items-center justify-content-between <?php if($item['status']==0) echo 'noti_bcolor'; ?>">
								<div class="d-flex align-items-center flex-shrink-0 mr05em">
									<div class="avatar avatar-xl mr05em bg-gray-200">
										<img class="avatar-img noti_image" src="<?php echo base_url().$this->config->item('user_images').$item['_sender']['image']; ?>" alt />
									</div>
									<div class="d-flex flex-column font-weight-bold position_relative">
										<a class="noti_title" href="<?php echo base_url().$item['source_url']; ?>"><?php echo $item['title']; ?></a>
										<a class="decoration_none noti_text" href="<?php echo base_url().$item['source_url']; ?>"><?php echo $item['message']; ?></a>
									</div>
								</div>
        			</div>
        			<!-- User Info -->

							<!-- Add Line -->
							<?php if($count != count($data)) echo "<div class='noti_hr'></div>"; ?>
							<?php $count++ ?>
							<!-- Add Line -->

						<?php } ?>
					<?php } else { ?>
						<div class="center"><b>All notifications are read</b></div>
					<?php } ?>

				</div>
				<!-- Notification -->

			</div>
			<!-- Card -->

		</div>
		<!-- Center Content -->

  </div>
  <!-- Main Content -->

  <!-- Scripts  -->
  <?php include_once("includes/footerscripts.php"); ?>
  <!-- Scripts  -->

	<!-- Calculate the html page height -->
	<script>
	var datatableScrollY = $(window).height() * 0.18;
	document.getElementById("notifications_height").style.maxHeight = "calc(100vh - "+datatableScrollY+"px)";
	</script>
	<!-- Calculate the html page height -->

</body>
</html>