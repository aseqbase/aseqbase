<?php
class Information extends InformationBase{
	public $Owner = "MiMFa";
	public $FullOwner = "Minimal Member Factory";
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
	public $LogoPath = "/file/logo/logo.svg";
	public $FullLogoPath = "/file/logo/full-logo.svg";
	public $BannerPath = null;
	public $FullBannerPath = null;
	public $HomePath = "/home";
	public $DownloadPath = null;
	public $WaitSymbolPath = "/file/general/wait.gif";
	public $ProcessSymbolPath = "/file/general/process.gif";
	public $ErrorSymbolPath = "/file/general/error.png";

	public $User = null;
	
	public $Location = null;
	
	public $KeyWords = array("MiMFa","Minimal Member Factory");

	public $MainMenus = array(
		array("Name"=>"HOME","Link"=>"/home","Image"=>"/file/symbol/home.png","Attributes"=> "class='menu-link'"),
		array("Name"=>"SERVICE","Link"=>"#embed","Image"=>"/file/symbol/service.png","Attributes"=> "class='embed-link' data-target='.page' onclick='ViewEmbed(\"https://opensea.io/collection/punkyface\",\"fade\"); ViewSideMenu(false);'"),
		array("Name"=>"ABOUT","Link"=>"/about","Image"=>"/file/symbol/about.png","Attributes"=> "class='menu-link'","Items"=> array(
			array("Name"=>"CONTACTS","Link"=>"/contacts","Image"=>"/file/symbol/contact.png","Attributes"=> "class='menu-link'"),
			array("Name"=>"ABOUT","Link"=>"/about","Image"=>"/file/symbol/about.png","Attributes"=> "class='menu-link'"),
			array("Name"=>"TEAM","Link"=>"/team","Image"=>"/file/symbol/team.png","Attributes"=> "class='menu-link'")
		)),
	);


	public $SideMenus = array(
		array("Name"=>"HOME","Link"=>"/home","Image"=>"/file/symbol/home.png","Attributes"=> "class='menu-link'"),
		array("Name"=>"SERVICE","Link"=>"#embed","Image"=>"/file/symbol/service.png","Attributes"=> "class='embed-link' data-target='.page' onclick='ViewEmbed(\"https://opensea.io/collection/punkyface\",\"fade\"); ViewSideMenu(false);'"),
		array("Name"=>"ABOUT","Link"=>"/about","Image"=>"/file/symbol/about.png","Attributes"=> "class='menu-link'","Items"=> array(
			array("Name"=>"CONTACTS","Link"=>"/contacts","Image"=>"/file/symbol/contact.png","Attributes"=> "class='menu-link'"),
			array("Name"=>"ABOUT","Link"=>"/about","Image"=>"/file/symbol/about.png","Attributes"=> "class='menu-link'"),
			array("Name"=>"TEAM","Link"=>"/team","Image"=>"/file/symbol/team.png","Attributes"=> "class='menu-link'")
		)),
	);

	public $Shortcuts = array(
		array("Name"=>"Menu","Link"=>"","Image"=>"/file/symbol/menu.png", "Attributes"=>"onclick='ViewSideMenu()'"),
		array("Name"=>"ABOUT","Link"=>"/about","Image"=>"/file/symbol/about.png","Attributes"=> "class='menu-link'"),
		array("Name"=>"Home","Link"=>"#internal","Image"=>"/file/symbol/home.png","Attributes"=> "class='internal-link' data-target='.page' onclick='ViewInternal(\"/home\",\"fade\"); ViewSideMenu(false);'"),
		array("Name"=>"About","Link"=>"#internal","Image"=>"/file/symbol/about.png", "Attributes"=>"class='internal-link' data-target='.page' onclick='ViewInternal(\"/about\",\"fade\"); ViewSideMenu(false);'"),
		array("Name"=>"CONTACTS","Link"=>"/contacts","Image"=>"/file/symbol/contact.png","Attributes"=> "class='menu-link'")
		);
		
	public $Services = array(
		array("Name"=>"General Service","Icon"=>"fa fa-cog", "Link"=>"/introduction"),
		array("Name"=>"Special Service","Icon"=>"fa fa-cog", "Link"=>"/introduction")
		);

	public $Members = array(
		array(
			"PreName"=>"",
			"FirstName"=>"MiMFa",
			"MiddleName"=>"",
			"LastName"=>"Development Group",
			"PostName"=>"MiMFa aseqbase Pure Web Design Framework",
			"Image"=>"/file/general/avatar.png",
			"Link"=>"https://www.linkedin.com/company/mimfa",
			"Assignees"=> array(
								"Solution Designer",
								"Web Developer"
							),
			"Items"=> array(
								array("Key"=>"Speaking Languages","Class"=>"fa-comment-dots","Value"=>"<span class='badge badge-primary badge-pill'>English</span>"),array("Key"=>'Mail',"Class"=>'fa-envelope-open',"Value"=>'<a href="mailto:i@mimfa.net" target="_blank" class="badge badge-primary badge-pill">i@mimfa.net</a>'),
								array("Key"=>'LinkedIn',"Class"=>'fa-linkedin',"Value"=>'<a href="https://www.linkedin.com/company/mimfa" target="_blank" class="badge badge-primary badge-pill">www.linkedin.com/company/mimfa</a>'),
								array("Key"=>'Powered By',"Class"=>'fa-globe',"Value"=>'<a href="http://mimfa.net" target="_blank" class="badge badge-primary badge-pill">www.mimfa.net</a>')
							)
		)
	);
	
	public $Contacts = array(
		array("Name"=>"Email","Value"=>"info@mimfa.net","Link"=>"mailto:info@mimfa.net","Icon"=>"fa fa-envelope"),
		array("Name"=>"LinkedIn","Value"=>"www.linkedin.com/company/mimfa","Link"=>"https://www.linkedin.com/company/mimfa","Icon"=>"fa fa-linkedin"),
		array("Name"=>"Forum","Value"=>"Contact","Link"=>"/contact","Icon"=>"fa fa-comments"));

}
?>