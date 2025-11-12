<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
use MiMFa\Library\Style;
use MiMFa\Library\Convert;

class SideMenu extends Module
{
	public $Image = null;
	public $Items = null;
	public $Shortcuts = null;
	public $Direction = "ltr";
	public $BackgroundMask = "#00000099";
	public $AllowBranding = true;
	public $AllowItems = true;
	public $AllowItemsLabel = true;
	public $AllowSubItemsLabel = true;
	public $AllowItemsDescription = true;
	public $AllowSubItemsDescription = true;
	public $AllowItemsImage = true;
	public $AllowSubItemsImage = true;
	public $AllowChangeColor = true;
	public $AllowHide = true;
	public $AllowHoverable = true;
	/**
	 * Leave null to use the defalut buttons otherwise put your buttons
	 * @var 
	 */
	public $Buttons = null;
	public $AllowDefaultButtons = true;
	public $ButtonsScreenSize = "md";
	public $AllowSignButton = true;
	public $SignButtonText = "&#9776;";
	public $SignButtonScreenSize = "md";
	public $LogoWidth = "calc(1.25 * var(--size-5))";
	public $LogoHeight = "calc(1.25 * var(--size-5))";
    public $Printable = false;


	public function __construct()
	{
		parent::__construct();
		$this->Direction = \_::$Back->Translate->Direction ?? \_::$Back->DefaultDirection;
	}

