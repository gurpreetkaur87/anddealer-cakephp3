<?php

class prod_summ{

var $t;
var $site_config;
var $page;
var $pcat;

  function __construct($t, $site_config, $page, $pcat){
    $this->t = $t;
    $this->site_config = $site_config;
    $this->page = $page;
    $this->pcat = $pcat;
  }//End __Construct

  function start(){
  try{
      $file = "config/products/$this->pcat/master_list.ini";
      if (!file_exists($file)){
        throw new Exception("Master List File Not Found!");
      }
      $master_list = parse_ini_file($file, true);
    }catch(Exception $ex){
      throw $ex;
    }
    try{
      if (file_exists("templates/products/$this->pcat/prod_info.html")){
        $this->t -> set_file(array('product_list' => "products/$this->pcat/prod_info.html"));
      }elseif (file_exists("templates/products/prod_info.html")){
        $this->t -> set_file(array('product_list' => "products/prod_info.html"));
      }else{
        throw new Exception("Can't Find prod_info.html template file");
      }//End If-Else-If-Else
    }catch(Exception $ex){
      throw $ex;
    }
    $this->t ->set_block('product_list', 'product', 'products');
    reset($master_list);
    for ($i=1; $i<=sizeof($master_list);$i++){
      $clist = current($master_list);
      $new_image = $clist['new'] ? $this->site_config['new']:'';
      $this->t -> set_var(array('page' => key($master_list),
                                'new_prod' => $new_image,
                                'prod_img' => $clist['img'],
                                'prod_desc' => $clist['Desc'],
                                'pname' => $clist['product'],
                                'iwidth' => $clist['iwidth'],
                                'iheight' => $clist['iheight'],
                                'vis' => 'visibile'));
      $this->t -> parse('products', 'product',true);
      next($master_list);
    }//End For
    $this->t -> set_var(array('ptype' => ucwords($this->pcat),
                              'pcat' => ucwords($this->pcat)));
    return $this->t;
  }//End Function Start
}//End Class prod_summ

?>
