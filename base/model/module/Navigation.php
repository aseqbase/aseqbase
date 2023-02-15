<?php namespace MiMFa\Module;
class Navigation extends Module{
	public $Items = array();
	public $Count = 12;
	public $Limit = 12;
	public $LimitRequest = "l";
	public $Page = 1;
	public $PageRequest = "p";
	public $Numbers = 1;
	public $Range = 7;
	public $BackLink = null;
	public $NextLink = null;
	public $Root = null;
	public $AllowCount = true;
	public $AllowLast = true;
	public $AllowFirst = true;
	
	public function __construct($items = null){
		parent::__construct();
		$query = GET($_REQUEST);
		$this->Page = isset($query[$this->PageRequest])? ((int)$query[$this->PageRequest]):(int)1;
		$this->Limit = isset($query[$this->LimitRequest])? ((int)$query[$this->LimitRequest]):(int)12;
		if($this->Page <= 0) $this->Page = 1;
		if($this->Limit <= 0) $this->Limit = 12;

		if(isValid($items)) {
			$this->Items = $items;
			$this->Count = count($this->Items);
		}
		$this->Numbers = ceil($this->Count / $this->Limit);
	}

	public function GetItems($iterator=null){
		return array_slice($iterator??$this->Items, $this->GetFromItem(), $this->GetLimit());
	}
	public function GetFromItem(){
		return min($this->Count, max(0, ($this->Page-1) * $this->Limit));
	}
	public function GetToItem(){
		return max(0, min($this->Count-1, $this->Page * $this->Limit));
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
		parent::EchoStyle();
		?>
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
		$query = GET($_REQUEST);
		?>
		<?php if(isValid($this->BackLink)){?>
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