<?php
/**
* web.php - Core component of the A&D Mercury Website.
* This file handles template assignment and calls to
* outside classes for additional functions on a page
* specific basis.
*
* Author: Peter Lowrey
* Copyright: 2005
**/
$start = microtime(true);
header("Content-type: text/html; charset=iso-8859-1");
session_start();
//Include the globally required files (The template Engine)
include ("includes/template.inc");

//Exception Defenitions
class InvalidConfigException extends Exception{}
class FileNotFoundException extends Exception{}
class InvalidReturnDataException extends Exception{}

//Parse Configuration files
$site_ini = parse_ini_file("config/site.ini", TRUE);
$temp_ini = parse_ini_file("config/templates.ini", TRUE);

//Process Site Configuration information
reset($site_ini);
$site_config="";
try{
  if (key($site_ini) == 'site'){
    $site_config = current($site_ini);
  }else{
    throw new InvalidConfigException('Cannot locate required config information');
  }
}catch(InvalidConfigException $ex){
  //Create an Instance of the Error handler
  include ("includes/error.php");
  $error = new error($site_ini, $ex);
  $error->catch_error();
}
//Create an instance of the template engine
$t = new template();
//Set the Debug level of the Template Engine
$t ->debug=0;
//Assign the template root path
$t ->set_root($site_config["template_path"]);

/**
* Retreive the Get variable if it exists. If not
* assume the index page is required. Also check for
* empty strings and null's
**/
$page = isset($_GET["p"]) ? $_GET["p"]:"1001";
$page = $page == 'null' ? "1001":$page;
$page = $page =='' ? "1001":$page;

/**
* Extract Common information from the template.ini file and
* assign it to the common_config array.
**/
$common_config = "";
reset($temp_ini);
while (current($temp_ini)){
  if ("common" == key($temp_ini)){
    $common_config = current($temp_ini);
    break;
  }//End If
  next($temp_ini);
}//End While

/**
* Loop through the template.ini array looking for a match on the
* page number stored in $page. Match found assign it to $temp_config
**/
$temp_config = "";
$trange = $temp_ini['range'];
$trange_array = explode(',',$trange[1]);
$tranges = array(0 => $trange_array[0],
                  1 => $trange_array[1],
                  2 => $trange_array[2]);
for($m = 2;$m<=sizeof($trange);$m++){
  $trange_array = explode(',',$trange[$m]);
  array_push($tranges, $trange_array[0], $trange_array[1],$trange_array[2]);
}//End For
reset($trange_array);
reset($temp_ini);
reset($tranges);
$pcat = 1;
while (current($temp_ini)){
  for ($l = 0;$l<sizeof($tranges);$l++){
    if ($page >= $tranges[$l] && $page <= $tranges[$l+1]){
      $pcat = $tranges[$l+2];
      try{
        if (file_exists('templates/products/'.$pcat.'/product_template.html')){
          $temp_config = array('content' => 'products/'.$pcat.'/product_template.html');
        }elseif (file_exists("templates/products/product_template.html")){
          $temp_config = array('content' => 'products/product_template.html');
        }else{
          throw new Exception('Missing Display Product Template file');
        }//End If-Else-If
      }catch (Exception $ex){
        //Create an Instance of the Error handler
        include ("includes/error.php");
        $error = new error($site_ini, $ex);
        $error->catch_error();
      }
      break;
    }//End If
    $l++;
    $l++;
  }//End For
  if ($page == key($temp_ini) || isset($temp_config['content'])){
    if (!isset($temp_config['content'])){
      $temp_config = current($temp_ini);
    }//End If
    break;
  }//End If
  next($temp_ini);

}//End While
reset($common_config);

//Assign the common files to the template engine
while (current($common_config)){
  $t -> set_file(key($common_config),current($common_config));
  next($common_config);
}//End While

//Assign the page specific files to the template engine
if(empty($temp_config)){
  $ex = new Exception('Missing Template Config Data');
  //Create an Instance of the Error handler
  include ("includes/error.php");
  $error = new error($site_ini, $ex);
  $error->catch_error();
}
reset($temp_config);
while (current($temp_config)){
  //Check that the file exists before attempting to assign the file
  try{
    if(file_exists($site_config["template_path"].current($temp_config))){
      $t -> set_file(key($temp_config),current($temp_config));
      next($temp_config);
    }else{
      throw new FileNotFoundException('Template File Not Found: '.$site_config["template_path"].current($temp_config));
    }
  }catch(FileNotFoundException $ex){
    //Create an Instance of the Error handler
    include ("includes/error.php");
    $error = new error($site_ini, $ex);
    $error->catch_error();
  }
}

