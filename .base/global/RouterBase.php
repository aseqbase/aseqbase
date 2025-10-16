<?php
use MiMFa\Library\Convert;
/*
 * A library to work by routes and pathes
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@var array<array<string,array<string>>>
 *@details [[ALL],[GET],[POST],[PUT],[FILE],[PATCH],[DELETE],[STREAM],[INTERNAL],[EXTERNAL]]
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#router See the Library Documentation
 */
class RouterBase extends ArrayObject
{
    public $Routes = [];


    /**
     * The top layer Name of this sequence
     */
    public string|null $Name;


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

    /**
     * Full part of the current url
     * @example: "https://www.mimfa.net:5056/Category/mimfa/service/web.php?p=3&l=10#serp"
     * @var string|null
     */
    public string|null $Url = null;
    /**
     * The path part of the current url
     * @example: "https://www.mimfa.net:5056/Category/mimfa/service/web.php"
     * @var string|null
     */
    public string|null $Path = null;
    /**
     * The host part of the current url
     * @example: "https://www.mimfa.net:5056"
     * @var string|null
     */
    public string|null $Host = null;
    /**
     * The site name part of the current url
     * @example: "www.mimfa.net"
     * @var string|null
     */
    public string|null $Site = null;
    /**
     * The domain name part of the current url
     * @example: "mimfa.net"
     * @var string|null
     */
    public string|null $Domain = null;
    /**
     * The request part of the current url
     * @example: "/Category/mimfa/service/web.php?p=3&l=10#serp"
     * @var string|null
     */
    public string|null $Request = null;
    /**
     * The direction part of the current url from the root
     * @example: "Category/mimfa/service/web.php"
     * @var string|null
     */
    public string|null $Direction = null;
    /**
     * The last part of the current direction url
     * @example: "web.php"
     * @var string|null
     */
    public string|null $Page = null;
    /**
     * The query part of the current url
     * @example: "p=3&l=10"
     * @var string|null
     */
    public string|null $Query = null;
    /**
     * The fragment or anchor part of the current url
     * @example: "serp"
     * @var string|null
     */
    public string|null $Fragment = null;


    public string|null $Directory;
    public string $ModelDirectory;
    public string $ViewDirectory;
    public string $ComputeDirectory;
    public string $RouteDirectory;
    public string $PrivateDirectory;
    public string $PublicDirectory;
    public string $AssetDirectory;
    public string $StorageDirectory;
    public string $TempDirectory;
    public string $LogDirectory;
    public string $LibraryDirectory;
    public string $ComponentDirectory;
    public string $TemplateDirectory;
    public string $ModuleDirectory;
    public string $PageDirectory;
    public string $RegionDirectory;
    public string $PartDirectory;
    public string $ScriptDirectory;
    public string $StyleDirectory;

    /**
     * The root path
     * @example: "/"
     * @var string|null
     */
    public string|null $Root;
    /**
     * The Asset root Route
     * @example: "/asset/"
     * @var string
     */
    public string $AssetRoot;
    /**
     * The Script root Route
     * @example: "/script/"
     * @var string
     */
    public string $ScriptRoot;
    /**
     * The Style root Route
     * @example: "/style/"
     * @var string
     */
    public string $StyleRoot;
    /**
     * The Content root Route
     * @example: "/content/"
     * @var string
     */
    public string $ContentRoot;
    /**
     * The Category root Route
     * @example: "/category/"
     * @var string
     */
    public string $CategoryRoot;
    /**
     * The Tag root Route
     * @example: "/tag/"
     * @var string
     */
    public string $TagRoot;
    /**
     * The User root Route
     * @example: "/user/"
     * @var string
     */
    public string $UserRoot;

