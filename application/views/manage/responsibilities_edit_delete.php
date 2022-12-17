<!-- Edit Modal -->
<div class="modal fade" id="personal_responsibilities_edit_delete_modal_<?php echo $pitem['uuid']; ?>" tabindex="-1" role="dialog" aria-labelledby="personal_responsibilities_edit_delete_title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="personal_responsibilities_edit_delete_title"><b class="app_color">Update | Delete</b></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <!-- Header -->

      <!-- Body -->
      <div class="modal-body">

        <!-- Success/error message -->
        <div id="personal_responsibilities_status_<?php echo $pitem['uuid']; ?>"></div>
        <!-- Success/error message -->

        <!-- Edit Responsibility Form -->
        <form>

          <!-- Uuid -->
          <input type="hidden" id="uuid_<?php echo $pitem['uuid']; ?>" value="<?php echo $pitem['uuid']; ?>"/>
          <!-- Uuid -->

          <!-- Select Type | Text | Value -->
          <div class="form-row">
            <div class="form-group col-md-12">
              <select class="form-control" id="type_<?php echo $pitem['uuid']; ?>">
                <?php $types = ["INCOME","EXPENSES","INVESTMENT"];
                foreach($types as $type) {
                  if($type == $pitem['type']) {?>
                    <option value="<?php echo $type; ?>" selected><?php echo $type; ?></option>
                  <?php } else { ?>
                    <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            </div>
            <div class="form-group col-md-12">
              <textarea id="title_<?php echo $pitem['uuid']; ?>" class="form-control" required><?php echo $pitem['title']; ?></textarea>
            </div>
            <div class="form-group col-md-12">
              <input type="number" id="value_<?php echo $pitem['uuid']; ?>" class="form-control" value="<?php echo $pitem['value']; ?>" required/>
            </div>
          </div>
          <!-- Select Type | Text | Value -->

          <!-- Submit Button -->
          <div class="form-group center">
            <input  type="submit" class="btn save_button" id="update_responsibility_<?php echo $pitem['uuid']; ?>" value="SAVE" >
            <button type="button" class="btn cancel_button" data-dismiss="modal">CANCEL</button><br>
          </div>
          <!-- Submit Button -->

        </form>
        <!-- Edit Responsibility Form -->

        <!-- Break line -->
        <hr>
        <!-- Break line -->

        <!-- Delete Responsibility Form -->
        <form class="pb5">

          <!-- Uuid -->
          <input type="hidden" id="uuid_<?php echo $pitem['uuid']; ?>" value="<?php echo $pitem['uuid']; ?>"/>
          <!-- Uuid -->

          <!-- Message -->
          <h6 class="center"><b>Do you want to delete this?</b></h6>
          <!-- Message -->

          <!-- Submit Button -->
          <div class="form-group center">
            <input  type="submit" class="btn yes_button" id="delete_responsibility_<?php echo $pitem['uuid']; ?>" value="YES" >
            <button type="button" class="btn no_button" data-dismiss="modal">NO</button>
          </div>
          <!-- Submit Button -->

        </form>
        <!-- Delete Responsibility Form -->

      </div>
      <!-- Body -->

    </div>
  </div>
</div>
<!-- Edit Modal -->

<!-- Edit & Delete Modal Scripts -->
<script type="text/javascript">
/**
 * Update param
 */
$("#update_responsibility_<?php echo $pitem['uuid']; ?>").click(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** Build request body */
  var uuidparam   = $("#uuid_<?php echo $pitem['uuid']; ?>").val();
  var typeparam   = $("#type_<?php echo $pitem['uuid']; ?>").val();
  var titleparam  = $("#title_<?php echo $pitem['uuid']; ?>").val().replace("&", "and");
  var valueparam  = $("#value_<?php echo $pitem['uuid']; ?>").val();
  var dataString  = 'uuid='+uuidparam+'&type='+typeparam+'&title='+titleparam+'&value='+valueparam;

  /** ajax call */
  $.ajax({
    type:'POST',
    data:dataString,
    url:'<?php echo base_url(); ?>responsibilities/personal/update',
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** append data to response  */
      response = data;
      /** on success load all required methods */
      if(data == "success") {
        /** get color & arrow */
        var color = "app_color_green";
        var arrow = "fa fa-arrow-up";
        if(typeparam == "INVESTMENT") {
          color = "app_color_blue";
          arrow = "fa fa-arrow-down";
        } else if (typeparam == "EXPENSES") {
          color = "app_color_red";
          arrow = "fa fa-arrow-down";
        }
        /** update values */
        $("#responsibilities_<?php echo $pitem['uuid']; ?>").removeClass().addClass(color);
        document.getElementById("responsibilities_title_<?php echo $pitem['uuid']; ?>").innerHTML = titleparam;
        document.getElementById("responsibilities_value_<?php echo $pitem['uuid']; ?>").innerHTML = "<b>"+valueparam+" <i class='"+arrow+"'></i></b>";
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response == "success") {
        /** close the modal */
        $('#personal_responsibilities_edit_delete_modal_<?php echo $pitem['uuid']; ?>').modal('hide');
        $(".modal-backdrop").remove();
        $('body').removeClass('modal-open');
        $('body').removeAttr( 'style' );
      } else {
        /** load html */
        $('#personal_responsibilities_status_<?php echo $pitem['uuid']; ?>').fadeIn().html(get_error_string("Error", response));
        /** close success or error msg */
        close_alert_message();
      }
    }
  });
});

/**
 * Delete param
 */
$("#delete_responsibility_<?php echo $pitem['uuid']; ?>").click(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** build request data */
  var uuidparam = $("#uuid_<?php echo $pitem['uuid']; ?>").val();
  var dataString = 'uuid='+uuidparam;

  /** ajax call */
  $.ajax({
    type:'POST',
    data:dataString,
    url:'<?php echo base_url(); ?>responsibilities/personal/delete',
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** append data to response  */
      response = data;
      /** on success load all required methods */
      if(data == "success") {
        /** remove record */
        document.getElementById("responsibilities_<?php echo $pitem['uuid']; ?>").remove();
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response == "success") {
        /** close the modal */
        $('#personal_responsibilities_edit_delete_modal_<?php echo $pitem['uuid']; ?>').modal('hide');
        $(".modal-backdrop").remove();
        $('body').removeClass('modal-open');
        $('body').removeAttr( 'style' );
      } else {
        /** load html */
        $('#personal_responsibilities_status_<?php echo $pitem['uuid']; ?>').fadeIn().html(get_error_string("Error", response));
        /** close success or error msg */
        close_alert_message();
      }
    }
  });
});
</script>
<!-- Edit & Delete Modal Scripts -->