<!-- Heading -->
<div class="card-header personal_heading">
  <b><?php echo \strtoupper(\date('F')); ?> MONTH RESPONSIBILITIES</b>
</div>
<!-- Heading -->

<!-- Card body -->
<div class="card-body">

  <!-- Success/error message -->
  <div id="responsibilities_status"></div>
  <!-- Success/error message -->

  <!-- Show list of responsibilities -->
  <table class="custom_table">
    <thead>
      <tr>
        <th>TITLE</th>
        <th>AMOUNT</th>
        <th>STATUS</th>
      </tr>
    </thead>
    <tbody id="personal_table_tbody_height">
      <?php if(!empty($responsibilities)) {
        foreach($responsibilities as $item) { ?>

          <!-- Load data -->
          <?php if($item['type'] == "INCOME") { ?>
            <tr class="center app_color_green">
              <td><b><?php echo $item['title']; ?></b></td>
              <td><b><?php echo currency_format($item['value']); ?></b></td>
              <td class="center"><button class="btn btn-primary rounded-pill responsibility_button" id="updatepersonalparam_<?php echo $item['uuid']; ?>"><b>RECEIVED</b></button></td>
            </tr>
          <?php } else if($item['type'] == "INVESTMENT") { ?>
            <tr class="center app_color_blue">
              <td><b><?php echo $item['title']; ?></b></td>
              <td><b><?php echo currency_format($item['value']); ?></b></td>
              <td class="center"><button class="btn btn-secondary rounded-pill responsibility_button" id="updatepersonalparam_<?php echo $item['uuid']; ?>"><b>PAID</b></button></td>
            </tr>
          <?php } else { ?>
            <tr class="center app_color_red">
              <td><b><?php echo $item['title']; ?></b></td>
              <td><b><?php echo currency_format($item['value']); ?></b></td>
              <td class="center"><button class="btn btn-secondary rounded-pill responsibility_button" id="updatepersonalparam_<?php echo $item['uuid']; ?>"><b>PAID</b></button></td>
            </tr>
          <?php } ?>
          <!-- Load data -->

          <!-- Update ajax call -->
          <script>
          $("#updatepersonalparam_<?php echo $item['uuid']; ?>").click(function(e)
          {
            /** Init var */
            var response = "";
            e.preventDefault();

            /** Build Body */
            var uuid = "<?php echo $item['uuid']; ?>";
            var dataString = 'param_uuid='+uuid;

            /** ajax call */
            $.ajax({
              type:'POST',
              data:dataString,
              url:'<?php echo base_url(); ?>personal/insert',
              beforeSend: function() {
                /** Show loader */
                $(".se-pre-con").show();
              },
              success: function(data) {
                /** append data to response  */
                response = data;
                /** on success load all required methods */
                if(data == "success") {
                  /** Load required functions */
                  load_responsibilities();
                  /** Data table */
                  $("#respDataTable").dataTable();
                }
              },
              complete:function() {
                /** Hide spinner */
                $(".se-pre-con").hide();
                if(response == "success") {
                  /** load html */
                  $('#responsibilities_status').fadeIn().html(get_success_string("Success", "Successfully Saved!"));
                } else {
                  /** load html */
                  $('#responsibilities_status').fadeIn().html(get_error_string("Error", response));
                }
                /** close success or error msg */
                close_alert_message();
              }
            });
          });
          </script>
          <!-- Update ajax call -->

        <?php } ?>
      <?php } else { ?>
        <!-- No data -->
        <tr><td id="no_data_height" colspan="3" class="center">ALL RESPONSIBILITIES DONE</td></tr>
        <!-- No data -->
      <?php } ?>
    </tbody>
  </table>
  <!-- Show list of responsibilities -->

</div>
<!-- Card body end here -->

<!-- Scripts -->
<script>
/** Calculate the html page height */
<?php if(\count($responsibilities) >= 8) { ?>
  var datatableScrollY = $(window).height() * 0.36;
  document.getElementById("personal_table_tbody_height").style.height = "calc(100vh - "+datatableScrollY+"px)";
<?php } ?>
</script>
<!-- Scripts -->