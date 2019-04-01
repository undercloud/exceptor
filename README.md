# exceptor

[![Build Status](https://travis-ci.org/undercloud/exceptor.svg?branch=master)](https://travis-ci.org/undercloud/exceptor)

Exception's flow

## Install

`composer require undercloud/exceptor`

## Requirements

PHP 5.4+

## Description

Provides a mechanism for catching errors and exceptions in the application flow. All built-in types of errors are transformed into exceptions, which greatly simplifies debugging. It also intercepts errors due to lack of memory and execution timeout.

## Usage

```php
// catch all
error_reporting(E_ALL);

use Undercloud\Exception\FlowHandler;

(new FlowHandler(function(){
    try {
        // your code
    // PHP 5.x 
    } catch (Exception $e) {
        return $e;
    // PHP 7.x
    } catch (Throwable $e) {
        return $e;
    }
}))->flow(function($e){
    // handle exception
})
```

## Type of Exceptions

* All standard PHP's exceptions
* CompileErrorException
* CompileWarningException
* CoreErrorException
* CoreWarningException
* DeprecatedException
* NoticeException
* ParseException
* RecoverableErrorException
* StrictException
* UserDeprecatedException
* UserErrorException
* UserNoticeException
* UserWarningException
* WarningException
* ExecutionTimeoutException
* OutOfMemoryException

## LICENSE

MIT