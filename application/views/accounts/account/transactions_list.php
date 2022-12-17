
<!-- Transactions List -->
<div class="card">
  <div class="card-body">

    <!-- Add some space at top level -->
    <div class="pt5"></div>
    <!-- Add some space at top level -->

    <!-- Show list of transactions -->
    <div class="datatable">
      <table class="table table-bordered table-hover display" id="transactions_DataTable" width="100%" cellspacing="0">
        <thead>
          <tr class="center">
            <th>DATE</th>
            <th>TITLE</th>
            <th>AMOUNT</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($transactions as $transaction) { ?>

            <!-- calculate color & arrow -->
            <?php $arrow = "fa fa-arrow-down"; ?>
            <?php if($transaction['user_uuid'] == $this->session->userdata('uuid')) { ?>
              <?php if($transaction['type'] == "CREDIT") $arrow = "fa fa-arrow-up"; ?>
            <?php } else { ?>
              <?php if($transaction['type'] == "DEBIT") $arrow = "fa fa-arrow-up"; ?>
            <?php } ?>
            <!-- calculate color & arrow -->

            <!-- show transaction list based on user -->
            <?php if($transaction['user_uuid'] == $this->session->userdata('uuid')) { ?>
              <!-- Transaction -->
              <tr id="transaction_<?php echo $transaction['id']; ?>" class="center <?php if($transaction['type'] == "CREDIT") echo "app_color_green"; else echo "app_color_red"; ?>" data-toggle="modal" data-target="#edit_delete_transaction_modal_<?php echo $transaction['id']; ?>">
                <td id="transaction_date_<?php echo $transaction['id']; ?>" data-sort="<?php echo $transaction['date']; ?>" class="no_wrap"><?php echo \year_date_text_format($transaction['date']); ?></td>
                <td id="transaction_title_<?php echo $transaction['id']; ?>" class="break_all"><?php echo $transaction['title']; ?></td>
                <td id="transaction_value_<?php echo $transaction['id']; ?>" class="no_wrap fright"><b><?php echo currency_format($transaction['amount']); ?> <i class="<?php echo $arrow; ?>"></i></b></td>
              </tr>
              <!-- Transaction -->
            <?php } else { ?>
              <!-- Transaction -->
              <tr id="transaction_<?php echo $transaction['id']; ?>" class="center <?php if($transaction['type'] == "CREDIT") echo "app_color_red"; else echo "app_color_green"; ?>" data-toggle="modal" data-target="#edit_delete_transaction_modal_<?php echo $transaction['id']; ?>">
                <td id="transaction_date_<?php echo $transaction['id']; ?>" data-sort="<?php echo $transaction['date']; ?>"  class="no_wrap"><?php echo \year_date_text_format($transaction['date']); ?></td>
                <td id="transaction_title_<?php echo $transaction['id']; ?>" class="break_all"><?php echo $transaction['title']; ?></td>
                <td id="transaction_value_<?php echo $transaction['id']; ?>" class="no_wrap fright"><b><?php echo currency_format($transaction['amount']); ?> <i class="<?php echo $arrow; ?>"></i></b></td>
              </tr>
              <!-- Transaction -->
            <?php } ?>
            <!-- show transaction list based on user -->

            <!-- Edit & delete Modal -->
            <?php include('transaction_edit_delete.php'); ?>
            <!-- Edit & delete Modal -->

          <?php } ?>
        </tbody>
      </table>
    </div>
    <!-- Show list of transactions -->

  </div>
</div>
<!-- Transactions List -->

<!-- Space between list & overview -->
<div class="pt5"></div>
<!-- Space between list & overview -->

<!-- Scripts -->
<script type="text/javascript">
/** calculate the height of the screen for datatable */
var datatableScrollY = $(window).height() * 0.50;
/** Display sorting & pagination for data-tables */
$(document).ready(function (e){
  $("#transactions_DataTable").dataTable({
    columnDefs: [
      { type: 'currency', targets: 2 }
    ],
    "aaSorting": [],
    "scrollY": "calc(100vh - "+datatableScrollY+"px)",
    "scrollCollapse": true
  });
});
</script>
<!-- Scripts -->