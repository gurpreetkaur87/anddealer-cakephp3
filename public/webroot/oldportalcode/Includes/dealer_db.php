<?php
class dealer_db{

var $db;
var $result;
var $result_array;

  function __construct($db_name){
    //Do Database Connection
    //echo "Dealer DB Constructor Start</br>";
    switch ($db_name):
      case 'andmerc':
     // echo "Case ANDMERC</br>";
        $this->db = mssql_connect('adel-sql2','vamlogin', 'go');
        mssql_select_db('andmerc', $this->db);
      break;
      case 'ANDDealerAccess':
      //echo "Case ANDDealerAccess</br>";
        $this->db = mssql_connect('adel-sql2','web', 'weighedup');
        mssql_select_db('DealerAccess', $this->db);
      break;
      case 'andweb':
      //echo "Case ANDWEB</br>";
        $this->db = mssql_connect('adel-sql2','vamlogin', 'go');
        mssql_select_db('andweb', $this->db);
      break;
      default:
        echo "Throw an error here...";
    endswitch;
  }//End Constructor

  function do_query($query){
    $this->result = mssql_query($query, $this->db);
  }//End function do_query

  function fetch_result_array(){
    $i = 0;
    $row = mssql_fetch_array($this->result);
    while ($row){
      $this->result_array[$i] = $row;
      $row = mssql_fetch_array($this->result);
      $i++;
    }//End While
    return $this->result_array;
  }//End Function fetch_result_array

  function fetch_result(){
    return $this->result;
  }

  function clear_results(){
    $this->result = '';
    $this->result_array = '';
  }
}//End Class dealer_db
//"ANDDealerAccess","web","weighedup"
?>
