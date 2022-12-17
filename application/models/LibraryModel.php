<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

# Load Firebase & Push
require(APPPATH . 'classes/Firebase.php');
require(APPPATH . 'classes/Push.php');

/**
 * LibraryModel
 */
class LibraryModel extends CI_Model
{
  # Constructor
  public function __construct()
  {
    # Parent constructor
    parent::__construct();

    # hide all error
    \error_reporting(0);
    \ini_set('display_errors', 0);
  }

#################################################################################################################################
#################################################################################################################################
########################################################   UUID GENERATOR   #####################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method Name : UUID
   * Description : Generate our own uuid's
   *
   * @param   boolean   $trim - false
   *
   * @return  string    UUID
   */
  public function UUID($trim = false): string
  {
    # Format
    $format = ($trim == false) ? '%04x%04x-%04x-%04x-%04x-%04x%04x%04x' : '%04x%04x%04x%04x%04x%04x%04x%04x';

    # generate UUID
    $uuid = sprintf($format,
      # 32 bits for "time_low"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),
      # 16 bits for "time_mid"
      mt_rand(0, 0xffff),
      # 16 bits for "time_hi_and_version", four most significant bits holds version number 4
      mt_rand(0, 0x0fff) | 0x4000,
      # 16 bits, 8 bits for "clk_seq_hi_res", 8 bits for "clk_seq_low", two most significant bits holds zero and one for variant DCE1.1
      mt_rand(0, 0x3fff) | 0x8000,
      # 48 bits for "node"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );

    return $uuid ?? "";
  }

#################################################################################################################################
#################################################################################################################################
######################################################   PASSWORD ENCRYPT   #####################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: passwordEncrypt
   * Encrypt password
   *
   * @param string $algo
   *
   * @param string $seed
   *
   * @param string $password
   *
   * @return string     password hash
   * @throws Exception  re-throw exception
   */
  public function passwordEncrypt(string $algo, string $seed, string $password): string
  {
    try {

      # get encryption algo
      $explode = \explode(",", $algo);
      $encryptionAlgo = $explode[0];

      # encryption algo check + generate password hash
      $hash = "";
      if(\strcasecmp($encryptionAlgo, "password_hash") == 0 && \strcasecmp($seed, "PASSWORD_BCRYPT") == 0) {
        # hash password
        $hash = \password_hash($password, PASSWORD_BCRYPT);
      }

    } catch (\Exception $e) {
      # re-throw
      throw $e;
    }

    # return
    return $hash;
  }

#################################################################################################################################
#################################################################################################################################
#######################################################   PASSWORD VERIFY   #####################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: passwordVerify
   * Verify password
   *
   * @param string $algo
   *
   * @param string $hash
   *
   * @param string $password
   *
   * @return bool       success - true | failed - false
   * @throws Exception  re-throw exception
   */
  public function passwordVerify($algo, $hash, $password): bool
  {
    try {

      # get encryption algo
      $explode = \explode(",", $algo);
      $verifyAlgo = $explode[1];

      # verify the hash against the password entered
      if (\strcasecmp($verifyAlgo, "password_verify") == 0) {
        # verify password
        $verify = \password_verify($password, $hash);
        if($verify) return true;
      }

    } catch (\Exception $e) {
      # re-throw
      throw $e;
    }

    # assume failed
    return false;
  }

#################################################################################################################################
#################################################################################################################################
#######################################################   UPLOAD IMAGE   ########################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: uploadImage
   * Upload image
   *
   * @param string $folderPath
   *
   * @param string $filename (without extension)
   *
   * @return array      filename
   * @throws Exception  error
   */
  public function uploadImage($folderPath, $filename): array
  {
    # Init var
    $result = [];

    try {

      # get file extension
      $imageFileType = \strtolower(\pathinfo($_FILES["image"]["name"],PATHINFO_EXTENSION));
      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        # throw new exception
        throw new \Exception("Sorry, only JPG, JPEG, PNG files are allowed!", 400);
      }

      # make sure file is an image
      $check = \getimagesize($_FILES["image"]["tmp_name"]);
      if($check == false) {
        # throw new exception
        throw new \Exception("Uploaded file is not an image!", 400);
      }

      # Build image path
      $imagePath = $folderPath.$filename.'.'.$imageFileType;

      # upload file
      if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
        # throw new exception
        throw new \Exception("Sorry, there was an error uploading your file!", 500);
      }

      # change image file permissions
      \chmod($imagePath,0777);

      # width & height of the image
      list($x, $y) = \getimagesize($imagePath);

      # get source based on image type
      if($imageFileType == "jpg" || $imageFileType == "jpeg") {
        $source = \imagecreatefromjpeg($imagePath);
      } else if($imageFileType == "png") {
        $source = \imagecreatefrompng($imagePath);
      }

      ## calculate new widths
      ## $square: square side length
      ## x offset based on the rectangle
      ## y offset based on the rectangle
      if($x > $y) {
        $square = $y;
        $offsetX = ($x - $y) / 2;
        $offsetY = 0;
      } else if($y > $x) {
        $square = $x;
        $offsetX = 0;
        $offsetY = ($y - $x) / 2;
      } else {
        $square = $x;
        $offsetX = $offsetY = 0;
      }

      # fixed size
      $endSize = 1024;

      # destination
      $destination = \imagecreatetruecolor($endSize, $endSize);

      # image crop re sampled
      \imagecopyresampled($destination, $source, 0, 0, $offsetX, $offsetY, $endSize, $endSize, $square, $square);

      # save image
      $finalFileName = $filename."-".\rand(11111111,99999999).'.'.$imageFileType;
      $destinyFile = $folderPath.$finalFileName;
      if($imageFileType == "jpg" || $imageFileType == "jpeg") {
        \imagejpeg($destination, $destinyFile, 100);
      } else if($imageFileType == "png") {
        \imagealphablending($destination , true);
        \imagesavealpha($destination , true);
        \imagepng($destination, $destinyFile, 9);
      }

      # Build response
      $result['filename'] = $finalFileName;

      # remove original file
      if(\file_exists($imagePath)) {
        # remove file
        unlink($imagePath);
      }

    } catch (\Exception $e) {
      # throw new exception
      throw new \Exception("Sorry, there was an error uploading your file!", 500);
    }

