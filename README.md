# Date Validator [![Build Status](https://scrutinizer-ci.com/g/alexanderholman/date-validator/badges/build.png?b=master)](https://scrutinizer-ci.com/g/alexanderholman/date-validator/build-status/master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexanderholman/date-validator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexanderholman/date-validator/?branch=master)
The purpose of this class is to validate a date against a defined format of `DD/MM/YYY`

## Usage
Include the files `result.php` and `date-validator.php` in where required e.g. `index.php` however you choose.

One of the simplest ways being:
```
require_once 'path/to/result.php';
require_once 'path/to/date-validator.php';
```

To check a date e.g. `29/02/2016` run:
```
$result = DateValidator::validateHistoricalDate('29/02/2016');

if ($result->isValid()) {

    // Valid date and format
    // This is what would actually run base on the example date

} else {
    
    // Invalid date or format
    
}
```

If you wish to find out in more detail what is wrong with a given date run the following instead:
```
DateValidator::validateHistoricalDate($dateString, true);
```

You will then be have a more specific error message when calling `$result->getMessage()`

## Example
View the file `example/getting-started.php` or `example/form.php`

## Try It
Enter a date in the form provided [here](http://holman.org.uk/date-validator/example/form.php) and see if it is valid.