<?php

class prices{

var $t;
var $site_config;
var $page;
var $pcat;
var $pc;
var $Prices;

  function __construct($t, $site_config, $page, $pcat){
    $this->t = $t;
    $this->site_config = $site_config;
    $this->page = $page;
    $this->pcat = $_GET['pcat'];
    $this->pc = $_GET['pc'];
  }//End function __construct

  function start(){
  	$this->get_prices();
    $this->from_file();
  	return $this->t;
  }//End function Start

  function get_prices(){
  	$this->read_prices();
  	//$this->t->set_var(array('pcat' => $this->pcat,
  							//'pc' => $this->page));
  }
  
  
  function read_prices(){
  	$row = 1;
	if (($handle = fopen("config/prices.csv", "r")) !== FALSE) {
	//Read the first Line Early to Remove Heading information
	$data = fgetcsv($handle, 60, ",");
	while (($data = fgetcsv($handle, 60, ",")) !== FALSE) {
		$this->Prices[$data[0]] = array('Page' => $data[1],
    								'Price' => $data[2],
        							'stamping' => $data[3],
        							'certification' => $data[4],
        							'Freight' => $data[5]);
    }//End While
    
    /*
    Echo "Listing Prices Array</br>";
    while (list($key,$value)=each($this->Prices)){
    	echo "$key:</br>";
    	while (list($key2,$value2)=each($value)){
    		echo "&nbsp;&nbsp;&nbsp;$key2 = $value2;</br>";
    	}
    }
    */
    fclose($handle);
	}//End If
  
  }//End Function
  
