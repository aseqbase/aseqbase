<?php namespace MiMFa\Module;

use MiMFa\Library\Struct;

class SearchForm extends Module{
    public $Tag = null;
	public $Path = "/search";
	public $SubmitLabel = "<i class='icon fa fa-search'></i>";
	public $PlaceHolder = "Search";
	public $QueryKey = "q";
	public $QueryValue = null;
    public $SearchAction = null;
    public $RealtimeAction = null;
    public $Printable = false;
	
	public function Get(){
		$src = $this->Path??"/search";
		return Struct::Form(
				parent::GetTitle().parent::GetDescription().
				Struct::SearchInput($this->QueryKey, receiveGet($this->QueryKey)??$this->QueryValue, ["placeholder"=>__($this->PlaceHolder),...($this->RealtimeAction?["onkeyup"=>$this->RealtimeAction]:[])])
				.(
					$this->SearchAction?Struct::Button($this->SubmitLabel, $this->SearchAction, ["class"=>"searchbutton"]):
					Struct::SubmitButton(value: $this->SubmitLabel, attributes:["class"=>"searchbutton"])
				).parent::GetContent()
			, $this->SearchAction??$src, ["method"=>"get"], $this->GetDefaultAttributes());
	}
}
?>
