<?php namespace MiMFa\Module;
use \MiMFa\Library\DataBase;
/**
 * Automatic paging for item iterations or even a direct query to database
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/mimfa/aseqbase
 *@link https://github.com/mimfa/aseqbase/wiki/Modules See the Documentation
 */
class Navigation extends Module{
	/**
	 * The source of items
	 * @var mixed
	 */
	public $Items = array();
	/**
	 * A simple select query to fetch data
	 * @var mixed
	 */
	public $Query = null;
	/**
     * The number of all items
     * Default: 12
     * @var int
     */
	public $Count = -1;
	/**
     * The current page, showed
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

	public function __construct($itemsOrQuery = null, $count = null){
		parent::__construct();
		$query = GET($_REQUEST)??array();
		$this->Page = (int)getValid($query,$this->PageRequest,-1);
		$this->Limit = (int)getValid($query,$this->LimitRequest, -12);
		$this->Count = (int)getValid($query,$this->CountRequest, -1);

		$this->SetItems($itemsOrQuery, $count);
	}

	/**
     * Set Items iterator or a direct SQL query
     * @param iterable|array|string Items iterator or a direct SQL query
     * @param null|int The number of all items, set null to count automatically
	 * @return int|mixed
	 */
	public function SetItems($itemsOrQuery, $count = null){
		if($this->Page <= 0) $this->Page = 1;
		if($this->Limit <= 0) $this->Limit = 12;
		if(is_string($itemsOrQuery)){
			$this->Items = null;
            $this->Query = trim($itemsOrQuery,"; \r\n\t\f\v\x00");
			if($this->LiveCount || $this->Count <= 0)
				if(isValid($count)) $this->Count = $count;
				else if($this->AllowCount) $this->Count = count(DataBase::Select($this->Query));
				else $this->Count = 12;
        }
		else {
			$this->Query = null;
			$this->Items = $itemsOrQuery;
			if($this->LiveCount || $this->Count <= 0)
                if(isValid($count)) $this->Count = $count;
                else if($this->AllowCount) $this->Count = count($this->Items);
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
		if(isValid($iterator??$this->Items)) return array_slice($iterator??$this->Items, $this->GetFromItem(), $this->GetLimit());
		else return DataBase::Select($this->Query." LIMIT ".$this->GetFromItem().", ".$this->GetLimit());
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

	public function EchoStyle(){
		parent::EchoStyle(); ?>
		<style>
			.<?php echo $this->Name; ?>{
				padding: 10px;
				margin: 0px;
				width: 100%;
				text-align: center;
			}
			.<?php echo $this->Name; ?> a,.<?php echo $this->Name; ?> a:visited{
			}
			.<?php echo $this->Name; ?> .item{
				font-size: var(--Size-2);
				font-weight: bold;
				padding: 0px 5px;
				margin: 5px;
			}
			.<?php echo $this->Name; ?> .item.active{
				color: <?php echo \_::$TEMPLATE->ForeColor(1)."88";?>;
			}

			.<?php echo $this->Name; ?> .item.next,
			.<?php echo $this->Name; ?> .item.back
			{
				font-size: var(--Size-1);
				font-weight: normal;
			}
		</style>
		<?php
	}

	public function Echo(){
		parent::Echo();
		$url = \_::$PATH."?";
		$fromP = $this->GetFromPage();
		$toP = $this->GetToPage();
		$query = GET($_REQUEST)??array();
		$query[$this->CountRequest] = $this->Count."";
		if(isValid($this->BackLink)){?>
			<a href="<?php echo $this->BackLink; ?>" class="item back">
				<i class="fa fa-arrow-left item"></i>
			</a>
		<?php 
		} elseif($this->Page > 1){
			if($this->AllowFirst && $fromP > 1) { $query[$this->PageRequest] = 1; ?>
				<a href="<?php echo $url.http_build_query($query); ?>" class="item first">
					1
				</a>
			<?php } 
			$query[$this->PageRequest] = $this->Page-1;
			?>
				<a href="<?php echo $url.http_build_query($query); ?>" class="item back">
					<i class="fa fa-arrow-left"></i>
				</a>
			<?php 
		}
		if($this->Numbers > 1){
			for($i = $fromP; $i <= $toP; $i++) {
				if($i == $this->Page) {
					?>
					<span class="item active">
						<?php echo $i; ?>
					</span>
					<?php 
				} else {
					$query[$this->PageRequest] = $i."";
					?>
					<a href="<?php echo $url.http_build_query($query); ?>" class="item">
						<?php echo $i; ?>
					</a>
					<?php 
				}
			}
		}
		if(isValid($this->NextLink)){?>
				<a href="<?php echo $this->NextLink; ?>" class="item next">
					<i class="fa fa-arrow-right"></i>
				</a>
			<?php
		} elseif($this->Page*$this->Limit < $this->Count){
			$query[$this->PageRequest] = $this->Page+1;
			?>
				<a href="<?php echo $url.http_build_query($query); ?>" class="item next">
					<i class="fa fa-arrow-right"></i>
				</a>
			<?php if($this->AllowLast && $toP < $this->Numbers) { $query[$this->PageRequest] = $this->Numbers; ?>
				<a href="<?php echo $url.http_build_query($query); ?>" class="item last">
					<?php echo $this->Numbers; ?>
				</a>
			<?php }
		}
	}
}
?>