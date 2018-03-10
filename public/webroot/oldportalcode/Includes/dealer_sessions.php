<?php

/**
* AND Web Dealer Sessions Handler
* This class handles the verification of session validity for the dealers
* section between the PHP code of the site and the FoxPro "Spare Parts System"
* written by Data Interactive Pty Ltd
*
* Dealer_sessions.php Written by Peter Lowrey
**/

class dealer_sessions{

var $result;
var $cookie;
var $conn;
var $dbresult;
var $odbc;
var $debug = 0;
var $page;

  function __construct($page){
  //echo "Dealer Session manager called</br>";
    $this->page = $page;
    //Create data connection
    $this->odbc = odbc_connect("andsession", '', '') or die(odbc_errormsg());
  }//End Constructor

  function start(){
    //Query the data source
    $this->query();
    //Retreive Cookie Data
    $this->get_cookie();
    //Compare data source to cookie data
    $this->compare();
    odbc_close($this->odbc);
    return $this->result;
  }//End Function start

  function query(){
    $this->dbresult = odbc_do($this->odbc, "Select * from wwsession");
  }//End Function Query

  function get_cookie(){
    //check that the cookie is set...
    if(isset($_COOKIE['adAD'])){
      $this->cookie = trim($_COOKIE['adAD']);
    }else{
//      Die('Cookie Not Set');
//echo "Throwing Exception: No Cookie</br>";
      throw new Exception("<b>Cookie not Set.  You do not have a valid session, please check your network connection or login again.</b>");
    }//end if-else
  }//End function get_cookie

  function compare(){
    $row = odbc_fetch_row($this->dbresult);
    while ($row){
      $data = odbc_result($this->dbresult,1);
      $laston = odbc_result($this->dbresult,4);
      $match = strcmp(trim($this->cookie), trim($data));
      if ($match == 0){
        $this->session = $data;
        $this->result = true;
        //Test for time condition as well.
        if(strtotime($laston) <= (time()- 10810) ){
          //Die('Time since last access exceeded');
          //echo "Throwing Exception: Idle Timeout</br>";
          throw new Exception("<b>You do not have a valid session (timed out), please check your network connection or login again.</b>",$this->page);
        }
        break;
      }//End If
      $row = odbc_fetch_row($this->dbresult);
    }//End While
    if (!$this->result){
      $this->result = false;
      //echo "Throwing Exception: No Matching Session</br>";
      throw new Exception("<b>You do not have a valid session (no match), please check your network connection or login again.</b>",$this->page);
    }//End If
    if ($this->debug == 1){
      //echo "Cookie: $this->cookie - Session: $this->session<br>";
      Die();
    }
  }//End Function compare

}//End Class dealer_sessions
?>
