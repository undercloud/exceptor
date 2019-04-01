<?php
use Undercloud\FlowHandler;

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

    public function testWarning()
    {
        (new FlowHandler(function(){
            try {
                in_array(false,false);
            } catch (Exception $e) {
                return $e;
            } catch (Throwable $e) {
                return $e;
            }
        }))->flow(function($e){
            $this->assertTrue($e instanceof WarningException);
        });
    }

    public function testUserDeprecated()
    {
        (new FlowHandler(function(){
            try {
                trigger_error('', E_USER_DEPRECATED);
            } catch (Exception $e) {
                return $e;
            } catch (Throwable $e) {
                return $e;
            }
        }))->flow(function($e){
            $this->assertTrue($e instanceof UserDeprecatedException);
        });
    }

    public function testOutOfMemory()
    {
        (new FlowHandler(function(){
            try {
                $a = [];
                while(true){
                    $a[] = time();
                }
            } catch (Exception $e) {
                return $e;
            } catch (Throwable $e) {
                return $e;
            }
        }))->flow(function($e){
            $this->assertTrue($e instanceof OutOfMemoryException);
        });
    }

    public function testExecutionTimeout()
    {
        set_time_limit(1);
        (new FlowHandler(function(){
            try {
                $a = [];
                while(true){
                    $a[] = time();
                    $a = [];
                }
            } catch (Exception $e) {
                return $e;
            } catch (Throwable $e) {
                return $e;
            }
        }))->flow(function($e){
            $this->assertTrue($e instanceof ExecutionTimeoutException);
        });
    }
}