<!-- Overview -->
<table class="table table-bordered table-hover" width="100%" cellspacing="0">
  <tbody>
    <tr class="app_color_green">
      <td><b>Income</b></td>
      <td class="fright"><b><?php echo currency_format($total['income']); ?> <i class="fa fa-arrow-up"></i></b></td>
    </tr>
    <tr class="app_color_blue">
      <td><b>Investment</b></td>
      <td class="fright"><b><?php echo currency_format($total['investment']); ?> <i class="fa fa-arrow-down"></i></b></td>
    </tr>
    <tr class="app_color_red">
      <td><b>Expenses</b></td>
      <td class="fright"><b><?php echo currency_format($total['expenses']); ?> <i class="fa fa-arrow-down"></i></b></td>
    </tr>
    <?php if($total['total'] >= 0) { ?>
      <tr class="app_heading_bc">
        <td><b>Balance</b></td>
        <td class="fright"><b><?php echo currency_format($total['total']); ?> <i class="fa fa-arrow-up"></i></b></td>
      </tr>
    <?php } else { ?>
      <tr class="app_heading_bc">
        <td><b>Balance</b></td>
        <td class="fright"><b><?php echo "- ".currency_format($total['total']); ?> <i class="fa fa-arrow-down"></i></b></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
<!-- Overview -->