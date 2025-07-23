<?php
namespace MiMFa\Library;
/**
 * A model to store route pathes
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@var array<array<string,array<string>>>
 *@details [[ALL],[GET],[POST],[PUT],[FILE],[PATCH],[DELETE],[STREAM],[INTERNAL],[EXTERNAL]]
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#router See the Library Documentation
 */
class Router extends \ArrayObject
{
    public bool $IsActive = true;

    /**
     * The request original method index
     * ALL:0,
     * GET:1,
     * POST:2,
     * PUT:3,
     * FILE:4,
     * PATCH:5,
     * DELETE:6,
     * STREAM:7,
     * INTERNAL:8,
     * EXTERNAL:9, 
     * OTHER:10
     * @var int
     */
    public int|null $DefaultMethodIndex = null;
    /**
     * The request original method name
     * ALL:0,
     * GET:1,
     * POST:2,
     * PUT:3,
     * FILE:4,
     * PATCH:5,
     * DELETE:6,
     * STREAM:7,
     * INTERNAL:8,
     * EXTERNAL:9, 
     * OTHER:10
     * @var string
     */
    public string|null $DefaultMethodName = null;

    /**
     * The latest used method
     * ALL:0,
     * GET:1,
     * POST:2,
     * PUT:3,
     * FILE:4,
     * PATCH:5,
     * DELETE:6,
     * STREAM:7,
     * INTERNAL:8,
     * EXTERNAL:9, 
     * OTHER:10
     * @var int
     */
    public $Method = null;
    public $Result = null;
    public $Point = 0;
    public $Global = false;
    public $Pattern = null;
    public $Taken = null;
    public $Direction = null;
    public $Request = null;


    function __construct(
        $pattern = null,
        $handler = null,
        $method = null,
        $global = false
    ) {
        $this->Global = $global;
        $this->Refresh($pattern, $method)->Route($handler);
    }
    public function __get($name)
    {
        //return $this->offsetExists($name = getMethodName($name)) ? $this->offsetGet($name) : ($this[$name] = []);
        return $this->offsetGet(getMethodName($name));
    }
    public function __set($name, $value)
    {
        return $this->offsetSet(getMethodName($name), $value);
    }


    /**
     * To get handlers for the received request
     */
    public function Handlers()
    {
        $this->Method = $this->DefaultMethodName;
        do
            foreach ($this[$this->Method]??[] as $pat => $handlers)
                if (preg_match($pat, $this->Request ?? "", $matches)) {
                    foreach ($handlers as $i => $handler) {
                        $this->Pattern = $pat;
                        $this->Point++;
                        $this->Taken = $matches[0];
                        $this->Direction = ltrim(preg_replace($this->Pattern, "", $this->Direction ?? ""), "/\\ ");
                        if (!$this->Global)
                            $this->Request = preg_replace($this->Pattern, "", $this->Request ?? "");
                        yield $handler;
                    }
                    if ($this->Point > 0 && !$this->Global)
                        continue;
                }
        while ($this->Method != "ALL" && $this->Method = "ALL");
    }
    /**
     * To handle routing for the received request
     * @param string|null|int $alternative An alternative route name
     */
    public function Handle()
    {
        foreach ($this->Handlers() as $handler)
            $this->Result = $handler();
        return $this;
    }

    /**
     * Refresh all properties, have no effect on routes
     * @return Router
     */
    public function Refresh($pattern = null, $method = null): Router
    {
        $this->IsActive = true;
        $this->Result = null;
        $this->Point = 0;
        $this->Taken = null;
        $this->Request = \Req::$Request;
        $this->Direction = \Req::$Direction;
        $this->DefaultMethodIndex = getMethodIndex();
        $this->DefaultMethodName = getMethodName();
        $this->Set($method)->On($pattern);
        return $this;
    }

