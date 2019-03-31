<?php
namespace Undercloud\Misc\Exception;

use Closure;
use Throwable;
use Exception;

class HandleException
{
    /**
     * @var Closure
     */
    protected $callback;

    /**
     * Default constructor
     * 
     * @param Closure $callback handler
     */
    public function __construct(Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Handle application flow
     * 
     * @param Closure $fallback handler
     * 
     * @return null
     */
    public function flow(Closure $fallback)
    {
        set_error_handler(function ($severity, $message, $file, $line) {
            return $this->castErrorToException(
                $severity,
                $message,
                $file,
                $line
            );
        });

        $supress = false;
        register_shutdown_function(function () use ($fallback, &$supress) {
            if ($supress) {
                return;
            }

            $lastError = error_get_last();
            if ($lastError) {
                ob_clean();
                
                try {
                    $lastError = (object) $lastError;

                    $this->castErrorToException(
                        $lastError->type,
                        $lastError->message,
                        $lastError->file,
                        $lastError->line
                    );
                } catch (Exception $exception) {
                    $fallback($exception);
                } catch (Throwable $exception) {
                    $fallback($exception);
                }
            }
        });

        $exception = $this->callback();
        if ($exception instanceof Exception or $exception instanceof Throwable) {
            $fallback($exception);
            $supress = true;
        }
    }

    /**
     * Convert E_* constant to ClassNameException 
     * 
     * @param string $error E_* constant
     *
     * @return string
     */
    protected function errorToClassName($error)
    {
        $error    = substr($error, 1);
        $error    = strtolower($error);
        $callback = function ($match) {
            return strtoupper($match[1]);
        };

        return (
            preg_replace_callback('~\_([a-z])~', $callback, $error) .
            'Exception'
        );
    }

    /**
     * Cast error to named exception
     * 
     * @param int    $severity severity
     * @param string $message  error
     * @param string $file     file
     * @param int    $line     line
     *
     * @return false|null
     */
    protected function castErrorToException($severity, $message, $file, $line)
    {
        if (!(error_reporting() & $severity)) {
            return false;
        }

        $errors = array(
            'E_ERROR', 'E_WARNING', 'E_PARSE',
            'E_NOTICE', 'E_CORE_ERROR', 'E_CORE_WARNING',
            'E_COMPILE_ERROR', 'E_COMPILE_WARNING', 'E_USER_ERROR',
            'E_USER_WARNING', 'E_USER_NOTICE', 'E_STRICT',
            'E_RECOVERABLE_ERROR', 'E_DEPRECATED', 'E_USER_DEPRECATED'
        );

        foreach ($errors as $error) {
            if ($severity === constant($error)) {
                $className = $this->errorToClassName($error);
                
                if ('ErrorException' === $className) {
                    $possibleException = array (
                        'OutOfMemoryException' => 'Allowed memory size of',
                        'ExecutionTimeoutException' => 'Maximum execution time'
                    );

                    foreach ($possibleException as $exception => $text) {
                        if (preg_match('~^' . $text . '~i', $message)) {
                            $className = $exception;

                            break;
                        }
                    }
                }

                $this->makeDynamicException($className);

                throw new $className($message, 0, $severity, $file, $line);
            }
        }
    }

    /**
     * Create dynamic exception on fly
     * 
     * @param string $className target
     * 
     * @return null
     */
    protected function makeDynamicException($className)
    {
        if (!class_exists($className, false)) {
            eval("class {$className} extends ErrorException {}");
        }
    }
}
