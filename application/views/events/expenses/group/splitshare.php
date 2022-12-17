<!-- Loggedin user uuid -->
<?php $userUuid = $this->session->userdata('uuid'); ?>
<!-- Loggedin user uuid -->

<!-- Heading -->
<div class="card-header heading">
  <b>SPLIT - GROUP EXPENSES</b>
</div>
<!-- Heading -->

<!-- Overview -->
<div class="card-body">
  <table class="table table-bordered table-hover" width="100%" cellspacing="0">
    <tbody>
      <tr class="app_heading_bc fs15">
        <td colspan="2">TOTAL GROUP EXPENSES</td>
        <td class="no_wrap left"><b><?php echo currency_format($members[$userUuid]['total_group_expenses']); ?></b></td>
      </tr>
      <tr class="app_heading_bc fs15">
        <td colspan="2">EACH MEMBER SHARE</td>
        <td class="no_wrap left"><b><?php echo currency_format($members[$userUuid]['share']); ?></b></td>
      </tr>
      <tr class="center app_heading_bc fs15">
        <td colspan="3"><b>EACH MEMBER STATUS</b></td>
      </tr>
      <tr class="center app_heading_bc fs15">
        <td><b>MEMBER</b></td>
        <td colspan="2"><b>BALANCE</b></td>
      </tr>
      <?php foreach($members as $member) { ?>

        <!-- List -->
        <tr>
          <td rowspan="<?php echo $member['rowspan']; ?>" class="center va_middle"><b><?php echo ucwords(strtolower($member['user_name'])); ?></b></td>
          <td class="no_wrap right"><b>Amount Spent</b></td>
          <td class="no_wrap left"><b><?php echo " + ".currency_format($member['group_expenses']); ?></b></td>
        </tr>
        <tr>
          <td class="no_wrap right"><b>Individual Share</b></td>
          <td class="no_wrap left"><b><?php echo " - ".currency_format($member['share']); ?></b></td>
        </tr>
        <?php if($member['total_paid_amount'] != 0) { ?>
          <tr>
            <td class="no_wrap right"><b>Amount Paid</b></td>
            <td class="no_wrap left"><b><?php echo " + ".currency_format($member['total_paid_amount']); ?></b></td>
          </tr>
        <?php } ?>
        <?php if($member['total_received_amount'] != 0) { ?>
          <tr>
            <td class="no_wrap right"><b>Amount Received</b></td>
            <td class="no_wrap left"><b><?php echo " - ".currency_format($member['total_received_amount']); ?></b></td>
          </tr>
        <?php } ?>
        <tr class="<?php echo $member['color']; ?> cwhite">
          <td colspan="2" class="no_wrap center"><b><?php echo $member['bal_msg']; ?></b></td>
          <td class="no_wrap left"><b><?php echo $member['sign'].currency_format($member['balance']); ?></b></td>
        </tr>
        <!-- List -->

      <?php } ?>
    </tbody>
  </table>
</div>
<!-- Overview -->