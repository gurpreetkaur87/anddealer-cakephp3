<?php

class techdocs{

var $t;
var $site_config;
var $page;
var $pcat;
var $mode;
var $icat;
var $itechdoc;

  function __construct($t, $site_config, $page, $pcat){
    $this->t = $t;
    $this->site_config = $site_config;
//    include ('includes/dealer_sessions.php');
//    $this->dsess = new dealer_sessions($page);
    include ('Includes/dealer_db.php');
    $this->dealer_db = new dealer_db('andmerc');
    $this->page = $page;
    $this->pcat = $pcat;
    $this->mode = $_GET['p'];
    $this->t->debug = 0;
    $this->icat = isset($_GET['icat'])? $_GET['icat']:null;
  }

  function start(){
    //Check Session Validity
//    $this->dsess->start();
    //Begin Processing
    if (isset($_POST['itemno'])){
      $this->search($_POST['itemno']);
    }else{
      $this->display();
    }
    return $this->t;
  }
  
  function display(){
    if (isset($this->icat)){
      $this->itechdoc = parse_ini_file($this->site_config['techdoc'], true);
      $this->t->set_block('content','techdoc_list','techdocs');
      $index = $this->itechdoc['index'];
      for ($j=1;$j<=sizeof($index);$j++){
        $temp_index = explode(",",$index[$j]);
        $key = $temp_index[0];
        $value = $temp_index[1];
        $full_index[$key] = $value; 
      }//End For
      $data = $this->itechdoc[$full_index[$this->icat]];
      $this->t->set_var('doc_title', htmlentities($data['title']));
      for ($i=1;$i<=sizeof($data)-1;$i++){
        $items = explode(',',$data[$i]);
        $this->t->set_var(array('folder' => rawurlencode($full_index[$this->icat]),
                                'title' => htmlentities(urldecode($items[0])),
                                'file' => rawurlencode($items[1]),
                                'visible' => 'visible'));
        $this->t->parse('techdocs','techdoc_list', true);
      }//End For
    }else{
      $this->t->set_var('visible','hidden');
    }//End If
    return($this->t);
  }//End Function Start
}
?>