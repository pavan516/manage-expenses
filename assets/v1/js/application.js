/*********************************************************************************************************************/
/************************************************** GLOBAL HETHODS ***************************************************/
/*********************************************************************************************************************/

/** Display sorting & pagination for data-tables */
$('table.display').DataTable();

/**
 * Method: enable_message
 *
 * Toaster info
 * 1. asynchronously: await Eggy
 * 2. position: ‘top-right’ (default), ‘top-left’, ‘bottom-right’, ‘bottom-left’
 * 3. type: ‘success’ (default), ‘warning’, ‘info’, ‘error’
 * 4. duration: Default: 5000ms
 * 5. styles: false (can set to false & implement own css)
 * 6. progressBar: false (to stop progressbar)
 * 7. title: title of the message
 * 8. message: details of the message
 *
 * @param   {string}  type
 * @param   {string}  title
 * @param   {string}  duration
 *
 * @returns {void}
 */
function enable_message(type="", title="", duration=2500)
{
  /** load toaster */
  Eggy({
    type:     type,
    title:    title,
    duration: duration
  });
}

/** convert date */
function date_format(date)
{
  /** get year, month, day */
  const dt = new Date(date);
  const ye = new Intl.DateTimeFormat('en', { year: '2-digit' }).format(dt);
  const mo = new Intl.DateTimeFormat('en', { month: 'short' }).format(dt);
  const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(dt);

  return `${getOrdinalNum(da.replace(/^0+/, ''))} ${mo}, ${ye}`;
}

/** get ordinal number */
function getOrdinalNum(number)
{
  /** Init var */
  let selector;

  /** value check */
  if (number <= 0) {
    selector = 4;
  } else if ((number > 3 && number < 21) || number % 10 > 3) {
    selector = 0;
  } else {
    selector = number % 10;
  }

  /** return */
  return number + ['th', 'st', 'nd', 'rd', ''][selector];
};

/*********************************************************************************************************************/
/******************************************** PROFILE RELATED METHODS ************************************************/
/*********************************************************************************************************************/

/** Load Profile */
function profile_profile()
{
  $.ajax({
    url: "profile/profile",
    method: "GET",
    async: true,
    success: function(data){
      $('#profile_profile').html(data);
    }
  });
}

/*********************************************************************************************************************/
/************************************** PERSONAL RESPONSIBILITIES RELATED METHODS ************************************/
/*********************************************************************************************************************/

