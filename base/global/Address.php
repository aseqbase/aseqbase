<?php
/*
 * A library to work by routes and paths
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#router See the Library Documentation
 */
class Address extends ArrayObject
{
    /**
     * The top layer Name of this sequence
     */
    public string|null $Name;

    /**
     * Full part of the current url
     * @example: "https://www.mimfa.net:80/Category/mimfa/service/web.php?p=3&l=10#serp"
     * @var string|null
     */
    public string|null $Url = null;
    /**
     * The Base part of the current url
     * @example: "https://www.mimfa.net:80/Category/mimfa/service/web.php"
     * @var string|null
     */
    public string|null $UrlBase = null;
    /**
     * The Origin part of the current url
     * @example: "https://www.mimfa.net:80"
     * @var string|null
     */
    public string|null $UrlOrigin = null;
    /**
     * The Host name part of the current url
     * @example: "www.mimfa.net"
     * @var string|null
     */
    public string|null $UrlHost = null;
    /**
     * The Domain name part of the current url
     * @example: "mimfa.net"
     * @var string|null
     */
    public string|null $UrlDomain = null;
    /**
     * The Request part of the current url
     * @example: "/Category/mimfa/service/web.php?p=3&l=10#serp"
     * @var string|null
     */
    public string|null $UrlRequest = null;
    /**
     * The Path part of the current url from the root
     * @example: "/Category/mimfa/service/web.php"
     * @var string|null
     */
    public string|null $UrlPath = null;
    /**
     * The Route part of the current url from the root
     * @example: "Category/mimfa/service/web.php"
     * @var string|null
     */
    public string|null $UrlRoute = null;
    /**
     * The last part of the current direction url
     * @example: "web.php"
     * @var string|null
     */
    public string|null $UrlResource = null;
    /**
     * The query part of the current url
     * @example: "p=3&l=10"
     * @var string|null
     */
    public string|null $UrlQuery = null;
    /**
     * The fragment or anchor part of the current url
     * @example: "serp"
     * @var string|null
     */
    public string|null $UrlFragment = null;


    /**
     * @example
     * "/"
     */
    public string|null $GlobalDirectory;
    /**
     * @example
     * "/model/"
     */
    public string $GlobalModelDirectory;
    /**
     * @example
     * "/view/"
     */
    public string $GlobalViewDirectory;
    /**
     * @example
     * "/compute/"
     */

    public string $GlobalComputeDirectory;
    /**
     * @example
     * "/route/"
     */
    public string $GlobalRouteDirectory;
    /**
     * @example
     * "/asset/"
     */
    public string $GlobalAssetDirectory;
    /**
     * @example
     * "/model/library/"
     */
    public string $GlobalLibraryDirectory;
    /**
     * @example
     * "/model/component/"
     */
    public string $GlobalComponentDirectory;
    /**
     * @example
     * "/model/template/"
     */
    public string $GlobalTemplateDirectory;
    /**
     * @example
     * "/model/module/"
     */
    public string $GlobalModuleDirectory;
    /**
     * @example
     * "/view/page/"
     */
    public string $GlobalPageDirectory;
    /**
     * @example
     * "/view/region/"
     */
    public string $GlobalRegionDirectory;
    /**
     * @example
     * "/view/part/"
     */
    public string $GlobalPartDirectory;
    /**
     * @example
     * "/asset/struct/"
     */
    public string $GlobalStructDirectory;
    /**
     * @example
     * "/asset/script/"
     */
    public string $GlobalScriptDirectory;
    /**
     * @example
     * "/asset/style/"
     */
    public string $GlobalStyleDirectory;
    /**
     * @example
     * "/temp/"
     */
    public string $GlobalTempDirectory;
    /**
     * @example
     * "/log/"
     */
    public string $GlobalLogDirectory;
    /**
     * @example
     * "/private/"
     */
    public string $GlobalPrivateDirectory;
    /**
     * @example
     * "/public/"
     */
    public string $GlobalPublicDirectory;

