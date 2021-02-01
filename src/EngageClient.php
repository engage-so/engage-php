<?php
namespace Engage;

class EngageClient
{

  private $resourceFactory;
  
  public function __construct($key = null, $secret = null)
  {
    if (!$key) {
      throw new \InvalidArgumentException('API key not added.');
    }
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