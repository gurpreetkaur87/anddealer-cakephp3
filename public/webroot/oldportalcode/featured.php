<?php
/*
* This is the Feature Rotation Script. It is run by a scheduled task at a predetermine time based on how
* often rotation should occur.  It reads the featured.ini file, collects an array of product information
* Then randomly selects the required number of products and writes that information out to another config file.
*
*/

//Parse Configuration files
$site_config = parse_ini_file("config/site.ini", TRUE);
$site_ini = $site_config['site'];
$featured_ini = parse_ini_file($site_ini['root_path']."config/featured.ini", TRUE);

//Read the product information files into an array.
for($i=0;$i<sizeof($featured_ini['directories']);$i++){
  $data = parse_ini_file($featured_ini['general']['directory_root'].$featured_ini['directories'][$i].$featured_ini['general']['filename'], true);  
  $product_info[$i] = $data;
/* Debuggung code Only.....
  while(list($key, $value)= each($product_info)){
    while(list($key1, $value1) = each($value)){
      while(list($key2, $value2) = each($value1)){
        echo "$key -> $key1 -> $key2 -> $value2\n";
      }
    }
  }*/
}//End For

  //Pick a 2D Random Product Number. ie. Category number (1-7) then actual product number
  //Insert a check here to prevent the same product being select more than once in a batch.
  $stored = '';
  $i=0;
  while($i < $featured_ini['general']['num_products']){
    //Random Selection Code
    $cat_num  = mt_rand(0,8);
    $prod_size = sizeof($product_info[$cat_num]);
    echo "Prod_size: $prod_size - Category: $cat_num\n";
    $prod_num = mt_rand(0,$prod_size);
    echo "Prod_num: $prod_num\n";
    $cat_selected = $product_info[$cat_num];
    reset($cat_selected);
    //$selected = key($cat_selected);
    $selected = array_rand($cat_selected);
    echo "Selected: $selected\n";
  //Dupe Check
    if ($i>0){
      $matched = false;
      for($j=0;$j<$i;$j++){
        if($selected == $stored[$j]){
          $matched = true;
          break;
        }//End If
      }//End For
      if(!$matched){
        $stored[$i] = $selected;
        $i++;
      }//End If
    }else{
      $stored[$i] = $selected;
      $i++;
    }//End If-else
  }//End While
  $products = $stored;  

  /** START Original Code 
  $cat_num  = mt_rand(0,7);
  $prod_size = sizeof($product_info[$cat_num]);
  $prod_num = mt_rand(0,$prod_size);
  $cat_selected = $product_info[$cat_num];
  reset($cat_selected);
  $selected = key($cat_selected);
  for($i=0;$i<$prod_num;$i++){
    $selected = key($cat_selected);
    next($cat_selected);
  }//End For
  $products[$k] = $selected;
 END Original Code**/

//Output the selected numbers to the config file for use with index.php
$data ='';
for($j=0;$j<sizeof($products);$j++){
  $data = $data."$j=".$products[$j]."\n";
}//End For
try{
  $file = @fopen($site_ini['root_path'].$featured_ini['general']['output_file'], 'w');
  if(!$file){
    throw new Exception('Failed to Open output File!');
  }//End If
  fwrite($file, $data);
  fclose($file);
}catch(Exception $ex){
  $to = $site_ini['webmaster'];
  $from = $site_ini['webmaster'];
  $headers  = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
  $headers .= "To: $to\r\n";
  $headers .= "From: $from\r\n";
  $subject = 'Random Feature config file generation Failed!';
  $message = date('H:i:s d-m-o', time())."\n".$ex;
  mail($to, $subject, $message, $headers);
}//End Try-Catch
?>