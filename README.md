# Speedy FTP Client

Speedy FTP client is a package component for simple communication with the ftp server written in php.

## Requirements

- \>= php 8.0

## Installation

```sh
$ composer require blackpanda-media/ftp-client
```

## Usage

Basic initialization and class usage for the most used ftp server operations

```php

require_once __DIR__ . '/vendor/autoload.php';

use BPM\FTP\Connection\Connection;
use BPM\FTP\SimpleFTPClient;

$connection = new Connection('host', 'login', 'password');
$client = new SimpleFTPClient($connection);

// shows the list of folders and files in the root server
var_dump($client->nlist('/'));
```

## Tests

This package contains the basic tests that provide a good basis for further development