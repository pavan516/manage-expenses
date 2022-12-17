<!-- Search & Dropdown -->
<div class="custom_table_header">
  <!-- Search -->
  <div class="custom_table_search">
    <form>
      <input type="text" id="search" name="search" class="form-control search_expand" placeholder="Search.." value="<?php echo $search; ?>">
    </form>
  </div>
  <!-- Search -->
  <!-- Entries dropdown -->
  <div class="custom_table_entries">
    <select class="btn btn-primary table_entries_dropdown" id="limit" name="limit" onchange="getEntries(this)">
      <option value="10" <?php if($limit == 10) echo "selected"; ?>>10</option>
      <option value="25" <?php if($limit == 25) echo "selected"; ?>>25</option>
      <option value="50" <?php if($limit == 50) echo "selected"; ?>>50</option>
      <option value="100"<?php if($limit == 100) echo "selected"; ?>>100</option>
    </select>
  </div>
  <!-- Entries dropdown -->
</div>
<!-- Search & Dropdown -->

<!-- Show table list -->
<table class="custom_table">
  <thead>
    <tr>
      <th onclick="getSorting('date', '<?php if($order == 'desc') echo 'asc'; else echo 'desc'; ?>')">DATE <?php if($name == "date") { if($order == "desc") { echo '<i class="fa fa-arrow-down"></i>'; } else { echo '<i class="fa fa-arrow-up"></i>'; }} ?></th>
      <th onclick="getSorting('title', '<?php if($order == 'desc') echo 'asc'; else echo 'desc'; ?>')">TITLE <?php if($name == "title") { if($order == "desc") { echo '<i class="fa fa-arrow-down"></i>'; } else { echo '<i class="fa fa-arrow-up"></i>'; }} ?></i></th>
      <th onclick="getSorting('value', '<?php if($order == 'desc') echo 'asc'; else echo 'desc'; ?>')">AMOUNT <?php if($name == "value") { if($order == "desc") { echo '<i class="fa fa-arrow-down"></i>'; } else { echo '<i class="fa fa-arrow-up"></i>'; }} ?></th>
    </tr>
  </thead>
  <tbody id="personal_table_tbody_height">
    <?php if(!empty($items)) {
      foreach($items as $item) { ?>

        <!-- Load data -->
        <tr id="ie_expenses_<?php echo $item['uuid']; ?>" onclick="open_expenses_modal('<?php echo $item['uuid']; ?>');">
          <td id="ie_expenses_date_<?php echo $item['uuid']; ?>" class="no_wrap"><?php echo \year_date_text_format($item['date']); ?></td>
          <td id="ie_expenses_title_<?php echo $item['uuid']; ?>" class="break_all"><?php echo $item['title']; ?></td>
          <td id="ie_expenses_value_<?php echo $item['uuid']; ?>" class="no_wrap fright"><b><?php echo currency_format($item['value']); ?></b></td>
        </tr>
        <!-- Load data -->

      <?php } ?>
    <?php } else { ?>
      <!-- No data -->
      <tr><td id="no_data_height" colspan="3" class="center">NO DATA TO DISPLAY</td></tr>
      <!-- No data -->
    <?php } ?>
  </tbody>
</table>
<!-- Show table list -->

<!-- Include pagination -->
<?php include($_SERVER['DOCUMENT_ROOT'].'/application/views/includes/pagination.php'); ?>
<!-- Include pagination -->

<!-- Edit Modal -->
<div class="modal fade" id="edit_delete_expenses_modal" tabindex="-1" role="dialog" aria-labelledby="edit_delete_expenses_modal_title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="edit_delete_expenses_modal_title"><b class="app_color center">Update | Delete</b></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <!-- Header -->

      <!-- Body -->
      <div class="modal-body">
        <div id="modal_response"></div>
      </div>
      <!-- Body -->

    </div>
  </div>
</div>
<!-- Edit Modal -->

<!-- Custom script -->
<script>

/** init var */
var eventUuid = "<?php echo $event['uuid']; ?>";
var limit     = "<?php echo $limit; ?>";
var pageno    = "<?php echo $pageno; ?>";
var name      = "<?php echo $name; ?>";
var order     = "<?php echo $order; ?>";
var search    = "<?php echo $search; ?>";
var timer     = null;

/** Search key down */
$('#search').keydown(function() {
  clearTimeout(timer);
  timer = setTimeout(doSearch, 1000);
});

/** doSearch function */
function doSearch()
{
  /** get search variable */
  var pageno = 0;
  var search = document.getElementById("search").value;

  /** load expenses list */
  load_iee_details(eventUuid, search='', order='', name='', pageno='', limit='')

  /** focus on search */
  document.getElementById('search').focus();
}

/** Get table entries (limit) */
function getEntries(input_type)
{
  /** Get selected field name & value */
  var selectedText  = input_type.options[input_type.selectedIndex].innerHTML;
  var limit = input_type.value;

  /** load expenses list */
  load_iee_details(eventUuid, search='', order='', name='', pageno='', limit='')
}

/** Get pagination */
function getPagination(pageno)
{
  /** load expenses list */
  load_iee_details(eventUuid, search='', order='', name='', pageno='', limit='')
}

/** Get sorting */
function getSorting(name, order)
{
  /** load expenses list */
  pageno = 0;
  load_iee_details(eventUuid, search='', order='', name='', pageno='', limit='')
}

/** open expenses modal */
function open_expenses_modal(uuid)
{
  /** ajax call */
  $.ajax({
    url: "personal/modalview?uuid="+uuid+"&page="+type,
    method: "GET",
    async: true,
    success: function(data){
      $('#modal_response').html(data);
    }
  });

  /** open modal */
  $('#edit_delete_expenses_modal').modal('show');
}

/** Calculate the html page height */
<?php if(\count($items) >= 10) { ?>
  var datatableScrollY = $(window).height() * 0.50;
  document.getElementById("personal_table_tbody_height").style.height = "calc(100vh - "+datatableScrollY+"px)";
<?php } ?>
</script>








































<!-- Expenses List -->
<div class="datatable">
  <table class="table table-bordered table-hover display" id="i_e_expenses_datatable" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th>DATE</th>
        <th>TITLE</th>
        <th>AMOUNT</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($expenses as $item) { ?>
        <tr onclick="open_ie_expenses_modal('<?php echo $item['uuid']; ?>');">
          <td class="no_wrap"><?php echo short_date_text_format($item['date']); ?></td>
          <td class="break_all"><?php echo $item['title']; ?></td>
          <td class="no_wrap fright"><b><?php echo currency_format($item['value']); ?></b></td>
          <?php include('editdelete.php'); ?>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<!-- Expenses List -->