<?php

class dealer_admin_reports{

var $t;
var $site_config;
var $page;
var $pcat;
var $dealer_db;
var $cpass;
var $admin_session;

  function __construct($t, $site_config, $page, $pcat){
    $this->t = $t;
    $this->site_config = $site_config;
    $this->page = $page;
    $this->pcat = $pcat;
    include ('Includes/dealer_db.php');
    $this->dealer_db = new dealer_db('andweb');
    include ('includes/admin_session.php');
    $this->admin_session = new admin_session();
  }//End function __construct

  function start(){
    try{
      $this->admin_session->start();
      if (isset($_GET['m'])){
        switch ($_GET['m']):
          case 'reports':
            //call the report selection code
            if (isset($_GET['rep'])){
              $this->do_report($_GET['rep']);
            }else{
              throw new Exception('No Report Specified.');
            }//End If else
          break;
          default:
          $this->display_report_list();
        endswitch;
      }else{
        $this->t->set_file('content','admin/admin_reports.html');
      }//end If-Else
    }catch(Exception $ex){
      $this->t->set_file('content','error.html');
      throw $ex;
    }//end Try-Catch
    return $this->t;
  }//end function start

  function display_report_list(){
    $this->t->set_file('content','admin/admin_reports.html');
  }//End Function display_report_list
  
  function do_report($rep){
    switch ($rep): 
      case 1:
      if (!empty($_POST['rtime'])){
        $this->report1($_POST['rtime']);
      }else{
        throw new Exception('Query Parameter not provided.');
      }//End If-Else
      break;
      case 2:
        $this->report2();  
      break;
      case 3:
        if (!empty($_POST['ccustno'])){
          $this->report3($_POST['ccustno']);  
        }else{
          throw new Exception('Query Parameter not provided.');
        }//End If-Else
      break;
      case 4:
        $this->report4();  
      break;
      default:
        echo "Default<br/>";
    endswitch;
  }//End function do_reports
  
  function report2(){
    $query = "SELECT ccustno, CUSERNAME, CFIRSTNAME, CLASTNAME, NWEBACCESS
              FROM Dealers
              ORDER BY NWEBACCESS DESC";
    $this->dealer_db->do_query($query);
    $result = $this->dealer_db->fetch_result_array();
    $this->t->set_file('content','admin/admin_report2.html');
    $this->t->set_block('content','dlist','dealers');
    $row = 'row1';
    if (sizeof($result)> 0){
      for($i=0;$i<sizeof($result);$i++){
        $result_data = $result[$i];
        $this->t->set_var(array('ccustno' => $result_data['ccustno'],
                                'cusername' => $result_data['CUSERNAME'],
                                'cfirstname' => $result_data['CFIRSTNAME'],
                                'clastname' => $result_data['CLASTNAME'],
                                'nwebaccess' => $result_data['NWEBACCESS'],
                                'row' => $row));
        $this->t->parse('dealers','dlist',true);
        $row = $row == 'row1' ? 'row2':'row1';
      }//End For
    }else{
      $this->t->set_var('no_results', 'Sorry, Your Query did not return any results.');
    }//End If-Else      
  }//End Function report2
  
  function report3($ccustno){
    $query = "SELECT ccustno, CUSERNAME, CFIRSTNAME, CLASTNAME, NWEBACCESS
              FROM Dealers
              WHERE (ccustno = '$ccustno')
              ORDER BY NWEBACCESS DESC";
    $this->dealer_db->do_query($query);
    $result = $this->dealer_db->fetch_result_array();
    $this->t->set_file('content','admin/admin_report3.html');
    $this->t->set_block('content','dlist','dealers');
    $row = 'row1';
    if (sizeof($result)> 0){
      for($i=0;$i<sizeof($result);$i++){
        $result_data = $result[$i];
        $this->t->set_var(array('ccustno' => $result_data['ccustno'],
                                'cusername' => $result_data['CUSERNAME'],
                                'cfirstname' => $result_data['CFIRSTNAME'],
                                'clastname' => $result_data['CLASTNAME'],
                                'nwebaccess' => $result_data['NWEBACCESS'],
                                'row' => $row));
        $this->t->parse('dealers','dlist',true);
        $row = $row == 'row1' ? 'row2':'row1';
      }//End For
    }else{
      $this->t->set_var('no_results', 'Sorry, Your Query did not return any results.');
    }//End If-Else
  }//End Function report3
  
  function report1($days){
    $date = date('m/d/Y',strtotime("-$days day"));
    //echo "Date: $date<br/>";
    $query = "SELECT ccustno, CUSERNAME, CFIRSTNAME, CLASTNAME, NWEBACCESS, TWEBACCESS
              FROM Dealers
              WHERE (TWEBACCESS BETWEEN '$date' AND GETDATE())
              ORDER BY NWEBACCESS DESC";
              //echo "Query: $query<br/>";
    $this->dealer_db->do_query($query);
    $result = $this->dealer_db->fetch_result_array();
    $this->t->set_file('content','admin/admin_report1.html');
    $this->t->set_block('content','dlist','dealers');
    $row = 'row1';
    if (sizeof($result)> 0){
      for($i=0;$i<sizeof($result);$i++){
        $result_data = $result[$i];
        $this->t->set_var(array('ccustno' => $result_data['ccustno'],
                                'cusername' => $result_data['CUSERNAME'],
                                'cfirstname' => $result_data['CFIRSTNAME'],
                                'clastname' => $result_data['CLASTNAME'],
                                'nwebaccess' => $result_data['NWEBACCESS'],
                                'twebaccess' => $result_data['TWEBACCESS'],
                                'days' => $days,
                                'row' => $row));
        $this->t->parse('dealers','dlist',true);
        $row = $row == 'row1' ? 'row2':'row1';
      }//End For
    }else{
      $this->t->set_var('no_results', 'Sorry, Your Query did not return any results.');
    }//End If-Else      
  }//End Function report4  
  
  function report4(){
    $query = "SELECT ccustno, CUSERNAME, CFIRSTNAME, CLASTNAME, NWEBACCESS
              FROM Dealers
              WHERE IINACTIVE = 1
              ORDER BY ccustno DESC";
    $this->dealer_db->do_query($query);
    $result = $this->dealer_db->fetch_result_array();
    $this->t->set_file('content','admin/admin_report4.html');
    $this->t->set_block('content','dlist','dealers');
    $row = 'row1';
    if (sizeof($result)> 0){
      for($i=0;$i<sizeof($result);$i++){
        $result_data = $result[$i];
        $this->t->set_var(array('ccustno' => $result_data['ccustno'],
                                'cusername' => $result_data['CUSERNAME'],
                                'cfirstname' => $result_data['CFIRSTNAME'],
                                'clastname' => $result_data['CLASTNAME'],
                                'nwebaccess' => $result_data['NWEBACCESS'],
                                'row' => $row));
        $this->t->parse('dealers','dlist',true);
        $row = $row == 'row1' ? 'row2':'row1';
      }//End For
    }else{
      $this->t->set_var('no_results', 'Sorry, Your Query did not return any results.');
    }//End If-Else      
  }//End Function report2
  
  

}//End Class
?>