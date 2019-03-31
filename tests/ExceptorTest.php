<?php
use Undercloud\Exception\FlowHandler;

class ExceptorTest extends PHPUnit_Framework_TestCase
{
    public function testNotice()
    {
        (new FlowHandler(function(){
            try {
                echo $someVariable;
            } catch (Exception $e) {
                return $e;
            } catch (Throwable $e) {
                return $e;
            }
        }))->flow(function($e){
            $this->assertTrue($e instanceof NoticeException);
        });
    }
}