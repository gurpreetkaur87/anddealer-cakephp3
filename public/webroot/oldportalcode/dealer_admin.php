<?php

class dealer_admin{

var $t;
var $site_config;
var $page;
var $pcat;
var $dealer_db;
var $cpass;
var $admin_session;
var $debug = false;
var $company_list;

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
          case 'list':
            $this->t->set_file('content','admin/admin_index.html');
            $this->get_dealer_list();
          break;
          case 'edit':
            $this->t->set_file('content','admin/admin_edit.html');
            $code = $_GET['c'];
            $user = $_GET['u'];
            $this->display_edit_page($code, $user);
          break;
          case 'add':
            $this->t->set_file('content','admin/admin_add.html');
  //          $this->display_add_dealer_page();
          break;
          case 'delete':
            $this->t->set_file('content','admin/admin_delete.html');
            $code = $_GET['c'];
            $user = $_GET['u'];
            $this->get_delete_data($code, $user);
          break;
          case 'details':
            $this->t->set_file('content','admin/admin_detail.html');
            $code = $_GET['c'];
            $user = $_GET['u'];
            $this->display_detail_page($code, $user);
          break;
          case 'search':
            $this->t->set_file('content','admin/admin_search.html');
  //          $this->display_add_dealer_page();
          break;
          case 'report':
            $this->t->set_file('content','admin/admin_reports.html');

