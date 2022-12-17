<!-- Loggedin user uuid -->
<?php $userUuid = $this->session->userdata('uuid'); ?>
<!-- Loggedin user uuid -->

<!-- Heading -->
<div class="card-header heading">
  <b>GROUP PAYMENT STATUS</b>
</div>
<!-- Heading -->

<!-- Active event -->
<?php if($event['status'] == 1 && empty($event['closed_at'])) { ?>

  <!-- Event status check -->
  <div class="card-body">

    <!-- check for logged-in user -->
    <?php if($event['user_uuid'] == $this->session->userdata('uuid')) { ?>

      <!-- add space -->
      <div class="pt10"></div>
      <!-- add space -->

      <!-- error status -->
      <div id="event_status"></div>
      <!-- error status -->

      <!-- Close Event -->
      <div class="p10">
        <div class="center"><b>To split your group expenses & view your payments list, please close the <?php echo strtolower($event['mode']." (".$event['name'].")"); ?>, as a admin, only you have access to close the <?php echo strtolower($event['mode']); ?>!</b></div><br>
        note: by closing the <?php echo strtolower($event['mode']." ( ".$event['name']." )"); ?> you may not add/update your expenses.
        Please close the <?php echo strtolower($event['mode']); ?> only if it is completed.<br><br>
        <form>
          <!-- Submit Button -->
          <div class="form-group center">
            <input type="submit" id="closeevent_<?php echo $event['uuid']; ?>" value="CLOSE <?php echo strtoupper($event['mode']); ?>" class="btn btn-primary">
          </div>
          <!-- Submit Button -->
        </form>
      </div>
      <!-- Close Event -->

    <?php } else { ?>

      <!-- Message -->
      <div class="p10 center">
        <b>To split group expenses & view your payments list, please close the <?php echo strtolower($event['mode']." ( ".$event['name']." )"); ?>,
        admin have access to close the <?php echo strtolower($event['mode']); ?>,
        please request admin to close the <?php echo strtolower($event['mode']); ?>!</b>
      </div>
      <!-- Message -->

    <?php } ?>
    <!-- check for logged-in user -->

  </div>
  <!-- Event status check -->

<?php } ?>
<!-- Active event -->



