<?php

class contact{
//Contact & Software & Service common Vars
var $title;
var $name;
var $organization;
var $phone;
var $fax;
var $email;
var $comment;
var $smtp_server;
var $sales_rep;
var $to;
var $site_config;
var $software;
var $message;
var $subject;

//Service Specific Vars
var $bname;
var $address;
var $suburb;
var $state;
var $country;
var $contact;
var $mobile;
var $capacity;
var $details;

  function __construct($t, $site_config, $page, $page_config){
    $this->site_config = $site_config;
    $this->smtp_server = $site_config['smtp_server'];
  }//End function __Construct

  function start(){
    switch ($_POST['mode']):
      case 'software':
        $this->do_software();
    break;
      case 'contact':
        $this->do_contact();
    break;
      case 'service':
        $this->do_service();
    break;
      default:
        $this->do_contact();
    endswitch;
    return $this->send_email();
  }//End function start

  function do_contact(){
    $this->get_data();
    $msg_type = $this->get_msg_type();
    $this->set_contact_msg();
    switch ($msg_type):
      case 'Sales Enquiry':
          $this->subject = 'Sales Enquiry';
          $this->to = $this->site_config['sales_rep'];
          break;
      case 'Hire Enquiry':
          $this->subject = 'Hire Enquiry';
          $this->to = $this->site_config['service'];
          break;
      case 'Website Feedback':
          $this->subject = 'Website Feedback';
          $this->to = $this->site_config['webmaster'];
          break;
      default:
          $this->to = $this->site_config['sales_rep'];
    endswitch;
  }

  function do_software(){
    $this->to = $this->site_config['sales_rep'];
    $this->software = $_POST['software'];
    $this->name = $_POST['name'];
    $this->email = $_POST['email'];
    $this->subject = 'Software Request';
    $this->set_software_msg();
  }

  function do_service(){
    $this->to = $this->site_config['service'];
    $this->get_service_data();
    $this->subject = $this->get_msg_type();
    $this->set_service_msg();
  }

  function get_data(){
    //Gather input information and assign to variables.
    $this->title = $_POST['Contact_Title'];
    $this->name = $_POST['Contact_FullName'];
    $this->organization = $_POST['Contact_Organization'];
    $this->phone = $_POST['Contact_Phone'];
    $this->fax = $_POST['Contact_FAX'];
    $this->email = $_POST['Contact_Email'];
    $this->comment = $_POST['comment'];
  }

  function get_service_data(){
/*    while (list($key, $value) = each($_POST)){
      echo "$key : $value<br/>";
    }*/
    //Gather Inoput data and assign to variables for the Service Page
    $this->bname  = $_POST['business'];
    $this->address = $_POST['address'];
    $this->suburb = $_POST['suburb'];
    $this->state = $_POST['state'];
    $this->country = $_POST['country'];
    $this->contact = $_POST['contact'];
    $this->phone = $_POST['phone'];
    $this->mobile = $_POST['mobile'];
    $this->fax = $_POST['fax'];
//    $this->capacity = $_POST['capacity'];
    $this->email = $_POST['email'];
    $this->details = $_POST['details'];
  }

  function get_msg_type(){
    return $_POST['subject'];
  }

  function set_contact_msg(){
    $this->message = "<b>Title:</b> $this->title<br>
    <b>Name:</b> $this->name<br>
    <b>Organization:</b> $this->organization<br>
    <b>Phone:</b> $this->phone<br>
    <b>Fax:</b> $this->fax<br>
    <b>Email:</b> $this->email<br>
    <b>Comment:</b> $this->comment";
  }

  function set_software_msg(){
    $this->message = "<b>Name:</b> $this->name<br>
    <b>Email:</b> $this->email<br>
    <b>Software Requested:</b> $this->software";
  }

  function set_service_msg(){
    $this->message = "<b>Business:</b> $this->bname<br>
    <b>Address:</b> $this->address<br>
    <b>Suburb:</b> $this->suburb<br>
    <b>State:</b> $this->state<br>
    <b>Country:</b> $this->country<br>
    <b>Contact:</b> $this->contact<br>
    <b>Phone:</b> $this->phone<br>
    <b>Fax:</b> $this->fax<br>
    <b>Email:</b> $this->email<br>
    <b>Details:</b> $this->details";
  }

  function send_email(){
//    echo "Sending Email.....<br/>";
    $type = "html";
    $server = $this->smtp_server;
    include ('includes/email.php');
    $email = new email($server, $type);
    $to = $this->to;
    //$from = "webmaster@andmercury.com";
    $replyto = $this->email;
    $subject = 'Web Site Enquiry';
//    echo "Mail content: To: $to, Reply: $replyto, Subject: $this->subject, message: $this->message<br/>";
    return $email->send($to, $replyto, $this->subject, $this->message);
  }//End Function email
}//End Class contact
