<!-- Friends List -->
<?php $count = 1; ?>
<?php if(!empty($users)) { ?>

  <!-- Loop each user -->
  <?php foreach($users as $user) { ?>

    <!-- User Info -->
    <div class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center flex-shrink-0 mr-3 ml5">
        <div class="avatar avatar-xl mr-3 bg-gray-200"><img class="avatar-img img-fluid" src="<?php echo base_url().$this->config->item('user_images').$user['_friend']['image']; ?>" alt /></div>
        <div class="d-flex flex-column font-weight-bold">
          <a class="text-dark line-height-normal mb-1" href="#!"><?php echo $user['_friend']['name']; ?></a>
          <div class="friend_text"><?php echo $user['_friend']['code']; ?></div>
        </div>
      </div>
      <form>
        <!-- reject request -->
        <button class="btn btn-red btn-icon mr-2 friend_remove" data-toggle="modal" data-target="#remove_friend_modal_<?php echo $user['_friend']['uuid']; ?>"><i class="fa fa-user-times"></i></button>
        <!-- reject request -->
      </form>
    </div>
    <!-- User Info -->

    <!-- Add Line -->
    <?php if($count != count($users)) echo "<div class='friends_hr'></div>"; ?>
    <?php $count++ ?>
    <!-- Add Line -->

    <!-- Delete friend modal -->
    <div class="modal fade" id="remove_friend_modal_<?php echo $user['_friend']['uuid']; ?>" tabindex="-1" role="dialog" aria-labelledby="remove_friend_modal_title" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

          <!-- Body -->
          <div class="modal-body">

            <!-- Remove friend confirmation form -->
            <form>

              <!-- Confirmation text -->
              <div class="center pb10">Do you want to remove (<?php echo \strtolower($user['_friend']['name']); ?>) as your friend?</div>
              <!-- Confirmation text -->

              <!-- Submit Button -->
              <div class="form-group center mb0em">
                <input class="btn yes_button" type="submit" id="unfriend_<?php echo $user['_friend']['uuid']; ?>" value="YES">
                <button class="btn no_button" type="button" data-dismiss="modal">NO</button>
              </div>
              <!-- Submit Button -->

            </form>
            <!-- Remove friend confirmation form -->

          </div>
          <!-- Body -->

        </div>
      </div>
    </div>
    <!-- Delete friend modal -->

    <!-- Delete Friend -->
    <script>
    $(document).ready(function (e) {
      $("#unfriend_<?php echo $user['_friend']['uuid']; ?>").click(function(e) {
        e.preventDefault();
        var uuidparam = "<?php echo $user['_friend']['uuid']; ?>";
        var statusparam = "DELETE";
        var sendnotification = "NO";
        var dataString = 'friend_uuid='+uuidparam+'&status='+statusparam+'&send_notification='+sendnotification;
        $.ajax({
          type:'POST',
          data:dataString,
          url:'<?php echo base_url(); ?>friends/acceptrejectfriend',
          success:function(data) {
            /** close modal */
            $('#remove_friend_modal_<?php echo $user['_friend']['uuid']; ?>').modal('hide');
            $(".modal-backdrop").remove();
            $('body').removeClass('modal-open');
            /** load required functions */
            load_friends_list("<?php echo $search; ?>");
          }
        });
      });
    });
    </script>
    <!-- Delete Friend -->

  <?php } ?>
  <!-- Loop each user -->

<?php } else { ?>

  <!-- User Info -->
  <div class="center">NO FRIENDS TO DISPLAY <a href="<?php echo base_url(); ?>friends?tab=search&search=" class="app_color_blue">ADD FRIENDS HERE</a></div>
  <!-- User Info -->

<?php } ?>
<!-- Friends List -->