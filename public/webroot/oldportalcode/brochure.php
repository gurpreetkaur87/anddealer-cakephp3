<?php

class brochure{

var $result;
var $var_array;
var $t;
var $site_config;
var $page;
var $pcat;

  function __construct($t, $site_config, $page, $pcat){
    $this->t = $t;
    $this->site_config = $site_config;
    $this->page = $page;
    $this->pcat = $pcat;
  }//End Function __Construct

  function start(){
    if(empty($_GET['b'])){
      $this->no_brochure();
    }else{
      header("Location: brochures/".$_GET['c'].'/'.$_GET['b']);
    }//End If-Else
    return $this->t;
  }//End Function Start

  function no_brochure(){
    //Output the No Brochure available page.
    $this->t->set_file('no_brochure', 'nobrochure');
    $this->t->set_var('pcode', $_GET['pc']);
    $this->t->parse('no_brochure', 'nobrochure');
  }//Function no_brochure
}//End Class index