	public function GetStyle()
	{
		$this->Direction = strtolower($this->Direction);
		$sdir = ($this->Direction == "rtl") ? "left" : "right";
		$activeselector = $this->AllowHoverable ? ".{$this->Name}:is(.active, :hover)" : ".{$this->Name}.active";
		$notactiveselector = ".{$this->Name}:not(.active)";
		return parent::GetStyle() . Struct::Style("
			.{$this->Name}{
				background-color: var(--fore-color-output);
				color: var(--back-color-output);
				font-size: var(--size-1);
				position: fixed;
				max-height: 100%;
				height: 100vh;
				max-width: 70%;
				top: 0px;
				margin-inline-start: -100vmax;
				overflow-y: auto;
				z-index: 9000;
				padding-bottom: 40px;
				box-shadow: var(--shadow-5);
				" . (Style::UniversalProperty("transition", "var(--transition-2)")) . "
			}

			.{$this->Name} .container{
				padding: 0px;
			}

			.{$this->Name} .header{
				background-color: var(--fore-color-5);
				color: var(--back-color-5);
				padding: 5px;
			}
			.{$this->Name} .footer{
				width:100%;
				display: flex;
				flex-direction: column;
				flex-wrap: nowrap;
				align-content: space-between;
				justify-content: space-around;
				align-items: stretch;
			}
			.{$this->Name} .header, .{$this->Name} .header a, .{$this->Name} .header a:visited{
				color: var(--back-color-5);
			}
			$notactiveselector .header .branding{
				display: none;
			}
			$activeselector .header .branding{
				display: table-cell;
			}
			.{$this->Name} .header .title{
				font-size: var(--size-2);
				padding: 0px 10px;
				" . (isValid($this->Description) ? "line-height: var(--size-2);" : "") . "
			}
			.{$this->Name} .header .description{
				font-size: var(--size-0);
				padding: 0px 10px;
			}
			.{$this->Name} .header .image{
				background-position: center;
				background-repeat: no-repeat;
				background-size: 80% auto;
				background-color: transparent;
				display: table-cell;
				font-size: var(--size-0);
				width: {$this->LogoWidth};
				height: {$this->LogoHeight};
			}

			.{$this->Name} :is(button, .button){
				border: var(--border-0);
				border-radius: var(--radius-0);
				box-shadow: var(--shadow-0);
			}
			.{$this->Name} :is(button, .button):hover{
				box-shadow: var(--shadow-2);
			}

			$activeselector .main-items .item :is(a, a:visited){
				column-gap: var(--size-1);
				" . (Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}

			.{$this->Name} .row{
				margin: 0px;
			}

			.{$this->Name} ul :is(li, li a, li a:visited, li .button, li .button:visited){
				border: none;
				text-align: start;
				background-color: inherit;
				width: 100%;
				color: inherit;
			}
				
			.{$this->Name} ul li .image{
				margin-inline-end: var(--size-0);
			}
			.{$this->Name} ul li .button>.description{
				opacity: 0.7;
				font-size: var(--size-0);
			}
			.{$this->Name} ul li .button:hover>.description{
				opacity: 1;
			}

			.{$this->Name} ul li .icon{
				font-size: var(--size-2);
			}

			.{$this->Name} ul li.dropdown{
				position: initial;
			}
			.{$this->Name} ul li.dropdown ul{
				text-align: start;
			}

			.{$this->Name} ul:not(.sub-items) {
				list-style: none;
				list-style-type: none;
				margin: 0;
				padding: 0;
				overflow: hidden;
				display: flex;
				align-items: stretch;
				width: 100%;
				flex-direction: column;
				flex-wrap: nowrap;
			}

			.{$this->Name} ul:not(.sub-items)>li.active{
				border-inline-start: var(--border-2) var(--back-color-special);
			}
			.{$this->Name} ul:not(.sub-items)>li>:is(.button, .button:visited){
				border: none;
				font-size: inherit;
				border-radius: unset;
				text-decoration: none;
				padding: var(--size-0) var(--size-1);
				display: block;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} ul:not(.sub-items)>li:hover>:is(.button, .button:visited) {
				font-weight: bold;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} ul:not(.sub-items)>li.dropdown:hover>ul.sub-items {
				display: block;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}

			.{$this->Name} ul.sub-items {
				display: none;
				padding: 0px;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} ul.sub-items .sub-items {
				display: flex;
				position: relative;
				font-size: 80%;
				padding: 0px;
				padding-inline-start: var(--size-5);
				padding-bottom: calc(var(--size-0) / 2);
				box-shadow: var(--shadow-1);
				flex-wrap: wrap;
				flex-direction: row;
				align-content: stretch;
				justify-content: flex-start;
				align-items: stretch;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} ul.sub-items .sub-items li :is(.button, .button:visited) {
				padding: calc(var(--size-0) / 2) var(--size-1);
				border-color: transparent;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} ul.sub-items>li {
				font-size: 80%;
				display: block;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} ul.sub-items>li>:is(.button, .button:visited){
				text-decoration: none;
				padding: calc(var(--size-1) / 2) var(--size-1);
				display: block;
				width: 100%;
				text-align: start;
			}
			.{$this->Name} ul.sub-items>li.dropdown{
				display: block;
				border-bottom: var(--border-1) transparent;
			}
			.{$this->Name} ul.sub-items>li.dropdown:hover{
				border-bottom: var(--border-1) var(--back-color-special-output);
				box-shadow: var(--shadow-1);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} ul.sub-items>li.dropdown:hover>:is(.button, .button:visited){
				font-weight: bold;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} ul.sub-items>li.dropdown>:is(.button, .button:visited):hover{
				font-weight: bold;
				border: none;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} ul.sub-items>li:not(.dropdown):hover>:is(.button, .button:visited){
				font-weight: bold;
				border: none;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			" . (
			isValid($this->BackgroundMask) ? "
			.{$this->Name}-background-mask {
				background: {$this->BackgroundMask};
				z-index:1;
			}
			" : "") . ($this->AllowSignButton ? "
				.{$this->Name}-sign-button-menu{
					font-size:  var(--size-3);
					cursor: pointer;
					margin: auto;
					" . ($sdir) . ": 2px;
					top: 0px;
					padding: 0px 5px;
					position: fixed;
					z-index: 9999;
					color:  var(--fore-color-special);
					" . (Style::UniversalProperty("transition", "var(--transition-1)")) . "
				}
				.{$this->Name}-sign-button-menu:hover{
					color: var(--fore-color);
				}
		" : "") . ($this->AllowDefaultButtons ? "
				.{$this->Name} .other{
					text-align: center;
					display: flex !important;
					justify-content: flex-start;
					gap: var(--size-0);
					padding: var(--size-0);
				}
				.{$this->Name} .other :is(button, .button){
					aspect-ratio: 1;
					border-radius: var(--radius-max);
				}

				.{$this->Name} .other form{
					margin: 0px;
					padding: 0px;
					text-decoration: none;
					color: var(--fore-color-output);
					background-color: var(--back-color-output);
					border: var(--border-1) var(--back-color-special-output);
					border-radius: var(--radius-3);
					box-shadow: var(--shadow-1);
					overflow: hidden;
					display: flex;
					align-content: center;
					align-items: center;
					justify-content: space-between;
					max-width: calc(100% - 6 * var(--size-0));
					" . (Style::UniversalProperty("transition", "var(--transition-1)")) . "
				}
				.{$this->Name} .other form:is(:hover, :active, :focus) {
					font-weight: bold;
					color: var(--fore-color-input);
					background-color: var(--back-color-input);
				}
				.{$this->Name} .other form :is(input, .input, .input:is(:hover, :active, :focus)) {
					max-width: calc(100% - 3 * var(--size-0));
					padding: calc(var(--size-0) / 2) var(--size-0);
					border: none;
					outline: none;
					background-color: transparent;
					color: unset;
				}
				.{$this->Name} .other form :is(button, .button)  {
					padding: calc(var(--size-0) / 2);
				}
					
        " : "") . ($this->AllowHide ? ("
			.{$this->Name}{
				width: 50vmax;
				margin-inline-start: -100vmax;
				display: none;
			}
			$activeselector{
				margin-inline-start: 0;
				display: block !important;
				" . (Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			.{$this->Name} .header .image{
				width: var(--size-5);
				aspect-ratio: 1;
			}") : ("
			.{$this->Name}{
				width: auto;
				margin-inline-start: 0;
			}
			$activeselector{
				display: block !important;
			}
			$notactiveselector .header .image{
				display: block;
				width: 100%;
				height: var(--size-5);
				" . (Style::UniversalProperty("transition", "var(--transition-0)")) . "
			}
			$activeselector .header .image{
				display: table-cell;
				width: var(--size-3);
				aspect-ratio: 1;
				" . (Style::UniversalProperty("transition", "var(--transition-0)")) . "
			}
			.{$this->Name} .pin-button{
				background-color: transparent;
				color: inherit;
				border: none;
				padding: calc(var(--size-0) / 3);
				aspect-ratio: 1;
				" . (Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			.{$this->Name} .pin-button:hover{
				background-color: #8881;
			}
			$notactiveselector .main-items .box{
				display: none;
				width: 0px;
				overflow: hidden;
				opacity: 0;
				" . (Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			$activeselector .main-items .box{
				display: inherit;
				width: 100%;
				opacity: 1;
				padding-inline-end: var(--size-5);
				" . (Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			$notactiveselector>:not(.container, .header), $notactiveselector .header .division{
				height: 0px;
				width: 0px;
				overflow: hidden;
				" . (Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			$activeselector>:not(.container, .header), $activeselector .header .division{
				height: fit-content;
				width: inherit;
				" . (Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
		")));
	}

	public function Get()
	{
		return Convert::ToString(function () {
			if ($this->AllowBranding)
				yield Struct::Header(
					(isValid($this->Image) ? Struct::Media("", $this->Image, ["class" => 'td image', "rowspan" => '2']) : "") .
					Struct::Division(
						(isValid($this->Description) ? Struct::Division(__($this->Description), ["class" => "td description"]) : "") .
						(isValid($this->Title) ? Struct::Division(Struct::Link(__($this->Title), '/'), ["class" => "td title"]) : "")
						,
						["class" => "td branding"]
					)
					,
					["class" => "td header"]
				);
			if ($this->AllowDefaultButtons || $this->Buttons) {
				$defaultButtons = [];
				if ($this->AllowDefaultButtons) {
					module("SearchForm");
					module("TemplateButton"); 
					$defaultButtons[] = new searchForm();
					if (\_::$User->AllowSigning) $defaultButtons[] = Struct::Icon("user", \_::$User->InHandlerPath);
					$defaultButtons[] = new TemplateButton();
				}
				yield Struct::Division([
						...($defaultButtons? $defaultButtons : []),
						...($this->Buttons? (is_array($this->Buttons)?$this->Buttons:[$this->Buttons]) : [])
					],
					["class" => "other view {$this->ButtonsScreenSize}-show"]
				);
			}
			if ($this->AllowItems)
				if (count($this->Items) > 0)
					yield Struct::Items(
						function () {
							foreach ($this->Items as $item)
								{
									if($item["Items"]??null)$item["Path"] = null;
									yield $this->CreateItem($item, 1);
								}
						},
						["onclick"=>"{$this->Name}_ViewSideMenu(false);"],
						["class" => (isValid($this->ShowItemsScreenSize) ? $this->ShowItemsScreenSize . '-show' : '') . ' ' . (isValid($this->HideItemsScreenSize) ? $this->HideItemsScreenSize . '-hide' : '')]
					);
			yield $this->GetContent();
			if ($this->AllowDefaultButtons && !isEmpty($this->Shortcuts)) {
				yield "<div class='footer'>";
				module("Shortcuts");
				$module = new Shortcuts();
				$module->Items = $this->Shortcuts;
				yield $module->ToString();
				if (!$this->AllowHide)
					yield Struct::Icon("map-pin", "$('.{$this->Name}').toggleClass('active')", ["class" => "btn pin-button"]);
				yield "</div>";
			}
		});
	}

	protected function CreateItem($item, $ind = 1)
	{
		if (!\_::$User->GetAccess(getValid($item, "Access", \_::$User->VisitAccess)))
			return null;
		$path = getBetween($item, "Path");
		$act = endsWith(\_::$Address->Path, $path) ? 'active' : '';
		$ind++;
		$count = count(getValid($item, "Items", []));
		return Struct::Item(
			($ind <=2? Struct::Button(
				($this->AllowItemsImage?Struct::Image(null, getBetween($item, "Image", "Icon")):"").
				($this->AllowItemsLabel?__(getBetween($item, "Title", "Name")):"").
				($this->AllowItemsDescription?Struct::Division(__(get($item, "Description")), ["class"=>"description"]):""),
				$path,
				get($item, "Attributes")
				) :
				Struct::Button(
					($this->AllowSubItemsImage?Struct::Image(null, getBetween($item, "Image", "Icon")):"").
					($this->AllowSubItemsLabel?__(getBetween($item, "Title", "Name")):"").
					($this->AllowSubItemsDescription?Struct::Division(__(get($item, "Description")), ["class"=>"description"]):""),
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

	public function BeforeHandle()
	{
		if (isValid($this->BackgroundMask))
			return "<div class=\"background-mask {$this->Name}-background-mask view hide\" onclick=\"{$this->Name}_ViewSideMenu(false);\"></div>";
	}

	public function AfterHandle()
	{
		return parent::AfterHandle() .
			($this->AllowSignButton ?
				Struct::Division(
					$this->SignButtonText,
					[
						"class" => "{$this->Name}-sign-button-menu view {$this->SignButtonScreenSize}-show",
						"onclick" => "{$this->Name}_ViewSideMenu()"
					]
				) : ""
			);
	}

	public function GetScript()
	{
		return parent::GetScript() . Struct::Script("
			function {$this->Name}_ViewSideMenu(show){
				if(show === undefined) show = !document.querySelector('.{$this->Name}').classList.contains('active');
				if(show) {
					document.querySelector('.{$this->Name}').classList.add('active');
					document.querySelector('.{$this->Name}-background-mask').classList.remove('hide');
				}
				else {
					document.querySelector('.{$this->Name}').classList.remove('active');
					document.querySelector('.{$this->Name}-background-mask').classList.add('hide');
				}
			}
		");
	}
}
?>