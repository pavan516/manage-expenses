<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * PersonalModel
 */
class PersonalModel extends CI_Model
{
  # Constructor
  public function __construct()
  {
    # Parent constructor
    parent::__construct();

    # Models
    $this->load->model('LibraryModel');
  }

#################################################################################################################################
#################################################################################################################################
############################################   FETCH RESPONSIBILITIES   #########################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * fetchResponsibilities
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchResponsibilities(array $data=[]): array
  {
    try {

      # Build sql
      $userUuid = $this->session->userdata('uuid') ?? "";
      $sql = "";
      $sql .= "SELECT * FROM `responsibilities_personal` WHERE user_uuid='".$userUuid."' AND uuid NOT IN ";
      $sql .= "(SELECT param_uuid FROM `personal` WHERE `date`>='".date('Y')."-".date('m')."-01' AND user_uuid='".$userUuid."')";

      # fetch data
      $responsibilities = $this->db->query($sql)->result_array();

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching responsibilities in models!", [
        'params'  => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return [];
    }

    # return response
    return $responsibilities;
  }

#################################################################################################################################
#################################################################################################################################
#####################################################   PERSONAL   ##############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * fetchPersonalFT
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchPersonalFT(array $data=[]): array
  {
    # init var
    $items = [];

    try {

      # build query
      $this->db->select('uuid, type, title, value, date')->from('personal')->where('user_uuid', $this->session->userdata('uuid'));

      # filter with uuid
      if(isset($data['uuid']) && !empty($data['uuid'])) {
        $this->db->where('uuid',$data['uuid']);
      }

      # filter with date
      if(isset($data['date']) && !empty($data['date'])) {
        $this->db->where('date',$data['date']);
      }

      # filter with from_date
      if(isset($data['from_date']) && !empty($data['from_date'])) {
        $this->db->where('date >=',$data['from_date']);
      }

      # filter with to_date
      if(isset($data['to_date']) && !empty($data['to_date'])) {
        $this->db->where('date <=',$data['to_date']);
      }

      # order by id desc
      $this->db->order_by("id", "desc");

      # result query
      $items = $this->db->get()->result_array();

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching personal expenses in models!", [
        'params'  => $data ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return [];
    }

    # return response
    return $items;
  }

