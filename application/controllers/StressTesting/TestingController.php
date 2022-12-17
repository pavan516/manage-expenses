<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Testing Controller
 */
class TestingController extends CI_Controller
{
  # Constructor
  function __construct()
  {
    # Parent constructor
    parent:: __construct();

    # Models
    $this->load->model('LibraryModel');
  }

#################################################################################################################################
#################################################################################################################################
#####################################################   PERSONAL   ##############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: addPersonalExpenses
   *
   * @return  array
   */
  public function addPersonalExpenses()
  {
    try {

      # types
      $types = ["INCOME", "INVESTMENT", "EXPENSES"];

      for($i=1; $i<=50000; $i++)
      {
        # Build body
        $body = [];
        $body['uuid']       = $this->LibraryModel->UUID();
        $body['user_uuid']  = "18a42084-21de-4692-bdad-66ebf62d4579";
        $body['param_uuid'] = "";
        $body['type']       = $types[array_rand($types,1)];
        $body['title']      = "Testing-".$i;
        $body['value']      = $i;
        $body['date']       = \get_date();
        $body['created_dt'] = \get_date_time();
        $body['modified_dt']= \get_date_time();

        # insert Personalft
        $this->db->insert('personal', $body);
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception inserting personal expenses!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # error
      print_r($e->getMessage());exit;
    }

    # return response
    print_r("success");exit;
  }

}?>