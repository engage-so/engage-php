<?php

use PHPUnit\Framework\TestCase;
use Engage\Resources\Api;
use Engage\EngageClient;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class APITest extends TestCase
{
  protected $defaultAuth = "Basic a2V5OnNlY3JldA==";

  public function testApiHttpClientIsInitialized()
  {
    $api = new Api();

    $this->assertInstanceOf(CurlHttpClient::class, $api->getClient());
  }

  public function testPayloadContentsRequiredData()
  {
    $client = new EngageClient('key', 'secret');
    $api = new Api();
    $api->setCredentials($client);

    $data = [
      'email' => 'test@email.com',
      'meta' => []
    ];
    $payload = $api->preparePayload($data);

    $this->assertSame($payload['headers']['Authorization'], $this->defaultAuth);
    $this->assertSame($payload['headers']['Content-Type'], 'application/json');
    $this->assertSame($payload['json'], $data);
  }

  public function testCanMakeRequest()
  {
    $client = new EngageClient('key', 'secret');
    $api = new Api();
    $api->setCredentials($client);

    $data = [
      'email' => 'test@email.com',
      'meta' => []
    ];

    $clientStub = $this->getStubbedClient(['status' => 'ok']);
    $api->setClient($clientStub);
    $resp = $api->put("/users/1234", $data);

    $this->assertSame("ok", $resp);
  }

  public function testCanMakeFailingRequest()
  {
    $client = new EngageClient('key', 'secret');
    $api = new Api();
    $api->setCredentials($client);


    $data = ['email' => 'test@email.com', 'id' => '1234'];
    $response = new MockResponse([json_encode(['error' => 'Unauthorized'])], ['http_code' => 403]);
    $clientStub = new MockHttpClient($response);
    $api->setClient($clientStub);
    $resp = $api->post("/users/1234", $data);

    $this->assertSame("Unauthorized", $resp->error);

  }

  public function getStubbedClient($body)
  {
    $response = new MockResponse($body);
    $client = new MockHttpClient($response);

    return $client;
  }
}
