<?php
class InformationAseq extends InformationBase
{
	public $Owner = "MiMFa";
	public $FullOwner = "Minimal Members Factory";
	public $Name = "aseqbase";
	public $FullName = "MiMFa aseqbase";
	public $Slogan = "<u>a seq</u>uence-<u>base</u>d framework";
	public $FullSlogan = "Develop websites by <u>a seq</u>uence-<u>base</u>d framework";
	public $Description = "An original, safe, very flexible, and innovative framework for web developments!";
	public $FullDescription = "A special framework for web development called \"aseqbase\" (a sequence-based framework) has been developed to implement safe, flexible, fast, and strong pure websites based on that, since 2018 so far.";

	public $Path = "/";
	public $DownloadPath = null;
	public $Location = null;
	public $Payment = null;

	public $KeyWords = array("MiMFa aseqbase Framework", "MiMFa", "aseqbase", "Web Development", "Development", "Web Framework", "Website", "Framework");

	public $MainMenus = [
		array("Name" => "HOME", "Path"=> "/home", "Image" => "home")
	];

	public $Shortcuts = [
		array("Name" => "Menu", "Path"=> "viewSideMenu()", "Image" => "bars"),
		array("Name" => "Posts", "Path"=> "/posts", "Image" => "th-large"),
		array("Name" => "Home", "Path"=> "/home", "Image" => "home"),
		array("Name" => "Contact", "Path"=> "/contact", "Image" => "phone"),
		array("Name" => "About", "Path"=> "/about", "Image" => "quote-left")
	];

}
?>