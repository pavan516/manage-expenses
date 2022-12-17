<!-- Accounts -->
<?php if(!empty($accounts)) { ?>

  <!-- Arrange in rows -->
  <div class="row">

    <!-- Loop each Account -->
    <?php foreach($accounts as $account) { ?>

      <!-- Event -->
      <div class="col-md-4 pl0 pr0">
        <div class="card card-header-actions mb-2">

          <!-- Heading -->
          <div class="card-header hbcolor cwhite"><?php echo $account['account_name']; ?></div>
          <!-- Heading -->

          <!-- Body -->
          <div class="card-body text-black border_radius_0">

              <!-- Boxes -->
              <div class="row pb5">

                <!-- Credit -->
                <div class="col-xs-4 pr5 width_33per">
                  <div class="card p5 app_bcolor_lgrey">
                    <div class="card-body center app_bcolor_lgrey app_color_green">
                      <b>Credit <i class="fa fa-arrow-up" aria-hidden="true" class="app_color_green"></i><br></b>
                      <b class="text_resize"><?php echo currency_format($account['_stats']['credit']); ?></b>
                    </div>
                  </div>
                </div>
                <!-- Credit -->

                <!-- Debit -->
                <div class="col-xs-4 pr5 width_33per">
                  <div class="card p5 app_bcolor_lgrey">
                    <div class="card-body center app_bcolor_lgrey app_color_red">
                      <b>Debit <i class="fa fa-arrow-down" aria-hidden="true" class="app_color_red"></i><br></b>
                      <b class="text_resize"><?php echo currency_format($account['_stats']['debit']); ?></b>
                    </div>
                  </div>
                </div>
                <!-- Debit -->

                <!-- Balance -->
                <div class="col-xs-4 width_33per">
                  <div class="card p5 app_bcolor">
                    <div class="card-body center app_heading_bc">
                      <?php if($account['_stats']['credit'] > $account['_stats']['debit']) { ?>
                        <b>Balance <i class="fa fa-arrow-up" aria-hidden="true" class="cwhite"></i><br></b>
                        <b class="text_resize"><?php echo currency_format($account['_stats']['balance']); ?></b>
                      <?php } else if($account['_stats']['credit'] < $account['_stats']['debit']) { ?>
                        <b>Balance <i class="fa fa-arrow-down" aria-hidden="true" class="cwhite"></i><br></b>
                        <b class="text_resize"><?php echo "- ".currency_format($account['_stats']['balance']); ?></b>
                      <?php } else { ?>
                        <b>Balance<br></b>
                        <b class="text_resize"><?php echo currency_format($account['_stats']['balance']); ?></b>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <!-- Balance -->

              </div>
              <!-- Boxes -->

              <!-- Overview -->
              <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <tbody>
                  <?php if($account['_stats']['credit'] > $account['_stats']['debit']) { ?>
                    <tr class="center app_heading_bc">
                      <?php if($this->session->userdata('uuid') == $account['user_uuid']) { ?>
                        <td><b>You need to receive <?php echo currency_format($account['_stats']['balance']); ?> from <?php echo $account['_friend']['name'] ?? $account['account_name']; ?></b></td>
                      <?php } else { ?>
                        <td><b>You need to receive <?php echo currency_format($account['_stats']['balance']); ?> from <?php echo $account['_user']['name']; ?></b></td>
                      <?php } ?>
                    </tr>
                  <?php } else if($account['_stats']['credit'] < $account['_stats']['debit']) { ?>
                    <tr class="center app_heading_bc">
                      <?php if($this->session->userdata('uuid') == $account['user_uuid']) { ?>
                        <td><b>You need to pay <?php echo currency_format($account['_stats']['balance']); ?> to <?php echo $account['_friend']['name'] ?? $account['account_name']; ?></b></td>
                      <?php } else { ?>
                        <td><b>You need to pay <?php echo currency_format($account['_stats']['balance']); ?> to <?php echo $account['_user']['name']; ?></b></td>
                      <?php } ?>
                    </tr>
                  <?php } else if($account['_stats']['credit'] != 0 && $account['_stats']['debit'] != 0) { ?>
                    <tr class="center app_heading_bc">
                      <td><b>ACCOUNT CLEARED</b></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
              <!-- Overview -->

          </div>
          <!-- Body -->

          <!-- Footer -->
          <div class="card-footer d-flex align-items-center justify-content-between hbcolor accounts_footer">
            <div class="medium app_color"><b><?php echo date_text_format($account['created_dt']); ?></b></div>
            <div class="medium">
              <a href="<?php echo base_url(); ?>account/view/<?php echo $account['uuid']; ?>?search=<?php echo $search ?? ""; ?>" class="btn btn-green btn-icon mr-2" ><i class="fa fa-eye"></i></a>
              <button class="btn btn-purple btn-icon mr-2" data-toggle="modal" data-target="#edit_account_modal_<?php echo $account['uuid']; ?>"><i class="fa fa-pen"></i></button>
              <button class="btn btn-red btn-icon mr-2" data-toggle="modal" data-target="#delete_account_modal_<?php echo $account['uuid']; ?>"><i class="fa fa-trash"></i></button>
            </div>
          </div>
          <!-- Footer -->

          <!-- Edit & delete Modal -->
          <?php include('account_edit_delete.php'); ?>
          <!-- Edit & delete Modal -->

        </div>
      </div>
      <!-- Account -->

    <?php } ?>
    <!-- Each Account -->

  </div>
  <!-- Row end -->

<?php } ?>
<!-- List Of Accounts -->

<!-- Resize text size based on length -->
<script>
$('.text_resize').each(function(){
  var el= $(this);
  var textLength = el.html().length;
  if (textLength > 7) {
    el.css('font-size', '0.7em');
  }
});
</script>
<!-- Resize text size based on length -->