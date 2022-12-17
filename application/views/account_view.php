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

				<!-- Make sure event exist -->
				<?php if(isset($error)) { ?>
					<div class="card-header breadcrumb_header">
						<a class="btn btn-blue btn-icon mr-2 breadcrumb_header_left" href="<?php echo base_url(); ?>accounts"><i class="fa fa-arrow-left" ></i></a>
						<b class="app_color"><?php echo $error; ?></b>
					</div>
				<?php } else { ?>
				<!-- Make sure event exist -->

					<!-- Breadcrumb -->
					<div class="card-header breadcrumb_header">

						<!-- Go Back Icon -->
						<a class="btn btn-blue btn-icon mr-2 breadcrumb_header_left" href="<?php echo base_url(); ?>accounts"><i class="fa fa-arrow-left" ></i></a>
						<!-- Go Back Icon -->

						<!-- Title -->
						<b class="app_color"><?php echo $account['account_name']; ?></b>
						<!-- Title -->

						<!-- Add Account Icon -->
						<button class="btn btn-blue btn-icon mr-2 breadcrumb_header_right" data-toggle="modal" data-target="#add_account_transaction_modal"><i class="fa fa-plus"></i></button>
						<!-- Add Account Icon -->

						<!-- Add Account Modal -->
						<div class="modal fade" id="add_account_transaction_modal" tabindex="-1" role="dialog" aria-labelledby="add_account_transaction_modal_title" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">

									<!-- Header -->
									<div class="modal-header">
										<h5 class="modal-title center" id="add_account_transaction_modal_title"><b class="app_color">ADD TRANSACTION</b></h5>
										<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
									</div>
									<!-- Header -->

									<!-- Body -->
									<div class="modal-body">

										<!-- Add Account Form -->
										<form id="add_account_transaction_form">
											<!-- Error status -->
											<div id="add_account_transaction_status"></div>
											<!-- Error status -->

											<!-- Select Transaction Date | Transaction Type -->
											<div class="form-row">
												<div class="form-group col-xs-6">
													<input type="date" name="date" id="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required/>
												</div>
												<div class="form-group col-xs-6">
													<select class="form-control" name="type" id="type">
														<option value="CREDIT" selected>Credit (+)</option>
														<option value="DEBIT">Debit (-)</option>
													</select>
												</div>
											</div>
											<!-- Select Transaction Date | Transaction Type -->

											<!-- Select Title | Amount -->
											<div class="form-row">
												<div class="form-group col-md-6">
													<textarea name="title" id="title" class="form-control" placeholder="Transaction details" required></textarea>
												</div>
												<div class="form-group col-md-6">
													<input type="number" name="amount" id="amount" class="form-control" placeholder="Amount" required/>
												</div>
											</div>
											<!-- Select Title | Amount -->

											<!-- Submit Button -->
											<div class="form-group center">
												<input class="btn save_button" type="submit" id="add_account_transaction" value="SAVE">
												<button class="btn cancel_button" type="button" data-dismiss="modal">CANCEL</button>
											</div>
											<!-- Submit Button -->

											<!-- Expand button text -->
											<a href="#" onclick="expandInfo();" class="app_color_blue decoration_none">what is the meaning of credit/debit?</a>
											<!-- Expand button text -->

										</form>
										<!-- Add Account Form -->

										<!-- Information -->
										<div class="card-body display_none pt5" id="add_account_transactions_info">
											<table class="table table-bordered table-hover" width="100%" cellspacing="0">
												<tbody>
													<tr class="app_heading_bc"><td><b>MEANING OF CREDIT</b></td></tr>
													<tr class="fleft"><td><b>1. AMOUNT YOU NEED TO RECEIVE</b></td></tr>
													<tr class="fleft"><td><b>2. AMOUNT YOU RECEIVED</b></td></tr>
													<tr class="app_heading_bc"><td><b>MEANING OF DEBIT</b></td></tr>
													<tr class="fleft"><td><b>1. AMOUNT YOU NEED TO PAY</b></td></tr>
													<tr class="fleft"><td><b>2. AMOUNT YOU PAID</b></td></tr>
												</tbody>
											</table>
										</div>
										<!-- Information -->

									</div>
									<!-- Body -->

								</div>
							</div>
						</div>
						<!-- Add Account Modal -->
					</div>
					<div class="pb5"></div>
					<!-- Breadcrumb -->

					<!-- Load account trasactions -->
					<div id="load_account_transactions"></div>
					<!-- Load account trasactions -->

					<!-- Load account trasactions overview -->
					<div id="load_account_transactions_overview"></div>
					<!-- Load account trasactions overview -->

				<?php } ?>
				<!-- Assuming event exist -->

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
    /** Add Account Transaction */
    $("#add_account_transaction").click(function(e) {
      /** Init var */
      var response = "";
      e.preventDefault();

      /** Build data */
      var dateParam 	= $("#date").val();
			var typeParam 	= $("#type").val();
			var titleParam 	= $("#title").val();
			var amountParam = $("#amount").val();
      var dataString 	= 'date='+dateParam+'&type='+typeParam+'&title='+titleParam+'&amount='+amountParam;

      /** ajax call */
      $.ajax({
        type:'POST',
        data:dataString,
        url:'<?php echo base_url(); ?>account/view/<?php echo $account['uuid']; ?>/transactions/add',
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
            document.getElementById("add_account_transaction_form").reset();
						/** load list of accounts */
						load_account_transactions("<?php echo $account['uuid']; ?>");
          }
        },
        complete:function() {
          /** Hide spinner */
          $(".se-pre-con").hide();
					if(response == "success") {
						/** load html */
						$('#add_account_transaction_status').fadeIn().html(get_success_string("Success", "Successfully Saved!"));
					} else {
        		/** load html */
						$('#add_account_transaction_status').fadeIn().html(get_error_string("Error", response));
					}
					/** close success or error msg */
					close_alert_message();
        }
      });
    });

		/** Load required functions */
		load_account_transactions("<?php echo $account['uuid']; ?>");
		load_account_transactions_overview("<?php echo $account['uuid']; ?>");
	});

	/** expand info */
	function expandInfo()
	{
		/** get element */
  	var infoDiv = document.getElementById("add_account_transactions_info");

		/** append style */
		if (infoDiv.style.display === "block") {
			infoDiv.style.display = "none";
		} else {
			infoDiv.style.display = "block";
		}
	}
	</script>
	<!-- Custom Scripts -->

	</script>
	<!-- Custom Scripts -->

</body>
</html>