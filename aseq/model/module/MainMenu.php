<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
use MiMFa\Library\Style;
use MiMFa\Library\Convert;
class MainMenu extends Module
{
	public $Tag = "nav";
	public $Class = "row";
	public $Image = null;
	public $Items = null;
	public $Shortcuts = null;
	public $AllowBranding = true;
	public $AllowItems = true;
	public $AllowOthers = true;
	public $AllowFixed = false;
	public $AllowItemsTitle = true;
	public $AllowSubItemsTitle = true;
	public $AllowItemsDescription = false;
	public $AllowSubItemsDescription = true;
	public $AllowItemsImage = false;
	public $AllowSubItemsImage = false;
	public $HideItemsScreenSize = 'md';
	public $ShowItemsScreenSize = null;
	public $HideOthersScreenSize = 'md';
	public $ShowOthersScreenSize = null;
	public $AllowDefaultButtons = true;
	public $LogoWidth = "auto";
	public $LogoHeight = "calc(var(--size-5) - var(--size-0) / 2)";
	public $FixedMargin = "calc(var(--size-1) * 3)";
	public $ToggleLabel;
    public $Printable = false;
    public $ZIndex = "9999";

	public function __construct($items = null)
	{
		parent::__construct();
		$this->Items = $items ?? $this->Items;
		$this->ToggleLabel = Struct::Icon("angle-down");
	}

