<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
use MiMFa\Library\Convert;
use MiMFa\Library\Style;
class Form extends Module{
	public $Capturable = true;
	public $Path = null;
	public $Action = null;
	public $Image = null;
	public $Title = "Form";
	public $SubmitLabel = "Submit";
	public $ResetLabel = "Reset";
	public $CancelLabel = null;
	public $CancelPath = "/";
	public $BackLabel = "Back to Home";
	public $BackPath = "/";
	public $Method = "POST";
	public $EncType="multipart/form-data";
	public $Timeout = 60000;
	public $SuccessPath = null;
	public $ErrorPath = null;
	public $HasDecoration = true;
	public $AllowHeader = true;
	public $AllowContent = true;
	public $AllowFooter = true;

	/**
     * Create the module
     */
	public function __construct($title = "Form", $action =  null, $method = "POST", mixed $children = [], $description = null){
        parent::__construct();
		$this->Set($title, $action, $method, $children, $description);
    }
	/**
     * Set the main properties of module
	 */
	public function Set($title = null, $action =  null, $method = "POST", mixed $children = [], $description = null){
		$this->Title = $title;
		$this->Description = $description;
		$this->Action = $action;
		$this->Method = $method;
		if(is_array($children) && count($children)>0 && !array_key_exists(0,$children))
			$this->Children = iteration($children, function($k,$v){ return HTML::Field(null, $k, $v); });
		else $this->Children = $children;
		return $this;
    }

