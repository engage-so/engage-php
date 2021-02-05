<?php
namespace Engage;

class EngageClient
{

  private $resourceFactory;
  public $key;
  public $secret;

  public function __construct($key = null, $secret = null)
  {
    if (!$key) {
      throw new \InvalidArgumentException('API key not added.');
    }

    if (!$secret) {
      throw new \InvalidArgumentException('API secret key not added.');
    }

    $this->key = $key;
    $this->secret = $secret;
  }

  public function __get($name)
  {
    if (null === $this->resourceFactory) {
      $this->resourceFactory = new \Engage\ResourceFactory($this);
    }

    return $this->resourceFactory->__get($name);
  }
}
?>
