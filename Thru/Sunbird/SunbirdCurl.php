<?php
namespace Thru\Sunbird;
class SunbirdCurl {
  private $headers;
  private $user_agent;
  private $compression;
  private $cookie_file;
  private $ssl_cert_authority;
  private $proxy;

  private $status;

  static private $instance;

  static public function get_instance($cookies = TRUE, $cookie = 'cookies.txt', $compression = 'gzip', $proxy = ''){
    if(!self::$instance instanceof SunbirdCurl){
      self::$instance = new SunbirdCurl($cookies, $cookie, $compression, $proxy);
    }
    self::$instance->reset();
    return self::$instance;
  }

  public function __construct($cookies = TRUE, $cookie = 'cookies.txt', $compression = 'gzip', $proxy = '') {
    $this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg, text/html, application/xhtml+xml, application/xml';
    $this->headers[] = 'Connection: Keep-Alive';
    $this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
    $this->user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)';
    $this->compression = $compression;
    $this->proxy = $proxy;
    $this->cookies = $cookies;
    if ($this->cookies == TRUE) {
      $this->cookie($cookie);
    }
  }

  public function cookie($cookie_file) {
    if (!file_exists($cookie_file)) {
      touch($cookie_file);
    }
    if (file_exists($cookie_file)) {
      $this->cookie_file = $cookie_file;
    } else {
      fopen($cookie_file, 'w') or $this->error(
        "The cookie file ($cookie_file) could not be opened. Make sure this directory has the correct permissions"
      );
      $this->cookie_file = $cookie_file;
      fclose($this->cookie_file);
    }
  }

  public function get($url) {
    $process = curl_init($url);
    curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($process, CURLOPT_HEADER, FALSE);
    curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
    if ($this->cookies == TRUE) {
      curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
    }
    if ($this->cookies == TRUE) {
      curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
    }
    if ($this->ssl_cert_authority){
      curl_setopt($process, CURLOPT_CAINFO, $this->ssl_cert_authority);
    }
    curl_setopt($process, CURLOPT_ENCODING, $this->compression);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    if ($this->proxy) {
      curl_setopt($process, CURLOPT_PROXY, $this->proxy);
    }
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
    $return = curl_exec($process);
    $this->status = curl_getinfo($process, CURLINFO_HTTP_CODE);
    curl_close($process);
    return $return;
  }

  public function put($url, $data) {
    $process = curl_init($url);
    curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($process, CURLOPT_HEADER, FALSE);
    curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
    if ($this->cookies == TRUE) {
      curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
    }
    if ($this->cookies == TRUE) {
      curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
    }
    if ($this->ssl_cert_authority){
      curl_setopt($process, CURLOPT_CAINFO, $this->ssl_cert_authority);
    }
    curl_setopt($process, CURLOPT_ENCODING, $this->compression);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    if ($this->proxy) {
      curl_setopt($process, CURLOPT_PROXY, $this->proxy);
    }
    curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($process, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($process, CURLOPT_POSTFIELDS,http_build_query($data));
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
    $return = curl_exec($process);
    $this->status = curl_getinfo($process, CURLINFO_HTTP_CODE);
    curl_close($process);
    return $return;
  }

  public function post($url, $data) {
    $process = curl_init($url);
    curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($process, CURLOPT_HEADER, FALSE);
    curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
    if ($this->cookies == TRUE) {
      curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
    }
    if ($this->cookies == TRUE) {
      curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
    }
    if ($this->ssl_cert_authority){
      curl_setopt($process, CURLOPT_CAINFO, $this->ssl_cert_authority);
    }
    curl_setopt($process, CURLOPT_ENCODING, $this->compression);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    if ($this->proxy) {
      curl_setopt($process, CURLOPT_PROXY, $this->proxy);
    }
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($process, CURLOPT_POST, TRUE);
    foreach($data as &$data_item){
      if($data_item === null){
        $data_item = 'NULL';
      }
    }
    curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($data));

    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
    $return = curl_exec($process);
    $this->status = curl_getinfo($process, CURLINFO_HTTP_CODE);
    curl_close($process);
    return $return;
  }

  public function post_body($url, $body){
    $process = curl_init($url);

    curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($process, CURLOPT_HEADER, FALSE);
    curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
    if ($this->cookies == TRUE) {
      curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
    }
    if ($this->cookies == TRUE) {
      curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
    }
    if ($this->ssl_cert_authority){
      curl_setopt($process, CURLOPT_CAINFO, $this->ssl_cert_authority);
    }
    curl_setopt($process, CURLOPT_ENCODING, $this->compression);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    if ($this->proxy) {
      curl_setopt($process, CURLOPT_PROXY, $this->proxy);
    }
    curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($process, CURLOPT_POSTFIELDS, $body);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($process, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($body)
    ));
    curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
    $return = curl_exec($process);
    $this->status = curl_getinfo($process, CURLINFO_HTTP_CODE);
    curl_close($process);
    return $return;
  }

  public function error($error) {
    echo "<div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div>";
    die;
  }

  public function get_status() {
    return $this->status;
  }

  public function reset(){
    $this->status = null;
    return $this;
  }

  public function set_ca_certificate($ca){
    $this->ssl_cert_authority = $ca;
  }
}