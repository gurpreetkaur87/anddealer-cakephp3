<?php

class dealer_stock{

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
//    $this->dealer_db = new dealer_db('andmerc');
  }//End Constructor
  
  function start(){
    //Check Session Validity
//    $this->dsess->start();
    //Begin Processing
    if (isset($_POST['itemno'])){
      $this->search($_POST['itemno']);
    }else{
      $this->display();
    }
    return $this->t;
  }

  function display(){
    //Used only if additional processing is required for the initial search page...
    $this->t->set_var(array('visible' => 'hidden',
                            'evisible' => 'hidden'));
  }//End function display

  function search($itemno){
    //Set up SOAP Objects 
    include('includes\vantageSoap.php');
    $params=array('CompanyID' => 'AND', 'cPartNum' => $itemno, 'cPlant' => 'MfgSys');    
    $vantageSoap = new vantageSoap('GetPartOnHandWhse',$params);
    $vantageSoap -> setup();                        
    
    
    //Make SOAP Call
    $result = $vantageSoap -> call();
    if (empty($result)){
      $this->t->set_var(array('evisible' =>'visible',
                              'visible' => 'hidden'));
    }else{
      $data = $result['GetPartOnHandWhseResult']['PartOnHandWhseDataSet']['PartOnHandWhse'];
      $data_size = sizeof($data);
      //echo "</br>Warehouse - Part - Qty - Allocated</br>";
      $qtyOnHand = 0;
      $allocatedQty = 0;
      for ($i=0;$i<$data_size;$i++){
        //Remove Showroom Warehouses from results
        if (!strpos($data[$i]['WarehouseCode'], 'SR')){
          //echo $data[$i]['WarehouseDesc']." - ".$data[$i]['PartNum']." - ".$data[$i]['QuantityOnHand']." - ".$data[$i]['AllocQty'].";</br>";
          $qtyOnHand += $data[$i]['QuantityOnHand'];
          $allocatedQty += $data[$i]['AllocQty'];
        }//End If
      }//End For
      $partNum = $data[0]['PartNum'];
      $partDesc = 'N/A';
      $nprice = 'N/A';
      $available = $qtyOnHand - $allocatedQty;
      $incoming = 'N/A';
      
      //Assign output
      $this->t->set_var(array('citemno' => $partNum,
                              'cdescript' => $partDesc,
//                              'nprice' => round($nprice, 2),
//                              'incoming' => round($incoming),
                              'nprice' => $nprice,
                              'incoming' => $incoming,
                              'available' => round($available),
                              'visible' => 'visible',
                              'evisible' => 'hidden'));
    }//End If-Else    
  }//End function search
}//End Class
?>