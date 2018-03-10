<?php
  include ('Includes/dealer_db.php');
  $dealer_db = new dealer_db('andweb');
  $query="SELECT Model.ccode, Model.cid
          FROM ModelDrawing INNER JOIN Model 
          ON ModelDrawing.cModelId = Model.cId
          GROUP BY Model.ccode, Model.cid";
  $dealer_db->do_query($query);
  $result = $dealer_db->fetch_result_array();
  while (list($key, $value) = each($result)){
    echo "<a href='dealers/ad.dll/addl01?m=056&id006=".$value[1]."' target='_parent'>".$value[0]."</a><br/>";
  }//End While
?>