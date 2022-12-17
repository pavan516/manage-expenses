<!-- Heading -->
<div class="card-header personal_heading">
  <b>PERSONAL EXPENSES OVERVIEW</b>
</div>
<!-- Heading -->

<!-- Overview -->
<div class="card-body" class="pt5">
  <table class="table table-bordered table-hover" width="100%" cellspacing="0">
    <tbody>
      <?php if($data['savings'] >= 0) { ?>
        <tr class="app_color_green">
          <td><b>Savings (Till <?php echo date("M, t", \strtotime("-1 month")); ?>)</b></td>
          <td class="fright"><b><?php echo currency_format($data['savings']); ?> <i class="fa fa-arrow-up"></i></b></td>
        </tr>
      <?php } else { ?>
        <tr class="app_color_red">
          <td><b>Debits (Till <?php echo date("M", \strtotime("-1 month")); ?>)</b></td>
          <td class="fright"><b><?php echo currency_format($data['savings']); ?> <i class="fa fa-arrow-down"></i></b></td>
        </tr>
      <?php } ?>
      <tr class="app_color_green">
        <td><b>Income (<?php echo date("M"); ?>)</b></td>
        <td class="fright"><b><?php echo currency_format($data['current_income']); ?> <i class="fa fa-arrow-up"></i></b></td>
      </tr>
      <tr class="app_color_blue">
        <td><b>Investment (<?php echo date("M"); ?>)</b></td>
        <td class="fright"><b><?php echo currency_format($data['current_investment']); ?> <i class="fa fa-arrow-down"></i></b></td>
      </tr>
      <tr class="app_color_red">
        <td><b>Expenses (<?php echo date("M"); ?>)</b></td>
        <td class="fright"><b><?php echo currency_format($data['current_expenses']); ?> <i class="fa fa-arrow-down"></i></b></td>
      </tr>
      <?php if($data['total'] >= 0) { ?>
        <tr class="app_heading_bc">
          <td><b>Balance</b></td>
          <td class="fright"><b><?php echo currency_format($data['total']); ?> <i class="fa fa-arrow-up"></i></b></td>
        </tr>
      <?php } else { ?>
        <tr class="app_heading_bc">
          <td><b>Balance</b></td>
          <td class="fright"><b><?php echo "- ".currency_format($data['total']); ?> <i class="fa fa-arrow-down"></i></b></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<!-- Overview -->