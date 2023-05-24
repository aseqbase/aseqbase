<?php namespace MiMFa\Module;
class SearchForm extends Module{
	public $Path = null;
	public $SubmitLabel = "<i class='fa fa-search'></i>";
	public $PlaceHolder = "Search";
	public $QueryKey = "q";
	
	public function Echo(){
		parent::Echo();
		$src = $this->Path??\_::$HOST."/search";
		if(isValid($src)):
			?>
				<form action="<?php echo $src; ?>" method="get">
					<input id="<?php echo $this->QueryKey; ?>" name="<?php echo $this->QueryKey; ?>" type="search" placeholder="<?php echo __($this->PlaceHolder);?>" value="<?php echo getValid($_GET,$this->QueryKey); ?>" />
					<button type="submit"><?php echo __($this->SubmitLabel);?></button>
				</form>
			<?php
		endif;
	}
}
?>
