# Engage PHP SDK

The Engage PHP SDK lets you capture user attributes and events on your site. You can later use this on Engage to create user segments for analytics, broadcast messages and automation messages.

## Getting Started

[Create an Engage account](https://engage.so/) to get your API key


## Installation

```ssh
composer require "engage/engage-php"
```

## Configuration

Initialise the SDK with your public and private key. Your keys are available in the settings page of your Engage dashboard.

```php
$engage = new \Engage\EngageClient($_SERVER['pub_key'], $_SERVER['pri_key']);
```

## Identifying users

You only need a unique identifier that represents the user on your platform to track their events and attributes on Engage. To correlate a proper profile to these tracked attributes and events, you can send the unique identifier and other properties to Engage. You only need to do this once per user, probably at user signup. 

```php
$engage->users->identify([
  'id' => 'u13345',
  'email' => 'dan@mail.app',
  'created_at' => '2020-05-30T09:30:10Z'
]);
```

`id` represents the unique identifier for the user on your platform. It is the only required parameter. You can send any other attribute you want e.g. `age`, `plan`. Here are the standard ones we use internally on the user profile:
- `first_name`
- `last_name`
- `email`
- `number` (with international dialing code without the +)
- `created_at` (represents when the user registered on your platform. If not added, Engage sets it to the current timestamp.)


## Update/add user attributes

If you need to add new attributes or update an existing attribute, you can use the `addAttribute` method. 

```php
$engage->users->addAttribute($userId, [
  'first_name' => 'Dan',
  'plan' => 'Premium'
]);
```

(You can also use `identify` to update or add new attributes.)

## Tracking user events and actions

You can track user events and actions in a couple of ways. 

Tracking an event with no value:

```php
$engage->users->track($userId, 'Delinquent');
```

Tracking an event with a value:

```php
$engage->users->track($userId, [
  'event' => 'New badge',
  'value' => 'gold',
  'timestamp' => '2020-05-30T09:30:10Z'
]);
```

`event` is the event you want to track. `value` is the value of the event. This can be a string, number or boolean. There is an optional `timestamp` parameter. If not included, Engage uses the current timestamp. The timestamp value must be a valid datetime string.

If you need to track more properties with the event, you can track it this way:

```php
$engage->users->track($userId, [
  'event' => 'Add to cart',
  'properties' => [
    'product' => 'T123',
    'currency' => 'USD',
    'amount' => 12.99
  ]
]);
```

## License

MIT