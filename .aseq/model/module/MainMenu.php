<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Style;
use MiMFa\Library\Convert;
class MainMenu extends Module
{
	public $Tag = "nav";
	public $Class = "row";
	public $Image = null;
	public $Items = null;
	public $Shortcuts = null;
	public $HasBranding = true;
	public $HasItems = true;
	public $HasOthers = true;
	public $AllowFixed = false;
	public $AllowItemsLabel = true;
	public $AllowSubItemsLabel = true;
	public $AllowItemsDescription = false;
	public $AllowSubItemsDescription = true;
	public $AllowItemsImage = false;
	public $AllowSubItemsImage = false;
	public $HideItemsScreenSize = 'md';
	public $ShowItemsScreenSize = null;
	public $HideOthersScreenSize = 'md';
	public $ShowOthersScreenSize = null;
	public $AllowDefaultButtons = true;
	public $LogoWidth = "calc(1.25 * var(--size-5))";
	public $LogoHeight = "calc(1.25 * var(--size-5))";
    public $Printable = false;

	public function GetStyle()
	{
		return parent::GetStyle() . Html::Style(
			"
			.{$this->Name} {
				margin: 0;
				padding: 0;
				background-color: " . \_::$Front->BackColor(3) . ($this->AllowFixed ? "ee" : "") . ";
				color: var(--fore-color-3);
				" . ($this->AllowFixed ? "
				position:fixed;
				top:0;
				left:0;
				right:0;
				z-index: 999;
            	" : "") . "
				box-shadow: var(--shadow-2);
			}
			" . ($this->AllowFixed ? "
			.{$this->Name}-margin{
				height: 75px;
				background: transparent;
			}
			" : "") . "

			.{$this->Name} .header{
				margin: 0;
				width: fit-content;
				padding: 0px 10px;
				display: flex;
				justify-content: flex-start;
				align-items: center;
				flex-direction: row;
			}
			.{$this->Name} :is(.header, .header a, .header a:visited, .header a:hover){
				text-decoration: none;
				font-weight: normal !important;
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
				width: {$this->LogoWidth};
				height: {$this->LogoHeight};
				font-size: var(--size-0);
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

			.{$this->Name}>ul>li:not(.sub-items)>button{
				text-transform: uppercase;
			}
				
			.{$this->Name} ul:not(.sub-items) {
				list-style: none;
				list-style-type: none;
				margin: 0;
				padding: 0;
				overflow: hidden;
				display: flex;
				align-items: center;
				" . ($this->AllowDefaultButtons? "
				min-width: fit-content;
				max-width: 70%;
				margin-inline-end: 100px;
				" : "") . "
			}
			.{$this->Name} ul:not(.sub-items)>li {
				background-color: transparent;
				color: inherit;
				display: inline-block;
			}
			.{$this->Name} ul:not(.sub-items)>li.active{
				border-top: var(--border-2) var(--back-color-3);
				color: " . \_::$Front->ForeColor(0) . "88;
				background-color: var(--back-color-0);
				box-shadow: var(--shadow-2);
			}
			.{$this->Name} ul:not(.sub-items)>li>:is(.button, .button:visited){
				background-color: transparent;
				color: var(--fore-color-3);
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
				background-color: var(--back-color-2);
				color: var(--fore-color-2);
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul:not(.sub-items)>li.active>:is(.button, .button:visited){
				color: " . \_::$Front->ForeColor(0) . "88;
			}
			.{$this->Name} ul:not(.sub-items)>li.active:hover>:is(.button, .button:visited){
				color: var(--fore-color-0);
			}
			.{$this->Name} ul:not(.sub-items)>li.dropdown:hover>:is(.button, .button:visited) {
				color: var(--fore-color-1);
				background-color: var(--back-color-1);
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul:not(.sub-items)>li.dropdown:hover>ul.sub-items {
				display: block;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}

			.{$this->Name} ul.sub-items {
				display: none;
				position: fixed;
				color: var(--fore-color-2);
				background-color: var(--back-color-1);
				min-width: 160px;
				max-width: 90vw;
				max-height: 70vh;
				padding: 0px;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
				overflow-x: hidden;
				overflow-y: auto;
				z-index: 99;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul.sub-items .sub-items {
				display: flex;
				position: relative;
				background-color: #8881;
				font-size: 80%;
				min-width: calc(5 * var(--size-5));
				max-width: 500px;
				width: 70vw;
				max-height: 60vh;
				padding: 0px;
				padding-inline-start: var(--size-5);
				padding-bottom: calc(var(--size-0) / 2);
				box-shadow: var(--shadow-1);
				overflow-x: hidden;
				overflow-y: auto;
				flex-wrap: wrap;
				flex-direction: row;
				align-content: stretch;
				justify-content: flex-start;
				align-items: stretch;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul.sub-items .sub-items li :is(.button, .button:visited) {
				padding: calc(var(--size-0) / 2) var(--size-1);
				background: transparent;
				border: none;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul.sub-items>li {
				font-size: 80%;
				display: block;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul.sub-items>li>:is(.button, .button:visited){
    			width: 100%;
				color: var(--fore-color-1);
				text-decoration: none;
				padding: calc(var(--size-1) / 2) var(--size-1);
				display: block;
				text-align: start;
				border: none;
			}
			.{$this->Name} ul.sub-items>li.dropdown{
				display: block;
				border-bottom: var(--border-1) transparent;
			}
			.{$this->Name} ul.sub-items>li.dropdown.active{
				box-shadow: var(--shadow-2);
				border: none;
			}
			.{$this->Name} ul.sub-items>li.dropdown.active>:is(.button, .button:visited){
				font-weight: bold;
				border: none;
			}
			.{$this->Name} ul.sub-items>li.dropdown:hover{
				border-bottom-color: var(--back-color-5);
				box-shadow: var(--shadow-1);
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul.sub-items>li.dropdown:hover>:is(.button, .button:visited){
				font-weight: bold;
				color: #8888;
				border: none;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul.sub-items>li.dropdown>:is(.button, .button:visited):hover{
				font-weight: bold;
				background-color: var(--back-color-5);
				color: var(--fore-color-5);
				border: none;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} ul.sub-items>li:not(.dropdown).active>:is(.button, .button:visited){
				font-weight: bold;
				box-shadow: var(--shadow-2);
				border: none;
			}
			.{$this->Name} ul.sub-items>li:not(.dropdown):hover>:is(.button, .button:visited){
				font-weight: bold;
				background-color: var(--back-color-5);
				color: var(--fore-color-5);
				border: none;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}

		" . ($this->HasOthers ? "
			.{$this->Name} .other{
				text-align: end;
				width: fit-content;
				position: absolute;
				clear: both;
				display: flex;
				align-items: center;
				" . ((\_::$Back->Translate->Direction ?? \_::$Config->DefaultDirection) == "rtl" ? "left" : "right") . ": var(--size-2);
			}
			.{$this->Name} .other>div{
				width: fit-content;
				display: inline-flex;
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
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} form:is(:hover, :active, :focus) {
				font-weight: bold;
				color: var(--fore-color-1);
				background-color: var(--back-color-1);
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} form :not(html,head,body,style,script,link,meta,title){
				padding: 0px;
				margin: 0px;
				display: inline-block;
				color: var(--fore-color-2);
				background-color: transparent;
				outline: none;
				border: none;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} form:is(:hover, :active, :focus) :not(html,head,body,style,script,link,meta,title) {
				font-weight: bold;
				outline: none;
				border: none;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} form:is(:hover, :active, :focus) :is(button, button :not(html,head,body,style,script,link,meta,title))  {
				color: var(--back-color-2);
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} form input[type='search']{
				max-width: 100%;
				width: 80%;
				width: 0px;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.{$this->Name} form:is(:hover, :active, :focus) input[type='search'], .{$this->Name} form input[type='search']:is(:hover, :active, :focus){
				color: var(--fore-color-1);
				width: 200px;
				" . Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}"
				: "")
		);
	}

	public function Get()
	{
		return Convert::ToString(function () {
			if ($this->HasBranding)
				yield Html::Division(
					(isValid($this->Image) ? Html::Link(Html::Media($this->Image, ['class' => 'col-sm image']), \_::$Info->Path) : "") .
					Html::Division(
						(isValid($this->Description) ? Html::Division(__($this->Description, true, false), ['class' => 'description']) : "") .
						(isValid($this->Title) ? Html::Link(Html::Division(__($this->Title, true, false), ['class' => 'title']), \_::$Info->Path) : ""),
					["class" => "brand"]),
				["class" => "header"]);
			if ($this->HasItems)
				if (count($this->Items) > 0)
					yield Html::Items(
						function () {
							foreach ($this->Items as $item)
								yield $this->CreateItem($item, 1);
						}
						,
						["class" => (isValid($this->ShowItemsScreenSize) ? $this->ShowItemsScreenSize . '-show' : '') . ' ' . (isValid($this->HideItemsScreenSize) ? $this->HideItemsScreenSize . '-hide' : '')]
					);
			if ($this->HasOthers) {
				$defaultButtons = [];
				if ($this->AllowDefaultButtons) {
					module("SearchForm");
					module("TemplateButton");
					module("UserMenu");
					$defaultButtons[] = new SearchForm();
					$defaultButtons[] = new TemplateButton();
					if (\_::$Config->AllowSigning) $defaultButtons[] = new UserMenu();
				}
				yield Html::Division([
						...($this->Content? (is_array($this->Content)?$this->Content:[$this->Content]) : []),
						...($defaultButtons? $defaultButtons : [])
					],
					["class" => "other {$this->ShowOthersScreenSize}-show {$this->HideOthersScreenSize}-hide"]
				);
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

	public function AfterHandle()
	{
		return parent::AfterHandle() . ($this->AllowFixed ? "<div class='{$this->Name}-margin'></div>" : "");
	}
}
?>