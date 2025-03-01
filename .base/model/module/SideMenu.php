<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Convert;
module("SearchForm");
module("UserMenu");
module("TemplateButton");
class SideMenu extends Module
{
	public $Image = null;
	public $Items = null;
	public $Shortcuts = null;
	public $Direction = "LTR";
	public SearchForm|null $SearchForm = null;
	public UserMenu|null $UserMenu = null;
	public TemplateButton|null $TemplateButton = null;
	public $HasBranding = true;
	public $HasItems = true;
	public $HasOthers = true;
	public $HasImages = true;
	public $HasTitles = true;
	public $AllowChangeColor = true;
	public $AllowHide = true;
	public $AllowHoverable = true;
	public $AllowSignButton = true;
	public $SignButtonText = "&#9776;";
	public $SignButtonScreenSize = "md";
	public $OthersScreenSize = "md";

	public function __construct()
	{
		parent::__construct();
		$this->SearchForm = new SearchForm();
		if (\_::$Config->AllowSigning) {
			$this->UserMenu = new UserMenu();
			$this->UserMenu->Path = null;
		}
		$this->TemplateButton = new TemplateButton();
		$this->Direction = \_::$Back->Translate->Direction ?? \_::$Config->DefaultDirection;
	}

	public function GetStyle()
	{
		$this->Direction = strtoupper($this->Direction);
		$sdir = $this->Direction == "RTL" ? "left" : "right";
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
				" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(2))) . "
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
			}

			.{$this->Name}>:not(.header, .other) :is(.item, a, a:visited){
				color: var(--back-color-2);
			}
			.{$this->Name}>:not(.header, .other) :is(.item, a, a:visited):hover, .{$this->Name}>:not(.header, .other) :is(.item, a, a:visited):hover *{
				color: var(--fore-color-2);
			}
			$activeselector .main-items .item :is(a, a:visited){
				column-gap: var(--size-1);
				" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
			}

			.{$this->Name} .row{
				margin: 0px;
			}

			.{$this->Name} .main-items.items{
				color: var(--back-color-2);
				text-transform: uppercase;
				padding: 0px;
				margin: 0vmax 0px 3vmax 0px;
			}
			$notactiveselector .main-items .item{
				padding: var(--size-0) calc(var(--size-0) / 3);
				" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
			}
			$activeselector .main-items .item{
				padding: var(--size-0) var(--size-1);
				" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
			}
			.{$this->Name} .main-items .item:hover{
				background-color: var(--back-color-2);
				color: var(--fore-color-2);
			}
			$activeselector .main-items .item.active{
				border: none;
				border-inline-start: 2vmin solid var(--back-color-2);
			}
			.{$this->Name} .main-items .item :is(a, a:visited, a:active){
				display: flex;
				color: var(--back-color-2);
				justify-content: space-evenly;
				flex-direction: row;
				align-content: space-between;
				flex-wrap: nowrap;
				align-items: center;
			}
			$notactiveselector .main-items .item.active{
				border: none;
				background-color: var(--back-color-0);
				color: var(--fore-color-0);
			}
			$notactiveselector .main-items .item.active :is(a, a:visited, a:active){
				color: var(--fore-color-0);
			}
			.{$this->Name} .main-items .item .image{
				height: var(--size-0);
				margin: calc(var(--size-0) / 2);
                " . ($this->AllowChangeColor ? \MiMFa\Library\Style::DropColor(\_::$Front->BackColor(2)) : "") . "
				" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
			}
			.{$this->Name} .main-items .item:hover .image{
                " . ($this->AllowChangeColor ? \MiMFa\Library\Style::DropColor(\_::$Front->ForeColor(2)) : "") . "
			}
			$notactiveselector .main-items .item.active .image{
				height: var(--size-1);
                " . ($this->AllowChangeColor ? \MiMFa\Library\Style::DropColor(\_::$Front->ForeColor(0)) : "") . "
			}
			.{$this->Name} .main-items .box{
				width: 100%;
			}
			.{$this->Name} .fa{
				font-size:  var(--size-2);
			}
			" . ($this->AllowSignButton ? "
				.{$this->Name}-sign-button-menu{
					font-size:  var(--size-3);
					cursor: pointer;
					margin: auto;
					" . ($sdir) . ": 2px;
					top: 0px;
					padding: 0px 5px;
					position: fixed;
					z-index: 9999;
					color:  var(--fore-color-2);
					" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
				}
				.{$this->Name}-sign-button-menu:hover{
					color: var(--fore-color-0);
				}

				.{$this->Name} .other{
					text-align: center;
				}
				.{$this->Name} .other>div{
					width: fit-content;
					display: initial;
				}
				.{$this->Name} .other .btn{
					color: unset;
					background-color: unset;
					border: none;
				}