    public function __construct(
        ?string $name = null,
        ?string $directory = null,
        ?string $root = null,
        $pattern = null,
        $handler = null,
        $method = null,
        $global = false
    ) {
        $this->Name = $name;
        $this->Directory = str_replace(["\\", "/"], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $directory ?? DIRECTORY_SEPARATOR);
        $this->ModelDirectory = $this->Directory . "model" . DIRECTORY_SEPARATOR;
        $this->ViewDirectory = $this->Directory . "view" . DIRECTORY_SEPARATOR;
        $this->ComputeDirectory = $this->Directory . "compute" . DIRECTORY_SEPARATOR;
        $this->RouteDirectory = $this->Directory . "route" . DIRECTORY_SEPARATOR;
        $this->PrivateDirectory = $this->Directory . "private" . DIRECTORY_SEPARATOR;
        $this->PublicDirectory = $this->Directory . "public" . DIRECTORY_SEPARATOR;
        $this->AssetDirectory = $this->Directory . "asset" . DIRECTORY_SEPARATOR;
        $this->StorageDirectory = $this->Directory . "storage" . DIRECTORY_SEPARATOR;
        $this->TempDirectory = "temp" . DIRECTORY_SEPARATOR;
        $this->LogDirectory = $this->Directory . "log" . DIRECTORY_SEPARATOR;
        $this->LibraryDirectory = $this->ModelDirectory . "library" . DIRECTORY_SEPARATOR;
        $this->ComponentDirectory = $this->ModelDirectory . "component" . DIRECTORY_SEPARATOR;
        $this->TemplateDirectory = $this->ModelDirectory . "template" . DIRECTORY_SEPARATOR;
        $this->ModuleDirectory = $this->ModelDirectory . "module" . DIRECTORY_SEPARATOR;
        $this->PageDirectory = $this->ViewDirectory . "page" . DIRECTORY_SEPARATOR;
        $this->RegionDirectory = $this->ViewDirectory . "region" . DIRECTORY_SEPARATOR;
        $this->PartDirectory = $this->ViewDirectory . "part" . DIRECTORY_SEPARATOR;
        $this->ScriptDirectory = $this->AssetDirectory . "script" . DIRECTORY_SEPARATOR;
        $this->StyleDirectory = $this->AssetDirectory . "style" . DIRECTORY_SEPARATOR;

        $this->Root = str_replace(["\\", "/"], ["/", "/"], $root ?? "/");
        $this->AssetRoot = $this->Root . "asset/";
        $this->ScriptRoot = $this->AssetRoot . "script/";
        $this->StyleRoot = $this->AssetRoot . "style/";
        $this->ContentRoot = $this->Root . "post/";
        $this->CategoryRoot = $this->Root . "category/";
        $this->TagRoot = $this->Root . "tag/";
        $this->UserRoot = $this->Root . "user/";

        $this->Global = $global;
        $this->Refresh($pattern, $method)->Route($handler);
    }

    // public function __get($name)
    // {
    //     return $this[$this->PropertyName($name)];
    // }
    // public function __set($name, $value)
    // {
    //     $this[$this->PropertyName($name)] = $value;
    // }
    // public function PropertyName($name)
    // {
    //     return preg_replace("/\W+/", "", strToProper($name));
    // }

    // public function __get($name)
    // {
    //     //return $this->offsetExists($name = getMethodName($name)) ? $this->offsetGet($name) : ($this[$name] = []);
    //     return $this->Routes[getMethodName($name)];
    // }
    // public function __set($name, $value)
    // {
    //     return $this->Routes[getMethodName($name)] = $value;
    // }

    // public function __get($name)
    // {
    //     //return $this->offsetExists($name = getMethodName($name)) ? $this->offsetGet($name) : ($this[$name] = []);
    //     return $this->offsetGet(getMethodName($name));
    // }
    // public function __set($name, $value)
    // {
    //     return $this->offsetSet(getMethodName($name), $value);
    // }


    /**
     * To get handlers for the received request
     */
    public function Handlers()
    {
        $this->Method = $this->DefaultMethodName;
        do
            foreach ($this->Routes[$this->Method] ?? [] as $pat => $handlers)
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
            $this->Result = $handler() ?? $this->Result;
        return $this->Result;
    }
    /**
     * Echos and returns whole the contents
     */
    public function Render()
    {
        return render($this->Handle());
    }
    public function ToString()
    {
        ob_start();
        $output = $this->Render();
        return ob_get_clean() ?? $output;
    }

