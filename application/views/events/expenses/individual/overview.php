<!-- Overview -->
<?php if(empty($expenses['total_expenses'])) $expenses['total_expenses'] = 0; ?>
<table class="table table-bordered table-hover" width="100%" cellspacing="0">
  <tbody>

    <!-- Heading -->
    <tr>
      <td colspan="2" class="center app_color_blue fs15">
        <b>
          <?php echo \ucwords(\strtolower($event['mode']))." From ".date_text_format($event['planned_at'])." To "; ?>
          <?php if(!empty($event['closed_at'])) { echo date_text_format($event['closed_at']); } else echo date_text_format(date('Y-m-d')); ?>
        </b>
      </td>
    </tr>
    <!-- Heading -->

    <!-- when user set budget -->
    <?php if($event['budget'] != 0) { ?>
      <tr class="app_color_red">
        <td><b>Amount you spent</b></td>
        <td class="fright"><b><?php echo currency_format($expenses['total_expenses']); ?> <i class="fa fa-arrow-down"></i></b></td>
      </tr>
      <tr class="app_color_green">
        <td><b>Budget set to the <?php echo strtolower($event['mode']); ?></b></td>
        <td class="fright"><b><?php echo currency_format($event['budget']); ?> <i class="fa fa-arrow-up"></i></b></td>
      </tr>
      <?php $balance = $event['budget']-$expenses['total_expenses']; ?>
      <?php if($balance >= 0) { ?>
        <tr class="app_heading_bc">
          <td><b>Balance</b></td>
          <td class="fright"><b><?php echo currency_format($balance); ?> <i class="fa fa-arrow-up"></i></b></td>
        </tr>
        <tr class="center app_heading_bc">
          <td colspan="2">You have more <b><?php echo currency_format($balance); ?></b> to spend!</td>
        </tr>
      <?php } else { ?>
        <tr class="app_heading_bc">
          <td><b>Balance</b></td>
          <td class="fright"><b><?php echo " - ".currency_format($balance); ?> <i class="fa fa-arrow-down"></i></b></td>
        </tr>
        <tr class="center app_heading_bc">
          <td colspan="2">You had spent more than the budget, set to the <?php echo strtolower($event['mode']); ?>!</td>
        </tr>
      <?php } ?>
    <?php } ?>
    <!-- when user set budget -->

    <!-- when budget not set -->
    <?php if($event['budget'] == 0) { ?>
      <tr class="center app_heading_bc">
        <td colspan="2">Total amount you spent for this <?php echo strtolower($event['mode']); ?> is <b><?php echo currency_format($expenses['total_expenses']); ?></b></td>
      </tr>
    <?php } ?>
    <!-- when budget not set -->

  </tbody>
</table>
<!-- Overview -->