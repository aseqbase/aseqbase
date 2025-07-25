<?php

class AddressBase extends ArrayObject
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

	public string|null $Route;
	public string $AssetRoute;
	public string $ScriptRoute;
	public string $StyleRoute;
	public string $ContentRoute;
	public string $CategoryRoute;
	public string $TagRoute;
	public string $UserRoute;

	public function __construct(?string $name = null, ?string $rootDir = null, ?string $rootRoute = null)
	{
		$this->Name = $name;
		$this->Directory = str_replace(["\\", "/"], [DIRECTORY_SEPARATOR,DIRECTORY_SEPARATOR], $rootDir ?? DIRECTORY_SEPARATOR);
		$this->ModelDirectory = $this->Directory . "model" . DIRECTORY_SEPARATOR;
		$this->ViewDirectory = $this->Directory . "view" . DIRECTORY_SEPARATOR;
		$this->ComputeDirectory = $this->Directory . "compute" . DIRECTORY_SEPARATOR;
		$this->RouteDirectory = $this->Directory . "route" . DIRECTORY_SEPARATOR;
		$this->PrivateDirectory = $this->Directory . "private" . DIRECTORY_SEPARATOR;
		$this->PublicDirectory = $this->Directory . "public" . DIRECTORY_SEPARATOR;
		$this->AssetDirectory = $this->Directory . "asset" . DIRECTORY_SEPARATOR;
		$this->StorageDirectory = $this->Directory . "storage" . DIRECTORY_SEPARATOR;
		$this->TempDirectory = "tmp" . DIRECTORY_SEPARATOR;
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

		$this->Route = str_replace(["\\", "/"], ["/", "/"], $rootRoute ?? "/");
		$this->AssetRoute = $this->Route . "asset/";
		$this->ScriptRoute = $this->AssetRoute . "script/";
		$this->StyleRoute = $this->AssetRoute . "style/";
		$this->ContentRoute = $this->Route . "post/";
		$this->CategoryRoute = $this->Route . "cat/";
		$this->TagRoute = $this->Route . "tag/";
		$this->UserRoute = $this->Route . "user/";
	}
	
	public function __get($name) {
        return $this[$this->PropertyName($name)];
    }
    public function __set($name, $value) {
        $this[$this->PropertyName($name)] = $value;
    }
    public function PropertyName($name) {
        return preg_replace("/\W+/", "", strToProper($name));
    }
}