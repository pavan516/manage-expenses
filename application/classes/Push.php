<?php

/**
 * @author Pavan Kumar
 */
class Push
{
  # push title, message, image, data/payload
  private $title;
  private $message;
  private $image;

  # Constructor
  function __construct() {
    # set default values
    $this->title    = "";
    $this->message  = "";
    $this->image    = "";
  }

  # Set title
  public function setTitle($title) {
    $this->title = $title;
  }

  # Set message
  public function setMessage($message) {
    $this->message = $message;
  }

  # Set Image
  public function setImage($imageUrl) {
    $this->image = $imageUrl;
  }

  # Set push function
  public function getPush()
  {
    # Build response
    $result = [];
    $result['title']    = $this->title;
    $result['message']  = str_replace("</b>", "", str_replace("<b>", "", $this->message));
    $result['image']    = $this->image;

    # return
    return $result;
  }

}