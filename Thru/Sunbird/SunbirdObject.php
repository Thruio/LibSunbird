<?php
namespace Thru\Sunbird;

class SunbirdObject{
  public function __construct($raw_object = null){
    if($raw_object !== null){
      foreach((array) $raw_object as $key => $value){
        $this->$key = $value;
      }
    }
  }
}