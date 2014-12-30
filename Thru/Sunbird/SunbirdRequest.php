<?php
namespace Thru\Sunbird;

use Thru\Inflection\Inflect;

class SunbirdRequest{
  private $curl;

  static public function factory(){
    $class = get_called_class();
    return new $class;
  }

  public function __construct(){
    $this->curl = new SunbirdCurl();
  }

  private function get_type(){
    return strtolower(str_replace("Thru\\Sunbird\\Sunbird","", get_called_class()));
  }

  private function get_endpoint($entity = null){
    if($entity === null){
      $entity = $this;
    }
    return SUNBIRD_HOST . "/" . SUNBIRD_API_VERSION . "/" . SUNBIRD_API_KEY . "/" . $entity->get_type();
  }

  public function post($object){
    $response = $this->request('post', $object);
    return $response;
  }

  public function get_list() {
    $response = $this->request('get');
    $list = ucfirst($this->get_type());
    $output = [];
    $child_class = "Thru\\Sunbird\\" . Inflect::singularize($list);
    $child_class_exists = class_exists($child_class);

    foreach($response->$list as $k => $element){
      if($child_class_exists){
        $output[$k] = new $child_class($element);

      }else{
        $output[$k] = $element;
      }
    }
    return $output;
  }

  private function request($mode = 'get', $data = null){
    $begin = microtime(true);
    $endpoint = $this->get_endpoint($data);
    if($data){
      $response = $this->curl->$mode($endpoint, $data);
    }else {
      $response = $this->curl->$mode($endpoint);
    }

    if($this->curl->get_status() != 200){
      throw new SunbirdException("Cannot talk to Sunbird, response was {$this->curl->get_status()} while requesting {$endpoint}");
    }
    $response_decoded = json_decode($response);
    if($response_decoded == false){
      if(strlen($response) > 1024){
        throw new SunbirdException("Cannot decode Sunbird Response, and it was over 1024 bytes, so its probably garbage.");
      }else {
        throw new SunbirdException("Cannot decode Sunbird Response: {$response}");
      }
    }
    $end = microtime(true);
    $time_to_execute = $end - $begin;
    SunbirdRequestLog::log($endpoint, $response_decoded, $time_to_execute);
    return $response_decoded;
  }
}
