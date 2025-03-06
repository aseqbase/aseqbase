<?php namespace MiMFa\Module;

use MiMFa\Library\Html;

class SearchForm extends Module{
	public $Path = null;
	public $SubmitLabel = "<i class='fa fa-search'></i>";
	public $PlaceHolder = "Search";
	public $QueryKey = "q";
	
	public function Get(){
		$src = $this->Path??\Req::$Host."/search";
		if(isValid($src))
			return parent::Get().Html::Form(
					Html::SearchInput($this->QueryKey, \Req::Get($this->QueryKey), ["placeholder"=>__($this->PlaceHolder)])
					."<button type='submit'>".__($this->SubmitLabel)."</button>"
				, $src, ["method"=>"get"]);
		return null;
	}
}
?>
