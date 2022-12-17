<!-- Update Param Form -->
<form>

  <!-- Build date -->
  <?php if($item['month'] < 10) $item['month'] = "0".$item['month']; ?>
  <!-- Build date -->

  <!-- Select Type | Date -->
  <div class="form-row">
    <div class="form-group col-xs-6 width_50per">
      <select class="form-control select_input_fs" id="type">
        <?php $types = ["INCOME","EXPENSES","INVESTMENT"];
        foreach($types as $type) {
          if($type == $item['type']) {?>
            <option value="<?php echo $type; ?>" selected><?php echo $type; ?></option>
          <?php } else { ?>
            <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
          <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="form-group col-xs-6 width_50per">
      <input type="date" name="date" id="date" class="form-control select_input_fs" value="<?php echo $item['date']; ?>" required/>
    </div>
  </div>
  <!-- Select Type | Date -->

  <!-- Select Text | Value -->
  <div class="form-row">
    <div class="form-group col-md-12">
      <textarea id="title" class="form-control" required><?php echo $item['title']; ?></textarea>
    </div>
    <div class="form-group col-md-12">
      <input type="number" id="value" class="form-control" value="<?php echo $item['value']; ?>" required/>
    </div>
  </div>
  <!-- Select Text | Value -->

  <!-- Submit Button -->
  <div class="form-group center">
    <input class="btn save_button" type="submit" id="update_expenses" value="SAVE">
    <button class="btn cancel_button" type="button" data-dismiss="modal">CANCEL</button>
  </div>
  <!-- Submit Button -->

</form>
<!-- Param Form -->

<!-- Break line -->
<hr>
<!-- Break line -->

<!-- Delete Expenses Form -->
<form>

  <!-- Message -->
  <h6 class="center fs09rem"><b>Do you want to delete this transacion?</b></h6>
  <!-- Message -->

  <!-- Submit Button -->
  <div class="form-group center">
    <input class="btn yes_button" type="submit" id="delete_expenses" value="YES" >
    <button class="btn no_button" type="button" data-dismiss="modal">NO</button>
  </div>
  <!-- Submit Button -->

</form>
<!-- Delete Expenses Form -->

<!-- Edit & Delete Modal Scripts -->
<script type="text/javascript">

/** Update */
$("#update_expenses").click(function(e) {
  /** Init var */
  var response  = "";
  var page = "<?php echo $page; ?>";
  e.preventDefault();

  /** Build var */
  var uuidparam   = "<?php echo $item['uuid']; ?>";
  var typeparam   = $("#type").val();
  var dateparam   = $("#date").val();
  var titleparam  = $("#title").val().replace("&", "and");
  var valueparam  = $("#value").val();
  var dataString  = 'uuid='+uuidparam+'&date='+dateparam+'&type='+typeparam+'&title='+titleparam+'&value='+valueparam;

  /** Ajax call */
  $.ajax({
    type:'POST',
    data:dataString,
    url:'<?php echo base_url(); ?>personal/update',
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
        /** update record in datatable */
        $("#expenses_<?php echo $item['uuid']; ?>").removeClass().addClass(color);
        document.getElementById("expenses_date_<?php echo $item['uuid']; ?>").innerHTML = date_format(dateparam);
        document.getElementById("expenses_title_<?php echo $item['uuid']; ?>").innerHTML = titleparam;
        document.getElementById("expenses_value_<?php echo $item['uuid']; ?>").innerHTML = "<b>"+valueparam+" <i class='"+arrow+"'></i></b>";
      }
    },
    complete:function() {
      /** close modal or show error */
      if(response == "success") {
        /** modal related */
        $('#edit_delete_expenses_modal').modal('hide');
        $(".modal-backdrop").remove();
        $('body').removeClass('modal-open');
        /** sucess message */
        enable_message("success", "successfully saved!");
      } else {
        /** error message */
        enable_message("error", "something went wrong!");
      }
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
});

/** Delete */
$("#delete_expenses").click(function(e)
{
  /** Init var */
  var response  = "";
  var page = "<?php echo $page; ?>";
  e.preventDefault();

  /** Build var */
  var uuidparam = "<?php echo $item['uuid']; ?>";
  var dataString = 'uuid='+uuidparam;

  /** Ajax call */
  $.ajax({
    type:'POST',
    data:dataString,
    url:'<?php echo base_url(); ?>personal/delete',
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
        document.getElementById("expenses_<?php echo $item['uuid']; ?>").remove();
      }
    },
    complete:function() {
      /** close modal */
      if(response == "success") {
        /** modal related */
        $('#edit_delete_expenses_modal').modal('hide');
        $(".modal-backdrop").remove();
        $('body').removeClass('modal-open');
        /** sucess message */
        enable_message("success", "successfully deleted!");
      } else {
        /** error message */
        enable_message("error", "something went wrong!");
      }
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
});
</script>
<!-- Edit & Delete Modal Scripts -->