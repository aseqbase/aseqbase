<?php

class AddressBase
{
	/**
	 * The top layer Name of this sequence
	 */
	public string|null $Name;

	public string|null $Directory;
	public string $ModelDirectory;
	public string $ViewDirectory;
	public string $LogicDirectory;
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

	public string|null $Path;
	public string $ViewPath;
	public string $PagePath;
	public string $RegionPath;
	public string $PartPath;
	public string $ScriptPath;
	public string $StylePath;
	
	public string $ContentPath;
	public string $CategoryPath;
	public string $TagPath;
	public string $UserPath;


	public function __construct(string $name = null, string $rootDir = null, string $rootPath = null)
	{
		$this->Name = $name;
		$this->Directory = str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $rootDir ?? DIRECTORY_SEPARATOR);
		$this->ModelDirectory = $this->Directory . "model" . DIRECTORY_SEPARATOR;
		$this->ViewDirectory = $this->Directory . "view" . DIRECTORY_SEPARATOR;
		$this->LogicDirectory = $this->Directory . "logic" . DIRECTORY_SEPARATOR;
		$this->RouteDirectory = $this->Directory . "route" . DIRECTORY_SEPARATOR;
		$this->PrivateDirectory = $this->Directory . "private" . DIRECTORY_SEPARATOR;
		$this->PublicDirectory = $this->Directory . "public" . DIRECTORY_SEPARATOR;
		$this->AssetDirectory = $this->Directory . "asset" . DIRECTORY_SEPARATOR;
		$this->StorageDirectory = $this->Directory . "storage" . DIRECTORY_SEPARATOR;
		$this->TempDirectory = "tmp" . DIRECTORY_SEPARATOR;
		$this->LogDirectory = $this->Directory . "log" . DIRECTORY_SEPARATOR;
		$this->LibraryDirectory = $this->Directory . "library" . DIRECTORY_SEPARATOR;
		$this->ComponentDirectory = $this->ModelDirectory . "component" . DIRECTORY_SEPARATOR;
		$this->TemplateDirectory = $this->ModelDirectory . "template" . DIRECTORY_SEPARATOR;
		$this->ModuleDirectory = $this->ModelDirectory . "module" . DIRECTORY_SEPARATOR;
		$this->PageDirectory = $this->ViewDirectory . "page" . DIRECTORY_SEPARATOR;
		$this->RegionDirectory = $this->ViewDirectory . "region" . DIRECTORY_SEPARATOR;
		$this->PartDirectory = $this->ViewDirectory . "part" . DIRECTORY_SEPARATOR;
		$this->ScriptDirectory = $this->ViewDirectory . "script" . DIRECTORY_SEPARATOR;
		$this->StyleDirectory = $this->ViewDirectory . "style" . DIRECTORY_SEPARATOR;

		$this->Path = str_replace(["\\", "/"], "/", $rootPath ?? "/");
		$this->ViewPath = $this->Path . "view/";
		$this->PagePath = $this->ViewPath . "page/";
		$this->RegionPath = $this->ViewPath . "region/";
		$this->PartPath = $this->ViewPath . "part/";
		$this->ScriptPath = $this->ViewPath . "script/";
		$this->StylePath = $this->ViewPath . "style/";

		$this->ContentPath = "/post/";
		$this->CategoryPath = "/cat/";
		$this->TagPath = "/tag/";
		$this->UserPath = "/user/";
	}
}
?>