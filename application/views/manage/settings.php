<!-- Settings Page -->
<div class="row">

  <!-- Left Side -->
  <div class="col-lg-8 pr0em pl0em">

    <!-- Feature update -->
    <div class="card mb-2">
      <div class="card-header hbcolor">Show (enable) | Hide (disable) Features</div>
      <div class="card-body">

        <!-- Success/Error Message -->
        <div id="feature_settings_status"></div>
        <!-- Success/Error Message -->

        <!-- Feature fom -->
        <form id="updatefeatureform">

          <!-- Personal Feature -->
          <div class="card card-header-actions mb-2">
            <div class="card-header hbcolor bcWhite cblack"> Personal Financial Tracker Feature
              <div class="custom-control custom-switch mt-n2">
                <input type="checkbox" name="feature_personal" id="feature_personal" class="custom-control-input" <?php if($user['feature_personal'] == 1) echo "checked"; ?>/>
                <label class="custom-control-label" for="feature_personal"></label>
              </div>
            </div>
          </div>
          <!-- Personal Feature -->

          <!-- Events Feature -->
          <div class="card card-header-actions mb-2">
            <div class="card-header hbcolor bcWhite cblack"> Events Financial Tracker Feature
              <div class="custom-control custom-switch mt-n2">
                <input type="checkbox" name="feature_events" id="feature_events" class="custom-control-input" <?php if($user['feature_events'] == 1) echo "checked"; ?>/>
                <label class="custom-control-label" for="feature_events"></label>
              </div>
            </div>
          </div>
          <!-- Events Feature -->

          <!-- Accounts Feature -->
          <div class="card card-header-actions mb-2">
            <div class="card-header hbcolor bcWhite cblack"> Accounts Management Feature
              <div class="custom-control custom-switch mt-n2">
                <input type="checkbox" name="feature_accounts" id="feature_accounts" class="custom-control-input" <?php if($user['feature_accounts'] == 1) echo "checked"; ?>/>
                <label class="custom-control-label" for="feature_accounts"></label>
              </div>
            </div>
          </div>
          <!-- Accounts Feature -->

          <!-- Submit Button -->
          <div class="form-group center mb0em">
            <input type="submit" name="submit" id="submit" value="SAVE" class="btn save_button">
          </div>
          <!-- Submit Button -->

        </form>
        <!-- Feature fom -->

      </div>
    </div>
    <!-- Feature update -->

    <!-- Security update -->
    <div class="card mb-2">
      <div class="card-header hbcolor">Add Security To Features</div>
      <div class="card-body">

        <!-- Success/Error Message -->
        <div id="update_security_status"></div>
        <!-- Success/Error Message -->

        <!-- Show form only pin is set -->
        <?php if(!empty($user['pin'])) { ?>

          <!-- Security fom -->
          <form id="updatesecurityform">

            <!-- Personal Feature -->
            <div class="card card-header-actions mb-2">
              <div class="card-header hbcolor bcWhite cblack"> Personal Financial Tracker Feature
                <div class="custom-control custom-switch mt-n2">
                  <input type="checkbox" name="security_personal" id="security_personal" class="custom-control-input" <?php if($user['security_personal'] == 1) echo "checked"; ?>/>
                  <label class="custom-control-label" for="security_personal"></label>
                </div>
              </div>
            </div>
            <!-- Personal Feature -->

            <!-- Events Feature -->
            <div class="card card-header-actions mb-2">
              <div class="card-header hbcolor bcWhite cblack"> Events Financial Tracker Feature
                <div class="custom-control custom-switch mt-n2">
                  <input type="checkbox" name="security_events" id="security_events" class="custom-control-input" <?php if($user['security_events'] == 1) echo "checked"; ?>/>
                  <label class="custom-control-label" for="security_events"></label>
                </div>
              </div>
            </div>
            <!-- Events Feature -->

            <!-- Accounts Feature -->
            <div class="card card-header-actions mb-2">
              <div class="card-header hbcolor bcWhite cblack"> Accounts Management Feature
                <div class="custom-control custom-switch mt-n2">
                  <input type="checkbox" name="security_accounts" id="security_accounts" class="custom-control-input" <?php if($user['security_accounts'] == 1) echo "checked"; ?>/>
                  <label class="custom-control-label" for="security_accounts"></label>
                </div>
              </div>
            </div>
            <!-- Accounts Feature -->

            <!-- Profile Feature -->
            <div class="card card-header-actions mb-2">
              <div class="card-header hbcolor bcWhite cblack"> Account Profile
                <div class="custom-control custom-switch mt-n2">
                  <input type="checkbox" name="security_profile" id="security_profile" class="custom-control-input" <?php if($user['security_profile'] == 1) echo "checked"; ?>/>
                  <label class="custom-control-label" for="security_profile"></label>
                </div>
              </div>
            </div>
            <!-- Profile Feature -->

            <!-- Friends -->
            <div class="card card-header-actions mb-2">
              <div class="card-header hbcolor bcWhite cblack"> Friends Management
                <div class="custom-control custom-switch mt-n2">
                  <input type="checkbox" name="security_friends" id="security_friends" class="custom-control-input" <?php if($user['security_friends'] == 1) echo "checked"; ?>/>
                  <label class="custom-control-label" for="security_friends"></label>
                </div>
              </div>
            </div>
            <!-- Friends -->

            <!-- Submit Button -->
            <div class="form-group center mb0em">
              <input type="submit" name="submitsecurity" id="submitsecurity" value="SAVE" class="btn save_button">
            </div>
            <!-- Submit Button -->

          </form>
          <!-- Security fom -->

        <?php } else { ?>
          <h1 class="center app_color">Please set the pin to Enable/Disable security on features!</h1>
        <?php } ?>

      </div>
    </div>
    <!-- Security update -->

  </div>
  <!-- Left Side -->

  <!-- Right side -->
  <div class="col-lg-4 pr0em pl0em">

    <!-- Two-Factor Authentication -->
    <div class="card mb-2">
      <div class="card-header hbcolor">Two-Factor Authentication</div>
      <div class="card-body">

        <!-- Success/Error Message -->
        <div id="update_pin_status"></div>
        <!-- Success/Error Message -->

        <p>
          &nbsp&nbsp&nbsp&nbsp&nbsp
          Add another level of security to your features by enabling two-factor authentication.
          If security is enabled on any feature.
          That feature can be viewed/used only by entering the 4 digit pin.
          By enabling two-factor authentication, your personal details will be hidden from an third eye.
        </p>
        <!-- two factor authentication form -->
        <form id="updatepinform">
          <!-- Password & Pin -->
          <div class="form-group">
            <label class="small mb-1" for="password">Current Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="******" required/>
          </div>
          <div class="form-group">
            <label class="small mb-1" for="twoFactorSMS">Set/Change Authentication PIN (4 digit pin)</label>
            <input type="number" name="pin" id="pin" class="form-control" placeholder="****" required/>
          </div>
          <!-- Password & Pin -->
          <!-- Submit Button -->
          <div class="form-group center mb0em">
            <input type="submit" name="submitpin" id="submitpin" value="SAVE" class="btn btn-primary save_button">
          </div>
          <!-- Submit Button -->
        </form>
        <!-- two factor authentication form -->
      </div>
    </div>
    <!-- Two-Factor Authentication -->

    <!-- Delete Account -->
    <div class="card mb-2">
      <div class="card-header hbcolor" >Delete Account</div>
      <div class="card-body center">
        <p>Deleting your account is a permanent action and cannot be undone. If you are sure you want to delete your account, select the button below.</p>
        <button class="btn btn-danger-soft text-danger" type="button" data-toggle="modal" data-target="#deleteusermodal">I understand, delete my account</button>
      </div>
    </div>
    <!-- Delete Account -->

  </div>
  <!-- Right side -->

