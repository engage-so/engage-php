<?php

namespace Engage\Resources;

use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;

class Api
{
    /**
     * The Symfony HTTP client instance.
     *
     * @var \Symfony\Component\HttpClient\HttpClient
     */
    protected $client;

    /**
     * The Engage API Key.
     *
     * @var string
     */
    protected $key;

    /**
     * The Engage API Secret Key.
     *
     * @var string
     */
    protected $secret;

    /**
     * Engage API endpoint.
     *
     * @var string
     */
    const ROOT = 'https://api.engage.so';

    /**
     * Instantiate a new API instance.
     *
     * @return void
     */
    public function __construct()
    {
        // initialize http client
        $this->client = HttpClient::create();
    }

    public function makeRequest($method, $url, $params)
    {
        $endpoint = self::ROOT.$url;
        $payload = $this->preparePayload($params);
        try {
            $response = $this->client->request($method, $endpoint, $payload);
            $code = $response->getStatusCode();

            if ($code > 300) {
                return json_decode($response->getContent(false));
            }

            return $response->getContent();
        } catch (ClientException $e) {
            throw new \Exception('API Connection error '.$e->getMessage());
        }
    }

    public function put($url, $params)
    {
        $response = $this->makeRequest('PUT', $url, $params);

        return $response;
    }

    public function post($url, $params)
    {
        $response = $this->makeRequest('POST', $url, $params);

        return $response;
    }

    public function preparePayload($params)
    {
        return [
          'json' => $params,
          'headers' => [
            'User-Agent' => 'Engage.so PHP Client',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.base64_encode(sprintf('%s:%s', $this->key, $this->secret)),
          ],
      ];
    }

    // allow client be mocked during testing
    public function setClient($client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setCredentials($clientObj)
    {
        $this->key = $clientObj->key;
        $this->secret = $clientObj->secret;
    }
}
