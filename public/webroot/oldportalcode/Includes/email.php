<?php

class email{

var $server;
var $type;

  function __construct($server, $type){
    $this->server = $server;
    $this->type = $type;
  }

  function send($to, $replyto, $subject, $message){
    switch($this->type){
      case 'html':
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $headers .= "To: $to\r\n";
        $headers .= "From: webmaster@andmercury.com.au\r\n";
        $headers .= "Reply-to: $replyto\r\n";
        if(mail($to, $subject, $message, $headers)){
          return true;
        }else{
          //$mail-error = error_get_last();
          //Redirect to a static error page on Mailing Failue.
          //The standard error handler cannot by used as the configuration information
          //required is not accssessible from this location and requires widespread changes
          //to make it available. This will then be left to the next version of the platform.
/*          $last_error = error_get_last();
          while(list($key, $value)=each($last_error)){
            echo "$key = $value</br>";
          }
          die("Email Failed!!!");*/
          header("Location: http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?p=1010");
          return false;
          die("Email Failed!!!");
        }
      break;
      case 'text':
        $headers .= "To: $to\r\n";
        $headers .= "From: webmaster@andmercury.com.au\r\n";
        $headers .= "Reply-to: $replyto\r\n";
        $headers .= "Cc: $cc\r\n";
        $headers .= "Bcc: $bcc\r\n";
        if (mail($to, $subject, $message, $headers)){
          return true;
        }else{
          return false;
          die("Email Failed!!!");
        }
      break;
      default:
        //perform a text email
        $headers .= "To: $to\r\n";
        $headers .= "From: webmaster@andmercury.com.au\r\n";
        $headers .= "Reply-to: ".$replyto."\r\n";        
        $headers .= "Cc: $cc\r\n";
        $headers .= "Bcc: $bcc\r\n";
        if(mail($to, $subject, $message, $headers)){
          return true;
        }else{
          return false;
          die("Email Failed!!!");
        }
      break;
    }//End Switch
  }//End Function
}//End Class
?>
