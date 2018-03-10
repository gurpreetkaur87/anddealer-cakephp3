<?php

$item = filter_input(INPUT_POST, "itemno");

$result = stock::search($item);


var_dump($result);



exit;


class stock {

    private static $debug = false;

    public static function search($itemno) {
        
        
        //Set up SOAP Objects 
        include('vantageSoap.php');
        $params = array('CompanyID' => 'AND', 'partNum' => "$itemno");
        $vantageSoap = new vantageSoap('GetByID', $params);
        $vantageSoap->setup();

        //Make SOAP Call
        $result = $vantageSoap->call();
        
        if (empty($result)) {
            $ret[] = array('evisible' => 'visible',   'visible' => 'hidden');
        } else {
            $data_part = $result['GetByIDResult']['PartDataSet']['Part'];
            $data_partwhse = $result['GetByIDResult']['PartDataSet']['PartWhse'];
            if (self::$debug) {
                echo "Data_Part: $data_part;;;</br>";
                echo "Data_Partwhse: $data_partwhse;;;</br>";
                while (list($key, $value) = each($data_part)) {
                    echo "<b>$key:</b> $value</br>";
                    //while(list($key2,$value2)=each($value)){
                    //echo "&nbsp;&nbsp;<b>$key2</b> = $value2;</br>";
                    //}
                }
                while (list($key, $value) = each($data_partwhse)) {
                    echo "<b>$key:</b> $value</br>";
                    while (list($key2, $value2) = each($value)) {
                        echo "&nbsp;&nbsp;<b>$key2</b> = $value2;</br>";
                    }//End While
                }//End While
            }//End If Debug
            $data_size = sizeof($data_partwhse);
            $qtyOnHand = 0;
            $allocatedQty = 0;
            for ($i = 0; $i < $data_size; $i++) {
                //Exclude Showroom Warehouses from results
                if (!strpos($data_partwhse[$i]['WarehouseCode'], 'SR')) {
                    $qtyOnHand += $data_partwhse[$i]['OnHandQty'];
                    $allocatedQty += $data_partwhse[$i]['AllocQty'];
                }//End If
            }//End For
            $partNum = $data_part[0]['PartNum'];
            $partDesc = $data_part[0]['PartDescription'];
            //If the price is less than or equal to zero, substitue the string "Call" for the value
            $nprice = $data_part[0]['UnitPrice'] <= 0 ? "Call" : $data_part[0]['UnitPrice'];
            $available = $qtyOnHand - $allocatedQty;
            $incoming = 'N/A';

            //Assign output
            $ret[] = array('citemno' => $partNum,
                'cdescript' => $partDesc,
                'nprice' => $nprice,
//              'incoming' => $incoming,
                'available' => round($available),
                'visible' => 'visible',
                'evisible' => 'hidden');
        }//End If-Else
        
        return $ret;
    }


}

//End Class
?>
