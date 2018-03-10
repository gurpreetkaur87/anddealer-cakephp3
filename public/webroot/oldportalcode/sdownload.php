<?php

class sdownload{

var $t;

  function __construct($t){
    $this->t = $t;
  }

  function start(){
    switch($_GET['i']){
      case 1:
        if (file_exists("templates/winct1.html")){
          $this->t->set_file('content', 'winct1.html');
        }else{
          throw new Exception("Download Information file not found");
        }//End If-Else
      break;
      case 2:
        if (file_exists("templates/winct1.html")){
        $this->t->set_file('content', 'winct-ucf.html');
        }else{
          throw new Exception("Download Information file not found");
        }//End If-Else
      break;
      case 3:
        if (file_exists("templates/winct1.html")){
        $this->t->set_file('content', 'winst.html');
        }else{
          throw new Exception("Download Information file not found");
        }//End If-Else
      break;
      case 4:
        if (file_exists("templates/winct1.html")){
        $this->t->set_file('content', 'winbp.html');
        }else{
          throw new Exception("Download Information file not found");
        }//End If-Else
      break;
      case 5:
        if (file_exists("templates/winct1.html")){
        $this->t->set_file('content', 'bpd.html');
        }else{
          throw new Exception("Download Information file not found");
        }//End If-Else
      break;
      case 6:
        if (file_exists("templates/winct1.html")){
        $this->t->set_file('content', 'wd.html');
        }else{
          throw new Exception("Download Information file not found");
        }//End If-Else
      break;
    }//End Switch
    return $this->t;
  }//End function start
}//End Class
?>
