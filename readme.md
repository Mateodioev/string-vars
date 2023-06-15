# String matcher

Get vars from a string

Example:
```php
$matcher = new Matcher(format: "Hello {name}");

// Validate input
var_dump($matcher->isValid('Hello Juan')); // true
var_dump($matcher->isValid('Hello  Juan')); // false

$vars = $matcher->match('Hello Juan'); // $vars contain an array of parameters
var_dump($vars['name']); // "Juan"
```

## Match with data types

```php
// "w": all string
// "d": all numbers
// "f": all decimals
// "all": all characters
// "": all characters except /
$matcher = new Matcher(format: "Hello {w:name}");

```
## Using custom formats

```php
$conf = new Config();

// This match all decimals numbers
$conf->addFormat('c', '([\d.]+)') // $key, $regexFormat

$matcher = new Matcher('The price is {c:price}', $conf);

var_dump($matcher->isValid('The price is 33.03')); // true
var_dump($matcher->isValid('The price is 33')); // false

$vars = $matcher->match('The price is 33.03');
var_dump($vars['price']); // 33.03
```
