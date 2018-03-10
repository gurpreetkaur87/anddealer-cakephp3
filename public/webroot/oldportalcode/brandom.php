<?php

class brandom{

var $result;
var $var_array;
var $t;
var $site_ini;
var $page;
var $pcat;
var $data;

  function __construct($t, $site_ini, $page){
    $this->t = $t;
    $this->site_ini = $site_ini;
    $this->page = $page;
  }

  function start(){
    //Get File List
    $list = $this->get_file_list();
    //Randomly select a file from the list.
    $file = $list[mt_rand(1,sizeof($list))];
    //Assign variables to the template engine.
    $this->t -> set_var('banner', $file);
    return $this->t;
  }//End Function Start
  
  function get_file_list(){
      $bconfig =  @parse_ini_file('config/banners.ini');
    return $bconfig;
  }//End Function get_file_list
}//End Class
?>