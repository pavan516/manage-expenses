<!-- Edit Modal -->
<div class="modal fade" id="edit_delete_transaction_modal_<?php echo $transaction['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="edit_delete_transaction_modal_title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="edit_delete_transaction_modal_title"><b class="app_color">Transaction (Update/Delete)</b></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <!-- Header -->

      <!-- Body -->
      <div class="modal-body">

        <!-- Success/error message -->
        <div id="edit_delete_transaction_status_<?php echo $transaction['id']; ?>"></div>
        <!-- Success/error message -->

        <!-- Update Transaction Form -->
        <form>

          <!-- Select Transaction Date | Transaction Type -->
          <div class="form-row">
            <div class="form-group col-xs-6">
              <input type="date" name="date_<?php echo $transaction['id']; ?>" id="date_<?php echo $transaction['id']; ?>" class="form-control" value="<?php echo $transaction['date']; ?>" required/>
            </div>
            <div class="form-group col-xs-6">
              <select class="form-control" name="type_<?php echo $transaction['id']; ?>" id="type_<?php echo $transaction['id']; ?>">
                <option value="CREDIT" <?php if($transaction['type']=="CREDIT") echo "selected"; ?>>Credit (+)</option>
                <option value="DEBIT"<?php if($transaction['type']=="DEBIT") echo "selected"; ?>>Debit (-)</option>
              </select>
            </div>
          </div>
          <!-- Select Transaction Date | Transaction Type -->

          <!-- Select Title | Amount -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <textarea name="title_<?php echo $transaction['id']; ?>" id="title_<?php echo $transaction['id']; ?>" class="form-control" placeholder="Transaction details" required><?php echo $transaction['title']; ?></textarea>
            </div>
            <div class="form-group col-md-6">
              <input type="number" name="amount_<?php echo $transaction['id']; ?>" id="amount_<?php echo $transaction['id']; ?>" class="form-control" value="<?php echo $transaction['amount']; ?>" required/>
            </div>
          </div>
          <!-- Select Title | Amount -->

          <!-- Submit Button -->
          <div class="form-group center">
            <input class="btn save_button" type="submit" id="edit_transaction_<?php echo $transaction['id']; ?>" value="SAVE">
            <button class="btn cancel_button" type="button" data-dismiss="modal">CANCEL</button>
          </div>
          <!-- Submit Button -->

        </form>
        <!-- Update Transaction Form -->

        <!-- Break line -->
        <hr>
        <!-- Break line -->

        <!-- Delete Transaction Form -->
        <form>

          <!-- Message -->
          <h6 class="center fs09rem"><b>Do you want to delete this transaction ?</b></h6>
          <!-- Message -->

          <!-- Submit Button -->
          <div class="form-group center">
            <input class="btn yes_button" type="submit" id="delete_transaction_<?php echo $transaction['id']; ?>" value="YES" >
            <button class="btn no_button" type="button" data-dismiss="modal">NO</button>
          </div>
          <!-- Submit Button -->

        </form>
        <!-- Delete Transaction Form -->

      </div>
      <!-- Body -->

    </div>
  </div>
</div>
<!-- Edit Modal -->

<!-- Edit & Delete Modal Scripts -->
<script type="text/javascript">
/** Update Transaction */
$("#edit_transaction_<?php echo $transaction['id']; ?>").click(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** Build data */
  var dateParam 	= $("#date_<?php echo $transaction['id']; ?>").val();
	var typeParam 	= $("#type_<?php echo $transaction['id']; ?>").val();
	var titleParam 	= $("#title_<?php echo $transaction['id']; ?>").val();
	var amountParam = $("#amount_<?php echo $transaction['id']; ?>").val();
  var dataString 	= 'date='+dateParam+'&type='+typeParam+'&title='+titleParam+'&amount='+amountParam;

  /** ajax call */
  $.ajax({
    type:'POST',
    data:dataString,
    url:'<?php echo base_url(); ?>account/view/<?php echo $account['uuid']; ?>/transaction/update/<?php echo $transaction['id']; ?>',
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** append data to response  */
      response = data;
      /** on success load all required methods */
      if(data == "success") {
        /** load transactions overview */
        load_account_transactions_overview("<?php echo $account['uuid']; ?>");
        /** get color & arrow */
        var color = "app_color_red";
        var arrow = "fa fa-arrow-down";
        if(typeParam == "CREDIT") {
          color = "app_color_green";
          arrow = "fa fa-arrow-up";
        }
        /** update values */
        $("#transaction_<?php echo $transaction['id']; ?>").removeClass().addClass(color);
        document.getElementById("transaction_date_<?php echo $transaction['id']; ?>").innerHTML = date_format(dateParam);
        document.getElementById("transaction_title_<?php echo $transaction['id']; ?>").innerHTML = titleParam;
        document.getElementById("transaction_value_<?php echo $transaction['id']; ?>").innerHTML = "<b>"+amountParam+" <i class='"+arrow+"'></i></b>";
        /** close modal */
        $('#edit_delete_transaction_modal_<?php echo $transaction['id']; ?>').modal('hide');
        $(".modal-backdrop").remove();
        $('body').removeClass('modal-open');
        $('body').removeAttr( 'style' );
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response != "success") {
        /** load html */
				$('#edit_delete_transaction_status_<?php echo $transaction['id']; ?>').fadeIn().html(get_error_string("Error", response));
        /** close success or error msg */
        close_alert_message();
      }
    }
  });
});

/** Delete Transaction */
$("#delete_transaction_<?php echo $transaction['id']; ?>").click(function(e) {
  /** Init var */
  var response = "";
  e.preventDefault();

  /** ajax call */
  $.ajax({
    type:'POST',
    data:'',
    url:'<?php echo base_url(); ?>account/view/<?php echo $account['uuid']; ?>/transaction/delete/<?php echo $transaction['id']; ?>',
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** append data to response  */
      response = data;
      /** on success load all required methods */
      if(data == "success") {
        /** load transactions overview */
        load_account_transactions_overview("<?php echo $account['uuid']; ?>");
        /** remove record */
        document.getElementById("transaction_<?php echo $transaction['id']; ?>").remove();
        /** close modal */
        $('#edit_delete_transaction_modal_<?php echo $transaction['id']; ?>').modal('hide');
        $(".modal-backdrop").remove();
        $('body').removeClass('modal-open');
        $('body').removeAttr( 'style' );
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
      if(response != "success") {
        /** load html */
				$('#edit_delete_transaction_status_<?php echo $transaction['id']; ?>').fadeIn().html(get_error_string("Error", response));
        /** close success or error msg */
        close_alert_message();
      }
    }
  });
});
</script>
<!-- Edit & Delete Modal Scripts -->