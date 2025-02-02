<?php
class AseqInformation extends InformationBase
{
	public $Owner = "MiMFa";
	public $FullOwner = "Minimal Members Factory";
	public $OwnerDescription = "We are a team has considered the idea as a small start-up project and has developed it through its financial and human resources. The start-up has gathered people from the professionals around a table. Our products are now ready to be presented to our dear users, and we are impatiently waiting to receive your comments.";
	public $Product = "aseqbase";
	public $FullProduct = "aseqbase";
	public $Name = "aseqbase";
	public $FullName = "MiMFa aseqbase";
	public $Slogan = "Your Satisfaction is our Greatest Goal";
	public $FullSlogan = "Your Satisfaction is our Greatest Goal";
	public $Description = "Designed by: MiMFa aseqbase Pure Web Design Framework";
	public $FullDescription = "aseqbase is a project sponsored by MiMFa. Designed by: MiMFa aseqbase Pure Web Design Framework";

	public $Path = "https://mimfa.net";
	public $LogoPath = "/asset/logo/logo.png";
	public $FullLogoPath = "/asset/logo/full-logo.png";
	public $BannerPath = null;
	public $FullBannerPath = null;
	public $HomePath = "/home";
	public $DownloadPath = null;
	public $WaitSymbolPath = "/asset/general/wait.gif";
	public $ProcessSymbolPath = "/asset/general/process.gif";
	public $ErrorSymbolPath = "/asset/general/error.png";

	public $User = null;

	public $Location = null;

	public $KeyWords = array("MiMFa", "Minimal Members Factory");

	public $MainMenus = array(
		array("Name" => "HOME", "Link" => "/home", "Image" => "/asset/symbol/home.png", "Attributes" => "class='menu-link'"),
		array("Name" => "POSTS", "Link" => "/posts", "Image" => "/asset/symbol/document.png", "Attributes" => "class='menu-link'"),
		array("Name" => "FORUMS", "Link" => "/forums", "Image" => "/asset/symbol/forum.png", "Attributes" => "class='menu-link'"),
		array("Name" => "SERVICE", "Link" => "#embed", "Image" => "/asset/symbol/service.png", "Attributes" => "class='embed-link' data-target='.page' onclick='viewEmbed(\"https://opensea.io/collection/punkyface\",\"fade\"); viewSideMenu(false);'"),
		array("Name" => "CONTACTS", "Link" => "/contact", "Image" => "/asset/symbol/contact.png", "Attributes" => "class='menu-link'"),
		array("Name" => "ABOUT", "Link" => "/about", "Image" => "/asset/symbol/about.png", "Attributes" => "class='menu-link'"),
		array("Name" => "TEAM", "Link" => "/team", "Image" => "/asset/symbol/team.png", "Attributes" => "class='menu-link'")
	);

	public $SideMenus = array(
		array("Name" => "HOME", "Link" => "/home", "Image" => "/asset/symbol/home.png", "Attributes" => "class='menu-link'"),
		array("Name" => "POSTS", "Link" => "/posts", "Image" => "/asset/symbol/document.png", "Attributes" => "class='menu-link'"),
		array("Name" => "FORUMS", "Link" => "/forums", "Image" => "/asset/symbol/forum.png", "Attributes" => "class='menu-link'"),
		array("Name" => "SERVICE", "Link" => "#embed", "Image" => "/asset/symbol/service.png", "Attributes" => "class='embed-link' data-target='.page' onclick='viewEmbed(\"https://opensea.io/collection/punkyface\",\"fade\"); viewSideMenu(false);'"),
		array("Name" => "ABOUT", "Link" => "/about", "Image" => "/asset/symbol/about.png", "Attributes" => "class='menu-link'"),
		array("Name" => "CONTACTS", "Link" => "/contact", "Image" => "/asset/symbol/contact.png", "Attributes" => "class='menu-link'"),
		array("Name" => "TEAM", "Link" => "/team", "Image" => "/asset/symbol/team.png", "Attributes" => "class='menu-link'")
	);

