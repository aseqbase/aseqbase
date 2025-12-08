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

    public string|null $Directory;
    public string $ModelDirectory;
    public string $ViewDirectory;
    public string $ComputeDirectory;
    public string $RouteDirectory;
    public string $AssetDirectory;
    public string $StorageDirectory;
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
    public string $PackageDirectory;
    public string $TempDirectory;
    public string $LogDirectory;
    public string $PrivateDirectory;
    public string $PublicDirectory;

    public string $Address;
    public string $ModelAddress;
    public string $ViewAddress;
    public string $ComputeAddress;
    public string $RouteAddress;
    public string $AssetAddress;
    public string $StorageAddress;
    public string $LibraryAddress;
    public string $ComponentAddress;
    public string $TemplateAddress;
    public string $ModuleAddress;
    public string $PageAddress;
    public string $RegionAddress;
    public string $PartAddress;
    public string $StructAddress;
    public string $ScriptAddress;
    public string $StyleAddress;
    public string $PackageAddress;
    public string $TempAddress;
    public string $LogAddress;
    public string $PrivateAddress;
    public string $PublicAddress;

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
     * The Search root Route
     * @example: "/search/"
     * @var string
     */
    public string $SearchRoot;
    /**
     * The User root Route
     * @example: "/user/"
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
        $this->StorageDirectory = $this->Directory . "storage" . DIRECTORY_SEPARATOR;
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
        $this->PackageDirectory = $this->AssetDirectory . "package" . DIRECTORY_SEPARATOR;
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
        $this->StorageAddress = $directory . $this->StorageDirectory;
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
        $this->PackageAddress = $directory . $this->PackageDirectory;
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