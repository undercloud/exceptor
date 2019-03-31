# exceptor
Exception's flow

```php
(new HandleException(function(){
		try {
			Appe::run();
		} catch (Exception $e) {
      return $e;
    } catch (Throwable $e) {
      return $e;
    }
}))->flow(function($e){
  
})
```
