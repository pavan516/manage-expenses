<!-- Search Form -->
<div class="card mb-2">
  <div class="card-header hbcolor friends_header">Search Friends</div>
  <div class="card-body">
    <form id="searchfriendsform">

      <!-- Select date from & to -->
      <div class="form-row">
        <div class="form-group col-md-12 mb05em">
          <label for="search" class="pl10">Search your friends, relatives, etc...</label>
          <input type="text" name="search" id="search" class="form-control" value="<?php echo $search; ?>" placeholder="name, email, mobile number, code" required/>
        </div>
      </div>
      <!-- Select date from & to -->

      <!-- Submit Button -->
      <div class="form-group center mb0em">
        <input type="submit" class="btn create_button" value="SEARCH">
      </div>
      <!-- Submit Button -->

    </form>
  </div>
</div>
<!-- Search Form -->

<!-- Search result -->
<div id="load_search_list"></div>
<!-- Search result -->

<!-- Friends search -->
<script>
$("#searchfriendsform").on('submit',(function(e) {
  e.preventDefault();
  $.ajax({
    url: "friends/search/list",
    type: "POST",
    data:  new FormData(this),
    contentType: false,
    cache: false,
    processData:false,
    success: function(data) {
      $('#load_search_list').fadeIn().html(data);
    }
  });
}));

/** Load friends if search is not empty */
var search = "<?php echo $search; ?>";
if(search) {
  /** load search list */
  load_search_list(search);
}
</script>