


<!-- Tabs -->
<div class="card-header hbcolor">
  <ul class="nav nav-tabs card-header-tabs" id="cardTab" role="tablist">
    <li class="nav-item"><a class="nav-link <?php if($tab == "overview" || empty($tab)) echo "active"; ?>" id="overview-tab" href="#overview" data-toggle="tab" role="tab" aria-controls="overview" aria-selected="true">OVERVIEW</a></li>
    <li class="nav-item"><a class="nav-link <?php if($tab == "details") echo "active"; ?>" id="details-tab" href="#details" data-toggle="tab" role="tab" aria-controls="details" aria-selected="false">DETAILS</a></li>
    <li class="nav-item"><a class="nav-link <?php if($tab == "status") echo "active"; ?>" id="status-tab" href="#status" data-toggle="tab" role="tab" aria-controls="status" aria-selected="false">STATUS</a></li>
  </ul>
</div>
<!-- Tabs -->

<!-- Tab Contents -->
<div class="card-body pt10">
  <div class="tab-content" id="cardTabContent">

    <!-- Tab1 -->
    <div class="tab-pane fade <?php if($tab == "overview" || empty($tab)) echo "show active"; ?>" id="overview" role="tabpanel" aria-labelledby="overview-tab">
      <!-- Load overview -->
      <div id="individual_event_expenses_overview"></div>
      <!-- Load overview -->
    </div>
    <!-- Tab1 -->

    <!-- Tab2 -->
    <div class="tab-pane fade <?php if($tab == "details") echo "show active"; ?>" id="details" role="tabpanel" aria-labelledby="details-tab">
      <!-- Load details -->
      <div id="individual_event_expenses_details"></div>
      <!-- Load details -->
    </div>
    <!-- Tab2 -->

    <!-- Tab3 -->
    <div class="tab-pane fade <?php if($tab == "status") echo "show active"; ?>" id="status" role="tabpanel" aria-labelledby="status-tab">
      <!-- Load status -->
      <div id="individual_event_expenses_status"></div>
      <!-- Load status -->
    </div>
    <!-- Tab3 -->

  </div>
</div>
<!-- Tab Contents -->

<!-- Custom Scripts - Get Profile -->
<script type="text/javascript">
$(document).ready(function()
{
  /** Load required functions */
  load_individual_event_expenses_overview("<?php echo $event['uuid']; ?>");
  load_individual_event_expenses_details("<?php echo $event['uuid']; ?>");
  load_individual_event_expenses_status("<?php echo $event['uuid']; ?>");
});
</script>
<!-- Custom Scripts - Get Profile -->