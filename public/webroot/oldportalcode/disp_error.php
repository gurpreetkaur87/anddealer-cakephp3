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

session_start();
//Include the globally required files (The template Engine)
include ("includes/template.inc");
$site_ini = parse_ini_file("config/site.ini", TRUE);
//Create an instance of the template engine
$t = new template();
//Set the Debug level of the Template Engine
$t ->debug=0;
//Assign the template root path
if (!empty($_SESSION['error_code'])){
  //HTTP HEADER 403 Error Code Here!!
  if ($_SESSION['error_msg'] == "Missing Template Config Data"){
    header("HTTP/1.0 403 Forbidden");
  }else if($_SESSION['error_msg'] == "File Not Found"){
    header("HTTP/1.0 404 Not Found");
  }
    
  $t -> set_file(array('master' => 'templates/master.html',
                        'header' => 'templates/header.html',
                        'menu1' => 'templates/dealers/menu1.html',
                        'menu2' => 'templates/dealers/menu2.html',
                        'content' => 'templates/error.html'));
}else{
  //HTTP HEADER 403 Error Code Here!!
  if ($_SESSION['error_msg'] == "Missing Template Config Data"){
    header("HTTP/1.0 403 Forbidden");
  }else if($_SESSION['error_msg'] == "File Not Found"){
    header("HTTP/1.0 404 Not Found");
  }
  $t -> set_file(array('master' => 'templates/master.html',
                        'header' => 'templates/header.html',
                        'menu1' => 'templates/menu1.html',
                        'menu2' => 'templates/menu2.html',
                        'content' => 'templates/error.html'));
}
//Assign Global Path  Variables
$t -> set_var(array('img' => $site_ini['paths']['images'],
                    'prod_img_path' => $site_ini['paths']['prod_img_path']));

$t -> set_var(array('error_file' => $_SESSION['error_file'],
                      'error_line' => $_SESSION['error_line'],
                      'error_msg' => $_SESSION['error_msg'],
                      'request_uri' => $_SESSION['request_uri'],
                      'user_ip' => $_SESSION['error_user_ip']));
$t ->parse('content','content');
$t ->parse('menu2','menu2');
$t ->parse('menu1','menu1');
$t ->parse('header','header');
$t ->parse('master','master');

//Final Parse and output of the page.
$t ->parse('output', 'master');
$t ->p('output');
?>
