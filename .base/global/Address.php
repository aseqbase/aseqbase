<?php
/*
 * A library to contain directories and pathes
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/global#address See the Library Documentation
 */
class Address extends ArrayObject
{
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
    public string $StructDirectory;
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
        ?string $directory = null,
        ?string $root = null
    ) {
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
        $this->StructDirectory = $this->AssetDirectory . "struct" . DIRECTORY_SEPARATOR;
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
     * Refresh all properties, have no effect on routes
     * @return
     */
    public function Initial()
    {
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
        return $this;
    }
}