	public function GetStyle()
	{
		return parent::GetStyle() . Struct::Style(
			"
			.{$this->Name} {
				" . ($this->AllowFixed ? "
				opacity: 0.95;
				position: fixed;
				top:0;
				left:0;
				right:0;
				z-index: 999;
			}
			.{$this->Name}-margin{
				min-height: {$this->FixedMargin};
			}
			" : "
			}") . "
			.{$this->Name} .inside{
				display: flex;
				flex-wrap: wrap;
			}
			.{$this->Name} .header{
				margin: 0;
				width: fit-content;
				display: flex;
				justify-content: flex-start;
				line-height: 1.5;
				align-items: center;
				flex-direction: row;
			    padding-inline-end: var(--size-3);
				gap: var(--size-0);
			}
			.{$this->Name} .header .title{
				" . (isValid($this->Description) ? "line-height: var(--size-2);" : "") . "
			}
			.{$this->Name} .header .image{
				background-position: center;
				background-repeat: no-repeat;
				background-size: 100% auto;
				background-color: transparent;
				min-width: {$this->LogoHeight};
				width: {$this->LogoWidth};
				width: fit-content;
				height: {$this->LogoHeight};
				font-size: var(--size-0);
			}
			" . ($this->AllowDefaultButtons? "
			.{$this->Name} ul:not(.sub-items) {
				min-width: fit-content;
				max-width: 70%;
				margin-inline-end: 100px;
			}
			" : "") ."
			.{$this->Name} ul:not(.sub-items) {
				list-style: none;
				list-style-type: none;
				margin: 0;
				padding: 0;
				overflow: hidden;
				display: flex;
				align-items: center;
			}

			.{$this->Name} ul.sub-items {
				display: none;
				position: fixed;
				min-width: 160px;
				max-width: 90vw;
				max-height: 70vh;
				padding: 0px;
				overflow-x: hidden;
				overflow-y: auto;
				z-index: $this->ZIndex;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
				
			.{$this->Name} ul.sub-items .sub-items {
				display: flex;
				position: relative;
				font-size: 90%;
				min-width: calc(5 * var(--size-5));
				max-width: 500px;
				width: 70vw;
				max-height: 60vh;
				padding: 0px;
				padding-inline-start: var(--size-5);
				padding-bottom: calc(var(--size-0) / 2);
				overflow-x: hidden;
				overflow-y: auto;
				flex-wrap: wrap;
				flex-direction: row;
				align-content: stretch;
				justify-content: flex-start;
				align-items: stretch;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}

			.{$this->Name} ul.sub-items>li>:is(.button, .button:visited){
				width: 100%;
				text-decoration: none;
				padding: calc(var(--size-1) / 2) var(--size-1);
				display: block;
				text-align: start;
				border: none;
			}

			" .
			($this->AllowOthers ? "
			.{$this->Name} .other{
				text-align: end;
				width: fit-content;
				position: absolute;
				clear: both;
				display: flex;
				align-items: center;
				justify-content: space-around;
				flex-wrap: nowrap;
				padding: calc(var(--size-0) / 3) var(--size-0);
				gap: calc(var(--size-0) * 0.5);
				" . ((\_::$Front->Translate->Direction ?? \_::$Front->DefaultDirection) == "rtl" ? "left" : "right") . ": var(--size-2);
			}

			.{$this->Name} .other form{
				text-decoration: none;
				padding: 0px;
				margin: 0px;
				border-radius: var(--radius-3);
				box-shadow: var(--shadow-1);
				display: flex;
				align-content: center;
				align-items: center;
				justify-content: space-between;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .other form:is(:hover, :active, :focus) {
				font-weight: bold;
				color: var(--fore-color-input);
				background-color: var(--back-color-input);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .other form :is(input, .input, .input:is(:hover, :active, :focus)) {
				padding: calc(var(--size-0) / 2) var(--size-0);
				border: none;
				outline: none;
				background-color: transparent;
				color: unset;
			}
			.{$this->Name} .other form:not(:hover, :active, :focus) :is(input, .input, .input:not(:hover, :active, :focus)){
				/*padding: calc(var(--size-0) / 2) 0px;*/
				width: 0px;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .other form:is(:hover, :active, :focus) :is(input, .input, .input:is(:hover, :active, :focus)){
				/*padding: calc(var(--size-0) / 2) var(--size-0);*/
				width: 200px;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .other form :is(button, .button)  {
				aspect-ratio: 1;
				padding: calc(var(--size-0) / 2);
				border-radius: var(--radius-max);
			}
			.{$this->Name} :is(.other .other-item>.button, .other>.icon, .other>.button){
				background-color: inherit;
				color: inherit;
				width: 50px;
				padding: calc(var(--size-0) / 3);
				border-radius: var(--radius-max);
				display: inline-flex;
				aspect-ratio: 1;
				text-align: center;
				justify-content: center;
				align-items: center;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} :is(.other .other-item>.button, .other>.icon, .other>.button):hover{
				box-shadow: var(--shadow-3);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			" : "")
		);
	}

	public function Get()
	{
		return Convert::ToString(function () {
			yield Struct::OpenTag("div", ["class"=>"inside"]);
			if ($this->AllowBranding)
				yield Struct::Division(
					(isValid($this->Image) ? Struct::Link(Struct::Media("", $this->Image, ['class' => 'col-sm image']), \_::$Front->Path) : "") .
					Struct::Division(
						(isValid($this->Description) ? Struct::Division(__($this->Description), ['class' => 'description']) : "") .
						(isValid($this->Title) ? Struct::Link(Struct::Division(__($this->Title), ['class' => 'title']), \_::$Front->Path) : ""),
					["class" => "brand"]),
				["class" => "header"]);
			if ($this->AllowItems)
				if (count($this->Items) > 0)
					yield Struct::Items(
						function () {
							foreach ($this->Items as $item)
								yield $this->CreateItem($item, 1);
						}
						,
						["class" => (isValid($this->ShowItemsScreenSize) ? $this->ShowItemsScreenSize . '-show' : "") . ' ' . (isValid($this->HideItemsScreenSize) ? $this->HideItemsScreenSize . '-hide' : "")]
					);
			if ($this->AllowOthers) {
				$defaultButtons = [];
				if ($this->AllowDefaultButtons) {
					module("SearchForm");
					module("TemplateButton");
					module("UserMenu");
					$defaultButtons[] = new SearchForm();
					$defaultButtons[] = new TemplateButton();
					if (\_::$User->AllowSigning) {
						$usermenu = new UserMenu();
						$usermenu->Class = "other-item";
						$defaultButtons[] = $usermenu;
					}
				}
				yield Struct::Division([
						...($this->Content? (is_array($this->Content)?$this->Content:[$this->Content]) : []),
						...($defaultButtons? $defaultButtons : [])
					],
					["class" => "other view {$this->ShowOthersScreenSize}-show {$this->HideOthersScreenSize}-hide"]
				);
			}
			yield Struct::CloseTag("div");
		});
	}

	protected function CreateItem($item, $ind = 1)
	{
		if (!\_::$User->HasAccess(getValid($item, "Access", \_::$User->VisitAccess)))
			return null;
		$path = getBetween($item, "Path");
		$act = endsWith(\_::$User->Path, $path) ? 'active' : "";
		$ind++;
		$count = count(getValid($item, "Items", []));
		return Struct::Item(
			($ind <=2? Struct::Button(
				($this->AllowItemsImage&& ($t = getBetween($item, "Icon", "Image"))?Struct::Image(null, $t):"").
				($this->AllowItemsTitle && ($t = getBetween($item, "Title", "Name"))?__($t):"").
				($count > 0?$this->ToggleLabel:"").
				($this->AllowItemsDescription && ($t = get($item, "Description"))?Struct::Division(__($t), ["class"=>"description"]):""),
				$path,
				get($item, "Attributes")
				) :
				Struct::Button(
					($this->AllowSubItemsImage && ($t = getBetween($item, "Icon", "Image"))?Struct::Image(null, $t):"").
					($this->AllowSubItemsTitle && ($t = getBetween($item, "Title", "Name"))?__($t):"").
					($count > 0?$this->ToggleLabel:"").
					($this->AllowSubItemsDescription && ($t = get($item, "Description"))?Struct::Division(__($t), ["class"=>"description"]):""),
					$path,
					get($item, "Attributes")
				)
			) .
			($count > 0 ?
				Struct::Items(
					function () use ($item, $ind) {
						foreach ($item["Items"] as $itm)
							yield $this->CreateItem($itm, $ind);
					}
					,
					["class" => "sub-items sub-items-$ind"]
				)
				: ""),
			["class" => $count > 0 ? "dropdown $act" : $act]
		);
	}

	public function AfterHandle()
	{
		return parent::AfterHandle() . ($this->AllowFixed ? "<div class='{$this->Name}-margin be unprintable top-invisible'></div>" : "");
	}
}