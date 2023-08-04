<?php namespace MiMFa\Module;
class Field extends Module{
	public $Template = null;
	public $Type = "text";
	public $Value = null;
	public $PlaceHolder = null;
	public $Options = null;
	public $ForeColor = "var(--ForeColor-1)";
	public $BackColor = "var(--BackColor-1)";
	public $BorderColor = "var(--BackColor-5)";
	public $Height = "auto";
	public $Width = "100%";
	public $MinHeight = "10px";
	public $MinWidth = "10px";
	public $MaxHeight = "100vh";
	public $MaxWidth = "100vw";
	
	
	public function __construct($type = "text", $title = null, $value = null, $description = null, $placeholder = null){
        parent::__construct();
		$this->Set($type, $title, $value, $description, $placeholder);
    }
	
	public function Set($type = null, $title = null, $value = null, $description = null, $placeholder = null){
		$this->Type = $type??$this->Type;
		$this->Title = $title??$this->Title;
		$this->Value = $value??$this->Value;
		$this->Description = $description??$this->Description;
		$this->PlaceHolder = $placeholder??$this->PlaceHolder;
    }

	public function EchoStyle(){
		switch (strtolower($this->Template??"")) {
			case 'v':
			case 'vertical':
				$this->EchoVerticalStyle();
				break;
			case 'h':
			case 'horizontal':
				$this->EchoHorizontalStyle();
				break;
			default:
				$this->EchoDefaultStyle();
				break;
		}
	}
	
