# Germania KG · Mailer

**Pimple Service Provider for email and SwiftMailer services.**


## Installation

```bash
$ composer require germania-kg/mailer
```

Alternatively, add this package directly to your *composer.json:*

```json
"require": {
    "germania-kg/mailer": "^1.0"
}
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

$result = $mailer("The subject", "<p>The mailtext</p>", "admin@test.com");
```



## Development

```bash
$ git clone https://github.com/GermaniaKG/Mailer.git
$ cd Mailer
$ composer install
```



## Unit tests

Either copy `phpunit.xml.dist` to `phpunit.xml` and adapt to your needs, or leave as is. 
Run [PhpUnit](https://phpunit.de/) like this:

```bash
$ vendor/bin/phpunit
```
