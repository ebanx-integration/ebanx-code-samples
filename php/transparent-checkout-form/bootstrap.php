<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
require_once 'helpers.php';

// Setup EBANX
\Ebanx\Config::set(array(
   'integrationKey' => ''
  , 'directMode'     => true
  , 'testMode'       => true
));

// Installments setup
define('INSTALLMENTS_INTEREST_TYPE', 'compound');
define('INSTALLMENTS_INTEREST_RATE', 2.0);
define('MAX_INSTALLMENTS', 12);

// Store URLS
define('RETURN_URL', 'https://www.ebanx.com');