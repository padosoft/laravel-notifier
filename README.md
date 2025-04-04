# Laravel Package for beautifull JS notification.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/padosoft/laravel-notifier.svg?style=flat-square)](https://packagist.org/packages/padosoft/laravel-notifier)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Quality Score](https://img.shields.io/scrutinizer/g/padosoft/laravel-notifier.svg?style=flat-square)](https://scrutinizer-ci.com/g/padosoft/laravel-notifier)
[![Total Downloads](https://img.shields.io/packagist/dt/padosoft/laravel-notifier.svg?style=flat-square)](https://packagist.org/packages/padosoft/laravel-notifier)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/3a39da13-6f5f-4041-9700-81e8c1f2e387.svg?style=flat-square)](https://insight.sensiolabs.com/projects/3a39da13-6f5f-4041-9700-81e8c1f2e387)

This package provides a wrapper of commands to Padosoft JS notifier, to display beautifull notifications with js plugin. 

![screenshoot](https://raw.githubusercontent.com/padosoft/laravel-notifier/master/resources/img/laravel-notifier.png)

Table of Contents
=================

   * [Laravel Package to notify with beautifull JS notifier.](#laravel-package-to-notify-with-beautiful-js-notifier)
      * [SCREENSHOOTS](#screenshoots)
      * [Requires](#requires)
      * [Installation](#installation)
      * [USAGE](#usage)
         * [EXAMPLE:](#example)
      * [Change log](#change-log)
      * [Testing](#testing)
      * [Contributing](#contributing)
      * [Security](#security)
      * [Credits](#credits)
      * [About Padosoft](#about-padosoft)
      * [License](#license)

##Requires
  
- "php" : ">=7.0.0",
- "illuminate/support": "^5.0|^6.0|^7.0",
- "illuminate/session": "^5.0|^6.0|^7.0"
  
## Installation

You can install the package via composer:
``` bash
$ composer require padosoft/laravel-notifier
```

### FOR LARAVEL 5.6+
No additional steps required because the service provider use new L5.5+ autodiscovery feature.

### FOR LARAVEL <=5.5
You must install this service provider.

``` php
// config/app.php
'provider' => [
    ...
    Padosoft\Laravel\Notification\Notifier\NotifierServiceProvider::class,
    ...
];
```

## USAGE

Call one of these methods in your controllers to insert a notification:

- `Notify::warning($message, $onlyNextRequest = false, $options = [])` - add a warning notification
- `Notify::error($message, $onlyNextRequest = false, $options = [])` - add an error notification
- `Notify::info($message, $onlyNextRequest = false, $options = [])` - add an info notification
- `Notify::success($message, $onlyNextRequest = false, $options = [])` - add a success notification
- `Notify::add($theme, $timeout, $type: warning|error|info|success, $layout, $text, $sounds = null, $soundsVolume = null)` - add a notification
  - `Notify::clear()` - clear all current notification

If you need to show the notification only if a particular condition is true, you can use these methods:

- `Notify::ifWarning($condition, $message, $onlyNextRequest = false, $options = [])` - add a warning notification if $condition is true
- `Notify::ifError($condition, $message, $onlyNextRequest = false, $options = [])` - add an error notification if $condition is true
- `Notify::ifInfo($condition, $message, $onlyNextRequest = false, $options = [])` - add an info notification if $condition is true
- `Notify::ifSuccess($condition, $message, $onlyNextRequest = false, $options = [])` - add a success notification if $condition is true

Example:
instead of use this:

```php
if($condition){
    Notify::success('You have an email!');
}
```

you can use this:

```php
Notify::IfSuccess($condition, 'You have an email!');
```

```php
{!! notify() !!}

```
### EXAMPLE:

```php
Notify::info('You have an email!');
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email instead of using the issue tracker.

## Credits
- [Lorenzo Padovani](https://github.com/lopadova)
- [All Contributors](../../contributors)

## About Padosoft
Padosoft (https://www.padosoft.com) is a software house based in Florence, Italy. Specialized in E-commerce and web sites.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
