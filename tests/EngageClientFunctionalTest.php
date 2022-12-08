<?php

use Engage\EngageClient;
use PHPUnit\Framework\TestCase;

class EngageClientFunctionalTest extends TestCase
{
    protected $key;
    protected $secret;
    protected $client;

    protected $id = '1234';
    protected $gid = 'abcd';
    protected $email = 'test@email.com';
    protected $users;
    protected $okStatus = ['status' => 'ok'];

    protected function setUp(): void
    {
        $this->key = $_SERVER['ENGAGE_KEY'];
        $this->secret = $_SERVER['ENGAGE_SECRET'];

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

    public function testCreateAccount()
    {
        $resp = $this->users->identify([
            'id' => $this->gid,
            'email' => $this->email,
            'is_account' => true
        ]);

        $this->assertArraySubset(['uid' => $this->gid, 'is_account' => true], json_decode($resp, true));
    }

    public function testAddToAccount()
    {
        $resp = $this->users->addToAccount($this->id, $this->gid);
        $sub = json_decode($resp, true);

        $this->assertSame([['id' => $this->gid, 'role' => null]], $sub['accounts']);
    }

    public function testChangeAccountRole()
    {
        $role = 'admin';
        $resp = $this->users->changeAccountRole($this->id, $this->gid, $role);
        $sub = json_decode($resp, true);

        $this->assertSame([['id' => $this->gid, 'role' => $role]], $sub['accounts']);
    }

    public function testRemoveFromAccount()
    {
        $resp = $this->users->removeFromAccount($this->id, $this->gid);
        $sub = json_decode($resp, true);

        $this->assertSame([], $sub['accounts']);
    }

    public function testConvertToCustomer()
    {
        $resp = $this->users->convertToCustomer($this->gid);
        $sub = json_decode($resp, true);
        $this->assertSame($sub['is_account'], false);
    }

    public function testConvertToAccount()
    {
        $resp = $this->users->convertToAccount($this->gid);
        $sub = json_decode($resp, true);
        $this->assertSame($sub['is_account'], true);
    }
}
