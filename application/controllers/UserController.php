<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User Controller
 */
class UserController extends CI_Controller
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
    $this->load->model('LibraryModel');

    # Helpers
    $this->load->helper('url');
    $this->load->helper('mylibrary');

    # redirect to login page if session does not exist
    if(empty($this->session->userdata('mobile'))) {
      # return
      \redirect('auth/login', 'refresh');
    }
  }

#################################################################################################################################
#################################################################################################################################
###################################################   USER PROFILE   ############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: profile
   * fetch user data
   *
   * @return  view      user profile view
   * @throws  Exception Log error
   */
  public function profile()
  {
    try {

      # move to two factor authentication page if security_profile auth is enabled
      if($this->session->userdata('security_profile') == 1) {
        # redirect to two factor authentication page
        \redirect(base_url().'feature/authentication?name=Account Profile&url=profile&code=security_profile');exit;
      }

      # fetch user & countries data
      $data['user'] = $this->UserModel->fetchUsers(['uuid'=>$this->session->userdata('uuid')])[0];
      $data['countries'] = $this->LibraryModel->fetchCountries();

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching user profile view!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load user profile page
    $this->load->view('profile', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: uploadImage
   * Upload user image
   *
   * Expected form data
   * 1. image (file upload)
   *
   * @return  string    success msg
   * @throws  Exception error msg
   */
  public function uploadImage()
  {
    try {

      # begin transaction
      $this->db->trans_begin();

        # validate image exist
        if(empty($_FILES["image"]["name"])) {
          throw new \Exception("No image to upload!", 400);
        }

        # get the user
        $user = $this->UserModel->fetchUsers(['uuid'=>$this->session->userdata('uuid')])[0];

        # delete existing image if exist
        if(!empty($user['image']) && $user['image'] != "default.jpg") {
          if(\file_exists($this->config->item('user_images').\basename($user['image']))) {
            unlink($this->config->item('user_images').basename($user['image']));
          }
        }

        # Upload image
        $uploadImage = $this->LibraryModel->uploadImage($this->config->item('user_images'), $this->session->userdata('uuid'));

        # update image in user data
        $this->db->where('id', $user['id'])->update('users', ['modified_dt'=>date('Y-m-d H:i:s'), 'image'=>$uploadImage['filename']]);

        # unset session data
        $this->session->unset_userdata('image');

        # set new data in session
        $this->session->set_userdata('image', $uploadImage['filename']);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception uploading user image!", [
        'data'    => $user ?? null,
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
   * Method: uploadUserDetails
   * Update user details
   *
   * Expected form data
   * 1. name
   * 2. country_id
   * 3. email
   * 4. mobile
   * 5. dob
   *
   * @return  string    success msg
   * @throws  Exception error msg
   */
  public function uploadUserDetails()
  {
    # Init var
    $body = [];

    try {

      # begin transaction
      $this->db->trans_begin();

        # get the user
        $user = $this->UserModel->fetchUsers(['uuid'=>$this->session->userdata('uuid')])[0];

        # Parameters
        $body['code']         = $user['code'];
        $body['country_id']   = $this->input->post('country_id') ?? $user['country_id'];
        $body['name']         = \trim($this->input->post('name')) ?? $user['name'];
        $body['email']        = \trim($this->input->post('email')) ?? $user['email'];
        $body['mobile']       = \trim($this->input->post('mobile')) ?? $user['mobile'];
        $body['dob']          = $this->input->post('dob') ?? $user['dob'];
        $body['modified_dt']  = \get_date_time();

        # update user code
        if($this->input->post('name') != $user['name']) {
          $body['code']       = \strtoupper(\str_replace(" ", "_", \trim($this->input->post('name'))))."_".\rand(1,999999);
        }

        # update user data
        $this->db->where('id', $user['id'])->update('users', $body);

        # get & set currency
        if($body['country_id'] != 0 && \strlen($body['country_id']) > 0) {
          $getCurrency = $this->db->select('*')->from('countries')->where('id', $body['country_id'])->get()->row_array();
          if(!empty($getCurrency)) {
            $this->session->set_userdata('currency', $getCurrency['currency']);
          }
        }

        # unset session data
        $this->session->unset_userdata('country_id');
        $this->session->unset_userdata('code');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('mobile');
        $this->session->unset_userdata('dob');

        # set new data in session
        $this->session->set_userdata('country_id', $body['country_id']);
        $this->session->set_userdata('code', $body['code']);
        $this->session->set_userdata('name', $body['name']);
        $this->session->set_userdata('email', $body['email']);
        $this->session->set_userdata('mobile', $body['mobile']);
        $this->session->set_userdata('dob', $body['dob']);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating user details!", [
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

#################################################################################################################################
#################################################################################################################################
###############################################   USER PERSONAL RESPONSIBILITIES   ##############################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: personalResponsibilities
   * Load personal responsibilities page
   *
   * @return  page  Responsibilities page
   */
  public function personalResponsibilities()
  {
    # load personal responsibilities page
    $this->load->view('responsibilities_personal', []);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: personalResponsibilitiesAdd
   * Load responsibilities page
   *
   * @return  page  Responsibilities page
   */
  public function personalResponsibilitiesAdd()
  {
    # load personal responsibilities add page
    $this->load->view('manage/responsibilities_add', []);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: personalResponsibilitiesList
   *
   * Expected get params
   * 1. search - search with date, title, value
   * 2. order  - apply asc or desc order
   * 3. name   - apply sorting on key field
   * 4. pageno - pagenohelps to find the start & limit
   * 5. limit  - number of records to return
   * 6. offset - start record from
   *
   * @return  view      personal responsible items
   * @throws  Exception Log error
   */
  public function personalResponsibilitiesList()
  {
    try {

      # move to two factor authentication page if Account Profile auth is enabled
      if($this->session->userdata('profile_auth') == 1) {
        # redirect to two factor authentication page
        \redirect(base_url().'feature/authentication?name=Account Profile&url=profile&code=profile_auth');exit;
      }

      # build data params
      $data = [];
      $data['search'] = !empty($this->input->get("search")) ? $this->input->get("search") : "";
      $data['order']  = !empty($this->input->get("order")) ? $this->input->get("order") : "desc";
      $data['name']   = !empty($this->input->get("name")) ? $this->input->get("name") : "value";
      $data['pageno'] = !empty($this->input->get("pageno")) ? (int)$this->input->get("pageno") : 1;
      $data['limit']  = !empty($this->input->get("limit")) ? (int)$this->input->get("limit") : 25;
      $data['offset'] = !empty($this->input->get("offset")) ? $this->input->get("offset") : 0;

      # calculate offset based on page no
      if($data['pageno'] != 1) {
        $data['offset'] = ($data['pageno']-1) * $data['limit'];
      }

      # get Personal params
      $getData = $this->UserModel->fetchPersonalResponsibilities($data);
      $data['items']         = $getData['items'];
      $data['total_records'] = $getData['total_records'];
      $data['total_pages']   = \ceil($data['total_records']/$data['limit']);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching persoanl responsibilities list!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load profile I & E Info page
    $this->load->view('manage/responsibilities_list', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: insertPersonalResponsibilities
   * Insert personal responsibilities
   *
   * Expected post body
   * 1. type
   * 2. title
   * 3. value
   *
   * @return  string    success msg
   * @throws  Exception error msg
   */
  public function insertPersonalResponsibilities()
  {
    try {

      # Build body
      $body = [];
      $body['uuid']         = $this->LibraryModel->UUID();
      $body['user_uuid']    = $this->session->userdata('uuid');
      $body['type']         = $this->input->post('type') ?? "";
      $body['title']        = $this->input->post('title') ?? "";
      $body['value']        = $this->input->post('value') ?? 0;
      $body['created_dt']   = \get_date_time();
      $body['modified_dt']  = \get_date_time();

      # begin transaction
      $this->db->trans_begin();

        # insert param
        $this->db->insert('responsibilities_personal', $body);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception inserting personal responsibilities!", [
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r("Failed to save, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: updatePersonalResponsibilities
   * Update personal responsibilities
   *
   * Expected post body
   * 1. type
   * 2. title
   * 3. value
   *
   * @return  string    success msg
   * @throws  Exception error msg
   */
  public function updatePersonalResponsibilities()
  {
    try {

      # begin transaction
      $this->db->trans_begin();

        # get param
        $param = $this->UserModel->fetchPersonalResponsibilities(['uuid'=>$this->input->post('uuid')])[0];

        # Build body
        $body = [];
        $body['type']         = $this->input->post('type')  ?? $param['type'];
        $body['title']        = $this->input->post('title') ?? $param['title'];
        $body['value']        = $this->input->post('value') ?? $param['value'];
        $body['modified_dt']  = \get_date_time();

        # udate param
        $this->db->where('id', $param['id'])->update('responsibilities_personal', $body);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating personal responsibilities!", [
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r("Failed to save, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: deletePersonalResponsibilities
   * Delete personal responsibilities
   *
   * Expected post body
   * 1. uuid
   *
   * @return  string    success msg
   * @throws  Exception error msg
   */
  public function deletePersonalResponsibilities()
  {
    try {

      # begin transaction
      $this->db->trans_begin();

        # delete param
        $this->db->where('uuid', $this->input->post('uuid'))->delete('responsibilities_personal');

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception deleting personal responsibilities!", [
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r("Failed to delete, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#################################################################################################################################
#################################################################################################################################
###################################################   USER SETTINGS   ###########################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: settings
   * Load seetings page
   *
   * @return  page      profile settings
   * @throws  Exception log error
   */
  public function settings()
  {
    try {

      # get the user
      $data['user'] = $this->UserModel->fetchUsers(['uuid'=>$this->session->userdata('uuid')])[0];

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception loading user settings page!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load settings page
    $this->load->view('settings', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: updateFeatureSettings
   * Update feature settings
   *
   * Expected post body
   * 1. feature_personal
   * 2. feature_events
   * 3. feature_accounts
   *
   * @return  string    success msg
   * @throws  Exception error msg
   */
  public function updateFeatureSettings()
  {
    try {

      # Init var
      $body = [];
      $body['feature_personal'] = 0;
      $body['feature_events']   = 0;
      $body['feature_accounts'] = 0;
      $body['modified_dt']      = \get_date_time();

      # Build body
      if($this->input->post('feature_personal')??"" == "on")  $body['feature_personal'] = 1;
      if($this->input->post('feature_events')??"" == "on")    $body['feature_events'] = 1;
      if($this->input->post('feature_accounts')??"" == "on")  $body['feature_accounts'] = 1;

      # begin transaction
      $this->db->trans_begin();

        # udate feature settings
        $this->db->where('uuid', $this->session->userdata('uuid'))->update('users', $body);

        # update session values
        $this->session->set_userdata('feature_personal', $body['feature_personal']);
        $this->session->set_userdata('feature_events', $body['feature_events']);
        $this->session->set_userdata('feature_accounts', $body['feature_accounts']);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating feature settings!", [
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r("Failed to update your settings, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: updatePin
   * Update two factor authentication pin
   *
   * Expected post body
   * 1. password
   * 2. pin
   *
   * @return  string    success msg
   * @throws  Exception error msg
   */
  public function updatePin()
  {
    # parameters
    $password = \trim((string)$this->input->post('password') ?? "");
    $pin = \trim((string)$this->input->post('pin') ?? "");

    try {

      # begin transaction
      $this->db->trans_begin();

        # pin must be only 4 characters
        if(strlen($pin) != 4) throw new \Exception("Pin must be only 4 characters!", 400);

        # get the user
        $user = $this->UserModel->fetchUsers(['uuid'=>$this->session->userdata('uuid')])[0];

        # verify password
        if(!$this->LibraryModel->passwordVerify($user['pw_algo'], $user['pw_hash'], $password)) {
          # throw new exception
          throw new \Exception("Password is in-correct!", 401);
        }

        # generate pin
        $encryptedPin = $this->LibraryModel->passwordEncrypt($user['pw_algo'], $user['pw_seed'], $pin);

        # update password
        $this->db->where('uuid', $this->session->userdata('uuid'))->update('users', ['modified_dt'=>date('Y-m-d H:i:s'), 'pin'=>$encryptedPin]);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating two-factor authentication pin!", [
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r($e->getMessage() ?? "Failed to update pin, Please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: updateSecuritySettings
   * Update security settings
   *
   * Expected post body
   * 1. security_personal
   * 2. security_events
   * 3. security_accounts
   * 4. security_profile
   * 5. security_friends
   *
   * @return  string    success msg
   * @throws  Exception error msg
   */
  public function updateSecuritySettings()
  {
    try {

      # Init var
      $body = [];
      $body['security_personal']  = 0;
      $body['security_events']    = 0;
      $body['security_accounts']  = 0;
      $body['security_profile']  = 0;
      $body['security_friends']   = 0;
      $body['modified_dt']        = \get_date_time();

      # Build body
      if($this->input->post('security_personal')??"" == "on") $body['security_personal'] = 1;
      if($this->input->post('security_events')??"" == "on")   $body['security_events'] = 1;
      if($this->input->post('security_accounts')??"" == "on") $body['security_accounts'] = 1;
      if($this->input->post('security_profile')??"" == "on")  $body['security_profile'] = 1;
      if($this->input->post('security_friends')??"" == "on")  $body['security_friends'] = 1;

      # begin transaction
      $this->db->trans_begin();

        # udate Security Settings
        $this->db->where('uuid', $this->session->userdata('uuid'))->update('users', $body);

        # update session values
        $this->session->set_userdata('security_personal', $body['security_personal']);
        $this->session->set_userdata('security_events', $body['security_events']);
        $this->session->set_userdata('security_accounts', $body['security_accounts']);
        $this->session->set_userdata('security_profile', $body['security_profile']);
        $this->session->set_userdata('security_friends', $body['security_friends']);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating security settings!", [
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r("Failed to update your settings, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

}?>