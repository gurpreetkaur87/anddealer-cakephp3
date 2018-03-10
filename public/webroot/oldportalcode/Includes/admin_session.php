<?php

class admin_session{

var $db;

  function __construct(){
    //session_start();
    $this->db = mssql_connect('adel-sql2','vamlogin', 'go');
    mssql_select_db('andweb', $this->db);
  }//End __constructor

  function validate(){
    //if cookie data matches session array data then user is validated
    $ccustno = $_SESSION['ccustno'];
    $cusername = $_SESSION['cusername'];
    $cpassword = $_SESSION['cpassword'];
    $query = "select * from dealers where ccustno='$ccustno' AND cusername='$cusername' AND cpassword='$cpassword'";
    $result = mssql_query($query, $this->db);
    if (mssql_num_rows($result) == 1){
      //Set session information
//      echo "Validation Successful<br/>";
      return true;
    }else{
      //Throw an error here
      Die ("Validation Failed");
    }//End If-Else
    //Else chuck a hissey fit.
  }//end Function validate
  
  function start(){
    if (isset($_POST['login'])){
      $this->login();
    }else if (isset($_GET['m']) && $_GET['m'] == 'logout'){
      $this->logout();
    }else if(!isset($_SESSION['cusername'])){
      header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/web.php?p=10000");
    }else{
//      echo "Session Information is set, Calling Validation<br/>";
      $this->validate();
    }//End If-Else
  }//End Function Start
  
  function login(){
    $ccustno = 'ADME01';
    $cusername = $_POST['cusername'];
    $cpassword = $_POST['cpassword'];
    $query = "select * from dealers where ccustno='$ccustno' AND cusername='$cusername' AND cpassword='$cpassword'";
    $result = mssql_query($query, $this->db);
    if (mssql_num_rows($result) == 1){
      //Set session information
//      echo "Setting session information</br>";
      $this->set_session($cusername, $ccustno, $cpassword);
    }else{
      //Throw an error here
      throw new Exception("Invalid Login Details, Please try again....");
    }//End If-Else
  }//End Function login

  function set_session($cusername, $ccustno, $cpassword){
    $_SESSION['cusername'] = $cusername;
    $_SESSION['ccustno'] = $ccustno;
    $_SESSION['cpassword'] = $cpassword;
  }
  
  function logout(){
    unset($_SESSION['cusername']);
    session_destroy();
    header("Location: http://".$_SERVER['HTTP_HOST']."/web.php");
    die();
  }//End Function logout
  
}//End Class
?>
