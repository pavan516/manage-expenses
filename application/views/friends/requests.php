<!-- Friend Requests -->
<div class="card mb-2">

  <!-- Heading -->
  <div class="card-header hbcolor friends_header">Friend Requests</div>
  <!-- Heading -->

  <!-- Friend requests card body -->
  <div class="card-body friends_scroll" id="requests_card_body_height">

    <!-- Make sure we have users -->
    <?php $count = 1; ?>
    <?php if(!empty($users)) { ?>

      <!-- Loop each user request -->
      <?php foreach($users as $user) { ?>

        <!-- User Info -->
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center flex-shrink-0 mr-3 ml5">
            <div class="avatar avatar-xl mr-3 bg-gray-200"><img class="avatar-img img-fluid" src="<?php echo base_url().$this->config->item('user_images').$user['_user']['image']; ?>" alt /></div>
            <div class="d-flex flex-column font-weight-bold">
              <a class="text-dark line-height-normal mb-1" href="#!"><?php echo $user['_user']['name']; ?></a>
              <div class="friend_text"><?php echo $user['_user']['code']; ?></div>
            </div>
          </div>
          <form>
            <!-- accept request -->
            <button class="btn btn-success btn-icon mr-2 friend_accept_btn" id="acceptrequest_<?php echo $user['_user']['uuid']; ?>"><i class="fa fa-check"></i></button>
            <!-- accept request -->
            <!-- reject request -->
            <button class="btn btn-red btn-icon mr-2 friend_reject_btn" id="rejectrequest_<?php echo $user['_user']['uuid']; ?>"><i class="fa fa-times"></i></button>
            <!-- reject request -->
          </form>
        </div>
        <!-- User Info -->

        <!-- Add Line -->
        <?php if($count != count($users)) echo "<div class='friends_hr'></div>"; ?>
        <?php $count++ ?>
        <!-- Add Line -->

        <!-- Accept / Reject Request -->
        <script>
        $(document).ready(function (e) {
          $("#acceptrequest_<?php echo $user['_user']['uuid']; ?>").click(function(e) {
            e.preventDefault();
            var uuidparam = "<?php echo $user['_user']['uuid']; ?>";
            var statusparam = "ACCEPTED";
            var dataString = 'user_uuid='+uuidparam+'&status='+statusparam;
            $.ajax({
              type:'POST',
              data:dataString,
              url:'<?php echo base_url(); ?>friends/acceptrejectfriend',
              success:function(data) {
                load_friend_requests();
              }
            });
          });
          $("#rejectrequest_<?php echo $user['_user']['uuid']; ?>").click(function(e) {
            e.preventDefault();
            var uuidparam = "<?php echo $user['_user']['uuid']; ?>";
            var statusparam = "DELETE";
            var sendnotification = "YES";
            var dataString = 'user_uuid='+uuidparam+'&status='+statusparam+'&send_notification='+sendnotification;
            $.ajax({
              type:'POST',
              data:dataString,
              url:'<?php echo base_url(); ?>friends/acceptrejectfriend',
              success:function(data) {
                load_friend_requests();
              }
            });
          });
        });
        </script>
        <!-- Accept / Reject Request -->

      <?php } ?>
      <!-- Loop each user request -->

    <?php } else { ?>
      <!-- User Info -->
      <div class="center">NO FRIEND REQUESTS</div>
      <!-- User Info -->
    <?php } ?>
    <!-- Make sure we have users -->

  </div>
</div>
<!-- Friend Requests -->

<!-- Custom script -->
<script>
/** Calculate the html page height */
var datatableScrollY = $(window).height() * 0.235;
document.getElementById("requests_card_body_height").style.maxHeight = "calc(100vh - "+datatableScrollY+"px)";
</script>
