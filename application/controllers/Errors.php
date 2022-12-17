<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Errors Controller
 */
class Errors extends CI_Controller
{
  # Constructor
  function __construct()
  {
    # Parent constructor
    parent:: __construct();

    # Helpers
    $this->load->helper('url');
    $this->load->helper('mylibrary');

    # Library
    $this->load->library('session');

    # default time zone
    date_default_timezone_set('Asia/Kolkata');

    # Configurations
    set_time_limit(0);

    # hide all error
    error_reporting(0);
    ini_set('display_errors', 0);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: index
   */
  public function index()
  {
    # load home page
    $this->load->view('error');
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

}?>