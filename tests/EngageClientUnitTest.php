<?php

use Engage\EngageClient;
use Engage\Resources\Users;
use PHPUnit\Framework\TestCase;

class EngageClientUnitTest extends TestCase
{
  protected $client;
  protected $key = "key";
  protected $secret = "secret";
  protected $id = '1071dfc';

  // @group ClientInitialization
  public function testClientInstantationIsSuccessful()
  {
    $client = new EngageClient($this->key, $this->secret);

    $this->assertInstanceOf(EngageClient::class, $client);
  }

  // @group ClientInitialization
  public function testShouldThrowIfNoParams()
  {
    $this->expectException(\InvalidArgumentException::class);

    $client = new EngageClient();
  }

  // @group ClientInitialization
  public function testShouldThrowIfEmptyArray()
  {
    $this->expectException(\InvalidArgumentException::class);

    $client = new EngageClient([]);
  }

  public function testResourceFactoryIsRetrievable()
  {
    $client = new EngageClient($this->key, $this->secret);
    $userResource = $client->users;

    $this->assertInstanceOf(Users::class, $userResource);
  }

  // @group Identify
  public function testIdentifyNoParameterPassed()
  {
    $this->expectException(\InvalidArgumentException::class);

    $client = new EngageClient($this->key, $this->secret);
    $data = $client->users->identify();
  }

  // @group Identify
  public function testIdentifyEmptyArrayPassed()
  {
    $this->expectException(\InvalidArgumentException::class);

    $client = new EngageClient($this->key, $this->secret);
    $data = $client->users->identify([]);
  }

  // @group Identify
  public function testIdentifyThrowIfNoEmail()
  {
    $this->expectException(\InvalidArgumentException::class);

    $client = new EngageClient($this->key, $this->secret);
    $data = $client->users->identify(['id' => $this->id]);
  }

  // @group Identify
  public function testIdentifyThrowIfInvalidEmail()
  {
    $this->expectException(\InvalidArgumentException::class);

    $client = new EngageClient($this->key, $this->secret);
    $data = $client->users->identify(['id' => $this->id, 'email' => 'invalid']);
  }

  // @group AddAttribute
  public function testAddAttributeThrowNoParameters()
  {
    $this->expectException(\ArgumentCountError::class);

    $client = new EngageClient($this->key, $this->secret);
    $data = $client->users->addAttribute();
  }

  // @group AddAttribute
  public function testAddAttributeThrowNoDataAtrrPassed()
  {
    $this->expectException(\InvalidArgumentException::class);

    $client = new EngageClient($this->key, $this->secret);
    $data = $client->users->addAttribute($this->id, null);
  }

  // @group AddAttribute
  public function testAddAttributeThrowEmptyDataPassed()
  {
    $this->expectException(\InvalidArgumentException::class);

    $client = new EngageClient($this->key, $this->secret);
    $data = $client->users->addAttribute($this->id, []);
  }

  // @group Track
  public function testTrackShouldThrowNoParameter()
  {
    $this->expectException(\ArgumentCountError::class);

    $client = new EngageClient($this->key, $this->secret);
    $data = $client->users->track();
  }

  // @group Track
  public function testTrackThrowNoDataAtrrPassed()
  {
    $this->expectException(\InvalidArgumentException::class);

    $client = new EngageClient($this->key, $this->secret);
    $data = $client->users->track($this->id, null);
  }
}
