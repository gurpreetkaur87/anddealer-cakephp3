<?php

class error{

var $site_config;
var $error_config;
var $error_msg;
var $error_file;
var $error_line;
var $error_trace;
var $error_code;
var $error_user_ip;
var $error_request_url;
var $template_config;

  function __construct($site_ini,$t){
    $this->site_config = $site_ini['site'];
    $this->error_config = $site_ini['error'];
    $this->t = $t;
  }
  
  function proc_error(){
    switch ($this->error_config['error_level']):
      case 0:
        Echo "No Reporting: Do Nothing<br>";
          break;
      case 1:
        //echo "Log Error: Write Error to the Error Log File<br>";
        $this->log_error();
//        die("A FATAL Error has occured. Please contact us to resolve this problem");
          break;
      case 2:
        //echo "Email Error: Send notification email<br>";
        $this->email_error();
//        die("A FATAL Error has occured. Please contact us to resolve this problem");
          break;
      case 3:
        //Display Notification: Display on screen notification
        $this->display_error();
          break;
      case 4:
        //echo "Log and Email: Log the error then send an email notification<br>";
        $this->log_error();
        $this->email_error();
//        die("A FATAL Error has occured. Please contact us to resolve this problem");
          break;
      case 5:
        //echo "Email and Display: Send an email notification and Display<br>";
        $this->email_error();
        $this->display_error();
//        die("A FATAL Error has occured. Please contact us to resolve this problem");
          break;
      case 6:
        //echo "Email and Display: Send an email notification and Display<br>";
        $this->log_error();
        $this->display_error();
//        die("A FATAL Error has occured. Please contact us to resolve this problem");
          break;
      case 7:
        //echo "Log Email and Display: Log the error, send an email notification and Display<br>";
        $this->log_error();
        $this->email_error();
        $this->display_error();
        //die("A FATAL Error has occured. Please contact us to resolve this problem");
          break;
      default:
        //Email and display the error
        $this->email_error();
        $this->display_error();
    endswitch;
  }

  function catch_error($ex){
	$this->error_file = $ex->getfile();
    $this->error_line = $ex->getline();
    $this->error_msg = $ex->getmessage();
    $this->error_trace = $ex->getTraceAsString();
    $this->error_code = $ex->getCode();
    $this->error_user_ip = isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];
    $this->error_user_agent = $_SERVER['HTTP_USER_AGENT'];
    $this->error_request_url = $_SERVER['REQUEST_URI'];
    $this->proc_error();
    return $this->template_config;
	}

  function email_error(){
//    echo "Emailing the Error<br>";
    $type = "html";
    $server = $this->site_config['smtp_server'];
    include ('email.php');
    $email = new email($server, $type);
    $to = $this->error_config['error_email'];
    $from = "webmaster@andmercury.com";
    $subject = "Website Error Report";
    $message = "<table><tr><th colspan='2'>Website Error Report</td></tr>
                      <tr><td>Error Message:</td><td>$this->error_msg</td></tr>
                      <tr><td>Error File:</td><td>$this->error_file</td></tr>
                      <tr><td>Error Line:</td><td>$this->error_line</td></tr>
                      <tr><td>Error Trace:</td><td>$this->error_trace</td></tr>
                      <tr><td>Requested URL:</td><td>".$this->error_request_url."</td></tr>
                      <tr><td>Users IP Address:</td><td>".$this->error_user_ip."</td></tr>
                      <tr><td>User Agent:</td><td>".$this->error_user_agent."</td></tr>
                </table>";
    $email->send($to, $from, $subject, $message);
  }

  function log_error(){
//    echo "Logging the error<br>";
    //Format a String for the error
    //format: Date time file line message
    $date_time = date("r");
    $mesg = $date_time.",".$this->error_file.",".$this->error_line.",".$this->error_msg.",".$this->error_request_url.",".$this->error_user_ip.",".$this->error_user_agent."\r\n";
    error_log($mesg, 3, $this->error_config['error_log']);
  }

  function display_error(){
    $file_array = explode("\\",$this->error_file);
    $size = sizeof($file_array);
    $_SESSION['error_msg'] = $this->error_msg;
//    $_SESSION['error_file'] = $file_array[$size-1];
//    $_SESSION['error_line'] = $this->error_line;
//    $_SESSION['error_trace'] = $this->error_trace;
    $_SESSION['error_code'] = $this->error_code;
    $_SESSION['request_uri' ] = $this->error_request_url;
    $_SESSION['error_user_ip'] = $this->error_user_ip;
//    header("Location: ".$site_config['site_url']."disp_error.php?SID=".session_id());
    $this->template_config['template'] = array('content' => 'error.html');
    $this->template_config['vars'] = array('error_msg' => $_SESSION['error_msg'],
//                						   'error_file' => $_SESSION['error_file'],
//						                   'error_line' => $_SESSION['error_line'],
				                    	   'request_uri' => $_SESSION['request_uri'],
										   'user_ip' => $_SESSION['error_user_ip']);
    
    //Determine the error type and send the appropriate header code. ie: 404 Not Found or 403 Forbidden.
    //A Further Rewrite and better grouping of Error types and messages is needed to make this really effective.
    switch($this->error_msg){
      case 'Missing Template Config Data':
        header("HTTP/1.0 404 Not Found");   
      break;
      case 'Required Include class not found.':
        header("HTTP/1.0 404 Not Found");
      default:
      //Default will be a 500 Internal server Error
        header("HTTP/1.0 500 Internal Server Error");
      break;
    }//End Switch
    
  }//End function display_error()
}//End Class error
?>