    /**
     * 
     * @example
     * "home/public_html/"
     */
    public string $Directory;
    /**
     * @example
     * "home/public_html/model/"
     */
    public string $ModelDirectory;
    /**
     * @example
     * "home/public_html/view/"
     */
    public string $ViewDirectory;
    /**
     * @example
     * "home/public_html/compute/"
     */
    public string $ComputeDirectory;
    /**
     * @example
     * "home/public_html/route/"
     */
    public string $RouteDirectory;
    /**
     * @example
     * "home/public_html/asset/"
     */
    public string $AssetDirectory;
    /**
     * @example
     * "home/public_html/model/library/"
     */
    public string $LibraryDirectory;
    /**
     * @example
     * "home/public_html/model/component/"
     */
    public string $ComponentDirectory;
    /**
     * @example
     * "home/public_html/model/template/"
     */
    public string $TemplateDirectory;
    /**
     * @example
     * "home/public_html/model/module/"
     */
    public string $ModuleDirectory;
    /**
     * @example
     * "home/public_html/view/page/"
     */
    public string $PageDirectory;
    /**
     * @example
     * "home/public_html/view/region/"
     */
    public string $RegionDirectory;
    /**
     * @example
     * "home/public_html/view/part/"
     */
    public string $PartDirectory;
    /**
     * @example
     * "home/public_html/asset/struct/"
     */
    public string $StructDirectory;
    /**
     * @example
     * "home/public_html/asset/script/"
     */
    public string $ScriptDirectory;
    /**
     * @example
     * "home/public_html/asset/style/"
     */
    public string $StyleDirectory;
    /**
     * @example
     * "home/public_html/temp/"
     */
    public string $TempDirectory;
    /**
     * @example
     * "home/public_html/log/"
     */
    public string $LogDirectory;
    /**
     * @example
     * "home/public_html/private/"
     */
    public string $PrivateDirectory;
    /**
     * @example
     * "home/public_html/public/"
     */
    public string $PublicDirectory;

    /**
     * The root path
     * @example
     * "/"
     * @var string|null
     */
    public string|null $RootPath;
    /**
     * The Public root Route
     * @example
     * "/public/"
     * @var string
     */
    public string $PublicRootPath;
    /**
     * The Asset root Route
     * @example
     * "/asset/"
     * @var string
     */
    public string $AssetRootPath;
    /**
     * The Script root Route
     * @example
     * "/script/"
     * @var string
     */
    public string $ScriptRootPath;
    /**
     * The Style root Route
     * @example
     * "/style/"
     * @var string
     */
    public string $StyleRootPath;
    /**
     * The Content root Route
     * @example
     * "/content/"
     * @var string
     */
    public string $ContentRootPath;
    /**
     * The Category root Route
     * @example
     * "/category/"
     * @var string
     */
    public string $CategoryRootPath;
    /**
     * The Tag root Route
     * @example
     * "/tag/"
     * @var string
     */
    public string $TagRootPath;
    /**
     * The Search root Route
     * @example
     * "/search/"
     * @var string
     */
    public string $SearchRootPath;
    /**
     * The User root Route
     * @example
     * "/user/"
     * @var string
     */
    public string $UserRootPath;