<!-- Closed event -->
<?php if($event['status'] != 1 && !empty($event['closed_at'])) { ?>

  <!-- Payments list -->
  <div class="card-body">
    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
      <tbody>

        <!-- Heading when balance is > 0 -->
        <?php if($members[$userUuid]['balance'] > 0) { ?>
          <tr>
            <td colspan="4" class="center app_heading_bc fs15">
              Total amount you spent for group is <b><?php echo currency_format($members[$userUuid]['group_expenses']); ?></b>
            </td>
          </tr>
          <tr>
            <td colspan="4" class="center app_heading_bc fs15">
              Individual share for each member is  <b><?php echo currency_format($members[$userUuid]['share']); ?></b>
            </td>
          </tr>
          <tr>
            <td colspan="4" class="center app_heading_bc fs15">
              you need to pay <b><?php echo currency_format($members[$userUuid]['balance']); ?></b> for your group members.
            </td>
          </tr>
        <?php } ?>
        <!-- Heading when balance is > 0 -->

        <!-- Heading when balance is 0 or < 0 -->
        <?php if($members[$userUuid]['balance'] <= 0) { ?>
          <tr>
            <td colspan="4" class="center app_heading_bc fs15">
              <b>Payment status</b>
            </td>
          </tr>
        <?php } ?>
        <!-- Heading when balance is 0 or < 0 -->


        <!-- Summary when balance is > 0 -->
        <?php if($members[$userUuid]['balance'] > 0) { ?>

          <!-- Title -->
          <tr>
            <td class="center app_color_blue fs15"><b>MEMBER</b></td>
            <td class="center app_color_blue fs15"><b>AMOUNT</b></td>
            <td class="center app_color_blue fs15"><b>STATUS</b></td>
          </tr>
          <!-- Title -->

          <!-- Each Member expenses + & - => payments -->
          <?php foreach($members as $member) { ?>
            <?php if($member['balance'] < 0) { ?>
              <?php $min=1; $max=$members[$userUuid]['balance']; ?>
              <?php if((int)ltrim((string)$member['balance'],"-") < $members[$userUuid]['balance']) $max=(int)ltrim((string)$member['balance'],"-"); ?>
              <form>
                <tr>
                  <td width="40%" class="va_middle"><b><?php echo ucwords(strtolower($member['user_name'])); ?><?php echo " (".currency_format($member['balance']).")"; ?></b></td>
                  <td  width="40%" class="va_middle">
                    <!-- Amount -->
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <input type="number" id="amount_<?php echo $member['user_uuid']; ?>" class="form-control" placeholder="<?php echo $min.' - '.$max; ?>" min="<?php echo $min; ?>" max="<?php echo $max; ?>" required/>
                        <div id="validation_error_<?php echo $member['user_uuid']; ?>"></div>
                      </div>
                    </div>
                    <!-- Amount -->
                  </td>
                  <td class="no_wrap">
                    <!-- Paid -->
                    <button id="paid_<?php echo $member['user_uuid']; ?>" class="form-control">PAID</button>
                    <!-- Paid -->
                  </td>
                </tr>
              </form>

              <!-- paid / debit script -->
              <script type="text/javascript">
                /** paid */
                $("#paid_<?php echo $member['user_uuid']; ?>").click(function(e) {
                  /** Build data */
                  e.preventDefault();
                  var eventUuid = "<?php echo $event['uuid']; ?>";
                  var minparam = "<?php echo $min; ?>";
                  var maxparam = "<?php echo ltrim($max, "-"); ?>";
                  var frienduuidparam = "<?php echo $member['user_uuid']; ?>";
                  var amountparam = $("#amount_<?php echo $member['user_uuid']; ?>").val();
                  var dataString = 'min_amount='+minparam+'&max_amount='+maxparam+'&event_uuid='+eventUuid+'&friend_uuid='+frienduuidparam+'&amount='+amountparam;

                  /** Send Request */
                  $.ajax({
                    type:'POST',
                    data:dataString,
                    url:'<?php echo base_url(); ?>event/expenses/group/payments/paid',
                    success:function(data) {
                      if(data == "success") {
                        /** Load required functions */
                        load_group_event_payments("<?php echo $event['uuid']; ?>");
                      } else {
                        $('#validation_error_<?php echo $member['user_uuid']; ?>').fadeIn().html("<p class='app_color_red'>"+data+"</p>");
                      }
                    }
                  });
                });
              </script>
              <!-- paid / debit script -->

            <?php } ?>
          <?php } ?>
          <!-- Each Member expenses + & - => payments -->

        <?php } ?>
        <!-- Summary when balance is > 0 -->


        <!-- Summary when balance is 0 or < 0 -->
        <?php if($members[$userUuid]['balance'] <= 0) { ?>

          <!-- Paid Payments -->
          <?php if(!empty($members[$userUuid]['paid_amounts'])) { ?>

            <!-- List -->
            <?php foreach($members[$userUuid]['paid_amounts'] as $paidPayments) { ?>
              <tr>
                <td class="break_all"><b>Paid to <?php echo ucwords(strtolower($paidPayments['user_name'])); ?></b></td>
                <td class="no_wrap left"><b><?php echo " + ".currency_format($paidPayments['amount']); ?></b></td>
              </tr>
            <?php } ?>
            <!-- List -->

            <!-- Footer -->
            <tr>
              <td class="break_all"><b>Total amount spent</b></td>
              <td class="no_wrap left"><b><?php echo "+ ".currency_format($members[$userUuid]['group_expenses']); ?></b></td>
            </tr>
            <tr>
              <td class="break_all"><b>Individual share</b></td>
              <td class="no_wrap left"><b><?php echo "- ".currency_format($members[$userUuid]['share']); ?></b></td>
            </tr>
            <tr class="center">
              <td class="break_all"><b>Balance Amount</b></td>
              <td class="no_wrap left"><b><?php echo currency_format($members[$userUuid]['balance']); ?></b></td>
            </tr>
            <tr>
              <td colspan="2" class="center cwhite app_bcolor_green fs15"><b>ACCOUNT CLEARED</b></td>
            </tr>
            <!-- Footer -->

          <?php } ?>
          <!-- Paid Payments -->

          <!-- Received Payments -->
          <?php if(!empty($members[$userUuid]['received_amounts'])) { ?>

            <!-- Amount spent -->
            <tr>
              <td class="break_all"><b>Total amount spent</b></td>
              <td class="no_wrap left"><b><?php echo "+ ".currency_format($members[$userUuid]['group_expenses']); ?></b></td>
            </tr>
            <!-- Amount spent -->

            <!-- Received payments -->
            <?php foreach($members[$userUuid]['received_amounts'] as $receivedPayments) { ?>
              <tr>
                <td class="break_all"><b>Amount received from <?php echo ucwords(strtolower($receivedPayments['user_name'])); ?></b></td>
                <td class="no_wrap left"><b><?php echo " - ".currency_format($receivedPayments['amount']); ?></b></td>
              </tr>
            <?php } ?>
            <!-- Received payments -->

            <!-- Footer -->
            <tr>
              <td class="break_all"><b>Individual share</b></td>
              <td class="no_wrap left"><b><?php echo "- ".currency_format($members[$userUuid]['share']); ?></b></td>
            </tr>
            <tr class="center app_heading_bc fs15">
              <td class="break_all"><b>Balance Amount</b></td>
              <td class="no_wrap left"><b><?php echo currency_format($members[$userUuid]['balance']); ?></b></td>
            </tr>
            <tr>
              <td colspan="2" class="center app_bcolor_green cwhite fs15"><b>ACCOUNT CLEARED</b></td>
            </tr>
            <!-- Footer -->

          <?php } ?>
          <!-- Received Payments -->

          <!-- No Received Payments & Balance is not equal to 0 -->
          <?php if(empty($members[$userUuid]['paid_amounts']) && empty($members[$userUuid]['received_amounts']) && $members[$userUuid]['balance'] != 0) { ?>
            <tr>
              <td colspan="2" class="center app_color fs15">
                <b>
                  Waiting to receive payments from your group members, total amount you need to receive is: <?php echo currency_format($members[$userUuid]['balance']); ?>
                </b>
              </td>
            </tr>
          <?php } ?>
          <!-- No Received Payments & Balance is not equal to 0 -->

          <!-- No Payments -->
          <?php if(empty($members[$userUuid]['paid_amounts']) && empty($members[$userUuid]['received_amounts']) && $members[$userUuid]['balance'] == 0) { ?>
            <tr>
              <td colspan="2" class="center app_color fs15">
                <b>Total amount you spent in the <?php echo strtolower($event['mode']." ( ".$event['name']." )"); ?> is <?php echo currency_format($members[$userUuid]['total_user_expenses']); ?></b>
              </td>
            </tr>
            <tr>
              <td colspan="2" class="center app_bcolor_green cwhite fs15"><b>ACCOUNT CLEARED</b></td>
            </tr>
          <?php } ?>
          <!-- No Payments -->


        <?php } ?>
        <!-- Summary when balance is 0 or < 0 -->

      </tbody>
    </table>
  </div>
  <!-- Payments card body -->

<?php } ?>
<!-- Closed event -->


<!-- Accept or Reject Scripts -->
<script type="text/javascript">
/** Close Event */
$("#closeevent_<?php echo $event['uuid']; ?>").click(function(e) {
  /** Build data */
  e.preventDefault();

  /** build data */
  var eventuuidparam = "<?php echo $event['uuid']; ?>";
  var dataString = 'uuid='+eventuuidparam+'&status=0';

  /** Send Request */
  $.ajax({
    type:'POST',
    data:dataString,
    url:'<?php echo base_url(); ?>event/expenses/group/close',
    success:function(data) {
      if(data == "success") {
        /** Load required functions */
        load_group_event_expenses_split_share("<?php echo $event['uuid']; ?>");
      } else {
        /** load html */
        $('#event_status').fadeIn().html(get_error_string("Error", response));
      }
    }
  });
});
</script>