<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Accounts Controller
 */
class AccountsController extends CI_Controller
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
    $this->load->model('AccountsModel');

    # Helpers
    $this->load->helper('url');
    $this->load->helper('mylibrary');

    # redirect to login page if session does not exist
    if(empty($this->session->userdata('mobile'))) {
      # return
      \redirect('auth/login', 'refresh');
    }

    # feature enable or disable check
    if($this->session->userdata('feature_accounts') == 0) {
      # redirect to load disabled page
      \redirect(\base_url().'feature/access?name=Accounts Management', 'refresh');
    }

    # move to two factor authentication page if security_accounts is enabled
    if($this->session->userdata('security_accounts') == 1) {
      # redirect to two factor authentication page
      \redirect(\base_url().'feature/authentication?name=Accounts Management&url=accounts&code=security_accounts');exit;
    }
  }

#################################################################################################################################
#################################################################################################################################
######################################################   ACCOUNTS   #############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: index
   * Accounts view
   *
   * @return page       accounts page
   * @throws Exception  log error
   */
  public function index()
  {
    try {
      # Build data
      $data['friends'] = $this->FriendsModel->fetchFriends(['user_uuid'=>$this->session->userdata('uuid'), 'status'=>'ACCEPTED', 'friend'=>1]);
    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching friends for accounts view!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load accounts page
    $this->load->view('accounts', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: insertAccount
   * Insert account
   *
   * Expected post body
   * 1. account_name
   * 2. friend_uuid
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function insertAccount()
  {
    try {
      # Init var
      $accountName  = $this->input->post('account_name') ?? "";

      # make sure account name is not empty
      if(empty($accountName)) throw new \Exception("Please fill account name!", 400);

      # Build body
      $body = [];
      $body['uuid']         = $this->LibraryModel->UUID();
      $body['account_name'] = $accountName;
      $body['user_uuid']    = $this->session->userdata('uuid') ?? "";
      $body['friend_uuid']  = $this->input->post('friend_uuid') ?? "";
      $body['created_dt']   = \get_date_time();
      $body['modified_dt']  = \get_date_time();

      # begin transaction
      $this->db->trans_begin();

        # insert account
        $this->db->insert('accounts', $body);

        # if friend_uuid is not empty - send notification
        if(!empty($body['friend_uuid'])) {
          # generate UUID
          $uuid = $this->LibraryModel->UUID() ?? "";

          # send notification to friend
          $this->NotificationsModel->sendNotification([
            'uuid'               => $uuid,
            'sender_uuid'        => $body['user_uuid'],
            'user_uuid'          => $body['friend_uuid'],
            'activity_type'      => "ACCOUNT_CREATED",
            'source_url'         => "account/view/".$body['uuid']."?search=&notification=".$uuid,
            'title'              => "New Account Created",
            'message'            => "<b>".$this->session->userdata('name')."</b> created a new account along with you!",
            'image_url'          => !empty($this->session->userdata('image')) ? $this->session->userdata('image') : "default.jpg"
          ]);
        }

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception creating an account!", [
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r($e->getMessage() ?? "failed to create an account, please try after sometime!");exit;
    }

    # assume all ok
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: searchAccounts
   * fetch all accounts & support search
   *
   * Expected query params
   * 1. search
   *
   * @return view       accounts list view
   * @throws Exception  Log error
   */
  public function searchAccounts()
  {
    # parameters
    $search = $this->input->get('search') ?? "";

    try {

      # Build data
      $data['search']   = $search;
      $data['friends']  = $this->FriendsModel->fetchFriends(['user_uuid'=>$this->session->userdata('uuid'), 'status'=>'ACCEPTED', 'friend'=>1]);
      $getAccounts      = $this->AccountsModel->fetchAccounts(['user_uuid'=>$this->session->userdata('uuid'), 'search'=>$search, 'expand'=>['user','friend']]);
      $data['accounts'] = [];

      # Add stats data to accounts
      if(!empty($getAccounts)) {
        foreach($getAccounts as $getAccount) {
          $getAccount['_stats'] = $this->AccountsModel->fetchAccountStats($getAccount);
          $data['accounts'][] = $getAccount;
        }
      }

    } catch(\Exception $e) {
      # log error
      \logger("error", "Exception fetching accounts!", [
        'search'  => $search ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load accounts page
    $this->load->view('accounts/accounts', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: updateAccount
   * Update account (name & change friend)
   *
   * Expected argument
   * 1. accountUuid
   *
   * Expected post body
   * 1. account_name
   * 2. friend_uuid
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function updateAccount($accountUuid)
  {
    try {

      # begin transaction
      $this->db->trans_begin();

        # get account
        $getAccount = $this->AccountsModel->fetchAccounts(['uuid'=>$accountUuid]);
        if(empty($getAccount)) throw new \Exception("Account not found!", 400);

        # get account
        $getAccount = $getAccount[0];

        # delete old friend expenses list
        if(\strcasecmp($getAccount['friend_uuid'], $this->input->post('friend_uuid')) != 0) {
          $this->db->where('account_uuid', $getAccount['uuid'])->where('user_uuid', $this->input->post('friend_uuid'))->delete('account_transactions');
        }

        # build body to update
        $body = [];
        $body['account_name'] = $this->input->post('account_name') ?? $getAccount['account_name'];
        $body['friend_uuid']  = $this->input->post('friend_uuid') ?? $getAccount['friend_uuid'];
        $body['modified_dt']  = \get_date_time();

        # update
        $this->db->where('uuid', $accountUuid)->update('accounts', $body);

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollbak transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating account!", [
        'account' => $accountUuid ?? null,
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r("failed to update, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: deleteAccount
   * Delete account
   *
   * Expected argument
   * 1. accountUuid
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function deleteAccount($accountUuid)
  {
    try {

      # begin transaction
      $this->db->trans_begin();

        # Delete all records from accounts
        $this->db->query("DELETE FROM `accounts` where uuid='".$accountUuid."'");

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollbak transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating account!", [
        'account' => $accountUuid ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r("failed to delete, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: viewAccount
   * View account page
   *
   * Expected argument
   * 1. accountUuid
   *
   * @return page       view account page
   * @throws Exception  log error
   */
  public function viewAccount($accountUuid)
  {
    try {

      # update notification if notification.uuid exist
      if(!empty($this->input->get('notification'))) {
        # update notification status to 1
        $this->LibraryModel->updateNotifications(['uuid'=>$this->input->get('notification'), 'status'=>1]);
      }

      # Build data
      $getAccount = $this->AccountsModel->fetchAccounts(['uuid'=>$accountUuid]);

      # make sure account exist
      if(empty($getAccount)) {
        # load account page
        $this->load->view('account_view', ['error'=>'Requested account does not exist!']);
      } else {
        # build required data
        $data['account'] = $getAccount[0];

        # load account page
        $this->load->view('account_view', $data);
      }

    } catch(\Exception $e) {
      # log error
      \logger("error", "Exception fetching account view page!", [
        'account' => $accountUuid ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }
  }

#################################################################################################################################
#################################################################################################################################
###################################################   TRANSACTIONS   ############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: accountTransactions
   * fetch the transactions list
   *
   * Expected argument
   * 1. accountUuid
   *
   * @return page       transactions list
   * @throws Exception  log error
   */
  public function accountTransactions($accountUuid)
  {
    try {

      # Build data
      $data = [];
      $data['account'] = $this->AccountsModel->fetchAccounts(['uuid'=>$accountUuid])[0];
      $data['transactions'] = $this->AccountsModel->fetchAccountTransactions(['account_uuid'=>$accountUuid]);

    } catch(\Exception $e) {
      # log error
      \logger("error", "Exception fetching account transactions list!", [
        'account' => $accountUuid ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load account page
    $this->load->view('accounts/account/transactions_list', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: accountTransactionsOverview
   * Calculate the transactions overview
   *
   * Expected argument
   * 1. accountUuid
   *
   * @return page       transactions overview
   * @throws Exception  log error
   */
  public function accountTransactionsOverview($accountUuid)
  {
    try {

      # Build data
      $data = [];
      $data['account'] = $this->AccountsModel->fetchAccounts(['uuid'=>$accountUuid])[0];
      $data['stats'] = $this->AccountsModel->fetchAccountStats($data['account']);

    } catch(\Exception $e) {
      # log error
      \logger("error", "Exception fetching account transactions list!", [
        'account' => $accountUuid ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load account page
    $this->load->view('accounts/account/transactions_overview', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: addAccountTransaction
   * Add account transactions
   *
   * Expected argument
   * 1. accountUuid
   *
   * Expected post body
   * 1. type
   * 2. title
   * 3. amount
   * 4. date
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function addAccountTransaction($accountUuid)
  {
    try {

      # validate title
      if(empty($this->input->post('title') ?? "")) throw new \Exception("Please enter the title of the transaction!", 400);

      # begin transaction
      $this->db->trans_begin();

        # get account
        $getAccount = $this->AccountsModel->fetchAccounts(['uuid'=>$accountUuid]);
        if(empty($getAccount)) throw new \Exception("Account not found!", 404);

        # get account
        $getAccount = $getAccount[0];

        # Build body
        $body = [];
        $body['account_id']   = $getAccount['id'];
        $body['account_uuid'] = $accountUuid;
        $body['user_uuid']    = $this->session->userdata('uuid') ?? "";
        $body['type']         = $this->input->post('type') ?? "";
        $body['title']        = $this->input->post('title') ?? "";
        $body['amount']       = $this->input->post('amount') ?? 0;
        $body['date']         = \get_date($this->input->post('date') ?? "");
        $body['created_dt']   = \get_date_time();
        $body['modified_dt']  = \get_date_time();

        # insert account
        $this->db->insert('account_transactions', $body);

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollbak transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception inserting account transactions!", [
        'account' => $accountUuid ?? null,
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r($e->getMessage() ?? "failed to save, please try after sometime!");exit;
    }

    # assume all ok
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: updateAccountTransaction
   * Update account transactions
   *
   * Expected argument
   * 1. accountUuid
   * 2. transactionId
   *
   * Expected post body
   * 1. type
   * 2. title
   * 3. amount
   * 4. date
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function updateAccountTransaction($accountUuid, $transactionId)
  {
    try {

      # begin transaction
      $this->db->trans_begin();

        # get account
        $getAccount = $this->AccountsModel->fetchAccounts(['uuid'=>$accountUuid]);
        if(empty($getAccount)) throw new \Exception("Account not found!", 404);

        # get account
        $getAccount = $getAccount[0];

        # get transaction
        $transaction = $this->AccountsModel->fetchAccountTransactions(['id'=>$transactionId])[0];
        if(empty($transaction)) throw new \Exception("Account transaction not found!", 404);

        # build body to update
        $body = [];
        $body['type']         = $this->input->post('type') ?? $transaction['type'];
        $body['title']        = $this->input->post('title') ?? $transaction['title'];
        $body['amount']       = $this->input->post('amount') ?? $transaction['amount'];
        $body['date']         = \get_date($this->input->post('date') ?? $transaction['date']);
        $body['modified_dt']  = \get_date_time();

        # update
        $this->db->where('id', $transactionId)->update('account_transactions', $body);

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollbak transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating account transaction!", [
        'account'     => $accountUuid ?? null,
        'transaction' => $transactionId ?? null,
        'data'        => $this->input->post() ?? null,
        'error'       => $e->getMessage(),
        'trace'       => $e->getTraceAsString()
      ]);

      # error
      print_r("failed to update, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: deleteAccountTransaction
   * Delete account transactions
   *
   * Expected argument
   * 1. accountUuid
   * 2. transactionId
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function deleteAccountTransaction($accountUuid, $transactionId)
  {
    try {

      # begin transaction
      $this->db->trans_begin();

        # Delete all records from accounts
        $this->db->query("DELETE FROM `account_transactions` where id='".$transactionId."'");

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollbak transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating account transaction!", [
        'account'     => $accountUuid ?? null,
        'transaction' => $transactionId ?? null,
        'data'        => $this->input->post() ?? null,
        'error'       => $e->getMessage(),
        'trace'       => $e->getTraceAsString()
      ]);

      # error
      print_r("failed to delete, please try after sometime!");exit;
    }

    # return response
    print_r("success");exit;
  }

}?>