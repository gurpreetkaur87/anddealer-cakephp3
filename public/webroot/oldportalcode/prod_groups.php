<?php

class prod_groups{

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
    if (isset($_GET['l'])){
      $this->group_level();
    }elseif (isset($_GET['g'])){
      $this->group_prod();
    }else{
      header("Location: web.php?p=$page");
      die();
    }//End If-ElseIf-Else
    return $this->t;
  }//End Start

  function group_level(){
    //Check to see if there is  customised Groups template
    //echo "Checking for Custom group template.</br>";
    if (file_exists("templates/products/$this->pcat/prod_groups.html")){
      //echo "Found custom file, re-assigning template</br>";
      $this->t -> set_file(array('content' => "products/$this->pcat/prod_groups.html"));
    }//End If
    //Read the Groups configuration file
    try{
      $gfile = "config/products/$this->pcat/groups.ini";
      if (!file_exists($gfile)){
        throw new Exception("Group List File Not Found!");
      }//End If
      $group_list = parse_ini_file($gfile, true);
    }catch(Exception $ex){
        throw $ex;
    }//End Try-Catch
    //Check from Group Level 0 and process message information if present

    reset($group_list);
    //Process the Group Level
    $j=0;
    while (current($group_list)){
      $key = substr(key($group_list),0,1);
      //echo "Key: $key</br>";
      //The 0-1, 0-2, 0-3, etc. Group levels allow the additon of extra information
      //such as summaries, to the group listing page for the various levels.
      //Check for 0 based group levels and assigning the information to $group_level_message,
      //Then proceed to retreive normal group config data.
      if ($key == 0){
        //echo "Retreiving group level messages<br/>";
        $group_level_message[$j] = current($group_list);
        //Check the current group level and assign the appropriate message to the template engine
        if ($j == $_GET['l']-1){
        //echo "Assigning: ".$group_level_message[$i]."<br/>";
        $this->t ->set_var('message', $group_level_message[$j]['message']);
      }//End If
        $j++;
      }elseif ($key == $_GET['l']){
        $glevel[$j] = current($group_list);
        $j++;
      }//End If
      next($group_list);
    }//End while
    //Process and assign group information to the template engine
    $this->t -> set_file(array('product_list' => "products/prod_info.html"));
    $this->t ->set_block('product_list', 'product', 'products');
    reset($glevel);
    for ($i=1; $i<=sizeof($glevel);$i++){
      $clist = current($glevel);
      $this->t -> set_var(array('page' => $clist['link'],
                                'prod_img' => $clist['img'],
                                'prod_desc' => $clist['desc'],
                                'pname' => $clist['title'],
                                'iwidth' => $clist['width'],
                                'iheight' => $clist['height'],
                                'vis' => 'visible'));
      $this->t -> parse('products', 'product',true);
      next($glevel);
    }//End For
    //Determine the summary page number for this product by rounding down to the nearest 100
    $round = floor($this->page/100);
    $rounded = $round*100;
    $this->t -> set_var(array('ptype' => ucwords($this->pcat),
                              'pcat' => ucwords($this->pcat),
                              'pcat_code' => $rounded,
                              'pp' => $this->page));
    if (isset($_GET['pp'])){
      $this->t -> set_var(array('ppp' => $_GET['pp']));
    }//End If
  }//End Function group_level



  function group_prod(){
    //Check to see if there is  customised Groups template
    //echo "Checking for Custom group template.</br>";
    if (file_exists("templates/products/$this->pcat/prod_groups.html")){
      //echo "Found custom file, re-assigning template</br>";
      $this->t -> set_file(array('content' => "products/$this->pcat/prod_groups.html"));
    }//End If
    try{
      $gfile = "config/products/$this->pcat/groups.ini";
      if (!file_exists($gfile)){
        throw new Exception("Group List File Not Found!");
      }//End If
      $group_list = parse_ini_file($gfile, true);
    }catch(Exception $ex){
        throw $ex;
    }//End Try-Catch
    $gprods = explode(',',$group_list[$_GET['g']]['products']);
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
    try{
      $file = "config/products/$this->pcat/master_list.ini";
      if (!file_exists($file)){
        throw new Exception("Master List File Not Found!");
      }
      $master_list = parse_ini_file($file, true);
    }catch(Exception $ex){
      throw $ex;
    }
    //echo "message: ".$master_list[$this->page]['message']." img: ".$master_list[$this->page]['img'].";</br>";
    if(isset($master_list[$this->page]['message'])){
      $this->t->set_var(array('message' => $master_list[$this->page]['message'],
                              'top_img' => "<a href='{url}{prod_img_path}$this->pcat/".$master_list[$this->page]['img_link']."'><img border='0' src='{url}{prod_img_path}$this->pcat/".$master_list[$this->page]['img']."'/></a>"));
    }
    $this->t ->set_block('product_list', 'product', 'products');
    for ($j=0;$j<sizeof($gprods);$j++){
      reset($master_list);
      for ($i=0; $i<=sizeof($master_list);$i++){
        $current = current($master_list);
        if ($gprods[$j] == key($master_list)){
          $clist = current($master_list);
          $new_image = $clist['new'] ? $this->site_config['new']:'';
          $this->t -> set_var(array('page' => key($master_list),
                                    'new_prod' => $new_image,
                                    'prod_img' => $clist['img'],
                                    'prod_desc' => $clist['Desc'],
                                    'pname' => $clist['product'],
                                    'iwidth' => $clist['iwidth'],
                                    'iheight' => $clist['iheight'],
                                    'vis' => 'visible'));
          $this->t -> parse('products', 'product',true);
          next($master_list);
        }//End If
        next($master_list);
      }//End for
    }//End For
    //Determine the summary page number for this product by rounding down to the nearest 100
    $round = floor($this->page/100);
    $rounded = $round*100;
    if (isset($group_list[$_GET['g']]['title'])){
      $ptype = $group_list[$_GET['g']]['title'];
    }else{
      $ptype = ucwords($this->pcat);
    };
    $this->t -> set_var(array('ptype' => $ptype,
                              'pp' => $this->page,
                              'pcat' => ucwords($this->pcat),
                              'pcat_code' => $rounded,
                              'ppp' => $_GET['pp']));
  }//End function group_prod
}//End Class
?>
