<?php

class VantageSoap{

  var $url;
  var $action;
  var $username;
  var $password;
  var $params;
  var $result_type;
  var $dataset_type;
  var $data_section;
  var $debug = false;
  var $result;  


  public function __construct($call_type, $params){
    //Read Config type and load configuration for the call_type
    $config = parse_ini_file('vantage_soap.ini',true);
    $this->params = $params;
    $this->username = $config['config']['username'];
    $this->password = $config['config']['password'];
    $this->url = $config[$call_type]['url'];
    $this->action = $config[$call_type]['action'];
    $this->result_type = $config[$call_type]['result_type'];
    $this->dataset_type = $config[$call_type]['dataset_type'];
    $this->data_section = $config[$call_type]['data_section'];  
  }//End __constructor

  public function setup(){
    //Include the WSSecurity Extension for the PHP SOAP System
    include('WSSecurity.class.php');
    //Create the WSSecurity Enabled SOAP Client
    $this->client = new WSSoapClient($this->url,array('trace' => 1,"features" => SOAP_SINGLE_ELEMENT_ARRAYS));
    //Assign the Username and Password to the SOAP Client for inclusion in the Security Headers
    $this->client->__setUsernameToken($this->username,$this->password);
    
  }//End setup()
  
  public function call(){
    //Attempt to Make the SOAP Call and check for Exceptions
    try{
      //while(list($tkey, $tvalue)=each($this->params)){
      //  echo "$tkey = $tvalue;</br>";
      //}
      $this->result = $this->client->__soapCall($this->action,array('parameters'=>$this->params), array('location'=>$this->url, 'soapaction'=>$this->action));
      //If Debugging is enabled, output the SOAP Request and Response.
      if ($this->debug){
        echo "Request :<br>", htmlspecialchars($this->client->__getLastRequest()), "<br>";
        echo "Response :<br>", htmlspecialchars($this->client->__getLastResponse()), "<br>";  
      }//End If
    }catch(Exception $ex){
      echo "SOAP Call Failed:</br>";
      echo $ex."</br>";
      echo "Checking for futher SOAP Fault information...</br>";
      if (is_soap_fault($this->result)){
        $object_vars = get_object_vars($this->result);
        while(list($key, $value)=each($object_vars)){
          echo "$key = $value;</br>";
        }//End While
      }else{
        echo "No further information Available!</br>";
      }//End If Else
      die();
    }//End Try Catch
    $this->result = $this->obj2array($this->result);
    return $this->result;
  }//End Function call()
  
  
  private function obj2array($obj) {
    //Convert the Object to a multi Dimensional Array
    //This function is freely available from the PHP Manual
    //Code provided by: stefan at datax dot biz
    //http://au.php.net/manual/en/soapclient.soapcall.php
    $out = array();
    foreach ($obj as $key => $val) {
      switch(true) {
          case is_object($val):
           $out[$key] = $this->obj2array($val);
          break;
        case is_array($val):
           $out[$key] = $this->obj2array($val);
           break;
        default:
          $out[$key] = $val;
      }//End Switch
    }//End foreach
  return $out;
  }//End function obj2array($obj)
}
?>