    public function __construct(
        ?string $name = null,
        ?string $directory = null
    ) {
        $this->Name = $name;

        $this->GlobalDirectory = DIRECTORY_SEPARATOR;
        $this->GlobalModelDirectory = $this->GlobalDirectory . "model" . DIRECTORY_SEPARATOR;
        $this->GlobalViewDirectory = $this->GlobalDirectory . "view" . DIRECTORY_SEPARATOR;
        $this->GlobalComputeDirectory = $this->GlobalDirectory . "compute" . DIRECTORY_SEPARATOR;
        $this->GlobalRouteDirectory = $this->GlobalDirectory . "route" . DIRECTORY_SEPARATOR;
        $this->GlobalAssetDirectory = $this->GlobalDirectory . "asset" . DIRECTORY_SEPARATOR;
        $this->GlobalTempDirectory = $this->GlobalDirectory . "temp" . DIRECTORY_SEPARATOR;
        $this->GlobalLibraryDirectory = $this->GlobalModelDirectory . "library" . DIRECTORY_SEPARATOR;
        $this->GlobalComponentDirectory = $this->GlobalModelDirectory . "component" . DIRECTORY_SEPARATOR;
        $this->GlobalTemplateDirectory = $this->GlobalModelDirectory . "template" . DIRECTORY_SEPARATOR;
        $this->GlobalModuleDirectory = $this->GlobalModelDirectory . "module" . DIRECTORY_SEPARATOR;
        $this->GlobalPageDirectory = $this->GlobalViewDirectory . "page" . DIRECTORY_SEPARATOR;
        $this->GlobalRegionDirectory = $this->GlobalViewDirectory . "region" . DIRECTORY_SEPARATOR;
        $this->GlobalPartDirectory = $this->GlobalViewDirectory . "part" . DIRECTORY_SEPARATOR;
        $this->GlobalStructDirectory = $this->GlobalAssetDirectory . "struct" . DIRECTORY_SEPARATOR;
        $this->GlobalScriptDirectory = $this->GlobalAssetDirectory . "script" . DIRECTORY_SEPARATOR;
        $this->GlobalStyleDirectory = $this->GlobalAssetDirectory . "style" . DIRECTORY_SEPARATOR;
        $this->GlobalLogDirectory = $this->GlobalDirectory . "log" . DIRECTORY_SEPARATOR;
        $this->GlobalPrivateDirectory = $this->GlobalDirectory . "private" . DIRECTORY_SEPARATOR;
        $this->GlobalPublicDirectory = $this->GlobalDirectory . "public" . DIRECTORY_SEPARATOR;

        $directory = rtrim(str_replace(["\\", "/"], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $directory), DIRECTORY_SEPARATOR);

        $this->Directory = $directory . DIRECTORY_SEPARATOR;
        $this->ModelDirectory = $directory . $this->GlobalModelDirectory;
        $this->ViewDirectory = $directory . $this->GlobalViewDirectory;
        $this->ComputeDirectory = $directory . $this->GlobalComputeDirectory;
        $this->RouteDirectory = $directory . $this->GlobalRouteDirectory;
        $this->AssetDirectory = $directory . $this->GlobalAssetDirectory;
        $this->TempDirectory = $directory . $this->GlobalTempDirectory;
        $this->LibraryDirectory = $directory . $this->GlobalLibraryDirectory;
        $this->ComponentDirectory = $directory . $this->GlobalComponentDirectory;
        $this->TemplateDirectory = $directory . $this->GlobalTemplateDirectory;
        $this->ModuleDirectory = $directory . $this->GlobalModuleDirectory;
        $this->PageDirectory = $directory . $this->GlobalPageDirectory;
        $this->RegionDirectory = $directory . $this->GlobalRegionDirectory;
        $this->PartDirectory = $directory . $this->GlobalPartDirectory;
        $this->StructDirectory = $directory . $this->GlobalStructDirectory;
        $this->ScriptDirectory = $directory . $this->GlobalScriptDirectory;
        $this->StyleDirectory = $directory . $this->GlobalStyleDirectory;
        $this->LogDirectory = $directory . $this->GlobalLogDirectory;
        $this->PrivateDirectory = $directory . $this->GlobalPrivateDirectory;
        $this->PublicDirectory = $directory . $this->GlobalPublicDirectory;

        $this->RootPath = "/";
        $this->PublicRootPath = $this->RootPath . "public/";
        $this->AssetRootPath = $this->RootPath . "asset/";
        $this->ScriptRootPath = $this->AssetRootPath . "script/";
        $this->StyleRootPath = $this->AssetRootPath . "style/";
        $this->ContentRootPath = $this->RootPath . "content/";
        $this->CategoryRootPath = $this->RootPath . "category/";
        $this->TagRootPath = $this->RootPath . "tag/";
        $this->SearchRootPath = $this->RootPath . "search/";
        $this->UserRootPath = $this->RootPath . "user/";
        
        $this->Url = getUrl();
        $this->UrlBase = getUrlBase();
        $this->UrlOrigin = getUrlOrigin();
        $this->UrlHost = getUrlHost();
        $this->UrlDomain = getUrlDomain();
        $this->UrlRequest = getUrlRequest();
        $this->UrlPath = getUrlPath();
        $this->UrlRoute = getUrlRoute();
        $this->UrlResource = getUrlResource();
        $this->UrlQuery = getUrlQuery();
        $this->UrlFragment = getUrlFragment();
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
}