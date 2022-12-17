<!-- Verify account -->
<?php if($user['verified'] == 0) { ?>
  <div class="col-md-12 pr0em pl0em pb5">
    <div class="card">
      <div class="card-header hbcolor">Verify Email (ACCOUNT)</div>
      <div class="card-body text-center">
        <!-- Verify account status -->
        <div id="verify_account_status"></div>
        <!-- Verify account status -->
        <!-- send mail -->
        <form id="send_mail_to_verify_account">
          <!-- Submit Button -->
          <div class="form-group mt-4 mb-0 center">
            <input type="submit" name="submit" value="Send Verification Link" class="btn save_button app_size">
          </div><br>
          <!-- Submit Button -->
        </form>
        <!-- send mail -->
      </div>
    </div>
  </div>
  <div class="pb5">
<?php } ?>
<!-- Verify account -->

<!-- Profile Picture-->
<div class="col-md-12 pr0em pl0em pb5">
  <div class="card">
    <div class="card-header hbcolor">Profile Picture</div>
    <div class="card-body text-center pb15">

      <!-- Profile picture image-->
      <?php if(!empty($user['image'])) { ?>
        <img class="img-account-profile rounded-circle mb-2" src="<?php echo base_url().$this->config->item('user_images').$user['image']; ?>" alt />
      <?php } else { ?>
        <img class="img-account-profile rounded-circle mb-2" src="<?php echo base_url().$this->config->item('user_images'); ?>default.jpg" alt />
      <?php } ?>
      <!-- Profile picture image-->

      <!-- Profile picture help block-->
      <div class="small font-italic text-muted mb-2">Only JPG | JPEG | PNG Square Media Files Are Allowed</div>
      <!-- Profile picture help block-->

      <!-- Profile picture upload button-->
      <button class="btn save_button app_size" type="button" data-toggle="modal" data-target="#uploadimagemodal" >Upload new image</button>
      <!-- Profile picture upload button-->
    </div>
  </div>
</div>
<!-- Profile Picture-->

<!-- Profile Picture Modal -->
<div class="modal fade" id="uploadimagemodal" tabindex="-1" role="dialog" aria-labelledby="uploadimagemodalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="uploadimagemodalTitle"><b class="app_color">UPLOAD IMAGE</b></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <!-- Header -->

      <!-- Error response -->
      <div id="uploadimageerror"></div>
      <!-- Error response -->

      <!-- Body -->
      <div class="modal-body">
        <!-- Upload Image Form -->
        <form id="uploadimageform" enctype="multipart/form-data">
          <!-- Image-->
          <div class="form-row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="small mb-1" for="image">Image</label>
                <input type="file" name="image" id="image" class="form-control" required>
              </div>
            </div>
          </div>
          <!-- Image-->
          <!-- Submit Button -->
          <div class="form-group mb-0 center">
            <input type="submit" class="btn create_button"name="submit" value="UPLOAD">
            <button type="button" class="btn cancel_button" data-dismiss="modal">CANCEL</button>
          </div>
          <!-- Submit Button -->
        </form>
        <!-- Upload Image Form -->
      </div>
      <!-- Body -->

    </div>
  </div>
</div>
<!-- Profile Picture Modal -->

<!-- Change Password -->
<div class="col-md-12 pr0em pl0em pb5">
  <div class="card">
    <div class="card-header hbcolor">Change Password</div>
    <div class="card-body">

      <!-- Error response -->
      <div id="changepassworderror"></div>
      <!-- Error response -->

      <!-- Change Password Form -->
      <form id="changepasswordform">
        <!-- Old Password -->
        <div class="form-group">
          <label class="small mb-1" for="old_pass">Old Password</label>
          <input type="password" name="old_pass" id="old_pass" class="form-control" placeholder="******" required/>
        </div>
        <!-- Old Password -->
        <!-- New | Repeat Password-->
        <div class="form-row">
          <div class="form-group col-md-6">
            <label class="small mb-1" for="new_pass">New Password</label>
            <input type="password" name="new_pass" id="new_pass" class="form-control" placeholder="******" required/>
          </div>
          <div class="form-group col-md-6">
            <label class="small mb-1" for="repeat_pass">Repeat Password</label>
            <input type="password" name="repeat_pass" id="repeat_pass" class="form-control" placeholder="******" required/>
          </div>
        </div>
        <!-- New | Repeat Password-->
        <!-- Submit Button -->
        <div class="form-group mb-0 center pb10">
          <input type="submit" name="submit" value="Change Password" class="btn save_button app_size">
        </div>
        <!-- Submit Button -->
      </form>
      <!-- Change Password Form -->

    </div>
  </div>
