<?php

class feature_logo_index{

var $t;
var $site_config;
var $page;
var $pcat;
var $feature_logo_index;

  function __construct($t, $site_config, $page, $pcat){
    $this->t = $t;
    $this->site_config = $site_config;
    $this->page = $page;
    $this->pcat = $pcat;
  }//End function __construct

  function start(){
    $this->feature_logo_index = parse_ini_file('config/products/feature_logo_index.ini',true);
    $this->display();
    return $this->t;
  }//End function Start
  
  function display(){
    $this->t -> set_block('master', 'Feat_list', 'features');
    reset($this->feature_logo_index);
    while(current($this->feature_logo_index)){
      $subarray = current($this->feature_logo_index);
      reset($subarray);
      $this->t->set_var(array('logo' => key($this->feature_logo_index).'.jpg',
                              'id' => key($this->feature_logo_index),
                              'logo_title' => $subarray['title'],
                              'description' =>$subarray['data']));
      $this->t -> parse('features', 'Feat_list', true);   
      next($this->feature_logo_index);
    }//End While
  }//End Function Display
}//End Class
?>