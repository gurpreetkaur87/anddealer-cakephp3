<?php

class set_contact{

var $t;

  function __construct($t, $site_config, $page, $page_config){
    $this->t = $t;
  }//End function __Construct

  function start(){
    if (isset($_GET['s'])){
      $subject = $_GET['s'];
//      $selected = "selected='selected'";
//      echo "Selected: $selected<br/>";
//      die("program Stop");
      switch ($subject):
        case 'sale':
          $this->t->set_var(array('sale_select' => "selected='selected'",
                                  'hire_select' => '',
                                  'web_select' => ''));
            break;
        case 'hire':
          $this->t->set_var(array('sale_select' => '',
                                  'hire_select' => "selected='selected'",
                                  'web_select' => ''));
            break;
        case 'web':
          $this->t->set_var(array('sale_select' => '',
                                  'hire_select' => '',
                                  'web_select' => "selected='selected'"));
            break;
        default:
          $this->t->set_var(array('sale_select' => "selected='selected'",
                                  'hire_select' => '',
                                  'web_select' => ''));
      endswitch;
    }else{
    $this->t->set_var(array('sale_select' => "selected='selected'",
                            'hire_select' => '',
                            'web_select' => ''));
    }//End If-Else
    return $this->t;
  }//End function start
}//End Class set_contact