	public function EchoDefaultStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name; ?>{
				<?php echo \MiMFa\Library\Style::DoProperty("min-width",$this->MinWidth); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("min-height", $this->MinHeight); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("max-width", $this->MaxWidth); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("max-height", $this->MaxHeight); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("width", $this->Width); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("height", $this->Height); ?>
				font-size: var(--Size-1);
				text-align: start;
				display: table-row;
			}
			.<?php echo $this->Name; ?> label.title{
				width: fit-content;
				display: table-cell;
				position: relative;
				text-align: initial;
				margin-right: -1px;
				padding: 0px 10px;
				z-index: 1;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> .field{
				<?php echo \MiMFa\Library\Style::DoProperty("color", $this->ForeColor); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("background-color", $this->BackColor); ?>
				display: table-cell;
				font-size: 125%;
				border-radius: 3px;
				<?php echo \MiMFa\Library\Style::DoProperty("border", \_::$TEMPLATE->Border(1)); ?>
				border-color: transparent;
				<?php echo \MiMFa\Library\Style::DoProperty("border-radius", \_::$TEMPLATE->Radius(0)); ?>
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> label.description{
				text-align: initial;
				display: block;
				font-size: 75%;
				padding: 5px;
				opacity: 0;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>:hover .field{
				<?php echo \MiMFa\Library\Style::DoProperty("border-color", $this->BorderColor); ?>
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>:hover label.description{
				opacity: 0.75;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
		</style>
		<?php
	}
	public function EchoHorizontalStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name; ?>{
				<?php echo \MiMFa\Library\Style::DoProperty("min-width",$this->MinWidth); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("min-height", $this->MinHeight); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("max-width", $this->MaxWidth); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("max-height", $this->MaxHeight); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("width", $this->Width); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("height", $this->Height); ?>
				font-size: var(--Size-1);
				text-align: start;
				display: table-row;
			}
			.<?php echo $this->Name; ?> label.title{
				<?php echo \MiMFa\Library\Style::DoProperty("color", $this->ForeColor); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("background-color", $this->BackColor); ?>
				width: fit-content;
				display: table-cell;
				position: relative;
				text-align: initial;
				margin-right: -1px;
				padding: 0px 10px;
				border-radius: 3px 0px 0px 3px;
				<?php echo \MiMFa\Library\Style::DoProperty("border", \_::$TEMPLATE->Border(1)); ?>
				border-color: transparent;
				border-right: 0px solid;
				z-index: 1;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> .field{
				<?php echo \MiMFa\Library\Style::DoProperty("color", $this->ForeColor); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("background-color", $this->BackColor); ?>
				display: table-cell;
				font-size: 125%;
				border-radius: 0px 3px 3px 0px;
				<?php echo \MiMFa\Library\Style::DoProperty("border", \_::$TEMPLATE->Border(1)); ?>
				border-color: transparent;
				border-left: 0px solid;
				<?php echo \MiMFa\Library\Style::DoProperty("border-radius", \_::$TEMPLATE->Radius(0)); ?>
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> label.description{
				text-align: initial;
				display: table-cell;
				font-size: 75%;
				padding: 0px 5px;
				opacity: 0;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>:hover label.title{
				<?php echo \MiMFa\Library\Style::DoProperty("border-color", $this->BorderColor); ?>
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>:hover .field{
				<?php echo \MiMFa\Library\Style::DoProperty("border-color", $this->BorderColor); ?>
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>:hover label.description{
				opacity: 0.75;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
		</style>
		<?php
	}
	public function EchoVerticalStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name; ?>{
				<?php echo \MiMFa\Library\Style::DoProperty("min-width",$this->MinWidth); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("min-height", $this->MinHeight); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("max-width", $this->MaxWidth); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("max-height", $this->MaxHeight); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("width", $this->Width); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("height", $this->Height); ?>
				font-size: var(--Size-1);
				text-align: start;
			}
			.<?php echo $this->Name; ?> label.title{
				<?php echo \MiMFa\Library\Style::DoProperty("color", $this->ForeColor); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("background-color", $this->BackColor); ?>
				width: fit-content;
				display: flex;
				position: relative;
				font-size: 125%;
				text-align: initial;
				margin-bottom: -1px;
				padding: 2px;
				border-radius: 3px 3px 0px 0px;
				<?php echo \MiMFa\Library\Style::DoProperty("border", \_::$TEMPLATE->Border(1)); ?>
				border-color: transparent;
				border-bottom: 0px solid;
				z-index: 1;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> .field{
				<?php echo \MiMFa\Library\Style::DoProperty("color", $this->ForeColor); ?>
				<?php echo \MiMFa\Library\Style::DoProperty("background-color", $this->BackColor); ?>
				font-size: 100%;
				width: 100%;
				border-radius: 0px 3px 3px 3px;
				<?php echo \MiMFa\Library\Style::DoProperty("border", \_::$TEMPLATE->Border(1)); ?>
				border-color: transparent;
				<?php echo \MiMFa\Library\Style::DoProperty("border-radius", \_::$TEMPLATE->Radius(0)); ?>
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> label.description{
				text-align: initial;
				display: block;
				font-size: 50%;
				margin-top: -1px;
				padding: 3px;
				opacity: 0;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>:hover label.title{
				font-size: 75%;
				<?php echo \MiMFa\Library\Style::DoProperty("border-color", $this->BorderColor); ?>
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>:hover .field{
				font-size: 125%;
				<?php echo \MiMFa\Library\Style::DoProperty("border-color", $this->BorderColor); ?>
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>:hover label.description{
				font-size: 75%;
				opacity: 0.75;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
		</style>
		<?php
	}

	public function Echo(){
		$this->EchoContent();
		$id = $this->Name.getID();
		if(isValid($this->Title)) echo "<label for='$id' class='title'>$this->Title</label>";
		$this->PlaceHolder = $this->PlaceHolder??(isValid($this->Value)?"":$this->Name);
		switch (strtolower($this->Type??"text")) {
            case 'multiline':
            case 'textarea':
				echo "<textarea id='$id' name='$this->Name' placeholder='$this->PlaceHolder' class='field'>$this->Value</textarea>";
				break;
			case 'dropdown':
			case 'combobox':
			case 'select':
				echo "<select id='$id' name='$this->Name' placeholder='$this->PlaceHolder' class='field'>";
				if(isValid($this->Options))
					foreach($this->Options as $key=>$value)
						echo "<option value='$key'".($key==$this->Value?" selected":"").">$value</option>";
				echo "</select>";
				break;
			case 'radio':
			case 'check':
			case 'radiobox':
			case 'checkbox':
				echo "<input id='$id' name='$this->Name' type='$this->Type' checked='$this->Value' value='$this->PlaceHolder' class='field'>";
				break;
			default:
				echo "<input id='$id' name='$this->Name' type='$this->Type' value='$this->Value' placeholder='$this->PlaceHolder' class='field'>";
				break;
        }
		if(isValid($this->Description)) echo "<label for='$id' class='description'>$this->Description</label>";
	}
}
?>