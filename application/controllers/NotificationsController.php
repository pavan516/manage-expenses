<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Notifications Controller
 */
class NotificationsController extends CI_Controller
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
  }

#################################################################################################################################
#################################################################################################################################
#####################################################   PERSONAL   ##############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: index
   * Fetch list of notifications
   *
   * @return page       notifications page
   * @throws Exception  Log error
   */
  public function index()
  {
    try {

      # Build data
      $data = [];
      $data['data'] = $this->NotificationsModel->getNotifications(['user_uuid'=>$this->session->userdata('uuid'), 'expand'=>['sender']]);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception loading notifications page!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # navigate to notifications page
    $this->load->view('notifications', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: fetchUnReadNotificationsCount
   * Fetch the count of unred notifications
   *
   * @return int        count
   * @throws Exception  0 as default value
   */
  public function fetchUnReadNotificationsCount()
  {
    try {

      # get count
      $count = $this->NotificationsModel->getNotificationsCount(['user_uuid'=>$this->session->userdata('uuid'), 'status'=>0]);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching the count of unread notifications!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # assume 0
      print_r(0);exit;
    }

    # get notifications count
    print_r($count);exit;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: updateNotifications
   * This method is used to update the notification status
   *
   * Expected post body
   * 1. uuid (notification uuid)
   * 2. status
   *
   * @return string     sucess msg
   * @throws Exception  Log error
   */
  public function updateNotifications()
  {
    try {

      # Params
      $params = [];
      $params['user_uuid']  = $this->session->userdata('uuid') ?? "";
      $params['uuid']       = $this->input->post('uuid') ?? "";
      $params['status']     = $this->input->post('status') ?? 1;
      $params['type']       = $this->input->get('type') ?? "";

      # update notifications
      $this->LibraryModel->updateNotifications($params);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception updating notification status!", [
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # assume all good
    print_r("success");exit;
  }

}?>