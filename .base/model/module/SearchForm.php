<?php namespace MiMFa\Module;

use MiMFa\Library\HTML;

class SearchForm extends Module{
	public $Capturable = true;
	public $Path = null;
	public $SubmitLabel = "<i class='fa fa-search'></i>";
	public $PlaceHolder = "Search";
	public $QueryKey = "q";
	
	public function Get(){
		$src = $this->Path??\_::$HOST."/search";
		if(isValid($src))
			return parent::Get().HTML::Form(
					HTML::SearchInput($this->QueryKey, getValid($_GET,$this->QueryKey), ["id"=>$this->QueryKey, "placeholder"=>__($this->PlaceHolder)])
					."<button type='submit'>".__($this->SubmitLabel)."</button>"
				, $src, ["method"=>"get"]);
	}
}
?>
