<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Script;
module("Modal");
class MessageModal extends Modal{
	public $Image = null;
	public $Content = "Our website uses cookies and similar technology (hereinafter: cookies) in order to collect and store information from your device. Among other things, this supports the functionality of the website, interactions with social media, the provision of relevant content, news, advertising and analyses. To enable recognition by our advertising partners, we use pseudonymized identifiers (eg a hashed email address, if available) without sharing readable personal data. To consent to this, please select \"Accept all cookies\". You can withdraw your consent at any time under the Cookie Policy. For further information, please refer to our Privacy Policy.";

	public function __construct($title=null, $description=null, $content=null, $image=null){
		$this->Title = $title;
		$this->Content = $content;
		$this->Description = $description;
		$this->Image = $image;
	}
	public function GetStyle(){
		return parent::GetStyle().Html::Style("
		");
	}
}
?>