<?php

class dealer_rb{

var $t;
var $site_config;
var $page;
var $pcat;
var $dsess;
var $dealer_db;
var $result_array;
var $data_values;

  function __construct($t, $site_config, $page, $pcat){
    $this->t = $t;
    $this->site_config = $site_config;
    $this->page = $page;
    $this->pcat = $pcat;
//    include ('includes/dealer_sessions.php');
//    $this->dsess = new dealer_sessions($page);
    include ('Includes/dealer_db.php');
    $this->dealer_db = new dealer_db('andweb');
  }//End Constructor

  function start(){
  //Check Session Validity
//    $this->dsess->start();
    if (isset($_GET['m'])){
      if ($_GET['m'] == 'req'){
        //Check Data for validity
        $vresult = $this->validate();
        //If data fails validity return immediately
        if (!$vresult){
          return $this->t;
        }//end If
        $result = $this->do_request();
      }else{
        throw new Exception('Fatal Error: Invalid parameter passed to function');
      }//End If-Else
    }else{
      $this->get_account_list(null);
      $this->gen_brochure_list();
    }
    return $this->t;
  }//End function start
  
  function get_account_list($account){
    //query db
    $query = "Select distinct ccustno from dealers";
    $this->dealer_db->do_query($query);
    //The result array is a multi-dimensional arry where each element on the
    //array is a row from the query result.
    $this->result_array = $this->dealer_db->fetch_result_array();
    if(!sort($this->result_array)){
      echo "Array Sort Failed<br>";
    }//End if
    $this->t->set_block('content','account_list','accounts');
    for ($i=0;$i<sizeof($this->result_array);$i++){
      $selected = trim($this->result_array[$i][0]) == $account?'selected':'';
      $this->t->set_var(array('acc_num' => trim($this->result_array[$i][0]),
                              'acc_selected' => $selected));
      $this->t->parse('accounts', 'account_list', true);
    }//End For
  }//End function get_account_list
  
  function gen_brochure_list(){
    $brochure_list = parse_ini_file('config/brochure.ini');
    $this->t->set_block('content', 'brochure_list', 'brochures');
    for ($i=1;$i<=sizeof($brochure_list);$i++){
      $this->t->set_var('brochure', $brochure_list[$i]);
      $this->t->parse('brochures', 'brochure_list', true);
    }//End For
  }//End Function gen_brochure_list

  function validate(){
    $name = $_POST['name'];
    $branch = $_POST['branch'];
    $account = $_POST['account'];
    $name_valid = empty($name)?false:true;
    $branch_valid = empty($branch)?false:true;
    $broch_valid = !isset($_POST['brochures'])?false:true;
    $acc_valid = empty($account)?false:true;
    if (!$name_valid || !$branch_valid || !$acc_valid || !$broch_valid){
      $this->t->set_var(array('name' => $name,
                              'branch' => $branch,
                              'account' => $account,
                              'error' => 'Error: Please fill in all of the fields'));
      $dvalid = false;
      $this->gen_brochure_list();
      $this->get_account_list($account);
      return false;
    }
    return true;
  }//End Function Validate

  function do_request(){
    include ('includes/email.php');
    $type = "html";
    $server = $this->site_config['smtp_server'];
    $email = new email($server, $type);
    //Collect POST information and email message before passing to the email class.
    $name = $_POST['name'];
    $branch = $_POST['branch'];
    $account = $_POST['account'];
    $brochures = $_POST['brochures'];
    $subject = "Brochure Request";
    $bselect = $brochures[0];
    for ($i=1;$i<sizeof($brochures);$i++){
      $bselect = $bselect."<br>".$brochures[$i];
    }//End For
    $to = $this->site_config['brochure_rep'];
    $from = $this->site_config['web_email'];
    $message = "<b>Name:</b> $name<br>";
    $message = $message."<b>Branch:</b> $branch<br>";
    $message = $message."<b>Account:</b> $account<br>";
    $message = $message."<b>Brochures Requested:</b><br> $bselect<br>";
    if ($email->send($to, $from, $subject, $message)){
      header("Location: web.php?p=9007");
    }else{
      throw new Exception("Attempt to Send Email Has failed");
    }//End If Else
  }//End function do_request
}//End Class
?>
