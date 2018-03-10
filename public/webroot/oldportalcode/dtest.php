<?php
  echo "This is the session tester<br>";
  include ('includes/dealer_sessions.php');
  try{
    $dsession = new dealer_sessions();
    $session = $dsession->start();
  }catch(Exception $ex){
    Die ("No Session: $ex<br>");
  }//End Try-Catch
  echo "This concludes the session tester<br>";
?>
