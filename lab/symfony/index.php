<?php

require __DIR__ . '/../../vendor/autoload.php';


echo '<pre>';


print_r($_POST);

print_r($_REQUEST);



/**
 * @doc https://symfony.com/doc/2.7/components/http_foundation.html
 */

use Symfony\Component\HttpFoundation\Request;

// initialize (new self) behand.
$request = Request::createFromGlobals();


print_r($request);
