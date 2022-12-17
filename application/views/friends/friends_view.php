<!-- Friends View -->
<div class="card mb-2">

  <!-- Heading -->
  <div class="card-header hbcolor friends_header">My Friends
    <a class="friends_search" href="#" onclick="my_friends_search()"><i class="fa fa-search cwhite pr5 fs22" id="cross_search_icon"></i></a>
  </div>
  <!-- Heading -->

  <!-- Search Form -->
  <div id="friends_search" class="card-body <?php if(empty($search)) echo "display_none"; ?>">
    <form>
      <div class="form-row">
        <div class="form-group col-md-12 mb0em">
          <div class="input-group">
            <?php if(!empty($search)) { ?>
              <input type="search" class="form-control py-2" value="<?php echo $search; ?>" id="fsearch" required>
            <?php } else { ?>
              <input type="search" class="form-control py-2" placeholder="search" id="fsearch" required>
            <?php } ?>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- Search Form -->

  <!-- Load friends list -->
  <div class="card-body friends_scroll" id="friends_card_body_height">
    <div id="load_friends_list"></div>
  </div>
  <!-- Load friends list -->

</div>
<!-- Friends View -->

<!-- Calculate the html page height -->
<script>
/** Search key down */
var timer = null;
$('#fsearch').keydown(function() {
  clearTimeout(timer);
  timer = setTimeout(doFriendsSearch, 500);
});

/** Calculate the html page height */
var datatableScrollY = $(window).height() * 0.235;
document.getElementById("friends_card_body_height").style.maxHeight = "calc(100vh - "+datatableScrollY+"px)";

/** doSearch function */
function doFriendsSearch()
{
  /** get search variable */
  var search = document.getElementById("fsearch").value;

  /** load friends list */
  load_friends_list(search);

  /** focus on search */
  document.getElementById('fsearch').focus();
}

/** My friends search */
function my_friends_search()
{
  /** get element */
  var searchDiv = document.getElementById("friends_search");

  /** append style */
  if (searchDiv.style.display === "block") {
    searchDiv.style.display = "none";
    $("#cross_search_icon").removeClass("fas fa-times fs25").addClass("fa fa-search fs22");
  } else {
    searchDiv.style.display = "block";
    $("#cross_search_icon").removeClass("fa fa-search fs22").addClass("fas fa-times fs25");
  }
}

/** By default load friends list */
load_friends_list("<?php echo $search; ?>");
</script>