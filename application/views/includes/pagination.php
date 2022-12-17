<!-- Pagination -->
<nav aria-label="Page navigation example">
  <ul class="pagination pagination-sm custom_table_pagination">

    <!-- show first button -->
    <?php if($pageno > 1) { ?>
      <li class='page-item'>
        <a class='page-link' onclick="getPagination('1')">
          <i class='fa fa-step-backward' style='font-size:12px' aria-hidden='true'></i>
        </a>
      </li>
    <?php } ?>
    <!-- show first button -->

    <!-- show previous button -->
    <li class='page-item <?php if($pageno <= 1) echo "disabled"; ?>'>
      <?php if($pageno > 1) { ?>
        <a class='page-link' onclick="getPagination('<?php echo $pageno-1; ?>')">
          <span aria-hidden="true">&laquo;</span>
        </a>
      <?php } else { ?>
        <a class='page-link'>
          <span aria-hidden="true">&laquo;</span>
        </a>
      <?php } ?>
    </li>
    <!-- show previous button -->

    <!-- Show page nos based on total pages & pageno -->
    <?php if($total_pages <= 10) { ?>
      <?php for($counter = 1; $counter <= $total_pages; $counter++) { ?>
        <?php if ($counter == $pageno) { ?>
          <li class='page-item active'><a class='page-link' onclick="getPagination('<?php echo $counter; ?>')"><?php echo $counter; ?></a></li>
        <?php } else { ?>
          <li class='page-item'><a class='page-link' onclick="getPagination('<?php echo $counter; ?>')"><?php echo $counter; ?></a></li>
        <?php } ?>
      <?php } ?>
    <?php } else if($total_pages > 10) { ?>
      <?php if($pageno <= 4) { ?>
        <?php for($counter = 1; $counter <= 4; $counter++) {	?>
          <?php if ($counter == $pageno) { ?>
            <li class='page-item active'><a class='page-link' onclick="getPagination('<?php echo $counter; ?>')"><?php echo $counter; ?></a></li>
          <?php } else { ?>
            <li class='page-item'><a class='page-link' onclick="getPagination('<?php echo $counter; ?>')"><?php echo $counter; ?></a></li>
          <?php } ?>
        <?php } ?>
        <li class='page-item'><a class='page-link'>...</a></li>
        <li class='page-item'><a class='page-link' onclick="getPagination('<?php echo $total_pages-1; ?>')"><?php echo $total_pages-1; ?></a></li>
        <li class='page-item'><a class='page-link' onclick="getPagination('<?php echo $total_pages; ?>')"><?php echo $total_pages; ?></a></li>
      <?php } else { ?>
        <?php if($pageno < $total_pages - 2) { ?>
          <li class='page-item active'><a class='page-link' onclick="getPagination('<?php echo $pageno; ?>')"><?php echo $pageno; ?></a></li>
          <li class='page-item'><a class='page-link' onclick="getPagination('<?php echo $pageno+1; ?>')"><?php echo $pageno+1; ?></a></li>
          <li class='page-item'><a class='page-link' >...</a></li>
        <?php } else { ?>
          <li class='page-item'><a class='page-link' onclick="getPagination('1')">1</a></li>
          <li class='page-item'><a class='page-link' onclick="getPagination('2')">2</a></li>
          <li class='page-item'><a class='page-link' >...</a></li>
        <?php } ?>
        <?php for($counter = $total_pages - 2; $counter <= $total_pages; $counter++) { ?>
          <?php if($counter == $pageno) { ?>
            <li class='page-item active'><a class='page-link' onclick="getPagination('<?php echo $counter; ?>')"><?php echo $counter; ?></a></li>
          <?php } else { ?>
            <li class='page-item'><a class='page-link' onclick="getPagination('<?php echo $counter; ?>')"><?php echo $counter; ?></a></li>
          <?php } ?>
        <?php } ?>
      <?php } ?>
    <?php } ?>
    <!-- Show page nos based on total pages & pageno -->

    <!-- show next button -->
    <li  class='page-item <?php if($pageno == $total_pages) echo "disabled"; ?>'>
      <?php if($pageno < $total_pages) { ?>
        <a class='page-link' onclick="getPagination('<?php echo $pageno+1; ?>')"><span aria-hidden="true">&raquo;</span></a>
      <?php } else { ?>
        <a class='page-link' onclick="getPagination('<?php echo $pageno; ?>')"><span aria-hidden="true">&raquo;</span></a>
      <?php } ?>
    </li>
    <!-- show next button -->

    <!-- show last button -->
    <?php if($pageno < $total_pages) { ?>
      <li class='page-item'>
        <a class='page-link' onclick="getPagination('<?php echo $total_pages; ?>')">
          <i class='fa fa-step-forward' style='font-size:12px' aria-hidden='true'></i>
        </a>
      </li>
    <?php } ?>
    <!-- show last button -->

  </ul>
</nav>
<!-- Pagination -->