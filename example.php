<?php

use Mateodioev\StringMatcher\{Config, Matcher};

require __DIR__ . '/vendor/autoload.php';

$conf = new Config;
// match email's
$conf->addFormat('mail', "([a-z0-9!\#$%&'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!\#$%&'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9]))");
# $conf->addFormat('c', '([\d.]+)');

$matcher = new Matcher('/{name}?/{aaaa}/{mail:umail}?/', $conf);

$text = '/nombre/asds/customemail@mail.com/';

var_dump($matcher->isValid($text, true));
$vars = $matcher->match($text, true);

echo json_encode($vars, JSON_PRETTY_PRINT);
