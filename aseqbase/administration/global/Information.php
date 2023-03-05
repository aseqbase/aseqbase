<?php
class Information extends InformationBase{
	public $Owner = "MiMFa";
	public $FullOwner = "Minimal Member Factory";
	public $Product = "aseqbase Administration";
	public $FullProduct = "aseqbase Administration";
	public $Name = "aseqbase Administration";
	public $FullName = "MiMFa aseqbase Administration";
	public $Slogan = "<u>a seq</u>uence-<u>base</u>d framework";
	public $FullSlogan = "Develop websites by <u>a seq</u>uence-<u>base</u>d framework";
	public $Description = "An original, safe, very flexible, and innovative framework for web developments!";
	public $FullDescription = "A special framework for web development called \"aseqbase\" (a sequence-based framework) has been developed to implement safe, flexible, fast, and strong pure websites based on that, since 2018 so far.";

	public $Path = "https://aseqbase.ir";
	public $DownloadPath = "https://github.com/mimfa/aseqbase";
	public $Location = null;

	public $KeyWords = array("MiMFa aseqbase Framework", "MiMFa", "aseqbase", "Web Development", "Development", "Web Framework", "Website", "Framework");

	public $MainMenus = array(
		array("Layer"=>1,"Name"=>"HOME","Link"=>"/administration/home","Image"=>"/file/symbol/home.png","Attributes"=> "class='menu-link'"),
		array("Layer"=>1,"Name"=>"UPDATE","Link"=>"/administration/update","Image"=>"/file/symbol/update.png", "Attributes"=>"class='menu-link'"),
		array("Layer"=>1,"Name"=>"ABOUT","Link"=>"/administration/about","Image"=>"","Attributes"=> "class='menu-link'", "Items"=> array(
					array("Layer"=>1,"Name"=>"GIT","Link"=>"http://github.com/mimfa/aseqbase","Image"=>"/file/symbol/market.png","Attributes"=> "class='menu-link'"),
					array("Layer"=>2,"Name"=>"FORUM","Link"=>"https://github.com/mimfa/aseqbase/issues","Image"=>"/file/symbol/chat.png","Attributes"=> "class='menu-link'"),
					array("Layer"=>1,"Name"=>"PRODUCTS","Link"=>"http://github.com/mimfa","Image"=>"/file/symbol/product.png", "Attributes"=>"class='menu-link'"),
					array("Layer"=>1,"Name"=>"ABOUT","Link"=>"/administration/about","Image"=>"","Attributes"=> "class='menu-link'")
				)
			)
		);

	public $SideMenus = array(
		array("Layer"=>1,"Name"=>"HOME","Link"=>"/administration/home","Image"=>"/file/symbol/home.png","Attributes"=> "class='menu-link'"),
		array("Layer"=>1,"Name"=>"UPDATE","Link"=>"/administration/update","Image"=>"/file/symbol/update.png", "Attributes"=>"class='menu-link'"),
		array("Layer"=>1,"Name"=>"ABOUT","Link"=>"/administration/about","Image"=>"","Attributes"=> "class='menu-link'", "Items"=> array(
					array("Layer"=>1,"Name"=>"GIT","Link"=>"http://github.com/mimfa/aseqbase","Image"=>"/file/symbol/market.png","Attributes"=> "class='menu-link'"),
					array("Layer"=>2,"Name"=>"FORUM","Link"=>"https://github.com/mimfa/aseqbase/issues","Image"=>"/file/symbol/chat.png","Attributes"=> "class='menu-link'"),
					array("Layer"=>1,"Name"=>"PRODUCTS","Link"=>"http://github.com/mimfa","Image"=>"/file/symbol/product.png", "Attributes"=>"class='menu-link'"),
					array("Layer"=>1,"Name"=>"ABOUT","Link"=>"/administration/about","Image"=>"","Attributes"=> "class='menu-link'")
				)
			)
		);

	public $Shortcuts = array(
		array("Name"=>"Menu","Link"=>"","Image"=>"/file/symbol/menu.png", "Attributes"=>"onclick='viewSideMenu()'"),
		array("Name"=>"Market","Link"=>"#embed","Image"=>"/file/symbol/market.png","Attributes"=> "class='embed-link' onclick='viewEmbed(\"https://github.com/mimfa/aseqbase\",\"fade\"); viewSideMenu(false);'"),
		array("Name"=>"Home","Link"=>"#internal","Image"=>"/file/symbol/home.png","Attributes"=> "class='internal-link' onclick='viewInternal(\"home\",\"fade\"); viewSideMenu(false);'"),
		array("Name"=>"Products","Link"=>"#internal","Image"=>"/file/symbol/product.png", "Attributes"=>"class='internal-link' onclick='viewInternal(\"https://github.com/mimfa\",\"fade\"); viewSideMenu(false);'"),
		array("Name"=>"Chat","Link"=>"#internal","Image"=>"/file/symbol/chat.png","Attributes"=> "class='internal-link' onclick='viewInternal(\"https://github.com/mimfa/aseqbase/issues\",\"fade\"); viewSideMenu(false);'")
		);

	public $Services = array();

	public $Contacts = array(
		array("Name"=>"Instagram","Link"=>"/administration/?page=https://www.instagram.com/aseqbase","Icon"=> "fa fa-instagram"),
		array("Name"=>"Telegram","Link"=>"https://t.me/aseqbase","Icon"=> "fa fa-telegram"),
		array("Name"=>"Email","Link"=>"mailto:aseqbase@mimfa.net","Icon"=> "fa fa-envelope"),
		array("Name"=>"Github","Link"=>"http://github.com/mimfa","Icon"=> "fa fa-github"),
		array("Name"=>"Forum","Link"=>"/administration/chat","Image"=>"/file/symbol/chat.png","Icon"=> "fa fa-comments")
	);
}
?>
