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

  public function put(){
    $request = new SunbirdRequest();
    $response = $request->put($this);
    die(":O");
  }

  public function get_type(){
    return Inflect::pluralize(strtolower(str_replace("Thru\\Sunbird\\Sunbird","", get_called_class())));
  }

}