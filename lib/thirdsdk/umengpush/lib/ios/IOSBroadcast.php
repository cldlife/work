<?php
/**
 * @desc iOS广播
 */
class IOSBroadcast extends IOSNotification {

  function __construct () {
    parent::__construct();
    $this->data["type"] = "broadcast";
  }
}