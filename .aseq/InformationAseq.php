<?php
class InformationAseq extends InformationBase
{
	public $Owner = "MiMFa";
	public $FullOwner = "Minimal Members Factory";
	public $Product = "aseqbase";
	public $FullProduct = "aseqbase";
	public $Name = "aseqbase";
	public $FullName = "MiMFa aseqbase";
	public $Slogan = "<u>a seq</u>uence-<u>base</u>d framework";
	public $FullSlogan = "Develop websites by <u>a seq</u>uence-<u>base</u>d framework";
	public $Description = "An original, safe, very flexible, and innovative framework for web developments!";
	public $FullDescription = "A special framework for web development called \"aseqbase\" (a sequence-based framework) has been developed to implement safe, flexible, fast, and strong pure websites based on that, since 2018 so far.";

	public $Path = "https://aseqbase.ir";
	public $DownloadPath = "https://github.com/aseqbase/aseqbase";
	public $Location = null;
	public $Payment = '{"Network":"TRC-20","Unit":"USDT","DestinationContent":"TLQrvG1sNKY2kNRfcBUgW4QLfe1zAtZQds"}';

	public $KeyWords = array("MiMFa aseqbase Framework", "MiMFa", "aseqbase", "Web Development", "Development", "Web Framework", "Website", "Framework");

	public $MainMenus = array(
		array("Name" => "HOME", "Path"=> "/home", "Image" => "home", "Attributes" => "class='menu-link'"),
		array("Name" => "POSTS", "Path"=> "/posts", "Image" => "th-large", "Attributes" => "class='menu-link'"),
		array("Name" => "FORUMS", "Path"=> "/forums", "Image" => "comments", "Attributes" => "class='menu-link'"),
		array("Name" => "INTRODUCTION", "Path"=> "/introduction", "Image" => "thumb-tack", "Attributes" => "class='menu-link'"),
		array("Name" => "GIT", "Path"=> "http://github.com/mimfa/aseqbase", "Image" => "shopping-bag", "Attributes" => "class='menu-link'"),
		array("Name" => "PRODUCTS", "Path"=> "http://github.com/mimfa", "Image" => "clone", "Attributes" => "class='menu-link'"),
		array("Name" =>"ABOUT","Path"=>"/about","Image" =>"info","Attributes"=> "class='menu-link'","Items"=> array(
			array("Name" =>"CONTACTS","Path"=>"/contact","Image" =>"address-book","Attributes"=> "class='menu-link'"),
			array("Name" =>"ABOUT","Path"=>"/about","Image" =>"info","Attributes"=> "class='menu-link'"),
			array("Name" =>"TEAM","Path"=>"/team","Image" =>"group","Attributes"=> "class='menu-link'")
		))
	);

	public $SideMenus = array(
		array("Name" => "HOME", "Path"=> "/home", "Image" => "home", "Attributes" => "class='menu-link'"),
		array("Name" => "POSTS", "Path"=> "/posts", "Image" => "th-large", "Attributes" => "class='menu-link'"),
		array("Name" => "FORUMS", "Path"=> "/forums", "Image" => "comments", "Attributes" => "class='menu-link'"),
		array("Name" => "ABOUT", "Path"=> "/about", "Image" => "info", "Attributes" => "class='menu-link'"),
		array("Name" => "CONTACTS", "Path"=>"/contact", "Image" => "address-book", "Attributes" => "class='menu-link'"),
		array("Name" => "TEAM", "Path"=> "/team", "Image" => "group", "Attributes" => "class='menu-link'")
	);

	public $Shortcuts = array(
		array("Name" => "Menu", "Path"=> "viewSideMenu()", "Image" => "bars"),
		array("Name" => "Posts", "Path"=> "/posts", "Image" => "th-large"),
		array("Name" => "Home", "Path"=> "/home", "Image" => "home"),
		array("Name" => "Contact", "Path"=> "/contact", "Image" => "phone"),
		array("Name" => "About", "Path"=> "/about", "Image" => "quote-left")
	);

	public $Services = array(
		array("Name" => "MiMFa aseqbase", "Description" => "<p class='md-hide'>A special framework for web development called \"aseqbase\" (a sequence-based framework) has been developed to implement safe, flexible, fast, and strong pure websites based on that, since 2018 so far.</p>", "Image" => "phone", "More" => "<a class='btn' href='/about'>MORE</a>"),
		array("Name" => "OUR TARGET", "Description" => "<p class='md-hide'>Develop websites by <u>a seq</u>uence-<u>base</u>d framework</p>", "Image" => "bullseye", "More" => "<a class='btn' href='/about'>MORE</a>"),
		array("Name" => "WHAT IS WEB FRAMEWORK", "Description" => "<p class='md-hide'>A web development framework is a set of resources and tools for software developers to build and manage web applications, web services and websites.</p>", "Image" => "quote-left", "More" => "<a href=\"introduction\">READ ABOUT ASEQBASE</a>")
	);

	public $Contacts = array(
		array("Name" => "Instagram", "Path"=> "/?page=https://www.instagram.com/aseqbase", "Icon"=> "fa fa-instagram"),
		array("Name" => "Telegram", "Path"=> "https://t.me/aseqbase", "Icon"=> "fa fa-telegram"),
		array("Name" => "Email", "Path"=> "mailto:aseqbase@mimfa.net", "Icon"=> "fa fa-envelope"),
		array("Name" => "Github", "Path"=> "http://github.com/mimfa", "Icon"=> "fa fa-github"),
		array("Name" => "Forum", "Path"=> "/chat", "Image" => "comment", "Icon"=> "fa fa-comments")
	);
}
?>