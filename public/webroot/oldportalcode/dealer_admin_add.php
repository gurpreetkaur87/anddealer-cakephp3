<?php

/*
* dadd.php
* the a&d mercury dealers administration website.
**/

class dadd{

  var $t;
  var $site_ini;
  var $page;
  var $db;
  var $cusername;
  var $ccustno;
  var $clastname;
  var $autogen;
  var $cpass;

  function __construct($t, $site_ini, $page){
    $this->t = $t;
    $this->site_ini = $site_ini;
    $this->page = $page;
    include ('includes/db.php');
    $this->db = new db();
  }//end __construct

  function start(){
    //If Dealer is being Added - Add dealer - else display the basic page.
    if (isset($_POST['dadd']) && $_POST['dadd']){
      $this->get_data();
      $this->data_check();
      //VAM Dealer Code Check
      //$this->dealer_check($ccustno);
      //Auto Gerneate PIN if needed
      if ($this->autogen == 'Yes'){
        $this->cpass = $this->PIN_gen();
      }//End If
      //Construct Query
      $query = "INSERT INTO Dealers(cid, ccustno, CUSERNAME, CLASTNAME, CPASSWORD, IINACTIVE, IADMIN, ILOGGEDIN, NLEVEL, NWEBACCESS) VALUES ('$this->cpass', '$this->ccustno', '$this->cusername', '$this->clastname', '$this->cpass', '$this->cstatus', '1', '0', '10', '0')";
      //Excute Query
      $result = $this->db->query($query);
      if (!$result){
        throw new Exception("Database update failed");
      }//End If
      header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/web.php?p=1002&code=$this->ccustno");
    }//End If
    return $this->t;
  }//end function start()
  
  function get_data(){
    $this->cusername = $_POST['cusername'];
    $this->ccustno = $_POST['ccustno'];
    $this->clastname = $_POST['clastname'];
    $this->autogen = $_POST['autogen'];
    if (array_key_exists('cpass',$_POST)){
      $this->cpass = $_POST['cpass'];
    }//End If
    if (array_key_exists('cstatus',$_POST)){
      switch ($_POST['cstatus']):
        case 'Active':
        $this->cstatus = 0;
            break;
        case 'Inactive':
        $this->cstatus = 1;
            break;
        default:
        $this->cstatus = 1;
      endswitch;
    }else{
      $this->cstatus = 1;
    }//End if Else
  }//End Function get_data.
  
  function data_check(){
    //Check all Required Data has been posted
    $data = array('ccustno' =>$this->ccustno,
                  'clastname' =>$this->clastname,
                  'cusername' =>$this->cusername,
                  'autogen' =>$this->autogen);
    //cstatus does not need to be check as it has a default value assigned in the get_data function.
    reset($data);
    for($i=0;$i<sizeof($data);$i++){
      $cell_data = current($data);
      if (empty($cell_data)){
        throw new Exception("There is missing Data for the ".key($data)."<br>");
      }//End If
      next($data);
    }//End While
    if ($this->autogen == 'No'){
      if (empty($this->cpass)){
        throw new Exception("Please enter a PIN number or select Auto Generate");
      }//end If
    }//End If
  }//End Function data_check
  
  function dealer_check($ccustno){
    echo "Dealer Check";
    $query = "select * from rv_dealers where ccustno='$ccustno'";
    $result = $this->db->query($query);
    $num_rows = mssql_num_rows($result);
    if ($num_rows != 1){
      throw new Exception("Could not find a matching Dealer Code in the Database");
    }
    echo "Dealer check passed<br>";
  }//End function dealer_check
  
  function PIN_gen(){
    //This function generates a random 4 digit code for use as the Dealer PIN.
    $PIN = rand(1000, 9999);
    return $PIN;
  }
}//End Class
