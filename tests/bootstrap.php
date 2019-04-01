<?php
// backward compatibility
if (!class_exists('\PHPUnit\Framework\TestCase', true)) {
    class_alias('\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
} elseif (!class_exists('\PHPUnit_Framework_TestCase', true)) {
    class_alias('\PHPUnit\Framework\TestCase', '\PHPUnit_Framework_TestCase');
}

error_reporting(-1);
require_once '/home/travis/build/undercloud/exceptor/src/Undercloud/Exception/FlowHandler.php';