  //          $this->display_add_dealer_page();
          break;
          case 'resend':
            $this->t->set_file('content','admin/admin_confirm.html');
            $dealer_data = $this->get_dealer_data($_GET['cid']);
            $data = $dealer_data[0];
            $mail_result = $this->email_pass($data['cid'], $data['ccustno'], $data['CUSERNAME'], $data['CFIRSTNAME'], $data['CLASTNAME'],$data['CPASSWORD'], $data['CEMAIL']);
            $this->t->set_var('action','resent their password details');
          break;
          default:
          $this->get_dealer_list();
        endswitch;
      }else{
        //Test for POSTed Data from add, delete, edit & Search pages...
        if (isset($_POST['m'])){
          switch ($_POST['m']):
            case 'add':
              $this->t->set_file('content','admin/admin_confirm.html');
              $this->t->set_var('action','added');
              $this->add_dealer();
                break;
            case 'delete':
              $this->t->set_file('content','admin/admin_confirm.html');
              $this->t->set_var('action','deleted');
              $this->delete_dealer();
                break;
            case 'edit':
              $this->t->set_file('content','admin/admin_confirm.html');
              $this->t->set_var('action','edited');
              $this->edit_dealer();
                break;
            case 'search':
              $this->t->set_file('content','admin/admin_search.html');
              $this->do_search();
            default:
            //Throw an error here!!!
          endswitch;
        }else{
          //If all of these tests fail then display the basic dealer list
          $this->get_dealer_list();
        }//End If-Else
      }//End if-else
    }catch(Exception $ex){
      //Create an Instance of the Error handler
      $this->t->set_file('content','admin/admin_error.html');
      $this->t->set_var('error', $ex->getmessage());
    }
    return $this->t;
  }//End function Start

  function get_dealer_list(){
    $query="select cid, ccustno, CUSERNAME, CFIRSTNAME, CLASTNAME, CPASSWORD, IINACTIVE from dealers order by ccustno, cusername";
    $this->dealer_db->do_query($query);
    $result = $this->dealer_db->fetch_result_array();
    $soap_result = $this->get_vantage_cust_list();
    $this->t->set_block('content','dlist','dealers');
    $row = 'row1';
    for($i=0;$i<sizeof($result);$i++){
      $result_data = $result[$i];
      $disabled = $result_data['IINACTIVE'] == 1 ?'checked':'';
      if($disabled == 'checked'){
        $row = 'red';
      }else{
        //Determine what colour row should be... :)
      }//End if-else
      $ccompany = array_key_exists(trim($result_data['ccustno']),$soap_result)? $soap_result[trim($result_data['ccustno'])]:"";
      $this->t->set_var(array('cid' => $result_data['cid'],
                              'ccustno' => $result_data['ccustno'],
                              'cusername' => $result_data['CUSERNAME'],
                              'cfirstname' => $result_data['CFIRSTNAME'],
                              'clastname' => $result_data['CLASTNAME'],
                              'cpassword' => $result_data['CPASSWORD'],
                              'ccompany' => ucwords(strtolower($ccompany)),
                              'iinactive' => $disabled,
                              'row' => $row));
      $this->t->parse('dealers','dlist',true);
      $row = $row == 'row1' ? 'row2':'row1';
    }//End For
  }//End Function get_dealer_list

  function display_edit_page($code,$user){
    $query="select cid, ccustno, CUSERNAME, CFIRSTNAME, CLASTNAME, CPASSWORD, CEMAIL, IINACTIVE from dealers where ccustno='$code' and cusername='$user'";
    $this->dealer_db->do_query($query);
    $result = $this->dealer_db->fetch_result_array();
    $result_data = $result[0];
    $disabled = $result_data['IINACTIVE']?'checked':'';
    $this->t->set_var(array('cid' => trim($result_data['cid']),
                            'ccustno' => trim($result_data['ccustno']),
                            'cusername' => trim($result_data['CUSERNAME']),
                            'cfirstname' => trim($result_data['CFIRSTNAME']),
                            'clastname' => trim($result_data['CLASTNAME']),
                            'cpassword' => trim($result_data['CPASSWORD']),
                            'cemail' => trim($result_data['CEMAIL']),
                            'iinactive' => $disabled));
  }//End function Display_edit_page

  function display_detail_page($code, $user){
    $query="select cid, ccustno, CUSERNAME, CFIRSTNAME, CLASTNAME, CPASSWORD, CEMAIL, IINACTIVE, twebaccess, nwebaccess
            from dealers
            where ccustno='$code' and cusername='$user'";
    $this->dealer_db->do_query($query);
    $result = $this->dealer_db->fetch_result_array();
    $result_data = $result[0];
    $disabled = $result_data['IINACTIVE']?'checked':'';
    $this->t->set_var(array('cid' => $result_data['cid'],
                            'ccustno' => $result_data['ccustno'],
                            'cusername' => $result_data['CUSERNAME'],
                            'cfirstname' => $result_data['CFIRSTNAME'],
                            'clastname' => $result_data['CLASTNAME'],
                            'cpassword' => $result_data['CPASSWORD'],
                            'cemail' => $result_data['CEMAIL'],
                            'iinactive' => $disabled,
                            'twebaccess' => $result_data['twebaccess'],
                            'nwebaccess' => $result_data['nwebaccess']));
  }//End function Display_detail_page

  function add_dealer(){
    //Retreive Data
    $cusername = strtolower($_POST['cfirstname'].$_POST['clastname']);
    $ccustno = strtoupper($_POST['ccustno']);
    $cfirstname = ucwords(strtolower($_POST['cfirstname']));
    $clastname = ucwords(strtolower($_POST['clastname']));
    $cemail = $_POST['cemail'];
    $autogen = $_POST['autogen'];
    $this->cpass = array_key_exists('cpass',$_POST) ? $_POST['cpass']:'';
    if (array_key_exists('cstatus',$_POST)){
      switch ($_POST['cstatus']):
        case 'Active':
        $cstatus = 0;
            break;
        case 'Inactive':
        $cstatus = 1;
            break;
        default:
        $cstatus = 1;
      endswitch;
    }else{
      $cstatus = 1;
    }//End if Else
    //Check all Required Data has been posted
    $data = array('ccustno' => $ccustno,
                  'cfirstname' => $cfirstname,
                  'clastname' => $clastname,
                  'cusername' => $cusername,
                  'cemail' => $cemail,
                  'autogen' => $autogen);
    //cstatus does not need to be check as it has a default value assigned in the get_data function.
    reset($data);
    for($i=0;$i<sizeof($data);$i++){
      $cell_data = current($data);
      if (empty($cell_data)){
        throw new Exception("There is missing Data for the ".key($data)."<br>");
      }//End If
      next($data);
    }//End While
    if ($autogen == 'No'){
      if (empty($this->cpass)){
        throw new Exception("Please enter a PIN number or select Auto Generate");
      }
    }else{
      //This function generates a random 4 digit code for use as the Dealer PIN.
      $this->cpass = rand(1000, 9999);
    }//end If
    //Get next ID Number
    $query = "select count(cid) from dealers";
    //Excute Query
    $this->dealer_db->do_query($query);
    $result_array = $this->dealer_db->fetch_result_array();
    $cid = $result_array[0][0];
    $cid ++;
    //Check for existing Dealer Code in Vantage
    $result = $this->get_vantage_custID($ccustno);
    if($result == false || !$result['CustID'] == $ccustno){
      throw new Exception("Could not find a matching Dealer Code in the Database");
    }else{
      //Check that this user does not already exist in the database
      $query = "select * from dealers where ccustno='$ccustno' and cusername='$cusername'";
      $this->dealer_db->do_query($query);
      $result = $this->dealer_db->fetch_result();
      if($result){
        $num_rows = mssql_num_rows($result);
        if ($num_rows > 0){
          throw new Exception("This user already exists in the Database");
        }//End If
      }//End If
      //die("Early Script end to prevent actual update</br>");
      //Construct Query
      $query = "INSERT INTO Dealers(cid, ccustno, CUSERNAME, CFIRSTNAME, CLASTNAME, CPASSWORD, IINACTIVE, IADMIN, ILOGGEDIN, NLEVEL, NWEBACCESS, CEMAIL) VALUES ('$cid', '$ccustno', '$cusername', '$cfirstname', '$clastname', '$this->cpass', '$cstatus', '1', '0', '10', '0','$cemail')";
      //Excute Query
      $this->dealer_db->do_query($query);
      $result = $this->dealer_db->fetch_result();
      if (!$result){
        throw new Exception("Database update failed");
      }//End If
      $mail_result = $this->email_pass($cid, $ccustno, $cusername, $cfirstname, $clastname, $this->cpass, $cemail);
      if ($mail_result){
        $this->t->set_var('confirm','Successful');
      }else{
        $this->t->set_var('confirm','Email Failed');
      }//End If-Else
    }//End If-Else
  }//End function add_dealer

  function email_pass($cid, $ccustno, $cusername, $cfirstname, $clastname, $cpassword, $cemail){
    include ('includes/email.php');
    $email = new email($this->site_config['smtp_server'],'html');
    $subject = "Attention: $cfirstname $clastname - Confidential A&D Mercury Dealer Access Information";
    $message = file_get_contents('templates/admin/admin_dealer_email.txt');
    $message = str_replace('{cfirstname}', $cfirstname, $message);
    $message = str_replace('{ccustno}', $ccustno, $message);
    $message = str_replace('{cusername}', $cusername, $message);
    $message = str_replace('{cpassword}', $cpassword, $message);
    $mail_result = $email->send($cemail, $this->site_config['webmaster'], $subject, $message);
    if (!$mail_result){
      throw new Exception("The database has been updated, however an Email could not be successfully sent. Please send the information manually.");
      return false;
    }
    return true;
  }//End Function email_pass

  function get_dealer_data($cid){
    $query="select cid, ccustno, CUSERNAME, CFIRSTNAME, CLASTNAME, CPASSWORD, CEMAIL
            from dealers
            where cid=$cid";
    $this->dealer_db->do_query($query);
    return $this->dealer_db->fetch_result_array();
  }//end get_dealer_data

  function get_delete_data($code, $user){
    $query = "Select cid, cfirstname, clastname, cemail from dealers where ccustno='$code' AND cusername='$user'";
    $this->dealer_db->do_query($query);
    $result_array = $this->dealer_db->fetch_result_array();
    $this->t->set_var(array('ccustno' => $code,
                            'cusername' => $user,
                            'cfirstname' => $result_array[0]['cfirstname'],
                            'clastname' => $result_array[0]['clastname'],
                            'cemail' => $result_array[0]['cemail'],
                            'cid' => $result_array[0]['cid']));
  }//End function get_delete_data

  function delete_dealer(){
    $cid = $_POST['cid'];
    $code = $_POST['ccustno'];
    $user = $_POST['cusername'];
    $query = "DELETE FROM dealers WHERE cid='$cid' AND ccustno='$code' AND cusername='$user'";
    $this->dealer_db->do_query($query);
    $result = $this->dealer_db->fetch_result();
    if (!$result){
      throw new Exception("Error: Unable to delete user");
     }//End If
  }//End Function delete_dealer

  function edit_dealer(){
    $ccustno = $_POST['ccustno'];
    $corigusername = $_POST['corigusername'];
    $cusername = $_POST['cusername'];
    $cfirstname = $_POST['cfirstname'];
    $clastname = $_POST['clastname'];
    $cpassword = $_POST['cpassword'];
    $iinactive = isset($_POST['iinactive'])?1:0;//Conversion required here
    $cemail = $_POST['cemail'];
    $query = "update dealers set ccustno='$ccustno',
                                  cusername='$cusername',
                                  cfirstname='$cfirstname',
                                  clastname='$clastname',
                                  cpassword='$cpassword',
                                  iinactive='$iinactive',
                                  cemail='$cemail'
              where ccustno='$ccustno' and cusername='$corigusername'";
    $this->dealer_db->do_query($query);
    if(!$this->dealer_db->fetch_result()){
      Die ('Update Failed'); //Better error handling required here
    }//End if
  }//End Function edit Dealer

  function do_search(){
    if (isset($_POST['search']) && !empty($_POST['search'])){
      $search = $_POST['search'];
    }else{
      //Default condition or error code here!
    }//end if-else
    $query = "select * from dealers where cfirstname like '%$search%' or
                                          clastname like '%$search%' or
                                          cusername like '%$search%' or
                                          ccustno like '%$search%'";
    $this->dealer_db->do_query($query);
    $result_array = $this->dealer_db->fetch_result_array();
    $soap_result = $this->get_vantage_cust_list();
    $this->t->set_block('content','dlist','dealers');
    $row = 'row1';
    for($i=0;$i<sizeof($result_array);$i++){
      $result_data = $result_array[$i];
      $disabled = $result_data['IINACTIVE'] == 1 ?'checked':'';
      if($disabled == 'checked'){
        $row = 'red';
      }else{
        //Determine what colour row should be... :)
      }//End if-else
      $ccompany = array_key_exists(trim($result_data['ccustno']),$soap_result)? $soap_result[trim($result_data['ccustno'])]:"";
      $this->t->set_var(array('cid' => $result_data['cid'],
                              'ccustno' => $result_data['ccustno'],
                              'cusername' => $result_data['CUSERNAME'],
                              'cfirstname' => $result_data['CFIRSTNAME'],
                              'clastname' => $result_data['CLASTNAME'],
                              'cpassword' => $result_data['CPASSWORD'],
                              'ccompany' => ucwords(strtolower($ccompany)),
                              'iinactive' => $disabled,
                              'row' => $row));
      $this->t->parse('dealers','dlist',true);
      $row = $row == 'row1' ? 'row2':'row1';
    }//End For
  }//End function do_search
  
  function get_vantage_custID($custID){
    include('includes\vantageSoap.php');
    $params=array('CompanyID' => 'AND','custID' => "$custID");
    $vantageSoap = new vantageSoap('GetByCustID',$params);
    $vantageSoap -> setup();                        
    
    //Make SOAP Call
    $result = $vantageSoap -> call();
    //print_r($result);
    if (empty($result)){
      //Dealer Record Not Found Error Here
      $this->t->set_var(array('evisible' =>'visible',
                              'visible' => 'hidden'));
    }else{
      $data_customer = $result['GetByCustIDResult']['CustomerDataSet']['Customer'][0];
      if ($this->debug){
        echo "Data_Part: $data_customer;;;</br>";
        while(list($key,$value)=each($data_customer)){
          echo "<b>$key:</b> $value</br>";
        }//End While
      }//End If Debug
      return $data_customer;
    }//End If
    return false;
  }//End Function get_vantage_cust()
  
  function get_vantage_cust_list(){
    include('includes/vantageSoap.php');
    $params=array('CompanyID' => 'AND', 'whereClause'=> '','pageSize' => 0, 'absolutePage' => 0, 'morePages' => false);
    $vantageSoap = new vantageSoap('GetList',$params);
    $vantageSoap -> setup();                        
    //Make SOAP Call
    $result = $vantageSoap -> call();
    if (empty($result)){
      //Dealer Record Not Found Error Here
			echo "Vantage SOAP Call is empty<br/>";
      $this->t->set_var(array('evisible' =>'visible',
                              'visible' => 'hidden'));
    }else{
      $data_customer = $result['GetListResult']['CustomerListDataSet']['CustomerList'];
      if ($this->debug){
        echo "Data_Part: $data_customer;;;</br>";
        while(list($key,$value)=each($data_customer)){
          echo "<b>$key:</b></br>";
          while(list($key2,$value2)=each($value)){
            echo "&nbsp;&nbsp;<b>$key2</b> = $value2;</br>";
          }
        }
      }//End If Debug
      $array_size = sizeof($data_customer);
      for($i=0;$i<$array_size;$i++){
        $this->company_list[$data_customer[$i]['CustID']] = $data_customer[$i]['Name'];
      }//End For
    }//End If-Else
  return $this->company_list;
  }//End Function get_vantage_cust()
}//End Class
?>
