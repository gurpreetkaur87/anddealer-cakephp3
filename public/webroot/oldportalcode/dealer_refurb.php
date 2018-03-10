<?php

class refurb{

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
    try{
      $file = "config/dealers/refurb.ini";
      if (!file_exists($file)){
        throw new Exception("Refurb List File Not Found!");
      }
      $refurb_list = parse_ini_file($file, true);
    }catch(Exception $ex){
      throw $ex;
    }
    try{
      if (file_exists("templates/dealers/refurb_info.html")){
        $this->t -> set_file(array('product_list' => "dealers/refurb_info.html"));
      }else{
        throw new Exception("Can't Find refurb_info.html template file");
      }//End If-Else-If-Else
    }catch(Exception $ex){
      throw $ex;
    }
    $this->t ->set_block('product_list', 'product', 'products');
    $this->t ->set_block('content', 'terms', 'terms_list');
    $terms_list = array_shift($refurb_list);
    for ($j=1; $j<sizeof($terms_list);$j++){
      $this->t -> set_var('terms_item', $terms_list[$j]);
      $this->t -> parse('terms_list', 'terms',true);
    }//End For
    $announce = array_shift($refurb_list);
    if ($announce['enabled'] == true){
      $this-> t -> set_var('announce', $announce['message']);
    }//End If
    $this->t->set_var('num_prods',sizeof($refurb_list));
    reset($refurb_list);
    for ($i=1; $i<=sizeof($refurb_list);$i++){
      $clist = current($refurb_list);
 //     $new_image = $clist['new'] ? $this->site_config['new']:'';
      $this->t -> set_var(array(/*'page' => key($master_list),*/
                                'img' => $clist['img'],
                                'desc' => $clist['desc'],
                                'model' => $clist['model'],
                                'sn' => $clist['sn'],
                                'price' => $clist['price'],
                                'iwidth' => $clist['iwidth'],
                                'iheight' => $clist['iheight'],
                                'vis' => 'visibile'));
      $this->t -> parse('products', 'product',true);
      next($refurb_list);
    }//End For
    $this->t -> set_var(array('ptype' => ucwords($this->pcat),
                              'pcat' => ucwords($this->pcat),
                              'title' => $terms_list['title']));
    return $this->t;
  }//End Function Start
}//End Class
?>