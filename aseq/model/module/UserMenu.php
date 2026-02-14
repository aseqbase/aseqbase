<?php
namespace MiMFa\Module;

use MiMFa\Library\Struct;
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
        yield parent::GetStyle();
        yield Struct::Style("
			.{$this->MainClass} .menu{
				overflow: hidden;
			}
			.{$this->MainClass} .menu .media{
				width: 100%;
				aspect-ratio: 1;
			}
			.{$this->MainClass} .sub-items{
				display: none;
				position: absolute;
				top: auto;
				".(\_::$Front->Translate->Direction=="rtl"?"left":"right").": 0;
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
			.{$this->MainClass} .sub-items .name{
				background-color: var(--fore-color-output);
				color: var(--back-color-output);
				" . \MiMFa\Library\Style::UniversalProperty("word-wrap", "break-word") . "
			}
			.{$this->MainClass} .sub-items .bio>:not(html,head,body,style,script,link,meta,title){
            	font-size: 80%;
				opacity: 0.8;
				background-color: var(--back-color-input);
				color: var(--fore-color-input);
				width: min-content;
				min-width: 100%;
				padding: var(--size-0) var(--size-1);
				" . \MiMFa\Library\Style::UniversalProperty("word-wrap", "break-word") . "
			}
			.{$this->MainClass} .sub-items :is(.link, .button):not(.name){
            	width: 100%;
            	text-align: initial;
            	padding: calc(var(--size-0) / 2) var(--size-1);
				flex-direction: row-reverse;
				align-content: center;
				justify-content: space-between;
			}
			.{$this->MainClass}:hover .sub-items{
            	display: grid;
            	" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-2)") . "
			}
		");
	}
	public function GetInner()
	{
		if ($this->Items == null) {
			if (!\_::$User->HasAccess(\_::$User->UserAccess))
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
			return Struct::Button(Struct::Media(null, \_::$User->Image??"user"), $this->Path, ["class" => "menu"]) .
				Struct::Division(function () {
					foreach ($this->Items as $item)
						if (is_array($item))
							if (isValid($item, 'Path'))
								yield Struct::Button(
									doValid(fn($v)=>$v?Struct::Image($v):"", $item,"Image").
									Struct::Division(__(getBetween($item, "Name", "Title"))),
									get($item, 'Path'),
									get($item, "Attributes")
								);
							else
								yield Struct::Span(
									Struct::Division(__(getBetween($item, "Name", "Title")), ["style" => (isValid($item, 'Image') ? ("background-image: url('" . $item['Image'] . "')") : "")]),
									null,
									get($item, "Attributes")
								);
						else
							yield $item;
				}, ["class" => "sub-items"]) . $this->GetContent();
		}
		return parent::GetInner();
	}
}