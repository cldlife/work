<?php
/**
 * @desc iOS单播
 */
class IOSUnicast extends IOSNotification {

  function __construct () {
    parent::__construct();
    $this->data["type"] = "unicast";
    $this->data["device_tokens"] = NULL;
  }

}