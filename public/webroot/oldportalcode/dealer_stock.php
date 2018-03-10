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
    include ('Includes/dealer_db.php');
    $this->dealer_db = new dealer_db('andmerc');
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
    //Set up Queries
    $query = "SELECT citemno, cdescript, nprice
                        FROM andmerc.dbo.icitem
                        GROUP BY citemno, cdescript, nprice
                        HAVING (citemno = '$itemno')";
    $quantity_query = "SELECT sum(iciwhs.ninprocess + iciwhs.nonorder) AS Incoming, sum(iciwhs.nonhand - iciwhs.nbook) AS Available
                 FROM andmerc.dbo.iciwhs inner join andmerc.dbo.icitem on iciwhs.citemno = icitem.citemno
                 WHERE (iciwhs.citemno =  '$itemno') AND
                       ((icitem.cclass LIKE 'F%' AND
                       (iciwhs.cwarehouse = 'SA' OR
                        iciwhs.cwarehouse = 'VIC' OR
                        iciwhs.cwarehouse = 'NSW')) OR
                        iciwhs.cwarehouse = 'SA-SPARES')";
                        
    //Perform queries
    $this->dealer_db->do_query($query);
    $result = $this->dealer_db->fetch_result_array();
    $result_data = $result[0];
    if (empty($result_data)){
      $this->t->set_var(array('evisible' =>'visible',
                              'visible' => 'hidden'));
    }else{
    $citemno = $result_data['citemno'];
    $cdescript = $result_data['cdescript'];
    $nprice = $result_data['nprice'];
    $this->dealer_db->do_query($quantity_query);
    $result1 = $this->dealer_db->fetch_result_array();
    $result_data1 = $result1[0];
    $incoming = $result_data1['Incoming'];
    $available = $result_data1['Available'];
    //Assign output
    $this->t->set_var(array('citemno' => $citemno,
                            'cdescript' => $cdescript,
                            'nprice' => round($nprice, 2),
                            'incoming' => round($incoming),
                            'available' => round($available),
                            'visible' => 'visible',
                            'evisible' => 'hidden'));
    }//End If-Else
  }//End function search
}//End Class
