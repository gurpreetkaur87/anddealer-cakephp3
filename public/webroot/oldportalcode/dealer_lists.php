<?php

class dealer_lists{

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
    if (isset($_GET['m'])){
      switch($_GET['m']){
        case 'en':
          $file = 'dealers/engineering/'.$_GET['i'].'.html';
          if (!file_exists($this->site_config['template_path'].$file)){
            header("Location: ".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."web.php?p=1099");
          }
          $this->t->set_file('content', $file);
        break;
        case 'id':
        //Nothing required for Installation Diagrams, directly calls the pdf file via document.php
        break;
        case 'im':
          $this->t->set_file('content', 'dealers/manuals_detail.html');
          $this->t->set_block('content', 'list', 'list_items');
          if ($_GET['mt'] == 'inst'){
            $mtype = 'Instruction';
            $tcode = 'inst';
            $this->read_config("config/dealers/inst_manuals.ini");
          }elseif($_GET['mt'] == 'maint'){
            $mtype = 'Maintenance / Technical';
            $tcode = 'maint';
            $this->read_config("config/dealers/main_tech_manuals.ini");
          }else{
            throw new Exception ("Invalid Parameter Passed to Function");
          }//End If-else-if-else
          $indicators = $this->config['Indicators'];
          $scales = $this->config['Scales'];
          $balances = $this->config['Balances'];
          $checkweigher = $this->config['Checkweigher'];
          $medical = $this->config['Medical'];
          $other = $this->config['Other'];
          for($i=1;$i<=sizeof($indicators);$i++){
              $array = explode(',',$indicators[$i]);
              $tarray[1] = $array[0];
              $tarray[2] = $array[1];
              $indicators[$i] = $tarray;
          }//End For
          for($i=1;$i<=sizeof($scales);$i++){
              $array = explode(',',$scales[$i]);
              $tarray[1] = $array[0];
              $tarray[2] = $array[1];
              $scales[$i] = $tarray;
          }//End For
          for($i=1;$i<=sizeof($balances);$i++){
              $array = explode(',',$balances[$i]);
              $tarray[1] = $array[0];
              $tarray[2] = $array[1];
              $balances[$i] = $tarray;
          }//End For
          for($i=1;$i<=sizeof($checkweigher);$i++){
              $array = explode(',',$checkweigher[$i]);
              $tarray[1] = $array[0];
              $tarray[2] = $array[1];
              $checkweigher[$i] = $tarray;
          }//End For
          for($i=1;$i<=sizeof($medical);$i++){
              $array = explode(',',$medical[$i]);
              $tarray[1] = $array[0];
              $tarray[2] = $array[1];
              $medical[$i] = $tarray;
          }//End For
          for($i=1;$i<=sizeof($other);$i++){
              $array = explode(',',$other[$i]);
              $tarray[1] = $array[0];
              $tarray[2] = $array[1];
              $other[$i] = $tarray;
          }//End For
          switch ($_GET['t']){
          case 'ind':
            $this->t->set_var(array('mtype' => 'Indicator '.$mtype,
                                    'tcode' => $tcode));
            $this->config = $indicators;
            $this->display();
          break;
          case 'bal':
            $this->t->set_var(array('mtype' => 'Balance '.$mtype,
                                    'tcode' => $tcode));
            $this->config = $balances;
            $this->display();
          break;
          case 'sca':
            $this->t->set_var(array('mtype' => 'Scale '.$mtype,
                                    'tcode' => $tcode));
            $this->config = $scales;
            $this->display();
          break;
          case 'chk':
            $this->t->set_var(array('mtype' => 'Checkweigher '.$mtype,
                                    'tcode' => $tcode));
            $this->config = $checkweigher;
            $this->display();
          break;          
          case 'med':
            $this->t->set_var(array('mtype' => 'Medical '.$mtype,
                                    'tcode' => $tcode));
            $this->config = $medical;
            $this->display();
          break;  
          case 'oth':
            $this->t->set_var(array('mtype' => 'Other '.$mtype,
                                    'tcode' => $tcode));
            $this->config = $other;
            $this->display();
          break;
          default:
          throw new Exception ("Invalid Parameter Passed to Function");
          break;
          }//End Switch
        break;
        case 'mark':
          $this->t->set_file('content', 'dealers/marketing/'.$_GET['d']);
        break;
        default:
        throw new Exception ("Invalid Parameter Passed to Function");
        break;
      }//End Switch
    }else{
      switch($this->page){
        case 9003:
          $this->t->set_block('content', 'list', 'list_items');
          $this->read_config("config/dealers/marketing.ini");
          $this->display();
        break;
        case 9004:
          $this->t->set_block('content', 'list', 'list_items');
          $this->read_config("config/dealers/engineering.ini");
          $this->display();
        break;
        case 9005:
          //No action is required to display this page.
        break;
        case 9006:
          $this->t->set_block('content', 'list', 'list_items');
          $this->read_config("config/dealers/inst_diags.ini");
          $this->t->set_var('tcode', 'idiag');
          $this->display();
        break;
        default:
          throw new Exception ("Invalid Page Number Passed to Function");
      }//End Switch
    }//End If
    return $this->t;
  }//End function start
  
  function display(){
    for ($i=1;$i<=sizeof($this->config);$i++){
      for ($j=1;$j<=sizeof($this->config[$i]);$j++){
        $field = 'field'.$j;
        $this->t->set_var($field, $this->config[$i][$j]);
      }//End For
      $this->t->parse('list_items', 'list', true);
    }//End For
  }//End function display
  
  function read_config($file){
    $this->config = parse_ini_file($file, true);
    if(empty($this->config)){
      throw new Exception ("Fatal Error: Unable to read configuration File");
    }//End If
  }//End Function read_config
}//End Class dealer_lists

?>
