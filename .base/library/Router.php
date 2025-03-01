<?php
namespace MiMFa\Library;
/**
 * A model to store route pathes
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@var array<array<string,array<string>>>
 *@details [[ALL],[GET],[POST],[PUT],[FILE],[PATCH],[DELETE]]
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#router See the Library Documentation
 */
class Router extends \ArrayObject
{
    public bool $IsActive = true;

    public $DefaultMethod = null;

    public $Result = null;
    public $Point = 0;
    public $Global = false;
    public $Method = null;
    public $Pattern = null;
    public $Taken = null;
    public $Direction = null;
    public $Request = null;


    function __construct(
        $pattern = null,
        $handler = null,
        $method = null,
        $global = false
        )
    {
        parent::__construct([[/*ALL*/], [/*GET*/], [/*POST*/], [/*PUT*/], [/*FILE*/], [/*PATCH*/], [/*DELETE*/]]);
        $this->Global = $global;
        $this->Renew($pattern, $method)->Set($handler);
    }
    public function __get($name)
    {
        return $this->offsetGet(getMethodIndex($name));
    }
    public function __set($name, $value)
    {
        return $this->offsetSet(getMethodIndex($name), $value);
    }
    
    /**
     * To get handlers for the received request
     */
    public function Handlers()
    {
        $this->Method = $this->DefaultMethod;
        do
            foreach ($this[$this->Method] as $pat => $handlers)
                if (preg_match($pat, $this->Request ?? "", $matches)) {
                    foreach ($handlers as $i => $handler) 
                    {
                        $this->Pattern = $pat;
                        $this->Point++;
                        $this->Taken = $matches[0];
                        $this->Direction = ltrim(preg_replace($this->Pattern, "", $this->Direction ?? ""), "/\\ ");
                        if(!$this->Global) $this->Request = preg_replace($this->Pattern, "", $this->Request ?? "");
                        yield $handler;
                    }
                    if ($this->Point > 0 && !$this->Global) continue;
                }
        while ($this->Method !== 0 && ($this->Method = 0) == 0);
    }
    /**
     * To handle routing for the received request
     * @param string|null|int $alternative An alternative route name
     */
    public function Handle()
    {
        foreach ($this->Handlers() as $handler) $this->Result = $handler();
        return $this;
    }

