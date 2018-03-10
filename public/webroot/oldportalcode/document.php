<?php

class document{

var $result;
var $var_array;
var $t;
var $site_config;
var $page;
var $pcat;

  function __construct($t, $site_config, $page, $pcat){
    $this->t = $t;
    $this->site_config = $site_config;
    $this->page = $page;
    $this->pcat = $pcat;
  }//End Function __Construct

  function start(){
    /**
    * Switch between brochure Display mode and Dealers manual display mode.
    * Retreive the GET information about the file, if the data doesn't match
    * the established cases through an invalid parameter error, else check that
    * the file exist then redirect to the file to Download.
    **/
    switch ($_GET['m']){
      case 'man':
        switch($_GET['t']){
          case 'inst':
            if (file_exists($this->site_config['root_path'].$this->site_config['path'].$this->site_config['man'].'/Instruction/'.$_GET['d'])){
              header("Location: ".$this->site_config['path'].$this->site_config['man']."/Instruction/".$_GET['d']);
            }else{
              throw new Exception ("Sorry, I can't find the requested file");
            }//End If-Else
          break;
          case 'maint':
            if (file_exists($this->site_config['root_path'].$this->site_config['path'].$this->site_config['man'].'/Maintenance/'.$_GET['d'])){
              header("Location: ".$this->site_config['path'].$this->site_config['man']."/Maintenance/".$_GET['d']);
            }else{
              throw new Exception ("Sorry, I can't find the requested file");
            }//End If-Else
          break;
          case 'idiag':
            echo "Installation Diagrams Code goes Here!";
            if (file_exists($this->site_config['root_path'].$this->site_config['path'].'/inst_diag/'.$_GET['d'])){
              header("Location: ".$this->site_config['path']."/inst_diag/".$_GET['d']);
            }else{
              throw new Exception ("Sorry, I can't find the requested file");
            }//End If-Else
          break;
          default:
          //ERROR Condition
          throw new Exception("Invaild Parameter passed to function");
        }//End Switch
      break;
      case 'bro':
        if(empty($_GET['b'])){
          $this->no_brochure();
        }else{
          if (file_exists($this->site_config['root_path']."brochures/".$_GET['c'].'/'.$_GET['b'])){
            header("Location: brochures/".$_GET['c'].'/'.$_GET['b']);
          }else{
            throw new Exception ("Sorry, I can't find the requested file");
          }//End If-Else
        }//End If-Else
      break;
      case 'ipl':
        if (file_exists($this->site_config['root_path'].$this->site_config['path'].$this->site_config['ipl'].$_GET['d'])){
            header("Location: ".$this->site_config['path'].$this->site_config['ipl'].$_GET['d']);
          }else{
            throw new Exception ("Sorry, I can't find the requested file");
          }//End If-Else
      break;
      case 'soft':
        if(empty($_GET['d'])){
          $this->no_software();
        }else{
//          die;
          if (file_exists($this->site_config['root_path'].$this->site_config['soft'].$_GET['d'])){
            header("Location: ".$this->site_config['soft'].$_GET['d']);
          }else{
            $this->no_software();
  //          throw new Exception ("Sorry, I can't find the requested file");
          }//End If-Else
        }//End if-else
      break;
      default:
      //Error Condition
      throw new Exception("Invalid Parameter passed to Function");
    }//End Switch
    return $this->t;
  }//End Function Start

  function no_brochure(){
    //Output the No Brochure available page.
    $this->t->set_file('no_brochure', 'nobrochure');
    $this->t->set_var('pcode', $_GET['pc']);
    $this->t->parse('no_brochure', 'nobrochure');
  }//Function no_brochure
  
  function no_software(){
    //Output the Request Software page .
    $this->t->set_file('content', 'nosoftware.html');
    $this->t->set_var(array('pcode' => $_GET['pc'],
                            'software' => $_GET['d']));
    $this->t->parse('no_software', 'nosoftware');
  }//Function no_brochure
}//End Class document
