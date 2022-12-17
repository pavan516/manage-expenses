<!DOCTYPE html>
<html lang="en">

<!-- Head -->
<head>

  <!-- Meta charset -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- Image Logo -->
  <link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/images/favicon.ico"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- Title -->
  <title itemprop="name">MANAGE EXPENSES - RESET PASSWORD</title>

  <!-- Images & SEO -->
  <meta property="og:image" content="<?php echo base_url(); ?>assets/images/og_image.png">
  <meta itemprop="image" content="<?php echo base_url(); ?>assets/images/image.png">
  <meta name="description" content="MANAGE EXPENSES, is a personal finance software that’s aimed to help you improve and manage your monthly budget by setting periodical goals. It's the easiest way to share and split the bills between your friends and family. It helps to create budgets and also assists you to track your debt. It allows you to track your expenses including all necessary or usual expenses like a cost of a trip or an event." />
  <meta name="keywords" content="manage expenses, expense manager, expense tracker, financial tracker, financial planner, finance manager, day to day expenses, daily expenses, day by day expenses, account manager, manage accounts, manage credits & debits, manage friends, family credits & debits, monthly expenses, monthly tracker, monthly financial tracker, monthly budgeting tool, monthly spending tracker, money manager, money tracker, budget manager, budget tracker, budget planner, house budget, split-share, manage expenses between friends, manage debits/credits between friends, event expenses manager, event expenses tracker, trip expenses tracker, trip expenses manager, split share, split amount, split amount among friends" />

  <!-- Bootstrap css -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/v2/bootstrap/css/bootstrap.min.css">

	<!-- Font-awesome icons cdn link -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- all types of css -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/v2/fonts/font-awesome-4.7.0.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/v2/fonts/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/v2/css/animate.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/v2/css/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/v2/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/v2/css/select2.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/v2/css/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/v2/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/v2/css/main.css">

  <!-- Jquery Script -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>

  <!-- Loader Gif -->
  <script>
    $(window).load(function() {
      $(".se-pre-con").fadeOut("slow");
    });
  </script>

</head>
<!-- Head -->

<!-- Body -->
<body>

	<!-- Include Loader -->
	<div class="se-pre-con"></div>
	<!-- Include Loader -->

	<!-- Container -->
	<div class="container-login100">
		<div class="wrap-login100 p-l-55 p-r-55 p-t-35 p-b-35">

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

			<!-- Login form-->
			<form action="<?php echo base_url(); ?>auth/forgotpassword/sendmail" method="post" class="login100-form validate-form">

				<!-- Heading -->
				<span class="login100-form-title p-b-37">Forgot Password</span>
				<!-- Heading -->

				<!-- Email -->
				<div class="wrap-input100 validate-input m-b-20" data-validate="enter email">
					<input class="input100" type="email" name="email" id="email" placeholder="email">
					<span class="focus-input100"></span>
				</div>
				<!-- Email -->

				<!-- Submit -->
				<div class="container-login100-form-btn">
					<input type="submit" name="submit" id="submit" value="Send Mail" class="login100-form-btn">
				</div>
				<!-- Submit -->

			</form>
			<!-- Login form-->

			<!-- Register / reset password here -->
			<div class="text-center p-t-20 p-b-50">
				<span class="txt1 cwhite">
          Do you remember your password? <a href="<?php echo base_url(); ?>auth/login"><b class="cwhite">LOGIN HERE</b></a>
				</span>
			</div>
			<!-- Register / reset password here -->

      <!-- Footer -->
      <div class="p-t-50 p-b-30">
				<span class="txt1">
          <div class="card-footer">
            <p><b>HOW IT WORKS?</b></p>
            <ul>
              <li>1. We generate a link to reset your password & send to your mail-id</li>
              <li>2. Please update your password through link
              <li>3. After update, please login to manage expenses & use our services!</li>
            </ul>
          </div>
				</span>
      </div>
      <!-- Footer -->

			<!-- List -->
			<div class="text-center">
				<b class="txt2 hov1">
					<a href="<?php echo base_url(); ?>terms-and-conditions">TERMS & CONDITIONS</a> |
					<a href="<?php echo base_url(); ?>privacy-policy">PRIVACY POLICY</a> |
					<a href="<?php echo base_url(); ?>about-us">ABOUT US</a> |
					<a href="<?php echo base_url(); ?>contact-us">CONTACT US</a>
				</b>
			</div>
			<!-- List -->

		</div>
	</div>
	<!-- Container -->

	<!-- Scripts -->
	<script src="<?php echo base_url(); ?>assets/v2/js/jquery-3.2.1.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/v2/js/animsition.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/v2/js/popper.js"></script>
	<script src="<?php echo base_url(); ?>assets/v2/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/v2/js/select2.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/v2/js/moment.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/v2/js/daterangepicker.js"></script>
	<script src="<?php echo base_url(); ?>assets/v2/js/countdowntime.js"></script>
	<script src="<?php echo base_url(); ?>assets/v2/js/main.js"></script>
	<!-- End of scripts -->

</body>
</html>