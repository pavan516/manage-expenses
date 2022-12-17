<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Home Controller
 */
class HomeController extends CI_Controller
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

    # Helpers
    $this->load->helper('url');
    $this->load->helper('mylibrary');

    # redirect to login page if session does not exist
    if(empty($this->session->userdata('mobile'))) {
      # return
      \redirect('auth/login', 'refresh');
    }
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: index
   */
  public function index()
  {
    # load fcm token update page
    $this->load->view('fcmtoken');
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: twoFactorAuthentication
   *
   * @return page Two actor authentication
   */
  public function twoFactorAuthentication()
  {
    # Parameters
    $name = $this->input->get('name') ?? "";
    $url = $this->input->get('url') ?? "";
    $code = $this->input->get('code') ?? "";

    # load two factor authentication page
    $this->load->view('twofactorauth', ['name'=>$name, 'url'=>base_url().$url, "code"=>$code]);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: featureAccessCheck
   *
   * @return page Feature access check
   */
  public function featureAccessCheck()
  {
    # Parameters
    $name = $this->input->get('name') ?? "";

    # redirect load disabled page
    $this->load->view('feature_disabled', ['name'=>$name]);
  }

}?>