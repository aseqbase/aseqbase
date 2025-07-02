<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Style;
use MiMFa\Library\Convert;
use MiMFa\Library\User;
class SideMenu extends Module
{
	public $Image = null;
	public $Items = null;
	public $Shortcuts = null;
	public $Direction = "ltr";
	public $HasBranding = true;
	public $HasItems = true;
	public $AllowItemsLabel = true;
	public $AllowSubItemsLabel = true;
	public $AllowItemsDescription = false;
	public $AllowSubItemsDescription = true;
	public $AllowItemsImage = false;
	public $AllowSubItemsImage = true;
	public $AllowChangeColor = true;
	public $BackgroundShadow = true;
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


	public function __construct()
	{
		parent::__construct();
		$this->Direction = \_::$Back->Translate->Direction ?? \_::$Config->DefaultDirection;
	}

	public function GetStyle()
	{
		$this->Direction = strtolower($this->Direction);
		$sdir = ($this->Direction == "rtl") ? "left" : "right";
		$activeselector = $this->AllowHoverable ? ".{$this->Name}:is(.active, :hover)" : ".{$this->Name}.active";
		$notactiveselector = ".{$this->Name}:not(.active)";
		return parent::GetStyle() . Html::Style("
			.{$this->Name}{
				background-color: var(--fore-color-2);
				color: var(--back-color-2);
				font-size: var(--size-1);
				position: fixed;
				max-height: 100%;
				height: 100vh;
				max-width: 70%;
				top: 0px;
				margin-inline-start: -100vmax;
				overflow-y: auto;
				z-index: 999;
				padding-bottom: 40px;
				box-shadow: var(--shadow-5);
				" . (Style::UniversalProperty("transition", \_::$Front->Transition(2))) . "
			}

			.{$this->Name} .container{
				padding: 0px;
			}

			.{$this->Name} .header{
				background-color:  var(--back-color-2);
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
				color: var(--fore-color-2);
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

			$activeselector .main-items .item :is(a, a:visited){
				column-gap: var(--size-1);
				" . (Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
			}

			.{$this->Name} .row{
				margin: 0px;
			}

			.{$this->Name} ul :is(li, li a, li a:visited, li .button, li .button:visited){
				border: none;
				text-align: start;
				background-color: inherit;
				color: inherit;
			}
				
			.{$this->Name} ul li .image{
				margin-inline-end: var(--size-0);
			}
			.{$this->Name} ul li .description{
				font-size: var(--size-0);
				color: #8888;
			}

			.{$this->Name} ul li .fa{
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
				border-inline-start: var(--border-2) var(--back-color-3);
			}
			.{$this->Name} ul:not(.sub-items)>li>:is(.button, .button:visited){
				border: none;
				font-size: inherit;
				border-radius: none;
				text-decoration: none;
				padding: var(--size-0) var(--size-1);
				display: block;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul:not(.sub-items)>li:hover>:is(.button, .button:visited) {
				font-weight: bold;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul:not(.sub-items)>li.dropdown:hover>ul.sub-items {
				display: block;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}

			.{$this->Name} ul.sub-items {
				display: none;
				padding: 0px;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
				z-index: 99;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
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
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul.sub-items .sub-items li :is(.button, .button:visited) {
				padding: calc(var(--size-0) / 2) var(--size-1);
				border-color: transparent;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul.sub-items>li {
				font-size: 80%;
				display: block;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul.sub-items>li>:is(.button, .button:visited){
				text-decoration: none;
				padding: calc(var(--size-1) / 2) var(--size-1);
				display: block;
				text-align: start;
			}
			.{$this->Name} ul.sub-items>li.dropdown{
				display: block;
				border-bottom: var(--border-1) transparent;
			}
			.{$this->Name} ul.sub-items>li.dropdown:hover{
				border-bottom: var(--border-1) var(--back-color-5);
				box-shadow: var(--shadow-1);
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul.sub-items>li.dropdown:hover>:is(.button, .button:visited){
				font-weight: bold;
				color: #8888;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul.sub-items>li.dropdown>:is(.button, .button:visited):hover{
				font-weight: bold;
				border: none;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul.sub-items>li:not(.dropdown):hover>:is(.button, .button:visited){
				font-weight: bold;
				border: none;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			" . (
			isValid($this->BackgroundShadow) ? "
			.{$this->Name}-background-screen {
				background-color: {$this->BackgroundShadow};
				width: 100%;
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
					color:  var(--fore-color-3);
					" . (Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
				}
				.{$this->Name}-sign-button-menu:hover{
					color: var(--fore-color-0);
				}
		" : "") . ($this->AllowDefaultButtons ? "
				.{$this->Name} .other{
					text-align: center;
					display: flex !important;
					flex-direction: row;
					flex-wrap: wrap;
					justify-content: center;
					align-content: center;
					align-items: center;
				}
				.{$this->Name} .other .button{
					color: unset;
					background-color: unset;
					border: none;
				}

				.{$this->Name} .other form{
					text-decoration: none;
					padding: 4px 10px;
					margin: 10px;
					display: block;
					color: var(--fore-color-2);
					background-color: var(--back-color-2);
					border: var(--border-1) var(--back-color-5);
					border-radius: var(--radius-3);
					box-shadow: var(--shadow-1);
					overflow: hidden;
					" . (Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
				}
				.{$this->Name} .other form:is(:hover, :active, :focus) {
					font-weight: bold;
					color: var(--fore-color-1);
					background-color: var(--back-color-1);
				}
				.{$this->Name} .other form :not(html,head,body,style,script,link,meta,title){
					padding: 0px;
					margin: 0px;
					display: inline-block;
					color: var(--fore-color-2);
					background-color: transparent;
					outline: none;
					border: none;
					" . (Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
				}
				.{$this->Name} .other form:is(:hover, :active, :focus) :not(html,head,body,style,script,link,meta,title) {
					font-weight: bold;
					outline: none;
					border: none;
					" . (Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
				}
				.{$this->Name} .other form:is(:hover, :active, :focus) :is(button, button :not(html,head,body,style,script,link,meta,title))  {
					color: var(--back-color-2);
				}
				.{$this->Name} .other form input[type='search']{
            		width: calc(100% - 50px);
					" . (Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
				}
				.{$this->Name} .other form:is(:hover, :active, :focus) input[type='search'], .{$this->Name} form input[type='search']:is(:hover, :active, :focus){
					color: var(--fore-color-1);
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
				" . (Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
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
				" . (Style::UniversalProperty("transition", \_::$Front->Transition(0))) . "
			}
			$activeselector .header .image{
				display: table-cell;
				width: var(--size-3);
				aspect-ratio: 1;
				" . (Style::UniversalProperty("transition", \_::$Front->Transition(0))) . "
			}
			.{$this->Name} .pin-button{
				background-color: transparent;
				color: inherit;
				border: none;
				padding: calc(var(--size-0) / 3);
				aspect-ratio: 1;
				" . (Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
			}
			.{$this->Name} .pin-button:hover{
				background-color: #8881;
			}
			$notactiveselector .main-items .box{
				display: none;
				width: 0px;
				overflow: hidden;
				opacity: 0;
				" . (Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
			}
			$activeselector .main-items .box{
				display: inherit;
				width: 100%;
				opacity: 1;
				padding-inline-end: var(--size-5);
				" . (Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
			}
			$notactiveselector>:not(.container, .header), $notactiveselector .header .division{
				height: 0px;
				width: 0px;
				overflow: hidden;
				" . (Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
			}
			$activeselector>:not(.container, .header), $activeselector .header .division{
				height: fit-content;
				width: inherit;
				" . (Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
			}
		")));
	}

	public function Get()
	{
		return Convert::ToString(function () {
			if ($this->HasBranding)
				yield Html::Header(
					(isValid($this->Image) ? Html::Media("", $this->Image, ["class" => 'td image', "rowspan" => '2']) : "") .
					Html::Division(
						(isValid($this->Description) ? Html::Division(__($this->Description, true, false), ["class" => "td description"]) : "") .
						(isValid($this->Title) ? Html::Division(Html::Link(__($this->Title, true, false), '/'), ["class" => "td title"]) : "")
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
					$defaultButtons[] = new SearchForm();
					if (\_::$Config->AllowSigning) $defaultButtons[] = Html::Button(Html::Icon("user"), User::$InHandlerPath);
					$defaultButtons[] = new TemplateButton();
				}
				yield Html::Division([
						...($defaultButtons? $defaultButtons : []),
						...($this->Buttons? (is_array($this->Buttons)?$this->Buttons:[$this->Buttons]) : [])
					],
					["class" => "other {$this->ButtonsScreenSize}-show"]
				);
			}
			if ($this->HasItems)
				if (count($this->Items) > 0)
					yield Html::Items(
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
			if ($this->AllowDefaultButtons && !isEmpty($this->Shortcuts)) {
				yield "<div class='footer'>";
				module("Shortcuts");
				$module = new Shortcuts();
				$module->Items = $this->Shortcuts;
				yield $module->ToString();
				if (!$this->AllowHide)
					yield Html::Icon("map-pin", "$('.{$this->Name}').toggleClass('active')", ["class" => "btn pin-button"]);
				yield "</div>";
			}
		});
	}

	protected function CreateItem($item, $ind = 1)
	{
		if (!auth(getValid($item, "Access", \_::$Config->VisitAccess)))
			return null;
		$path = getBetween($item, "Path", "Link", "Url");
		$act = endsWith(\Req::$Path, $path) ? 'active' : '';
		$ind++;
		$count = count(getValid($item, "Items", []));
		return Html::Item(
			($ind <=2? Html::Button(
				($this->AllowItemsImage?Html::Image(getBetween($item, "Image", "Icon")):"").
				($this->AllowItemsLabel?__(getBetween($item, "Title", "Name"), true, false):"").
				($this->AllowItemsDescription?Html::Division(__(get($item, "Description"), true, false), ["class"=>"description"]):""),
				$path,
				get($item, "Attributes")
				) :
				Html::Button(
					($this->AllowSubItemsImage?Html::Image(getBetween($item, "Image", "Icon")):"").
					($this->AllowSubItemsLabel?__(getBetween($item, "Title", "Name"), true, false):"").
					($this->AllowSubItemsDescription?Html::Division(__(get($item, "Description"), true, false), ["class"=>"description"]):""),
					$path,
					get($item, "Attributes")
				)
			) .
			($count > 0 ?
				Html::Items(
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
		if (isValid($this->BackgroundShadow))
			return "<div class=\"background-screen {$this->Name}-background-screen hide\" onclick=\"{$this->Name}_ViewSideMenu(false);\"></div>";
	}

	public function AfterHandle()
	{
		return parent::AfterHandle() .
			($this->AllowSignButton ?
				Html::Division(
					$this->SignButtonText,
					[
						"class" => "{$this->Name}-sign-button-menu {$this->SignButtonScreenSize}-show",
						"onclick" => "{$this->Name}_ViewSideMenu()"
					]
				) : ""
			);
	}

	public function GetScript()
	{
		return parent::GetScript() . Html::Script("
			function {$this->Name}_ViewSideMenu(show){
				if(show === undefined) show = !document.querySelector('.{$this->Name}').classList.contains('active');
				if(show) {
					document.querySelector('.{$this->Name}').classList.add('active');
					document.querySelector('.{$this->Name}-background-screen').classList.remove('hide');
				}
				else {
					document.querySelector('.{$this->Name}').classList.remove('active');
					document.querySelector('.{$this->Name}-background-screen').classList.add('hide');
				}
			}
		");
	}
}
?>