<!-- Edit Modal -->
<div class="modal fade" id="edit_account_modal_<?php echo $account['uuid']; ?>" tabindex="-1" role="dialog" aria-labelledby="edit_account_modal_title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="edit_account_modal_title"><b class="app_color">Update Your Account</b></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <!-- Header -->

      <!-- Body -->
      <div class="modal-body">

        <!-- Success/error message -->
        <div id="edit_account_status_<?php echo $account['uuid']; ?>"></div>
        <!-- Success/error message -->

        <!-- Update Param Form -->
        <form>

          <!-- Account Name | Friend -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <input type="text" name="account_name_<?php echo $account['uuid']; ?>" id="account_name_<?php echo $account['uuid']; ?>" class="form-control" value="<?php echo $account['account_name']; ?>" required/>
            </div>
            <div class="form-group col-md-6">
              <select class="form-control" name="friend_uuid_<?php echo $account['uuid']; ?>" id="friend_uuid_<?php echo $account['uuid']; ?>">
                <option value="" selected>Select Friend</option>
                <?php if(!empty($friends)) { ?>
                  <?php foreach($friends as $friend) { ?>
                    <?php if($account['friend_uuid'] == $friend['friend_uuid']) { ?>
                      <option value="<?php echo $friend['friend_uuid']; ?>" selected><?php echo $friend['_friend']['name']."( ".$friend['_friend']['mobile']." )"?></option>
                    <?php } else { ?>
                      <option value="<?php echo $friend['friend_uuid']; ?>"><?php echo $friend['_friend']['name']."( ".$friend['_friend']['mobile']." )"?></option>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <!-- Account Name | Friend -->

          <!-- Submit Button -->
          <div class="form-group center">
            <input type="submit" class="btn save_button" id="update_account_<?php echo $account['uuid']; ?>" value="SAVE" >
            <button type="button" class="btn cancel_button" data-dismiss="modal">CANCEL</button><br>
          </div>
          <!-- Submit Button -->

        </form>
        <!-- Param Form -->

      </div>
      <!-- Body -->

    </div>
  </div>
</div>
<!-- Edit Modal -->

<!-- Delete Modal -->
<div class="modal fade" id="delete_account_modal_<?php echo $account['uuid']; ?>" tabindex="-1" role="dialog" aria-labelledby="delete_account_modal_title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="delete_account_modal_title"><b class="app_color">Delete Confirmation</b></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <!-- Header -->

      <!-- Body -->
      <div class="modal-body">

        <!-- Delete Form -->
        <form>

          <!-- Message -->
          <h6><b>Are you sure you want to delete this account (<?php echo $account['account_name']; ?>) ?</b></h6>
          <!-- Message -->

          <!-- Submit Button -->
          <div class="form-group center">
            <input type="submit" class="btn yes_button" id="delete_account_<?php echo $account['uuid']; ?>" value="YES">
            <button type="button" class="btn no_button" data-dismiss="modal">NO</button>
          </div>
          <!-- Submit Button -->

        </form>
        <!-- Delete Form -->

      </div>
      <!-- Body -->

    </div>
  </div>
</div>
<!-- Delete Modal -->

<!-- Edit & Delete Modal Scripts -->
<script type="text/javascript">
/** Update Account */
$("#update_account_<?php echo $account['uuid']; ?>").click(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** Build data */
  var accountNameParam = $("#account_name_<?php echo $account['uuid']; ?>").val();
  var friendUuidParam = $("#friend_uuid_<?php echo $account['uuid']; ?>").val();
  var dataString = 'account_name='+accountNameParam+'&friend_uuid='+friendUuidParam;

  /** ajax call */
  $.ajax({
    type:'POST',
    data:dataString,
    url:'<?php echo base_url(); ?>account/update/<?php echo $account['uuid']; ?>',
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** append data to response  */
      response = data;
      /** on success load all required methods */
      if(data == "success") {
        /** close modal */
        $('#edit_account_modal_<?php echo $account['uuid']; ?>').modal('hide');
        $(".modal-backdrop").remove();
        $('body').removeClass('modal-open');
        $('body').removeAttr( 'style' );
        /** Load required functions */
        load_accounts();
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response != "success") {
        /** load html */
        $('#edit_account_status_<?php echo $account['uuid']; ?>').fadeIn().html(get_error_string("Error", response));
        /** close success or error msg */
        close_alert_message();
      }
    }
  });
});

/** Delete Account */
$("#delete_account_<?php echo $account['uuid']; ?>").click(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** ajax call */
  $.ajax({
    type:'POST',
    data:'',
    url:'<?php echo base_url(); ?>account/delete/<?php echo $account['uuid']; ?>',
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** append data to response  */
      response = data;
      /** on success load all required methods */
      if(data == "success") {
        /** close modal */
        $('#delete_account_modal_<?php echo $account['uuid']; ?>').modal('hide');
        $(".modal-backdrop").remove();
        $('body').removeClass('modal-open');
        $('body').removeAttr( 'style' );
        /** Load required functions */
        load_accounts();
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response != "success") {
        /** load html */
        $('#delete_account_status_<?php echo $account['uuid']; ?>').fadeIn().html(get_error_string("Error", response));
        /** close success or error msg */
        close_alert_message();
      }
    }
  });
});
</script>
<!-- Edit & Delete Modal Scripts -->