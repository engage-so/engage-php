<?php
namespace Engage\Resources;

class Api
{
  const ROOT = 'https://api.engage.so';

  private function request() {
  }
  
  public function put($url, $params) {
    var_dump($params, $url);
  }
  
  public function post($url, $params) {
    var_dump($params, $url);
  }
}
?>