  /**
   * fetchPersonalExpenses
   *
   * @param   array  $data
   * Expected params
   * 1. draw
   * 2. start (start)
   * 3. length (Rows display per page)
   * 4. index (Column index)
   * 5. name (Column name)
   * 6. sortOrder (sort asc or desc)
   * 7. search (search value)
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchPersonalExpenses(array $data=[]): array
  {
    # init var
    $result = [];
    $result['items'] = [];
    $result['total_records'] = 0;

    try {

      ###
      ### FETCH TOTAL RECORDS COUNT
      ###

          # build query
          $this->db->select('count(id) as total_records')->from('personal')->where('user_uuid', $this->session->userdata('uuid'));

          # filter with uuid
          if(isset($data['uuid']) && !empty($data['uuid'])) {
            $this->db->where('uuid', $data['uuid']);
          }

          # filter with date
          if(isset($data['date']) && !empty($data['date'])) {
            $this->db->where('date', $data['date']);
          }

          # filter with f_date
          if(isset($data['f_date']) && !empty($data['f_date'])) {
            $this->db->where('date >=', $data['f_date']);
          }

          # filter with t_date
          if(isset($data['t_date']) && !empty($data['t_date'])) {
            $this->db->where('date <=', $data['t_date']);
          }

          # filter with search
          if(isset($data['search']) && !empty($data['search'])) {
            $this->db->like('title', $data['search']);
          }

          # get total records count
          $result['total_records'] = $this->db->get()->row_array()['total_records'] ?? 0;

      ###
      ### FETCH LIST OF EXPENSES
      ###

          # build query
          $this->db->select('uuid, date, title, value, type')->from('personal')->where('user_uuid', $this->session->userdata('uuid'));

          # filter with uuid
          if(isset($data['uuid']) && !empty($data['uuid'])) {
            $this->db->where('uuid', $data['uuid']);
          }

          # filter with date
          if(isset($data['date']) && !empty($data['date'])) {
            $this->db->where('date', $data['date']);
          }

          # filter with f_date
          if(isset($data['f_date']) && !empty($data['f_date'])) {
            $this->db->where('date >=', $data['f_date']);
          }

          # filter with t_date
          if(isset($data['t_date']) && !empty($data['t_date'])) {
            $this->db->where('date <=', $data['t_date']);
          }

          # filter with search
          if(isset($data['search']) && !empty($data['search'])) {
            $this->db->like('title', $data['search']);
          }

          # filter with order & name
          $this->db->order_by($data['name'], $data['order']);

          # filter with limit
          $this->db->limit($data['limit'], $data['offset']);

          # get records count
          $result['items'] = $this->db->get()->result_array();

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching personal expenses in models!", [
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

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * fetchYearlyData
   *
   * @param   array  $data
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchYearlyData(array $data=[]): array
  {
    # init var
    $items = [];

    try {

      # user_uuid & year is mandatory
      $userUuid = $this->session->userdata('uuid');

      # calculate months
      $limit = 12;
      if($data['year'] == \date('Y')) $limit = \date('m');

      # get income | investment | expenses | total for each month
      for($i=1; $i<=$limit; $i++) {
        # build from & to dates
        $from_date = $data['year']."-".$i."-01";
        $to_date   = $data['year']."-".($i+1)."-01";

        # build data
        $items[$i]['income']       = $this->db->query("SELECT SUM(value) as total_income FROM personal where `user_uuid`='".$userUuid."' AND `date`>='".$from_date."' AND `date`<'".$to_date."' AND `type`='INCOME'")->row_array()['total_income'] ?? 0;
        $items[$i]['investment']   = $this->db->query("SELECT SUM(value) as total_investment FROM personal where `user_uuid`='".$userUuid."' AND `date`>='".$from_date."' AND `date`<'".$to_date."' AND `type`='INVESTMENT'")->row_array()['total_investment'] ?? 0;
        $items[$i]['expenses']     = $this->db->query("SELECT SUM(value) as total_expenses FROM personal where `user_uuid`='".$userUuid."' AND `date`>='".$from_date."' AND `date`<'".$to_date."' AND `type`='EXPENSES'")->row_array()['total_expenses'] ?? 0;
        $items[$i]['all_expenses'] = $items[$i]['investment'] + $items[$i]['expenses'];
        $items[$i]['total']        = $items[$i]['income'] - $items[$i]['all_expenses'];
        $items[$i]['month_name']   = $this->LibraryModel->getMonthName($i);
        $items[$i]['month']        = $i;
      }

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching personal yearly expenses in models!", [
        'params'  => $params ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return [];
    }

    # return response
    return $items;
  }

#################################################################################################################################
#################################################################################################################################
#####################################################   OVERVIEW   ##############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * getOverview
   *
   * @param   array  $params
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function getOverview(array $params=[]): array
  {
    # init var
    $data     = [];
    $userUuid = $this->session->userdata('uuid');
    $date     = \get_year_month()."-01";

    try {

      # get total income till current month 1st
      $getTotalIncomeTillLastMonth = $this->db->query("SELECT SUM(value) as income FROM personal where `user_uuid`='".$userUuid."' AND `date`<'".$date."' AND `type`='INCOME'")->row_array()['income'] ?? 0;

      # get total investment till current month 1st
      $getTotalInvestmentTillLastMonth = $this->db->query("SELECT SUM(value) as investment FROM personal where `user_uuid`='".$userUuid."' AND `date`<'".$date."' AND `type`='INVESTMENT'")->row_array()['investment'] ?? 0;

      # get total expenses till current month 1st
      $getTotalExpensesTillLastMonth = $this->db->query("SELECT SUM(value) as expenses FROM personal where `user_uuid`='".$userUuid."' AND `date`<'".$date."' AND `type`='EXPENSES'")->row_array()['expenses'] ?? 0;

      # get income | investment | expenses | total
      $currentMonthIncome = $this->db->query("SELECT SUM(value) as income FROM personal where `user_uuid`='".$userUuid."' AND `date`>='".$date."' AND `type`='INCOME'")->row_array()['income'] ?? 0;
      $currentMonthInvestment = $this->db->query("SELECT SUM(value) as investment FROM personal where `user_uuid`='".$userUuid."' AND `date`>='".$date."' AND `type`='INVESTMENT'")->row_array()['investment'] ?? 0;
      $currentMonthExpenses = $this->db->query("SELECT SUM(value) as expenses FROM personal where `user_uuid`='".$userUuid."' AND `date`>='".$date."' AND `type`='EXPENSES'")->row_array()['expenses'] ?? 0;

      # build data
      $data['last_income']        = $getTotalIncomeTillLastMonth;
      $data['last_investment']    = $getTotalInvestmentTillLastMonth;
      $data['last_expenses']      = $getTotalExpensesTillLastMonth;
      $data['savings']            = $data['last_income'] - ($data['last_investment']+$data['last_expenses']);
      $data['current_income']     = $currentMonthIncome;
      $data['current_investment'] = $currentMonthInvestment;
      $data['current_expenses']   = $currentMonthExpenses;
      $data['current_total']      = $data['current_income'] - ($data['current_investment']+$data['current_expenses']);
      $data['total']              = $data['savings'] + $data['current_total'];

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching expenses overview in models!", [
        'params'  => $params ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return [];
    }

    # return response
    return $data;
  }

#################################################################################################################################
#################################################################################################################################
#####################################################   OVERVIEW   ##############################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * getOverview
   *
   * @param   array  $params
   * expected: from_date, to_date
   *
   * @return  array     data
   * @throws  Exception Log error
   */
  public function fetchUserOverview(array $params=[]): array
  {
    # init var
    $data     = [];
    $userUuid = $this->session->userdata('uuid') ?? "";

    try {

      # get total income | investment | expenses
      $data['income']     = $this->db->query("SELECT SUM(value) as income FROM personal where `user_uuid`='".$userUuid."' AND `date`>='".$params['from_date']."' AND date <= '".$params['to_date']."' AND `type`='INCOME'")->row_array()['income'] ?? 0;
      $data['investment'] = $this->db->query("SELECT SUM(value) as investment FROM personal where `user_uuid`='".$userUuid."' AND `date`>='".$params['from_date']."' AND date <= '".$params['to_date']."' AND `type`='INVESTMENT'")->row_array()['investment'] ?? 0;
      $data['expenses']   = $this->db->query("SELECT SUM(value) as expenses FROM personal where `user_uuid`='".$userUuid."' AND `date`>='".$params['from_date']."' AND date <= '".$params['to_date']."' AND `type`='EXPENSES'")->row_array()['expenses'] ?? 0;
      $data['total']      = $data['income'] - $data['investment'] - $data['expenses'];

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception fetching user expenses overview in models!", [
        'params'  => $params ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return [];
    }

    # return response
    return $data;
  }

}?>