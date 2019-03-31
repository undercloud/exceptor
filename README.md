# exceptor
Exception's flow

```php
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
  
})
```