    # return result
    return $result;
  }

#################################################################################################################################
#################################################################################################################################
########################################################   COUNTRIES   ##########################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * fetchCountries
   * Fetch list of countries
   *
   * @param   array  $data
   *
   * @return  array     countries
   * @throws Exception  re-throw
   */
  public function fetchCountries(array $data=[]): array
  {
    # init var
    $countries = [];

    try {
      # build query
      $this->db->select('*')->from('countries');

      # filter with id
      if(isset($data['id']) && !empty($data['id'])) {
        $this->db->where('id',$data['id']);
      }

      # filter with code
      if(isset($data['code']) && !empty($data['code'])) {
        $this->db->where('code',$data['code']);
      }

      # filter with alpha3
      if(isset($data['alpha3']) && !empty($data['alpha3'])) {
        $this->db->where('alpha3',$data['alpha3']);
      }

      # order by id desc
      $this->db->order_by("id", "asc");

      # result query
      $countries = $this->db->get()->result_array();

    } catch (\Exception $e) {
      # re-throw
      throw $e;
    }

    # return response
    return $countries;
  }

#################################################################################################################################
#################################################################################################################################
#######################################################   SEND MAIL   ###########################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method Name : sendEmail
   * Description : Send Email
   *
   * @param   string    $email
   * @param   string    $subject
   * @param   string    $message
   *
   * @return  bool      success = 1 | failue = 0
   * @throws  Exception throw exception
   */
  public function sendEmail($email, $subject, $message): bool
  {
    # Load Library
    $this->load->library('email');

    try {
      # initialize config
      $config['protocol']       = $this->config->item('protocol');
      $config['smtp_host']      = $this->config->item('smtp_host');
      $config['smtp_port']      = $this->config->item('smtp_port');
      $config['smtp_user']      = $this->config->item('smtp_user');
      $config['smtp_pass']      = $this->config->item('smtp_pass');
      $config['smtp_crypto']    = $this->config->item('smtp_crypto');
      $config['mailtype']       = $this->config->item('mailtype');
      $config['smtp_timeout']   = $this->config->item('smtp_timeout');
      $config['charset']        = $this->config->item('charset');
      $config['wordwrap']       = $this->config->item('wordwrap');
      $config['crlf']           = $this->config->item('crlf');
      $config['newline']        = $this->config->item('newline');

      # Initialize config
      $this->email->initialize($config);

      # in codeigniter set new line
      $this->email->set_newline("\r\n");

      # send mail
      $this->email->from($config['smtp_user'], 'MANAGE EXPENSES');
      $this->email->to($email);
      $this->email->subject($subject);
      $this->email->message($message);
      $this->email->send();

    } catch (Exception $e) {
      if(!$this->email->send()) {
        # throw new exception
        throw new \Exception("Sorry, there was an error sending mail!", 500);
      }
    }

    # return result
    return true;
  }

