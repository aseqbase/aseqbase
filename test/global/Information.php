<?php
class Information extends InformationBase{
	public $Owner = "MiMFa";
	public $FullOwner = "Minimal Memeber Factory";
	public $Product = "aseqbase";
	public $FullProduct = "aseqbase";
	public $Name = "aseqbase";
	public $FullName = "MiMFa aseqbase";
	public $Slogan = "<u>a seq</u>uence-<u>base</u>d framework";
	public $FullSlogan = "Develop websites by <u>a seq</u>uence-<u>base</u>d framework";
	public $Description = "An original, safe, very flexible, and innovative framework for web developments!";
	public $FullDescription = "A special framework for web development called "aseqbase" (a sequence-based framework) has been developed to implement safe, flexible, fast, and strong pure websites based on that, since 2018 so far.";

	public $Path = "https://aseqbase.ir";
	public $DownloadPath = null;
	public $Location = null;

	public $KeyWords = array("MiMFa aseqbase Framework", "MiMFa", "aseqbase", "Web Development", "Development", "Web Framework", "Website", "Framework");

	public $MainMenus = array(
		array("Layer"=>1,"Name"=>"HOME","Link"=>"/home","Image"=>"/file/symbol/home.png","Attributes"=> "class='menu-link'"),
		array("Layer"=>1,"Name"=>"PRODUCTS","Link"=>"/products-gallery","Image"=>"/file/symbol/product.png", "Attributes"=>"class='menu-link'"),
		array("Layer"=>2,"Name"=>"COLLECTIONS","Link"=>"/collections","Image"=>"","Attributes"=> "class='menu-link'"),
		array("Layer"=>1,"Name"=>"BUY","Link"=>"https://opensea.io/collection/punkyface","Image"=>"/file/symbol/market.png","Attributes"=> "class='menu-link'"),
		array("Layer"=>2,"Name"=>"FORUM","Link"=>"/chat","Image"=>"/file/symbol/chat.png","Attributes"=> "class='menu-link'"),
		array("Layer"=>1,"Name"=>"ABOUT","Link"=>"/about","Image"=>"","Attributes"=> "class='menu-link'")
		);

	public $SideMenus = array(
		array("Layer"=>1,"Name"=>"HOME","Link"=>"/home","Image"=>"/file/symbol/home.png","Attributes"=> "class='menu-link'"),
		array("Layer"=>1,"Name"=>"PRODUCTS","Link"=>"/products-gallery","Image"=>"/file/symbol/product.png", "Attributes"=>"class='menu-link'"),
		array("Layer"=>2,"Name"=>"COLLECTIONS","Link"=>"/collections","Image"=>"","Attributes"=> "class='menu-link'"),
		array("Layer"=>1,"Name"=>"BUY","Link"=>"https://opensea.io/collection/punkyface","Image"=>"/file/symbol/market.png","Attributes"=> "class='menu-link'"),
		array("Layer"=>2,"Name"=>"FORUM","Link"=>"/chat","Image"=>"/file/symbol/chat.png","Attributes"=> "class='menu-link'"),
		array("Layer"=>1,"Name"=>"ABOUT","Link"=>"/about","Image"=>"","Attributes"=> "class='menu-link'")
		);

	public $Shortcuts = array(
		array("Name"=>"Menu","Link"=>"","Image"=>"/file/symbol/menu.png", "Attributes"=>"onclick='viewSideMenu()'"),
		array("Name"=>"Market","Link"=>"#embed","Image"=>"/file/symbol/market.png","Attributes"=> "class='embed-link' onclick='viewEmbed(\"https://opensea.io/collection/punkyface\",\"fade\"); viewSideMenu(false);'"),
		array("Name"=>"Home","Link"=>"#internal","Image"=>"/file/symbol/home.png","Attributes"=> "class='internal-link' onclick='viewInternal(\"home\",\"fade\"); viewSideMenu(false);'"),
		array("Name"=>"Products","Link"=>"#internal","Image"=>"/file/symbol/product.png", "Attributes"=>"class='internal-link' onclick='viewInternal(\"products-card\",\"fade\"); viewSideMenu(false);'"),
		array("Name"=>"Chat","Link"=>"#internal","Image"=>"/file/symbol/chat.png","Attributes"=> "class='internal-link' onclick='viewInternal(\"chat\",\"fade\"); viewSideMenu(false);'")
		);

	public $Services = array(
		array("Name"=>"MiMFa Collection","Description"=>"<p class='md-hide'><center>10,000 unique collectible characters with proof of ownership stored on the Polygon blockchain</center></p>","Image"=>"/file/icon/amplifier.png", "More"=>"<a class='btn' onclick='viewInternal(\"products-card\",\"fade\");' href='#internal'>SHOW PRODUCTS</a>"),
		array("Name"=>"PRODUCTS","Description"=>"<p class='md-hide'>Here is an original collection of professional graphic designs from the human eye, each of which has secrets hidden.</p><p class='md-hide'>The privilege of using each of these graphic documents can be provided as NFT.</p>","Image"=>"/file/icon/gallery.png", "More"=>"<a class='btn' onclick='viewInternal(\"products-card\",\"fade\");' href='#internal'>SHOW PRODUCTS</a>"),
		array("Name"=>"OUR TARGET","Description"=>"<p class='md-hide'>Here is an original collection of professional graphic designs from the human eye, each of which has secrets hidden.</p><p class='md-hide'>The privilege of using each of these graphic documents can be provided as NFT.</p>","Image"=>"/file/icon/target.png", "More"=>"<a class='btn' onclick=\"viewEmbed('https://opensea.io/collection/punkyface');\" href='#embed'>OUR NFT COLLECTION</a>"),
		array("Name"=>"WHAT IS THE NFT","Description"=>"<p class='md-hide'>A non-fungible token (NFT) is a non-interchangeable unit of data stored on a blockchain, a form of digital ledger, that can be sold and traded. Types of NFT data units may be associated with digital files such as photos, videos, and audio. Because each token is uniquely identifiable, NFTs differ from most cryptocurrencies, such as Bitcoin, which are fungible.</p>","Image"=>"/file/icon/coach.png", "More"=>"<a class=\"btn\" onclick=\"viewExternal('https://en.wikipedia.org/wiki/Non-fungible_token','fade');\" data-target=\".page\" href=\"#external\">READ ABOUT NFT</a>")
		);

	public $Contacts = array(
		array("Name"=>"Instagram","Link"=>"/?page=https://www.instagram.com/mysteryeye_official","Image"=>"/file/symbol/chat.png","Icon"=> "fa fa-instagram"),
		array("Name"=>"Telegram","Link"=>"https://t.me/mysteryeye_official","Image"=>"/file/symbol/chat.png","Icon"=> "fa fa-telegram"),
		array("Name"=>"Email","Link"=>"mailto:mysteryeye@mimfa.net","Image"=>"/file/symbol/chat.png","Icon"=> "fa fa-envelope"),
		array("Name"=>"Forum","Link"=>"/chat","Image"=>"/file/symbol/chat.png","Icon"=> "fa fa-comments")
	);
}
?>