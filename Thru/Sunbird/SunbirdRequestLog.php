<?php
namespace Thru\Sunbird;

class SunbirdRequestLog{
  private $log;
  private static $instance;

  private static function factory(){
    if(!self::$instance instanceof SunbirdRequestLog){
      self::$instance = new SunbirdRequestLog();
    }
    return self::$instance;
  }

  public static function log($request, $response, $time_to_execute){
    return self::factory()->add_log($request, $response, $time_to_execute);
  }

  public function add_log($request, $response, $time_to_execute){
    $this->log[] = ['request' => $request, 'response' => $response, 'time' => $time_to_execute];
  }
}
