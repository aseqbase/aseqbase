<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
use MiMFa\Library\Style;

class SideMenu extends Module
{
	public $Image = null;
	public $Items = null;
	public $Shortcuts = null;
	public $Direction = "ltr";
	public $BackgroundMask = "#00000099";
	public $AllowBranding = true;
	public $AllowItems = true;
	public $AllowItemsTitle = true;
	public $AllowSubItemsTitle = true;
	public $AllowItemsDescription = true;
	public $AllowSubItemsDescription = true;
	public $AllowItemsImage = true;
	public $AllowSubItemsImage = true;
	public $AllowHide = true;
	public $AllowHoverable = true;
	/**
	 * Leave null to use the defalut buttons otherwise put your buttons
	 * @var 
	 */
	public $Buttons = null;
	public $AllowSearch = true;
	public $AllowTemplate = true;
	public $AllowProfile = true;
	public $AllowDefaultButtons = true;
	public $AllowFloatButton = true;
	public $FloatButtonText = "&#9776;";
	public $FloatButtonScreenSize = "md";
	public $ToggleLabel;
	public $LogoWidth = "calc(1.25 * var(--size-5))";
	public $LogoHeight = "calc(1.25 * var(--size-5))";
	public $Printable = false;


	public function __construct($items = null)
	{
		parent::__construct();
		$this->Items = $items ?? $this->Items;
		$this->Direction = \_::$Front->Translate->Direction ?? \_::$Front->Direction;
		$this->ToggleLabel = Struct::Icon("angle-down", null, ["class"=>"hoverable"]);
	}

