<?php

class disp_product{

var $t;
var $site_config;
var $page;
var $pcat;

  function __construct($t, $site_config, $page, $pcat){
    $this->t = $t;
    $this->site_config = $site_config;
    $this->page = $page;
    $this->pcat = $pcat;
  }//End function __construct

  function start(){
    switch ($this->site_config['data_store']):
      case 'db':
        $this->from_db();
        //Check the type of database to be used (MSsql/Mysql)
        //Import and initilise files and classes required for database access
        //Call the from_db function
          break;
      case 'file':
        //Call the from_file function
        $this->from_file();
          break;
      default:
        //Call the from_file function
        $this->from_file();
      endswitch;
    return $this->t;
  }//End function Start
  
  function from_db(){
    die("Reading from a Database is not yet supported");
  }

  function from_file(){
    try{
      $file = 'config/products/'.$this->pcat.'/'.$this->page.'.ini';
      $data = parse_ini_file($file, true);
      if (empty($data)){
        throw new Exception("Product Information file not found");
      }
    }catch(Exception $ex){
      throw $ex;
    }
    $misc = isset($data['misc']) ? $data['misc']:null;
    $features = isset($data['features']) ? $data['features']:null;
    $capacities = isset($data['capacities']) ? $data['capacities']:null;
    $options = isset($data['options']) ? $data['options']:null;
    $applications = isset($data['applications']) ? $data['applications']:null;
    $info = isset($data['info']) ? $data['info']:null;
    $raw = isset($data['raw']) ? $data['raw']:null;
    $feat_logos = isset($data['feat_logos']) ? $data['feat_logos']:null;

    //Get file size for Brochure/software download - EXPERIMENTAL!
    $path = $this->site_config['root_path'].$this->site_config['brochure'].$this->pcat;
   /* switch ($this->pcat):
      case 'balances':
        $path = $this->site_config['root_path'].$this->site_config['brochure'].$this->pcat;
      break;
      case 'loadcells':
        $path = $this->site_config['root_path'].$this->site_config['brochure'].$this->pcat;
      break;
      case 'scales':
        $path = $this->site_config['root_path'].$this->site_config['brochure'].$this->pcat;
      break;
      case 'indicators':
        $path = $this->site_config['root_path'].$this->site_config['brochure'].$this->pcat;
      break;
      case 'accessories':
        $path = $this->site_config['root_path'].$this->site_config['brochure'].$this->pcat;
      break;
      case 'baseworks':
        $path = $this->site_config['root_path'].$this->site_config['brochure'].$this->pcat;
      break;
      case 'dsp-tng':
        $path = $this->site_config['root_path'].$this->site_config['brochure'].$this->pcat;
      break;
      case 'measurement':
        $path = $this->site_config['root_path'].$this->site_config['brochure'].$this->pcat;
      break;
      case 'software':
        $path = $this->site_config['root_path'].$this->site_config['soft'];
      break;
      default:
        //Throw Error Here!!!
        $path = "Category not found";
        throw new Exception('Invalid Category');
    endswitch;*/
    if(file_exists($path."/".$misc['brochure']) && !empty($misc['brochure'])){
      $filesize = filesize($path."/".$misc['brochure']);
      if ($filesize < 1024000){
        //Use Kilo Bytes
        $filesize = round($filesize/1024,2);
        $this->t->set_var('size', "($filesize KB)");
      }else{
        //Use Mega Bytes
        $filesize = round($filesize/1048576,2);
        $this->t->set_var('size', "($filesize MB)");
      }//End If-else
    }//End If

    //Loop for assigning Misc Items
    reset($misc);
    for ($i=1; $i<=sizeOf($misc); $i ++){
      $this->t -> set_var(key($misc), current($misc));
      next($misc);
    }
    //Assign the addition Context Title variable (Uses the product title config field)
    $this->t -> set_var("title_context", $misc['product_title']);
    
    //Loop for assigning Raw Items
    if(is_array($raw)&& $raw[1]){
        $this->t -> set_var('raw', $raw[2]);
    }//End If
    
    //Assign Other Required Variables (Product category and round the product
    //page code down to the product summary page code for the back link)
    $this->t -> set_var(array('pcat' => ucwords($this->pcat),
                              'pcat_code' => round($this->page, -2),
                              'pc' => $this->page));

    //Loop for assigning features
    if (isset($features)){
      try{
        if (file_exists("templates/products/$this->pcat/features.html")){
          $this->t -> set_file(array('feature_list' => "products/$this->pcat/features.html"));
        }elseif (file_exists("templates/products/features.html")){
          $this->t -> set_file(array('feature_list' => "products/features.html"));
        }else{
          throw new Exception('Missing Features Template file');
        }//End If-Else-If
      }catch (Exception $ex){
        throw $ex;
      }//End Try-Catch
      $this->t -> set_block('feature_list', 'feature', 'features');
      for ($i=1; $i<=sizeOf($features); $i ++){
        $this->t -> set_var('feature_item', $features[$i]);
        $this->t -> parse('features', 'feature', true);
      }//End For
    }//End If
    //Loop for assigning capacities
    if ($capacities[1]){
      try{
        if (file_exists("templates/products/$this->pcat/capacities.html")){
          $this->t -> set_file(array('capacity_list' => "products/$this->pcat/capacities.html"));
        }elseif (file_exists("templates/products/capacities.html")){
          $this->t -> set_file(array('capacity_list' => "products/capacities.html"));
        }else{
          throw new Exception('Missing Capacities Template file');
        }//End If-Else-If
      }catch (Exception $ex){
        throw $ex;
      }//End Try-Catch
      $this->t -> set_block('capacity_list', 'capacity', 'capacities');
      for ($i=2; $i<=sizeOf($capacities); $i ++){
        $cap = explode(',',$capacities[$i]);
        $this->t -> set_var(array('capacity_item1' => $cap[0],
                                  'capacity_item2' => $cap[1]));
        $this->t -> parse('capacities', 'capacity', true);
      }//End For
    }//End If
    //Loop for assigning option
    if ($options[1]){
      try{
        if (file_exists("templates/products/$this->pcat/options.html")){
          $this->t -> set_file(array('option_list' => "products/$this->pcat/options.html"));
        }elseif (file_exists("templates/products/options.html")){
          $this->t -> set_file(array('option_list' => "products/options.html"));
        }else{
          throw new Exception('Missing Options Template file');
        }//End If-Else-If
      }catch (Exception $ex){
        throw $ex;
      }//End Try-Catch
      $this->t -> set_block('option_list', 'option', 'options');
      for ($i=2; $i<=sizeOf($options); $i ++){
        $option_item = '';
        $opt = explode(',', $options[$i]);
        if (sizeof($opt)<2){
          $option_item = "<td colspan='2'>".$opt[0]."</td>";
          $this->t -> set_var(array('option_item' => $option_item));
        }else{
          $option_item = "<td>".$opt[0]."</td><td>".$opt[1]."</td>";
          $this->t -> set_var(array('option_item' => $option_item));
        }//End if-else
        $this->t -> parse('options', 'option', true);
      }//End For
    }//End If
    //Loop for assigning applications
    if ($applications[1]){
      try{
        if (file_exists("templates/products/$this->pcat/applications.html")){
          $this->t -> set_file(array('app_list' => "products/$this->pcat/applications.html"));
        }elseif (file_exists("templates/products/applications.html")){
          $this->t -> set_file(array('app_list' => "products/applications.html"));
        }else{
          throw new Exception('Missing Applications Template file');
        }//End If-Else-If
      }catch (Exception $ex){
        throw $ex;
      }//End Try-Catch
      $this->t -> set_block('app_list', 'application', 'applications');
      for ($i=2; $i<=sizeOf($applications); $i ++){
        $this->t -> set_var(array('app_item1' => $applications[$i]));
        $this->t -> parse('applications', 'application', true);
      }//End For
    }//End If
    //Loop for assigning Addittional Information
    if ($info[1]){
      try{
        if (file_exists("templates/products/$this->pcat/info.html")){
          $this->t -> set_file(array('info_list' => "products/$this->pcat/info.html"));
        }elseif (file_exists("templates/products/info.html")){
          $this->t -> set_file(array('info_list' => "products/info.html"));
        }else{
          throw new Exception('Missing Information Template file');
        }//End If-Else-If
      }catch (Exception $ex){
        throw $ex;
      }//End Try-Catch
      $this->t -> set_block('info_list', 'information', 'informations');
      for ($i=2; $i<=sizeOf($info); $i ++){
        $this->t -> set_var(array('info_item1' => $info[$i]));
        $this->t -> parse('informations', 'information', true);
      }//End For
    }//End If
    if(isset($feat_logos)){
      $this->feature_logos($feat_logos);
    }
  }//End function from_file
  
