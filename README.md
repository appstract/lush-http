# Lush Http

[![Latest Version on Packagist](https://img.shields.io/packagist/v/appstract/lush-http.svg?style=flat-square)](https://packagist.org/packages/appstract/lush-http)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/appstract/lush-http/master.svg?style=flat-square)](https://travis-ci.org/appstract/lush-http)
[![Total Downloads](https://img.shields.io/packagist/dt/appstract/lush-http.svg?style=flat-square)](https://packagist.org/packages/appstract/lush-http)

## Smart Http Client for PHP.

Lush is a small Http client that focuses on the most basic use cases. It also tries to format the responses to objects, so you don't have to. 
This makes Lush great for API requests.

Lush can be installed in any PHP application through composer, but has some extras when used in combination with Laravel.

### Wip

This package is still in development, you are free to try it, without any warranty.

Todo
- Support cookies
- Docs

## Requirements
- PHP 5.6+
- php_curl

## Installation

You can install the package via composer:

``` bash
composer require appstract/lush-http
```

## Usage
The most basic usage:

``` php
    // Create a new instance
    $lush = new Lush();
    
    // Make a requests
    $response = $lush->url('http://example.com', ['id' => 3])
                        ->headers(['X-some-header' => 'some-value']
                        ->get(); // Method (get, post, put, etc.)
    
    // Response returns JSON or XML?
    // then you can directly access it's properties
    echo $response->name;
```

Link to the docs will be added soon!

## Contributing

Contributions are welcome, [thanks to y'all](https://github.com/appstract/lush-http/graphs/contributors) :)

## About Appstract

Appstract is a small team from The Netherlands. We create (open source) tools for webdevelopment and write about related subjects on [Medium](https://medium.com/appstract). You can [follow us on Twitter](https://twitter.com/teamappstract), [buy us a beer](https://www.paypal.me/teamappstract/10) or [support us on Patreon](https://www.patreon.com/appstract).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
