<?php

namespace Engage\Resources;

class Users extends Api
{
    public function __construct($clientObj)
    {
        parent::__construct();
        $this->setCredentials($clientObj);
    }

    public function identify($o = null)
    {
        if (!$o) {
            throw new \InvalidArgumentException('You need to pass an object with at least an id and email.');
        }
        if (!$o['id']) {
            throw new \InvalidArgumentException('ID missing.');
        }
        if ($o['email'] && !preg_match('/^\S+@\S+$/', $o['email'])) {
            throw new \InvalidArgumentException('Email missing or invalid.');
        }
        $allowed = ['id', 'email', 'is_account', 'device_token', 'device_platform', 'number', 'created_at', 'first_name', 'last_name'];
        $params = ['meta' => []];
        foreach ($o as $k => $v) {
            if (in_array($k, $allowed)) {
                $params[$k] = $v;
            } else {
                $params['meta'][$k] = $v;
            }
        }

        if (!count($params['meta'])) {
            unset($params['meta']);
        }

        return $this->put("/users/{$o['id']}", $params);
    }

    public function addAttribute($uid, $data)
    {
        if (!$uid) {
            throw new \InvalidArgumentException('User id missing.');
        }
        if (!$data || !is_array($data)) {
            throw new \InvalidArgumentException('Attributes missing.');
        }
        if (!count($data)) {
            throw new \InvalidArgumentException('No attributes provided.');
        }
        $notMeta = ['email', 'device_token', 'is_account', 'device_platform', 'number', 'created_at', 'first_name', 'last_name'];
        $params = ['meta' => []];
        foreach ($data as $k => $v) {
            if (in_array($k, $notMeta)) {
                $params[$k] = $v;
            } else {
                $params['meta'][$k] = $v;
            }
        }

        if (!count($params['meta'])) {
            unset($params['meta']);
        }

        return $this->put("/users/$uid", $params);
    }

    public function track($uid, $data)
    {
        if (!$uid) {
            throw new \InvalidArgumentException('User id missing.');
        }
        if (!$data) {
            throw new \InvalidArgumentException('Data missing.');
        }
        if (is_string($data)) {
            $data = [
              'event' => $data,
              'value' => true
            ];
        } else {
            if (!count($data)) {
                throw new \InvalidArgumentException('No data provided.');
            }
        }

        return $this->post("/users/$uid/events", $data);
    }

    public function addToAccount($uid, $gid, $role = null)
    {
        if (!$uid) {
            throw new \InvalidArgumentException('User id missing.');
        }
        if (!$gid) {
            throw new \InvalidArgumentException('Account id missing.');
        }
        if ($role && !is_string($data)) {
            throw new \InvalidArgumentException('Role should be a text.');
        }
        $g = ['id' => $gid];
        if ($role) {
            $g['role'] = $role;
        }

        return $this->post("/users/$uid/accounts", ['accounts' => [$g]]);
    }

    public function removeFromAccount($uid, $gid)
    {
        if (!$uid) {
            throw new \InvalidArgumentException('User id missing.');
        }
        if (!$gid) {
            throw new \InvalidArgumentException('Account id missing.');
        }

        return $this->delete("/users/$uid/accounts/$gid");
    }

    public function changeAccountRole($uid, $gid, $role)
    {
        if (!$uid) {
            throw new \InvalidArgumentException('User id missing.');
        }
        if (!$gid) {
            throw new \InvalidArgumentException('Account id missing.');
        }
        if (!$role) {
            throw new \InvalidArgumentException('New role missing.');
        }

        return $this->put("/users/$uid/accounts/$gid", ['role' => $role]);
    }

    public function convertToCustomer($uid)
    {
        if (!$uid) {
            throw new \InvalidArgumentException('User id missing.');
        }

        return $this->post("/users/$uid/convert", ['type' => 'customer']);
    }

    public function convertToAccount($uid)
    {
        if (!$uid) {
            throw new \InvalidArgumentException('User id missing.');
        }

        return $this->post("/users/$uid/convert", ['type' => 'account']);
    }
}