#################################################################################################################################
#################################################################################################################################
####################################################   PUSH NOTIFICATIONS   #####################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * sendNotification
   *
   * @param   array  $data
   *
   * @return  bool      success - 1 | fail - 0
   * @throws  Exception log error
   */
  public function sendNotification(array $data): bool
  {
    # initialize var
    $imageUrl     = "";
    $notification = [];
    $body         = [];

    try {

      # get user data
      $getUser = $this->db->select('fcm_token')->from('users')->where('uuid', $data['user_uuid'])->get()->row_array();
      if(empty($getUser)) throw new \Exception("User not found!", 400);

      # make sure fcm token exist
      if(empty($getUser['fcm_token'])) throw new \Exception("User fcm_token is missing!", 400);

      # Create Objects
      $firebase = new Firebase();
      $push     = new Push();

      # Build image url
      if(!empty($data['image_url']) && $data['image_url'] != "default.jpg") {
        $imageUrl = base_url().$this->config->item('user_images').$data['image_url'];
      }

      # set values
      $push->setTitle($data['title']);
      $push->setMessage($data['message']);
      $push->setImage($imageUrl);

      # notification data
      $notification = $push->getPush();

      # build notification data
      $body = [
        "to" => $getUser['fcm_token'],
        "notification"  => [
          'title'        => $notification['title'],
          'body'         => $notification['message'],
          'image'        => $notification['image'],
          'click_action' => "OPEN_URL_ACTIVITY"
        ],
        "data"  => [
          'url' => base_url().$data['source_url']
        ]
      ];

      # Send
      $firebase->send($body);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception sending push notification!", [
        'params'        => $data ?? null,
        'notification'  => $body ?? null,
        'error'         => $e->getMessage(),
        'trace'         => $e->getTraceAsString()
      ]);

      # return
      return false;
    }

    # assume all good
    return true;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * updateNotifications
   *
   * @param   array  $params
   *
   * @return  bool
   * @throws  Exception log error
   */
  public function updateNotifications(array $params=[]): bool
  {
    try {

      # delete all user notifications if type="delete_all"
      if(isset($params['type']) && !empty($params['type']) && $params['type']=="delete_all") {
        # delete all user notifications
        $this->db->where('user_uuid', $params['user_uuid'])->delete('notifications');
        return true;
      }

      # filter based on params
      if(isset($params['uuid']) && !empty($params['uuid'])) {
        $this->db->where('uuid',$params['uuid']);
      }

      # filter with sender_uuid
      if(isset($params['sender_uuid']) && !empty($params['sender_uuid'])) {
        $this->db->where('sender_uuid',$params['sender_uuid']);
      }

      # filter with user_uuid
      if(isset($params['user_uuid']) && !empty($params['user_uuid'])) {
        $this->db->where('user_uuid',$params['user_uuid']);
      }

      # update
      $this->db->update('notifications', ['status'=>$params['status']]);

    } catch (\Exception $e) {
      # log error
      \logger("error", "Exception updating notification in models!", [
        'params'  => $params ?? null,
        'error'   => $e->getMessage(),
        'trace'   => $e->getTraceAsString()
      ]);

      # return an empty array
      return true;
    }

    # assume all good
    return true;
  }

#################################################################################################################################
#################################################################################################################################
####################################################   DATE CALCULATIONS   ######################################################
#################################################################################################################################
#################################################################################################################################

  /**
   * Method: getLastDaysInMonth
   *
   * @return  int   Response
   */
  public function getLastDaysInMonth()
  {
    # Build array
    $data[1]['last_day'] = 31;
    $data[2]['last_day'] = 28;
    if($this->checkForLeapYear(date('Y'))) {
      $data[2]['last_day'] = 29;
    }
    $data[3]['last_day'] = 31;
    $data[4]['last_day'] = 30;
    $data[5]['last_day'] = 31;
    $data[6]['last_day'] = 30;
    $data[7]['last_day'] = 31;
    $data[8]['last_day'] = 31;
    $data[9]['last_day'] = 30;
    $data[10]['last_day'] = 31;
    $data[11]['last_day'] = 30;
    $data[12]['last_day'] = 31;

    # return result
    return $data;
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: checkForLeapYear
   *
   * @param   int   $month
   *
   * @return  boolean   true/false
   */
  public function checkForLeapYear(int $year)
  {
    # validate year for leap year
    if ($year % 400 == 0) {
      return true;
    } else if($year % 4 == 0) {
      return true;
    } else if($year % 100 == 0) {
      return false;
    } else {
      return false;
    }
  }

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: getNumberlm
   *
   * @return  int   Response
   */
  public function getNumberlm($number)
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

#*******************************************************************************************************************************#
#*******************************************************************************************************************************#

  /**
   * Method: getMonthName
   *
   * @param   int     $number
   *
   * @return  string  Response
   */
  public function getMonthName($number=0)
  {
    # month array
    $months = [1=>"January", 2=>"February", 3=>"March", 4=>"April", 5=>"May", 6=>"June", 7=>"July", 8=>"August", 9=>"September", 10=>"October", 11=>"November", 12=>"December"];

    # return result
    if($number == 0) return $months;
    return $months[$number];
  }

}?>