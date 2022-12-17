<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Personal Controller
 */
class PersonalController extends CI_Controller
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
    $this->load->model('PersonalModel');
    $this->load->model('LibraryModel');

    # Helpers
    $this->load->helper('url');
    $this->load->helper('mylibrary');

    # redirect to login page if session does not exist
    if(empty($this->session->userdata('mobile'))) {
      # redirect to login page
      \redirect(base_url().'auth/login', 'refresh');
    }

    # feature enable or disable check
    if($this->session->userdata('feature_personal') == 0) {
      # redirect to load disabled page
      \redirect(base_url().'feature/access?name=Personal Financial Tracker', 'refresh');
    }

    # move to two factor authentication page if security_personal is enabled
    if($this->session->userdata('security_personal') == 1) {
      # redirect to two factor authentication page
      \redirect(base_url().'feature/authentication?name=Personal Financial Tracker&url=personal&code=security_personal');exit;
    }
  }

#################################################################################################################################
#################################################################################################################################
#####################################################   PERSONAL   ##############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: index
   *
   * @return  view  personal page
   */
  public function index()
  {
    # load personal financial tracker page
    $this->load->view('personal');
  }

#################################################################################################################################
#################################################################################################################################
###############################################   PERSONAL - OVERVIEW   #########################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: overviewPersonal
   * Personal overview
   *
   * @return  view      personal overview
   * @throws  Exception Log error
   */
  public function overviewPersonal()
  {
    try {
      # Build data
      $data['data'] = $this->PersonalModel->getOverview();
    } catch (\Exception $e) {
      # log error
      \logger("error", "Failed to fetch personal overview data!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load user profile page
    $this->load->view('personal/overview/overview', $data);
  }

#################################################################################################################################
#################################################################################################################################
#############################################   PERSONAL - ADD EXPENSES   #######################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: addPersonal
   * Add personal expenses view
   *
   * @return  view  add personal expenses
   */
  public function addPersonal()
  {
    # load user profile page
    $this->load->view('personal/add/add');
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: insertPersonal
   * Add personal expenses
   *
   * Expected post data
   * 1. date
   * 2. type (INC / EXP / INV)
   * 3. title
   * 4. value
   * 5. param (optional)
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function insertPersonal()
  {
    try {

      # begin transaction
      $this->db->trans_begin();

        # get param details if not empty
        if(!empty($this->input->post('param_uuid')??"")) {
          # get param
          $getParam = $this->UserModel->fetchPersonalResponsibilities(['uuid'=>$this->input->post('param_uuid')])[0];
        }

        # Build body
        $body = [];
        $body['uuid']       = $this->LibraryModel->UUID();
        $body['user_uuid']  = $this->session->userdata('uuid');
        $body['param_uuid'] = $this->input->post('param_uuid') ?? "";
        if(!empty($this->input->post('param_uuid')??"")) {
          $body['type']     = $getParam['type'];
          $body['title']    = $getParam['title'];
          $body['value']    = $getParam['value'];
        } else {
          $body['type']     = $this->input->post('type') ?? "";
          $body['title']    = $this->input->post('title') ?? "";
          $body['value']    = $this->input->post('value') ?? 0;
        }
        $body['date']       = \get_date($this->input->post('date') ?? "");
        $body['created_dt'] = \get_date_time();
        $body['modified_dt']= \get_date_time();

        # insert Personalft
        $this->db->insert('personal', $body);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception inserting personal expenses!", [
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
   * Method: updatePersonal
   * Update personal expenses
   *
   * Expected post data
   * 1. date
   * 2. type (INC / EXP / INV)
   * 3. title
   * 4. value
   * 5. uuid
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function updatePersonal()
  {
    try {

      # begin transaction
      $this->db->trans_begin();

        # get responsibilities,  data
        $personal = $this->PersonalModel->fetchPersonalFT(['uuid'=>$this->input->post('uuid')])[0];

        # Build body
        $body = [];
        $body['type']         = $this->input->post('type') ?? $personal['type'];
        $body['title']        = $this->input->post('title') ?? $personal['title'];
        $body['value']        = $this->input->post('value') ?? $personal['value'];
        $body['date']         = \get_date($this->input->post('date') ?? "");
        $body['modified_dt']  = \get_date_time();

        # update Personalft
        $this->db->where('uuid', $this->input->post('uuid'))->update('personal', $body);

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception updating personal expenses!", [
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
   * Method: deletePersonal
   * Delete personal expenses
   *
   * Expected post data
   * 1. uuid
   *
   * @return string     success msg
   * @throws Exception  error msg
   */
  public function deletePersonal()
  {
    try {

      # begin transaction
      $this->db->trans_begin();

        # delete personal
        $this->db->where('uuid', $this->input->post('uuid'))->delete('personal');

      # commit transaction
      $this->db->trans_commit();

    } catch (\Exception $e) {
      # rollback transaction
      $this->db->trans_rollback();

      # log error
      \logger("error", "Exception deleting personal expenses!", [
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
###############################################   PERSONAL - DAY-TO-DAY   #######################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: dayToDay
   * Load day to day expenses
   *
   * @return  view      day to day expenses view
   * @throws  Exception Log error
   */
  public function dayToDay()
  {
    try {
      # Build data - date, daytoday, calculations
      $data['tab']  = $this->input->get('tab') ?? "overview";
      $data['date'] = $this->input->get('date') ? (!empty($this->input->get('date')) ? $this->input->get('date') : \get_date()) : \get_date();
      $data['total']  = $this->PersonalModel->fetchUserOverview(['from_date'=>\get_date($data['date']), 'to_date'=>\get_date($data['date'])]);
    } catch (\Exception $e) {
      # log error
      \logger("error", "Failed to fetch day to day view!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load user profile page
    $this->load->view('personal/daytoday/daytoday', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: dayToDayOverview
   * Load day to day expenses overview
   *
   * @return  view      day to day overview
   * @throws  Exception Log error
   */
  public function dayToDayOverview()
  {
    try {
      # Build data - date, daytoday, calculations
      $data['tab']    = $this->input->get('tab') ?? "overview";
      $data['date']   = $this->input->get('date') ? (!empty($this->input->get('date')) ? $this->input->get('date') : \get_date()) : \get_date();
      $data['total']  = $this->PersonalModel->fetchUserOverview(['from_date'=>\get_date($data['date']), 'to_date'=>\get_date($data['date'])]);
    } catch (\Exception $e) {
      # log error
      \logger("error", "Failed to fetch day to day expenses list!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load user profile page
    $this->load->view('personal/daytoday/daytoday_overview', $data);
  }

#################################################################################################################################
#################################################################################################################################
#################################################   PERSONAL - MONTHLY   ########################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: monthly
   * Load monthly expenses
   *
   * @return  view      monthly expenses view
   * @throws  Exception Log error
   */
  public function monthly()
  {
    try {

      # Parameters
      $data['tab']    = $this->input->get('tab') ? $this->input->get('tab') : "overview";
      $data['date']   = !empty($this->input->get('date')) ? $this->input->get('date') : \get_year_month();
      $yearmonth      = \date_parse_from_format("Y-m-d", $data['date']);
      $from_date      = $yearmonth['year']."-".$yearmonth['month']."-01";
      $to_date        = \date("Y-m-t", \strtotime($from_date));
      $data['total']  = $this->PersonalModel->fetchUserOverview(['from_date'=>$from_date, 'to_date'=>$to_date]);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching monthly personal view!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load user profile page
    $this->load->view('personal/monthly/monthly', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: monthlyOverview
   * Load monthly overview
   *
   * @return  view      monthly overview
   * @throws  Exception Log error
   */
  public function monthlyOverview()
  {
    try {

      # get from & to date
      $date      = !empty($this->input->get('date')) ? $this->input->get('date') : \get_year_month();
      $yearmonth = \date_parse_from_format("Y-m-d", $date);
      $from_date = $yearmonth['year']."-".$yearmonth['month']."-01";
      $to_date   = \date("Y-m-t", \strtotime($from_date));

      # build data & get monthly
      $data['total']  = $this->PersonalModel->fetchUserOverview(['user_uuid'=>$this->session->userdata('uuid'), 'from_date'=>$from_date, 'to_date'=>$to_date]);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching monthly personal overview!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load user profile page
    $this->load->view('personal/monthly/monthly_overview', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: monthlyCharts
   * Load monthly expenses in charts view
   *
   * @return  view      monthly expenses charts
   * @throws  Exception Log error
   */
  public function monthlyCharts()
  {
    try {

      # Parameters
      $chart_type = $this->input->get('chart_type');
      $yearmonth  = !empty($this->input->get('date')) ? $this->input->get('date') : \get_year_month();
      $yearmonth  = \date_parse_from_format("Y-m-d", $yearmonth);
      $from_date = $yearmonth['year']."-".$yearmonth['month']."-01";
      $to_date   = \date("Y-m-t", \strtotime($from_date));

      # BAR CHART
      if($chart_type == "BAR_CHART") {
        # load pie chart
        $this->load->view('personal/monthly/charts/barchart', $this->monthlyAreaChartCalculations($from_date));
      }

      # AREA CHART
      if($chart_type == "AREA_CHART") {
        # load pie chart
        $this->load->view('personal/monthly/charts/areachart', $this->monthlyAreaChartCalculations($from_date));
      }

      # PIE CHART
      if($chart_type == "PIE_CHART") {
        # load pie chart
        $this->load->view('personal/monthly/charts/piechart', $this->monthlyAreaChartCalculations($from_date));
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching monthly personal expenses charts data!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

  }

#################################################################################################################################
#################################################################################################################################
#################################################   PERSONAL - YEARLY   #########################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: yearly
   * Load yearly expenses
   *
   * @return  view      yearly expenses
   * @throws  Exception Log error
   */
  public function yearly()
  {
    # Init var
    $total_income = 0;
    $total_investment = 0;
    $total_expenses = 0;
    $all_expenses = 0;
    $total = 0;

    try {
      # parameter
      $year = !empty($this->input->get('year')) ? $this->input->get('year') : \date('Y');

      # get yearly data
      $yearly = $this->PersonalModel->fetchYearlyData(['year'=>$year]);

      # calculate overview
      foreach($yearly as $item) {
        # do calculations
        $total_income     = $total_income + $item['income'];
        $total_investment = $total_investment + $item['investment'];
        $total_expenses   = $total_expenses + $item['expenses'];
        $all_expenses     = $all_expenses + $item['all_expenses'];
        $total            = $total + $item['total'];

        # push to an array
        $data['yearly'][] = $item;
      }

      # Build data
      $data['tab']              = $this->input->get('tab') ?? "overview";
      $data['total_income']     = $total_income;
      $data['total_investment'] = $total_investment;
      $data['total_expenses']   = $total_expenses;
      $data['all_expenses']     = $all_expenses;
      $data['total']            = $total;
      $data['year']             = $year;

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching yearly personal expenses data!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load user profile page
    $this->load->view('personal/yearly/yearly', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: yearlyCharts
   * Load yearly expenses chart
   *
   * @return  view      yearly expenses chart
   * @throws  Exception Log error
   */
  public function yearlyCharts()
  {
    # Parameters
    $chart_type = $this->input->get('chart_type');
    $year = $this->input->get('year') ?? date('Y');

    try {

      # get yearly data
      $yearly = $this->PersonalModel->fetchYearlyData(['year'=>$year]);

      # BAR CHART
      if($chart_type == "BAR_CHART") {
        # load pie chart
        $this->load->view('personal/yearly/charts/barchart', $this->yearlyAreaBarChartCalculations($yearly));
      }

      # AREA CHART
      if($chart_type == "AREA_CHART") {
        # load pie chart
        $this->load->view('personal/yearly/charts/areachart', $this->yearlyAreaBarChartCalculations($yearly));
      }

      # PIE CHART
      if($chart_type == "PIE_CHART") {
        # load pie chart
        $this->load->view('personal/yearly/charts/piechart', $this->yearlypieProgressInvoiceCalculations($yearly));
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching yearly personal expenses chart data!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }
  }

#################################################################################################################################
#################################################################################################################################
#############################################   PERSONAL - RESPONSIBILITIES   ###################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: responsibilities
   * Load current month responsibilities
   *
   * @return  view      responsibilities view
   * @throws  Exception Log error
   */
  public function responsibilities()
  {
    try {
      # get responsibilities,  data
      $data['responsibilities'] = $this->PersonalModel->fetchResponsibilities();
    } catch (\Exception $e) {
      # log error
      \logger("error", "Failed to fetch current month responsibilities!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load user profile page
    $this->load->view('personal/responsibilities/responsibilities', $data);
  }

#################################################################################################################################
#################################################################################################################################
###################################################   PERSONAL - CUSTOM   #######################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: custom
   * Load custom expenses
   *
   * Expected query params
   * 1. cf_date
   * 2. ct_date
   *
   * @return  view  custom expenses
   */
  public function custom()
  {
    # parameters
    $fromDate = !empty($this->input->get('cf_date')) ? $this->input->get('cf_date') : \get_date();
    $toDate   = !empty($this->input->get('ct_date')) ? $this->input->get('ct_date') : \get_date();

    try {

      # validation 1
      if(empty($fromDate) && empty($toDate)) {
        # throw new exception
        throw new \Exception("Please select from/to dates!", 400);
      }

      # validation 2
      if($fromDate > $toDate) {
        # throw new exception
        throw new \Exception("Please select proper from & to dates!", 400);
      }

      # Build data - date, daytoday, calculations
      $data['tab']     = "overview";
      $data['cf_date'] = $fromDate;
      $data['ct_date'] = $toDate;
      $data['total']   = $this->PersonalModel->fetchUserOverview(['from_date'=>$fromDate, 'to_date'=>$toDate]);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching custom personal expenses data!", [
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
      # force append
      $data['cf_date'] = \get_date();
      $data['ct_date'] = \get_date();
    }

    # load custom page
    $this->load->view('personal/custom/custom', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: customOverview
   * Load custom overview
   *
   * Expected query params
   * 1. cf_date
   * 2. ct_date
   *
   * @return  view  custom expenses
   */
  public function customOverview()
  {
    # parameters
    $from_date = !empty($this->input->get('cf_date')) ? $this->input->get('cf_date') : \get_date();
    $to_date   = !empty($this->input->get('ct_date')) ? $this->input->get('ct_date') : \get_date();

    try {
      # Build data
      $data['tab']    = "overview";
      $data['total']  = $this->PersonalModel->fetchUserOverview(['user_uuid'=>$this->session->userdata('uuid'), 'from_date'=>$from_date, 'to_date'=>$to_date]);
    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching custom personal overview data!", [
        'data'    => $this->input->post() ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load custom page
    $this->load->view('personal/custom/custom_overview', $data);
  }

#################################################################################################################################
#################################################################################################################################
##################################################   COMMON METHODS   ###########################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: getExpenses
   * Load day to day expenses list
   *
   * Expected get params
   * 1. offset - start record from
   * 2. limit  - number of records to return
   * 3. pageno - pagenohelps to find the start & limit
   * 4. name   - apply sorting on key field
   * 5. order  - apply asc or desc order
   * 6. search - search with date, title, value
   * 7. date   - custom filter
   * 8. f_date - filer with from date
   * 9. t_date - filer with to date
   * 10. type  - daytoday, monthly, custom
   *
   * @return  string    Json data
   * @throws  Exception Log error
   */
  public function getExpenses()
  {
    # build data params
    $data = [];
    $data['type']   = !empty($this->input->get("type")) ? $this->input->get("type") : "monthly";
    $data['tab']    = !empty($this->input->get("tab")) ? $this->input->get("tab") : "details";
    $data['offset'] = !empty($this->input->get("offset")) ? $this->input->get("offset") : 0;
    $data['limit']  = !empty($this->input->get("limit")) ? (int)$this->input->get("limit") : 25;
    $data['pageno'] = !empty($this->input->get("pageno")) ? (int)$this->input->get("pageno") : 1;
    $data['name']   = !empty($this->input->get("name")) ? $this->input->get("name") : "date";
    $data['order']  = !empty($this->input->get("order")) ? $this->input->get("order") : "desc";
    $data['search'] = !empty($this->input->get("search")) ? $this->input->get("search") : "";
    $data['date']   = "";
    $data['f_date'] = "";
    $data['t_date'] = "";

    # calculate offset based on page no
    if($data['pageno'] != 1) {
      $data['offset'] = ($data['pageno']-1) * $data['limit'];
    }

    # get dates based on type param
    if($data['type'] == "daytoday") {
      $data['date']   = !empty($this->input->get("date")) ? $this->input->get("date") : \get_date();
    } else if($data['type'] == "monthly") {
      $getDate        = !empty($this->input->get("date")) ? $this->input->get("date") : \get_date();
      $yearmonth      = \date_parse_from_format("Y-m-d", $getDate);
      $data['f_date'] = $yearmonth['year']."-".$yearmonth['month']."-01";
      $data['t_date'] = \date("Y-m-t", \strtotime($data['f_date']));
    } else if($data['type'] == "custom") {
      $data['f_date'] = !empty($this->input->get("f_date")) ? $this->input->get("f_date") : \get_date();
      $data['t_date'] = !empty($this->input->get("t_date")) ? $this->input->get("t_date") : \get_date();
    }

    try {
      # get records
      $getData = $this->PersonalModel->fetchPersonalExpenses($data);
      $data['items']         = $getData['items'];
      $data['total_records'] = $getData['total_records'];
      $data['total_pages']   = \ceil($data['total_records']/$data['limit']);
    } catch (\Exception $e) {
      # log error
      \logger("error", "Failed to fetch ".$data['type']." expenses list!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load expenses page
    $this->load->view('personal/expenses_details', $data);
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: expensesModalview
   * Load day to day modal view
   *
   * @return  view      day to day modal view
   * @throws  Exception Log error
   */
  public function expensesModalview()
  {
    try {
      # get transaction
      $data['uuid'] = $this->input->get('uuid') ?? "";
      $data['item'] = $this->PersonalModel->fetchPersonalFT(['uuid'=>$data['uuid']])[0] ?? [];
    } catch (\Exception $e) {
      # log error
      \logger("error", "Failed to fetch expenses modalview!", [
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);
    }

    # load user profile page
    $this->load->view('personal/expenses_modalview', $data);
  }

#################################################################################################################################
#################################################################################################################################
#################################   INTERNAL PRIVATE METHODS - MONTHLY CALCULATIONS   ###########################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: monthlypieChartCalculations
   * Calculate data required for monthly pie chart
   *
   * @param   array   $items
   *
   * @return  array     data
   * @throws  Exception re-throw
   */
  private function monthlypieChartCalculations(array $items): array
  {
    # init var
    $data = [];
    $income = 0; // default income
    $term1 = 0; // 1st to 5th
    $term2 = 0; // 6th to 10th
    $term3 = 0; // 11th to 15th
    $term4 = 0; // 16th to 20th
    $term5 = 0; // 21th to 25th
    $term6 = 0; // 26th to eom

    try {

      # do calculations
      foreach($items as $item) {
        # calculate expenses
        if($item['day'] >= 1 && $item['day'] <= 5) {
          if($item['type'] != "INCOME") $term1 = $term1 + $item['value'];
        } else if($item['day'] >= 6 && $item['day'] <= 10) {
          if($item['type'] != "INCOME") $term2 = $term2 + $item['value'];
        } else if($item['day'] >= 11 && $item['day'] <= 15) {
          if($item['type'] != "INCOME") $term3 = $term3 + $item['value'];
        } else if($item['day'] >= 16 && $item['day'] <= 20) {
          if($item['type'] != "INCOME") $term4 = $term4 + $item['value'];
        } else if($item['day'] >= 21 && $item['day'] <= 25) {
          if($item['type'] != "INCOME") $term5 = $term5 + $item['value'];
        } else if($item['day'] >= 26 && $item['day'] <= 31) {
          if($item['type'] != "INCOME") $term6 = $term6 + $item['value'];
        }

        # calculate income
        if($item['type'] == "INCOME") $income = $income + $item['value'];
      }

      # Build body
      $data['income']     = $income;
      $data['term1']      = $term1;
      $data['term2']      = $term2;
      $data['term3']      = $term3;
      $data['term4']      = $term4;
      $data['term5']      = $term5;
      $data['term6']      = $term6;
      $data['allterms']   = $term1 + $term2 + $term3 + $term4 + $term5 + $term6;
      if($data['allterms'] == 0) {
        $data['term1_pct']  = 0;
        $data['term2_pct']  = 0;
        $data['term3_pct']  = 0;
        $data['term4_pct']  = 0;
        $data['term5_pct']  = 0;
        $data['term6_pct']  = 0;
      } else {
        $data['term1_pct']  = \number_format(($term1/$data['allterms']) * 100, 2);
        $data['term2_pct']  = \number_format(($term2/$data['allterms']) * 100, 2);
        $data['term3_pct']  = \number_format(($term3/$data['allterms']) * 100, 2);
        $data['term4_pct']  = \number_format(($term4/$data['allterms']) * 100, 2);
        $data['term5_pct']  = \number_format(($term5/$data['allterms']) * 100, 2);
        $data['term6_pct']  = \number_format(($term6/$data['allterms']) * 100, 2);
      }
      $data['total']      = $data['income'] - $data['allterms'];
      $data['terms']      = [$data['term1_pct'],$data['term2_pct'],$data['term3_pct'],$data['term4_pct'],$data['term5_pct'],$data['term6_pct']];
      $data['term1days']  = "1st to 5th";
      $data['term2days']  = "6th to 10th";
      $data['term3days']  = "11th to 15th";
      $data['term4days']  = "16th to 20th";
      $data['term5days']  = "21st to 25th";
      $data['term6days']  = "26th to ".$this->LibraryModel->getLastDaysInMonth()[\ltrim(\date('m'),0)]['last_day'].$this->LibraryModel->getNumberlm($this->LibraryModel->getLastDaysInMonth()[\ltrim(\date('m'),0)]['last_day']);
      $data['termdays']   = [$data['term1days'], $data['term2days'], $data['term3days'], $data['term4days'], $data['term5days'], $data['term6days']];

    } catch (\Exception $e) {
      # re-throw exception (calling client will handle it)
      throw $e;
    }

    # return result
    return $data;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: monthlyAreaChartCalculations
   * Calculate data required for monthly area chart
   *
   * @param   string    $date
   *
   * @return  array     data
   * @throws  Exception re-throw
   */
  private function monthlyAreaChartCalculations(string $date): array
  {
    # init var
    $data       = [];
    $yearmonth  = \date("Y-m", \strtotime($date));
    $total_days = \date("t", \strtotime($date));

    try {

      # loop days
      $day = 1;
      for($i=1; $i<=$total_days; $i=$i+7)
      {
        # build data
        if($i+6 >= $total_days) {
          $data['labels'][] = "Week-".$day." (".$i."-".$total_days.")";
          $from_date = $yearmonth."-".$i;
          $to_date = $yearmonth."-".$total_days;
        } else {
          $data['labels'][] = "Week-".$day." (".$i."-".($i+6).")";
          $from_date = $yearmonth."-".$i;
          $to_date = $yearmonth."-".($i+6);
        }

        # get user expenses
        $getOverview = $this->PersonalModel->fetchUserOverview(['from_date'=>$from_date, 'to_date'=>$to_date]);
        $data['terms'][] = $getOverview['investment'] + $getOverview['expenses'];

        # increment day
        $day++;
      }

    } catch (\Exception $e) {
      # re-throw exception (calling client will handle it)
      throw $e;
    }

    # return result
    return $data;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: monthlyBarChartCalculations
   * Calculate data required for monthly bar chart
   *
   * @param   array   $items
   *
   * @return  array     data
   * @throws  Exception re-throw
   */
  private function monthlyBarChartCalculations(array $items): array
  {
    # init var
    $data = [];
    $income = 0; // default income
    $term1 = 0; // 1st to 5th
    $term2 = 0; // 6th to 10th
    $term3 = 0; // 11th to 15th
    $term4 = 0; // 16th to 20th
    $term5 = 0; // 21th to 25th
    $term6 = 0; // 26th to eom

    try {

      # do calculations
      foreach($items as $item) {
        # calculate expenses
        if($item['day'] >= 1 && $item['day'] <= 5) {
          if($item['type'] != "INCOME") $term1 = $term1 + $item['value'];
        } else if($item['day'] >= 6 && $item['day'] <= 10) {
          if($item['type'] != "INCOME") $term2 = $term2 + $item['value'];
        } else if($item['day'] >= 11 && $item['day'] <= 15) {
          if($item['type'] != "INCOME") $term3 = $term3 + $item['value'];
        } else if($item['day'] >= 16 && $item['day'] <= 20) {
          if($item['type'] != "INCOME") $term4 = $term4 + $item['value'];
        } else if($item['day'] >= 21 && $item['day'] <= 25) {
          if($item['type'] != "INCOME") $term5 = $term5 + $item['value'];
        } else if($item['day'] >= 26 && $item['day'] <= 31) {
          if($item['type'] != "INCOME") $term6 = $term6 + $item['value'];
        }
        # calculate income
        if($item['type'] == "INCOME") $income = $income + $item['value'];
      }

      # Build body
      $data['income']     = $income;
      $data['term1']      = $term1;
      $data['term2']      = $term2;
      $data['term3']      = $term3;
      $data['term4']      = $term4;
      $data['term5']      = $term5;
      $data['term6']      = $term6;
      $data['allterms']   = $term1 + $term2 + $term3 + $term4 + $term5 + $term6;
      $data['total']      = $data['income'] - $data['allterms'];
      $data['terms']      = [$data['term1'],$data['term2'],$data['term3'],$data['term4'],$data['term5'],$data['term6']];
      $data['lastday']    = $this->LibraryModel->getLastDaysInMonth()[ltrim(date('m'),0)]['last_day'];
      $data['lastdaymsg'] = $this->LibraryModel->getNumberlm($data['lastday']);
      $data['term1days']  = "1st to 5th";
      $data['term2days']  = "6th to 10th";
      $data['term3days']  = "11th to 15th";
      $data['term4days']  = "16th to 20th";
      $data['term5days']  = "21st to 25th";
      $data['term6days']  = "26th to ".$this->LibraryModel->getLastDaysInMonth()[\ltrim(\date('m'),0)]['last_day'].$this->LibraryModel->getNumberlm($this->LibraryModel->getLastDaysInMonth()[\ltrim(\date('m'),0)]['last_day']);
      $data['termdays']   = [$data['term1days'], $data['term2days'], $data['term3days'], $data['term4days'], $data['term5days'], $data['term6days']];

    } catch (\Exception $e) {
      # re-throw exception (calling client will handle it)
      throw $e;
    }

    # return result
    return $data;
  }

#################################################################################################################################
#################################################################################################################################
###################################   INTERNAL PRIVATE METHODS - YEARLY CALCULATIONS   ##########################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: monthlyBarChartCalculations
   * Calculate data required for monthly bar chart
   *
   * @param   array   $items
   *
   * @return  array     data
   * @throws  Exception re-throw
   */
  private function yearlypieProgressInvoiceCalculations(array $items): array
  {
    # init var
    $total_income = 0;
    $total_expenses = 0;
    $total = 0;
    $allExpenses = [];
    $yearly = [];
    $percentages = [];

    try {

      # do calculations
      foreach($items as $item) {
        # do calculations
        $total_income = $total_income + $item['income'];
        $total_expenses = $total_expenses + $item['all_expenses'];
        $total = $total + $item['total'];
        $allExpenses[] = $item['all_expenses'];
      }

      # calculate each month percentage
      foreach($items as $item) {
        # do calculations
        if($total_expenses == 0) {
          $item['percentage']  = 0;
        } else {
          $item['percentage']  = \number_format(($item['all_expenses']/$total_expenses) * 100, 2);
        }
        $percentages[] = $item['percentage'];
        $yearly[] = $item;
      }

      # Build data
      $data['total_income']   = $total_income;
      $data['total_expenses'] = $total_expenses;
      $data['total']          = $total;
      $data['yearly']         = $yearly;
      $data['data']           = $allExpenses;
      $data['xaxis']          = $this->LibraryModel->getMonthName();
      $data['percentages']    = $percentages;

    } catch (\Exception $e) {
      # re-throw exception (calling client will handle it)
      throw $e;
    }

    # return result
    return $data;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: yearlyAreaBarChartCalculations
   * Calculate data required for yearly area & bar chart
   *
   * @param   array   $items
   *
   * @return  array     data
   * @throws  Exception re-throw
   */
  private function yearlyAreaBarChartCalculations(array $items): array
  {
    # init var
    $total_income = 0;
    $total_expenses = 0;
    $total = 0;
    $allExpenses = [];

    try {

      # do calculations
      foreach($items as $item) {
        # do calculations
        $total_income = $total_income + $item['income'];
        $total_expenses = $total_expenses + $item['all_expenses'];
        $total = $total + $item['total'];
        $allExpenses[] = $item['all_expenses'];
      }

      # Build data
      $data['total_income']   = $total_income;
      $data['total_expenses'] = $total_expenses;
      $data['total']          = $total;
      $data['yearly']         = $items;
      $data['data']           = $allExpenses;
      $data['xaxis']          = $this->LibraryModel->getMonthName();

    } catch (\Exception $e) {
      # re-throw exception (calling client will handle it)
      throw $e;
    }

    # return result
    return $data;
  }

}?>