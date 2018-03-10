<?php

class newsbox{

var $t;
var $site_config;
var $page;
var $pcat;
var $news_array;

  function __construct($t, $site_config, $page, $pcat){
    $this->t = $t;
    $this->site_config = $site_config;
    $this->page = $page;
    $this->pcat = $pcat;
  }

  function start(){
    $this->read_config();
    $this->process();
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

/*
* This code populates the news box from the news array, grouping the items
* under date headings.
*/

  function process(){
    $this->t->set_block('newsbox', 'news_items', 'news_list');
    $this->t->set_block('newsbox', 'news_date', 'dates');
    $i = 0;
    while (list($key, $value) = each($this->news_array)){
      $date = $key;
      $year = substr($date, 1, 4);
      $month = substr($date, 5, 2);
      $day = substr($date, 7, 2);
      $hour = substr($date, 9, 2);
      $minute = substr($date, 11, 2);
      if ($i<4){
        $new = $i<1?$this->site_config['new']:'';
        $this->t->set_var('date', $date);
        $link = $value['link'];
        $title = $value['title'];
        $this->t->set_var(array('new' => $new,
                                'date' => "$day/$month/$year",
                                'date1' => $date,
                                'link' => $link,
                                'title' => $title));
        $this->t->parse('news_list', 'news_items', true);
        $this->t->parse('dates', 'news_date',true);
        $this->t->set_var('news_list','');
        $i++;
      }//End if
    }//End While
  }//End Function process
}//End Class Newsbox
