<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * NotificationsModel
 */
class NotificationsModel extends CI_Model
{
  # Constructor
  public function __construct()
  {
    # Parent constructor
    parent::__construct();

    # Models
    $this->load->model('LibraryModel');
    $this->load->model('UserModel');
  }

#################################################################################################################################
#################################################################################################################################
##################################################   NOTIFICATIONS   ############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * sendNotification
   *
   * @param   array  $data
   *
   * @return  bool      true/false
   * @throws  Exception re-throw
   */
  public function sendNotification(array $data=[]): bool
  {
    try {

      # build data
      $body = [];
      $body['uuid']               = $data['uuid'] ?? $this->LibraryModel->UUID() ?? ""; // UUID - AUTO GENERATED
      $body['sender_uuid']        = $data['sender_uuid'] ?? "";   // The uuid of the user who sends notification
      $body['user_uuid']          = $data['user_uuid'] ?? "";     // The uuid of the user who receives notification
      $body['activity_type']      = $data['activity_type'] ?? ""; // FRIEND_REQUEST | FRIEND_ACCEPTED | FRIEND_REJECTED
      $body['source_url']         = $data['source_url'] ?? "";    // URL to navigate notification to appropriate page
      $body['title']              = $data['title'] ?? "";         // Title of the notification
      $body['message']            = $data['message'] ?? "";       // Notification short message
      $body['notification_sent']  = 0;                            // 0 = NOT SENT | 1 = SENT
      $body['status']             = 0;                            // 0 = NOT SEEN | 1 = SEEN
      $body['created_dt']         = date('Y-m-d H:i:s');          // Current datetime

      # insert notification
      $this->db->insert('notifications', $body);

      # push notification to user + append user image
      $body['image_url'] = $data['image_url'] ?? ""; # Image url
      $sendNotification  = $this->LibraryModel->sendNotification($body);

      # if notification is sent successfully = update inserted record
      if($sendNotification) {
        # update notification
        $this->db->where('id', $this->db->insert_id())->update('notifications', ['notification_sent'=>1]);
      }

    } catch(\Exception $e) {
      # log error
      \logger("error", "Exception sending notification!", [
        'params'  => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
      # re-throw
      throw $e;
    }

    # assume all good
    return true;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * getNotifications
   *
   * @param   array  $params
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function getNotifications(array $params=[]): array
  {
    # init var
    $items  = [];
    $result = [];

    try {

      # build query
      $this->db->select('*')->from('notifications');

      # filter with uuid
      if(isset($params['uuid']) && !empty($params['uuid'])) {
        $this->db->where('uuid',$params['uuid']);
      }

      # filter with sender_uuid
      if(isset($params['sender_uuid']) && !empty($params['sender_uuid'])) {
        $this->db->where('sender_uuid',$params['sender_uuid']);
      }

      # filter with user_uuid
      if(isset($params['user_uuid']) && !empty($params['user_uuid'])) {
        $this->db->where('user_uuid',$params['user_uuid']);
      }

      # filter with activity_type
      if(isset($params['activity_type']) && !empty($params['activity_type'])) {
        $this->db->where('activity_type',$params['activity_type']);
      }

      # filter with notification_sent
      if(isset($params['notification_sent']) && !empty($params['notification_sent'])) {
        $this->db->where('notification_sent',$params['notification_sent']);
      }

      # filter with status
      if(isset($params['status']) && strlen($params['status'])>0) {
        $this->db->where('status',$params['status']);
      }

      # filter with from
      if(isset($params['from']) && !empty($params['from'])) {
        $this->db->where('created_dt >=',$params['from']);
      }

      # filter with to
      if(isset($params['to']) && !empty($params['to'])) {
        $this->db->where('created_dt <=',$params['to']);
      }

      # filter with limit
      if(isset($params['limit']) && !empty($params['limit'])) {
        $this->db->limit($params['limit']);
      }

      # filter with order_by_field & order_by
      if(isset($params['order_by_field']) && !empty($params['order_by_field']) && isset($params['order_by']) && !empty($params['order_by'])) {
        $this->db->order_by($params['order_by_field'], $params['order_by']);
      } else {
        # order by id desc
        $this->db->order_by("id", "desc");
      }

      # result query
      $items = $this->db->get()->result_array();

      # expand = sender
      if(isset($params['expand']) && !empty($params['expand']) && \in_array("sender", $params['expand'])) {
        foreach($items as $item) {
          $item['_sender'] = $this->UserModel->fetchUsers(['fields'=>'name,image','uuid'=>$item['sender_uuid']])[0];
          $result[] = $item;
        }

        # return response
        return $result;
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching notifications in models!", [
        'params'  => $params ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return [];
    }

    # return response
    return $items;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * getNotificationsCount
   *
   * @param   array  $params
   *
   * @return  int       count
   * @throws  Exception Log error
   */
  public function getNotificationsCount(array $params=[])
  {
    # init var
    $items  = [];

    try {

      # build query
      $this->db->select('count(id) as count')->from('notifications');

      # filter with uuid
      if(isset($params['uuid']) && !empty($params['uuid'])) {
        $this->db->where('uuid',$params['uuid']);
      }

      # filter with sender_uuid
      if(isset($params['sender_uuid']) && !empty($params['sender_uuid'])) {
        $this->db->where('sender_uuid',$params['sender_uuid']);
      }

      # filter with user_uuid
      if(isset($params['user_uuid']) && !empty($params['user_uuid'])) {
        $this->db->where('user_uuid',$params['user_uuid']);
      }

      # filter with activity_type
      if(isset($params['activity_type']) && !empty($params['activity_type'])) {
        $this->db->where('activity_type',$params['activity_type']);
      }

      # filter with notification_sent
      if(isset($params['notification_sent']) && !empty($params['notification_sent'])) {
        $this->db->where('notification_sent',$params['notification_sent']);
      }

      # filter with status
      if(isset($params['status']) && strlen($params['status'])>0) {
        $this->db->where('status',$params['status']);
      }

      # filter with from
      if(isset($params['from']) && !empty($params['from'])) {
        $this->db->where('created_dt >=',$params['from']);
      }

      # filter with to
      if(isset($params['to']) && !empty($params['to'])) {
        $this->db->where('created_dt <=',$params['to']);
      }

      # result query
      $items = $this->db->get()->row_array();

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching notifications count in models!", [
        'params'  => $params ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return 0
      return 0;
    }

    # return response
    return $items['count'];
  }

}?>