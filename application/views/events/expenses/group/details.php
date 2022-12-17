<!-- Live Expenses -->
<div class="datatable">
  <table class="table table-bordered table-hover" id="ge_details" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th>Member</th>
        <th>Spent On</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($expenses as $item) { ?>
        <tr>
          <td><b><?php echo $item['user_name']; ?></b></td>
          <td><b><?php echo $item['title']; ?></b></td>
          <td><b><?php echo currency_format($item['value']); ?></b></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<!-- Live Expenses -->

<!-- Script -->
<script>
$(document).ready(function() {
  $('#ge_details').DataTable({
     columnDefs: [
       { type: 'currency', targets: 2 }
     ],
     "aaSorting": [[ 0, "asc" ]]
  });
});
</script>
<!-- Script -->