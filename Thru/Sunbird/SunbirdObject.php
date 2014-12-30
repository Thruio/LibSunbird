<?php
namespace Thru\Sunbird;
use Thru\Inflection\Inflect;

class SunbirdObject{
  public function __construct($raw_object = null){
    if($raw_object !== null){
      foreach((array) $raw_object as $key => $value){
        $this->$key = $value;
      }
    }
  }

  /**
   * @return SunbirdAsset
   */
  public function save(){
    $request = new SunbirdRequest();
    $response = $request->post($this);
    if($response->Status == "OKAY"){
      $asset = new SunbirdAsset($response->Asset);
      return $asset;
    }else{
      throw new SunbirdException("Could not save Object: " . $response->StatusReason);
    }
  }

  public function set_data($file){
    if(!file_exists($file)){
      throw new SunbirdException("Cannot find file {$file}.");
    }
    $this->data = file_get_contents($file);
    $this->filename = basename($file);
    return strlen($this->data);
  }

  public function get_type(){
    return Inflect::pluralize(strtolower(str_replace("Thru\\Sunbird\\Sunbird","", get_called_class())));
  }

}