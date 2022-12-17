<!-- Overview -->
<div class="card-body border_radius_0">
  <div class="row">

    <!-- Credit -->
    <div class="col-xs-4 pr5 width_33per">
      <div class="card app_bcolor_lgrey p5">
        <div class="card-body center app_bcolor_lgrey app_color_green">
          <b>Credit <i class="fa fa-arrow-up" aria-hidden="true" class="app_color_green"></i>&nbsp;<br></b>
          <b class="text_resize"><?php echo currency_format($stats['credit']); ?></b>
        </div>
      </div>
    </div>
    <!-- Credit -->

    <!-- Debit -->
    <div class="col-xs-4 pr5 width_33per">
      <div class="card app_bcolor_lgrey p5">
        <div class="card-body center app_bcolor_lgrey app_color_red">
          <b>Debit <i class="fa fa-arrow-down" aria-hidden="true" class="app_color_red"></i>&nbsp;<br></b>
          <b class="text_resize"><?php echo currency_format($stats['debit']); ?></b>
        </div>
      </div>
    </div>
    <!-- Debit -->

    <!-- Balance -->
    <div class="col-xs-4 width_33per">
      <div class="card app_color p5">
        <div class="card-body center app_heading_bc">
          <?php if($stats['credit'] > $stats['debit']) { ?>
            <b>Balance <i class="fa fa-arrow-up" aria-hidden="true" class="cwhite"></i><br></b>
            <b class="text_resize"><?php echo currency_format($stats['balance']); ?></b>
          <?php } else if($stats['credit'] < $stats['debit']) { ?>
            <b>Balance <i class="fa fa-arrow-down" aria-hidden="true" class="cwhite"></i><br></b>
            <b class="text_resize"><?php echo "- ".currency_format($stats['balance']); ?></b>
          <?php } else { ?>
            <b>Balance<br></b>
            <b class="text_resize"><?php echo currency_format($stats['balance']); ?></b>
          <?php } ?>
        </div>
      </div>
    </div>
    <!-- Balance -->

  </div>
</div>
<!-- Overview -->

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