<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Friends Controller
 */
class FriendsController extends CI_Controller
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

    # move to two factor authentication page if security_friends is enabled
    if($this->session->userdata('security_friends') == 1) {
      # redirect to two factor authentication page
      \redirect(base_url().'feature/authentication?name=Friends&url=friends&code=security_friends');exit;
    }
  }

#################################################################################################################################
#################################################################################################################################
######################################################   FRIENDS   ##############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: index
   * Load friends page
   *
   * @return page       friends page
   * @throws Exception  Log error
   */
  public function index()
  {
    try {

      # update notification if notification.uuid exist
      if(!empty($this->input->get('notification'))) {
        # update notification status to 1
        $this->LibraryModel->updateNotifications(['uuid'=>$this->input->get('notification'), 'status'=>1]);
      }

      # build data
      $data['tab']    = $this->input->get('tab') ? $this->input->get('tab') : "friends";
      $data['search'] = $this->input->get('search') ? $this->input->get('search') : "";

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception loading friends page!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load friends page
    $this->load->view('friends', $data);
  }

#################################################################################################################################
#################################################################################################################################
###################################################   FRIENDS SEARCH  ###########################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: searchView
   * Search view
   *
   * @return page       search view
   * @throws Exception  Log error
   */
  public function searchView()
  {
    try {
      # Build data
      $data['search'] = $this->input->post('search') ?? $this->input->get('search') ?? "";
    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception loading searching view!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load friends search page
    $this->load->view('friends/search_view', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: searchList
   * Search friends
   *
   * @return page       search friends view
   * @throws Exception  Log error
   */
  public function searchList()
  {
    try {

      # Build data
      $data['search'] = $this->input->get('search') ? $this->input->get('search') : "";
      if(empty($data['search'])) $data['search'] = $this->input->post('search') ? $this->input->post('search') : "";
      $data['users']  = [];

      # get users list, only if search is not empty
      if(!empty($data['search'])) {
        $data['users'] = $this->UserModel->fetchUsers(['search'=>$data['search'], 'expand'=>'FRIEND']);
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception searching friends!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load friends search page
    $this->load->view('friends/search_list', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: updateSearchFriend
   * Request or delete friend request
   *
   * Expected post body
   * 1. mobile
   * 2. friend_uuid
   * 3. status
   *
   * @return page       button view
   * @throws Exception  Log error
   */
  public function updateSearchFriend()
  {
    # Init var
    $user_uuid    = $this->session->userdata('uuid') ?? "";
    $friend_uuid  = $this->input->post('friend_uuid') ?? "";
    $status       = $this->input->post('status') ?? "";

    # Build body
    $body = [];
    $body['user_uuid']    = $user_uuid;
    $body['friend_uuid']  = $friend_uuid;
    $body['status']       = $status;
    $body['created_date'] = \get_date_time();

    try {

      # begin transaction
      $this->db->trans_begin();

        # if status = delete => delete record
        if($status == "DELETE") {
          # delete friend from friends table
          $this->db->where('user_uuid',$user_uuid)->where('friend_uuid', $friend_uuid)->delete('friends');

          # delete notification
          $this->db->where('sender_uuid',$user_uuid)->where('user_uuid', $friend_uuid)->where('activity_type', "FRIEND_REQUEST")->delete('notifications');
        }

        # if status = requested => insert record
        if($status == "REQUESTED") {
          # insert Friend
          $this->db->insert('friends', $body);

          # generate UUID
          $uuid = $this->LibraryModel->UUID() ?? "";

          # send notification to friend
          $this->NotificationsModel->sendNotification([
            'uuid'          => $uuid,
            'sender_uuid'   => $user_uuid,
            'user_uuid'     => $friend_uuid,
            'activity_type' => "FRIEND_REQUEST",
            'source_url'    => "friends?tab=requests&notification=".$uuid,
            'title'         => "New Friend Request",
            'message'       => "hey, you got a new friend request from <b>".$this->session->userdata('name')."</b>",
            'image_url'     => !empty($this->session->userdata('image')) ? $this->session->userdata('image') : "default.jpg"
          ]);
        }

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollbak transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception requesting or deleting friend request!", [
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # return add remove accept friend view
    $data['user'] = $this->UserModel->fetchUsers(['uuid'=>$friend_uuid,'expand'=>'FRIEND'])[0] ?? [];

    # load button view
    $this->load->view('friends/updatesearchfriend', $data);
  }

#################################################################################################################################
#################################################################################################################################
###################################################   FRIEND REQUESTS  ##########################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: requests
   * Friend requests list
   *
   * @return page       friend requests page
   * @throws Exception  Log error
   */
  public function requests()
  {
    try {

      # Build data
      $data['users']  = $this->FriendsModel->fetchFriends(['friend_uuid'=>$this->session->userdata('uuid'), 'status'=>'REQUESTED', 'user'=>1]);

    } catch(\Exception $e) {
      # log error
      \logger("error", "Exception fetching friend requests!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load friend requests page
    $this->load->view('friends/requests', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: acceptrejectfriend
   * Accept or Reject friend request
   *
   * Expected post body
   * 1. status
   * 2. user_uuid / friend_uuid
   * 3. send_notification
   *
   * @return page       success msg
   * @throws Exception  Log error
   */
  public function acceptrejectfriend()
  {
    # Parameters
    $status = $this->input->post('status') ?? "";
    $user_uuid = $this->input->post('user_uuid') ?? $this->session->userdata('uuid') ?? "";
    $friend_uuid = $this->input->post('friend_uuid') ?? $this->session->userdata('uuid') ?? "";
    $send_notification = $this->input->post('send_notification') ?? "";

    try {

      # begin transaction
      $this->db->trans_begin();

        # if status = delete => delete record
        if($status == "DELETE") {
          # delete friend from friends table
          $this->db->where('friend_uuid',$friend_uuid)->where('user_uuid', $user_uuid)->delete('friends');

          # send notification to friend
          if(!empty($send_notification) && $send_notification == "YES") {

            # generate UUID
            $uuid = $this->LibraryModel->UUID() ?? "";

            $this->NotificationsModel->sendNotification([
              'uuid'          => $uuid,
              'sender_uuid'   => $friend_uuid,
              'user_uuid'     => $user_uuid,
              'activity_type' => $this->input->post('activity_type') ?? "FRIEND_REQUEST_REJECTED",
              'source_url'    => "friends?tab=search&mobile=".$this->session->userdata('mobile')."&notification=".$uuid,
              'title'         => "Friend Request Rejected",
              'message'       => "<b>".$this->session->userdata('name')."</b> rejected your friend request, please re-send!",
              'image_url'     => !empty($this->session->userdata('image')) ? $this->session->userdata('image') : "default.jpg"
            ]);
          }
        }

        # if status = accepted => update record
        if($status == "ACCEPTED") {
          # update friend
          $this->db->where('friend_uuid',$friend_uuid)->where('user_uuid', $user_uuid)->update('friends', ['status'=>$status]);

          # generate UUID
          $uuid = $this->LibraryModel->UUID() ?? "";

          # send notification to friend
          $this->NotificationsModel->sendNotification([
            'uuid'          => $uuid,
            'sender_uuid'   => $friend_uuid,
            'user_uuid'     => $user_uuid,
            'activity_type' => "FRIEND_REQUEST_ACCEPTED",
            'source_url'    => "friends?tab=friends&notification=".$uuid,
            'title'         => "Friend Request Accepted",
            'message'       => "<b>".$this->session->userdata('name')."</b> accepted your friend request",
            'image_url'     => !empty($this->session->userdata('image')) ? $this->session->userdata('image') : "default.jpg"
          ]);
        }

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollbak transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception accepting or rejecting fried request!", [
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r("something went wrong, please try after sometime!");exit;
    }

    # print success
    print_r("success");
  }

#################################################################################################################################
#################################################################################################################################
#####################################################   FOLLOWERS  ##############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: followersView
   * Followers view
   *
   * @return page       followers view page
   * @throws Exception  Log error
   */
  public function followersView()
  {
    try {
      # Build data
      $data['search'] = $this->input->get('search') ? $this->input->get('search') : "";
    } catch(\Exception $e) {
      # log error
      \logger("error", "Exception fetching followers view!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load friend requests page
    $this->load->view('friends/followers_view', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: followersList
   * Followers list
   *
   * @return page       followers list page
   * @throws Exception  Log error
   */
  public function followersList()
  {
    try {

      # Build data
      $data['search'] = $this->input->get('search') ? $this->input->get('search') : "";
      $data['friends']= $this->FriendsModel->fetchFriends(['friend_uuid'=>$this->session->userdata('uuid'), 'status'=>'ACCEPTED', 'user'=>1]);
      if(!empty($data['search']) && !empty($data['friends'])) {
        $data['friends'] = $this->filterFriends($data['friends'], $data['search'], "_user");
      }

    } catch(\Exception $e) {
      # log error
      \logger("error", "Exception fetching followers list!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load friend requests page
    $this->load->view('friends/followers_list', $data);
  }

#################################################################################################################################
#################################################################################################################################
######################################################   FRIENDS  ###############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: friendsView
   * Friend view
   *
   * @return page       friend view page
   * @throws Exception  Log error
   */
  public function friendsView()
  {
    try {
      # Build data
      $data['search'] = $this->input->get('search') ? $this->input->get('search') : "";
    } catch(\Exception $e) {
      # log error
      \logger("error", "Exception fetching friends view!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load friends page
    $this->load->view('friends/friends_view', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: friendsList
   * Friend list
   *
   * @return page       friend list page
   * @throws Exception  Log error
   */
  public function friendsList()
  {
    try {

      # Build data
      $userUuid       = $this->session->userdata('uuid');
      $data['search'] = $this->input->get('search') ? $this->input->get('search') : "";
      $data['users']  = $this->FriendsModel->fetchFriends(['user_uuid'=>$userUuid, 'search'=> $data['search'], 'status'=>'ACCEPTED', 'friend'=>1]);
      if(!empty($data['search']) && !empty($data['users'])) {
        $data['users'] = $this->filterFriends($data['users'], $data['search'], "_friend");
      }

    } catch(\Exception $e) {
      # log error
      \logger("error", "Exception fetching friends list!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load friends page
    $this->load->view('friends/friends_list', $data);
  }

#################################################################################################################################
#################################################################################################################################
##################################################  FILTER FRIENDS LIST  ########################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: filterFriends
   * search with name, mobile
   *
   * @param   array   $friends
   * @param   string  $search
   * @param   string  $key
   *
   * @return array      Friends
   * @throws Exception
   */
  public function filterFriends(array $friends, string $search, string $key)
  {
    try {

      # Init var
      $result = [];

      # foreach friends
      foreach($friends as $friend) {
        if( (\preg_match("/".$search."/i", $friend[$key]['name'])) || (\preg_match("/".$search."/i", $friend[$key]['mobile'])) ) {
          # push to an array
          $result[] = $friend;
        }
      }

    } catch(\Exception $e) {
      # rethrow exception
      throw $e;
    }

    # return result
    return $result;
  }

}?>