				.{$this->Name} form{
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
					" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
				}
				.{$this->Name} form:is(:hover, :active, :focus) {
					font-weight: bold;
					color: var(--fore-color-1);
					background-color: var(--back-color-1);
				}
				.{$this->Name} form :not(html,head,body,style,script,link,meta,title){
					padding: 0px;
					margin: 0px;
					display: inline-block;
					color: var(--fore-color-2);
					background-color: transparent;
					outline: none;
					border: none;
					" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
				}
				.{$this->Name} form:is(:hover, :active, :focus) :not(html,head,body,style,script,link,meta,title) {
					font-weight: bold;
					outline: none;
					border: none;
					" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
				}
				.{$this->Name} form:is(:hover, :active, :focus) :is(button, button :not(html,head,body,style,script,link,meta,title))  {
					color: var(--back-color-2);
				}
				.{$this->Name} form input[type='search']{
            		width: calc(100% - 50px);
					" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
				}
				.{$this->Name} form:is(:hover, :active, :focus) input[type='search'], .{$this->Name} form input[type='search']:is(:hover, :active, :focus){
					color: var(--fore-color-1);
				}<?php
        " : "") . ($this->UserMenu != null ? "
			.{$this->UserMenu->Name} :is(button, a).menu{
				aspect-ratio: initial !important;
				width: 100% !important;
				margin: 0px !important;
			}
			.{$this->UserMenu->Name} .menu>*{
				width: 40% !important;
				margin: 0px 30% !important;
			}
			.{$this->UserMenu->Name} .submenu{
				position: relative !important;
				width: 100% !important;
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
				" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
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
				" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(0))) . "
			}
			$activeselector .header .image{
				display: table-cell;
				width: var(--size-3);
				aspect-ratio: 1;
				" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(0))) . "
			}
			.{$this->Name} .pin-button{
				background-color: transparent;
				color: inherit;
				border: none;
				padding: calc(var(--size-0) / 3);
				aspect-ratio: 1;
				" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
			}
			.{$this->Name} .pin-button:hover{
				background-color: #8881;
			}
			$notactiveselector .main-items .box{
				display: none;
				width: 0px;
				overflow: hidden;
				opacity: 0;
				" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
			}
			$activeselector .main-items .box{
				display: inherit;
				width: 100%;
				opacity: 1;
				padding-inline-end: var(--size-5);
				" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
			}
			$notactiveselector>:not(.container, .header), $notactiveselector .header .division{
				height: 0px;
				width: 0px;
				overflow: hidden;
				" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
			}
			$activeselector>:not(.container, .header), $activeselector .header .division{
				height: fit-content;
				width: inherit;
				" . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
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

			if ($this->HasOthers)
				yield Html::Division(
					($this->SearchForm != null ? $this->SearchForm->ToString() : "") .
					($this->UserMenu != null ? $this->UserMenu->ToString() : "") .
					($this->TemplateButton != null ? $this->TemplateButton->ToString() : "")
					,
					["class" => "other {$this->OthersScreenSize}-show"]
				);

			if ($this->HasItems) {
				$count = count($this->Items);
				if ($count > 0) {
					$menuTags = "";
					$ll = 999999999;
					$i = 0;
					foreach ($this->Items as $item) {
						if (auth(findValid($item, "Access", \_::$Config->VisitAccess))) {
							$sl = findValid($item, 'Layer', 1);
							if ($sl <= $ll) {
								if ($sl <= $ll && $i !== 0)
									$menuTags .= "</div>";
								$menuTags .= "<div class='row'>";
							}
							$ll = $sl;
							$link = findBetween($item, 'Path', 'Link') ?? "";
							$menuTags .= Html::Item(
								Html::Link(
									($this->HasImages ? Html::Image(findBetween($item, "Image", "Icon", "Logo")) : "") .
									($this->HasTitles ? Html::Division(
										__(findBetween($item, 'Name', 'Title'), true, false)
										,
										["class" => "box"]
									) : ""),
									$link,
									get($item, 'Attributes')
								),
								["class" => "col-sm " . ((endsWith(\Req::$Url, $link) ? 'active' : ''))]
							);
						}
						$i++;
					}
					//$menuTags .= "</div>";
					yield Html::Container(
						(isValid($this->Content) ? Html::Content(__($this->Content, true, false)) : "") .
						Html::Items($menuTags, ["class" => "main-items"]),
						["onclick" => "{$this->Name}_ViewSideMenu(false);"]
					);
				}
			}
			if ($this->HasOthers && !isEmpty($this->Shortcuts)) {
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
				if(show === undefined) $('.{$this->Name}').toggleClass('active');
				else if(show) $('.{$this->Name}').addClass('active');
				else $('.{$this->Name}').removeClass('active');
			}
			$('.page').click(function(){ {$this->Name}_ViewSideMenu(false); });
		");
	}
}
?>