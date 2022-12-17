<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

##
##  CURRENCY FORMAT
##
if(!function_exists('currency_format'))
{
  /**
   * currency_format
   *
   * @param   int
   *
   * @return  int
   */
  function currency_format(int $value)
  {
    # trim value with -
    $value = trim($value, "-");

    # create instance
    $ci = &get_instance();

    # Load session library
    $ci->load->library('session');

    # get currency from session
    $currency = $ci->session->userdata("currency");

    # Handle Indian Currency Differently
    if($currency == "₹") {
      # convert to indian currency type
      $value = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $value);
    } else {
      $value = number_format($value);
    }

    # build data to return
    $data = $currency." ".$value;

    # return value
    return $data;
  }
}


##
##  NUMBER MESSAGE
##
if(!function_exists('number_lm'))
{
  /**
   * Method: number_lm
   * Description: get Number Last Message
   * Example: 1 -> 1st | 2 -> 2nd | etc...
   *
   * @param   int
   *
   * @return  string
   */
  function number_lm($number)
  {
    # Init var
    $message = "";

    # get number last msg
    if($number == 1 || $number == 21 || $number == 31) {
      $message = "st";
    } else if($number == 2 || $number == 22) {
      $message = "nd";
    } else if($number == 3 || $number == 23) {
      $message = "rd";
    } else {
      $message = "th";
    }

    # return result
    return $message;
  }
}


##
##  DATE TEXT FORMAT
##
if(!function_exists('date_text_format'))
{
  /**
   * Method: date_text_format
   * Description: convert date/timestamp to readable format
   * Example: 2020-01-01 -> 1st January, 2020
   *
   * @param   date
   *
   * @return  string
   */
  function date_text_format($date)
  {
    # convert date to readable format
    $date_format = date("jS F, Y", strtotime($date));

    # return result
    return $date_format;
  }
}


##
##  SHORT DATE TEXT FORMAT
##
if(!function_exists('short_date_text_format'))
{
  /**
   * Method: short_date_text_format
   * Description: convert date/timestamp to readable format
   * Example: 2020-01-01 -> 1st January
   *
   * @param   date
   *
   * @return  string
   */
  function short_date_text_format($date)
  {
    # convert date to readable format
    $date_format = date("jS F", strtotime($date));

    # return result
    return $date_format;
  }
}


##
##  YEAR DATE TEXT FORMAT
##
if(!function_exists('year_date_text_format'))
{
  /**
   * Method: year_date_text_format
   * Description: convert date/timestamp to readable format
   * Example: 2020-01-01 -> 1st Jan, 20
   *
   * @param   date
   *
   * @return  string
   */
  function year_date_text_format($date)
  {
    # convert date to readable format
    $date_format = date("jS M, y", strtotime($date));

    # return result
    return $date_format;
  }
}


##
##  BUILD UTC DATE TIME
##
if(!function_exists('get_date'))
{
  /**
   * Method: get_date
   * Description: generate current date in utc
   * Example: 2020-01-01
   *
   * @param   date
   *
   * @return  string
   */
  function get_date($date = '')
  {
    # return datetime
    return (new \DateTime($date,new \DateTimeZone('UTC')))->format('Y-m-d');
  }
}


##
##  BUILD UTC DATE TIME
##
if(!function_exists('get_date_time'))
{
  /**
   * Method: get_date_time
   * Description: generate current date time in utc
   * Example: 2020-01-01 12:01:01
   *
   * @param   date
   *
   * @return  string
   */
  function get_date_time($date = '')
  {
    # return datetime
    return (new \DateTime($date,new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');
  }
}


##
##  BUILD UTC DATE TIME
##
if(!function_exists('get_year_month'))
{
  /**
   * Method: get_year_month
   * Description: generate current date with year & month in utc
   * Example: 2020-01
   *
   * @param   date
   *
   * @return  string
   */
  function get_year_month($date = '')
  {
    # return datetime
    return (new \DateTime($date,new \DateTimeZone('UTC')))->format('Y-m');
  }
}


##
##  LOGGER IMPLEMENTAION
##
if(!function_exists('logger'))
{
  /**
   * Method: logger
   * Description: convert date/timestamp to readable format
   * Example: 2020-01-01 -> 1st January
   *
   * @param   string  $logType
   *
   * @param   string  $message
   *
   * @param   array   $data
   *
   * @return  string
   */
  function logger($logType, $message, $data)
  {
    # create instance
    $ci = &get_instance();

    # logpath + logfilename
    $filename = $ci->config->item('log_path')."custom-log-".(new \DateTime('',new \DateTimeZone('UTC')))->format('Y-m-d').".log";

    # Build log message
    $timestamp = (new \DateTime('',new \DateTimeZone('UTC')))->format('Y-m-d H:i:s.v');
    $data      = \json_encode($data);

    # build log string
    $string  = "";
    $string .= "[".$timestamp."] ";
    $string .= "[".\strtoupper($logType)."] ";
    $string .= $message." ".$data.PHP_EOL;

    # save the file
    \file_put_contents($filename,$string,\FILE_APPEND);

    # assume all okay
    return true;
  }
}