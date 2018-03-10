<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2007
 */

/** Hack Detection Script - Detect and reject requests from user-agent containing
* the string "libwww-perl" or and empty string and reject the connection.
**/

$user_agent = isset($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT']:'';
$needle = array(0 => 'libwww-perl',
                1 =>'Wget',
                2 =>'DataCha0s/2.0');
$needle_size = sizeof($needle);
for($i=0;$i<$needle_size;$i++){
/*  echo "Anti-Hack Test:</br>";
  echo "Needle: ".$needle[$i]."</br>";
  echo "User Agent: $user_agent</br>";*/
  $search_result = stristr($user_agent,$needle[$i]);
//  echo "Test Result: $search_result</br>";
  if ($search_result || empty($user_agent)) {
    //Hacking indicator found Reject connection.
    header("HTTP/1.0 403 Forbidden");
    //Format a String for logging the hack attempt
    //Format: Date time file line message
    $date_time = date("r");
    $mesg = $date_time.",".$user_agent.",".$_SERVER['REMOTE_ADDR'].",".$_SERVER['REQUEST_URI']."\r\n";
    error_log($mesg, 3, 'c:/Inetpub/wwwroot/web/web/hack.log');
    Die('Hacking attempt detected, Connection Terminated.');
  }//End If
}//End For

?>