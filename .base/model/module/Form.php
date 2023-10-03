<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
use MiMFa\Library\Convert;
use MiMFa\Library\Style;
class Form extends Module{
	public $Capturable = true;
	public $Template = null;
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
	public $ResponseView = null;
	public $AllowHeader = true;
	public $AllowContent = true;
	public $AllowFooter = true;

	public $FieldsForeColor = "var(--ForeColor-1)";
	public $FieldsBackColor = "var(--BackColor-1)";
	public $FieldsBorderColor = "var(--ForeColor-4)";
	public $FieldsHeight = "auto";
	public $FieldsWidth = "100%";
	public $FieldsMinHeight = "10px";
	public $FieldsMinWidth = "auto";
	public $FieldsMaxHeight = "25vmin";
	public $FieldsMaxWidth = "100vw";

	/**
     * Create the module
     */
	public function __construct($title = "Form", $action =  null, $method = "POST", mixed $children = [], $description = null){
        parent::__construct();
		$this->Set($title, $action, $method, $children, $description);
		if(!is_null($this->ResponseView)){
            unset($_GET[\_::$CONFIG->ViewHandlerKey]);
            unset($_REQUEST[\_::$CONFIG->ViewHandlerKey]);
        }
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
		if($this->HasDecoration){
			$style = parent::GetStyle().HTML::Style("
				.{$this->Name}{
					max-width: 100%;
				}
				.{$this->Name} .rack {
					align-items: center;
				}
				.{$this->Name} form {
					padding-top: var(--Size-0);
					padding-bottom: var(--Size-0);
				}
				.{$this->Name} .header {
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

				.{$this->Name} .content {
					background-color: var(--BackColor-0);
					color: var(--ForeColor-0);
				}

				.{$this->Name} .content .title {
					white-space: nowrap;
				}

				.{$this->Name} .button {
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
			");
			switch (strtolower($this->Template??"")) {
				case 'v':
				case 'vertical':
					return $style . $this->GetFieldsVerticalStyle();
				case 'h':
				case 'horizontal':
					return $style . $this->GetFieldsHorizontalStyle();
				case 'b':
				case 'both':
					return $style . $this->GetFieldsBothStyle();
				case 's':
				case 'special':
				case 't':
				case 'table':
					return $style . $this->GetFieldsTableStyle();
				default:
					return $style . $this->GetFieldsDefaultStyle();
			}
		}
		else return parent::GetStyle();
	}
	public function GetFieldsDefaultStyle(){
		return HTML::Style("
				.{$this->Name} .field {
					".Style::DoProperty("min-width",$this->FieldsMinWidth)."
					".Style::DoProperty("min-height", $this->FieldsMinHeight)."
					".Style::DoProperty("max-width", $this->FieldsMaxWidth)."
					".Style::DoProperty("max-height", $this->FieldsMaxHeight)."
					".Style::DoProperty("width", $this->FieldsWidth)."
					".Style::DoProperty("height", $this->FieldsHeight)."
					display: flex;
					padding: 0px var(--Size-0) var(--Size-0);
					padding: 3px 0px;
				}

				.{$this->Name} .field .title{
					font-size: 90%;
					opacity: 80%;
					min-width: fit-content;
					display: inline-flex;
					position: relative;
					text-align: initial;
					vertical-align: top;
					padding: 0px var(--Size-0);
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
					".Style::DoProperty("color", $this->FieldsForeColor)."
					".Style::DoProperty("background-color", $this->FieldsBackColor)."
					font-size: 125%;
					display: inline-flex;
					width: 100%;
					max-width: -webkit-fill-available;
					padding-top: 0px;
					padding-bottom: 0px;
					border: none;
					border-bottom: var(--Border-1);
					border-color: transparent;
					border-radius: var(--Radius-0);
					".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
				}
				.{$this->Name} .field .input::placeholder {
					color: #888;
					font-weight: bold;
					font-size: 0.9rem;
				}
				.{$this->Name} .field .input:focus {
					box-shadow: none;
					".Style::DoProperty("border-color", $this->FieldsBorderColor)."
					".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
				}
				.{$this->Name} .field label.description{
					font-size: 75%;
					line-height: 100%;
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
    }
	public function GetFieldsBothStyle(){
		return HTML::Style("
			.{$this->Name} .field{
				".Style::DoProperty("min-width",$this->FieldsMinWidth)."
				".Style::DoProperty("min-height", $this->FieldsMinHeight)."
				".Style::DoProperty("max-width", $this->FieldsMaxWidth)."
				".Style::DoProperty("max-height", $this->FieldsMaxHeight)."
				".Style::DoProperty("width", $this->FieldsWidth)."
				".Style::DoProperty("height", $this->FieldsHeight)."
				font-size: var(--Size-1);
				text-align: start;
				display: table-row;
			}
			.{$this->Name} .field label.title{
				width: fit-content;
				display: table-cell;
				position: relative;
				text-align: initial;
				vertical-align: top;
				margin: 5px;
				padding: 5px var(--Size-0);
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field .input{
				".Style::DoProperty("color", $this->FieldsForeColor)."
				".Style::DoProperty("background-color", $this->FieldsBackColor)."
				display: table-cell;
				font-size: 125%;
				width: 100%;
				border: none;
				border-bottom: var(--Border-1);
				".Style::DoProperty("border-color", $this->FieldsBorderColor)."
				border-radius: var(--Radius-0);
				margin: 5px;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field label.description{
				text-align: initial;
				display: block;
				font-size: 75%;
				line-height: 100%;
				padding: 5px 2px;
				opacity: 0.5;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field:hover .input{
				".Style::DoProperty("outline-color", $this->FieldsBorderColor)."
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field:hover label.description{
				opacity: 0.75;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
		");
	}
	public function GetFieldsHorizontalStyle(){
		return HTML::Style("
			.{$this->Name} .field{
				".Style::DoProperty("min-width",$this->FieldsMinWidth)."
				".Style::DoProperty("min-height", $this->FieldsMinHeight)."
				".Style::DoProperty("max-width", $this->FieldsMaxWidth)."
				".Style::DoProperty("max-height", $this->FieldsMaxHeight)."
				".Style::DoProperty("width", $this->FieldsWidth)."
				".Style::DoProperty("height", $this->FieldsHeight)."
				font-size: var(--Size-1);
				text-align: start;
				display: table-row;
				padding: 3px 0px;
			}
			.{$this->Name} .field label.title{
				width: fit-content;
				display: table-cell;
				position: relative;
				text-align: initial;
				vertical-align: top;
				margin-right: -1px;
				padding: 3px var(--Size-1);
				border-radius: 3px 0px 0px 3px;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field .input{
				".Style::DoProperty("color", $this->FieldsForeColor)."
				".Style::DoProperty("background-color", $this->FieldsBackColor)."
				display: table-cell;
				font-size: 125%;
				width: 100%;
				height: 100%;
				border-radius: 0px 3px 3px 0px;
				outline: none;
				border: none;
				padding: 3px;
				border-bottom: var(--Border-1);
				border-color: transparent;
				border-radius: var(--Radius-0);
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field label.description{
				text-align: initial;
				vertical-align: middle;
				display: table-cell;
				font-size: 75%;
				line-height: 100%;
				padding: 3px;
				opacity: 0;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field:hover label.title{
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field:hover .input{
				outline: none;
				".Style::DoProperty("border-color", $this->FieldsBorderColor)."
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field:hover label.description{
				opacity: 0.75;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
		");
	}
	public function GetFieldsVerticalStyle(){
		return HTML::Style("
			.{$this->Name} .field{
				".Style::DoProperty("min-width",$this->FieldsMinWidth)."
				".Style::DoProperty("min-height", $this->FieldsMinHeight)."
				".Style::DoProperty("max-width", $this->FieldsMaxWidth)."
				".Style::DoProperty("max-height", $this->FieldsMaxHeight)."
				".Style::DoProperty("width", $this->FieldsWidth)."
				".Style::DoProperty("height", $this->FieldsHeight)."
				font-size: var(--Size-1);
				text-align: start;
				display: table-row;
			}
			.{$this->Name} .field label.title{
				".Style::DoProperty("color", $this->FieldsForeColor)."
				".Style::DoProperty("background-color", $this->FieldsBackColor)."
				width: fit-content;
				display: flex;
				position: relative;
				font-size: 125%;
				text-align: initial;
				margin-bottom: -1px;
				padding: 2px var(--Size-0);
				border-radius: 3px 3px 0px 0px;
				border: var(--Border-1);
				border-color: transparent;
				border-bottom: 0px solid;
				z-index: 1;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field .input{
				".Style::DoProperty("color", $this->FieldsForeColor)."
				".Style::DoProperty("background-color", $this->FieldsBackColor)."
				font-size: 100%;
				width: 100%;
				border-radius: 0px 3px 3px 3px;
				border: var(--Border-1);
				border-color: transparent;
				padding: 2px var(--Size-0);
				border-radius: var(--Radius-0);
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field label.description{
				text-align: initial;
				display: block;
				font-size: 50%;
				line-height: 100%;
				margin-top: -1px;
				padding: 2px var(--Size-0);
				opacity: 0;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field:hover label.title{
				font-size: 75%;
				".Style::DoProperty("border-color", $this->FieldsBorderColor)."
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field:hover .input{
				font-size: 125%;
				outline: none;
				".Style::DoProperty("border-color", $this->FieldsBorderColor)."
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field:hover label.description{
				font-size: 75%;
				opacity: 0.75;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
		");
	}
	public function GetFieldsTableStyle(){
		return HTML::Style("
			.{$this->Name} .field{
				".Style::DoProperty("min-width",$this->FieldsMinWidth)."
				".Style::DoProperty("min-height", $this->FieldsMinHeight)."
				".Style::DoProperty("max-width", $this->FieldsMaxWidth)."
				".Style::DoProperty("max-height", $this->FieldsMaxHeight)."
				".Style::DoProperty("width", $this->FieldsWidth)."
				".Style::DoProperty("height", $this->FieldsHeight)."
				font-size: var(--Size-1);
				text-align: start;
				display: table-row;
			}
			.{$this->Name} .field label.title{
				width: fit-content;
				display: table-cell;
				position: relative;
				text-align: initial;
				vertical-align: top;
				margin-right: -1px;
				padding: 5px var(--Size-0);
			    margin: 5px;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field .input{
				".Style::DoProperty("color", $this->FieldsForeColor)."
				".Style::DoProperty("background-color", $this->FieldsBackColor)."
				display: table-cell;
				font-size: 125%;
				width: 100%;
			    margin: 5px;
				border: none;
				border-color: transparent;
				border-bottom: var(--Border-1);
				border-radius: var(--Radius-0);
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field label.description{
				opacity: 0;
				display: none;
				font-size: 0%;
				line-height: 100%;
				text-align: initial;
				border-radius: 3px;
				border: var(--Border-1) var(--BackColor-2);
				background-color: var(--ForeColor-2);
				color: var(--BackColor-2);
				position: absolute;
				padding: calc(var(--Size-0) / 2);
				z-index: 1;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field:hover .input{
				outline: none;
				".Style::DoProperty("border-color", $this->FieldsBorderColor)."
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field:hover label.description{
				opacity: 1;
				display: block;
				font-size: 75%;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
		");
	}


	public function Get(){
		$name = $this->Name."_Form";
		$src = $this->Action??$this->Path??\_::$PATH;
		$src .=	(is_null($this->ResponseView)?null:((strpos($src,"?")?"&":"?").\_::$CONFIG->ViewHandlerKey."=".$this->ResponseView));
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
		yield (isValid($this->CancelLabel)?HTML::Button($this->CancelLabel, $this->CancelPath, ["name"=>"cancel", "class"=>"col-lg-3"]):"");
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