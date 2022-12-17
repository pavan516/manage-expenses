<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Auth Controller
 */
class AuthController extends CI_Controller
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

  }

#################################################################################################################################
#########################################################   REGISTER   ##########################################################
#################################################################################################################################

  /**
   * Method: register
   * This method will show the user registration page
   *
   * @return page       Register page
   * @throws Exception  Log error
   */
  public function register()
  {
    try {

      # send countries
      $data['countries'] = $this->LibraryModel->fetchCountries();

    } catch (\Exception $e) {
      # log error
      \logger("error", "Failed to fetch countries list!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load register page
    $this->load->view('register', $data);
  }

#################################################################################################################################
#######################################################   INSERT USER   #########################################################
#################################################################################################################################

  /**
   * Method: insertUser
   * This method will create user
   *
   * Expected Form Data
   * 1. name
   * 2. email
   * 3. mobile
   * 4. password
   * 5. country_id
   *
   * @return page Login
   * @throws Exception
   */
  public function insertUser()
  {
    try {

      # seed | algo | hash
      $seed = "PASSWORD_BCRYPT";
      $algo = "password_hash,password_verify";
      $hash = $this->LibraryModel->passwordEncrypt($algo, $seed, \trim($this->input->post('password')));

    } catch (\Exception $e) {
      # log error
      \logger("error", "Failed to encrypt password!", [
        'seed'    => $seed ?? null,
        'algo'    => $algo ?? null,
        'pass'    => $this->input->post('password') ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # set flash error & redirect to registration page
      $this->session->set_flashdata('error', "something went wrong, please try after sometime!");
      \redirect('auth/register', 'refresh');
    }

    try {

      # Parameters
      $body = [];
      $body['uuid']               = $this->LibraryModel->UUID();
      $body['fcm_token']          = "";
      $body['country_id']         = $this->input->post('country_id') ?? 0;
      $body['code']               = \strtoupper(\str_replace(" ", "_", \trim($this->input->post('name'))))."_".\rand(1,999999);
      $body['name']               = \trim($this->input->post('name'));
      $body['email']              = \trim($this->input->post('email'));
      $body['mobile']             = \trim($this->input->post('mobile'));
      $body['pw_seed']            = $seed;
      $body['pw_hash']            = $hash;
      $body['pw_algo']            = $algo;
      $body['image']              = "default.jpg";
      $body['dob']                = null;
      $body['role']               = "USERS";
      $body['pin']                = "";
      $body['account_type']       = "BASIC";
      $body['feature_personal']   = 1;
      $body['feature_events']     = 1;
      $body['feature_accounts']   = 1;
      $body['security_personal']  = 0;
      $body['security_events']    = 0;
      $body['security_accounts']  = 0;
      $body['security_profile']   = 0;
      $body['security_friends']   = 0;
      $body['verified']           = 0; # 1 = verified | 0 = not-verified
      $body['otp']                = "";
      $body['jwt_token']          = \md5($this->LibraryModel->UUID());
      $body['status']             = 0; # 0 = offline | 1 = online
      $body['created_dt']         = \get_date_time();
      $body['modified_dt']        = \get_date_time();

      # begin transaction
      $this->db->trans_begin();

        # make sure email already exist
        $verifyEmail = $this->db->select('*')->from('users')->where('email', $body['email'])->get()->row_array();
        if(!empty($verifyEmail)) throw new \Exception("Email (".$body['email'].") already exist!", 400);

        # make sure mobile number already exist
        $verifyMobile = $this->db->select('*')->from('users')->where('mobile', $body['mobile'])->get()->row_array();
        if(!empty($verifyMobile)) throw new \Exception("Mobile number (".$body['mobile'].") already exist!", 400);

        # insert user
        $insert = $this->db->insert('users', $body);
        if(!$insert) throw new \Exception("Failed to create an account! Please contact our support team @ 8520872771", 500);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception creating user!", [
        'body'    => $body ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # set flash error & redirect to registration page
      $this->session->set_flashdata('error', $e->getMessage() ?? "something went wrong, please try after sometime!");
      \redirect('auth/register', 'refresh');
    }

    # return to login page
    $this->session->set_flashdata('success', "Successfully account created, please login here!");
    \redirect('auth/login', 'refresh');
  }

#################################################################################################################################
##################################################   USER LOGIN VIEW   ##########################################################
#################################################################################################################################

  /**
   * Method: login
   * Verify user had session
   * - if yes - redirect to home page
   * - if no - load login page
   * - set cache for 1 year = 31536000 seconds
   *
   * @return page login
   */
  public function login()
  {
    # if session exist - redirect to home page
    if(!empty($this->session->userdata('mobile'))) \redirect('', 'refresh');

    # set cache to 1 year
    // $this->output->cache(31536000);

    # load login page
    $this->load->view('login');
  }

#################################################################################################################################
#####################################################   USER LOGIN   ############################################################
#################################################################################################################################

  /**
   * Method: userLogin
   * Login with email or mobile
   * - save user data in session
   * - save security_* in session
   * - save currency in session
   *
   * Expected Form Data
   * 1. emailormobile
   * 2. password
   *
   * @return page       Home page
   * @throws Exception  Login page
   */
  public function userLogin()
  {
    # Parameters
    $emailormobile  = \trim($this->input->post('emailormobile'));
    $password       = \trim($this->input->post('password'));

    try {

      # begin transaction
      $this->db->trans_begin();

        # check with email
        $user = $this->db->select('*')->from('users')->where('email', $emailormobile)->get()->row_array();

        # check with mobile if user not found with email
        if(empty($user)) {
          $user = $this->db->select('*')->from('users')->where('mobile', $emailormobile)->get()->row_array();
        }

        # if user does not exist throw an error
        if(empty($user)) throw new \Exception("Account does not exist with this email/mobile (".$emailormobile.")", 404);

        # verify password
        $verify = $this->LibraryModel->passwordVerify($user['pw_algo'], $user['pw_hash'], $password);
        if(!$verify) {
          # throw new exception
          throw new \Exception("Invalid password!", 401);
        }

        # if all okay - store data in session
        $this->session->set_userdata($user);

        # get & set currency
        if($user['country_id'] != 0) {
          $getCurrency = $this->db->select('*')->from('countries')->where('id', $user['country_id'])->get()->row_array();
          if(!empty($getCurrency)) {
            $this->session->set_userdata('currency', $getCurrency['currency']);
          }
        } else {
          $this->session->set_userdata('currency', '₹');
        }

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception authenticating user!", [
        'user'    => $emailormobile ?? null,
        'pass'    => $password ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # set flash error & redirect to login page
      $this->session->set_flashdata('error', $e->getMessage() ?? "something went wrong, please try after sometime!");
      \redirect('auth/login', 'refresh');
    }

    # load fcm token update page
    $this->load->view('fcmtoken');
  }

#################################################################################################################################
###############################################   FORGOT PASSWORD VIEW  #########################################################
#################################################################################################################################

  /**
   * Method: forgotPassword
   *
   * @return page forgot password page
   */
  public function forgotPassword()
  {
    # load forgot password page
    $this->load->view('forgotpassword');
  }

#################################################################################################################################
#############################################   FORGOT PASSWORD SEND MAIL  ######################################################
#################################################################################################################################

  /**
   * Method: forgotPasswordSendMail
   * This method will send a reset-password link to user through mail
   *
   * Expected form data
   * 1. email
   *
   * @return page       Login page
   * @throws Exception  Forgot password page
   */
  public function forgotPasswordSendMail()
  {
    # Parameter
    $email = \trim($this->input->post('email'));

    try {
      # begin transaction
      $this->db->trans_begin();

        # get the user
        $user = $this->UserModel->fetchUsers(['email'=>$email]);
        if(empty($user)) throw new \Exception("This email (".$email.") not yet registered with MANAGE EXPENSES!", 404);

        # append user
        $user = $user[0];

        # generate random key as OTP
        $otp = \mt_rand(100000,999999);

        # Update user
        $this->db->where('id',$user['id'])->update('users',['otp'=>$otp,'modified_dt'=>\get_date_time()]);

        # build reset password link & send email
        $link = \base_url()."auth/resetpassword/view?email=".\base64_encode($email)."&otp=".\base64_encode($otp);
        $message = $this->load->view('forgot_password_email', ['name'=>$user['name'], 'link'=>$link], true);
        $this->LibraryModel->sendEmail($email, "Manage Expenses Reset Password", $message);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception sending reset password link to user!", [
        'email'   => $email,
        'user'    => $user ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # set flash error & redirect to forgot password page
      $this->session->set_flashdata('error', $e->getMessage() ?? "Failed to send reset-password link to your mail-id, please try after sometime!");
      \redirect('auth/forgotpassword', 'refresh');
    }

    # redirect to login page
    $this->session->set_flashdata('success', 'Reset password link sent to your mail address @ '.$email);
    \redirect('auth/login', 'refresh');
  }

#################################################################################################################################
################################################   RESET PASSWORD VIEW  #########################################################
#################################################################################################################################

  /**
   * Method: resetPasswordView
   * Verify email & otp
   *
   * Expected query params
   * 1. email
   * 2. otp
   *
   * @return page       Reset password page
   * @throws Exception  Reset password page
   */
  public function resetPasswordView()
  {
    # build parameters body
    $data = [];
    $data['email']  =  \base64_decode(\trim($this->input->get('email')));
    $data['otp']    =  \base64_decode(\trim($this->input->get('otp')));

    try {

      # get the user
      $user = $this->UserModel->fetchUsers(['email'=>$data['email']]);
      if(empty($user)) throw new \Exception("Invalid reset password link!", 401);

      # load reset password view page
      $this->load->view('reset_password', $data);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching user!", [
        'email'   => $data['email'] ?? null,
        'otp'     => $data['otp'] ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # set flash error & load to reset page
      $this->load->view('reset_password', ['error'=> $e->getMessage() ?? "Invalid reset password link!"]);
    }
  }

#################################################################################################################################
###################################################   RESET PASSWORD  ###########################################################
#################################################################################################################################

  /**
   * Method: resetPassword
   * Verify email, otp & password's match
   *
   * Expected post body
   * 1. email
   * 2. otp
   * 3. new_pass
   * 4. repeat_pass
   *
   * @return page       Login page
   * @throws Exception  Reset password page
   */
  public function resetPassword()
  {
    # parameters
    $email      = \trim($this->input->post('email') ?? "");
    $otp        = \trim($this->input->post('otp') ?? "");
    $newPass    = \trim($this->input->post('new_pass') ?? "");
    $repeatPass = \trim($this->input->post('repeat_pass') ?? "");

    try {
      # begin transaction
      $this->db->trans_begin();

        # get the user
        $user = $this->UserModel->fetchUsers(['email'=>$email]);
        if(empty($user)) throw new \Exception("Invalid reset password link!", 400);

        # append user
        $user = $user[0];

        # verify OTP
        if(\strcasecmp($user['otp'], $otp) != 0) throw new \Exception("Invalid reset password link!", 400);

        # make sure new password & repeat password are same
        if(\strcasecmp($newPass, $repeatPass) != 0) {
          throw new \Exception("Old password & new password does not match!", 400);
        }

        # generate new password
        $pw_hash = $this->LibraryModel->passwordEncrypt($user['pw_algo'], $user['pw_seed'], $newPass);

        # update password
        $update = $this->db->where('uuid', $user['uuid'])->update('users', ['modified_dt'=>\get_date_time(), 'pw_hash'=>$pw_hash]);
        if(!$update) throw new \Exception("Failed to change password, please try after sometime!", 500);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating user password on reset password link!", [
        'user'    => $user ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # set flash error & redirect to reset password page
      $this->session->set_flashdata('error', $e->getMessage() ?? "Failed to change password, Please try after sometime!");
      \redirect('auth/resetpassword/view?email='.base64_encode($email).'&otp='.base64_encode($otp), 'refresh');
    }

    # redirect to login page
    $this->session->set_flashdata('success', 'Password successfully updated!');
    \redirect('auth/login', 'refresh');
  }

#################################################################################################################################
#################################################   CHANGE PASSWORD   ###########################################################
#################################################################################################################################

  /**
   * Method: changePassword
   * This endpoint will update user login password
   *
   * Expected Form Data
   * 1. old_pass
   * 2. new_pass
   * 3. repeat_pass
   *
   * @return string     success
   * @throws Exception  error
   */
  public function changePassword()
  {
    # parameters
    $oldPass    = \trim($this->input->post('old_pass') ?? "");
    $newPass    = \trim($this->input->post('new_pass') ?? "");
    $repeatPass = \trim($this->input->post('repeat_pass') ?? "");

    try {

      # begin transaction
      $this->db->trans_begin();

        # get the user
        $user = $this->UserModel->fetchUsers(['uuid'=>$this->session->userdata('uuid')])[0];

        # verify old password
        if(!$this->LibraryModel->passwordVerify($user['pw_algo'], $user['pw_hash'], $oldPass)) {
          throw new \Exception("Your old password is incorrect!", 400);
        }

        # make sure new & repeat password are same
        if($newPass != $repeatPass) {
          throw new \Exception("Your password & new password does not match!", 400);
        }

        # generate new password
        $pw_hash = $this->LibraryModel->passwordEncrypt($user['pw_algo'], $user['pw_seed'], $newPass);

        # update password
        $update = $this->db->where('uuid', $this->session->userdata('uuid'))->update('users', ['modified_dt'=>\get_date_time(), 'pw_hash'=>$pw_hash]);
        if(!$update) throw new \Exception("Failed to change password, Please try after sometime!", 500);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating password on change password!", [
        'user'    => $user ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r($e->getMessage() ?? "Failed to change password, Please try after sometime!");exit;
    }

    # success
    print_r("success");exit;
  }

#################################################################################################################################
####################################################   DELETE USER   ############################################################
#################################################################################################################################

  /**
   * Method: userDelete
   *
   * Delete list of data
   * 1. delete accounts + account transactions related to user
   * 2. delete events related to user
   * 3. delete all friends or followers or requests
   * 4. delete notifications
   * 5. delete personal responsibilities
   * 6. delete personal
   * 7. delete user
   *
   * @return page       logout
   * @throws Exception  logout
   */
  public function userDelete()
  {
    try {

      # user uuid
      $userUuid = $this->session->userdata('uuid');

      # begin transaction
      $this->db->trans_begin();

        # delete accounts + account transactions related to user
        $this->db->where('user_uuid', $userUuid)->delete('accounts');

        # delete events related to user
        $this->db->where('user_uuid', $userUuid)->delete('events');

        # delete all friends or followers or requests
        $this->db->where('user_uuid', $userUuid)->delete('friends');
        $this->db->where('friend_uuid', $userUuid)->delete('friends');

        # delete notifications
        $this->db->where('sender_uuid', $userUuid)->delete('notifications');
        $this->db->where('user_uuid', $userUuid)->delete('notifications');

        # delete 	responsibilities_personal
        $this->db->where('user_uuid', $userUuid)->delete('	responsibilities_personal');

        # delete personal
        $this->db->where('user_uuid', $userUuid)->delete('personal');

        # delete user
        $this->db->where('uuid', $userUuid)->delete('users');

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception deleting user!", [
        'user'    => $userUuid ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # force redirect
      \redirect('auth/logout', 'refresh');
    }

    # return
    \redirect('auth/logout', 'refresh');
  }

#################################################################################################################################
#################################################   LOGOUT SESSION   ############################################################
#################################################################################################################################

  /**
   * Method: logout
   * on logout, delete all session related data
   *
   * @return page       home page
   * @throws Exception  home page
   */
  public function logout()
  {
    try {
      # get the user
      $user = $this->UserModel->fetchUsers(['uuid'=>$this->session->userdata('uuid')])[0];

      # make sure session exist
      if(!empty($this->session->userdata('mobile'))) {
        # unset session data
        foreach($user as $item) {
          $this->session->unset_userdata($item);
        }
        $this->session->unset_userdata('currency');
        $this->session->sess_destroy();
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception while logging out!", [
        'user'    => $user ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # force redirect
      \redirect('', 'refresh');
    }

    # return
    \redirect('', 'refresh');
  }

#################################################################################################################################
###########################################   TWO FACTOR AUTHENTICATION   #######################################################
#################################################################################################################################

  /**
   * Method: twoFactorAuthentication
   * Authenticate pin to access secured pages
   *
   * Expected Form Data
   * 1. url
   * 2. password
   * 3. code
   *
   * @return page       URL
   * @throws Exception  URL
   */
  public function twoFactorAuthentication()
  {
    # parameters
    $url  = \trim($this->input->post('url') ?? "");
    $pin  = \trim($this->input->post('password') ?? "");
    $code = \trim($this->input->post('code') ?? "");

    try {
      # get the user
      $user = $this->UserModel->fetchUsers(['uuid'=>$this->session->userdata('uuid')])[0];

      # verify pin
      if(!$this->LibraryModel->passwordVerify($user['pw_algo'], $user['pin'], $pin)) {
        # set error
        $this->session->set_flashdata('error', "Two factor authentication failed, invalid pin!");
        \redirect($url, 'refresh');
      }

      # set userdata for given code to 0
      if($code == "security_personal")  $this->session->set_userdata('security_personal', 0);
      if($code == "security_events")    $this->session->set_userdata('security_events', 0);
      if($code == "security_accounts")  $this->session->set_userdata('security_accounts', 0);
      if($code == "security_profile")   $this->session->set_userdata('security_profile', 0);
      if($code == "security_friends")   $this->session->set_userdata('security_friends', 0);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception on authenticating pin!", [
        'user'    => $user ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # force redirect
      \redirect($url, 'refresh');
    }

    # redirect to requested url
    \redirect($url, 'refresh');
  }

#################################################################################################################################
#################################################################################################################################
################################################   UPDATE FCM TOKEN   ###########################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: updateFcmToken
   * Update firebase token for user
   *
   * Expected query param
   * 1. fcm_tken
   *
   * @return bool
   * @throws Exception
   */
  public function updateFcmToken()
  {
    # param
    $fcmToken = $this->input->get('fcm_token') ?? $this->input->post('fcm_token') ?? "";

    try {

      # begin transaction
      $this->db->trans_begin();

        # update fcm token
        if(!empty($fcmToken)) {
          $this->db->where('uuid', $this->session->userdata('uuid'))->update('users', ['modified_dt'=>\get_date_time(), 'fcm_token'=>$fcmToken]);
        }

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception on updating FCM token!", [
        'token'   => $fcmToken ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # force return as true
      print_r(true);exit;
    }

    # just assume ok
    print_r(true);exit;
  }

#################################################################################################################################
##############################################   VERIFY ACCOUNT SEND MAIL  ######################################################
#################################################################################################################################

  /**
   * Method: verifyAccountSendMail
   * This method will send a verify account link to user through mail
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function verifyAccountSendMail()
  {
    # Parameter
    $email  = $this->session->userdata('email');
    $uuid   = $this->session->userdata('uuid');
    $name   = $this->session->userdata('name');

    try {

      # get the user
      $user = $this->UserModel->fetchUsers(['email'=>$email]);
      if(empty($user)) throw new \Exception("Invalid link!", 400);

      # append user
      $user = $user[0];

      # make sure account not yet verified already
      if($user['verified'] == 1) {
        # error
        print_r("Your account already verified, please logout & login to apply changes!");exit;
      }

      # begin transaction
      $this->db->trans_begin();

        # generate random key as OTP
        $otp = \mt_rand(100000,999999);

        # Update user
        $this->db->where('uuid',$uuid)->update('users',['otp'=>$otp,'modified_dt'=>\get_date_time()]);

        # build verify account link & send email
        $link = \base_url()."auth/account/verify?email=".\base64_encode($email)."&otp=".\base64_encode($otp);
        $message = $this->load->view('verify_account_email', ['name'=>$name, 'link'=>$link], true);
        $this->LibraryModel->sendEmail($email, "Manage Expenses Verify Account", $message);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception sending verify account link to user!", [
        'email'   => $email,
        'user'    => $this->session->userdata() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r("Failed to send reset-password link to your mail-id, please try after sometime!");exit;
    }

    # success msg
    print_r("verification link sent to your mail address ".$email);exit;
  }

#################################################################################################################################
##################################################   VERIFY ACCOUNT  ############################################################
#################################################################################################################################

  /**
   * Method: verifyAccount
   * Verify email & otp
   *
   * Expected query params
   * 1. email
   * 2. otp
   *
   * @return page       refresh page
   * @throws Exception  refresh page
   */
  public function verifyAccount()
  {
    # parameters
    $email  =  \base64_decode(\trim($this->input->get('email')));
    $otp    =  \base64_decode(\trim($this->input->get('otp')));

    try {

      # begin transaction
      $this->db->trans_begin();

        # get the user
        $user = $this->UserModel->fetchUsers(['email'=>$email]);
        if(empty($user)) throw new \Exception("Invalid link!", 400);

        # append user
        $user = $user[0];

        # verify OTP
        if(\strcasecmp($user['otp'], $otp) != 0) throw new \Exception("Invalid link!", 400);

        # update account
        $this->db->where('uuid', $user['uuid'])->update('users', ['modified_dt'=>\get_date_time(), 'verified'=>1]);

        # update session
        $this->session->unset_userdata('verified');
        $this->session->set_userdata('verified', 1);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating user verify account link!", [
        'user'    => $user ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # set flash error & redirect to reset password page
      $this->session->set_flashdata('error', $e->getMessage() ?? "Failed to verify account, Please try after sometime!");
      \redirect('', 'refresh');
    }

    # redirect to login page
    $this->session->set_flashdata('success', 'Account verified successfully!');
    \redirect('', 'refresh');
  }

}?>