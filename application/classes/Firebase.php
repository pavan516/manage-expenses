<?php

# include firebase_key.php
require APPPATH . 'classes/firebase_key.php';

/**
 * @author Pavan kumar
 */
class Firebase {

  /**
   * sending push message to single user by firebase reg id
   *
   * @param   array $fields
   *
   * @return  bool
   * @throws  Exception
   */
  public function send(array $fields) :bool
  {
    try {
      # Set POST variables
      $url = 'https://fcm.googleapis.com/fcm/send';
      $headers = array(
        'Authorization: key=' . FIREBASE_API_KEY,
        'Content-Type: application/json'
      );

      # Open connection
      $ch = curl_init();

      # Set the url, number of POST vars, POST data
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      # Disabling SSL Certificate support temporarly
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

      # Execute post
      curl_exec($ch);

      # Close connection
      curl_close($ch);
    } catch (\Exception $e) {
      # throw back exception
      throw $e;
    }

    # assume all ok
    return true;
  }

}
?>