	public function GetStyle(){
		if($this->HasDecoration) return parent::GetStyle().HTML::Style("
			.{$this->Name} .rack{
				align-items: center;
			}
			.{$this->Name} form{
				padding-top: var(--Size-0);
				padding-bottom: var(--Size-0);
			}
			.{$this->Name} .header{
				position: sticky;
				top: 0px;
				bottom: 0px;
				margin-top: 5vmin;
				margin-bottom: 5vmin;
				padding: var(--Size-1);
			}
			.{$this->Name} .header :is(.image, .image:before) {
				color: var(--ForeColor-3);
				font-size: 300%;
				margin: 0px 5%;
            	width: 90%;
				padding: var(--Size-1);
				height: auto;
				text-align: center;
			}
			.{$this->Name} .header :not(i):is(.image, .image:before) {
				background-size: cover;
				background-repeat: no-repeat;
				border-radius: 100%;
				aspect-ratio: 1;
			}

			.{$this->Name} .content{
				background-color: var(--BackColor-0);
				color: var(--ForeColor-0);
			}

			.{$this->Name} .button{
				background-color: inherit;
				color: inherit;
				padding: calc(var(--Size-0) / 2) var(--Size-1);
			}
			.{$this->Name} .submitbutton {
				background-color: var(--ForeColor-2);
				color: var(--BackColor-2);
			}
			.{$this->Name} .submitbutton:hover {
				background-color: var(--ForeColor-4);
				color: var(--BackColor-4);
			}

			.{$this->Name} .group {
				padding: var(--Size-0);
			}

			.{$this->Name} .field {
				display: flex;
				padding: 0px var(--Size-0) var(--Size-0);
			}

			.{$this->Name} .field .title{
				font-size: 90%;
				opacity: 80%;
				min-width: fit-content;
				display: inline-flex;
				position: relative;
				text-align: initial;
				vertical-align: top;
				margin-top: var(--Size-0);
				margin-bottom: 0px;
				padding: 0px;
				z-index: 1;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field .prepend{
				display: inline-flex;
				margin: 0px;
				width: fit-content;
				padding: var(--Size-0);
				height: 100%;
				border: none;
				background-color: var(--BackColor-1);
				color: var(--ForeColor-1);
				border-top: none;
				border-bottom: 1px solid var(--ForeColor-4);
			}
			.{$this->Name} .field .input {
				font-size: 125%;
				display: inline-flex;
				width: 100%;
				max-width: -webkit-fill-available;
				border-left: 0px;
				padding: 0px var(--Size-0);
				border: none;
				background-color: var(--BackColor-1);
				color: var(--ForeColor-1);
				border-top: none;
				border-bottom: 1px solid var(--ForeColor-4);
			}
			.{$this->Name} .field .input::placeholder {
				color: #888;
				font-weight: bold;
				font-size: 0.9rem;
			}
			.{$this->Name} .field .input:focus {
				box-shadow: none;
			}
			.{$this->Name} .field label.description{
				font-size: 75%;
				opacity: 0.5;
				text-align: initial;
				display: block;
				padding-top: 5px;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field:hover label.description{
				opacity: 0.75;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
		");
		else return parent::GetStyle();
	}

	public function Get(){
		$name = $this->Name."_Form";
		$src = $this->Action??$this->Path??\_::$PATH;
		if(isValid($src))
			if($this->HasDecoration)
				return HTML::Container(
					HTML::Rack(
						($this->AllowHeader?HTML::LargeSlot(
							HTML::Media(null, $this->Image,["class"=>"image"]).
							$this->GetHeader().
							$this->GetTitle().
							$this->GetDescription().
							(isValid($this->BackLabel)? HTML::Link($this->BackLabel, $this->BackPath):"")
						,["class"=>"header"]):"").
						HTML::LargeSlot(
							HTML::Form(
								($this->AllowContent?$this->GetContent():"").
								Convert::ToString($this->GetFields()).
								HTML::Rack(Convert::ToString($this->GetButtons()),[ "class"=>"group"])
							,$src, ["id"=>$name, "name"=>$name, "enctype"=>$this->EncType, "method"=>$this->Method]).
							($this->AllowFooter?$this->GetFooter():"")
						,["class"=>"content"])
					)
					,["class"=>"page"]);
            else
                return
					($this->AllowHeader?
						HTML::Media(null, $this->Image,["class"=>"image"]).
						$this->GetHeader().
						$this->GetTitle().
						$this->GetDescription().
						(isValid($this->BackLabel)? HTML::Link($this->BackLabel, $this->BackPath):"")
					:"").
					HTML::Form(
                        ($this->AllowContent?$this->GetContent():"").
						Convert::ToString($this->GetFields()).
						HTML::Rack(Convert::ToString($this->GetButtons()),[ "class"=>"group"])
                    ,$src, ["id"=>$name, "name"=>$name, "enctype"=>$this->EncType, "method"=>$this->Method]).
                    ($this->AllowFooter?$this->GetFooter():"");
        return null;
    }
	public function GetHeader(){

    }
	public function GetFields(){

    }
	public function GetButtons(){
		yield (isValid($this->SubmitLabel)?HTML::SubmitButton($this->SubmitLabel, ["name"=>"submit", "class"=>"col-md"]):"");
		yield (isValid($this->ResetLabel)?HTML::ResetButton($this->ResetLabel, ["name"=>"reset", "class"=>"col-md-4"]):"");
		yield (isValid($this->CancelLabel)?HTML::Button($this->CancelLabel,$this->CancelPath, ["name"=>"cancel", "class"=>"col-lg-3"]):"");
    }
	public function GetFooter(){

    }

	public function GetScript(){
		return parent::GetScript().HTML::Script("
			$(function () {
				handleForm('.{$this->Name} form',
					function (data, selector)  {//success
						if (data.includes('result success')) {
							$(`.{$this->Name} form .result`).remove();
							$(`.{$this->Name} form`).append(data);
							".(isValid($this->SuccessPath)? "load(`{$this->SuccessPath}`);":"")."
						}
						else {
							$(`.{$this->Name} form .result`).remove();
							$(`.{$this->Name} form`).append(data);
            				".(isValid($this->ErrorPath)? "load(`{$this->ErrorPath}`);":"")."
						}
					},
					{ timeout: {$this->Timeout} }
				);
				$(`.{$this->Name} :is(input, select, textarea)`).on('focus', function () {
					$(this).parent().find(`.{$this->Name} .input-group .text`).css('border-color', 'var(--ForeColor-2)');
				});
				$(`.{$this->Name} :is(input, select, textarea)`).on('blur', function () {
					$(this).parent().find(`.{$this->Name} .input-group .text`).css('border-color', 'var(--ForeColor-2)');
				});
			});
		");
    }

	public function Action(){
		echo $this->GetAction();
    }
	public function GetAction(){
		$_req = $_REQUEST;
		switch(strtolower($this->Method)){
            case "get":
				$_req = $_GET;
			break;
            case "post":
				$_req = $_POST;
			break;
        }
		try {
			if(count($_req) > 0)
                return HTML::Success("The form submitted successfully!",["class"=>"page"]);
			else return HTML::Warning("There a problem is occured!");
		} catch(\Exception $ex) { return HTML::Error($ex); }
    }
}
?>