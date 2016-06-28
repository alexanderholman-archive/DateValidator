<?php

require_once '../src/result.php';
require_once '../src/date-validator.php';

$automate = false;

$message = 'Hi! if you\'re reading this the date is valid and matches the pattern DD/MM/YYYY';

$dateString = '03/12/1999';            // Valid
//$dateString = '03/12/2999';            // Valid
//$dateString = '3/12/1999';             // Error
//$dateString = '12/31/1999';            // Error
//$dateString = '12/31/1999/03/12/1999'; // Error
//$dateString = '03/31/99';              // Error
//$dateString = '3/31/99';               // Error
//$dateString = '';                      // Error
//$dateString = null;                    // Error

$result = DateValidator::validateHistoricalDate($dateString);

if (!$result->isValid()) {

    $message = $result->getMessage();

}

echo $automate ? 'Function automated test result: ' . (DateValidator::testValidateHistoricalDate() ? 'working' : 'error') : $message;