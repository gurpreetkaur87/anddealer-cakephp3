<?php

class dealer_ipl{

var $page;
var $config;
var $t;
var $site_config;

  function __construct($t, $site_config, $page, $pcat){
    $this->t = $t;
    $this->page = $page;
    $this->site_config = $site_config;
//    include ('includes/dealer_sessions.php');
//    $this->dsess = new dealer_sessions($page);
//    include ('Includes/dealer_db.php');
//    $this->dealer_db = new dealer_db();
  }//End Constructr

  function start(){
    //Check Session Validity
//    $this->dsess->start();
    return $this->t;
  }//End function start
}//End Class
?>
