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
    public string|null $Directory;
    /**
     * @example
     * "/model/"
     */
    public string $ModelDirectory;
    /**
     * @example
     * "/view/"
     */
    public string $ViewDirectory;
    /**
     * @example
     * "/compute/"
     */

    public string $ComputeDirectory;
    /**
     * @example
     * "/route/"
     */
    public string $RouteDirectory;
    /**
     * @example
     * "/asset/"
     */
    public string $AssetDirectory;
    /**
     * @example
     * "/model/library/"
     */
    public string $LibraryDirectory;
    /**
     * @example
     * "/model/component/"
     */
    public string $ComponentDirectory;
    /**
     * @example
     * "/model/template/"
     */
    public string $TemplateDirectory;
    /**
     * @example
     * "/model/module/"
     */
    public string $ModuleDirectory;
    /**
     * @example
     * "/view/page/"
     */
    public string $PageDirectory;
    /**
     * @example
     * "/view/region/"
     */
    public string $RegionDirectory;
    /**
     * @example
     * "/view/part/"
     */
    public string $PartDirectory;
    /**
     * @example
     * "/asset/struct/"
     */
    public string $StructDirectory;
    /**
     * @example
     * "/asset/script/"
     */
    public string $ScriptDirectory;
    /**
     * @example
     * "/asset/style/"
     */
    public string $StyleDirectory;
    /**
     * @example
     * "/temp/"
     */
    public string $TempDirectory;
    /**
     * @example
     * "/log/"
     */
    public string $LogDirectory;
    /**
     * @example
     * "/private/"
     */
    public string $PrivateDirectory;
    /**
     * @example
     * "/public/"
     */
    public string $PublicDirectory;

    /**
     * 
     * @example
     * "home/public_html/"
     */
    public string $Address;
    /**
     * @example
     * "home/public_html/model/"
     */
    public string $ModelAddress;
    /**
     * @example
     * "home/public_html/view/"
     */
    public string $ViewAddress;
    /**
     * @example
     * "home/public_html/compute/"
     */
    public string $ComputeAddress;
    /**
     * @example
     * "home/public_html/route/"
     */
    public string $RouteAddress;
    /**
     * @example
     * "home/public_html/asset/"
     */
    public string $AssetAddress;
    /**
     * @example
     * "home/public_html/model/library/"
     */
    public string $LibraryAddress;
    /**
     * @example
     * "home/public_html/model/component/"
     */
    public string $ComponentAddress;
    /**
     * @example
     * "home/public_html/model/template/"
     */
    public string $TemplateAddress;
    /**
     * @example
     * "home/public_html/model/module/"
     */
    public string $ModuleAddress;
    /**
     * @example
     * "home/public_html/view/page/"
     */
    public string $PageAddress;
    /**
     * @example
     * "home/public_html/view/region/"
     */
    public string $RegionAddress;
    /**
     * @example
     * "home/public_html/view/part/"
     */
    public string $PartAddress;
    /**
     * @example
     * "home/public_html/asset/struct/"
     */
    public string $StructAddress;
    /**
     * @example
     * "home/public_html/asset/script/"
     */
    public string $ScriptAddress;
    /**
     * @example
     * "home/public_html/asset/style/"
     */
    public string $StyleAddress;
    /**
     * @example
     * "home/public_html/temp/"
     */
    public string $TempAddress;
    /**
     * @example
     * "home/public_html/log/"
     */
    public string $LogAddress;
    /**
     * @example
     * "home/public_html/private/"
     */
    public string $PrivateAddress;
    /**
     * @example
     * "home/public_html/public/"
     */
    public string $PublicAddress;

    /**
     * The root path
     * @example
     * "/"
     * @var string|null
     */
    public string|null $Root;
    /**
     * The Asset root Route
     * @example
     * "/asset/"
     * @var string
     */
    public string $AssetRoot;
    /**
     * The Script root Route
     * @example
     * "/script/"
     * @var string
     */
    public string $ScriptRoot;
    /**
     * The Style root Route
     * @example
     * "/style/"
     * @var string
     */
    public string $StyleRoot;
    /**
     * The Content root Route
     * @example
     * "/content/"
     * @var string
     */
    public string $ContentRoot;
    /**
     * The Category root Route
     * @example
     * "/category/"
     * @var string
     */
    public string $CategoryRoot;
    /**
     * The Tag root Route
     * @example
     * "/tag/"
     * @var string
     */
    public string $TagRoot;
    /**
     * The Search root Route
     * @example
     * "/search/"
     * @var string
     */
    public string $SearchRoot;
    /**
     * The User root Route
     * @example
     * "/user/"
     * @var string
     */
    public string $UserRoot;


    public function __construct(
        ?string $name = null,
        ?string $directory = null
    ) {
        $this->Name = $name;

        $this->Directory = DIRECTORY_SEPARATOR;
        $this->ModelDirectory = $this->Directory . "model" . DIRECTORY_SEPARATOR;
        $this->ViewDirectory = $this->Directory . "view" . DIRECTORY_SEPARATOR;
        $this->ComputeDirectory = $this->Directory . "compute" . DIRECTORY_SEPARATOR;
        $this->RouteDirectory = $this->Directory . "route" . DIRECTORY_SEPARATOR;
        $this->AssetDirectory = $this->Directory . "asset" . DIRECTORY_SEPARATOR;
        $this->TempDirectory = $this->Directory . "temp" . DIRECTORY_SEPARATOR;
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
        $this->LogDirectory = $this->Directory . "log" . DIRECTORY_SEPARATOR;
        $this->PrivateDirectory = $this->Directory . "private" . DIRECTORY_SEPARATOR;
        $this->PublicDirectory = $this->Directory . "public" . DIRECTORY_SEPARATOR;

        $directory = rtrim(str_replace(["\\", "/"], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $directory), DIRECTORY_SEPARATOR);

        $this->Address = $directory . DIRECTORY_SEPARATOR;
        $this->ModelAddress = $directory . $this->ModelDirectory;
        $this->ViewAddress = $directory . $this->ViewDirectory;
        $this->ComputeAddress = $directory . $this->ComputeDirectory;
        $this->RouteAddress = $directory . $this->RouteDirectory;
        $this->AssetAddress = $directory . $this->AssetDirectory;
        $this->TempAddress = $directory . $this->TempDirectory;
        $this->LibraryAddress = $directory . $this->LibraryDirectory;
        $this->ComponentAddress = $directory . $this->ComponentDirectory;
        $this->TemplateAddress = $directory . $this->TemplateDirectory;
        $this->ModuleAddress = $directory . $this->ModuleDirectory;
        $this->PageAddress = $directory . $this->PageDirectory;
        $this->RegionAddress = $directory . $this->RegionDirectory;
        $this->PartAddress = $directory . $this->PartDirectory;
        $this->StructAddress = $directory . $this->StructDirectory;
        $this->ScriptAddress = $directory . $this->ScriptDirectory;
        $this->StyleAddress = $directory . $this->StyleDirectory;
        $this->LogAddress = $directory . $this->LogDirectory;
        $this->PrivateAddress = $directory . $this->PrivateDirectory;
        $this->PublicAddress = $directory . $this->PublicDirectory;

        $this->Root = "/";
        $this->AssetRoot = $this->Root . "asset/";
        $this->ScriptRoot = $this->AssetRoot . "script/";
        $this->StyleRoot = $this->AssetRoot . "style/";
        $this->ContentRoot = $this->Root . "post/";
        $this->CategoryRoot = $this->Root . "category/";
        $this->TagRoot = $this->Root . "tag/";
        $this->SearchRoot = $this->Root . "search/";
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
}