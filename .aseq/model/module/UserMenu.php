<?php
namespace MiMFa\Module;

use MiMFa\Library\Html;
use MiMFa\Library\Convert;
class UserMenu extends Module
{
	public $Items = null;
	public $AllowLabels = false;
	public $AllowAnimate = true;
	public $AllowMiddle = true;
	public $AllowChangeColor = true;
	public $Path = null;
    public $Printable = false;

	public function __construct()
	{
		parent::__construct();
		$this->Path = \_::$User->InHandlerPath;
	}

	public function GetStyle()
	{
		return parent::GetStyle() . Html::Style("
			.{$this->Name} .menu{
				overflow: hidden;
			}
			.{$this->Name} .menu .media{
				width: 100%;
				aspect-ratio: 1;
			}
			.{$this->Name} .submenu{
				display: none;
				position: absolute;
				top: auto;
				".(\_::$Back->Translate->Direction=="rtl"?"left":"right").": 0;
				background-color: var(--back-color-special);
				color: var(--fore-color-special);
				min-width: 300px;
				min-width: min(210px, 100%);
				max-width: 90vw;
				max-height: 70vh;
				width: max-content;
				padding: 0px;
				box-shadow: 0px 16px 16px 0px rgba(0,0,0,0.2);
				overflow-x: hidden;
				overflow-y: auto;
				text-align: initial;
				z-index: 9;
            	" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-2)") . "
			}
			.{$this->Name} .submenu .name{
				background-color: var(--fore-color-output);
				color: var(--back-color-output);
				" . \MiMFa\Library\Style::UniversalProperty("word-wrap", "break-word") . "
			}
			.{$this->Name} .submenu .bio>:not(html,head,body,style,script,link,meta,title){
            	font-size: 80%;
				opacity: 0.8;
				background-color: var(--back-color-input);
				color: var(--fore-color-input);
				width: min-content;
				min-width: 100%;
				padding: var(--size-0) var(--size-1);
				" . \MiMFa\Library\Style::UniversalProperty("word-wrap", "break-word") . "
			}
			.{$this->Name} .submenu :is(.link, .button):not(.name){
            	width: 100%;
            	text-align: initial;
            	padding: calc(var(--size-0) / 2) var(--size-1);
				flex-direction: row-reverse;
				align-content: center;
				justify-content: space-between;
			}
			.{$this->Name}:hover .submenu{
            	display: grid;
            	" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-2)") . "
			}
		");
	}
	public function Get()
	{
		if ($this->Items == null) {
			if (!\_::$User->GetAccess(\_::$User->UserAccess))
				$this->Items = array(
					array("Name" => "Sign In", "Image" => "sign-in", "Path" => \_::$User->InHandlerPath),
					array("Name" => "Sign Up", "Image" => "user-plus", "Path" => \_::$User->UpHandlerPath)
				);
			else
				$this->Items = array(
					array("Name" => \_::$User->Name??"Profile", "Path" => \_::$User->ProfileHandlerPath, "Attributes"=>["class"=>"name main"]),
					array("Name" => Convert::ToExcerpt(Convert::ToText(between(\_::$User->GetValue("Bio"), "New User..."))), "Attributes" => ["class" => "bio"]),
					array("Name" => "Dashboard", "Image" => "gamepad", "Path" => \_::$User->DashboardHandlerPath),
					array("Name" => "Edit Profile", "Image" => "edit", "Path" => \_::$User->EditHandlerPath),
					array("Name" => "Sign Out", "Image" => "power-off", "Path" => "sendDelete(`" . \_::$User->OutHandlerPath . "`);")
				);
		}
		if (count($this->Items) > 0) {
			return Html::Button(Html::Media(null, \_::$User->Image??"user"), $this->Path, ["class" => "menu"]) .
				Html::Division(function () {
					foreach ($this->Items as $item)
						if (is_array($item))
							if (isValid($item, 'Path'))
								yield Html::Button(
									doValid(fn($v)=>$v?Html::Image($v):"", $item,"Image").
									Html::Division(__(getBetween($item, "Name", "Title"))),
									get($item, 'Path'),
									get($item, "Attributes")
								);
							else
								yield Html::Span(
									Html::Division(__(getBetween($item, "Name", "Title")), ["style" => (isValid($item, 'Image') ? ("background-image: url('" . $item['Image'] . "')") : "")]),
									null,
									get($item, "Attributes")
								);
						else
							yield $item;
				}, ["class" => "submenu"]) . $this->GetContent();
		}
		return parent::Get();
	}
}