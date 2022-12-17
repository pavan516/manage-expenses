<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * UserModel
 */
class UserModel extends CI_Model
{
  # Constructor
  public function __construct()
  {
    # Parent constructor
    parent::__construct();
  }

#################################################################################################################################
#################################################################################################################################
######################################################   USERS   ################################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * fetchUsers
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchUsers(array $data=[]): array
  {
    # init var
    $users = [];

    try {

      # build query
      if(isset($data['fields']) && !empty($data['fields'])) {
        $this->db->select($data['fields'])->from('users');
      } else {
        $this->db->select('*')->from('users');
      }

      # filter with id
      if(isset($data['id']) && !empty($data['id'])) {
        $this->db->where('id',$data['id']);
      }

      # filter with uuid
      if(isset($data['uuid']) && !empty($data['uuid'])) {
        $this->db->where('uuid',$data['uuid']);
      }

      # filter with country_id
      if(isset($data['country_id']) && !empty($data['country_id'])) {
        $this->db->where('country_id',$data['country_id']);
      }

      # filter with code
      if(isset($data['code']) && !empty($data['code'])) {
        $this->db->where('code',$data['code']);
      }

      # filter with email
      if(isset($data['email']) && !empty($data['email'])) {
        $this->db->where('email',$data['email']);
      }

      # filter with mobile
      if(isset($data['mobile']) && !empty($data['mobile'])) {
        $this->db->where('mobile',$data['mobile']);
      }

      # filter with role
      if(isset($data['role']) && !empty($data['role'])) {
        $this->db->where('role',$data['role']);
      }

      # filter search - mobile
      if(isset($data['search']) && !empty($data['search'])) {
        $this->db->where('name LIKE "%'.$data['search'].'%" OR email LIKE "%'.$data['search'].'%" OR code LIKE "%'.$data['search'].'%" OR mobile LIKE "%'.$data['search'].'%"');
      }

      # order by id desc
      $this->db->order_by("id", "desc");

      # result query
      $users = $this->db->get()->result_array();

      # expand branch & subject
      $result = [];
      foreach($users as $user) {
        # expand country
        if(!empty($user['country_id'])) {
          $user['_country'] = $this->db->select('*')->from('countries')->where('id', $user['country_id'])->get()->row_array();
        }
        # expand friend - with loggedin user
        if(isset($data['expand']) && !empty($data['expand']) && $data['expand'] == "FRIEND") {
          $getFriend = $this->db->select('*')->from('friends')->where('user_uuid',$this->session->userdata('uuid'))->where('friend_uuid', $user['uuid'])->get()->row_array();
          if(!empty($getFriend)) {
            $user['_friend'] = $getFriend['status'];
          } else {
            $user['_friend'] = "";
          }
        }
        $result[] = $user;
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching users in models!", [
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

#################################################################################################################################
#################################################################################################################################
######################################################   PARAMS   ###############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * fetchPersonalResponsibilities
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchPersonalResponsibilities(array $data=[]): array
  {
    # init var
    $result = [];

    try {

      ###
      ##  FETCH TOTAL COUNT
      ###

      # build query
      $this->db->select('count(id) as total_records')->from('responsibilities_personal')->where('user_uuid', $this->session->userdata('uuid'));

      # filter with id
      if(isset($data['id']) && !empty($data['id'])) {
        $this->db->where('id',$data['id']);
      }

      # filter with uuid
      if(isset($data['uuid']) && !empty($data['uuid'])) {
        $this->db->where('uuid',$data['uuid']);
      }

      # filter with search
      if(isset($data['search']) && !empty($data['search'])) {
        $this->db->like('title', $data['search']);
      }

      # get total records count
      $result['total_records'] = $this->db->get()->row_array()['total_records'] ?? 0;

      ###
      ##  FETCH LIST
      ###

      # build query
      $this->db->select('*')->from('responsibilities_personal')->where('user_uuid', $this->session->userdata('uuid'));

      # filter with id
      if(isset($data['id']) && !empty($data['id'])) {
        $this->db->where('id',$data['id']);
      }

      # filter with uuid
      if(isset($data['uuid']) && !empty($data['uuid'])) {
        $this->db->where('uuid',$data['uuid']);
      }

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

      # result query
      $result['items'] = $this->db->get()->result_array();

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching personal responsibilities in models!", [
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

}?>