/** Load Add Personal Resp */
function personal_responsibilities_add()
{
  /** Show loader */
  $(".se-pre-con").show();

  /** set color */
  update_personal_responsibilities_menu_colors("personal_responsibilities_add");

  $.ajax({
    url: "/responsibilities/personal/add",
    method: "GET",
    async: true,
    success: function(data){
      $('#personal_responsibilities_response').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load Personal List Resp */
function personal_responsibilities_list(search='', order='', name='', pageno='', limit='')
{
  /** set color */
  update_personal_responsibilities_menu_colors("personal_responsibilities_list");

  /** build params */
  var params = "?search="+search+"&order="+order+"&name="+name+"&pageno="+pageno+"&limit="+limit;

  $.ajax({
    url: "/responsibilities/personal/list"+params,
    method: "GET",
    async: true,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data){
      $('#personal_responsibilities_response').html(data);
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Update icon colors */
function update_personal_responsibilities_menu_colors(page)
{
  /** init array */
  var menus = ['personal_responsibilities_list', 'personal_responsibilities_add'];

  for(i=0; i<menus.length; i++) {
    if(menus[i] == page) {
      /** change icon bg color */
      document.getElementById(menus[i]).style.backgroundColor = "#ffffff";
      document.getElementById(menus[i]).style.color = "#1a6ca6";
      document.getElementById(menus[i]).style.borderColor = "#1a6ca6";
    } else {
      /** change icon bg color */
      document.getElementById(menus[i]).style.backgroundColor = "#1a6ca6";
      document.getElementById(menus[i]).style.color = "#ffffff";
    }
  }
}

/*********************************************************************************************************************/
/********************************************** SETTINGS RELATED METHODS *********************************************/
/*********************************************************************************************************************/

/** Load Settings */
function profile_settings()
{
  $.ajax({
    url: "settings",
    method: "GET",
    async: true,
    success: function(data){
      $('#profile_settings').html(data);
    }
  });
}

/*********************************************************************************************************************/
/****************************************** PERSONAL FEATURE RELATED METHODS *****************************************/
/*********************************************************************************************************************/

/** Load overview */
function load_overview()
{
  /** set color */
  update_menu_colors("overview");

  /** ajax call */
  $.ajax({
    url: "personal/overview",
    method: "GET",
    async: true,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      $('#personal_expenses_response').html(data);
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load add */
function load_add()
{
  /** set color */
  update_menu_colors("add");

  /** ajax call */
  $.ajax({
    url: "personal/add",
    method: "GET",
    async: true,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      $('#personal_expenses_response').html(data);
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load daytoday */
function load_daytoday(date='')
{
  /** set color */
  update_menu_colors("daytoday");

  /** ajax call */
  $.ajax({
    url: "personal/daytoday?date="+date,
    method: "GET",
    async: true,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      $('#personal_expenses_response').html(data);
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load daytoday overview */
function load_daytoday_overview(date='')
{
  /** ajax call */
  $.ajax({
    url: "personal/daytoday/overview?date="+date,
    method: "GET",
    async: true,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data){
      $('#personal_daytoday_response').html(data);
      /** tab active check */
      $("#daytoday_overview").addClass("tab_active");
      $("#daytoday_details").removeClass("tab_active");
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load monthly */
function load_monthly(tab="", date="")
{
  /** set color */
  update_menu_colors("monthly");

  /** ajax call */
  $.ajax({
    url: "personal/monthly?tab="+tab+"&date="+date,
    method: "GET",
    async: true,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      $('#personal_expenses_response').html(data);
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load monthly */
function load_monthly_overview(date="")
{
  /** ajax call */
  $.ajax({
    url: "personal/monthly/overview?date="+date,
    method: "GET",
    async: true,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** show/hide graphs view */
      document.getElementById("personal_monthly_response").style.display = "block";
      /** load html */
      $('#personal_monthly_response').html(data);
      /** tab active check */
      $("#monthly_overview").addClass("tab_active");
      $("#monthly_details").removeClass("tab_active");
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load yearly */
function load_yearly(year="")
{
  /** set color */
  update_menu_colors("yearly");

  /** ajax call */
  $.ajax({
    url: "personal/yearly?year="+year,
    method: "GET",
    async: true,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data){
      $('#personal_expenses_response').html(data);
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** show yearly tabs */
function load_yearly_tabs(tab)
{
  if(tab == "details") {
    /** tab active check */
    $("#yearly_overview").removeClass("tab_active");
    $("#yearly_details").addClass("tab_active");
    /** show or hide div's */
    document.getElementById("load_yearly_details").style.display = "block";
    document.getElementById("load_yearly_overview").style.display = "none";
  } else if(tab == "graphs") {
    /** tab active check */
    $("#yearly_overview").removeClass("tab_active");
    $("#yearly_details").removeClass("tab_active");
    /** show or hide div's */
    document.getElementById("load_yearly_overview").style.display = "none";
    document.getElementById("load_yearly_details").style.display = "none";
  } else {
    /** tab active check */
    $("#yearly_overview").addClass("tab_active");
    $("#yearly_details").removeClass("tab_active");
    /** show or hide div's */
    document.getElementById("load_yearly_overview").style.display = "block";
    document.getElementById("load_yearly_details").style.display = "none";
  }
}

/** Load responsibilities */
function load_responsibilities()
{
  /** set color */
  update_menu_colors("responsibilities");

  /** ajax call */
  $.ajax({
    url: "personal/responsibilities",
    method: "GET",
    async: true,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data){
      $('#personal_expenses_response').html(data);
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load custom */
function load_custom(fDate="", tDate="")
{
  /** set color */
  update_menu_colors("custom");

  /** ajax call */
  $.ajax({
    url: "personal/custom?cf_date="+fDate+"&ct_date="+tDate,
    method: "GET",
    async: true,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data){
      $('#personal_expenses_response').html(data);
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load custom */
function load_custom_overview(fDate="", tDate="")
{
  /** ajax call */
  $.ajax({
    url: "personal/custom/overview?cf_date="+fDate+"&ct_date="+tDate,
    method: "GET",
    async: true,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data){
      $('#personal_custom_response').html(data);
      $("#custom_overview").addClass("tab_active");
      $("#custom_details").removeClass("tab_active");
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load expenses */
function load_expenses(type='', date='', fdate='', tdate='', search='', order='', name='', pageno='', limit='')
{
  /** build params */
  var params = "type="+type+"&date="+date+"&f_date="+fdate+"&t_date="+tdate+"&search="+search+"&order="+order+"&name="+name+"&pageno="+pageno+"&limit="+limit;

  /** ajax call */
  $.ajax({
    url: "personal/expenses?"+params,
    method: "GET",
    async: true,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** load response + tab active check */
      if(type == "daytoday") {
        $('#personal_daytoday_response').html(data);
        $("#daytoday_overview").removeClass("tab_active");
        $("#daytoday_details").addClass("tab_active");
      } else if(type == "custom") {
        $('#personal_custom_response').html(data);
        $("#custom_overview").removeClass("tab_active");
        $("#custom_details").addClass("tab_active");
      } else {
        /** show/hide graphs view */
        document.getElementById("personal_monthly_response").style.display = "block";
        /** load html */
        $('#personal_monthly_response').html(data);
        /** tab active check */
        $("#monthly_overview").removeClass("tab_active");
        $("#monthly_details").addClass("tab_active");
      }
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Update icon colors */
function update_menu_colors(page)
{
  /** init array */
  var menus = ['overview', 'add', 'daytoday', 'monthly', 'yearly', 'responsibilities', 'custom'];

  for(i=0; i<menus.length; i++) {
    if(menus[i] == page) {
      /** change icon bg color */
      document.getElementById(menus[i]).style.backgroundColor = "#ffffff";
      document.getElementById(menus[i]).style.color = "#1a6ca6";
      document.getElementById(menus[i]).style.borderColor = "#1a6ca6";
      document.getElementById(menus[i]).style.borderStyle = "dashed";
    } else {
      /** change icon bg color */
      document.getElementById(menus[i]).style.backgroundColor = "#1a6ca6";
      document.getElementById(menus[i]).style.color = "#ffffff";
    }
  }
}

/*********************************************************************************************************************/
/******************************************** FRIENDS RELATED METHODS ************************************************/
/*********************************************************************************************************************/

/** Load friends view */
function load_friends_view(search="")
{
  /** Show loader */
  $(".se-pre-con").show();

  /** set color */
  update_friends_menu_colors("friends");

  $.ajax({
    url: "friends/friends/view?search="+search,
    method: "GET",
    async: true,
    success: function(data){
      $('#friends_response').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load friends */
function load_friends_list(search="")
{
  /** Show loader */
  $(".se-pre-con").show();

  /** set color */
  update_friends_menu_colors("friends");

  $.ajax({
    url: "friends/friends/list?search="+search,
    method: "GET",
    async: true,
    success: function(data){
      $('#load_friends_list').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load followers view */
function load_followers_view(search="")
{
  /** Show loader */
  $(".se-pre-con").show();

  /** set color */
  update_friends_menu_colors("followers");

  $.ajax({
    url: "friends/followers/view?search="+search,
    method: "GET",
    async: true,
    success: function(data){
      $('#friends_response').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load followers list */
function load_followers_list(search="")
{
  /** Show loader */
  $(".se-pre-con").show();

  /** set color */
  update_friends_menu_colors("followers");

  $.ajax({
    url: "friends/followers/list?search="+search,
    method: "GET",
    async: true,
    success: function(data){
      $('#load_followers_list').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load friend requests */
function load_friend_requests()
{
  /** Show loader */
  $(".se-pre-con").show();

  /** set color */
  update_friends_menu_colors("requests");

  $.ajax({
    url: "friends/requests",
    method: "GET",
    async: true,
    success: function(data){
      $('#friends_response').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load friends search view */
function load_search_view(search='')
{
  /** Show loader */
  $(".se-pre-con").show();

  /** set color */
  update_friends_menu_colors("search");

  $.ajax({
    url: "friends/search/view?search="+search,
    method: "GET",
    async: true,
    success: function(data){
      $('#friends_response').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load friends search list */
function load_search_list(search='')
{
  /** Show loader */
  $(".se-pre-con").show();

  /** set color */
  update_friends_menu_colors("search");

  $.ajax({
    url: "friends/search/list?search="+search,
    method: "GET",
    async: true,
    success: function(data){
      $('#load_search_list').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Update friends menu icon colors */
function update_friends_menu_colors(page)
{
  /** init array */
  var menus = ['friends','followers','requests','search'];

  /** update button background color */
  for(i=0; i<menus.length; i++) {
    /** make sure id.element exists */
    if($('#'+menus[i]).length) {
      if(menus[i] == page) {
        /** change icon bg color */
        document.getElementById(menus[i]).style.backgroundColor = "#ffffff";
        document.getElementById(menus[i]).style.color = "#1a6ca6";
        document.getElementById(menus[i]).style.borderColor = "#1a6ca6";
      } else {
        /** change icon bg color */
        document.getElementById(menus[i]).style.backgroundColor = "#1a6ca6";
        document.getElementById(menus[i]).style.color = "#ffffff";
      }
    }
  }
}

/*********************************************************************************************************************/
/********************************************* EVENTS RELATED METHODS ************************************************/
/*********************************************************************************************************************/

/** Load Add Events */
function load_events_add()
{
  $.ajax({
    url: "event/add",
    method: "GET",
    async: true,
    success: function(data){
      $('#events_add').html(data);
    }
  });
}

/** Load Events */
function load_events(mode,status,type,search)
{
  $.ajax({
    url: "events/view?mode="+mode+"&status="+status+"&type="+type+"&search="+search,
    method: "GET",
    async: true,
    success: function(data){
      $('#events').html(data);
    }
  });
}

/*********************************************************************************************************************/
/**************************************** INDIVIDUAL EVENTS RELATED METHODS ******************************************/
/*********************************************************************************************************************/

/** Load Individual Event Expenses Overview */
function load_iee_overview(eventUuid)
{
  $.ajax({
    url: "/event/expenses/individual/overview/"+eventUuid,
    method: "GET",
    async: true,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data) {
      /** load response + tab active check */
      $('#individual_event_response').html(data);
      $("#ie_overview").addClass("tab_active");
      $("#ie_details").removeClass("tab_active");
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load Individual Event Expenses Details */
function load_iee_details(eventUuid, search='', order='', name='', pageno='', limit='')
{
  /** build params */
  var params = "?search="+search+"&order="+order+"&name="+name+"&pageno="+pageno+"&limit="+limit;

  $.ajax({
    url: "/event/expenses/individual/details/"+eventUuid+params,
    method: "GET",
    async: true,
    beforeSend: function() {
      /** Show loader */
      $(".se-pre-con").show();
    },
    success: function(data){
      /** load response + tab active check */
      $('#individual_event_response').html(data);
      $("#ie_overview").removeClass("tab_active");
      $("#ie_details").addClass("tab_active");
    },
    complete:function() {
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/*********************************************************************************************************************/
/****************************************** GROUP EVENTS RELATED METHODS *********************************************/
/*********************************************************************************************************************/

/** Load edit group event view */
function load_edit_delete_group(eventUuid)
{
  /** Show loader */
  $(".se-pre-con").show();

  /** set color */
  update_group_menu_colors("edit_delete_group_event");

  /** ajax call */
  $.ajax({
    url: "/event/edit/"+eventUuid,
    method: "GET",
    async: true,
    success: function(data){
      $('#group_event_response').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load group event add expenses view */
function load_group_event_add_expenses(eventUuid)
{
  /** Show loader */
  $(".se-pre-con").show();

  /** set color */
  update_group_menu_colors("add_expenses");

  /** ajax call */
  $.ajax({
    url: "/event/expenses/group/add/"+eventUuid,
    method: "GET",
    async: true,
    success: function(data){
      $('#group_event_response').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load Group Event User Expenses */
function load_group_event_user_expenses(eventUuid)
{
  /** Show loader */
  $(".se-pre-con").show();

  /** set color */
  update_group_menu_colors("user_expenses");

  $.ajax({
    url: "/event/expenses/group/personal/"+eventUuid,
    method: "GET",
    async: true,
    success: function(data){
      $('#group_event_response').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load Group Event Expenses */
function load_group_event_expenses(eventUuid)
{
  /** Show loader */
  $(".se-pre-con").show();

  /** set color */
  update_group_menu_colors("group_expenses");

  $.ajax({
    url: "/event/expenses/group/view/"+eventUuid,
    method: "GET",
    async: true,
    success: function(data){
      $('#group_event_response').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}


/** Load Group Event Expenses Overview */
function load_group_event_expenses_overview(eventUuid)
{
  /** Show loader */
  $(".se-pre-con").show();

  $.ajax({
    url: "/event/expenses/group/overview/"+eventUuid,
    method: "GET",
    async: true,
    success: function(data){
      $('#group_event_expenses_overview').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load Group Event Expenses Charts */
function load_group_event_expenses_charts(eventUuid)
{
  /** Show loader */
  $(".se-pre-con").show();

  /** set color */
  update_group_menu_colors("group_charts");

  $.ajax({
    url: "/event/expenses/group/charts/"+eventUuid,
    method: "GET",
    async: true,
    success: function(data){
      $('#group_event_response').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load Group Event Expenses Split Share */
function load_group_event_expenses_split_share(eventUuid)
{
  /** Show loader */
  $(".se-pre-con").show();

  /** set color */
  update_group_menu_colors("group_shares");

  $.ajax({
    url: "/event/expenses/group/splitshare/"+eventUuid,
    method: "GET",
    async: true,
    success: function(data){
      $('#group_event_response').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Load Group Event Payments */
function load_group_event_payments(eventUuid)
{
  /** Show loader */
  $(".se-pre-con").show();

  /** set color */
  update_group_menu_colors("group_payments");

  $.ajax({
    url: "/event/expenses/group/payments/list/"+eventUuid,
    method: "GET",
    async: true,
    success: function(data){
      $('#group_event_response').html(data);
      /** Hide spinner */
      $(".se-pre-con").hide();
    }
  });
}

/** Update group menu icon colors */
function update_group_menu_colors(page)
{
  /** init array */
  var menus = ['edit_delete_group_event','user_expenses','group_expenses','group_charts','group_shares','group_payments','add_expenses'];

  /** update button background color */
  for(i=0; i<menus.length; i++) {
    /** make sure id.element exists */
    if($('#'+menus[i]).length) {
      if(menus[i] == page) {
        /** change icon bg color */
        document.getElementById(menus[i]).style.backgroundColor = "#ffffff";
        document.getElementById(menus[i]).style.color = "#1a6ca6";
        document.getElementById(menus[i]).style.borderColor = "#1a6ca6";
      } else {
        /** change icon bg color */
        document.getElementById(menus[i]).style.backgroundColor = "#1a6ca6";
        document.getElementById(menus[i]).style.color = "#ffffff";
      }
    }
  }
}

/*********************************************************************************************************************/
/****************************************** NOTIFICATIONS RELATED METHODS ********************************************/
/*********************************************************************************************************************/

/** Load Un-read Notifications Count */
function load_unread_notifications_count()
{
  $.ajax({
    url: "/notifications/unread/count",
    method: "GET",
    async: true,
    success: function(data){
      if(data == 0) {
        $('#load_unread_notifications_count').html("");
        $('#mark_all_as_read').html("");
      } else {
        $('#load_unread_notifications_count').html(data);
      }
    }
  });
}

/** Update Notification */
function updateNotifications(type="")
{
  /** Ajax call */
  $.ajax({
    url: "/notifications/update?type="+type,
    method: "GET",
    async: true,
    success: function(data) {
      /** reload the page on success */
      location.reload();
    }
  });
}

/*********************************************************************************************************************/
/********************************************** CHARTS RELATED METHODS ***********************************************/
/*********************************************************************************************************************/
/** Calculate Height */
function calcHeight()
{
  /** find the height of the internal page  & change the height of the iframe */
  document.getElementById('iframe').height = document.getElementById('iframe').contentWindow.document.body.scrollHeight;
};

/*********************************************************************************************************************/
/******************************************** ACCOUNTS RELATED METHODS ***********************************************/
/*********************************************************************************************************************/

/** Load Account */
function load_accounts(search='')
{
  $.ajax({
    url: "accounts/search?search="+search,
    method: "GET",
    async: true,
    success: function(data){
      $('#load_accounts').html(data);
    }
  });
}

/** Load Account Transactons */
function load_account_transactions(accountUuid)
{
  $.ajax({
    url: "/account/view/"+accountUuid+"/transactions",
    method: "GET",
    async: true,
    success: function(data){
      $('#load_account_transactions').html(data);
    }
  });
}

/** Load Account Transactons Overview */
function load_account_transactions_overview(accountUuid)
{
  $.ajax({
    url: "/account/view/"+accountUuid+"/transactions/overview",
    method: "GET",
    async: true,
    success: function(data){
      $('#load_account_transactions_overview').html(data);
    }
  });
}