<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * EventsModel
 */
class EventsModel extends CI_Model
{
  # Constructor
  public function __construct()
  {
    # Parent constructor
    parent::__construct();

    # Load users model
    $this->load->model('UserModel');
  }

#################################################################################################################################
#################################################################################################################################
#######################################################   EVENTS   ##############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * fetchGroupEvents
   * Params in an array supported are:
   * 1. by default type will be GROUP in events
   * 2. uuid (uuid in events & event_uuid in group members)
   * 3. user_uuid (user admin of the event)
   * 3. member_uuid (members of the event)
   * 4. memer_role
   * 5. member_status
   * 6. mode
   * 5. status
   * 6. planned_at
   * 7. planned_from
   * 8. planned_to
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchGroupEvents(array $data=[]): array
  {
    try {

      # build fields
      $fields = "e.id,e.uuid,e.user_uuid,e.name,e.type,e.mode,e.budget,e.status,e.add_to_personal,e.planned_at,e.closed_at,a.name as admin_name,a.image as admin_image";

      # build query
      $this->db->select($fields)->from('event_members as em');
      $this->db->join('events e','e.uuid = em.event_uuid','left')->where('e.type','GROUP');
      $this->db->join('users u','u.uuid = em.user_uuid','left');
      $this->db->join('users a','a.uuid = e.user_uuid','left');

      # filter with uuid
      if(isset($data['uuid']) && !empty($data['uuid'])) {
        $this->db->where('e.uuid',$data['uuid']);
      }

      # filter with user_uuid
      if(isset($data['user_uuid']) && !empty($data['user_uuid'])) {
        $this->db->where('e.user_uuid',$data['user_uuid']);
      }

      # filter with member_uuid
      if(isset($data['member_uuid']) && !empty($data['member_uuid'])) {
        $this->db->where('em.user_uuid',$data['member_uuid']);
      }

      # filter with member_role
      if(isset($data['member_role']) && !empty($data['member_role'])) {
        $this->db->where('em.role',$data['member_role']);
      }

      # filter with member_status
      if(isset($data['member_status']) && !empty($data['member_status'])) {
        $this->db->where('em.status',$data['member_status']);
      }

      # filter with mode
      if(isset($data['mode']) && !empty($data['mode'])) {
        $this->db->where('e.mode',$data['mode']);
      }

      # filter with status
      if(isset($data['status']) && strlen($data['status'])>0) {
        $this->db->where('e.status', $data['status']);
      }

      # filter with planned_at
      if(isset($data['planned_at']) && !empty($data['planned_at'])) {
        $this->db->where('e.planned_at',$data['planned_at']);
      }

      # filter with planned_from
      if(isset($data['planned_from']) && !empty($data['planned_from'])) {
        $this->db->where('e.planned_from >=',$data['planned_from']);
      }

      # filter with planned_to
      if(isset($data['planned_to']) && !empty($data['planned_to'])) {
        $this->db->where('e.planned_to >=',$data['planned_to']);
      }

      # filter with search
      if(isset($data['search']) && !empty($data['search'])) {
        $this->db->like('e.name',$data['search']);
      }

      # forced filters
      $this->db->group_by('em.event_uuid');
      $this->db->order_by('em.id', 'desc');

      # result query
      $events = $this->db->get()->result_array();

      # Build proper response
      $result = [];
      if(isset($data['expand']) && !empty($data['expand']) && \in_array("members", $data['expand'])) {
        foreach($events as $event) {
          # expand members
          $this->db->select('em.user_uuid as member_uuid,em.role as member_role,em.status as member_status,u.name as member_name,u.image as member_image');
          $this->db->from('event_members as em')->where('event_uuid', $event['uuid']);
          $this->db->join('users u','u.uuid = em.user_uuid','left');
          $event['_members'] = $this->db->get()->result_array();

          # push to an array
          $result[] = $event;
        }
      } else {
        # push to result array
        $result = $events;
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching group events in models!", [
        'params'  => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return [];
    }

    # return response
    return $result;
  }

/* ************************************************************************************************************** */
/* ************************************************************************************************************** */

  /**
   * fetchIndividualEvents
   * Params in an array supported are:
   * 1. by default type will be INDIVIDUAL
   * 2. uuid
   * 3. user_uuid
   * 4. mode
   * 5. status
   * 6. planned_at
   * 7. planned_from
   * 8. planned_to
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchIndividualEvents(array $data=[]): array
  {
    try {

      # get all events
      $this->db->select('id,uuid,user_uuid,name,type,mode,budget,status,add_to_personal,planned_at,closed_at');
      $this->db->from('events')->where('type',"INDIVIDUAL");

      # filter with uuid
      if(isset($data['uuid']) && !empty($data['uuid'])) {
        $this->db->where('uuid',$data['uuid']);
      }

      # filter with user_uuid
      if(isset($data['user_uuid']) && !empty($data['user_uuid'])) {
        $this->db->where('user_uuid',$data['user_uuid']);
      }

      # filter with mode
      if(isset($data['mode']) && !empty($data['mode'])) {
        $this->db->where('mode',$data['mode']);
      }

      # filter with status
      if(isset($data['status']) && strlen($data['status'])>0) {
        $this->db->where('status', $data['status']);
      }

      # filter with planned_at
      if(isset($data['planned_at']) && !empty($data['planned_at'])) {
        $this->db->where('planned_at',$data['planned_at']);
      }

      # filter with planned_from
      if(isset($data['planned_from']) && !empty($data['planned_from'])) {
        $this->db->where('planned_from >=',$data['planned_from']);
      }

      # filter with planned_to
      if(isset($data['planned_to']) && !empty($data['planned_to'])) {
        $this->db->where('planned_to >=',$data['planned_to']);
      }

      # filter with search
      if(isset($data['search']) && !empty($data['search'])) {
        $this->db->like('name',$data['search']);
      }

      # filter
      $this->db->order_by('id', 'desc');

      # result query
      $events = $this->db->get()->result_array();

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching individual events in models!", [
        'params'  => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return [];
    }

    # return response
    return $events;
  }

/* ************************************************************************************************************** */
/* ************************************************************************************************************** */

  /**
   * fetchMembers
   * Params in an array supported are:
   * 1. by default type will be EVENT
   * 2. event_uuid
   * 3. user_uuid
   * 4. role
   * 5. status
   * 6. expand = user
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchMembers(array $data=[]): array
  {
    try {

      # get all events
      $this->db->select('user_uuid,role,status,add_to_personal')->from('event_members');

      # filter with id
      if(isset($data['id']) && strlen($data['id']) > 0) {
        $this->db->where('id',$data['id']);
      }

      # filter with event_uuid
      if(isset($data['event_uuid']) && !empty($data['event_uuid'])) {
        $this->db->where('event_uuid',$data['event_uuid']);
      }

      # filter with user_uuid
      if(isset($data['user_uuid']) && !empty($data['user_uuid'])) {
        $this->db->where('user_uuid',$data['user_uuid']);
      }

      # filter with role
      if(isset($data['role']) && !empty($data['role'])) {
        $this->db->where('role',$data['role']);
      }

      # filter with status
      if(isset($data['status']) && strlen($data['status'])>0) {
        $this->db->where('status', $data['status']);
      }

      # filter
      $this->db->order_by('id', 'desc');

      # result query
      $items = $this->db->get()->result_array();

      # expand = members
      if(isset($data['expand']) && !empty($data['expand']) && \in_array("user", $data['expand'])) {
        foreach($items as $item) {
          $item['_user'] = $this->UserModel->fetchUsers(['fields'=>'name,image','uuid'=>$item['user_uuid']])[0];
          $members[] = $item;
        }

        # return response
        return $members;
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching group members in models!", [
        'params'  => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return [];
    }

    # return response
    return $items;
  }

/* ************************************************************************************************************** */
/* ************************************************************************************************************** */

  /**
   * fetchEvents
   * Params in an array supported are:
   * 1. id
   * 2. uuid
   * 3. user_uuid
   * 4. type
   * 5. mode
   * 6. status
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchEvents(array $data=[]): array
  {
    try {

      # get all events
      $this->db->select('*')->from('events');

      # filter with id
      if(isset($data['id']) && strlen($data['id']) > 0) {
        $this->db->where('id',$data['id']);
      }

      # filter with uuid
      if(isset($data['uuid']) && !empty($data['uuid'])) {
        $this->db->where('uuid',$data['uuid']);
      }

      # filter with user_uuid
      if(isset($data['user_uuid']) && !empty($data['user_uuid'])) {
        $this->db->where('user_uuid',$data['user_uuid']);
      }

      # filter with type
      if(isset($data['type']) && !empty($data['type'])) {
        $this->db->where('type',$data['type']);
      }

      # filter with mode
      if(isset($data['mode']) && strlen($data['mode'])>0) {
        $this->db->where('mode', $data['mode']);
      }

      # filter with status
      if(isset($data['status']) && strlen($data['status'])>0) {
        $this->db->where('status', $data['status']);
      }

      # filter
      $this->db->order_by('id', 'desc');

      # result query
      $data = $this->db->get()->result_array();

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching events in models!", [
        'params'  => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return [];
    }

    # return response
    return $data;
  }

/* ************************************************************************************************************** */
/* ************************************************************************************************************** */

  /**
   * fetchIndividualExpenses
   * Params in an array supported are:
   * 1. id
   * 2. event_uuid
   * 3. date
   * 4. date_from
   * 5. date_to
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchIndividualExpenses(array $data=[]): array
  {
    try {

      # Build fields
      $fields = "";

      # expand statistics
      if(isset($data['expand']) && !empty($data['expand']) && \in_array("statistics", $data['expand'])) {
        $fields = "sum(value) as total_expenses";
      }

      # expand data
      if(isset($data['expand']) && !empty($data['expand']) && \in_array("data", $data['expand'])) {
        $fields = "*";
      }

      # get all events
      $this->db->select($fields)->from('individual_event_expenses');

      # filter with id
      if(isset($data['id']) && strlen($data['id']) > 0) {
        $this->db->where('id',$data['id']);
      }

      # filter with event_uuid
      if(isset($data['event_uuid']) && !empty($data['event_uuid'])) {
        $this->db->where('event_uuid',$data['event_uuid']);
      }

      # filter with date
      if(isset($data['date']) && !empty($data['date'])) {
        $this->db->where('date',$data['date']);
      }

      # filter with date_from
      if(isset($data['date_from']) && !empty($data['date_from'])) {
        $this->db->where('date >=',$data['date_from']);
      }

      # filter with date_to
      if(isset($data['date_to']) && !empty($data['date_to'])) {
        $this->db->where('date >=',$data['date_to']);
      }

      # expand statistics
      if(isset($data['expand']) && !empty($data['expand']) && \in_array("statistics", $data['expand'])) {
        # filter & execute
        $data = $this->db->get()->row_array();
      }

      # expand data
      if(isset($data['expand']) && !empty($data['expand']) && \in_array("data", $data['expand']))
      {
        # filter with search
        if(isset($data['search']) && !empty($data['search'])) {
          $this->db->like('title', $data['search']);
        }

        # filter with order & name
        if(isset($data['name']) && !empty($data['name']) && isset($data['order']) && !empty($data['order'])) {
          $this->db->order_by($data['name'], $data['order']);
        }

        # filter with limit
        if(isset($data['limit']) && strlen((string)$data['limit'])>0 && isset($data['offset']) && strlen((string)$data['offset'])>0) {
          $this->db->limit($data['limit'], $data['offset']);
        }

        # execute
        $data = $this->db->get()->result_array();
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching individual event expenses in models!", [
        'params'  => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return [];
    }

    # return response
    return $data;
  }

/* ************************************************************************************************************** */
/* ************************************************************************************************************** */

  /**
   * fetchGroupExpenses
   * Params in an array supported are:
   * 1. event_uuid
   * 2. user_uuid
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchGroupExpenses(array $data=[]): array
  {
    try {

      # init var
      $expenses = [];

      # get all events
      if(!empty($data['user_uuid']??'')) {
        $getEventMembers = $this->fetchMembers(['event_uuid'=>$data['event_uuid'],'user_uuid'=>$data['user_uuid'],'expand'=>['user']]);
      } else {
        $getEventMembers = $this->fetchMembers(['event_uuid'=>$data['event_uuid'],'expand'=>['user']]);
      }

      # get expenses for each user
      foreach($getEventMembers as $member) {
        # get expenses
        $this->db->select('id,title,value,split,date')->from('group_event_expenses');
        $this->db->where('event_uuid',$data['event_uuid']);
        $this->db->where('user_uuid',$member['user_uuid']);
        # filter with split
        if(isset($data['split']) && strlen($data['split'])>0) {
          $this->db->where('split',$data['split']);
        }
        $this->db->order_by('id', 'desc');
        $member['_expenses'] = $this->db->get()->result_array();
        $expenses[] = $member;
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching group event expenses in models!", [
        'params'  => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return [];
    }

    # return response
    return $expenses;
  }

/* ************************************************************************************************************** */
/* ************************************************************************************************************** */

  /**
   * fetchGroupPayments
   * Params in an array supported are:
   * 1. event_uuid
   * 2. user_uuid
   *
   * NOTE: response is different when user uuid is sent & when not sent
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchGroupPayments(array $data=[]): array
  {
    try {

      # Initialize variables
      $payments = [];
      $payments['paid_amounts'] = [];
      $payments['received_amounts'] = [];

      # if user given
      if(!empty($data['user_uuid'])) {
        # get paid & received amount
        $getPaidAmounts = $this->db->select('*')->from('group_event_payments')->where('event_uuid',$data['event_uuid'])->where('user_uuid',$data['user_uuid'])->get()->result_array();
        $getReceivedAmounts = $this->db->select('*')->from('group_event_payments')->where('event_uuid',$data['event_uuid'])->where('friend_uuid',$data['user_uuid'])->get()->result_array();

        # get user whom i paid
        foreach($getPaidAmounts as $getPaidAmount) {
          $getPaidAmount['_user'] = $this->UserModel->fetchUsers(['fields'=>'name,image','uuid'=>$getPaidAmount['friend_uuid']])[0];
          $payments['paid_amounts'][] = $getPaidAmount;
        }

        # get user who paid to me
        foreach($getReceivedAmounts as $getReceivedAmount) {
          $getReceivedAmount['_user'] = $this->UserModel->fetchUsers(['fields'=>'name,image','uuid'=>$getReceivedAmount['user_uuid']])[0];
          $payments['received_amounts'][] = $getReceivedAmount;
        }
      } else {
        # Get event members
        $getEventMembers = $this->fetchMembers(['event_uuid'=>$data['event_uuid']]);
        foreach($getEventMembers as $member) {
          # get paid & received amount
          $getPaidAmount = $this->db->select('SUM(amount) as total_amount')->from('group_event_payments')->where('event_uuid',$data['event_uuid'])->where('user_uuid',$member['user_uuid'])->get()->row_array();
          $getReceivedAmount = $this->db->select('SUM(amount) as total_amount')->from('group_event_payments')->where('event_uuid',$data['event_uuid'])->where('friend_uuid',$member['user_uuid'])->get()->row_array();

          # Build body
          $body = [];
          $body['paid_amount'] = $getPaidAmount['total_amount'] ?? 0;
          $body['received_amount'] = $getReceivedAmount['total_amount'] ?? 0;

          # push to an array
          $payments[$member['user_uuid']] = $body;
        }
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching group payments in models!", [
        'params'  => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return [];
    }

    # return response
    return $payments;
  }

/* ************************************************************************************************************** */
/* ************************************************************************************************************** */

  /**
   * fetchGroupSplitShareData
   * Params in an array supported are:
   * 1. event_uuid
   * 2. user_uuid
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchGroupSplitShareData(array $data=[]): array
  {
    try {

      # init var
      $result   = [];

      # get event members count
      $eventMembersCount = $this->db->select('count(id) as count')->from('event_members')->where('event_uuid', $data['event_uuid'])->get()->row_array()['count'] ?? 1;

      # get event members
      if(isset($data['user_uuid']) && !empty($data['user_uuid'])) {
        $getEventMembers = $this->db->select('user_uuid, role, status, add_to_personal')->from('event_members')->where('event_uuid', $data['event_uuid'])->where('user_uuid', $data['user_uuid'])->get()->result_array();
      } else {
        $getEventMembers = $this->db->select('user_uuid, role, status, add_to_personal')->from('event_members')->where('event_uuid', $data['event_uuid'])->get()->result_array();
      }

      # total group expenses
      $totalGroupExpenses = $this->db->select('sum(value) as total_group_expenses')->from('group_event_expenses')->where('split', 1)->where('event_uuid', $data['event_uuid'])->get()->row_array()['total_group_expenses'] ?? 0;

      # each member share amount
      $share = $totalGroupExpenses/$eventMembersCount;

      # assume we have members
      foreach($getEventMembers as $member) {

        # get user personal expenses
        $member['personal_expenses'] = $this->db->select('sum(value) as personal_expenses')->from('group_event_expenses')->where('split', 0)->where('event_uuid', $data['event_uuid'])->where('user_uuid', $member['user_uuid'])->get()->row_array()['personal_expenses'] ?? 0;

        # get user name & append
        $member['user_name'] = $this->db->select('name')->from('users')->where('uuid', $member['user_uuid'])->get()->row_array()['name'] ?? "";

        # get group expenses for each user
        $member['group_expenses'] = $this->db->select('sum(value) as group_expenses')->from('group_event_expenses')->where('split', 1)->where('event_uuid', $data['event_uuid'])->where('user_uuid', $member['user_uuid'])->get()->row_array()['group_expenses'] ?? 0;

        # get paid amounts
        $member['paid_amounts'] = [];
        $member['total_paid_amount'] = 0;
        $getPaidAmounts = $this->db->select('user_uuid, friend_uuid, sum(amount) as amount')->from('group_event_payments')->where('event_uuid',$data['event_uuid'])->where('user_uuid',$member['user_uuid'])->group_by(['user_uuid','friend_uuid'])->get()->result_array();
        if(!empty($getPaidAmounts)) {
          foreach($getPaidAmounts as $paidItem) {
            # get user & friend name
            $paidItem['user_name'] = $this->db->select('name')->from('users')->where('uuid', $paidItem['user_uuid'])->get()->row_array()['name'] ?? "";
            $paidItem['friend_name'] = $this->db->select('name')->from('users')->where('uuid', $paidItem['friend_uuid'])->get()->row_array()['name'] ?? "";
            # push to an array
            $member['paid_amounts'][] = $paidItem;
            # calculate total paid amount
            $member['total_paid_amount'] = $member['total_paid_amount'] + $paidItem['amount'];
          }
        }

        # get received amounts
        $member['received_amounts'] = [];
        $member['total_received_amount'] = 0;
        $getReceivedAmounts = $this->db->select('user_uuid, friend_uuid, sum(amount) as amount')->from('group_event_payments')->where('event_uuid',$data['event_uuid'])->where('friend_uuid',$member['user_uuid'])->group_by(['user_uuid','friend_uuid'])->get()->result_array();
        if(!empty($getReceivedAmounts)) {
          foreach($getReceivedAmounts as $receivedItem) {
            # get user & friend name
            $receivedItem['user_name'] = $this->db->select('name')->from('users')->where('uuid', $receivedItem['user_uuid'])->get()->row_array()['name'] ?? "";
            $receivedItem['friend_name'] = $this->db->select('name')->from('users')->where('uuid', $receivedItem['friend_uuid'])->get()->row_array()['name'] ?? "";
            # push to an array
            $member['received_amounts'][] = $receivedItem;
            # calculate total received amount
            $member['total_received_amount'] = $member['total_received_amount'] + $receivedItem['amount'];
          }
        }

        # add total group expenses inside each-member
        $member['total_group_expenses'] = $totalGroupExpenses;

        # each member share
        $member['share'] = $share;

        # calculate the balance amount
        $member['balance'] = (int) ( $member['share'] - ($member['group_expenses'] + $member['total_paid_amount'] - $member['total_received_amount']) );

        # get color & arrow
        $member['color'] = "app_bcolor_green";
        $member['arrow'] = "fa fa-arrow-up";
        $member['sign']  = " + ";
        $member['bal_msg'] = "Balance (Amount To Receive)";
        if($member['balance'] > 0) {
          $member['color'] = "app_bcolor_red";
          $member['arrow'] = "fa fa-arrow-down";
          $member['sign']  = " - ";
          $member['bal_msg'] = "Balance (Amount To Pay)";
        } else if($member['balance'] == 0) {
          $member['bal_msg'] = "Balance (Account Cleared)";
        }

        # calculate rowspan
        $member['rowspan'] = 2;
        if($member['total_paid_amount'] > 0) $member['rowspan'] = $member['rowspan'] + 1;
        if($member['total_received_amount'] > 0) $member['rowspan'] = $member['rowspan'] + 1;

        # calculate total user expenses, he spent for event
        $member['total_user_expenses'] = $member['personal_expenses'] + $member['group_expenses'] + $member['total_paid_amount'] - $member['total_received_amount'];

        # calculate total user expenses, he spent for event
        $member['total_user_group_expenses'] = $member['group_expenses'] + $member['total_paid_amount'] - $member['total_received_amount'];

        # push to an array
        $result[$member['user_uuid']] = $member;
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching group split share data!", [
        'params'  => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return $result;
    }

    # return
    return $result;
  }

}?>