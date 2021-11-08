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
        $allowed = ['id', 'email', 'device_token', 'device_platform', 'number', 'created_at', 'first_name', 'last_name'];
        $params = ['meta' => []];
        foreach ($o as $k => $v) {
            if (in_array($k, $allowed)) {
                $params[$k] = $v;
            } else {
                $params['meta'][$k] = $v;
            }
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
        $notMeta = ['email', 'device_token', 'device_platform', 'number', 'created_at', 'first_name', 'last_name'];
        $params = ['meta' => []];
        foreach ($data as $k => $v) {
            if (in_array($k, $notMeta)) {
                $params[$k] = $v;
            } else {
                $params['meta'][$k] = $v;
            }
        }

        return $this->put("/users/$uid", $params);
    }

    public function track($uid, $data)
    {
        if (!$uid) {
            throw new \InvalidArgumentException('User id missing');
        }
        if (!$data) {
            throw new \InvalidArgumentException('Attributes missing');
        }
        if (is_string($data)) {
            $data = [
              'event' => data,
              'value' => true,
            ];
        } else {
            if (!count($data)) {
                throw new \InvalidArgumentException('No attributes provided');
            }
        }

        return $this->post("/users/$uid/events", $data);
    }
}
