<?php

class news{

var $t;
var $site_config;
var $page;
var $pcat;
var $news_array;
var $mode;

  function __construct($t, $site_config, $page, $pcat){
    $this->t = $t;
    $this->site_config = $site_config;
    $this->page = $page;
    $this->pcat = $pcat;
    $this->mode = $_GET['p'];
  }

  function start(){
    $this->read_config();
    switch ($this->mode):
      case 1011:
        $this->process();
        $this->year_list();
        $this->select_month();
        break;
      case 1017:
        $this->get_archive();
        $this->year_list();
        $this->select_month();
        break;
      default:
        $this->process();
        $this->year_list();
        $this->select_month();
    endswitch;
    return $this->t;
  }

  function read_config(){
    try{
      if (file_exists('config/news.ini')){
        $this->news_array = parse_ini_file("config/news.ini", true);
      }else{
        throw new Exception('Can Not Find News Configuration File!');
      }//End If-Else
    }catch(Exception $ex){
      throw $ex;
    }//End Try-Catch
  }//End Function read_config

  function process(){
    $this->t -> set_block('content', 'news_list', 'news_items');
    $i = 0;
    while (list($key, $value) = each($this->news_array)){
      if ($i<4){
        if (file_exists("templates/news/$key.html")){
          $fh = fopen("templates/news/$key.html", 'r');
          $contents = '';
          while (!feof($fh)) {
            $contents .= fread($fh, 8192);
          }//End While
          $date = $key;
          $this->t->set_var('news', $contents);
          $this->t->set_var(array('date' => $date));
        }//End if
        $this->t->parse('news_items', 'news_list', true);
        $i++;
      }//End If
    }//End While
  }//End Process

  function get_archive(){
    $month = date("m",strtotime($_POST['month']." ".$_POST['year']));
    $year = $_POST['year'];
    $start = $year.$month."010000";
    $end = $year.$month."311259";
    $this->t -> set_block('content', 'news_list', 'news_items');
    $items = 0;
    while (list($key, $value) = each($this->news_array)){
      if (file_exists("templates/news/$key.html")){
        if($key > $start && $key < $end){
          $items++;
          $fh = fopen("templates/news/$key.html", 'r');
          $contents = '';
          while (!feof($fh)) {
            $contents .= fread($fh, 8192);
          }//End While
          $date = $key;
          $this->t->set_var('news', $contents);
          $this->t->set_var(array('date' => $date));
          $this->t->parse('news_items', 'news_list', true);
        }//End if
      }//End If
    }//End While
    if ($items == 0){
      $this->t->set_var('news', "There are no News items for ".$_POST['month']." ".$_POST['year']."...");
      $this->t->parse('news_items', 'news_list');
    }//End If
  }//End function get_archive
  
  function year_list(){
    $current_year = date('Y', time());
    $this->t ->set_block('content', 'year_block', 'years');
    $selected_year = isset($_POST['year'])?$_POST['year']:$current_year;
    $start_year = $this->site_config['syear'];//2005;
    $year = $current_year;
    while (($current_year - $start_year) >= 0){
      $selected = $selected_year == $current_year?"selected='selected'":"";
      $this->t -> set_var(array('year' => $current_year,
                                'selected' => $selected));
      $this->t ->parse('years', 'year_block', true);
      $current_year = $current_year - 1;
      $selected = "";
    }//End While
  }//End function year_list
  
  function select_month(){
    $current_month = date('F', time());
    $this->t ->set_block('content', 'month_list', 'months');
    $month = isset($_POST['month'])?$_POST['month']:$current_month;
    $months = array(1 => 'January',
                    2 => 'February',
                    3 => 'March',
                    4 => 'April',
                    5 => 'May',
                    6 => 'June',
                    7 => 'July',
                    8 => 'August',
                    9 => 'September',
                    10 => 'October',
                    11 => 'Novemeber',
                    12 => 'December');
    for ($i=1;$i<=sizeof($months);$i++){
      $selected = $months[$i] == $month?"selected='selected'":"";
      $this->t -> set_var(array('month' => $months[$i],
                                'selected' => $selected));
      $this->t ->parse('months', 'month_list', true);
      $selected = "";
    }//End For
  }//End function select_month
}//End Class News
