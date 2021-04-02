<?php

use Engage\EngageClient;
use PHPUnit\Framework\TestCase;

class EngageClientFunctionalTest extends TestCase
{
    protected $key;
    protected $secret;
    protected $client;

    protected $id = '1234';
    protected $email = 'test@email.com';
    protected $users;
    protected $okStatus = ['status' => 'ok'];

    // @skip
    protected function setUp()
    {
        $this->key = $_SERVER['ENGAGE_KEY'];
        $this->secret = $_SERVER['ENGAGE_SECRET'];
        $skip = $_SERVER['ENGAGE_PHP_SKIP_INTEGRATION'];

        if ($skip == '') {
            $this->markTestSkipped(
                'Set ENGAGE_KEY and ENGAGE_SECRET environment variables to enable integration testing'
            );
        }

        $this->client = new EngageClient($this->key, $this->secret);
        $this->users = $this->client->users;
    }

    public function testIdentifyUsers()
    {
        $resp = $this->users->identify([
            'id' => $this->id,
            'email' => $this->email,
        ]);

        $this->assertArraySubset(['uid' => $this->id], json_decode($resp, true));
    }

    public function testAddAttribute()
    {
        $resp = $this->users->addAttribute($this->id, [
            'first_name' => 'Opeyemi',
            'active' => true,
            'created_at' => '2021-02-08',
        ]);

        $this->assertArraySubset(['uid' => $this->id], json_decode($resp, true));
    }

    public function testTrackUserEvent()
    {
        $resp = $this->users->track($this->id, [
            'event' => 'Withdrawal',
            'value' => 190.23,
        ]);

        $this->assertSame($this->okStatus, json_decode($resp, true));
    }

    public function testTrackWithExtraPayload()
    {
        $resp = $this->users->track($this->id, [
            'event' => 'Add to cart',
            'timestamp' => '2020-05-30T09:30:10Z',
            'properties' => [
                'product' => 'T123',
                'currency' => 'USD',
                'amount' => 12.99,
            ],
        ]);

        $this->assertSame($this->okStatus, json_decode($resp, true));
    }
}