/**
* Check if there is an external processing class required for this page
* Search for matching page information when found assign page
* config information to a variable for easy access.
* If multiple Files require execution to achive a desired result a itermediary
* file should be used to call the necessary files and the collate and return the
* results as a boolean, array or tempalte oject.
**/
$range = $site_ini['range'];
$range_array = explode(',',$range[1]);
$ranges = array(0 => $range_array[0],
                1 => $range_array[1],
                2 => $range_array[2]);
for($k = 2;$k<=sizeof($range);$k++){
  $range_array = explode(',',$range[$k]);
  array_push($ranges, $range_array[0], $range_array[1], $range_array[2]);
}
reset($site_ini);
reset($range_array);
reset($ranges);
while (current($site_ini)){
  for ($l = 0;$l<sizeof($ranges);$l++){
    if ($page >= $ranges[$l] && $page <= $ranges[$l+1]){
      $pcat = $ranges[$l+2];
      $page_config = array('file' => 'disp_product.php',
                            'class' => 'disp_product',
                            'pcat' => $pcat);
      break;
    }//End If
    $l++;
    $l++;
  }//End For
  if ($page == key($site_ini) || isset($page_config['file'])){
    if (!isset($page_config['file'])){
      $page_config = current($site_ini);
    }
    if (isset($page_config["file"])){
      /**
      *Include the required file using the information gained above, then
      *instantiate the class
      **/
      try{
        if (file_exists($page_config['file'])){
          include($page_config['file']);
          $proc = new $page_config['class']($t, $site_config, $page, $page_config['pcat']);
        }else{
          throw new FileNotFoundException('Required Include class not found.');
        }
      }catch(FileNotFoundException $ex){
        //Create an Instance of the Error handler
        include ("includes/error.php");
        $error = new error($site_ini, $ex);
        $error->catch_error();
      }
      //Execute the start command and accept the returned data
      try{
        $result = $proc -> start();
      }catch(Exception $ex){
        //Create an Instance of the Error handler
        include ("includes/error.php");
        $error = new error($site_ini, $ex);
        $error->catch_error();
      }
      //Test the returned data, if it's an array process the data
      //If it is an Object, assign it to the template varible $t (It is template data)
        $type = gettype($result);
        switch ($type):
          case "boolean":
              try{
                if(!$result){
                  throw new InvalidReturnDataException('Additional Data Processing Failed');
                }
              }catch(InvalidReturnDataException $ex){
                include ("includes/error.php");
                $error = new error($site_ini, $ex);
                $error->catch_error();
              }//End Try-catch
              break;
          case "array":
              reset($result);
              //Assign the Result data into the page variables
              while (current($result)){
                $t -> set_var(key($result),current($result));
              next($result);
              }//End While
              break;
          case "object":
              $t = $result;
              break;
          default:
              $ex = new InvalidReturnDataException('Invalid data type returned from additional processing system.');
              include ("includes/error.php");
              $error = new error($site_ini, $ex);
              $error->catch_error();
          endswitch;
    }//End If
    break;
  }//End If
next($site_ini);
}//End While

//Reset the position of the common_config array
reset($common_config);

//Assign Global Path  Variables
$t -> set_var(array('img' => $site_ini['paths']['images'],
                    'prod_img_path' => $site_ini['paths']['prod_img_path'],
                    'url' => $site_ini['paths']['url']));

//Select the random Banner to be displayed and assign the variables
include ('brandom.php');
$brandom = new brandom($t, $site_ini, $page);
$t = $brandom -> start();

//Parse the common template peices according to the variable name
while (list($key, $value) = each($common_config)){
  $t ->parse($key,$key);
}//End While

//Reset the position of the temp_config array
reset($temp_config);

//Parse the template peices according to the variable name
while (list($key, $value) = each($temp_config)){
  $t ->parse($key,$key);
}//End While
$end = microtime(true);
$total = round($end - $start, 2);
$t -> set_var('gtime', $total);
//echo "Page generation Time: $total seconds<br>";
//Final Parse and output of the page.
$t ->parse('output', 'master');
$t ->p('output');
?>