  function from_file(){
    try{
      $file = 'config/products/'.$this->pcat.'/'.$this->pc.'.ini';
      //echo "File: $file;</br>";
      if (file_exists($file)){
        $data = parse_ini_file($file, true);
				if (empty($data)){
					echo "Throwing Exception 1</br>";
					throw new Exception("Product Information file contains no data");
				}//End If
      }else{
      	//echo "Throwing Exception 2</br>";
      	throw new Exception("Product Information file not found");
      }//End If-Else
    }catch(Exception $ex){
      throw $ex;
    }//End Try Catch
	//	echo "After try catch Block</br>";
    $capacities = isset($data['capacities']) ? $data['capacities']:null;
    $options = isset($data['options']) ? $data['options']:null;
    $misc = isset($data['misc']) ? $data['misc']:null;    
    $this->t->set_var('product', $misc['product_title']);
    
    /*
      Echo "Listing Capacities Array</br>";
    while (list($key,$value)=each($capacities)){
    	echo "$key: $value</br>";
    	//while (list($key2,$value2)=each($value)){
    	//	echo "&nbsp;&nbsp;&nbsp;$key2 = $value2;</br>";
    	//}
    }

      Echo "Listing Options Array</br>";
    while (list($key,$value)=each($options)){
    	echo "$key: $value</br>";
    	//while (list($key2,$value2)=each($value)){
    	//	echo "&nbsp;&nbsp;&nbsp;$key2 = $value2;</br>";
    	//}
    } 
    */   
    //$Product_price['test'] = 'Prices';
    $cap_size = sizeof($capacities);
    //echo "CapSIZE: $cap_size</br>";
    for ($i=1;$i<=$cap_size;$i++){
    	//echo "Capacity PCode: ".$capacities[$i]."</br>";
    	$caprow = explode(',',$capacities[$i]);
    	//echo "CAPROW: $caprow - ";
    	//print_r($caprow);
    	if (array_key_exists($caprow[0], $this->Prices)){
			//echo "Adding: ".$caprow[0]." to the Array</br>";
			$temp_array = $this->Prices[$caprow[0]];
			$temp_array['model'] = $caprow[1];
			$temp_array['capacity'] = $caprow[2];
    		//$Product_price[$caprow[0]] = $this->Prices[$caprow[0]];
    		$Capacity_price[$caprow[0]] = $temp_array;
    		//array_merge($Product_price[$caprow[0]], $caprow);
       	}// End If
    }//End For
    
    $op_size = sizeof($options);
    //echo "OpSIZE: $op_size</br>";
    for ($i=1;$i<=$op_size;$i++){
    	//echo "Option PCode: ".$options[$i]."</br>";
    	$Oprow = explode(',',$options[$i]);
    	//echo "OPROW: $Oprow - ";
    	//print_r($Oprow);
    	if (array_key_exists($Oprow[0], $this->Prices)){
			//echo "Adding: ".$Oprow[0]." to the Array</br>";
			$temp_array = $this->Prices[$Oprow[0]];
			$temp_array['model'] = $Oprow[1];
			$temp_array['description'] = $Oprow[2];
			//$Product_price[$Oprow[0]] = $this->Prices[$Oprow[0]];
    		$Option_price[$Oprow[0]] = $temp_array;
			//array_push($Product_price[$Oprow[0]], $Oprow);
       	}// End If
    }//End For
    
    /*
  	Echo "Listing Capacity Prices Array</br>";
    while (list($key,$value)=each($Capacity_price)){
    	echo "$key:</br>";
    	while (list($key2,$value2)=each($value)){
    		echo "&nbsp;&nbsp;&nbsp;$key2 = $value2;</br>";
    	}
    }
    Echo "Listing Option Prices Array</br>";
    while (list($key,$value)=each($Option_price)){
    	echo "$key:</br>";
    	while (list($key2,$value2)=each($value)){
    		echo "&nbsp;&nbsp;&nbsp;$key2 = $value2;</br>";
    	}
    }
    */
    /*
     * Capacities Block
     */
	try{
        if (file_exists("templates/products/$this->pcat/capacity_price.html")){
          $this->t -> set_file(array("capacity_item" => "products/$this->pcat/capacity_price.html"));
        }elseif (file_exists("templates/products/capacity_price.html")){
          $this->t -> set_file(array("capacity_item" => "products/capacity_price.html"));
        }else{
          throw new Exception('Missing Capacities Template file');
        }//End If-Else-If
    	}catch (Exception $ex){
      	throw $ex;
    	}//End Try-Catch
      $this->t -> set_block("capacity_item", 'capacity_list', 'capacities');
      if (empty($Capacity_price)){
      	$this->t->set_var('capacity', 'Pricing for this Series is not currently available');
      	$this->t -> parse('capacities', 'capacity_list');
      }else{
        reset($Capacity_price);
        while (current($Capacity_price)){  
        	$part_price = $Capacity_price[key($Capacity_price)]['Price'] * 1.10;
          $this->t -> set_var(array('model' => $Capacity_price[key($Capacity_price)]['model'],
                                    'capacity' => $Capacity_price[key($Capacity_price)]['capacity'],
          							'partnum' => key($Capacity_price),
          							'part_price' => number_format($part_price,2)));
          $this->t -> parse('capacities', 'capacity_list', true);
          next($Capacity_price);
        }//End While
      }//End if Else
      /*
       *  Stamping Block - Removed to Simplify Pricing System
       */
      /*
      try{
        if (file_exists("templates/products/stamping.html")){
          $this->t -> set_file(array("stamping_item" => "products/stamping.html"));
        }else{
          throw new Exception('Missing stamping Template file');
        }//End If-Else-If
      }catch (Exception $ex){
      	throw $ex;
      }//End Try-Catch
      $this->t -> set_block("stamping_item", 'stamping_list', 'stampings');
      reset($Capacity_price);
      while (current($Capacity_price)){  
      	$part_price = $Capacity_price[key($Capacity_price)]['stamping'] * 1.10;
        $this->t -> set_var(array('model' => $Capacity_price[key($Capacity_price)]['model'],
                                  //'capacity' => $Capacity_price[key($Capacity_price)]['capacity'],
        							//'partnum' => key($Capacity_price),
        							'stamping' => number_format($part_price,2)));
        $this->t -> parse('stampings', 'stamping_list', true);
        next($Capacity_price);
      }//End While
      */
      /*
       * Options Block
       */
      
      try{
        if (file_exists("templates/products/$this->pcat/option_price.html")){
          $this->t -> set_file(array("option_item" => "products/$this->pcat/option_price.html"));
        }elseif (file_exists("templates/products/option_price.html")){
          $this->t -> set_file(array("option_item" => "products/option_price.html"));
        }else{
          throw new Exception('Missing Options Template file');
        }//End If-Else-If
    	}catch (Exception $ex){
      		throw $ex;
    	}//End Try-Catch
      if (empty($Option_price)){
      	/*$this->t->set_var('description', 'Pricing for these options is not currently available');*/
      	$this->t -> set_file(array("option_item" => "products/option_price_na.html"));
      	$this->t -> set_block("option_item", 'option_list', 'options');
      	$this->t->set_var('description', 'There are currently no options for this product or pricing is not yet available online.');
      	$this->t -> parse('options', 'option_list');
		
      }else{
      	$this->t -> set_block("option_item", 'option_list', 'options');
        reset($Option_price);
        while (current($Option_price)){
        	$option_price = $Option_price[key($Option_price)]['Price'] * 1.10;
          $this->t -> set_var(array('omodel' => $Option_price[key($Option_price)]['model'],
                                    'odescription' => $Option_price[key($Option_price)]['description'],
          							'opartnum' => key($Option_price),
          							'opart_price' => number_format($option_price,2)));
          $this->t -> parse('options', 'option_list', true);
          next($Option_price);
        }//End While
      }
/*      reset($Capacity_price);
      //echo "Stamping: ".$Capacity_price[key($Capacity_price)]['stamping'].";;</br>";
      $this->t->set_var(array(//'stamping' => $Capacity_price[key($Capacity_price)]['stamping'],
      							'freight'=> $Capacity_price[key($Capacity_price)]['Freight']));*/
}//End Function
  
}//End Class  
?>