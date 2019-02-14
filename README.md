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

// Use with Pimple or Slim3:
$dic->register( new MailerServiceProvider );

$dic->extend('Mailer.Config', function($default_config, $dic) {
  $mail_file = ...
  $custom_config = Yaml::parseFile( $mail_file );
  return array_merge($default_config, $custom_config);
});
```

## Usage 

```php
<?php
$mailer = $dic['Mailer.Callable'];


$result = $mailer("The subject", "<p>The mailtext</p>");

# Override receipient address
$result = $mailer("The subject", "<p>The mailtext</p>", "admin@test.com");
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
