<!-- Heading -->
<div class="card-header heading">
  <b>GROUP EXPENSES OVERVIEW</b>
</div>
<!-- Heading -->

<!-- Load overview -->
<div class="card-body pt10">
  <div id="group_event_expenses_overview"></div>
</div>
<!-- Load overview -->

<!-- Make sure you have expenses -->
<?php if(!empty($expenses)) { ?>

  <!-- add space only if we have data to show -->
  <div class="pb10"></div>
  <!-- add space only if we have data to show -->

  <!-- Heading -->
  <div class="card-header heading">
    <b>GROUP EXPENSES DETAILS</b>
  </div>
  <!-- Heading -->

  <!-- Card body -->
  <div class="card-body">

    <!-- add space only if we have data to show -->
    <div class="pb10"></div>
    <!-- add space only if we have data to show -->

    <!-- Expenses list -->
    <div class="datatable">
      <table class="table table-bordered table-hover" id="group_expenses_details_datatable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>MEMBER</th>
            <th>SPENT ON</th>
            <th>AMOUNT</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($expenses as $item) { ?>
            <tr class="app_color_grey">
              <?php if(\strlen($item['user_name']) >= 12) $item['user_name'] = \substr($item['user_name'],0,10).'...'; ?>
              <td class="no_wrap"><b><?php echo $item['user_name']; ?></b></td>
              <td class="break_all"><b><?php echo $item['title']; ?></b></td>
              <td class="no_wrap fright"><b><?php echo currency_format($item['value']); ?></b></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <!-- Expenses list -->

  </div>
  <!-- Card body -->

<?php } ?>

<!-- Script -->
<script>
$(document).ready(function() {
  $('#group_expenses_details_datatable').DataTable({
     columnDefs: [
       { type: 'currency', targets: 2 }
     ],
     "aaSorting": [[ 0, "asc" ]]
  });
});
</script>
<!-- Script -->

  </div>
</div>
<!-- Tab Contents -->

<!-- Custom Scripts - Get Profile -->
<script type="text/javascript">
$(document).ready(function()
{
  /** Load required functions */
  load_group_event_expenses_overview("<?php echo $event['uuid']; ?>");
});
</script>
<!-- Custom Scripts - Get Profile -->
