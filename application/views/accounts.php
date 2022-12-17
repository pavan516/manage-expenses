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
      <div class="card-body bcolor">

        <!-- Breadcrumb -->
        <div class="card-header breadcrumb_header">

					<!-- Go Back Icon -->
          <a class="btn btn-blue btn-icon mr-2 breadcrumb_header_left" href="#" onclick="accountsSearch()"><i class="fa fa-search" ></i></a>
          <!-- Go Back Icon -->

					<!-- Title -->
					<b class="app_color">USER DEBITS / CREDITS</b>
					<!-- Title -->

					<!-- Add Account Icon -->
					<button class="btn btn-blue btn-icon mr-2 breadcrumb_header_right" data-toggle="modal" data-target="#add_account_modal"><i class="fa fa-plus"></i></button>
					<!-- Add Account Icon -->

					<!-- Add Account Modal -->
					<div class="modal fade" id="add_account_modal" tabindex="-1" role="dialog" aria-labelledby="add_account_modal_title" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">

								<!-- Header -->
								<div class="modal-header">
									<h5 class="modal-title" id="add_account_modal_title center"><b class="app_color">ADD ACCOUNT</b></h5>
									<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
								</div>
								<!-- Header -->

								<!-- Body -->
								<div class="modal-body">

									<!-- Add Account Form -->
									<form id="add_account_form">
										<!-- Error status -->
										<div id="add_account_status"></div>
										<!-- Error status -->

										<!-- Select Friend | Account-Name -->
										<div class="form-row">
											<div class="form-group col-md-6">
												<input type="text" name="account_name" id="account_name" class="form-control" placeholder="Account Name" required/>
											</div>
											<div class="form-group col-md-6">
												<select class="form-control" name="friend_uuid" id="friend_uuid">
													<option value="" selected>SELECT ACCOUNT</option>
													<?php if(!empty($friends)) { ?>
														<?php foreach($friends as $friend) { ?>
															<option value="<?php echo $friend['friend_uuid']; ?>"><?php echo $friend['_friend']['name']."( ".$friend['_friend']['mobile']." )"?></option>
														<?php } ?>
													<?php } ?>
												</select>
											</div>
										</div>
										<!-- Select Friend | Account-Name -->

										<!-- Submit Button -->
										<div class="form-group center">
											<input type="submit" class="btn save_button" id="addaccount" value="SAVE" >
											<button type="button" class="btn cancel_button"	data-dismiss="modal">CANCEL</button>
										</div>
										<!-- Submit Button -->

									</form>
									<!-- Add Account Form -->

								</div>
								<!-- Body -->

							</div>
						</div>
					</div>
					<!-- Add Account Modal -->
				</div>
        <div class="pb5"></div>
        <!-- Breadcrumb -->

				<!-- Search Form -->
				<form id="accounts_search" class="<?php if(empty($search)) echo "display_none"; ?>">
					<div class="form-row">
						<div class="form-group col-md-12 mb05em">
							<div class="input-group">
								<?php if(!empty($search)) { ?>
									<input type="search" class="form-control py-2" value="<?php echo $search; ?>" id="search" required>
								<?php } else { ?>
									<input type="search" class="form-control py-2" placeholder="search" id="search" required>
								<?php } ?>
								<span class="input-group-append">
									<button type="submit" class="btn btn-primary search_icon" id="search_accounts_form"><i class="fa fa-search"></i></button>
								</span>
							</div>
						</div>
					</div>
				</form>
				<!-- Search Form -->

				<!-- Search script -->
				<script>
					/** Load search Accounts */
					$("#search_accounts_form").click(function(e)
					{
						/** prevent default event */
						e.preventDefault();

						/** Parameters */
						var searchparam = $("#search").val();

						/** Load function */
						load_accounts(searchparam);
					});
				</script>
				<!-- Search script -->

				<!-- Load accounts -->
				<div id="load_accounts"></div>
				<!-- Load accounts -->

      </div>
    </div>
    <!-- Center Content -->

  </div>
  <!-- Main Content -->

  <!-- Scripts  -->
  <?php include_once("includes/footerscripts.php"); ?>
  <!-- Scripts  -->

  <!-- Custom Scripts -->
  <script type="text/javascript">
  $(document).ready(function()
  {
    /** Add Account */
    $("#addaccount").click(function(e) {
      /** Init var */
      var response = "";
      e.preventDefault();

      /** Build data */
      var accountName = $("#account_name").val();
			var friendUuid 	= $("#friend_uuid").val();
      var dataString 	= 'account_name='+accountName+'&friend_uuid='+friendUuid;

      /** ajax call */
      $.ajax({
        type:'POST',
        data:dataString,
        url:'<?php echo base_url(); ?>accounts/add',
        beforeSend: function() {
          /** Show loader */
          $(".se-pre-con").show();
        },
        success: function(data) {
          /** append data to response  */
          response = data;
          /** on success load all required methods */
          if(data == "success") {
						/** reset form */
            document.getElementById("add_account_form").reset();
						/** load list of accounts */
						load_accounts();
          }
        },
        complete:function() {
          /** Hide spinner */
          $(".se-pre-con").hide();
					if(response == "success") {
						/** modal related */
						$('#add_account_modal').modal('hide');
						$(".modal-backdrop").remove();
						$('body').removeClass('modal-open');
					} else {
        		/** load html */
						$('#add_account_status').fadeIn().html(get_error_string("Error", response));
						/** close success or error msg */
						close_alert_message();
					}
        }
      });
    });

		/** Load required functions */
		load_accounts();
	});

	/** accounts search */
	function accountsSearch()
	{
		/** get element */
  	var searchDiv = document.getElementById("accounts_search");

		/** append style */
		if (searchDiv.style.display === "block") {
			searchDiv.style.display = "none";
		} else {
			searchDiv.style.display = "block";
		}
	}
	</script>
	<!-- Custom Scripts -->

</body>
</html>