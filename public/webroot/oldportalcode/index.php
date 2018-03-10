<?php

class index{

var $result;
var $var_array;
var $t;
var $site_config;
var $page;
var $pcat;
var $data;

  function __construct($t, $site_config, $page, $pcat){
    $this->t = $t;
    $this->site_config = $site_config;
    $this->page = $page;
    $this->pcat = $pcat;
  }

  function start(){
    $this->read_config();
    $this->newsbox();
    switch ($this->var_array['type']):
      case 'feature':
        $this->do_feature();
          break;
      case 'new_prod':
        $this->do_new_prod();
          break;
      default:
        $this->do_feature();
      endswitch;
      if ($this->var_array['message']){
        if(time() < $this->var_array['msg_expire']){
          $this->t->set_file('message', 'message.html');
          $this->t->parse('message', 'message');
        }//End If

      }//End If
    return $this->t;
  }//End Function Start

  function read_config(){
    $this->var_array = parse_ini_file('config/index.ini');
  }//End function read_config

  function newsbox(){
    include ('newsbox.php');
    $newsbox = new newsbox($this->t, $this->site_config, $this-> page, $this->pcat);
    $newsbox->start();
    //Set the CSI Value
    $this->t->set_var('csi', $this->var_array['csi']);
  }//End Function newsbox

/*
  function do_feature(){
    $this->t->set_file('featured_product', 'featured1.html');
    $this->feat_array = parse_ini_file('config/feature1.ini', true);
    $day = date('j',time());
    reset($this->feat_array);
    while(current($this->feat_array)){
      if (key($this->feat_array) == $day){
        $data = current($this->feat_array);
        $this->t->set_var(array('fp_title' => $data['title'],
                                'fp_img' => $data['img'],
                                'fp_description' => $data['desc']));
      }//End If
      next($this->feat_array);
    }//End For
    $this->t->parse('featured_product', 'featured_product');
  }//End Function
  */
  function do_feature(){
    $product_list = parse_ini_file('config/feat_list.ini', TRUE);
    $site_ini = parse_ini_file('config/site.ini', TRUE);
    $site_range = $site_ini['range'];
    $this->t->set_file('featured_product', 'featured1.html');
    //Read the listed product information into a multi D array for easy refernce
    $range_array = explode(',',$site_range[1]);
    $ranges = array(0 => $range_array[0],
                    1 => $range_array[1],
                    2 => $range_array[2]);
    for($k = 2;$k<=sizeof($site_range);$k++){
      $range_array = explode(',',$site_range[$k]);
      array_push($ranges, $range_array[0], $range_array[1], $range_array[2]);
    }
/*    while(list($key, $value) = each($ranges)){
      echo "Key: $key - Value: $value<br/>";
    }//End While
  */
    for ($j=0;$j<sizeof($product_list);$j++){
      for ($l = 0;$l<sizeof($ranges);$l++){
       // echo "Ranges: ".$ranges[$l]." ".$ranges[$l+1]." ".$ranges[$l+2]." l = $l<br/>";
        if ($product_list[$j] >= $ranges[$l] && $product_list[$j] <= $ranges[$l+1]){
          $cat = $ranges[$l+2];
          $tdata = parse_ini_file("config/products/$cat/master_list.ini", TRUE);
/*          echo "TData: <br/>";
          while(list($key, $value) = each($tdata)){
            echo "Key: $key - Value: $value<br/>";
          }//End While*/
//          echo "PID: ".$product_list[$j]."<br/>";
          $pdata[$j] = $tdata[$product_list[$j]];
          $pdata[$j]['cat'] = $cat;
          $pdata[$j]['pid'] = $product_list[$j];
        }//End If
        $l = $l + 2;
      }//End For
    }//End For Loop
/*    echo "PData:<br/>";
    while(list($key, $value) = each($pdata)){
      echo "Key: $key - Value: $value<br/>";
      while(list($key1, $value1) = each($value)){
        echo "Key: $key1 - Value: $value1<br/>";
      }//End While
    }//End While*/

    //loop through multi D array assigning variables
    $this->t->set_block('featured_product','featured_products', 'features');
//    $position = 'left';
    $padding = '5px 5px 5px 0';
    for($m=0;$m<sizeof($pdata);$m++){
      $this->t->set_var(array('product' => $pdata[$m]['product'],
                              'desc' => $pdata[$m]['Desc'],
                              'image' => $pdata[$m]['img'],
                              'iwidth' => $pdata[$m]['iwidth'],
                              'iheight' => $pdata[$m]['iheight'],
                              'cat' => $pdata[$m]['cat'],
                              'pid' => $pdata[$m]['pid'],
                              //'float' => $position,
                              //'text-align' => $position,
                              'padding' => $padding,));
      $this->t->parse('features','featured_products',true);
/*      if ($position == 'left'){
        $position = 'right';
        $padding = '0 0 0 5px';
      }else{
        $position = 'left';
        $padding = '0 5px 0 0';
      }//End If-else*/
    }//End For
  }//End Function do_feature

  function do_new_prod(){
    $this->t->set_file('new_product', 'new_product.html');
    if (mt_rand(0,1)){
      $this->data = parse_ini_file('config/new_product.ini', true);
    }else{
      $this->data = parse_ini_file('config/new_product1.ini', true);
    }//End If Else
    reset($this->data);
    $misc = isset($this->data['misc']) ? $this->data['misc']:null;
    //Loop for assigning Misc Items
    reset($misc);
    for ($i=1; $i<=sizeOf($misc); $i ++){
      $this->t -> set_var(key($misc), current($misc));
      next($misc);
    }

    //Assign Other Required Variables (Product category and round the product
    //page code down to the product summary page code for the back link)
    $this->t -> set_var(array(//'pcat' => ucwords($this->pcat),
                              'pcat_code' => round($this->page, -2),
                              'pc' => $this->page));
    $this->t->set_var('summary', $this->data['summary']['summary']);
    //$this->t->set_var('pcat', $this->var_array['pcat']);
    $this->t->parse('new_product', 'new_product');
  }//End function do_new_prod
}//End Class index
