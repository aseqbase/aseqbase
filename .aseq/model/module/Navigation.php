<?php
namespace MiMFa\Module;
use \MiMFa\Library\DataBase;
use \MiMFa\Library\Html;
use \MiMFa\Library\Style;
use \MiMFa\Library\Translate;
use \MiMFa\Library\Convert;
/**
 * Automatic paging for item iterations or even a direct query to database
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Navigation extends Module{
	/**
     * The source of items
     * @var mixed
     */
	public $Items = array();
	/**
     * The source of default items
     * @var mixed
     */
	public $DefaultItems = array();
	/**
     * A simple select query to fetch data
     * @var mixed
     */
	public $Query = null;
	/**
     * Select query parameters to fetch data
     * @var mixed
     */
	public $QueryParameters = [];
	/**
     * The number of all navigable items
     * Default: -1
     * @var int
     */
	public $Count = -1;
	/**
     * The number of all navigable items request key
     * Default: c
     * @var int
     */
	public $CountRequest = "c";
	/**
     * The number of shown items in each page
     * Default: 12
     * @var int
     */
	public $Limit = 12;
	/**
     * The requested key for showing items in each page
     * Default: l
     * @var int
     */
	public $LimitRequest = "l";
	public $MinLimit = 1;
	public $MaxLimit = 580;
	/**
     * The current page, showed
     * @var mixed
     */
	public $Page = 1;
	/**
     * The requested key for page number
     * Default: p
     * @var int
     */
	public $PageRequest = "p";
	/**
     * The numbers of all pages Count / Limit
     * @var mixed
     */
	public $Numbers = 1;
	/**
     * Page numbers range to show as a navigator
     * @var mixed
     */
	public $Range = 7;
	/**
     * A custom link for the showed button as the previous page
     * @var mixed
     */
	public $BackLink = null;
	/**
     * A custom link for the showed button as the next page
     * @var mixed
     */
	public $NextLink = null;
	/**
     * Allow to compute count of items in each turn
     * @var mixed
     */
	public $LiveCount = false;
	/**
     * Allow to compute count of items
     * @var mixed
     */
	public $AllowCount = true;
	/**
     * Show the last page number in the navbar
     * @var mixed
     */
	public $AllowLast = true;
	/**
     * Show the first page number in the navbar
     * @var mixed
     */
	public $AllowFirst = true;

	public function __construct($itemsOrQuery = null, $count = null, $queryParameters = null, $defaultItems = null){
		parent::__construct();
		$query = \Req::ReceiveGet()??array();
		$r = "_".getId(false); 
		$this->PageRequest .= $r; 
		$this->LimitRequest .= $r; 
		$this->CountRequest .= $r; 
		$this->Page = (int)takeValid($query,$this->PageRequest,-1);
		$this->Limit = (int)takeValid($query,$this->LimitRequest, -12);
		$this->Count = (int)takeValid($query,$this->CountRequest, -1);
		$this->SetItems($itemsOrQuery, $count, $queryParameters, $defaultItems);
	}

	/**
     * Set Items iterator or a direct SQL query
     * @param iterable|array|string Items iterator or a direct SQL query
     * @param null|int The number of all items, set null to count automatically
     * @return int|mixed
     */
	public function SetItems($itemsOrQuery, $count = null, $queryParameters = null, $defaultItems = null){
		if($this->Page <= 0) $this->Page = 1;
		if($this->Limit <= 0) $this->Limit = 12;
		$this->QueryParameters = $queryParameters??$this->QueryParameters;
		$this->DefaultItems = $defaultItems??$this->DefaultItems;
		if(is_string($itemsOrQuery)){
			$this->Items = null;
            $this->Query = trim($itemsOrQuery,"; \r\n\t\f\v\x00");
			if($this->LiveCount || $this->Count <= 0)
				if(isValid($count)) $this->Count = $count;
				elseif($this->AllowCount)
                    $this->Count = count(between(\_::$Back->DataBase->FetchRowsExecute($this->Query, $this->QueryParameters), $this->DefaultItems));
				else $this->Count = 12;
        }
		else {
			$this->Query = null;
			$this->Items = $itemsOrQuery;
			if($this->LiveCount || $this->Count <= 0)
                if(isValid($count)) $this->Count = $count;
                elseif($this->AllowCount) $this->Count = count(Convert::ToItems(between($this->Items,$this->DefaultItems)));
				else $this->Count = 12;
        }
		$this->Numbers = ceil($this->Count / $this->Limit);
		return $this->Count;
	}
	/**
     * Get curent page items
     * @param iterable|array|null Set just if you want to change the default process
     * @return mixed
     */
	public function GetItems($iterator=null){
		if(isValid($iterator??$this->Items)) return array_slice(Convert::ToItems($iterator??$this->Items), $this->GetFromItem(), $this->GetLimit());
		else return between(\_::$Back->DataBase->FetchRowsExecute($this->Query." LIMIT ".$this->GetFromItem().", ".$this->GetLimit(),$this->QueryParameters),array_slice($this->DefaultItems, $this->GetFromItem(), $this->GetLimit()));
	}
	public function GetFromItem(){
		return min($this->Count, max(0, ($this->Page-1) * $this->Limit));
	}
	public function GetToItem(){
		return max(0, min($this->Count, $this->Page * $this->Limit));
	}
	public function GetLimit(){
		return $this->GetToItem() - $this->GetFromItem();
	}
	public function GetFromPage(){
		return max($this->Page-(int)($this->Range/2), 1);
	}
	public function GetToPage(){
		return min($this->GetFromPage() + $this->Range - 1 ,$this->Numbers);
	}

	public function GetStyle(){
		return parent::GetStyle().Html::Style("
			.{$this->Name}{
				padding: 10px;
				margin: 0px;
				width: 100%;
				text-align: center;
				align-items: center;
			}
			.{$this->Name} a,.{$this->Name} a:visited{
			}
			.{$this->Name} .contents{
				display: inline-block;
				width: fit-content;
			}
			".($this->MinLimit < $this->MaxLimit?"
			.{$this->Name} .rangepanel{
				display: flex;
				width: 100%;
				align-content: stretch;
				justify-content: space-between;
				align-items: center;
				flex-direction: row;
				flex-wrap: wrap;
			}
			.{$this->Name} .rangepanel .rangeinput{
				".($this->AllowCount?"min-width: 70%;max-width: 95%;":"width: 100%;")."
			}
			.{$this->Name} .rangepanel span{
				font-size: var(--size-0);
				padding: 0px 5px;
			}

			/*Chrome*/
			@media screen and (-webkit-min-device-pixel-ratio:0) {
				.{$this->Name} input[type='range'] {
					height: var(--size-1);
					overflow: hidden;
					-webkit-appearance: none;
					border-color: var(--back-color);
					background-color: var(--fore-color-inside);
					".Style::UniversalProperty("transition","var(--transition-1)")."
				}
				.{$this->Name} input[type='range']::-webkit-slider-runnable-track {
					height: 100%;
					-webkit-appearance: none;
					color: var(--back-color-outside);
					background-color: var(--back-color-inside);
				}
				.{$this->Name} input[type='range']::-webkit-slider-thumb {
					aspect-ratio: 1;
					-webkit-appearance: none;
					height: 100%;
					cursor: pointer;
					border-radius: var(--radius-5);
					border: var(--border-1) var(--fore-color-inside);
					background-color: var(--back-color-inside);
					box-shadow: ".(\_::$Back->Translate->Direction=="rtl"?"":"-")."100vw 0 0 calc(100vw - var(--size-1) / 2) var(--fore-color-inside);
					".Style::UniversalProperty("transition","var(--transition-1)")."
				}
				.{$this->Name} input[type='range']:hover::-webkit-slider-thumb {
					border-color: var(--fore-color-outside);
					background-color: var(--back-color-outside);
				}
			}
			/*FF*/
			.{$this->Name} input[type='range']::-moz-range-progress {
				background-color: var(--back-color-inside);
			}
			.{$this->Name} input[type='range']::-moz-range-track {
				background-color: var(--fore-color-inside);
			}
			/*IE*/
			.{$this->Name} input[type='range']::-ms-fill-lower {
				background-color: var(--back-color-inside);
			}
			.{$this->Name} input[type='range']::-ms-fill-upper {
				background-color: var(--fore-color-inside);
			}
			":"").
			"
			.{$this->Name} .item{
				font-size: var(--size-2);
				font-weight: bold;
				padding: 0px 5px;
				margin: 5px;
			}
			.{$this->Name} .item.active{
				color: var(--fore-color-inside);
			}

			.{$this->Name} :is(.item.next, .item.back){
				font-size: var(--size-1);
				font-weight: normal;
			}
		");
	}

	public function Get(){
		return Convert::ToString(function(){
			yield parent::Get();
			$url = \Req::$Path."?";
			$fromP = $this->GetFromPage();
			$toP = $this->GetToPage();
			$query = \Req::ReceiveGet()??array();
			if(isset($query[$this->CountRequest])) $query[$this->CountRequest] = $this->Count."";
			$right = \_::$Back->Translate->Direction=="rtl"?"left":"right";
			$left = \_::$Back->Translate->Direction=="rtl"?"right":"left";

			yield "<div class='contents'>";
				$maxLimit = $this->AllowCount?min($this->Count,$this->MaxLimit):$this->MaxLimit;
				if($this->MinLimit < $maxLimit)
					yield Html::Division(
						Html::RangeInput(null,$this->Limit, $this->MinLimit, $maxLimit, ["onchange"=>"load('/".\Req::$Direction."?".preg_replace("/\&{$this->LimitRequest}\=\d+/","",\Req::$Query??"")."&{$this->LimitRequest}='+this.value);"]).
						($this->AllowCount?Html::Span($this->Count):0)
					,["class"=>"rangepanel"]);

				if(isValid($this->BackLink)) yield Html::Link(Html::Icon("arrow-$left",null,["class"=>"item"]),$this->BackLink,["class"=>"item back"]);
				elseif($this->Page > 1){
					if($this->AllowFirst && $fromP > 1)
						yield Html::Link($query[$this->PageRequest] = 1,$url.http_build_query($query),["class"=>"item first"]);
					$query[$this->PageRequest] = $this->Page-1;
					yield Html::Link(Html::Icon("arrow-$left",null,["class"=>"item"]),$url.http_build_query($query),["class"=>"item back"]);
				}

				if($this->Numbers > 1)
					for($i = $fromP; $i <= $toP; $i++)
						if($i == $this->Page)
							yield Html::Span($i,null,["class"=>"item active"]);
						else {
							$query[$this->PageRequest] = $i."";
							yield Html::Link($i,$url.http_build_query($query),["class"=>"item"]);
						}

				if(isValid($this->NextLink)) yield Html::Link(Html::Icon("arrow-$right",null,["class"=>"item"]),$this->NextLink,["class"=>"item next"]);
				elseif($this->Page*$this->Limit < $this->Count){
					$query[$this->PageRequest] = $this->Page+1;
					yield Html::Link(Html::Icon("arrow-$right",null,["class"=>"item"]),$url.http_build_query($query),["class"=>"item next"]);
					if($this->AllowLast && $toP < $this->Numbers)
						yield Html::Link($query[$this->PageRequest] = $this->Numbers,$url.http_build_query($query),["class"=>"item last"]);
				}
			yield "</div>";
        });
	}
}
?>