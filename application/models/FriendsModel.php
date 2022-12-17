<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * FriendsModel
 */
class FriendsModel extends CI_Model
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
######################################################   FRIENDS   ##############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * fetchFriends
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchFriends(array $data=[]): array
  {
    try {

      # build query
      $this->db->select('*')->from('friends');

      # filter with user_uuid
      if(isset($data['user_uuid']) && !empty($data['user_uuid'])) {
        $this->db->where('user_uuid',$data['user_uuid']);
      }

      # filter with friend_uuid
      if(isset($data['friend_uuid']) && !empty($data['friend_uuid'])) {
        $this->db->where('friend_uuid',$data['friend_uuid']);
      }

      # filter with status
      if(isset($data['status']) && !empty($data['status'])) {
        $this->db->where('status',$data['status']);
      }

      # result query
      $friends = $this->db->get()->result_array();

      # expand branch & subject
      $result = [];
      foreach($friends as $friend) {
        # expand user
        if(isset($data['user']) && $data['user'] == 1 && !empty($friend['user_uuid'])) {
          $friend['_user'] = $this->UserModel->fetchUsers(['uuid'=>$friend['user_uuid'],'fields'=>'id,uuid,name,mobile,image,code'])[0];
        }
        # expand friend
        if(isset($data['friend']) && $data['friend'] == 1 && !empty($friend['user_uuid'])) {
          $friend['_friend'] = $this->UserModel->fetchUsers(['uuid'=>$friend['friend_uuid'],'fields'=>'id,uuid,name,mobile,image,code'])[0];
        }
        $result[] = $friend;
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching friends in models!", [
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