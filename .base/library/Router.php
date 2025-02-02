<?php
namespace MiMFa\Library;
/**
 * A model to store route pathes
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@var array<array<string,string>>
 *@template [[GET],[POST],[ADD],[PUT],[DEL]]
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#router See the Library Documentation
 */
class Router extends \ArrayObject
{
    function __construct()
    {
        parent::__construct([-1 => [], 0 => [], 1 => [], 2 => [], 3 => [], 4 => []]);
    }
    /**
     * To handle routing for the received request
     * @param mixed $path Url
     */
    public function Handle($path = null, $method = null): bool
    {
        $path = $path ?? \_::$REQUEST;
        $method = $method ?? \_::$METHOD;
        foreach ($this[$method] as $pat => $handler)
            if (preg_match($pat, $path) && \_::$CONFIG->Router->View($handler))
                return true;
        if ($method >= 0)
            foreach ($this[-1] as $pat => $handler)
                if (preg_match($pat, $path) && \_::$CONFIG->Router->View($handler))
                    return true;
        return false;
    }
    /**
     * To handle the view handler
     * @param mixed $handler Url
     */
    public function View($handler)
    {
        if (is_string($handler))
            return VIEW($handler, variables: $_REQUEST);
        else
            return Convert::By($handler) ?? true;
    }
    
    private $TempPattern = "/.*/";
    /**
     * To start routing for the requests
     * @param mixed $pattern Url or Regex Pattern
     * @template Route("/^\/post(\/|\?|$)/i")
     * @return Router
     */
    public function Route($pattern=null): Router
    {
        $this->TempPattern = $pattern??$this->TempPattern;
        return $this;
    }
    /**
     * Add new route for the requests
     * @param mixed $handler A view name or handeler function
     * @param mixed $pattern Url or Regex Pattern
     * @param int $method -1: ALL, 0:GET, 1:POST, 2:ADD, 3:PUT, 4:DEL
     * @return Router
     */
    public function Set($handler, $pattern = null, $method = -1): Router
    {
        if ($method < 0)
            $this[$method][$pattern ?? $this->TempPattern] = $handler;
        else
            $this[$method][$pattern ?? $this->TempPattern] = $handler;
        return $this;
    }
    /**
     * Remove a route from this object
     * @param int $method -1: ALL, 0:GET, 1:POST, 2:ADD, 3:PUT, 4:DEL
     * @param mixed $pattern Url or Regex Pattern
     * @return Router
     */
    public function Unset($pattern = null, $method = -1): Router
    {
        if ($method < 0)
            unset($this[$method][$pattern ?? $this->TempPattern]);
        else
            unset($this[$method][$pattern ?? $this->TempPattern]);
        return $this;
    }

    /**
     * Add new route to ALL requests
     * @param mixed $pattern Url or Regex Pattern
     * @param mixed $handler A view name or handeler function
     * @return Router
     */
    public function ALL($handler, $pattern = null): Router
    {
        return $this->Set($handler, $pattern, -1);
    }
    /**
     * Add new route to the GET requests
     * @param mixed $pattern Url or Regex Pattern
     * @param mixed $handler A view name or handeler function
     * @return Router
     */
    public function GET($handler, $pattern = null): Router
    {
        return $this->Set($handler, $pattern, 0);
    }
    /**
     * Add new route to the POST requests
     * @param mixed $pattern Url or Regex Pattern
     * @param mixed $handler A view name or handeler function
     * @return Router
     */
    public function POST($handler, $pattern = null): Router
    {
        return $this->Set($handler, $pattern, 1);
    }
    /**
     * Add new route to the ADD requests
     * @param mixed $pattern Url or Regex Pattern
     * @param mixed $handler A view name or handeler function
     * @return Router
     */
    public function ADD($handler, $pattern = null): Router
    {
        return $this->Set($handler, $pattern, 2);
    }
    /**
     * Add new route to the PUT requests
     * @param mixed $pattern Url or Regex Pattern
     * @param mixed $handler A view name or handeler function
     * @return Router
     */
    public function PUT($handler, $pattern = null): Router
    {
        return $this->Set($handler, $pattern, 3);
    }
    /**
     * Add new route to the DEL requests
     * @param mixed $pattern Url or Regex Pattern
     * @param mixed $handler A view name or handeler function
     * @return Router
     */
    public function DEL($handler, $pattern = null): Router
    {
        return $this->Set($handler, $pattern, 4);
    }
}
?>