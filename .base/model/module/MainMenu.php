<?php
namespace MiMFa\Module;
MODULE("SearchForm");
MODULE("UserMenu");
MODULE("TemplateButton");
use MiMFa\Library\HTML;
use MiMFa\Library\Style;
use MiMFa\Library\Convert;
class MainMenu extends Module{
	public $Capturable = true;
	public $Tag = "nav";
	public $Class = "row";
	public $Image = null;
	public $Items = null;
	public $Shortcuts = null;
	public SearchForm|null $SearchForm = null;
	public UserMenu|null $UserMenu = null;
	public TemplateButton|null $TemplateButton = null;
	public $HasBranding = true;
	public $HasItems = true;
	public $HasOthers = true;
	public $AllowFixed = false;
	public $HideItemsScreenSize = 'md';
	public $ShowItemsScreenSize = null;
	public $HideOthersScreenSize = 'md';
	public $ShowOthersScreenSize = null;

	public function __construct(){
        parent::__construct();
		$this->SearchForm = new SearchForm();
		if(\_::$CONFIG->AllowSigning) $this->UserMenu = new UserMenu();
		$this->TemplateButton = new TemplateButton();
    }

	public function GetStyle(){
		$rtl = (\MiMFa\Library\Translate::$Direction??\_::$CONFIG->DefaultDirection) == "RTL";
		return parent::GetStyle().HTML::Style("
			.{$this->Name} {
				margin: 0;
				padding: 0;
				display: flex;
				overflow: hidden;
				background-color: ".\_::$TEMPLATE->BackColor(2).($this->AllowFixed?"ee":"").";
				color: var(--ForeColor-2);
				".($this->AllowFixed?"
				position:fixed;
				top:0;
				left:0;
				right:0;
				z-index: 999;
            	":"")."
				box-shadow: var(--Shadow-2);
			}
			".($this->AllowFixed?"
			.{$this->Name}-margin{
				height: 75px;
				background: transparent;
			}
			":"")."

			.{$this->Name} .header{
				margin: 0;
				width: fit-content;
				padding: 5px 10px;
				display: inline-table;
			}
			.{$this->Name} :is(.header, .header a, .header a:visited){
				color: var(--ForeColor-2);
			}
			.{$this->Name} .header .title{
				font-size: var(--Size-2);
				padding: 0px 10px;
				".(isValid($this->Description)?"line-height: var(--Size-2);":"")."
			}
			.{$this->Name} .header .description{
				font-size: var(--Size-0);
				padding: 0px 10px;
			}
			.{$this->Name} .header .image{
				background-position: center;
				background-repeat: no-repeat;
				background-size: 80% auto;
				background-color: transparent;
				width: 50px;
				display: table-cell;
				font-size: var(--Size-0);
			}

			.{$this->Name} :is(li, li a, li a:visited){
				border: none;
			}

			.{$this->Name} li .fa{
				font-size: var(--Size-2);
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
				display: inline-table;
				".($this->SearchForm != null?"
				min-width: fit-content;
				max-width: 70% !important;
				":"")."
			}

			.{$this->Name} ul:not(.sub-items)>li {
				background-color: transparent;
				color: inherit;
				display: inline-block;
			}
			.{$this->Name} ul:not(.sub-items)>li.active{
				border-top: var(--Border-2) var(--BackColor-2);
				border-radius: var(--Radius-2) var(--Radius-2) 0px 0px;
				color: ".\_::$TEMPLATE->ForeColor(0)."88;
				background-color: var(--BackColor-0);
				box-shadow: var(--Shadow-2);
			}
			.{$this->Name} ul:not(.sub-items)>li>:is(.button, .button:visited){
				background-color: transparent;
				color: var(--ForeColor-2);
				border: none;
				font-size: inherit;
				border-radius: none;
				text-decoration: none;
				padding: var(--Size-0) var(--Size-1);
				display: block;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} ul:not(.sub-items)>li:hover>:is(.button, .button:visited) {
				font-weight: bold;
				background-color: var(--BackColor-2);
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} ul:not(.sub-items)>li.active>:is(.button, .button:visited){
				color: ".\_::$TEMPLATE->ForeColor(0)."88;
			}
			.{$this->Name} ul:not(.sub-items)>li.active:hover>:is(.button, .button:visited){
				color: var(--ForeColor-0);
			}
			.{$this->Name} ul:not(.sub-items)>li.dropdown:hover>:is(.button, .button:visited) {
				color: var(--ForeColor-1);
				background-color: var(--BackColor-1);
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} ul:not(.sub-items)>li.dropdown:hover>ul.sub-items {
				display: block;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}

			.{$this->Name} ul.sub-items {
				display: none;
				position: fixed;
				color: var(--ForeColor-2);
				background-color: var(--BackColor-1);
				min-width: 160px;
				max-width: 90vw;
				max-height: 70vh;
				padding: 0px;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
				overflow-x: hidden;
				overflow-y: auto;
				z-index: 99;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} ul.sub-items .sub-items {
				display: flex;
				position: relative;
				background-color: #8881;
				font-size: 80%;
				min-width: calc(5 * var(--Size-5));
				max-width: 500px;
				width: 70vw;
				max-height: 60vh;
				padding: 0px;
				padding-".($rtl?"right":"left").": var(--Size-5);
				padding-bottom: calc(var(--Size-0) / 2);
				box-shadow: var(--Shadow-1);
				overflow-x: hidden;
				overflow-y: auto;
				flex-wrap: wrap;
				flex-direction: row;
				align-content: stretch;
				justify-content: flex-start;
				align-items: stretch;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} ul.sub-items .sub-items li :is(.button, .button:visited) {
				padding: calc(var(--Size-0) / 2) var(--Size-1);
				background: transparent;
				border-color: transparent;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} ul.sub-items>li {
				font-size: 80%;
				display: block;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} ul.sub-items>li>:is(.button, .button:visited){
				color: var(--ForeColor-1);
				text-decoration: none;
				padding: calc(var(--Size-1) / 2) var(--Size-1);
				display: block;
				text-align: start;
			}
			.{$this->Name} ul.sub-items>li.dropdown{
				display: block;
				border-bottom: var(--Border-1) transparent;
			}
			.{$this->Name} ul.sub-items>li.dropdown.active{
				box-shadow: var(--Shadow-2);
			}
			.{$this->Name} ul.sub-items>li.dropdown.active>:is(.button, .button:visited){
				font-weight: bold;
			}
			.{$this->Name} ul.sub-items>li.dropdown:hover{
				border-bottom: var(--Border-1) var(--BackColor-5);
				box-shadow: var(--Shadow-1);
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} ul.sub-items>li.dropdown:hover>:is(.button, .button:visited){
				font-weight: bold;
				color: #8888;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} ul.sub-items>li.dropdown>:is(.button, .button:visited):hover{
				font-weight: bold;
				background-color: var(--BackColor-5);
				color: var(--ForeColor-5);
				border: none;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} ul.sub-items>li:not(.dropdown).active>:is(.button, .button:visited){
				font-weight: bold;
				box-shadow: var(--Shadow-2);
			}
			.{$this->Name} ul.sub-items>li:not(.dropdown):hover>:is(.button, .button:visited){
				font-weight: bold;
				background-color: var(--BackColor-5);
				color: var(--ForeColor-5);
				border: none;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}

		".($this->HasOthers?"
			.{$this->Name} .other{
				text-align: end;
				width: fit-content;
				position: absolute;
				clear: both;
				display: flex;
				align-items: center;
				".($rtl?"left":"right").": var(--Size-2);
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
				color: var(--ForeColor-2);
				background-color: var(--BackColor-2);
				border: var(--Border-1) var(--BackColor-5);
				border-radius: var(--Radius-3);
				box-shadow: var(--Shadow-1);
				overflow: hidden;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} form:is(:hover, :active, :focus) {
				font-weight: bold;
				color: var(--ForeColor-1);
				background-color: var(--BackColor-1);
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} form :not(html,head,body,style,script,link,meta,title){
				padding: 0px;
				margin: 0px;
				display: inline-block;
				color: var(--ForeColor-2);
				background-color: transparent;
				outline: none;
				border: none;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} form:is(:hover, :active, :focus) :not(html,head,body,style,script,link,meta,title) {
				font-weight: bold;
				outline: none;
				border: none;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} form:is(:hover, :active, :focus) :is(button, button :not(html,head,body,style,script,link,meta,title))  {
				color: var(--BackColor-2);
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} form input[type='search']{
				max-width: 100%;
				width: 80%;
				width: 0px;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} form:is(:hover, :active, :focus) input[type='search'], .{$this->Name} form input[type='search']:is(:hover, :active, :focus){
				color: var(--ForeColor-1);
				width: 200px;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}"
			:"")
		);
	}

	public function Get(){
		return Convert::ToString(function(){
			if($this->HasBranding)
				yield HTML::Rack(
					(isValid($this->Image)? HTML::Media($this->Image,['class'=>'image']):"").
					HTML::Division(
						(isValid($this->Description)? HTML::Division(__($this->Description,true,false),['class'=>'description']):"").
						(isValid($this->Title)? HTML::Division(__($this->Title,true,false),['class'=>'title']):"")
					)
				,["class"=>"header"]);
			if($this->HasItems)
				if(count($this->Items) > 0)
					yield HTML::Items(function(){
						foreach($this->Items as $item)
							yield $this->CreateItem($item, 1);
					}
					,["class"=>(isValid($this->ShowItemsScreenSize)?$this->ShowItemsScreenSize.'-show':'').' '.(isValid($this->HideItemsScreenSize)?$this->HideItemsScreenSize.'-hide':'')]);
			if($this->HasOthers){
				yield "<div class='other ".(isValid($this->ShowOthersScreenSize)?$this->ShowOthersScreenSize.'-show':'').' '.(isValid($this->HideOthersScreenSize)?$this->HideOthersScreenSize.'-hide':'')."'>";
				if($this->SearchForm != null) yield $this->SearchForm->Capture();
				if($this->UserMenu != null) yield $this->UserMenu->Capture();
				if($this->TemplateButton != null) yield $this->TemplateButton->Capture();
				if(isValid($this->Content)) yield $this->Content;
				yield "</div>";
            }
        });
	}

	protected function CreateItem($item, $ind = 1){
		if(!getAccess(getValid($item,"Access",\_::$CONFIG->VisitAccess))) return null;
		$path = getBetween($item,"Path","Link");
		$act = endsWith($_SERVER['REQUEST_URI'],$path)?'active':'';
		$ind++;
		$count = isValid($item, "Items")?count($item["Items"]):0;
		return HTML::Item(
			HTML::Button(
				__(getBetween($item,"Title","Name"),true,false),
				$path, getValid($item,"Attributes")
			).
			($count > 0?
				HTML::Items(function() use($item, $ind){
                    foreach($item["Items"] as $itm)
                        yield $this->CreateItem($itm, $ind);
                }
				,["class"=>"sub-items sub-items-$ind"])
			:"")
		,["class"=>$count > 0?"dropdown $act":$act]);
	}

	public function PostCapture(){
		return parent::PostCapture().($this->AllowFixed?"<div class='{$this->Name}-margin'></div>":"");
	}

	public function GetScript(){
		return parent::GetScript().HTML::Script("
			function ViewSideMenu(show){
				if(show === undefined) $('.{$this->Name}').toggleClass('active');
				else if(show) $('.{$this->Name}').addClass('active');
				else $('.{$this->Name}').removeClass('active');
			}
		");
	}
}
?>