</div>
<!-- Settings Page -->

<!-- User delete Modal -->
<div class="modal fade" id="deleteusermodal" tabindex="-1" role="dialog" aria-labelledby="deleteusermodalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="deleteusermodalTitle"><b class="app_color">Delete Account Confirmation</b></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <!-- Header -->

      <!-- Body -->
      <div class="modal-body">

        <!-- Update Param Form -->
        <form action="<?php echo base_url(); ?>auth/delete" method="post">

          <!-- Message -->
          <h6><b>Are you sure you want to delete your account permanently?</b></h6>
          <!-- Message -->

          <!-- Submit Button -->
          <div class="form-group center mb0em">
            <input type="submit" value="YES" class="btn btn-primary yes_button">
            <button class="btn btn-primary no_button" type="button" data-dismiss="modal">NO</button>
          </div>
          <!-- Submit Button -->

        </form>
        <!-- Param Form -->

      </div>
      <!-- Body -->

    </div>
  </div>
</div>
<!-- User delete Modal -->

<!-- Scripts -->
<script type="text/javascript">
/**
 * feature settings
 */
$("#updatefeatureform").on('submit',(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** ajax call */
  $.ajax({
    url: "<?php echo base_url(); ?>settings/feature",
    type: "POST",
    data:  new FormData(this),
    contentType: false,
    cache: false,
    processData:false,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** append data to response  */
      response = data;
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response == "success") {
        /** load html */
        $('#feature_settings_status').fadeIn().html(get_success_string("Success", "Successfully Saved!"));
      } else {
        /** load html */
        $('#feature_settings_status').fadeIn().html(get_error_string("Error", response));
      }
      /** close success or error msg */
      close_alert_message();
    }
  });
}));

/**
 * Update pin
 */
$("#updatepinform").on('submit',(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** ajax call */
  $.ajax({
    url: "<?php echo base_url(); ?>settings/updatepin",
    type: "POST",
    data:  new FormData(this),
    contentType: false,
    cache: false,
    processData:false,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** append data to response  */
      response = data;
      /** load required functions */
      if(data == "success") {
        /** reset form */
        document.getElementById('updatepinform').reset();
        profile_settings();
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response == "success") {
        /** load html */
        $('#update_pin_status').fadeIn().html(get_success_string("Success", "Successfully Saved!"));
      } else {
        /** load html */
        $('#update_pin_status').fadeIn().html(get_error_string("Error", response));
      }
      /** close success or error msg */
      close_alert_message();
      /** reload page */
      window.location.reload();
    }
  });
}));

/**
 * Update security
 */
$("#updatesecurityform").on('submit',(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** ajax call */
  $.ajax({
    url: "<?php echo base_url(); ?>settings/security",
    type: "POST",
    data:  new FormData(this),
    contentType: false,
    cache: false,
    processData:false,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** append data to response  */
      response = data;
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response == "success") {
        /** load html */
        $('#update_security_status').fadeIn().html(get_success_string("Success", "Successfully Saved!"));
      } else {
        /** load html */
        $('#update_security_status').fadeIn().html(get_error_string("Error", response));
      }
      /** close success or error msg */
      close_alert_message();
    }
  });
}));
</script>
<!-- Scripts -->