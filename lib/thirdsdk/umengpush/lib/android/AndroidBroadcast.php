<?php
/**
 * @desc Android广播
 */
class AndroidBroadcast extends AndroidNotification {

  function __construct () {
    parent::__construct();
    $this->data["type"] = "broadcast";
  }
}