<?php

class faq{

var $t;
var $site_config;
var $page;
var $pcat;
var $mode;
var $faq;

  function __construct($t, $site_config, $page, $pcat){
    $this->t = $t;
    $this->site_config = $site_config;
    $this->page = $page;
    $this->pcat = $pcat;
    $this->mode = $_GET['p'];
    $this->t->debug = 0;
  }

  function start(){
    switch ($this->page):
      case 1020:
        $this->read_faq($this->site_config['faq']);
      break;
      case 9009:
        include('includes/dealer_sessions.php');
        $ds = new dealer_sessions($this->page);
        $ds->start();
        $this->read_faq($this->site_config['dealerfaq']);
      break;
      default:
        $this->read_faq($this->site_config['faq']);
    endswitch;
    $this->get_faq();
    return($this->t);
  }//End Function Start

  function read_faq($file){
    $this->faq = parse_ini_file($file, true);
  }//End read_faq

  function get_titles(){
    for($i=1;$i<=sizeof($this->faq);$i++){
      $title_list[$i] = $this->faq[$i]['title'];
    }//End For
    return $title_list;
  }//End function get_titles

  function get_faq(){
    //This function will read the questions for the configuration file
    //and display them on screen
//    $this->t->set_file('faqlist', 'faqlist.html');
    $this->t->set_block('content','faqtitle','faqtitles');
    $titles = $this->get_titles();
    for($k=1;$k<=sizeof($titles);$k++){
      $this->t->set_var('ttitle', $titles[$k]);
      $this->t->parse('faqtitles','faqtitle',true);
    }//End For
    $this->t->set_file('content', 'faq.html');
    $this->t->set_block('content','faqitem','faqitems');
    $this->t->set_block('content','faqlist','faqs');
    for($i=1;$i<=sizeof($this->faq);$i++){
      $this->t->set_var('title', $this->faq[$i]['title']);
      for($j=1;$j<sizeof($this->faq[$i]);$j++){
        $this->t->set_var('question', $this->faq[$i][$j]);
        $j++;
        $this->t->set_var('answer', $this->faq[$i][$j]);
        $this->t->parse('faqitems','faqitem',true);
      }//End For
      $this->t->parse('faqs','faqlist',true);
      $this->t->set_var('faqitems', '');
    }//End For
  }//End get_faq
}//End Class
?>