<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Events Controller
 */
class EventsController extends CI_Controller
{
  # Constructor
  function __construct()
  {
    # Parent constructor
    parent:: __construct();

    # Configurations & hide errors
    \set_time_limit(0);
    \error_reporting(0);
    \ini_set('display_errors', 0);

    # Models
    $this->load->model('UserModel');
    $this->load->model('FriendsModel');
    $this->load->model('EventsModel');
    $this->load->model('NotificationsModel');
    $this->load->model('LibraryModel');

    # Helpers
    $this->load->helper('url');
    $this->load->helper('mylibrary');

    # redirect to login page if session does not exist
    if(empty($this->session->userdata('mobile'))) {
      # return
      \redirect('auth/login', 'refresh');
    }

    # feature enable or disable check
    if($this->session->userdata('feature_events') == 0) {
      # redirect to load disabled page
      \redirect(\base_url().'feature/access?name=Events Financial Tracker', 'refresh');
    }

    # move to two factor authentication page if security_events is enabled
    if($this->session->userdata('security_events') == 1) {
      # redirect to two factor authentication page
      \redirect(\base_url().'feature/authentication?name=Events Financial Tracker&url=events&code=security_events');exit;
    }
  }

#################################################################################################################################
#################################################################################################################################
########################################################   EVENTS   #############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: index
   * Build dropdown data
   *
   * Expected query params
   * 1. type
   * 2. mode
   * 3. status
   * 4. search
   *
   * @return page       events page
   * @throws Exception  Log error
   */
  public function index()
  {
    try {
      # default parameters
      $data = [];
      $data['type']     = \trim($this->input->get('type') ?? "");
      $data['mode']     = \trim($this->input->get('mode') ?? "");
      $data['status']   = \trim($this->input->get('status') ?? "");
      $data['search']   = \trim($this->input->get('search') ?? "");
      $data['filters']  = [
        ""                          =>  "ALL EVENTS & TRIPS",
        "LIVE_EVENTS_INDIVIDUAL"    =>  "INDIVIDUAL EVENTS LIVE",
        "LIVE_TRIPS_INDIVIDUAL"     =>  "INDIVIDUAL TRIPS LIVE",
        "CLOSED_EVENTS_INDIVIDUAL"  =>  "INDIVIDUAL EVENTS CLOSED",
        "CLOSED_TRIPS_INDIVIDUAL"   =>  "INDIVIDUAL TRIPS CLOSED",
        "LIVE_EVENTS_GROUP"         =>  "GROUP EVENTS LIVE",
        "LIVE_TRIPS_GROUP"          =>  "GROUP TRIPS LIVE",
        "CLOSED_EVENTS_GROUP"       =>  "GROUP EVENTS CLOSED",
        "CLOSED_TRIPS_GROUP"        =>  "GROUP TRIPS CLOSED"
      ];
      $data['selected_filter'] = "";

      # build status
      if(strlen($data['type'])>0 && strlen($data['mode'])>0 && strlen($data['status'])>0) {
        $status = $data['status'] ? "LIVE" : "CLOSED";
        $data['selected_filter'] = $status."_".$data['mode']."S_".$data['type'];
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception building data for events page!", [
        'data'    => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load events page
    $this->load->view('events', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: addEvent
   * Add event page, build friends
   *
   * @return page       add event block
   * @throws Exception  Log error
   */
  public function addEvent()
  {
    try {

      # get friends
      $data = [];
      $data['users']    = $this->FriendsModel->fetchFriends(['user_uuid'=>$this->session->userdata('uuid'), 'status'=>'ACCEPTED', 'friend'=>1]);
      $data['friends']  = $this->convertToMultiselectAdd($data['users']);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception building data for add events block!", [
        'data'    => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load events or trips create page
    $this->load->view('events/add', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: editEvent
   * Edit/delete event page
   *
   * @param   string    $eventUuid
   *
   * @return  page      edit/delete event page
   * @throws  Exception Log error
   */
  public function editEvent(string $eventUuid)
  {
    try {

      # get event
      $data['event'] = $this->EventsModel->fetchEvents(['uuid'=>$eventUuid])[0];

      # load view events or trips page
      if($data['event']['type'] == "GROUP") {
        # add memebers list for group event
        $data['event'] = $this->EventsModel->fetchGroupEvents(['uuid'=>$eventUuid, 'expand'=>['members']])[0];
      }

      # get Friends
      $data['friends'] = $this->FriendsModel->fetchFriends(['user_uuid'=>$this->session->userdata('uuid'), 'status'=>'ACCEPTED', 'friend'=>1]);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception building data for edit/delete events block!", [
        'data'    => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load events or trips create page
    $this->load->view('events/group_edit_delete', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: viewEvents
   * Load list of events (trips/events & individual/group)
   * - load individual events seperately
   * - load group events seperately
   * - merge all events in desc order based on id
   *
   * Expected query params
   * 1. type
   * 2. mode
   * 3. status
   * 4. search
   *
   * @return page       load events block
   * @throws Exception  Log error
   */
  public function viewEvents()
  {
    try {

      # get parameters
      $type = $this->input->get('type') ?? "";
      $params = [
        'mode'  => $this->input->get('mode') ?? "",
        'status'=> $this->input->get('status') ?? "",
        'search'=> $this->input->get('search') ?? "",
        'expand'=> ['members']
      ];

      # Build data to sent based on params
      if($type == "INDIVIDUAL") {
        $params = \array_merge(['user_uuid'=>$this->session->userdata('uuid')], $params);
        $data['events'] = $this->EventsModel->fetchIndividualEvents($params);
      } else if($type == "GROUP") {
        $params = \array_merge(['member_uuid'=>$this->session->userdata('uuid')], $params);
        $data['events'] = $this->EventsModel->fetchGroupEvents($params);
      } else {
        $indvParams = \array_merge(['user_uuid'=>$this->session->userdata('uuid')], $params);
        $indv_events = $this->EventsModel->fetchIndividualEvents($indvParams);
        $groupParams = \array_merge(['member_uuid'=>$this->session->userdata('uuid')], $params);
        $group_events = $this->EventsModel->fetchGroupEvents($groupParams);
        $data['events'] = \array_merge($group_events,$indv_events);
      }

      # Filter events response
      $data['events'] = $this->filterEventsResp($data['events']);

      # get Friends
      $data['friends'] = $this->FriendsModel->fetchFriends(['user_uuid'=>$this->session->userdata('uuid'), 'status'=>'ACCEPTED', 'friend'=>1]);

      # add default params
      $data['type'] = $this->input->get('type') ?? "";
      $data['mode'] = $this->input->get('mode') ?? "";
      $data['status'] = $this->input->get('status') ?? "";
      $data['search'] = $this->input->get('search') ?? "";

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception building data for load events block!", [
        'data'    => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load view events or trips page
    $this->load->view('events/views', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: insertEvent
   * This internal method will create an event/trip
   * - create event
   * - if event type is group
   *   - add members in event members table
   *   - send notification to each user regarding the event
   *
   * Expected post body
   * 1. mode
   * 2. name
   * 3. type
   * 4. budget
   * 5. friends
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function insertEvent()
  {
    try {
      # make sure mode is not empty
      if(empty($this->input->post('mode'))) throw new \Exception("Please select plan!", 400);

      # make sure name is not empty
      if(empty($this->input->post('name'))) throw new \Exception("Please enter your event/trip name!", 400);

      # make sure type is not empty
      if(empty($this->input->post('type'))) throw new \Exception("Please select event type!", 400);

      # make sure friends is not empty
      if($this->input->post('type') == "GROUP") {
        if(empty($this->input->post('friends'))) throw new \Exception("Please add friends!", 400);
      }

      # Build body
      $body = [];
      $body['uuid']             = $this->LibraryModel->UUID();
      $body['user_uuid']        = $this->session->userdata('uuid');
      $body['name']             = $this->input->post('name') ?? "";
      $body['type']             = $this->input->post('type') ?? "";
      $body['mode']             = $this->input->post('mode') ?? "";
      $body['budget']           = $this->input->post('budget') ?? 0;
      $body['status']           = 1;
      $body['add_to_personal']  = 0;
      $body['planned_at']       = \get_date();
      $body['closed_at']        = null;
      $body['created_dt']       = \get_date_time();
      $body['modified_dt']      = \get_date_time();

      # begin transaction
      $this->db->trans_begin();

        # insert event
        $this->db->insert('events', $body);

        # get event_id
        $event_id = $this->db->insert_id();

        # Add friends
        if( $this->input->post('type')=="GROUP" && !empty($this->input->post('friends')) ) {
          # By default add current user to a group
          $member = [];
          $member['event_id']   = $event_id;
          $member['event_uuid'] = $body['uuid'];
          $member['user_uuid']  = $this->session->userdata('uuid');
          $member['role']       = "ADMIN";
          $member['status']     = "ACCEPTED";

          # insert member
          $this->db->insert('event_members', $member);

          # insert each friend into group
          foreach($this->input->post('friends') as $friend) {
            # make sure friend is not the current user
            if($friend != $this->session->userdata('uuid')) {
              # Build Body
              $member = [];
              $member['event_id']   = $event_id;
              $member['event_uuid'] = $body['uuid'];
              $member['user_uuid']  = $friend;
              $member['role']       = "";
              $member['status']     = "PENDING"; // ACCEPTED // REJECTED // PENDING

              # insert member
              $this->db->insert('event_members', $member);

              # generate UUID
              $uuid = $this->LibraryModel->UUID() ?? "";

              # send notification to friend
              $this->NotificationsModel->sendNotification([
                'uuid'          => $uuid,
                'sender_uuid'   => $this->session->userdata('uuid'),
                'user_uuid'     => $friend,
                'activity_type' => "GROUP_INVITATION",
                'source_url'    => "event/view/".$body['uuid']."?mode=&status=&type=&search=&notification=".$uuid,
                'title'         => "Group ".ucfirst($this->input->post('mode'))." Invitation",
                'message'       => "<b>".$this->session->userdata('name')."</b> sent you a invitation to join ".strtoupper($this->input->post('name'))." ".$this->input->post('mode'),
                'image_url'     => !empty($this->session->userdata('image')) ? $this->session->userdata('image') : "default.jpg"
              ]);
            }
          }
        }

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception creating an event!", [
        'post'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # print error
      print_r($e->getMessage() ?? "Failed to create, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: updateEvent
   * This endpoint will update the event
   * - if event type is group
   *   - update group members
   *
   * Expected post body
   * 1. mode
   * 2. name
   * 3. type
   * 4. budget
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function updateEvent()
  {
    try {
      # make sure mode is not empty
      if(empty($this->input->post('mode')))  throw new \Exception("Please select plan!", 400);

      # make sure name is not empty
      if(empty($this->input->post('name'))) throw new \Exception("Please enter your event/trip name!", 400);

      # make sure type is not empty
      if(empty($this->input->post('type'))) throw new \Exception("Please select event type!", 400);

      # make sure friends is not empty
      if($this->input->post('type') == "GROUP") {
        if(empty($this->input->post('friends'))) throw new \Exception("Please add friends!", 400);
      }

      # Build body
      $body = [];
      $body['mode']         = $this->input->post('mode') ?? "";
      $body['name']         = $this->input->post('name') ?? "";
      $body['budget']       = $this->input->post('budget') ?? "";
      $body['modified_dt']  = \get_date_time();

      # begin transaction
      $this->db->trans_begin();
        # get event
        $getEvent = $this->EventsModel->fetchEvents(['uuid'=>$this->input->post('uuid')])[0];
        if(empty($getEvent)) throw new \Exception("Event not found!", 404);

        # update event
        $this->db->where('uuid', $this->input->post('uuid'))->update('events', $body);

        # update members - only if event type is group
        if($this->input->post('type') == "GROUP") {
          # get old members
          $oldMembers = [];
          $getMembers = $this->EventsModel->fetchMembers(['event_uuid'=>$this->input->post('uuid')]);
          foreach($getMembers as $getMember) {
            $oldMembers[] = $getMember['user_uuid'];
          }

          # get new Members
          $newMembers = explode(",", $this->input->post('friends'));

          # Update friends
          $this->updateMembersInGroup($getEvent['id'], $this->input->post('uuid'), $oldMembers, $newMembers);
        }

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating an event!", [
        'post'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # print error
      print_r($e->getMessage() ?? "Failed to update, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: deleteEvent
   * This endpoint will delete the event
   * - using on delete cascade
   *   - will delete event_members
   *   - will delete group_event_expenses
   *   - will delete group_event_payments
   *   - will delete individual_event_expenses
   *
   * Expected arguments
   * 1. eventUuid
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function deleteEvent($eventUuid)
  {
    try {

      # begin transaction
      $this->db->trans_begin();

        # Delete event
        $this->db->query("DELETE FROM `events` where uuid='".$eventUuid."'");

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception deleting an event!", [
        'event'   => $eventUuid ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r($e->getMessage() ?? "Failed to delete, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: memberUpdateStatus
   * This endpoint will accept or reject member in event
   *
   * Expected post body
   * 1. uuid (groupUuid)
   * 2. status
   * 3. userUuid (get from session)
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function memberUpdateStatus()
  {
    # Parameters
    $groupUuid = $this->input->post('uuid');
    $userUuid  = $this->session->userdata('uuid');
    $status    = $this->input->post('status');

    try {

      # begin transaction
      $this->db->trans_begin();

        # update member status if he accept the event
        if($status == "ACCEPTED") {
          $this->db->query("UPDATE `event_members` SET `status`='".$status."' where event_uuid='".$groupUuid."' AND user_uuid='".$userUuid."'");
        }

        # delete the member from group if he reject the event
        if($status == "REJECTED") {
          $this->db->query("DELETE FROM `event_members` where event_uuid='".$groupUuid."' AND user_uuid='".$userUuid."'");
        }

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating member status in event!", [
        'group'   => $groupUuid ?? null,
        'status'  => $status ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r($e->getMessage() ?? "something went wrong, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#################################################################################################################################
#################################################################################################################################
########################################################   EVENT   ##############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: viewEvent
   */
  public function viewEvent($eventUuid)
  {
    try {

      # update notification if notification.uuid exist
      if(!empty($this->input->get('notification'))) {
        # update notification status to 1
        $this->LibraryModel->updateNotifications(['uuid'=>$this->input->get('notification'), 'status'=>1]);
      }

      # get parameters
      $data = [];
      $data['old_par_type']   = $this->input->get('type') ?? "";
      $data['old_par_mode']   = $this->input->get('mode') ?? "";
      $data['old_par_status'] = $this->input->get('status') ?? "";
      $data['old_par_search'] = $this->input->get('search') ?? "";
      $data['event']          = [];

      # get event
      $getEvent = $this->EventsModel->fetchEvents(['uuid'=>$eventUuid]);

      # load event deleted page - if event not found
      if(empty($getEvent)) {
        # load view page
        $this->load->view('eventnotfound', $data);
      } else {
        # event data
        $data['event'] = $getEvent[0];
        # load view events or trips page
        if($data['event']['type'] == "GROUP") {
          # add memebers list for group event
          $data['event'] = $this->EventsModel->fetchGroupEvents(['uuid'=>$eventUuid, 'expand'=>['members']])[0];
          # load view page
          $this->load->view('groupeventview', $data);
        } else {
          # load view page
          $data['tab'] = $this->input->get('tab') ?? "overview";
          $data['expenses'] = $this->EventsModel->fetchIndividualExpenses(['event_uuid'=>$eventUuid,'expand'=>['statistics']]);
          $this->load->view('individualeventview', $data);
        }
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching event related data!", [
        'event'   => $eventUuid ?? null,
        'data'    => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # load view page
      $this->load->view('eventnotfound', $data);
    }
  }

#################################################################################################################################
#################################################################################################################################
###################################################   INDIVIDUAL EVENT   ########################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: insertIndividualEventExpenses
   * Insert individual event expenses
   *
   * Expected post body
   * 1. event_uuid
   * 2. title
   * 3. value
   * 4. date
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function insertIndividualEventExpenses()
  {
    try {

      # begin transaction
      $this->db->trans_begin();

        # get event
        $getEvent = $this->EventsModel->fetchEvents(['uuid'=>$this->input->post('event_uuid')])[0];
        if($getEvent['status'] == 0) throw new \Exception("You can't add expenses, ".$getEvent['mode']." closed!", 400);

        # make sure title is not empty
        if(empty($this->input->post('title'))) throw new \Exception("Please enter the title!", 400);

        # make sure value is not empty
        if(empty($this->input->post('value'))) throw new \Exception("Please enter an amount!", 400);

        # Build body
        $body = [];
        $body['event_id']   = $getEvent['id'];
        $body['event_uuid'] = $getEvent['uuid'];
        $body['title']      = $this->input->post('title') ?? "";
        $body['value']      = $this->input->post('value') ?? "";
        $body['date']       = \get_date($this->input->post('date') ?? \date('Y-m-d')) ?? null;

        # insert event
        $this->db->insert('individual_event_expenses', $body);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception inserting individual event expenses!", [
        'event'   => $getEvent ?? null,
        'body'    => $body ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      print_r($e->getMessage() ?? "Failed to save, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: individualEventExpensesOverview
   * Calculate expenses statistics for individual event
   *
   * @param string $eventUuid
   *
   * @return view       overview
   * @throws Exception  log error
   */
  public function individualEventExpensesOverview($eventUuid)
  {
    try {

      # get parameters
      $data = [];
      $data['tab']      = $this->input->get('tab') ?? "overview";
      $data['event']    = $this->EventsModel->fetchEvents(['uuid'=>$eventUuid])[0];
      $data['expenses'] = $this->EventsModel->fetchIndividualExpenses(['event_uuid'=>$eventUuid,'expand'=>['statistics']]);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching data for overview in individual event!", [
        'event'   => $eventUuid ?? null,
        'data'    => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load overview
    $this->load->view('events/expenses/individual/overview', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: individualEventExpensesDetails
   * List of individual event expenses
   *
   * @param string $eventUuid
   *
   * Expected get params
   * 1. offset - start record from
   * 2. limit  - number of records to return
   * 3. pageno - pagenohelps to find the start & limit
   * 4. name   - apply sorting on key field
   * 5. order  - apply asc or desc order
   * 6. search - search with date, title, value
   *
   * @return view       details
   * @throws Exception  log error
   */
  public function individualEventExpensesDetails($eventUuid)
  {
    try {

      # build data params
      $data = [];
      $data['offset'] = !empty($this->input->get("offset")) ? $this->input->get("offset") : 0;
      $data['limit']  = !empty($this->input->get("limit")) ? (int)$this->input->get("limit") : 25;
      $data['pageno'] = !empty($this->input->get("pageno")) ? (int)$this->input->get("pageno") : 1;
      $data['name']   = !empty($this->input->get("name")) ? $this->input->get("name") : "date";
      $data['order']  = !empty($this->input->get("order")) ? $this->input->get("order") : "desc";
      $data['search'] = !empty($this->input->get("search")) ? $this->input->get("search") : "";

      # calculate offset based on page no
      if($data['pageno'] != 1) {
        $data['offset'] = ($data['pageno']-1) * $data['limit'];
      }

      # Build data
      $data['event']  = $this->EventsModel->fetchEvents(['uuid'=>$eventUuid])[0];
      $data['items']  = $this->EventsModel->fetchIndividualExpenses(['event_uuid'=>$eventUuid, 'expand'=>['data']]);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching data for individual event details!", [
        'event'   => $eventUuid ?? null,
        'data'    => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load expenses
    $this->load->view('events/expenses/individual/details', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: updateIndividualEventExpenses
   * Update individual event expenses
   *
   * Expected post body
   * 1. id (event expenses id)
   * 2. title
   * 3. amount
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function updateIndividualEventExpenses()
  {
    try {
      # Parameters
      $id = $this->input->post('id');

      # make sure title is not empty
      if(empty($this->input->post('title') ?? '')) throw new \Exception("Please enter the title!");

      # make sure value is not empty
      if(empty($this->input->post('value') ?? '')) throw new \Exception("Please enter an amount!");

      # begin transaction
      $this->db->trans_begin();

        # get event expenses
        $getEventExpenses = $this->EventsModel->fetchIndividualExpenses(['id'=>$id, 'expand'=>['data']])[0];

        # make sure id is valid
        if(empty($getEventExpenses)) throw new \Exception("Event expenses not found!");

        # Build Body
        $body = [];
        $body['title'] = $this->input->post('title') ?? $getEventExpenses['title'];
        $body['value'] = $this->input->post('value') ?? $getEventExpenses['value'];

        # Update expenses
        $this->db->where('id',$id)->update('individual_event_expenses', $body);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating individual event expenses!", [
        'id'      => $id ?? null,
        'body'    => $body ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r($e->getMessage() ?? "something went wrong, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: deleteIndividualEventExpenses
   * Delete individual event expenses
   *
   * Expected post body
   * 1. id
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function deleteIndividualEventExpenses()
  {
    # Parameters
    $id = $this->input->post('id');

    try {

      # begin transaction
      $this->db->trans_begin();

        # get event expenses
        $getEventExpenses = $this->EventsModel->fetchIndividualExpenses(['id'=>$id, 'expand'=>['data']])[0];

        # make sure id is valid
        if(empty($getEventExpenses)) throw new \Exception("Event expenses not found!");

        # Update expenses
        $this->db->where('id',$id)->delete('individual_event_expenses');

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception deleting individual event expenses!", [
        'id'      => $id ?? null,
        'body'    => $body ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      print_r($e->getMessage() ?? "something went wrong, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: closeIndividualEventExpenses
   * Close individual event expenses, set event status to 0
   *
   * Expected post body
   * 1. uuid (event.uuid)
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function closeIndividualEventExpenses()
  {
    # Parameters
    $body = [];
    $eventUuid            = $this->input->post('uuid');
    $body['status']       = 0;
    $body['closed_at']    = \get_date();
    $body['modified_dt']  = \get_date_time();

    try {

      # begin transaction
      $this->db->trans_begin();

        # Update expenses
        $this->db->where('uuid',$eventUuid)->update('events', $body);

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception closing individual event!", [
        'id'      => $id ?? null,
        'body'    => $body ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      print_r($e->getMessage() ?? "something went wrong, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: addIndividualEventExpensesToPersonal
   * Add individual event expenses into personal expenses
   *
   * Expected post body
   * 1. uuid (event.uuid)
   * 2. expenses (amount)
   * 3. name (event.name)
   * 4. mode (event.mode)
   * 5. date
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function addIndividualEventExpensesToPersonal()
  {
    # Parameters
    $eventUuid    = $this->input->post('uuid');
    $expenses     = $this->input->post('expenses');
    $name         = $this->input->post('name');
    $mode         = $this->input->post('mode');
    $selectedDate = $this->input->post('date');
    $date         = new DateTime($selectedDate);
    $dayText      = \date('D', \strtotime($selectedDate));
    $monthText    = \date('M', \strtotime($selectedDate));

    # Build body
    $body = [];
    $body['uuid']         = $this->LibraryModel->UUID();
    $body['user_uuid']    = $this->session->userdata('uuid');
    $body['param_uuid']   = "";
    $body['type']         = "EXPENSES";
    $body['title']        = $name." (".$mode.")";
    $body['value']        = $expenses;
    $body['year']         = $date->format('Y');
    $body['month']        = $date->format('m');
    $body['month_text']   = $monthText;
    $body['day']          = $date->format('d');
    $body['day_text']     = $dayText;
    $body['date']         = $selectedDate;
    $body['created_dt']   = \get_date_time();
    $body['modified_dt']  = \get_date_time();

    try {

      # begin transaction
      $this->db->trans_begin();

        # Insert into personal
        $this->db->insert('personal', $body);

        # Update event
        $this->db->where('uuid',$eventUuid)->update('events', ['modified_dt'=>date('Y-m-d H:i:s'), 'add_to_personal'=>1]);

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception adding individual event expenses to personal!", [
        'data'    => $this->input->post() ?? null,
        'body'    => $body ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      print_r($e->getMessage() ?? "something went wrong, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#################################################################################################################################
#################################################################################################################################
#####################################################   GROUP EVENT   ###########################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: addGroupEventExpenses
   * Add group event expenses view
   *
   * @param   string    $eventUuid
   *
   * @return  page       add group expenses view
   * @throws  Exception  Log error
   */
  public function addGroupEventExpenses($eventUuid)
  {
    try {

      # get event
      $data            = [];
      $data['event']   = $this->EventsModel->fetchEvents(['uuid'=>$eventUuid])[0];
      if($data['event']['status'] == 0) {
        $data['member'] = $this->EventsModel->fetchGroupSplitShareData(['event_uuid'=>$eventUuid, 'user_uuid'=>$this->session->userdata('uuid')])[$this->session->userdata('uuid')];
      }

    } catch(\Exception $e) {
      # log error
      \logger("error", "Exception fetching group event!", [
        'event' => $eventUuid ?? null,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);
    }

    # load events or trips create page
    $this->load->view('events/expenses/group/add', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: exitGroup
   * This endpoint will remove user from an event & remove all his related data from the event
   *
   * Expected arguments
   * 1. eventUuid
   * 2. user_uuid (taken from session)
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function exitGroup($eventUuid)
  {
    # Parameters
    $userUuid = $this->session->userdata('uuid');

    try {

      # begin transaction
      $this->db->trans_begin();

        # delete current user expenses from group expenses
        $this->db->where('event_uuid', $eventUuid)->where('user_uuid', $userUuid)->delete('group_event_expenses');

        # delete current user event payments as paid payments
        $this->db->where('event_uuid', $eventUuid)->where('user_uuid', $userUuid)->delete('group_event_payments');

        # delete current user event payments as received payments
        $this->db->where('event_uuid', $eventUuid)->where('friend_uuid', $userUuid)->delete('group_event_payments');

        # delete current user from the event
        $this->db->where('event_uuid', $eventUuid)->where('user_uuid', $userUuid)->where('role !=', 'ADMIN')->delete('event_members');

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception user exiting from group event!", [
        'event' => $eventUuid ?? null,
        'user'  => $userUuid ?? null,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);

      # error
      print_r("something went wrong, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: insertGroupEventExpenses
   * Insert group event expenses
   *
   * Expected post body
   * 1. event_uuid
   * 2. title
   * 3. value
   * 4. split
   * 5. date
   * 6. user_uuid (taken from session)
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function insertGroupEventExpenses()
  {
    try {
      # get event
      $getEvent = $this->EventsModel->fetchEvents(['uuid'=>$this->input->post('event_uuid')])[0];
      if($getEvent['status'] == 0) throw new \Exception("You can't add expenses, ".$getEvent['mode']." closed!", 400);

      # make sure title is not empty
      if(empty($this->input->post('title'))) throw new \Exception("Please enter the title!", 400);

      # make sure value is not empty
      if(empty($this->input->post('value'))) throw new \Exception("Please enter an amount!", 400);

      # Build body
      $body = [];
      $body['event_id']   = $getEvent['id'];
      $body['event_uuid'] = $this->input->post('event_uuid') ?? "";
      $body['user_uuid']  = $this->session->userdata('uuid') ?? "";
      $body['title']      = $this->input->post('title') ?? "";
      $body['value']      = $this->input->post('value') ?? "";
      $body['split']      = $this->input->post('split') ?? 0;
      $body['date']       = \get_date_time($this->input->post('date') ?? date('Y-m-d H:i:s'));

      # begin transaction
      $this->db->trans_begin();

        # insert expenses
        $this->db->insert('group_event_expenses', $body);

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception inserting user group event expenses!", [
        'data'  => $this->input->post() ?? null,
        'user'  => $this->session->userdata('uuid') ?? null,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);

      # error
      print_r($e->getMessage() ?? "Failed to save, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: groupEventExpensesPersonal
   * Load personal user expenses done in group
   *
   * Expected argument
   * 1. eventUuid
   *
   * @return string     success msg
   * @throws Exception  log error
   */
  public function groupEventExpensesPersonal($eventUuid)
  {
    try {

      # get parameters
      $data = [];
      $data['event']    = $this->EventsModel->fetchEvents(['uuid'=>$eventUuid])[0];
      $data['member']   = $this->EventsModel->fetchGroupSplitShareData(['event_uuid'=>$eventUuid, 'user_uuid'=>$this->session->userdata('uuid')])[$this->session->userdata('uuid')];
      $data['expenses'] = $this->EventsModel->fetchGroupExpenses(['event_uuid'=>$eventUuid,'user_uuid'=>$this->session->userdata('uuid')])[0]['_expenses'] ?? [];

    } catch(\Exception $e) {
      # log error
      \logger("error", "Exception fetching user personal expenses in group event!", [
        'event' => $eventUuid ?? null,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);
    }

    # load overview
    $this->load->view('events/expenses/group/personal', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: updateGroupEventExpenses
   * Update group event expenses
   *
   * Expected post body
   * 1. id
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function updateGroupEventExpenses()
  {
    try {
      # Parameters
      $id = $this->input->post('id');

      # make sure title is not empty
      if(empty($this->input->post('title'))) throw new \Exception("Please enter the title!");

      # make sure value is not empty
      if(\strlen($this->input->post('value')) == 0) throw new \Exception("Please enter an amount!");

      # begin transaction
      $this->db->trans_begin();

        # fetch group expenses
        $getGroupExpenses = $this->db->select('*')->from('group_event_expenses')->where('id', $id)->get()->row_array();

        # make sure id is valid
        if(empty($getGroupExpenses)) throw new \Exception("Group event expenses not found!");

        # Build Body
        $body = [];
        $body['title'] = $this->input->post('title') ?? $getGroupExpenses['title'];
        $body['value'] = $this->input->post('value') ?? $getGroupExpenses['value'];
        $body['split'] = $this->input->post('split') ?? $getGroupExpenses['split'];
        $body['date']  = $this->input->post('date') ?? $getGroupExpenses['date'];

        # Update expenses
        $this->db->where('id',$id)->update('group_event_expenses', $body);

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating user group event expenses!", [
        'data'  => $this->input->post() ?? null,
        'user'  => $this->session->userdata('uuid') ?? null,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);

      # error
      print_r($e->getMessage() ?? "Failed to save, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: deleteGroupEventExpenses
   * Delete group event expenses
   *
   * Expected post body
   * 1. id
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function deleteGroupEventExpenses()
  {
    # Parameters
    $id = $this->input->post('id');

    try {

      # begin transaction
      $this->db->trans_begin();

        # Update expenses
        $this->db->where('id',$id)->delete('group_event_expenses');

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception deleting user group event expenses!", [
        'id'    => $this->input->post('id') ?? null,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);

      # error
      print_r($e->getMessage() ?? "Failed to delete, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: groupEventExpensesView
   * Group event expenses view
   *
   * Expected argument
   * 1. eventUuid
   *
   * @return page       group expenses view  page
   * @throws Exception  log error & event not found page
   */
  public function groupEventExpensesView($eventUuid)
  {
    try {
      # Build data
      $data = [];
      $data['event']    = $this->EventsModel->fetchEvents(['uuid'=>$eventUuid])[0];
      $getGroupExpenses = $this->EventsModel->fetchGroupExpenses(['event_uuid'=>$eventUuid,'split'=>1]);
      $data['expenses'] = $this->allMembersExpenses($getGroupExpenses);

      # raise exception - if event not found
      if(empty($data['event'])) throw new \Exception("Event not found!", 404);

      # load expenses
      $this->load->view('events/expenses/group/view', $data);
    } catch (\Exception $e) {
      # log error
      \logger("error", "Event not found!", [
        'event'   => $eventUuid ?? null,
        'data'    => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # load view page
      $this->load->view('eventnotfound', $data);
    }
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: groupEventExpensesOverview
   * Group event expenses overview
   *
   * Expected argument
   * 1. eventUuid
   *
   * @return view       group expenses overview
   * @throws Exception  log error
   */
  public function groupEventExpensesOverview($eventUuid)
  {
    try {

      # get parameters
      $data = [];
      $data['event']    = $this->EventsModel->fetchEvents(['uuid'=>$eventUuid])[0];
      $data['members']  = $this->EventsModel->fetchGroupSplitShareData(['event_uuid'=>$eventUuid]);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching group event expenses overview!", [
        'event'   => $eventUuid ?? null,
        'data'    => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load overview
    $this->load->view('events/expenses/group/overview', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: groupEventExpensesCharts
   * Group event expenses charts
   *
   * Expected argument
   * 1. eventUuid
   *
   * @return view       group expenses charts
   * @throws Exception  log error
   */
  public function groupEventExpensesCharts($eventUuid)
  {
    try {

      # get parameters
      $data = [];
      $data['event']     = $this->EventsModel->fetchEvents(['uuid'=>$eventUuid])[0];

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching group event charts page!", [
        'event'   => $eventUuid ?? null,
        'data'    => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load expenses
    $this->load->view('events/expenses/group/charts', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: groupEventExpensesGraphs
   * Group event expenses in graps view
   *
   * Expected argument
   * 1. eventUuid
   *
   * @return view       group expenses graphs view
   * @throws Exception  error msg
   */
  public function groupEventExpensesGraphs($eventUuid)
  {
    try {

      # Parameters
      $chart_type = $this->input->get('chart_type') ?? "";

      # make sure chart type is not empty
      if(empty($chart_type)) throw new \Exception("Please select any chart!", 400);

      # get data
      $data = [];
      $data['event']    = $this->EventsModel->fetchEvents(['uuid'=>$eventUuid])[0];
      $data['members']  = $this->EventsModel->fetchGroupSplitShareData(['event_uuid'=>$eventUuid]);

      # BAR CHART
      if($chart_type == "BAR_CHART") {
        # load pie chart
        $this->load->view('events/expenses/group/charts/barchart', $this->chartCalculations($data));
      }

      # AREA CHART
      if($chart_type == "AREA_CHART") {
        # load pie chart
        $this->load->view('events/expenses/group/charts/areachart', $this->chartCalculations($data));
      }

      # PIE CHART
      if($chart_type == "PIE_CHART") {
        # load pie chart
        $this->load->view('events/expenses/group/charts/piechart', $this->chartCalculations($data));
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception preparing charts for group event expenses!", [
        'event'   => $eventUuid ?? null,
        'data'    => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r($e->getMessage() ?? "something went wrong, please try after sometime!");exit;
    }
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: groupEventExpensesSplitShare
   * Group event expenses shares between group memebers
   *
   * Expected argument
   * 1. eventUuid
   *
   * @return view       group expenses split share view
   * @throws Exception  log error
   */
  public function groupEventExpensesSplitShare($eventUuid)
  {
    try {

      # get parameters
      $data = [];
      $data['event']    = $this->EventsModel->fetchEvents(['uuid'=>$eventUuid])[0];
      $data['members']  = $this->EventsModel->fetchGroupSplitShareData(['event_uuid'=>$eventUuid]);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching group event expenses split-share!", [
        'event'   => $eventUuid ?? null,
        'data'    => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load expenses
    $this->load->view('events/expenses/group/splitshare', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: groupEventPayments
   * Group event payments between group memebers
   *
   * Expected argument
   * 1. eventUuid
   *
   * @return view       group event payments
   * @throws Exception  log error
   */
  public function groupEventPayments($eventUuid)
  {
    try {

      # get parameters
      $data = [];
      $data['event']    = $this->EventsModel->fetchEvents(['uuid'=>$eventUuid])[0];
      $data['members']  = $this->EventsModel->fetchGroupSplitShareData(['event_uuid'=>$eventUuid]);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching group event expenses split-share!", [
        'event'   => $eventUuid ?? null,
        'data'    => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load expenses
    $this->load->view('events/expenses/group/payments', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: closeGroupEventExpenses
   * Close event group
   *
   * Expected post body
   * 1. uuid (event.uuid)
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function closeGroupEventExpenses()
  {
    # Parameters
    $body = [];
    $eventUuid            = $this->input->post('uuid');
    $body['status']       = 0;
    $body['closed_at']    = \get_date();
    $body['modified_dt']  = \get_date_time();

    try {

      # begin transaction
      $this->db->trans_begin();

        # Update event
        $this->db->where('uuid',$eventUuid)->update('events', $body);

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception closing the group event!", [
        'event'   => $eventUuid ?? null,
        'body'    => $body ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r($e->getMessage() ?? "something went wrong, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: paidGroupEventExpenses
   * Add group event expenses payments
   *
   * Expected post body
   * 1. min_amount
   * 2. max_amount
   * 3. amount
   * 4. event_uuid
   * 5. friend_uuid
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function paidGroupEventExpenses()
  {
    # Parameters
    $minAmount    = $this->input->post('min_amount') ?? 1;
    $maxAmount    = $this->input->post('max_amount') ?? 2;
    $amount       = $this->input->post('amount') ?? 0;
    $event_uuid   = $this->input->post('event_uuid') ?? "";
    $friend_uuid  = $this->input->post('friend_uuid') ?? "";
    $status       = "PAID";

    try {
      # get event
      $getEvent = $this->EventsModel->fetchEvents(['uuid'=>$this->input->post('event_uuid')])[0];
      if(empty($getEvent)) throw new \Exception("Event not found!", 404);

      # error validation
      if(empty($amount) || $amount == 0) throw new \Exception("Please enter the amount you paid!", 400);
      if($amount < $minAmount)  throw new \Exception("Please enter the amount more than ".$minAmount, 400);
      if($amount > $maxAmount)  throw new \Exception("Please enter the amount less than ".$maxAmount, 400);

      # Build body
      $body = [];
      $body['event_id']     = $getEvent['id'];
      $body['event_uuid']   = $event_uuid;
      $body['user_uuid']    = $this->session->userdata('uuid') ?? "";
      $body['friend_uuid']  = $friend_uuid;
      $body['amount']       = $amount;
      $body['status']       = $status;
      $body['created_dt']   = \get_date_time();

      # begin transaction
      $this->db->trans_begin();

        # insert expenses
        $this->db->insert('group_event_payments', $body);

      # commit ransaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception inserting user payment in group event!", [
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r($e->getMessage() ?? "Failed to save, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: addGroupEventExpensesToPersonal
   * Add group event expenses to personal
   *
   * Expected post body
   * 1. uuid (event.uuid)
   * 2. expenses (amount)
   * 3. name (event.name)
   * 4. mode (event.mode)
   * 5. friend_uuid
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function addGroupEventExpensesToPersonal()
  {
    # Parameters
    $eventUuid    = $this->input->post('uuid');
    $expenses     = $this->input->post('expenses');
    $name         = $this->input->post('name');
    $mode         = $this->input->post('mode');
    $selectedDate = $this->input->post('date');
    $date         = new DateTime($selectedDate);
    $dayText      = date('D', strtotime($selectedDate));
    $monthText    = date('M', strtotime($selectedDate));

    # Build body
    $body = [];
    $body['uuid']         = $this->LibraryModel->UUID();
    $body['user_uuid']    = $this->session->userdata('uuid');
    $body['param_uuid']   = "";
    $body['type']         = "EXPENSES";
    $body['title']        = $name." (".$mode.")";
    $body['value']        = $expenses;
    $body['year']         = $date->format('Y');
    $body['month']        = $date->format('m');
    $body['month_text']   = $monthText;
    $body['day']          = $date->format('d');
    $body['day_text']     = $dayText;
    $body['date']         = $selectedDate;
    $body['created_dt']   = date('Y-m-d H:i:s');
    $body['modified_dt']  = date('Y-m-d H:i:s');

    try {

      # commit ransaction
      $this->db->trans_commit();

        # Insert into personal
        $this->db->insert('personal', $body);

        # Update event member
        $this->db->where('event_uuid',$eventUuid)->where('user_uuid',$this->session->userdata('uuid'))->update('event_members', ['add_to_personal'=>1]);

      # commit ransaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception inserting group event expenses to personal!", [
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r($e->getMessage() ?? "Failed to add, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#################################################################################################################################
#################################################################################################################################
##############################################   INTERNAL PRIVATE METHODS   #####################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: filterEventsResp
   * This method will re-order the events based on its id
   *
   * @param   array   $events
   *
   * @return  array
   * @throws  Exception
   */
  public function filterEventsResp($events): array
  {
    # init var
    $data = [];

    try {
      # Build key as ids
      foreach($events as $event) {
        $data[$event['id']] = $event;
      }

      # sort keys in descending order
      \krsort($data, 1);
    } catch(\Exception $e) {
      # throw exception
      throw $e;
    }

    # return data
    return $data;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: updateMembersInGroup
   * This method will insert/delete the members from group
   *
   * @param   string  $eventId
   *
   * @param   string  $eventUuid
   *
   * @param   array   $oldMembers
   *
   * @param   array   $newMembers
   *
   * @return  bool
   * @throws  Exception
   */
  private function updateMembersInGroup(int $eventId, string $eventUuid, array $oldMembers, array $newMembers): bool
  {
    try {
      # unset the logged in user from old members
      foreach($oldMembers as $idx => $oldMember) {
        if($this->session->userdata('uuid') == $oldMember) unset($oldMembers[$idx]);
      }

      # unset the logged in user from new members
      foreach($newMembers as $idx => $newMember) {
        if($this->session->userdata('uuid') == $newMember) unset($newMembers[$idx]);
      }

      # check new member exist in old member list
      # if exist - just continue
      # if does not exist - just add them
      foreach($newMembers as $newMember) {
        if(!\in_array($newMember, $oldMembers)) {
          # Build Body
          $member = [];
          $member['event_id']   = $eventId;
          $member['event_uuid'] = $eventUuid;
          $member['user_uuid']  = $newMember;
          $member['role']       = "";
          $member['status']     = "PENDING"; // ACCEPTED // REJECTED // PENDING

          # insert member
          $this->db->insert('event_members', $member);
        }
      }

      # get difference of 2 arrays
      $getRemovedMembers = \array_diff($oldMembers,$newMembers);
      foreach($getRemovedMembers as $getRemovedMember) {
        # delete
        $this->db->where('event_uuid',$eventUuid)->where('user_uuid',$getRemovedMember)->delete('event_members');
      }
    } catch(\Exception $e) {
      # throw exception
      throw $e;
    }

    # return
    return true;
  }

/* ************************************************************************************************************** */
/* ************************************************************************************************************** */

  /**
   * method: groupExpensesCalculations
   * Calculate group expenses calculations + include paymens
   *
   * @param   string  $eventUuid
   *
   * @param   array   $data
   *
   * @return  array
   * @throws  Exception
   */
  private function groupExpensesCalculations(string $eventUuid, array $data=[]): array
  {
    # init var
    $expenses = [];
    $totalGroupAmount = 0;

    try {

      # fetch group payments
      $payments = $this->EventsModel->fetchGroupPayments(['event_uuid'=>$eventUuid]);

      # process each user
      foreach($data as $member) {
        # Init var
        $personalAmount = 0;
        $groupAmount = 0;

        # process each expenses
        foreach($member['_expenses'] as $expense) {
          if($expense['split'] == 1) {
            $groupAmount    = $groupAmount + $expense['value'];
          } else {
            $personalAmount = $personalAmount + $expense['value'];
          }
        }

        # total group amount
        $totalGroupAmount = $totalGroupAmount + $groupAmount;

        # push to an array
        $member['personal_expenses']  = $personalAmount;
        $member['group_expenses']     = $groupAmount;
        $member['paid_amount']        = $payments[$member['user_uuid']]['paid_amount'];
        $member['received_amount']    = $payments[$member['user_uuid']]['received_amount'];

        $expenses[] = $member;
      }

    } catch (\Exception $e) {
      # re-throw excepption
      throw $e;
    }

    # return response
    return $expenses;
  }

/* ************************************************************************************************************** */
/* ************************************************************************************************************** */

  /**
   * method: groupExpensesStatistics
   * Calculate group expenses statistics such as: total & share value
   *
   * @param   array  $members
   *
   * @return  array
   * @throws  Exception
   */
  private function groupExpensesStatistics(array $members=[]): array
  {
    # init var
    $statistics = [];
    $totalGroupAmount = 0;

    try {
      # process each user
      foreach($members as $member) {
        # calculate total group expenses
        $totalGroupAmount = $totalGroupAmount + $member['group_expenses'];
      }

      # Build statistics
      $statistics['total_group_expenses'] = $totalGroupAmount;
      $statistics['share']                = $totalGroupAmount/count($members);
    } catch (\Exception $e) {
      # re-throw excepption
      throw $e;
    }

    # return response
    return $statistics;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: allMembersExpenses
   * This method will combine all users expenses & push to an array
   *
   * @param   array  $members
   *
   * @return  array
   * @throws  Exception
   */
  private function allMembersExpenses(array $members): array
  {
    # Init variables
    $data = [];

    try {
      # process each member
      foreach($members as $member) {
        if(!empty($member['_expenses'] ?? [])) {
          foreach($member['_expenses'] as $item) {
            $item['user_name'] = $member['_user']['name'];
            $data[$item['id']] = $item;
          }
        }
      }

      # sort keys in descending order
      \krsort($data, 1);
    } catch (\Exception $e) {
      # re-throw excepption
      throw $e;
    }

    # return
    return $data;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: chartCalculations
   *
   * @param   array   $data
   *
   * Expected data to return
   * [
   *    "data": [],
   *    "xaxis": [],
   *    "percentages": [],
   *    "colors": []
   * ]
   *
   * @return  array
   * @throws  Exception
   */
  private function chartCalculations(array $data): array
  {
    # init var
    $member_names       = [];
    $member_expenses    = [];
    $member_percentages = [];
    $member_colors      = [];

    try {

      # do calculations
      foreach($data['members'] as $member) {
        # get member names
        $member_names[] = $member['user_name'];

        # get member expenses
        $totalExpenses = $member['group_expenses'] + $member['total_paid_amount'] - $member['total_received_amount'];
        $member_expenses[] = $totalExpenses;

        # get member percentage
        $percentage  = 0;
        if($member['total_group_expenses'] != 0) {
          $percentage  = \number_format(($totalExpenses/$member['total_group_expenses']) * 100, 2);
        }
        $member_percentages[] = $percentage;

        # get member color
        $member_colors[] = "#".$this->random_color();
      }

      # Build data
      $data = [];
      $data['data']           = $member_expenses;
      $data['xaxis']          = $member_names;
      $data['percentages']    = $member_percentages;
      $data['colors']         = $member_colors;

    } catch (\Exception $e) {
      # re-throw excepption
      throw $e;
    }

    # return result
    return $data;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: convertToMultiselectAdd
   *
   * @param   array   $friends
   *
   * Expected data to return
   * [
   *    {
   *       disabled: false, // by default it will be false
   *       groupId: 1, // by default it will be 1
   *       groupName: "", // by default it will be select friends
   *       id: 1, // friend_uuid
   *       name: "", // friend name
   *       selected: false // by default it will be false
   *    }
   * ]
   *
   * @return  array
   * @throws  Exception
   */
  private function convertToMultiselectAdd(array $friends): array
  {
    # Init var
    $result = [];

    try {
      # loop each friend
      foreach($friends as $friend) {
        # build data
        $data = [];
        $data['disabled']   = false;
        $data['groupId']    = 1;
        $data['groupName']  = "Select Friends";
        $data['id']         = $friend['_friend']['uuid'];
        $data['name']       = $friend['_friend']['name']."(".$friend['_friend']['mobile'].")";
        $data['selected']   = false;

        # push to an array
        $result[] = $data;
      }
    } catch (\Exception $e) {
      # re-throw excepption
      throw $e;
    }

    # return
    return $result;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: random_color
   * Generate 6 chars random rgb color
   *
   * @return  string
   * @throws  Exception
   */
  private function random_color(): string
  {
    try {
      # return
      return $this->random_color_part().$this->random_color_part().$this->random_color_part();
    } catch (\Exception $e) {
      # re-throw excepption
      throw $e;
    }
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: random_color_part
   * Generate random color string hexa
   *
   * @return  string
   * @throws  Exception
   */
  private function random_color_part(): string
  {
    try {
      # return
      return \str_pad( \dechex( \mt_rand( 0, 255 ) ), 2, '0', \STR_PAD_LEFT);
    } catch (\Exception $e) {
      # re-throw excepption
      throw $e;
    }
  }

}?>