	public $Shortcuts = array(
		array("Name" => "Menu", "Link" => "", "Image" => "/asset/symbol/menu.png", "Attributes" => "onclick='viewSideMenu()'"),
		array("Name" => "POSTS", "Link" => "/posts", "Image" => "/asset/symbol/document.png", "Attributes" => "class='menu-link'"),
		array("Name" => "FORUMS", "Link" => "/forums", "Image" => "/asset/symbol/forum.png", "Attributes" => "class='menu-link'"),
		array("Name" => "Home", "Link" => "#internal", "Image" => "/asset/symbol/home.png", "Attributes" => "class='internal-link' data-target='.page' onclick='viewInternal(\"/home\",\"fade\"); viewSideMenu(false);'"),
		array("Name" => "About", "Link" => "#internal", "Image" => "/asset/symbol/about.png", "Attributes" => "class='internal-link' data-target='.page' onclick='viewInternal(\"/about\",\"fade\"); viewSideMenu(false);'"),
		array("Name" => "CONTACTS", "Link" => "/contact", "Image" => "/asset/symbol/contact.png", "Attributes" => "class='menu-link'")
	);

	public $Services = array(
		array("Name" => "MiMFa Collection", "Description" => "<p class='md-hide'>A special framework for web development called \"aseqbase\" (a sequence-based framework) has been developed to implement safe, flexible, fast, and strong pure websites based on that, since 2018 so far.</p>", "Image" => "/asset/symbol/amplifier.png", "More" => "<a class='btn' href='/about'>MORE</a>"),
		array("Name" => "OUR TARGET", "Description" => "<p class='md-hide'>Develop websites by <u>a seq</u>uence-<u>base</u>d framework</p><p class='md-hide'>The privilege of using each of these graphic documents can be provided as NFT.</p>", "Image" => "/asset/symbol/target.png", "More" => "<a class='btn' href='/about'>MORE</a>"),
		array("Name" => "WHAT IS WEB FRAMEWORK", "Description" => "<p class='md-hide'>A web development framework is a set of resources and tools for software developers to build and manage web applications, web services and websites.</p>", "Image" => "/asset/symbol/info.png", "More" => "<a class=\"btn\" onclick=\"viewExternal('https://www.techtarget.com/searchcontentmanagement/definition/web-development-framework-WDF#:~:text=A%20web%20development%20framework%20is,applications%2C%20web%20services%20and%20websites.','fade');\" data-target=\".page\" href=\"#external\">READ ABOUT NFT</a>")
	);
	public $Members = array(
		array(
			"PreName" => "",
			"FirstName" => "MiMFa",
			"MiddleName" => "",
			"LastName" => "Development Group",
			"PostName" => "MiMFa aseqbase Pure Web Design Framework",
			"Image" => "/asset/general/avatar.png",
			"Link" => "https://www.linkedin.com/company/mimfa",
			"Assignees" => array(
				"Solution Designer",
				"Web Developer"
			),
			"Items" => array(
				array("Key" => "Speaking Languages", "Class" => "fa-comment-dots", "Value" => "<span class='badge badge-primary badge-pill'>English</span>"),
				array("Key" => 'Mail', "Class" => 'fa-envelope-open', "Value" => '<a href="mailto:i@mimfa.net" target="_blank" class="badge badge-primary badge-pill">i@mimfa.net</a>'),
				array("Key" => 'LinkedIn', "Class" => 'fa-linkedin', "Value" => '<a href="https://www.linkedin.com/company/mimfa" target="_blank" class="badge badge-primary badge-pill">www.linkedin.com/company/mimfa</a>'),
				array("Key" => 'Powered By', "Class" => 'fa-globe', "Value" => '<a href="http://mimfa.net" target="_blank" class="badge badge-primary badge-pill">www.mimfa.net</a>')
			)
		)
	);

	public $Contacts = array(
		array("Name" => "Email", "Value" => "info@mimfa.net", "Link" => "mailto:info@mimfa.net", "Icon" => "fa fa-envelope"),
		array("Name" => "LinkedIn", "Value" => "www.linkedin.com/company/mimfa", "Link" => "https://www.linkedin.com/company/mimfa", "Icon" => "fa fa-linkedin"),
		array("Name" => "Forum", "Value" => "Contact", "Link" => "/contact", "Icon" => "fa fa-comments")
	);

}
?>