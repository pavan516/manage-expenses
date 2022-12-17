<!-- Followers List -->
<?php $count = 1; ?>
<?php if(!empty($friends)) { ?>

  <!-- Loop each user -->
  <?php foreach($friends as $friend) { ?>

    <!-- User Info -->
    <div class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center flex-shrink-0 mr-3 ml5">
        <div class="avatar avatar-xl mr-3 bg-gray-200"><img class="avatar-img img-fluid" src="<?php echo base_url().$this->config->item('user_images').$friend['_user']['image']; ?>" alt /></div>
        <div class="d-flex flex-column font-weight-bold">
          <a class="text-dark line-height-normal mb-1" href="#!"><?php echo $friend['_user']['name']; ?></a>
          <div class="friend_text"><?php echo $friend['_user']['code']; ?></div>
        </div>
      </div>
      <form>
        <!-- remove request -->
        <button class="btn btn-red btn-icon mr-2 fs20 follower_remove" data-toggle="modal" data-target="#remove_follower_modal_<?php echo $friend['_user']['uuid']; ?>"><i class="fa fa-trash"></i></button>
        <!-- remove request -->
      </form>
    </div>
    <!-- User Info -->

    <!-- Add Line -->
    <?php if($count != count($friends)) echo "<div class='friends_hr'></div>"; ?>
    <?php $count++ ?>
    <!-- Add Line -->

    <!-- Delete follower modal -->
    <div class="modal fade" id="remove_follower_modal_<?php echo $friend['_user']['uuid']; ?>" tabindex="-1" role="dialog" aria-labelledby="remove_follower_modal_title" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

          <!-- Body -->
          <div class="modal-body">

            <!-- Remove friend confirmation form -->
            <form>

              <!-- Confirmation text -->
              <div class="center pb10">Do you want to remove (<?php echo \strtolower($friend['_user']['name']); ?>) as your follower?</div>
              <!-- Confirmation text -->

              <!-- Submit Button -->
              <div class="form-group center mb0em">
                <input class="btn yes_button" type="submit" id="delete_follower_<?php echo $friend['_user']['uuid']; ?>" value="YES">
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

    <!-- Delete Follower -->
    <script>
    $(document).ready(function (e) {
      $("#delete_follower_<?php echo $friend['_user']['uuid']; ?>").click(function(e) {
        e.preventDefault();
        var uuidparam = "<?php echo $friend['_user']['uuid']; ?>";
        var statusparam = "DELETE";
        var dataString = 'user_uuid='+uuidparam+'&status='+statusparam;
        $.ajax({
          type:'POST',
          data:dataString,
          url:'<?php echo base_url(); ?>friends/acceptrejectfriend',
          success:function(data) {
            /** close modal */
            $('#remove_followers_modal_<?php echo $friend['_user']['uuid']; ?>').modal('hide');
            $(".modal-backdrop").remove();
            $('body').removeClass('modal-open');
            /** load required functions */
            load_followers_list("<?php echo $search; ?>");
          }
        });
      });
    });
    </script>
    <!-- Delete Follower -->

  <?php } ?>
  <!-- Loop each user -->

<?php } else { ?>

  <!-- User Info -->
  <div class="center">NO FOLLOWERS TO DISPLAY</div>
  <!-- User Info -->

<?php } ?>
<!-- Followers List -->