<!-- Heading -->
<div class="card-header heading">
  <b>MONTHLY RESPONSIBILITIES</b>
</div>
<!-- Heading -->

<!-- Responsibilities -->
<div class="card-body">

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
        <th onclick="getSorting('title', '<?php if($order == 'desc') echo 'asc'; else echo 'desc'; ?>')">TITLE <?php if($name == "title") { if($order == "desc") { echo '<i class="fa fa-arrow-down"></i>'; } else { echo '<i class="fa fa-arrow-up"></i>'; }} ?></i></th>
        <th onclick="getSorting('value', '<?php if($order == 'desc') echo 'asc'; else echo 'desc'; ?>')">AMOUNT <?php if($name == "value") { if($order == "desc") { echo '<i class="fa fa-arrow-down"></i>'; } else { echo '<i class="fa fa-arrow-up"></i>'; }} ?></th>
      </tr>
    </thead>
    <tbody id="rp_table_tbody_height" class="scroll">
      <?php if(!empty($items)) {
        foreach($items as $item) { ?>

          <!-- calculate color & arrow -->
          <?php $color = ""; $arrowClass = "fa fa-arrow-down" ?>
          <?php if($item['type'] == "INCOME") { ?>
            <?php $color="app_color_green"; ?>
            <?php $arrowClass = "fa fa-arrow-up" ?>
          <?php } else if($item['type'] == "INVESTMENT") { ?>
            <?php $color="app_color_blue"; ?>
          <?php } else { ?>
            <?php $color="app_color_red"; ?>
          <?php } ?>
          <!-- calculate color -->

          <!-- Load data -->
          <tr id="responsibilities_<?php echo $item['uuid']; ?>" onclick="open_responsibilities_modal('<?php echo $item['uuid']; ?>');" class="<?php echo $color; ?>">
            <td id="responsibilities_title_<?php echo $item['uuid']; ?>" class="break_all"><?php echo $item['title']; ?></td>
            <td id="responsibilities_value_<?php echo $item['uuid']; ?>" class="no_wrap fright"><b><?php echo currency_format($item['value']); ?> <i class="<?php echo $arrowClass; ?>" aria-hidden="true" class="<?php echo $color; ?>"></b></td>
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

</div>
<!-- Responsibilities -->

<!-- Custom script -->
<script>

/** init var */
var search = "<?php echo $search; ?>";
var order  = "<?php echo $order; ?>";
var name   = "<?php echo $name; ?>";
var pageno = "<?php echo $pageno; ?>";
var limit  = "<?php echo $limit; ?>";
var timer  = null;

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

  /** load list */
  personal_responsibilities_list(search, order, name, pageno, limit);

  /** focus on search */
  document.getElementById('search').focus();
}

/** Get table entries (limit) */
function getEntries(input_type)
{
  /** Get selected field name & value */
  var selectedText  = input_type.options[input_type.selectedIndex].innerHTML;
  var limit = input_type.value;

  /** load list */
  personal_responsibilities_list(search, order, name, pageno, limit);
}

/** Get pagination */
function getPagination(pageno)
{
  /** load list */
  personal_responsibilities_list(search, order, name, pageno, limit);
}

/** Get sorting */
function getSorting(name, order)
{
  /** load list */
  pageno = 0;
  personal_responsibilities_list(search, order, name, pageno, limit);
}

/** open expenses modal */
function open_expenses_modal(uuid)
{
  /** ajax call */
  $.ajax({
    url: "responsibilities/personal/modalview?uuid="+uuid,
    method: "GET",
    async: true,
    success: function(data){
      $('#modal_response').html(data);
    }
  });

  /** open modal */
  $('#edit_delete_expenses_modal').modal('show');
}

var height = $(window).height() * 0.395;
document.getElementById("rp_table_tbody_height").style.height = "calc(100vh - "+height+"px)";
</script>
<!-- Custom script -->
