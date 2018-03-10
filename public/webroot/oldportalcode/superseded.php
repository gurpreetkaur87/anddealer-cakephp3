<?php

class superseded{

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
      $file = "config/products/$this->pcat/superseded.ini";
      if (!file_exists($file)){
        throw new Exception("Superseded List File Not Found!");
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
    }//End Try-Catch
    $this->t ->set_block('product_list', 'product', 'products');
    reset($master_list);
    $count = 0;
    for ($i=1; $i<=sizeof($master_list);$i++){
      if (key($master_list) != 'slist'){
      $count ++;
      $clist = current($master_list);
      $new_image = $clist['new'] ? $this->site_config['new']:'';
      $this->t -> set_var(array('page' => key($master_list),
                                'new_prod' => $new_image,
                                'prod_img' => $clist['img'],
                                'prod_desc' => $clist['Desc'],
                                'pname' => $clist['product'],
                                'iwidth' => $clist['iwidth'],
                                'iheight' => $clist['iheight'],
                                'pcat' => $this->pcat,
                                'vis' => 'visible',
                                'sa_vis' => 'hidden'));
      $this->t -> parse('products', 'product',true);
      }else if(key($master_list) == 'slist'){
        $this->t ->set_var('sa_vis', 'visible');
        $clist = current($master_list);
        $this->t -> set_var(array('ptype' => ucwords($this->pcat)));
        $this->t ->set_block('content', 'super_list', 'slist_items');
        for($j=1;$j<=sizeof($clist);$j++){
          $sup_list = explode(',',$clist[$j]);
          $smodel = $sup_list[0];
          $cmodel = "";
          for ($k=1;$k<=sizeof($sup_list)-1;$k++){
            //Test for the precense of and Unavailable keyword. If found do not add hyperlink
            if (strcmp($sup_list[$k],"Unavailable") == 0){
              $cmodel = $cmodel."Unavailable";
            }else{
              $cmodel = $cmodel."<a href='web.php?p=".$sup_list[$k+1]."'>".$sup_list[$k]."</a>";
            }//End If Else
            $k++;
            //Test if this is the last entry in the list. If so do not include the separator
            if ($k<sizeof($sup_list)-1){
              $cmodel = $cmodel." / ";
            }//End If
          }//End for
          $this->t -> set_var(array('smodel' => $smodel,
                                    'cmodel' => $cmodel));
          $this->t -> parse('slist_items', 'super_list',true);
        }//End For
      }//End Else If
      next($master_list);
    }//End For
    //Check that Master List has an even value, excluding the slist section. if not, pad it using an invisible & empty product info cell.
    if (fmod($count,2) != 0){
      $this->t -> set_var(array('page' => 'null',
                                'new_prod' => false,
                                'prod_img' => 'null',
                                'prod_desc' => 'null',
                                'pname' => 'null',
                                'iwidth' => 'null',
                                'iheight' => 'null',
                                'pcat' => $this->pcat,
                                'vis' => 'hidden'));
      $this->t -> parse('products', 'product',true);
    }
    return $this->t;
  }//End Function Start
}//End Class
?>
