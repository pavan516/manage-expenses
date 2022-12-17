<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Info Controller
 */
class InfoController extends CI_Controller
{
  # Constructor
  function __construct()
  {
    # Parent constructor
    parent:: __construct();

    # Helpers
    $this->load->helper('url');
    $this->load->helper('mylibrary');
  }

#################################################################################################################################
#################################################################################################################################
###############################################   APPLICATION RELATED INFO  #####################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: termsAndConditions
   */
  public function termsAndConditions()
  {
    # load register page
    $this->load->view('terms_and_conditions', []);
  }

  /**
   * Method: privacyPolicy
   */
  public function privacyPolicy()
  {
    # load register page
    $this->load->view('privacy_policy', []);
  }

  /**
   * Method: aboutUs
   */
  public function aboutUs()
  {
    # load register page
    $this->load->view('about_us', []);
  }

  /**
   * Method: contactUs
   */
  public function contactUs()
  {
    # load register page
    $this->load->view('contact_us', []);
  }

  /**
   * Method: insertContactForm
   *
   * Expected post body
   * 1. name
   * 2. email
   * 3. mobile
   * 4. description
   *
   * @return redirect   Contact-Us Page
   * @throws Exception  Contact-Us Page
   */
  public function insertContactForm()
  {
    # Build body
    $body = [];
    $body['name']         = $this->input->post('name') ?? "";
    $body['email']        = $this->input->post('email') ?? "";
    $body['mobile']       = $this->input->post('mobile') ?? "";
    $body['description']  = $this->input->post('description') ?? "";
    $body['status']       = 0;
    $body['created_dt']   = \get_date_time();

    try {

      # begin transaction
      $this->db->trans_begin();

        # insert
        $this->db->insert('contact_forms', $body);

      # commit transaction
      $this->db->trans_commit();

    } catch(\Exception $e) {
      # rollbak transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception inserting contact form!", [
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # set error
      $this->session->set_flashdata('error', $e->getMessage() ?? "something went wrong please try after sometime!");
      \redirect('contact-us', 'refresh');
    }

    # set success
    $this->session->set_flashdata('success', "Thanks ".$body['name']." for contacting us, we will get back to you soon!");
    \redirect('contact-us', 'refresh');
  }

}?>