    /**
     * Refresh all properties, have no effect on routes
     * @return
     */
    public function Refresh($pattern = null, $method = null)
    {
        $this->IsActive = true;
        $this->Result = null;
        $this->Point = 0;
        $this->Taken = null;

        $this->Url = getUrl();
        $this->Host = getHost();
        $this->Site = getSite();
        $this->Domain = getDomain();
        $this->Path = getPath();
        $this->Request = getRequest();
        $this->Direction = getDirection();
        $this->Page = getPage();
        $this->Query = getQuery();
        $this->Fragment = getFragment();

        $this->DefaultMethodIndex = getMethodIndex();
        $this->DefaultMethodName = getMethodName();
        $this->Set($method)->On($pattern);
        return $this;
    }

    /**
     * If condition be true
     * @param $condition if condition
     * @return
     */
    public function if($condition)
    {
        if ($this->IsActive)
            $this->IsActive = Convert::By($condition, $this) ? true : false;
        return $this;
    }
    /**
     * Else or Else if condition
     * @param $condition else if condition (optional)
     * @return
     */
    public function else($condition = null)
    {
        if (!$this->IsActive)
            $this->IsActive = Convert::By($condition ?? true, $this) ? true : false;
        return $this;
    }
    /**
     * And condition to change update ability
     * @return
     */
    public function and($condition)
    {
        $this->IsActive = $this->IsActive && Convert::By($condition, $this);
        return $this;
    }
    /**
     * Or condition to change update ability
     * @return
     */
    public function or($condition)
    {
        $this->IsActive = $this->IsActive || Convert::By($condition, $this);
        return $this;
    }
    /**
     * Reset conditions to able you update routes
     * @return
     */
    public function anyway()
    {
        $this->IsActive = true;
        return $this;
    }

    /**
     * To change the default method to handle
     * @example: ->Post()->Switch()
     * @example: ->Delete()->Switch()
     * @return
     */
    public function Switch()
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
     * @return
     */
    public function On($pattern = null)
    {
        if ($this->IsActive)
            $this->Pattern = self::CreatePattern($pattern);
        return $this;
    }
    /**
     * To set routing of the request
     * @param int|string|null $method 0: ALL, 1:GET, 2:POST, 3:PUT, 4:FILE, 5:PATCH, 6:DELETE
     * @return
     */
    public function Set($method = null)
    {
        if ($this->IsActive)
            if (!isset($this->Routes[$this->Method = getMethodName($method)]))
                $this->Routes[$this->Method] = [];
        return $this;
    }
    /**
     * Remove the current pattern from the current or defined method
     * @param int|string|null $method 0: ALL, 1:GET, 2:POST, 3:PUT, 4:FILE, 5:PATCH, 6:DELETE
     * @return
     */
    public function Unset($method = null)
    {
        if ($this->IsActive)
            unset($this->Routes[$method ? getMethodName($method) : $this->Method][$this->Pattern]);
        return $this;
    }
    /**
     * Remove the current pattern from all methods
     * @return
     */
    public function Reset()
    {
        if ($this->IsActive)
            foreach ($this->Routes as $mthd => $pttrns)
                unset($this->Routes[$mthd][$this->Pattern]);
        return $this;
    }
    /**
     * Remove all patterns from all methods
     * @return
     */
    public function Clear()
    {
        if ($this->IsActive)
            foreach ($this->Routes as $mthd => $pttrns)
                $this->Routes[$mthd] = [];
        return $this;
    }

