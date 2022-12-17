<!-- Loggedin user uuid -->
<?php $userUuid = $this->session->userdata('uuid'); ?>
<!-- Loggedin user uuid -->

<!-- Overview -->
<table class="table table-bordered table-hover" width="100%" cellspacing="0">
  <tbody>

    <!-- Heading -->
    <tr>
      <td class="center app_color_blue fs15"><b>MEMBERS</b></td>
      <td class="center app_color_blue fs15"><b>AMOUNT SPENT</b></td>
    </tr>
    <!-- Heading -->

    <!-- Member Expenses List -->
    <?php foreach($members as $member) { ?>
      <tr>
        <td class="break_all">
          <b><?php echo ucwords(strtolower($member['user_name'])); ?></b>
          <?php if($member['role'] == "ADMIN") echo "<b class='app_color_green'> (A)</b>"; ?>
          <?php if($member['role'] != "ADMIN" && $member['user_uuid'] == $this->session->userdata('uuid')) echo "<b> (ME)</b>"; ?>
        </td>
        <td class="no_wrap left">
          <b><?php echo currency_format($member['total_user_group_expenses']); ?></b>
          <b><i class="<?php echo $member['color']; ?>" class="<?php echo $member['arrow']; ?>"></i></b>
        </td>
      </tr>
    <?php } ?>
    <!-- Member Expenses List -->

    <!-- Expenses Result -->
    <?php if($event['budget'] == 0) { ?>

      <!-- Total expenses -->
      <tr class="app_heading_bc center">
        <td class="break_all"><b>Total Group Expenses</b></td>
        <td class="no_wrap left"><b><?php echo currency_format($members[$userUuid]['total_group_expenses']); ?></b></td>
      </tr>
      <!-- Total expenses -->

    <?php } else { ?>

      <!-- Total expenses -->
      <tr class="app_heading_bc center">
        <td class="break_all"><b>Total Group Expenses</b></td>
        <td class="no_wrap left"><b><?php echo " - ".currency_format($members[$userUuid]['total_group_expenses']); ?></b></td>
      </tr>
      <!-- Total expenses -->

      <!-- Budget -->
      <tr class="app_heading_bc center">
        <td class="break_all"><b>Budget set to the <?php echo strtolower($event['mode']); ?></b></td>
        <td class="no_wrap left"><b><?php echo " + ".currency_format($event['budget']); ?></b></td>
      </tr>
      <!-- Budget -->

      <!-- Balance Calculation -->
      <?php $balance = $event['budget'] - $members[$userUuid]['total_group_expenses']; ?>
      <?php $sign = " + "; ?>
      <?php if($balance >= 0) $arrow = " - "; ?>
      <!-- Balance Calculation -->

      <!-- Balance -->
      <tr class="app_heading_bc center">
        <td><b>Balance</b></td>
        <td class="no_wrap left"><b><?php echo $sign.currency_format($balance); ?></b></td>
      </tr>
      <!-- Balance -->

      <!-- Balance -->
      <?php if($balance > 0) { ?>
        <tr class="app_heading_bc center">
          <td colspan="2"><b>Group members have more <?php echo currency_format($balance); ?> to spend!</b></td>
        </tr>
      <?php } else if($balance == 0) { ?>
        <tr class="app_heading_bc center">
          <td colspan="2"><b>Group members have reached budget amount!</b></td>
        </tr>
      <?php } else { ?>
        <tr class="app_heading_bc center;">
          <td colspan="2"><b>Group members had spent more than the budget set to the <?php echo strtolower($event['mode']); ?>!</b></td>
        </tr>
      <?php } ?>
      <!-- Balance -->

    <?php } ?>
    <!-- Expenses Result -->

  </tbody>
</table>
<!-- Overview -->