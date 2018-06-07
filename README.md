# PHP Command Parser

PHP Command Parser可以用來在php cli操作時，以更方便更快捷的方式將參數傳遞。

### Install
```
composer require minhsieh/php-command
```

### Usage
```php
#php test.php --foo=bar
require "vendor/autoload.php";

use Command\Parser;

$args = Parser::parse($_SERVER['argv']);

echo $args['foo'];
//bar
```