    /**
     * If condition be true
     * @param $condition if condition
     * @return Router
     */
    public function if($condition): Router
    {
        if ($this->IsActive)
            $this->IsActive = Convert::By($condition, $this) ? true : false;
        return $this;
    }
    /**
     * Else or Else if condition
     * @param $condition else if condition (optional)
     * @return Router
     */
    public function else($condition = null): Router
    {
        if (!$this->IsActive)
            $this->IsActive = Convert::By($condition ?? true, $this) ? true : false;
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
     * Or condition to change update ability
     * @return Router
     */
    public function or($condition): Router
    {
        $this->IsActive = $this->IsActive || Convert::By($condition, $this);
        return $this;
    }
    /**
     * Reset conditions to able you update routes
     * @return Router
     */
    public function anyway(): Router
    {
        $this->IsActive = true;
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
        if ($this->IsActive) {
            $this->DefaultMethodIndex = getMethodIndex($this->Method);
            $this->DefaultMethodName = getMethodName($this->Method);
        }
        return $this;
    }

    /**
     * To set routing of the request
     * @param mixed $pattern Url or Regex Pattern @example "/^\/post(?=\/|\\\|\?|#|@|$)/i"
     * @return Router
     */
    public function On($pattern = null): Router
    {
        if ($this->IsActive)
            $this->Pattern = self::CreatePattern($pattern);
        return $this;
    }
    /**
     * To set routing of the request
     * @param int|string|null $method 0: ALL, 1:GET, 2:POST, 3:PUT, 4:FILE, 5:PATCH, 6:DELETE
     * @return Router
     */
    public function Set($method = null): Router
    {
        if ($this->IsActive)
            if(!isset($this[$this->Method = getMethodName($method)])) $this[$this->Method] = [];
        return $this;
    }
    /**
     * Remove the current pattern from the current or defined method
     * @param int|string|null $method 0: ALL, 1:GET, 2:POST, 3:PUT, 4:FILE, 5:PATCH, 6:DELETE
     * @return Router
     */
    public function Unset($method = null): Router
    {
        if ($this->IsActive)
            unset($this[$method ? getMethodName($method) : $this->Method][$this->Pattern]);
        return $this;
    }
    /**
     * Remove the current pattern from all methods
     * @return Router
     */
    public function Reset(): Router
    {
        if ($this->IsActive)
            foreach ($this as $mthd => $pttrns)
                unset($this[$mthd][$this->Pattern]);
        return $this;
    }
    /**
     * Remove all patterns from all methods
     * @return Router
     */
    public function Clear(): Router
    {
        if ($this->IsActive)
            foreach ($this as $mthd => $pttrns)
                $this[$mthd] = [];
        return $this;
    }

    /**
     * Add new route for the current request method and pattern
     * @param mixed $handler A route name or handeler function
     * @return Router
     */
    public function Route($handler, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        if ($this->IsActive && isValid($handler)) {
            $method = $this->Method;
            $pattern = $this->Pattern;
            $h = function () use ($handler, $data, $print, $origin, $depth, $alternative, $default) {
                return (isStatic($handler)) ? route($handler, $data, $print, $origin, $depth, $alternative, $default) :
                    Convert::By($handler, $this);
            };
            if (isset($this[$method][$pattern]))
                $this[$method][$pattern][] = $h;
            else
                $this[$method][$pattern] = [$h];
        }
        return $this;
    }
    /**
     * Add new route to other requests methods
     * Leave null to clear other requests
     * @param mixed $handler A route name or handeler function, Leave null to clear other requests
     * @return Router
     */
    public function Rest($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        if ($this->IsActive) {
            $method = $this->Method;
            $pattern = $this->Pattern;
            if (isValid($handler)) {
                $h = function () use ($handler, $data, $print, $origin, $depth, $alternative, $default) {
                    return (isStatic($handler)) ? route($handler, $data, $print, $origin, $depth, $alternative, $default) :
                        Convert::By($handler, $this);
                };
                foreach ($this as $mthd => $pttrns)
                    if ($mthd !== $method && $mthd !== "ALL") {
                        if (isset($this[$mthd][$pattern]))
                            $this[$mthd][$pattern][] = $h;
                        else
                            $this[$mthd][$pattern] = [$h];
                    }
            } else
                foreach ($this as $mthd => $pttrns)
                    if ($mthd !== $method && $mthd !== "ALL")
                        unset($this[$mthd][$pattern]);
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
        if ($this->IsActive) {
            $pattern = $this->Pattern;
            $h = function () use ($handler, $data, $print, $origin, $depth, $alternative, $default) {
                if ($this->Point <= 1 || $this->Global)
                    return (isStatic($handler)) ? route($handler, $data, $print, $origin, $depth, $alternative, $default) :
                        Convert::By($handler, $this);
                return null;
            };
            foreach ($this as $mthd => $pttrns)
                if ($mthd !== "ALL") {
                    if (isset($this[$mthd][$pattern]))
                        $this[$mthd][$pattern][] = $h;
                    else
                        $this[$mthd][$pattern] = [$h];
                }
        }
        return $this;
    }

    /**
     * Add new route for ALL requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function All($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        return $this->Set("ALL")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the GET requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function Get($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        return $this->Set("GET")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the POST requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function Post($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        return $this->Set("POST")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the PUT requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function Put($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        return $this->Set("PUT")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the FILE requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function File($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        return $this->Set("FILE")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the PATCH requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function Patch($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        return $this->Set("PATCH")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the DELETE requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function Delete($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        return $this->Set("DELETE")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }

    /**
     * Add new route to the STREAM requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function Stream($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        return $this->Set("STREAM")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }

    /**
     * Add new route to the INTERNAL requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function Internal($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        return $this->Set("INTERNAL")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }

    /**
     * Add new route to the EXTERNAL requests
     * @param string|callable $handler A route name or a handeler function
     * @return Router
     */
    public function External($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null): Router
    {
        return $this->Set("EXTERNAL")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }

    /**
     * 
     * Convert to Regular Expression pattern
     * @param mixed $pattern Url or Regex Pattern
     */
    public static function CreatePattern($pattern = null, $default = "/^\/?[^\/\\\\.\?#@]*(\.\w*)*(?=\/|\\\|\?|#|@|$)/i"): string
    {
        if ($pattern)
            return preg_match("/^\/[\s\S]+\/[gimsxU]{0,6}$/", $pattern) ?
                $pattern :
                ("/^\\/?" . str_replace("/", "\/", $pattern) . "(\\.\\w*)*(?=\\/|\\\|\\?|#|@|$)/i");
        return $default;
    }
}