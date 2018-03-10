<?php
error_reporting(1);
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
  var $config;
  var $basetime;


  public function __construct($call_type,$params){
    //Read Config type and load configuration for the call_type
    $this->basetime = microtime(true);
    $config = parse_ini_file('vantage_soap.ini',true);

	$this->params = $params;
    $this->username = $config['config']['username'];
    $this->password = $config['config']['password'];
    $this->url = $config[$call_type]['url'];
    //echo "URL: ".$this->url."</br>";
    $this->action = $config[$call_type]['action'];
    $this->result_type = $config[$call_type]['result_type'];
    $this->dataset_type = $config[$call_type]['dataset_type'];
    $this->data_section = $config[$call_type]['data_section'];
    $constructorTime = microtime(true);
    $outputTime = $constructorTime - $this->basetime;
    if ($this->debug){
    	echo "End of Constructor - Total Script Time: $outputTime</br>";
    }
  }//End __constructor

  public function setup(){
  	if ($this->debug){
  		$setupStartTime = microtime(true);
    	$setupStartOutput = $setupStartTime - $this->basetime;
    	echo "Start of Setup Function - Total Script Time: $setupStartOutput</br>";
  	}
    //$this->client = new WSSoapClient($this->url,array('trace' => 1,"features" => SOAP_SINGLE_ELEMENT_ARRAYS));
	$this->client = new SoapClient($this->url,array('trace' => 1,"features" => SOAP_SINGLE_ELEMENT_ARRAYS));
	  echo '<pre>';
	 // var_dump($this->client->__getFunctions()); 
	  //var_dump($this->client->__getTypes()); 
	  echo '</pre>';
	if ($this->debug){
		$endClientSetup = microtime(true);
		$endClientOutput = $endClientSetup - $this->basetime;
		echo "End of Client Setup - Total Script Time: $endClientOutput</br>";
	}//End Debug If
	$auth="<wsse:Security SOAP-ENV:mustUnderstand='1' xmlns:wsse='http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd'>
    <wsse:UsernameToken>
    <wsse:Username>".$this->username."</wsse:Username>
    <wsse:Password>".$this->password."</wsse:Password>
    </wsse:UsernameToken>
    </wsse:Security>";
	
	$authvalues=new SoapVar($auth,XSD_ANYXML);
	
	$headers = new SoapHeader("http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd",
							  "wsse:Security SOAP-ENV:mustUnderstand='1' xmlns:wsse='http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd",
							  $authvalues,
							  true);
    

    
    $this->client -> __setSoapHeaders($headers);	
    //Assign the Username and Password to the SOAP Client for inclusion in the Security Headers
    //$this->client->__setUsernameToken($this->username,$this->password); 
	  print_r($this->client);
    if ($this->debug){
    	$setupEndTime = microtime(true);
    	$setupEndOutput = $setupEndTime - $this->basetime;
    	echo "End of Setup Function - Total Script Time: $setupEndOutput</br>";
    }//End Debug If
  }//End setup()
  
  public function call(){
	  
    //Attempt to Make the SOAP Call and check for Exceptions
    try{ 
		echo 'dvd';
		echo $this->debug;
    if ($this->debug){
    	echo "Trying Call...</br>";
    	$callStartTime = microtime(true);
    	$callStartOutput = $callStartTime - $this->basetime;
    	echo "Start of Call Function - Total Script Time: $callStartOutput</br>";
    }//End Debug If


      if ($this->debug){
       
      }//End If
    }catch(SoapFault $fault){
      if (is_soap_fault($this->result)){
        $object_vars = get_object_vars($this->result);
        if ($this->debug){
          echo "Catch Block</br>";
          while(list($key, $value)=each($object_vars)){
            echo "$key = $value;</br>";
          }//End While
        }//ENd If Debug
      }else{
        if ($this->debug){
          echo "No further information Available!</br>";
        }
      }//End If Else
        if ($this->debug){
          echo "Catch Block</br>";
          echo "Request :<br>", htmlspecialchars($this->client->__getLastRequest()), "<br>";
          echo "Response :<br>", htmlspecialchars($this->client->__getLastResponse()), "<br>";
          echo "------------------------------------------------</br>";
          echo "Request:".$this->client->__getLastRequest()."</br>";
      	  echo "Request:".$this->client->__getLastResponse()."";  
        }//End If
        return "";
      die();
    }//End Try Catch
    if ($this->debug){
    	$callEndTime = microtime(true);
    	$callEndOutput = $callEndTime - $this->basetime;
    	echo "End of Call Function - Total Script Time: $callEndOutput</br>";
    }//End Debug If
    $this->result = $this->obj2array($this->result);
	 
	 
    return $this->result;
  }//End Function call()
  
  
  private function obj2array($obj) {
    //Convert the Object to a multi Dimensional Array
    //This function is freely available from the PHP Manual
    //Code provided by: stefan at datax dot biz
    //http://au.php.net/manual/en/soapclient.soapcall.php
  	if ($this->debug){
  		$obj2arrayStartTime = microtime(true);
    	$obj2arrayStartOutput = $obj2arrayStartTime - $this->basetime;
    	echo "Start of obj2array Function - Total Script Time: $obj2arrayStartOutput</br>";
  	}//End Debug If
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
    if ($this->debug){
    	$obj2arrayEndTime = microtime(true);
    	$obj2arrayEndOutput = $obj2arrayEndTime - $this->basetime;
    	echo "End of obj2array Function - Total Script Time: $obj2arrayEndOutput</br>";
    }//End Debug If
  return $out;
  }//End function obj2array($obj)
}



