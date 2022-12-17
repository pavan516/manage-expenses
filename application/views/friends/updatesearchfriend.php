<?php if(!empty($user)) {
  if($user['_friend'] == "REQUESTED") { ?>
    <form id="update_search_friend_form">
      <!-- status -->
      <input type="hidden" id="status_<?php echo $user['uuid']; ?>" name="status_<?php echo $user['uuid']; ?>" value="DELETE">
      <!-- status -->
      <!-- Request button -->
      <input type="submit" name="send_search_request_<?php echo $user['uuid']; ?>" id="send_search_request_<?php echo $user['uuid']; ?>" class="btn btn-secondary" value="Pending">
      <!-- Request button -->
    </form>
  <?php } else if($user['_friend'] == "ACCEPTED") { ?>
    <form id="update_search_friend_form">
      <!-- status -->
      <input type="hidden" id="status_<?php echo $user['uuid']; ?>" name="status_<?php echo $user['uuid']; ?>" value="DELETE">
      <!-- status -->
      <!-- Request button -->
      <input type="submit" name="send_search_request_<?php echo $user['uuid']; ?>" id="send_search_request_<?php echo $user['uuid']; ?>" class="btn btn-danger" value="Un Friend">
      <!-- Request button -->
    </form>
  <?php } else { ?>
    <form id="update_search_friend_form">
      <!-- status -->
      <input type="hidden" id="status_<?php echo $user['uuid']; ?>" name="status_<?php echo $user['uuid']; ?>" value="REQUESTED">
      <!-- status -->
      <!-- Request button -->
      <input type="submit" name="send_search_request_<?php echo $user['uuid']; ?>" id="send_search_request_<?php echo $user['uuid']; ?>" class="btn btn-primary" value="Add Friend">
      <!-- Request button -->
    </form>
  <?php } ?>
<?php } ?>

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