	public function GetStyle()
	{
		$this->Direction = strtolower($this->Direction);
		$sdir = ($this->Direction == "rtl") ? "left" : "right";
		$activeselector = $this->AllowHoverable ? ".{$this->MainClass}:is(.active, :hover)" : ".{$this->MainClass}.active";
		$notactiveselector = ".{$this->MainClass}:not(.active, :hover)";
		yield parent::GetStyle();
		yield Struct::Style(content: "
			.{$this->MainClass}{
				background-color: var(--fore-color-output);
				color: var(--back-color-output);
				font-size: var(--size-1);
				position: fixed;
				max-height: 100%;
				height: 100vh;
				max-width: 80%;
				top: 0px;
				margin-inline-start: -100vmax;
				overflow-y: auto;
				z-index: 9000;
				padding-bottom: 40px;
				box-shadow: var(--shadow-5);
				" . (Style::UniversalProperty("transition", "var(--transition-2)")) . "
			}

			.{$this->MainClass} .container{
				padding: 0px;
			}

			.{$this->MainClass} .header{
				background-color: var(--fore-color-5);
				color: var(--back-color-5);
				padding: 5px;
			    display: flex;
				gap: var(--size-0);
    			align-items: center;
			}
			.{$this->MainClass} .header, .{$this->MainClass} .header a, .{$this->MainClass} .header a:visited{
				color: var(--back-color-5);
			}
			.{$this->MainClass} .header .title{
				font-size: var(--size-2);
				" . (isValid($this->Description) ? "line-height: var(--size-2);" : "") . "
			}
			.{$this->MainClass} .header .description{
				font-size: var(--size-0);
			}
			.{$this->MainClass} .header .image{
				background-position: center;
				background-repeat: no-repeat;
				background-size: 80% auto;
				background-color: transparent;
				font-size: var(--size-0);
				width: {$this->LogoWidth};
				height: {$this->LogoHeight};
			}

			.{$this->MainClass} .footer{
				width:100%;
				display: flex;
				flex-direction: column;
				flex-wrap: nowrap;
				align-content: space-between;
				justify-content: space-around;
				align-items: stretch;
			}

			.{$this->MainClass} :is(button, .button){
				border: var(--border-0);
				border-radius: var(--radius-0);
				box-shadow: var(--shadow-0);
			}
			.{$this->MainClass} :is(button, .button):hover{
				box-shadow: var(--shadow-2);
			}

			$activeselector .main-items .item :is(a, a:visited){
				column-gap: var(--size-1);
				" . (Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			.{$this->MainClass} .row{
				margin: 0px;
			}

			.{$this->MainClass} ul :is(li, li a, li a:visited, li .button, li .button:visited){
				border: none;
				text-align: start;
				background-color: inherit;
				width: 100%;
				color: inherit;
				padding: calc(var(--size-0) / 4);
			}
				
			$activeselector ul li .image{
				margin-inline-end: var(--size-0);
			}
			$activeselector ul li .button>.title{
				font-size: 110%;
			}
			$activeselector ul li .button>.description{
				opacity: 0.7;
				font-size: 90%;
				line-height: 150%;
			}
			$activeselector ul li .button:hover>.description{
				opacity: 0.9;
			}

			.{$this->MainClass} ul li .icon{
				font-size: var(--size-2);
			}

			.{$this->MainClass} ul li.dropdown{
				position: initial;
			}
			.{$this->MainClass} ul li.dropdown ul{
				margin-inline-start: var(--size-0);
				text-align: start;
			}

			.{$this->MainClass} .main-items {
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
			$notactiveselector .main-items {
				padding-top: var(--size-1);
			}
			$notactiveselector ul>li.active{
				background-color: var(--back-color-special);
				color: var(--fore-color-special);
			}
			$activeselector .main-items>li.active{
				border-inline-start: var(--border-2) var(--back-color-special);
			}
			$activeselector .main-items>li>:is(.button, .button:visited){
				border: none;
				font-size: inherit;
				border-radius: unset;
				text-decoration: none;
				padding: var(--size-0) var(--size-1);
				display: block;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass} .main-items>li:hover>:is(.button, .button:visited) {
				font-weight: bold;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass} ul.sub-items {
				display: none;
				font-size: var(--size-0);
				padding: 0px;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass} ul.sub-items.active {
				display: block;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass} ul.sub-items>li {
				display: block;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass} ul.sub-items>li>:is(.button, .button:visited){
				text-decoration: none;
				padding: calc(var(--size-1) / 2) var(--size-1);
				display: block;
				width: 100%;
				text-align: start;
			}
			.{$this->MainClass} ul.sub-items>li.dropdown{
				display: block;
				border-bottom: var(--border-1) transparent;
			}
			.{$this->MainClass} ul.sub-items>li.dropdown:hover{
				border-bottom: var(--border-1) var(--back-color-special-output);
				box-shadow: var(--shadow-1);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass} ul.sub-items>li.dropdown:hover>:is(.button, .button:visited){
				font-weight: bold;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass} ul.sub-items>li.dropdown>:is(.button, .button:visited):hover{
				font-weight: bold;
				border: none;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass} ul.sub-items>li:not(.dropdown):hover>:is(.button, .button:visited){
				font-weight: bold;
				border: none;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			" . (
			isValid($this->BackgroundMask) ? "
			.{$this->MainClass}-background-mask {
				background: {$this->BackgroundMask};
				z-index:1;
			}
			" : "") . ($this->AllowFloatButton ? "
				.{$this->MainClass}-float-button-menu{
					font-size:  var(--size-5);
					line-height:  var(--size-max);
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
				.{$this->MainClass}-float-button-menu:hover{
					color: var(--fore-color);
				}
		" : "") . ($this->AllowDefaultButtons ? "
				.{$this->MainClass} .other{
					text-align: center;
					display: flex;
					justify-content: flex-start;
					gap: var(--size-0);
					padding: var(--size-0);
				}
				.{$this->MainClass} .other :is(button, .button, .icon){
					border-radius: var(--radius-max);
					padding: calc(var(--size-0) / 4);
					aspect-ratio: 1;
				}

				.{$this->MainClass} .other form{
					margin: 0px;
					padding: 0px;
					text-decoration: none;
					color: var(--fore-color-output);
					background-color: var(--back-color-output);
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
				.{$this->MainClass} .other form:is(:hover, :active, :focus) {
					font-weight: bold;
					color: var(--fore-color-input);
					background-color: var(--back-color-input);
				}
				.{$this->MainClass} .other form :is(input, .input, .input:is(:hover, :active, :focus)) {
					max-width: calc(100% - 3 * var(--size-0));
					padding: calc(var(--size-0) / 2) var(--size-0);
					border: none;
					outline: none;
					background-color: transparent;
					color: unset;
				}
				.{$this->MainClass} .other form :is(button, .button)  {
					padding: calc(var(--size-0) / 2);
				}
					
        " : "") . ($this->AllowHide ? ("
			$notactiveselector{
				width: 50vmax;
				margin-inline-start: -100vmax;
				display: none;
			}
			$activeselector{
				margin-inline-start: 0px;
				display: block;
			}
			.{$this->MainClass} .header .image{
				width: var(--size-5);
				aspect-ratio: 1;
			}
			") : 
			("
			body{
    			margin-inline-start: var(--size-max) !important;
			}
			.{$this->MainClass}{
				width: auto;
				margin-inline-start: calc(-1 * var(--size-max));
			}
			$notactiveselector .header{
    			justify-content: center;
			}
			.{$this->MainClass} .pin-button{
				background-color: transparent;
				color: inherit;
				border: none;
				padding: calc(var(--size-0) / 3);
				display:flex;
				justify-content: flex-end;
				" . (Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			.{$this->MainClass} .pin-button:hover{
				background-color: #8881;
			}
			$notactiveselector .hoverable{
				display: none;
			}
		")));
	}

	public function GetInner()
	{
		if ($this->AllowBranding)
			yield Struct::Header(
				(isValid($this->Image) ? Struct::Media("", $this->Image, ["class" => 'td image', "rowspan" => '2']) : "") .
				Struct::Division(
					(isValid($this->Description) ? Struct::Division(__($this->Description), ["class" => "td description"]) : "") .
					(isValid($this->Title) ? Struct::Division(Struct::Link(__($this->Title), '/'), ["class" => "td title"]) : "")
					,
					["class" => "td branding hoverable"]
				)
				,
				["class" => "td header"]
			);
		if ($this->AllowDefaultButtons || $this->Buttons) {
			$defaultButtons = [];
			if ($this->AllowDefaultButtons) {
				module("SearchForm");
				module("TemplateButton");
				if ($this->AllowSearch)
					$defaultButtons[] = new SearchForm();
				if ($this->AllowProfile && \_::$User->AllowSigning)
					$defaultButtons[] = Struct::Button(Struct::Icon("user"), \_::$User->InHandlerPath);
				if ($this->AllowTemplate)
					$defaultButtons[] = new TemplateButton();
			}
			yield Struct::Box(
				[
					...($defaultButtons ? $defaultButtons : []),
					...($this->Buttons ? (is_array($this->Buttons) ? $this->Buttons : [$this->Buttons]) : [])
				],
				["class" => "other hoverable"]
			);
		}
		if ($this->AllowItems)
			if (count($this->Items) > 0)
				yield Struct::Items(
					function () {
						foreach ($this->Items as $item)
							yield $this->CreateItem($item, 1);
					},
					["class" => "main-items ".(isValid($this->ShowItemsScreenSize) ? $this->ShowItemsScreenSize . '-show' : '') . ' ' . (isValid($this->HideItemsScreenSize) ? $this->HideItemsScreenSize . '-hide' : '')]
				);
		yield $this->GetContent();
		if ($this->AllowDefaultButtons && !isEmpty($this->Shortcuts)) {
			yield "<div class='footer hoverable'>";
			module("Shortcuts");
			$module = new Shortcuts();
			$module->Items = $this->Shortcuts;
			yield $module->ToString();
			if (!$this->AllowHide)
				yield Struct::Icon("map-pin", "_('.{$this->MainClass}').toggleClass('active')", ["class" => "pin-button", "Tooltip"=>"To pin or unpin the menu"]);
			yield "</div>";
		}
	}

	protected function CreateItem($item, $ind = 1)
	{
		if (!\_::$User->HasAccess(getValid($item, "Access", \_::$User->VisitAccess)))
			return null;
		$ind++;
		$itms = loop(get($item, "Items") ?? [], fn($itm) => $this->CreateItem($itm, $ind));
		$count = count($itms);
		$path = $count ? "if(this.nextElementSibling.classList.contains('active'))
			_('.{$this->MainClass} ul.sub-items').removeClass('active');
		else {
			_(getQuery(this.parentElement.parentElement)+' .active').removeClass('active');
			this.nextElementSibling.classList.add('active');
		}" : getBetween($item, "Path");
		$act = endsWith(\_::$Address->UrlBase, $path) ? 'active' : '';
		return Struct::Item(
			($ind <= 2 ? Struct::Button(
				Struct::Box(
					Struct::Box(
						($this->AllowItemsImage && ($t = getBetween($item, "Icon", "Image")) ? Struct::Image(null, $t) : "") .
						($this->AllowItemsTitle && ($t = getBetween($item, "Title", "Name")) ? Struct::Span($t, null, ["class"=>"title hoverable"]) : "")
					) .
					($count > 0 ? $this->ToggleLabel : ""),
					["class" => "be flex justify"]
				) .
				($this->AllowItemsDescription && ($t = get($item, "Description")) ? Struct::Division(__($t), ["class" => "description hoverable"]) : ""),
				$path,
				get($item, "Attributes")
			) :
				Struct::Button(
					Struct::Box(
						Struct::Box(
							($this->AllowSubItemsImage && ($t = getBetween($item, "Icon", "Image")) ? Struct::Image(null, $t) : "") .
							($this->AllowSubItemsTitle && ($t = getBetween($item, "Title", "Name")) ? Struct::Span($t, null, ["class"=>"title hoverable"]) : "")
						) .
						($count > 0 ? $this->ToggleLabel : ""),
						["class" => "be flex justify"]
					) .
					($this->AllowSubItemsDescription && ($t = get($item, "Description")) ? Struct::Division(__($t), ["class" => "description hoverable"]) : ""),
					$path,
					get($item, "Attributes")
				)
			) .
			($count > 0 ?
				Struct::Items($itms, ["class" => "sub-items sub-items-$ind"])
				: ""),
			["class" => $count > 0 ? "dropdown $act" : $act]
		);
	}

	public function BeforeHandle()
	{
		if (isValid($this->BackgroundMask))
			return "<div class=\"background-mask {$this->MainClass}-background-mask view hide\" onclick=\"{$this->MainClass}_ViewSideMenu(false);\"></div>";
	}

	public function AfterHandle()
	{
		return parent::AfterHandle() .
			($this->AllowFloatButton ?
				Struct::Division(
					$this->FloatButtonText,
					[
						"class" => "{$this->MainClass}-float-button-menu view {$this->FloatButtonScreenSize}-show",
						"onclick" => "{$this->MainClass}_ViewSideMenu()"
					]
				) : ""
			);
	}

	public function GetScript()
	{
		yield parent::GetScript();
		yield Struct::Script("
			function {$this->MainClass}_ViewSideMenu(show){
				if(show === undefined) show = !document.querySelector('.{$this->MainClass}').classList.contains('active');
				if(show) {
					document.querySelector('.{$this->MainClass}').classList.add('active');
					document.querySelector('.{$this->MainClass}-background-mask').classList.remove('hide');
				}
				else {
					document.querySelector('.{$this->MainClass}').classList.remove('active');
					document.querySelector('.{$this->MainClass}-background-mask').classList.add('hide');
					_('.{$this->MainClass} ul.sub-items').removeClass('active');
				}
			}
		");
	}
}