$itemno = "03175710";
//ADME01
$CompanyID = "ADME01";
$para=array('CompanyID' => "$CompanyID",'partNum' => "$itemno");
$vantageSoap = new VantageSoap('GetByID',$para);


$chk = $vantageSoap -> setup();  

    
    //Make SOAP Call
    $result = $vantageSoap -> call();



print_r($result);


if (empty($result)){
      $this->t->set_var(array('evisible' =>'visible',
                              'visible' => 'hidden'));
    }else{
      $data_part = $result['GetByIDResult']['PartDataSet']['Part'];
      $data_partwhse = $result['GetByIDResult']['PartDataSet']['PartWhse'];
      if ($this->debug){
        echo "Data_Part: $data_part;;;</br>";
        echo "Data_Partwhse: $data_partwhse;;;</br>";
        while(list($key,$value)=each($data_part)){
          echo "<b>$key:</b> $value</br>";
          //while(list($key2,$value2)=each($value)){
            //echo "&nbsp;&nbsp;<b>$key2</b> = $value2;</br>";
          //}
        }
        while(list($key,$value)=each($data_partwhse)){
          echo "<b>$key:</b> $value</br>";
          while(list($key2,$value2)=each($value)){
            echo "&nbsp;&nbsp;<b>$key2</b> = $value2;</br>";
          }//End While
        }//End While
      }//End If Debug
      $data_size = sizeof($data_partwhse);
      $qtyOnHand = 0;
      $allocatedQty = 0;
      for ($i=0;$i<$data_size;$i++){
        //Exclude Showroom Warehouses from results
        if (!strpos($data_partwhse[$i]['WarehouseCode'], 'SR')){
          $qtyOnHand += $data_partwhse[$i]['OnHandQty'];
          $allocatedQty += $data_partwhse[$i]['AllocQty'];
        }//End If
      }//End For
      $partNum = $data_part[0]['PartNum'];
      $partDesc = $data_part[0]['PartDescription'];
      //If the price is less than or equal to zero, substitue the string "Call" for the value
      $nprice = $data_part[0]['UnitPrice']<= 0 ? "Call":$data_part[0]['UnitPrice'];
      $available = $qtyOnHand - $allocatedQty;
      $incoming = 'N/A';
      
      //Assign output
      $this->t->set_var(array('citemno' => $partNum,
                              'cdescript' => $partDesc,
                              'nprice' => $nprice,
//                              'incoming' => $incoming,
                              'available' => round($available),
                              'visible' => 'visible',
                              'evisible' => 'hidden'));
    }//End If-Else 

//print_r($result);




die();





/*$url="http://dealers.andmercury.com.au/vs/PartService.asmx.xml";
$client = new SoapClient($url,array('trace' => 1,"features" => SOAP_SINGLE_ELEMENT_ARRAYS));


$auth="<wsse:Security SOAP-ENV:mustUnderstand='1' xmlns:wsse='http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd'>
    <wsse:UsernameToken>
    <wsse:Username>manager</wsse:Username>
    <wsse:Password>manager</wsse:Password>
    </wsse:UsernameToken>
    </wsse:Security>";
	
	$authvalues=new SoapVar($auth,XSD_ANYXML);
	
	$headers = new SoapHeader("http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd",
							  "wsse:Security SOAP-ENV:mustUnderstand='1' xmlns:wsse='http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd",
							  $authvalues,
							  true);

	$client -> __setSoapHeaders($headers);	

$action = 'GetByID';
$params="CompanyID,partNum";

	$result = $this->client->__soapCall($action,array($params));//, array('location'=>$this->url, 'soapaction'=>$this->action));

print_r($result);
*/
?>