</div>
<!-- Change Password -->

<!-- Account Details -->
<div class="col-md-12 pr0em pl0em">
  <div class="card mb-4">
    <div class="card-header hbcolor">Account Details</div>
    <div class="card-body">

      <!-- Error / Success -->
      <div id="account_details_status"></div>
      <!-- Error / Success -->

      <!-- Acount Details Form -->
      <form id="accountdetailsform">
        <!-- Select Country | Name | Code -->
        <div class="form-row">
          <div class="form-group col-md-4">
            <label class="small mb-1" for="country_id">Select Country</label>
            <select class="form-control" name="country_id" id="country_id">
              <?php
              if(!empty($countries)) {
                foreach($countries as $country) {
                  if($user['country_id'] == $country['id']) { ?>
                    <option value="<?php echo $country['id']; ?>" selected><?php echo $country['name']; ?></option>
                  <?php } else { ?>
                    <option value="<?php echo $country['id']; ?>"><?php echo $country['name']; ?></option>
                  <?php }
                }
              }?>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label class="small mb-1" for="name">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo $user['name']; ?>" required/>
          </div>
          <div class="form-group col-md-4">
            <label class="small mb-1" for="code">Code</label>
            <input type="text" name="code" id="code" class="form-control" value="<?php echo $user['code']; ?>" disabled/>
          </div>
        </div>
        <!-- Select Country | Name | Code -->
        <!-- Email | Mobile | Dob -->
        <div class="form-row">
          <div class="form-group col-md-4">
            <label class="small mb-1" for="email">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo $user['email']; ?>" required/>
          </div>
          <div class="form-group col-md-4">
            <label class="small mb-1" for="mobile">Mobile Number</label>
            <input type="number" name="mobile" id="mobile" class="form-control" value="<?php echo $user['mobile']; ?>" required/>
          </div>
          <div class="form-group col-md-4">
            <label class="small mb-1" for="dob">Date Of Birth</label>
            <input type="date" name="dob" id="dob" class="form-control" value="<?php echo $user['dob']; ?>" required/>
          </div>
        </div>
        <!-- Email | Mobile | Dob -->
        <!-- Submit Button -->
        <div class="form-group mb-0 center pn10">
          <input type="submit" name="submit" value="Update Details" class="btn save_button app_size">
        </div>
        <!-- Submit Button -->
      </form>
      <!-- Acount Details Form -->

    </div>
  </div>
</div>
<!-- Account Details -->

<!-- Scripts -->
<script type="text/javascript">

/**
 * Verify Account
 */
$("#send_mail_to_verify_account").on('submit',(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** ajax call */
  $.ajax({
    url: "<?php echo base_url(); ?>auth/account/verify/sendmail",
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
      /** load html */
      $('#verify_account_status').fadeIn().html(get_success_string("Info", response));
    }
  });
}));

/**
 * Upload Image
 */
$("#uploadimageform").on('submit',(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** ajax call */
  $.ajax({
    url: "<?php echo base_url(); ?>profile/image/upload",
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
      /** on success load all required methods */
      if(data == "success") {
        /** Load required functions */
        profile_profile();
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response == "success") {
        /** close modal */
        $('#uploadimagemodal').modal('hide');
        $(".modal-backdrop").remove();
        $('body').removeClass('modal-open');
        $('body').removeAttr( 'style' );
      } else {
        /** load html */
        $('#uploadimageerror').fadeIn().html(get_error_string("Error", response));
      }
    }
  });
}));

/**
 * Change Password
 */
$("#changepasswordform").on('submit',(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** ajax call */
  $.ajax({
    url: "<?php echo base_url(); ?>auth/change/password",
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
      if(response != "success") {
        /** load html */
        $('#changepassworderror').fadeIn().html(get_error_string("Error", response));
        /** close success or error msg */
        close_alert_message();
      } else {
        top.location.href="<?php echo base_url(); ?>auth/logout"; // redirection
      }
    }
  });
}));

/**
 * Update account details
 */
$("#accountdetailsform").on('submit',(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** ajax call */
  $.ajax({
    url: "<?php echo base_url(); ?>profile/details/upload",
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
        $('#account_details_status').fadeIn().html(get_success_string("Success", "Successfully Saved!"));
      } else {
        /** load html */
        $('#account_details_status').fadeIn().html(get_error_string("Error", response));
      }
      /** close success or error msg */
      close_alert_message();
    }
  });
}));
</script>
<!-- Scripts -->