    /**
     * Reset all properties, have no effect on routes
     * @return Router
     */
    public function Renew($pattern = null, $method = null): Router
    {
        $this->IsActive = true;
        $this->Result = null;
        $this->Point = 0;
        $this->Taken = null;
        $this->Request = \Req::$Request;
        $this->Direction = \Req::$Direction;
        $this->DefaultMethod = getMethodIndex();
        $this->Pattern = self::CreatePattern($pattern);
        $this->Method = getMethodIndex($method);
        return $this;
    }
    /**
     * To start routing for the requests
     * @param mixed $pattern Url or Regex Pattern
     * @example Route("/^\/post(?=\/|\\\|\?|#|@|$)/i")
     * @return Router
     */
    public function Route($pattern = null): Router
    {
        if ($this->IsActive) $this->Pattern = self::CreatePattern($pattern);
        return $this;
    }
    /**
     * Add new route for the requests
     * @param mixed $handler A route name or handeler function
     * @return Router
     */
    public function Set($handler, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        if ($this->IsActive && isValid($handler)) {
            $method = $this->Method;
            $pattern = $this->Pattern;
            $h = function() use($handler, $data, $print, $origin, $depth, $alternative, $default){
                return (is_string($handler) || isEmpty($handler))? 
                route($handler, $data ?? $this, $print, $origin, $depth, $alternative, $default):
                Convert::By($handler, $this);
            };
            if (isset($this[$method][$pattern])) $this[$method][$pattern][] = $h;
            else $this[$method][$pattern] = [$h];
        }
        return $this;
    }
    /**
     * Remove a route from this object
     * @return Router
     */
    public function Unset(): Router
    {
        if ($this->IsActive) unset($this[$this->Method][$this->Pattern]);
        return $this;
    }
    /**
     * Set only selected route for the requests
     * And clear all other routes, Leave null to clear other requests
     * @param mixed $handler A route name or handeler function, Leave null to clear other requests
     * @return Router
     */
    public function Reset($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        if ($this->IsActive) {
                $pattern = $this->Pattern;
                for ($method = 0; $method < count($this); $method++)
                    unset($this[$method][$pattern]);
                if(isValid($handler)) $this[$this->Method][$pattern] = [function() use($handler, $data, $print, $origin, $depth, $alternative, $default){
                    return (is_string($handler) || isEmpty($handler))? 
                    route($handler, $data ?? $this, $print, $origin, $depth, $alternative, $default):
                    Convert::By($handler, $this);
                }];
            }
        return $this;
    }
    /**
     * Add new route to other requests
     * Leave null to clear other requests
     * @param mixed $handler A route name or handeler function, Leave null to clear other requests
     * @return Router
     */
    public function Rest($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        if ($this->IsActive) 
            if(isValid($handler)){
                $m = $this->Method;
                $pattern = $this->Pattern;
                $h = function() use($handler, $data, $print, $origin, $depth, $alternative, $default){
                    return (is_string($handler) || isEmpty($handler))? 
                    route($handler, $data ?? $this, $print, $origin, $depth, $alternative, $default):
                    Convert::By($handler, $this);
                };
                for ($method = 1; $method < count($this); $method++)
                    if ($m !== $method)
                        if(isset($this[$method][$pattern])) $this[$method][$pattern][] = $h;
                        else $this[$method][$pattern] = [$h];
            }
            else {
                $m = $this->Method;
                $pattern = $this->Pattern;
                for ($method = 1; $method < count($this); $method++)
                    if ($m !== $method)
                        unset($this[$method][$pattern]);
            }
        return $this;
    }
    /**
     * Add new handler for ALL requests, to handle if specified methods could not handle before
     * @param string|null|callable $handler A route name or a handeler function
     * @param mixed $data The passed data 
     * @param string|null|int $alternative An alternative route name
     * @return Router
     */
    public function Default($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        if ($this->IsActive){
            $pattern = $this->Pattern;
            $h = function() use($handler, $data, $print, $origin, $depth, $alternative, $default){
                if($this->Point <= 1 || $this->Global)
                    if(is_string($handler) || isEmpty($handler)) return route($handler, $data ?? $this, $print, $origin, $depth, $alternative, $default);
                    else return Convert::By($handler, $this);
                return null;
            };
            for ($method = 1; $method < count($this); $method++)
                if(isset($this[$method][$pattern])) $this[$method][$pattern][] = $h;
                else $this[$method][$pattern] = [$h];
        }
        return $this;
    }
    /**
     * If condition be true
     * @return Router
     */
    public function if($condition): Router
    {
        $this->IsActive = Convert::By($condition, $this);
        return $this;
    }
    /**
     * Else condition
     * @return Router
     */
    public function else(): Router
    {
        $this->IsActive = !$this->IsActive;
        return $this;
    }
    /**
     * Else if condition
     * @return Router
     */
    public function elseIf($condition): Router
    {
        $this->IsActive = !$this->IsActive && Convert::By($condition, $this);
        return $this;
    }
    /**
     * And condition to change update ability
     * @return Router
     */
    public function and($condition): Router
    {
        $this->IsActive = $this->IsActive && Convert::By($condition, $this);
        return $this;
    }
    /**
     * Or condition to  change update ability
     * @return Router
     */
    public function or($condition): Router
    {
        $this->IsActive = $this->IsActive || Convert::By($condition, $this);
        return $this;
    }

    /**
     * To change the default method to handle
     * @example: ->Post()->Switch()
     * @example: ->Delete()->Switch()
     * @return Router
     */
    public function Switch(): Router
    {
        if ($this->IsActive) $this->DefaultMethod = getMethodIndex($this->Method);
        return $this;
    }
    /**
     * To change the method of router
     * @param int|string|null $method 0: ALL, 1:GET, 2:POST, 3:PUT, 4:FILE, 5:PATCH, 6:DELETE
     * @example: On("post")
     * @example: On(2)
     * @return Router
     */
    public function On($method = null): Router
    {
        if ($this->IsActive) $this->Method = getMethodIndex($method);
        return $this;
    }
    
    /**
     * Add new route for ALL requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function All($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        if ($this->IsActive) $this->Method = 0;
        return $this->Set($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the GET requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function Get($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        if ($this->IsActive) $this->Method = 1;
        return $this->Set($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the POST requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function Post($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        if ($this->IsActive) $this->Method = 2;
        return $this->Set($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the PUT requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function Put($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        if ($this->IsActive) $this->Method = 3;
        return $this->Set($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the FILES requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function File($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        if ($this->IsActive) $this->Method = 4;
        return $this->Set($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the PATCH requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function Patch($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        if ($this->IsActive) $this->Method = 5;
        return $this->Set($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the DEL requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function Delete($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        if ($this->IsActive) $this->Method = 6;
        return $this->Set($handler, $data, $print, $origin, $depth, $alternative, $default);
    }


    /**
     * Convert to Regular Expression pattern
     * @param mixed $pattern Url or Regex Pattern
     */
    public static function CreatePattern($pattern = null, $default = "/^\/?[^\/\\\\.\?#@]*(\.\w*)*(?=\/|\\\|\?|#|@|$)/i"): string
    {
        if ($pattern)
            return preg_match("/^\/[\s\S]+\/[gimsxU]{0,6}$/", $pattern) ?
                $pattern :
                ("/^\\/?" . str_replace(["\\", "/"], ["\\\\", "\/"], $pattern) . "(\\.\\w*)*(?=\\/|\\\|\\?|#|@|$)/i");
        return $default;
    }
}
?>