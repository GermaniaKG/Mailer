# Germania KG · Mailer

**Pimple Service Provider for email and SwiftMailer services.**

[![Packagist](https://img.shields.io/packagist/v/germania-kg/mailer.svg?style=flat)](https://packagist.org/packages/germania-kg/mailer)
[![PHP version](https://img.shields.io/packagist/php-v/germania-kg/mailer.svg)](https://packagist.org/packages/germania-kg/mailer)
[![Build Status](https://img.shields.io/travis/GermaniaKG/Mailer.svg?label=Travis%20CI)](https://travis-ci.org/GermaniaKG/Mailer)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GermaniaKG/Mailer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/Mailer/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/GermaniaKG/Mailer/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/Mailer/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/GermaniaKG/Mailer/badges/build.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/Mailer/build-status/master)


## Installation

```bash
$ composer require germania-kg/mailer
```



## Registering


```php
<?php
use Germania\Mailer\MailerServiceProvider;

$dic = new \Pimple\Container;
$dic->register( new MailerServiceProvider );
```



## Configuration

The configuration is an array with typical mail configration fields, each of them empty.

You can retrieve the configuration from the Pimple container like this:

```php
$dic->register( new MailerServiceProvider );
$config = $dic['Mailer.Config'];
print_r($config);
```

```text
(
    [smtp] =>
    [port] =>
    [ssl] =>
    [user] => 
    [pass] =>
    [from_name] =>
    [from_mail] =>
    [to] =>
    [subject] =>
)
```

### Configure on Instantiation

Pass a custom configuration to the *MailerServiceProvider* constructor. Register it to Pimple container as usual.

```php
$mailer_service_provider = new MailerServiceProvider([
  'from_name' => 'John Doe',
  'from_mail' => 'me@test.com'
]);

$dic->register( $mailer_service_provider );
$config = $dic['Mailer.Config'];
print_r($config);
```

```text
(
    [smtp] =>
    [port] =>
    [ssl] => 
    [user] => 
    [pass] =>
    [from_name] => John Doe
    [from_mail] => me@test.com
    [to] =>
    [subject] =>
)
```

### Configure at Runtime

Two ways:

- After registering the ServiceProvider to Pimple (i.e. extending the `Mailer.Config` service definition) 
- Before registering, *pre-defining* the configuration in a `Mailer.Config` service definition

#### After registering with Pimple

```php
$dic->register( new MailerServiceProvider() );
$dic->extend('Mailer.Config', function($default_config, $dic) {
  $new_config = array_merge($default_config, [
    'from_name' => 'John Doe',
    'from_mail' => 'me@test.com'
  ]);
  return $new_config;
});
```

#### Before registering with Pimple

```php
$dic['Mailer.Config'] = function($dic) {
  return array(
    'from_name' => 'John Doe',
    'from_mail' => 'me@test.com'
  );
};

$dic->register( new MailerServiceProvider() );
```





## Usage

**Setup MailerServiceProvider:**

```php
$dic->register( new MailerServiceProvider([
    'from_name' => 'John Doe',
    'from_mail' => 'me@test.com',
    'to'        => 'admin@test.com',
  	'subject'   => 'Default subject'
]));
```



### SwiftMailerCallable

```php
<?php
use Germania\SwiftMailerCallable\SwiftMailerCallable;

// Grab the Mailer callable
$mailer = $dic[SwiftMailerCallable::class];
// DEPRECATED service name: 
$mailer = $dic['Mailer.Callable'];

// Send with subject and mail body
$result = $mailer("The subject", "<p>The mailtext</p>");

# Override receipient address
# previosuly set in Mailer.Config
$result = $mailer("The subject", "<p>The mailtext</p>", "admin@test.com");
```



### SwiftMailer

See https://swiftmailer.symfony.com/docs/introduction.html

```php
<?php
use Germania\Mailer\SwiftMessageFactory;
use \Swift_Mailer;

// Grab Swiftmailer
$swift_mailer = $dic[Swift_Mailer::class];  

// Setup message
$message_factory = $dic[SwiftMessageFactory::class];
$message = $message_factory->createMessage();
$message->setBody("This is the mail body");

// This optional as we set it with Configuration
$message->setSubject( "Custom subject line" );

// Send message
$result = $swift_mailer->send( $message ));
```







## Development

```bash
$ git clone https://github.com/GermaniaKG/Mailer.git
$ cd Mailer
$ composer install
```



## Unit tests

Either copy `phpunit.xml.dist` to `phpunit.xml` and adapt to your needs, or leave as is. Run [PhpUnit](https://phpunit.de/) test or composer scripts like this:

```bash
$ composer test
# or
$ vendor/bin/phpunit
```
