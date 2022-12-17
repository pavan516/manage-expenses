<!-- Heading -->
<div class="card-header heading">
  <b>MY EXPENSES OVERVIEW</b>
</div>
<!-- Heading -->

<!-- Overview -->
<div class="card-body">
  <table class="table table-bordered table-hover" width="100%" cellspacing="0">
    <tbody>
      <tr>
        <td colspan="2" class="no_wrap center"><b>Personal Expenses</b></td>
        <td class="no_wrap left"><b><?php echo currency_format($member['personal_expenses']); ?></b></td>
      </tr>
      <tr>
        <td rowspan="3" class="break_all va_middle center"><b>Group Expenses</b></td>
        <td class="no_wrap"><b>Amount Spent</b></td>
        <td class="no_wrap left"><b><?php echo currency_format($member['group_expenses']); ?></b></td>
      </tr>
      <tr>
        <td class="no_wrap"><b>Amount Paid</b></td>
        <td class="no_wrap left"><b><?php echo currency_format($member['total_paid_amount']); ?></b></td>
      </tr>
      <tr>
        <td class="no_wrap"><b>Amount Received</b></td>
        <td class="no_wrap left"><b><?php echo "- ".currency_format($member['total_received_amount']); ?></b></td>
      </tr>
      <tr class="center app_heading_bc">
        <td colspan="2"><b>Total Expenses</b></td>
        <td class="no_wrap left"><b><?php echo currency_format($member['total_user_expenses']); ?></b></td>
      </tr>
    </tbody>
  </table>
</div>
<!-- Overview -->

<!-- show all data - expenses list -->
<?php if(!empty($expenses)) { ?>

  <!-- add space only if we have data to show -->
  <div class="pb10"></div>
  <!-- add space only if we have data to show -->

  <!-- Heading -->
  <div class="card-header personal_heading_greyout">
    <b>MY EXPENSES DETAILS</b>
  </div>
  <!-- Heading -->

  <!-- Card body -->
  <div class="card-body">

    <!-- add space only if we have data to show -->
    <div class="pb10"></div>
    <!-- add space only if we have data to show -->

    <!-- Live Expenses -->
    <div class="datatable">
      <table class="table table-bordered table-hover" id="group_expenses_datatable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>DATE</th>
            <th>TITLE</th>
            <th>AMOUNT</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($expenses as $item) { ?>
            <?php if($item['split'] == 1) $color = "app_color_blue"; else $color = "app_color_green"; ?>
            <tr class="app_color_grey;" data-toggle="modal" data-target="#edit_delete_group_expenses_modal_<?php echo $item['id']; ?>">
              <td class="no_wrap"><b><?php echo short_date_text_format($item['date']); ?></b></td>
              <td class="break_all"><b><?php echo $item['title']; if($item['split'] == 1) echo "<b class='.$color.'> (G)</b>"; else echo "<b class='.$color.'> (P)</b>";?></b></td>
              <td class="no_wrap right"><b><?php echo currency_format($item['value']); ?></b></td>
              <?php include('editdelete.php'); ?>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <!-- Live Expenses -->

  </div>
  <!-- Card body -->

<?php } ?>
<!-- show all data - list of expenses -->


<!-- Custom Scripts - Get Profile -->
<script type="text/javascript">
$(document).ready(function()
{
  /** Load Data Table */
  $('#group_expenses_datatable').DataTable({
     columnDefs: [
       { type: 'currency', targets: [0,2] }
     ],
     "aaSorting": []
  });
});
</script>
<!-- Custom Scripts - Get Profile -->