    /**
     * Add new route for the current request method and pattern
     * @param mixed $handler A route name or handeler function
     * @return
     */
    public function Route($handler, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
    {
        if ($this->IsActive && $handler) {
            $method = $this->Method;
            $pattern = $this->Pattern;
            $h = function () use ($handler, $data, $print, $origin, $depth, $alternative, $default) {
                return (isStatic($handler ?? "")) ? route($handler ?? $this->Direction, $data, $print, $origin, $depth, $alternative, $default) :
                    Convert::By($handler, $this);
            };
            if (isset($this->Routes[$method][$pattern]))
                $this->Routes[$method][$pattern][] = $h;
            else
                $this->Routes[$method][$pattern] = [$h];
        }
        return $this;
    }
    /**
     * Add new route for all other requests methods
     * Leave null to clear other requests
     * @param mixed $handler A route name or handeler function, Leave null to clear other requests
     * @return
     */
    public function Rest($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
    {
        if ($this->IsActive) {
            $method = $this->Method;
            $pattern = $this->Pattern;
            if ($handler) {
                $h = function () use ($handler, $data, $print, $origin, $depth, $alternative, $default) {
                    return (isStatic($handler ?? "")) ? route($handler ?? $this->Direction, $data, $print, $origin, $depth, $alternative, $default) :
                        Convert::By($handler, $this);
                };
                foreach ($this->Routes as $mthd => $pttrns)
                    if ($mthd !== $method && $mthd !== "ALL" && !isset($this->Routes[$mthd][$pattern]))
                        $this->Routes[$mthd][$pattern] = [$h];
            } else
                foreach ($this->Routes as $mthd => $pttrns)
                    if ($mthd !== $method && $mthd !== "ALL")
                        unset($this->Routes[$mthd][$pattern]);
        }
        return $this;
    }
    /**
     * Add new handler for ALL requests, to handle if specified methods could not handle before
     * @param string|null|callable $handler A route name or a handeler function or null for default routing by route directory
     * @param mixed $data The passed data 
     * @param string|null|int $alternative An alternative route name
     * @return
     */
    public function Default($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
    {
        if ($this->IsActive) {
            $pattern = $this->Pattern;
            $h = function () use ($handler, $data, $print, $origin, $depth, $alternative, $default) {
                if ($this->Point <= 1 || $this->Global)
                    return (isStatic($handler ?? "")) ? route($handler ?? $this->Direction, $data, $print, $origin, $depth, $alternative, $default) :
                        Convert::By($handler, $this);
                return null;
            };
            foreach ($this->Routes as $mthd => $pttrns)
                if ($mthd !== "ALL") {
                    if (isset($this->Routes[$mthd][$pattern]))
                        $this->Routes[$mthd][$pattern][] = $h;
                    else
                        $this->Routes[$mthd][$pattern] = [$h];
                }
        }
        return $this;
    }

    /**
     * Add new route for ALL requests
     * @param string|callable $handler A route name or a handeler function
     * @return
     */
    public function All($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
    {
        return $this->Set("ALL")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the GET requests
     * @param string|callable $handler A route name or a handeler function
     * @return
     */
    public function Get($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
    {
        return $this->Set("GET")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the POST requests
     * @param string|callable $handler A route name or a handeler function
     * @return
     */
    public function Post($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
    {
        return $this->Set("POST")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the PUT requests
     * @param string|callable $handler A route name or a handeler function
     * @return
     */
    public function Put($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
    {
        return $this->Set("PUT")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the FILE requests
     * @param string|callable $handler A route name or a handeler function
     * @return
     */
    public function File($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
    {
        return $this->Set("FILE")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the PATCH requests
     * @param string|callable $handler A route name or a handeler function
     * @return
     */
    public function Patch($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
    {
        return $this->Set("PATCH")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }
    /**
     * Add new route to the DELETE requests
     * @param string|callable $handler A route name or a handeler function
     * @return
     */
    public function Delete($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
    {
        return $this->Set("DELETE")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }

    /**
     * Add new route to the STREAM requests
     * @param string|callable $handler A route name or a handeler function
     * @return
     */
    public function Stream($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
    {
        return $this->Set("STREAM")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }

    /**
     * Add new route to the INTERNAL requests
     * @param string|callable $handler A route name or a handeler function
     * @return
     */
    public function Internal($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
    {
        return $this->Set("INTERNAL")->Route($handler, $data, $print, $origin, $depth, $alternative, $default);
    }

    /**
     * Add new route to the EXTERNAL requests
     * @param string|callable $handler A route name or a handeler function
     * @return
     */
    public function External($handler = null, mixed $data = null, bool $print = true, int $origin = 0, int $depth = 99, string|null $alternative = null, $default = null)
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
            return isPattern($pattern) ?
                $pattern :
                ("/^\\/?" . str_replace("/", "\/", $pattern) . "(\\.\\w*)*(?=\\/|\\\|\\?|#|@|$)/i");
        return $default;
    }
}