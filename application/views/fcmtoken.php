<!DOCTYPE html>
<html lang="en">

<!-- Head -->
<head>

  <!-- Title -->
  <title itemprop="name">Manage Expenses</title>

  <!-- Jquery Script -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <!-- Update fcm token -->

</head>
<!-- Head -->

<!-- Body -->
<body>

  <!-- Fcm token div -->
  <div class="display_none;" id="firebase_token"></div>
  <!-- Fcm token div -->

  <!-- Update fcm token -->
  <script type="text/javascript">

    try {
      /** load app data */
      this.loadAppData = function() {
        /** get the data from android app */
        return firebase_token.getFirebaseToken();
      };

      /** append to div */
      $('#firebase_token').html(this.loadAppData);

      /** get value from html div by id */
      var fcm_token = document.getElementById('firebase_token').innerHTML;

      /** get the data from android app & update */
      $.ajax({
        url: "auth/update/fcm_token?fcm_token="+fcm_token,
        method: "GET",
        async: true,
        success: function(data) {}
      });
    } catch(error) {
      /** by default redirect to home page */
      window.location = "<?php echo base_url(); ?>personal";
    }

    /** by default redirect to home page */
    window.location = "<?php echo base_url(); ?>personal";

  </script>
  <!-- Update fcm token -->

</body>
<!-- Body -->

</html>