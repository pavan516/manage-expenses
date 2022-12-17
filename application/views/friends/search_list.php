<!-- Friends Search -->
<?php $count = 1; ?>
<?php if(!empty($users)) { ?>
  <div class="card mb-2">
    <div class="card-header hbcolor friends_header">Make Friends</div>
    <div class="card-body friends_scroll" id="search_card_body_height">

      <!-- Load each user -->
      <?php foreach($users as $user) { ?>

        <!-- User Info -->
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center flex-shrink-0 mr-3 ml5">
            <div class="avatar avatar-xl mr-3 bg-gray-200"><img class="avatar-img img-fluid" src="<?php echo base_url().$this->config->item('user_images').$user['image']; ?>" alt /></div>
            <div class="d-flex flex-column font-weight-bold">
              <a class="text-dark line-height-normal mb-1" href="#!"><?php echo $user['name']; ?></a>
              <div class="friend_text"><?php echo $user['code']; ?></div>
            </div>
          </div>
          <div id="update_search_friend_result_<?php echo $user['uuid']; ?>">
            <?php if($user['_friend'] == "REQUESTED") { ?>
              <form id="update_search_friend_form">
                <!-- status -->
                <input type="hidden" id="status_<?php echo $user['uuid']; ?>" name="status_<?php echo $user['uuid']; ?>" value="DELETE">
                <!-- status -->
                <!-- Request button -->
                <input type="submit" name="send_search_request_<?php echo $user['uuid']; ?>" id="send_search_request_<?php echo $user['uuid']; ?>" class="btn btn-secondary friend_search_button" value="Pending">
                <!-- Request button -->
              </form>
            <?php } else if($user['_friend'] == "ACCEPTED") { ?>
              <form id="update_search_friend_form">
                <!-- status -->
                <input type="hidden" id="status_<?php echo $user['uuid']; ?>" name="status_<?php echo $user['uuid']; ?>" value="DELETE">
                <!-- status -->
                <!-- Request button -->
                <input type="submit" name="send_search_request_<?php echo $user['uuid']; ?>" id="send_search_request_<?php echo $user['uuid']; ?>" class="btn btn-danger friend_search_button" value="Un Friend">
                <!-- Request button -->
              </form>
            <?php } else { ?>
              <form id="update_search_friend_form">
                <!-- status -->
                <input type="hidden" id="status_<?php echo $user['uuid']; ?>" name="status_<?php echo $user['uuid']; ?>" value="REQUESTED">
                <!-- status -->
                <!-- Request button -->
                <input type="submit" name="send_search_request_<?php echo $user['uuid']; ?>" id="send_search_request_<?php echo $user['uuid']; ?>" class="btn btn-primary friend_search_button" value="Add Friend">
                <!-- Request button -->
              </form>
            <?php } ?>
          </div>
        </div>
        <!-- User Info -->

        <!-- Add Line -->
        <?php if($count != count($users)) echo "<div class='friends_hr'></div>"; ?>
        <?php $count++ ?>
        <!-- Add Line -->

        <!-- Send Request -->
        <script>
        $("#send_search_request_<?php echo $user['uuid']; ?>").click(function(e)
        {
          /** event */
          e.preventDefault();

          /** Build data */
          var friendParam = "<?php echo $user['uuid']; ?>";
          var statusParam = $("#status_<?php echo $user['uuid']; ?>").val();
          var dataString = 'friend_uuid='+friendParam+'&status='+statusParam;

          /** ajax call */
          $.ajax({
            type:'POST',
            data:dataString,
            url:'<?php echo base_url(); ?>friends/updatesearchfriend',
            beforeSend: function() {
              /** Show loader */
              $(".se-pre-con").show();
            },
            success: function(data) {
              /** update button view */
              $('#update_search_friend_result_<?php echo $user['uuid']; ?>').fadeIn().html(data);
            },
            complete:function() {
              /** Hide spinner */
              $(".se-pre-con").hide();
            }
          });
        });
        </script>
        <!-- Send Request -->

      <?php } ?>

    </div>
  </div>
<?php } else { ?>
  <!-- User Info -->
  <div class="card-body"><div class="center">USER NOT FOUND!</div></div>
  <!-- User Info -->
<?php } ?>
<!-- Friends Search -->

<script>
/** Calculate the html page height */
var datatableScrollY = $(window).height() * 0.480;
document.getElementById("search_card_body_height").style.maxHeight = "calc(100vh - "+datatableScrollY+"px)";
</script>
