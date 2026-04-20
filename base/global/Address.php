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
     * @example
     * "/"
     */
    public string|null $RootDirectory;
    /**
     * @example
     * "/model/"
     */
    public string $ModelRootDirectory;
    /**
     * @example
     * "/view/"
     */
    public string $ViewRootDirectory;
    /**
     * @example
     * "/compute/"
     */

    public string $ComputeRootDirectory;
    /**
     * @example
     * "/route/"
     */
    public string $RouteRootDirectory;
    /**
     * @example
     * "/asset/"
     */
    public string $AssetRootDirectory;
    /**
     * @example
     * "/model/library/"
     */
    public string $LibraryRootDirectory;
    /**
     * @example
     * "/model/component/"
     */
    public string $ComponentRootDirectory;
    /**
     * @example
     * "/model/template/"
     */
    public string $TemplateRootDirectory;
    /**
     * @example
     * "/model/module/"
     */
    public string $ModuleRootDirectory;
    /**
     * @example
     * "/model/plugin/"
     */
    public string $PluginRootDirectory;
    /**
     * @example
     * "/view/page/"
     */
    public string $PageRootDirectory;
    /**
     * @example
     * "/view/region/"
     */
    public string $RegionRootDirectory;
    /**
     * @example
     * "/view/part/"
     */
    public string $PartRootDirectory;
    /**
     * @example
     * "/asset/struct/"
     */
    public string $StructRootDirectory;
    /**
     * @example
     * "/asset/script/"
     */
    public string $ScriptRootDirectory;
    /**
     * @example
     * "/asset/style/"
     */
    public string $StyleRootDirectory;
    /**
     * @example
     * "/temp/"
     */
    public string $TempRootDirectory;
    /**
     * @example
     * "/log/"
     */
    public string $LogRootDirectory;
    /**
     * @example
     * "/private/"
     */
    public string $PrivateRootDirectory;
    /**
     * @example
     * "/public/"
     */
    public string $PublicRootDirectory;

    /**
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
     * "home/public_html/model/plugin/"
     */
    public string $PluginDirectory;
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
     * Full part of the current url
     * @example "https://www.mimfa.net:80/Category/mimfa/service/web.php?p=3&l=10#serp"
     * @var string|null
     */
    public string|null $Url = null;
    /**
     * The Base part of the current url
     * @example "https://www.mimfa.net:80/Category/mimfa/service/web.php"
     * @var string|null
     */
    public string|null $UrlBase = null;
    /**
     * The Origin part of the current url
     * @example "https://www.mimfa.net:80"
     * @var string|null
     */
    public string|null $UrlOrigin = null;
    /**
     * The Host name part of the current url
     * @example "www.mimfa.net"
     * @var string|null
     */
    public string|null $UrlHost = null;
    /**
     * The Domain name part of the current url
     * @example "mimfa.net"
     * @var string|null
     */
    public string|null $UrlDomain = null;
    /**
     * The Request part of the current url
     * @example "/Category/mimfa/service/web.php?p=3&l=10#serp"
     * @var string|null
     */
    public string|null $UrlRequest = null;
    /**
     * The Path part of the current url from the root
     * @example "/Category/mimfa/service/web.php"
     * @var string|null
     */
    public string|null $UrlPath = null;
    /**
     * The Route part of the current url from the root
     * @example "Category/mimfa/service/web.php"
     * @var string|null
     */
    public string|null $UrlRoute = null;
    /**
     * The last part of the current direction url
     * @example "web.php"
     * @var string|null
     */
    public string|null $UrlResource = null;
    /**
     * The query part of the current url
     * @example "p=3&l=10"
     * @var string|null
     */
    public string|null $UrlQuery = null;
    /**
     * The fragment or anchor part of the current url
     * @example "serp"
     * @var string|null
     */
    public string|null $UrlFragment = null;


    /**
     * The root path
     * @example
     * "/"
     * @var string|null
     */
    public string|null $RootUrlPath;
    /**
     * The Public root Route
     * @example
     * "/public/"
     * @var string
     */
    public string $PublicRootUrlPath;
    /**
     * The Temp root Route
     * @example
     * "/temp/"
     * @var string
     */
    public string $TempRootUrlPath;
    /**
     * The Asset root Route
     * @example
     * "/asset/"
     * @var string
     */
    public string $AssetRootUrlPath;
    /**
     * The Script root Route
     * @example
     * "/script/"
     * @var string
     */
    public string $ScriptRootUrlPath;
    /**
     * The Style root Route
     * @example
     * "/style/"
     * @var string
     */
    public string $StyleRootUrlPath;
    /**
     * The Content root Route
     * @example
     * "/content/"
     * @var string
     */
    public string $ContentRootUrlPath;
    /**
     * The Category root Route
     * @example
     * "/category/"
     * @var string
     */
    public string $CategoryRootUrlPath;
    /**
     * The Tag root Route
     * @example
     * "/tag/"
     * @var string
     */
    public string $TagRootUrlPath;
    /**
     * The Search root Route
     * @example
     * "/search/"
     * @var string
     */
    public string $SearchRootUrlPath;
    /**
     * The User root Route
     * @example
     * "/user/"
     * @var string
     */
    public string $UserRootUrlPath;


    public function __construct(
        ?string $name = null,
        ?string $directory = null
    ) {
        $this->Name = $name;

        $this->RootDirectory = DIRECTORY_SEPARATOR;
        $this->ModelRootDirectory = $this->RootDirectory . "model" . DIRECTORY_SEPARATOR;
        $this->ViewRootDirectory = $this->RootDirectory . "view" . DIRECTORY_SEPARATOR;
        $this->ComputeRootDirectory = $this->RootDirectory . "compute" . DIRECTORY_SEPARATOR;
        $this->RouteRootDirectory = $this->RootDirectory . "route" . DIRECTORY_SEPARATOR;
        $this->AssetRootDirectory = $this->RootDirectory . "asset" . DIRECTORY_SEPARATOR;
        $this->TempRootDirectory = $this->RootDirectory . "temp" . DIRECTORY_SEPARATOR;
        $this->LibraryRootDirectory = $this->ModelRootDirectory . "library" . DIRECTORY_SEPARATOR;
        $this->ComponentRootDirectory = $this->ModelRootDirectory . "component" . DIRECTORY_SEPARATOR;
        $this->TemplateRootDirectory = $this->ModelRootDirectory . "template" . DIRECTORY_SEPARATOR;
        $this->ModuleRootDirectory = $this->ModelRootDirectory . "module" . DIRECTORY_SEPARATOR;
        $this->PluginRootDirectory = $this->ModelRootDirectory . "plugin" . DIRECTORY_SEPARATOR;
        $this->PageRootDirectory = $this->ViewRootDirectory . "page" . DIRECTORY_SEPARATOR;
        $this->RegionRootDirectory = $this->ViewRootDirectory . "region" . DIRECTORY_SEPARATOR;
        $this->PartRootDirectory = $this->ViewRootDirectory . "part" . DIRECTORY_SEPARATOR;
        $this->StructRootDirectory = $this->AssetRootDirectory . "struct" . DIRECTORY_SEPARATOR;
        $this->ScriptRootDirectory = $this->AssetRootDirectory . "script" . DIRECTORY_SEPARATOR;
        $this->StyleRootDirectory = $this->AssetRootDirectory . "style" . DIRECTORY_SEPARATOR;
        $this->LogRootDirectory = $this->RootDirectory . "log" . DIRECTORY_SEPARATOR;
        $this->PrivateRootDirectory = $this->RootDirectory . "private" . DIRECTORY_SEPARATOR;
        $this->PublicRootDirectory = $this->RootDirectory . "public" . DIRECTORY_SEPARATOR;

        $directory = rtrim(str_replace(["\\", "/"], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $directory), DIRECTORY_SEPARATOR);

        $this->Directory = $directory . DIRECTORY_SEPARATOR;
        $this->ModelDirectory = $directory . $this->ModelRootDirectory;
        $this->ViewDirectory = $directory . $this->ViewRootDirectory;
        $this->ComputeDirectory = $directory . $this->ComputeRootDirectory;
        $this->RouteDirectory = $directory . $this->RouteRootDirectory;
        $this->AssetDirectory = $directory . $this->AssetRootDirectory;
        $this->TempDirectory = $directory . $this->TempRootDirectory;
        $this->LibraryDirectory = $directory . $this->LibraryRootDirectory;
        $this->ComponentDirectory = $directory . $this->ComponentRootDirectory;
        $this->TemplateDirectory = $directory . $this->TemplateRootDirectory;
        $this->ModuleDirectory = $directory . $this->ModuleRootDirectory;
        $this->PluginDirectory = $directory . $this->PluginRootDirectory;
        $this->PageDirectory = $directory . $this->PageRootDirectory;
        $this->RegionDirectory = $directory . $this->RegionRootDirectory;
        $this->PartDirectory = $directory . $this->PartRootDirectory;
        $this->StructDirectory = $directory . $this->StructRootDirectory;
        $this->ScriptDirectory = $directory . $this->ScriptRootDirectory;
        $this->StyleDirectory = $directory . $this->StyleRootDirectory;
        $this->LogDirectory = $directory . $this->LogRootDirectory;
        $this->PrivateDirectory = $directory . $this->PrivateRootDirectory;
        $this->PublicDirectory = $directory . $this->PublicRootDirectory;

        $this->RootUrlPath = "/";
        $this->PublicRootUrlPath = $this->RootUrlPath . "public/";
        $this->TempRootUrlPath = $this->RootUrlPath . "temp/";
        $this->AssetRootUrlPath = $this->RootUrlPath . "asset/";
        $this->ScriptRootUrlPath = $this->AssetRootUrlPath . "script/";
        $this->StyleRootUrlPath = $this->AssetRootUrlPath . "style/";
        $this->ContentRootUrlPath = $this->RootUrlPath . "content/";
        $this->CategoryRootUrlPath = $this->RootUrlPath . "category/";
        $this->TagRootUrlPath = $this->RootUrlPath . "tag/";
        $this->SearchRootUrlPath = $this->RootUrlPath . "search/";
        $this->UserRootUrlPath = $this->RootUrlPath . "user/";
        
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