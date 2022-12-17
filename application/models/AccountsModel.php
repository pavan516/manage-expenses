<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * AccountsModel
 */
class AccountsModel extends CI_Model
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
######################################################   ACCOUNTS   #############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * fetchAccounts
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchAccounts(array $data=[]): array
  {
    try {

      # build query
      $this->db->select('*')->from('accounts');

      # filter with uuid
      if(isset($data['uuid']) && !empty($data['uuid'])) {
        $this->db->where('uuid',$data['uuid']);
      }

      # filter with user_uuid
      if(isset($data['user_uuid']) && !empty($data['user_uuid'])) {
        $this->db->where('user_uuid', $data["user_uuid"]);
      }

      # filter with friend_uuid
      if(isset($data['friend_uuid']) && !empty($data['friend_uuid'])) {
        $this->db->where('friend_uuid',$data['friend_uuid']);
      }

      # filter with user_uuid as friend_uuid
      if(isset($data['user_uuid']) && !empty($data['user_uuid']) && $data['user_uuid'] != $this->session->userdata('uuid')) {
        $this->db->or_where('friend_uuid',$data['user_uuid']);
      }

      # filter with search
      if(isset($data['search']) && !empty($data['search'])) {
        $this->db->like('account_name',$data['search']);
      }

      # return in desc order
      $this->db->order_by('id', 'desc');

      # items
      $items = $this->db->get()->result_array();

      # expand = user & friend
      if(!empty($items) && isset($data['expand']) && !empty($data['expand'])) {
        foreach($items as $item) {
          # expand = user
          if(\in_array("user", $data['expand'])) {
            $item['_user'] = $this->UserModel->fetchUsers(['fields'=>'name,image','uuid'=>$item['user_uuid']])[0];
          }

          # expand = friend
          if(\in_array("friend", $data['expand'])) {
            $item['_friend'] = [];
            if(!empty($item['friend_uuid'])) $item['_friend'] = $this->UserModel->fetchUsers(['fields'=>'name,image','uuid'=>$item['friend_uuid']])[0];
          }

          # push to an array
          $result[] = $item;
        }

        # return response
        return $result;
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching accounts in models!", [
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

#################################################################################################################################
#################################################################################################################################
###################################################   TRANSACTIONS   ############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * fetchAccountTransactions
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchAccountTransactions(array $data=[]): array
  {
    try {

      # build query
      $this->db->select('*')->from('account_transactions');

      # filter with id
      if(isset($data['id']) && !empty($data['id'])) {
        $this->db->where('id', $data['id']);
      }

      # filter with account_uuid
      if(isset($data['account_uuid']) && !empty($data['account_uuid'])) {
        $this->db->where('account_uuid', $data['account_uuid']);
      }

      # filter with user_uuid
      if(isset($data['user_uuid']) && !empty($data['user_uuid'])) {
        $this->db->where('user_uuid', $data["user_uuid"]);
      }

      # filter with type
      if(isset($data['type']) && !empty($data['type'])) {
        $this->db->where('type', $data['type']);
      }

      # filter with from_date
      if(isset($data['from_date']) && !empty($data['from_date'])) {
        $this->db->like('date >=', $data['from_date']);
      }

      # filter with to_date
      if(isset($data['to_date']) && !empty($data['to_date'])) {
        $this->db->like('date <=', $data['to_date']);
      }

      # filter with date
      if(isset($data['date']) && !empty($data['date'])) {
        $this->db->like('date <=', $data['date']);
      }

      # filter by order_by & order_type
      $this->db->order_by($data['order_by'] ?? "id", $data['order_type'] ?? "desc");

      # result
      $result = $this->db->get()->result_array();

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching account transactions in models!", [
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
   * fetchAccountStats
   *
   * @param   array  $account
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchAccountStats(array $account): array
  {
    # Init var
    $userCreditSum    = 0;
    $userDebitSum     = 0;
    $friendCreditSum  = 0;
    $friendDebitSum   = 0;

    try {

      # get user credit amount
      $this->db->select('sum(amount) as credit')->from('account_transactions');
      $this->db->where('account_uuid', $account['uuid']);
      $this->db->where('user_uuid', $this->session->userdata('uuid'));
      $this->db->where('type', 'CREDIT');
      $userCreditSum = $this->db->get()->row_array()['credit'] ?? 0;

      # get user debit amount
      $this->db->select('sum(amount) as debit')->from('account_transactions');
      $this->db->where('account_uuid', $account['uuid']);
      $this->db->where('user_uuid', $this->session->userdata('uuid'));
      $this->db->where('type', 'DEBIT');
      $userDebitSum = $this->db->get()->row_array()['debit'] ?? 0;

      # get friend credit & debit amounts
      if(!empty($account['friend_uuid'])) {
        # get user credit amount
        $this->db->select('sum(amount) as credit')->from('account_transactions');
        $this->db->where('account_uuid', $account['uuid']);
        if($this->session->userdata('uuid') == $account['user_uuid']) {
          $this->db->where('user_uuid', $account['friend_uuid']);
        } else {
          $this->db->where('user_uuid', $account['user_uuid']);
        }
        $this->db->where('type', 'CREDIT');
        $friendCreditSum = $this->db->get()->row_array()['credit'] ?? 0;

        # get user debit amount
        $this->db->select('sum(amount) as debit')->from('account_transactions');
        $this->db->where('account_uuid', $account['uuid']);
        if($this->session->userdata('uuid') == $account['user_uuid']) {
          $this->db->where('user_uuid', $account['friend_uuid']);
        } else {
          $this->db->where('user_uuid', $account['user_uuid']);
        }
        $this->db->where('type', 'DEBIT');
        $friendDebitSum = $this->db->get()->row_array()['debit'] ?? 0;
      }

      # Build final result
      $result = [];
      $result['credit']  = $userCreditSum + $friendDebitSum;
      $result['debit']   = $userDebitSum + $friendCreditSum;
      $result['balance'] = $result['credit'] - $result['debit'];

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching account stats in models!", [
        'params'  => $account ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return [];
    }

    # return response
    return $result;
  }


}?>