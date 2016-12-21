<?php
/**
 * @desc Android组播
 */
class AndroidGroupcast extends AndroidNotification {

  function __construct () {
    parent::__construct();
    $this->data["type"] = "groupcast";
    $this->data["filter"] = NULL;
  }
}