  function feature_logos($feat_logos){
    if ($feat_logos != null || $feat_logos['enabled']){
      $this->t->set_file('feature_logos', 'products/feature_logos.html');
      reset($feat_logos);
      $logos = each($feat_logos);
      if ($logos['value']){
//        echo "Feature Logos are Enabled!</br>";
        $this->t -> set_block('feature_logos', 'feat_logos', 'feat_logo');
        $this->t -> set_block('feat_logos', 'feat_row', 'feature_rows');
        $assigned = false;
        while($logos){
//          echo "Entered While Loop....</br>";  
          for($j=0;$j<7;$j){    
            if(!$logos){
//              echo "Reached end of Array!!!</br>";
              break;
            }else{
//            echo "entered for loop....$j</br>";
              $logos = each($feat_logos);
//            echo "Testing Value.....</br>";
              if($logos['value']){
//                echo "Assigning <b>".$logos['key']."</b> logo to template engine...</br>";
                $this->t ->set_var(array('logo' => $logos['key'].".jpg",
                                          'title' => $logos['key']));
                $this->t -> parse('feature_rows', 'feat_row', true);
                $assigned=true;
                $j++;
              }//End If
            }//End If-else
          }//End For
//          echo "Exit For loop!</br>";
          if ($assigned){
            $this->t -> parse('feat_logo', 'feat_logos', true);
            $this->t ->set_var('feature_rows', '');
          }//End If
          $assigned=false;
        }//End while
//      echo "Exit While Loop!</br>";
      }else{
        $logos = null;
//        echo "Feature logos for this products are diabled...</br>";
      }//End If-Else
    }//End If
  }//End Function feature_logos
}//End Class disp_product

/*
while($logos){
//        echo "Entered While Loop....</br>";  
        for($j=0;$j<8;$j++){    
//          echo "entered for loop....$j</br>";
          $logos = each($feat_logos);
          if(!$logos){
//            echo "Reached end of Array!!!</br>";
            break;
          }else{
//            echo "Testing Value.....</br>";
            if($logos['value']){
//              echo "Assigning <b>".$logos['key']."</b> logo to template engine...</br>";
              $this->t ->set_var('logo', $logos['key'].".jpg");
              $this->t -> parse('feature_rows', 'feat_row', true);
            }//End If
          }//End If-else
        }//End For
//        echo "Exit For loop!</br>";
      $this->t -> parse('feat_logo', 'feat_logos', true);
      $this->t ->set_var('feature_rows', '');
      }//End while
*/
