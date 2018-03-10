<?php
  include ('Includes/dealer_db.php');
  $dealer_db = new dealer_db('andweb');
  $query="SELECT COUNT(DISTINCT cModelId) AS num_Models
          FROM ModelDrawing";
  $query2="SELECT COUNT(DISTINCT cId) AS num_Models
           FROM Model";
  $dealer_db->do_query($query);
  $dresult = $dealer_db->fetch_result_array();
  $dealer_db->do_query($query2);
  $tresult = $dealer_db->fetch_result_array();
  $wdata = $dresult[0][0];
  $total = $tresult[0][0];
  $page = "<html>
            <head>
            </head>
            <body style='background-color: #F6F5D5;font-family:Verdana;font-size:1em;color: black;margin: 3px;'>
              <h3 style='margin-bottom:5px;font-size: 85%; text-align: center'>Database Stats</h3>    
              <table style='margin: 0; padding: 5px;font-size: 75%;' cellpadding=2 cellspacing=0>
                <tr>
                  <td>Listed Models:</td>
                  <td style='color: red;'>$total</td>
                </tr>
                <tr>
                  <td><a href='md_list.php' target='_blank'>Models w/ Data:</a></td>
                  <td style='color: green;'>$wdata</td>
                </tr>
              </table>
            </body>
            </html>";
  echo $page;
?>