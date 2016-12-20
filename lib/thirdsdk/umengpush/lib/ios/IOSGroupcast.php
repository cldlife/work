<?php
/**
 * @desc iOS组播
 */
class IOSGroupcast extends IOSNotification {

  function __construct () {
    parent::__construct();
    $this->data["type"] = "groupcast";
    $this->data["filter"] = NULL;
  }
}