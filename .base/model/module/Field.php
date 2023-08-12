<?php
namespace MiMFa\Module;
use MiMFa\Library\Convert;
class Field extends Module{
	public $Template = null;
	/**
     * The key name of the field
     * @var string|null
     */
	public $Key = null;
	/**
     * Can be a datatype or an input type
     * @var object|string|null
     */
	public $Type = "text";
	/**
     * The default value of the field
     * @var string|null
	 */
	public $Value = null;
	/**
     * The default place holder of the field
     * @var string|null
     */
	public $PlaceHolder = null;
	/**
     * The other options of the fields like dropdowns
     * @var array|string|null
	 */
	public $Options = null;
	/**
     * Other important attributes of the field
     * @var array|string|null
	 */
	public $Attributes = null;
	public $ForeColor = "var(--ForeColor-1)";
	public $BackColor = "var(--BackColor-1)";
	public $BorderColor = "var(--ForeColor-5)";
	public $Height = "auto";
	public $Width = "100%";
	public $MinHeight = "10px";
	public $MinWidth = "10px";
	public $MaxHeight = "100vh";
	public $MaxWidth = "100vw";
	/**
	 * The field is required or not
     * @var bool|null
	 */
	public $Required = false;
	/**
     * Indicate the field be static or changable
     * @var bool|null
	 */
	public $Lock = false;


	/**
     * Create the module
     * @param object|string|null $type Can be a datatype or an input type
     * @param string|null $title The label text of the field
     * @param string|null $value The default value of the field
     * @param string|null $description The more detaled text about the field
     * @param array|string|null $options The other options of the field
     * @param array|string|null $attributes Other important attributes of the field
     * @param bool|null $lock Indicate the field be static or changable
     * @return Field
     */
	public function __construct($type = "text", $title = null, $value = null, $description = null, $options = null,  $attributes = null, $required = null, $lock = null){
        parent::__construct();
		$this->Set($type, $title, $value, $description, $options,  $attributes, $required, $lock);
    }
	/**
	 * Change the inputs tool
	 * @param object|string|null $type Can be a datatype or an input type
	 * @param string|null $title The label text of the field
     * @param string|null $value The default value of the field
     * @param string|null $description The more detaled text about the field
     * @param array|string|null $options The other options of the field
     * @param array|string|null $attributes Other important attributes of the field
     * @param bool|null $lock Indicate the field be static or changable
	 * @return Field
	 */
	public function Set($type = null, $title = null, $value = null, $description = null, $options = null, $attributes = null, $required = null, $lock = null){
		if(!is_null($type))
			if(is_string($type)) $this->Type = $type;
			else $this->Type = gettype($type);
        else $this->Type = null;
		$this->Title = $title;
		$this->Key = Convert::ToName($this->Title);
		$this->Value = $value;
		$this->Description = $description;
		$this->Options = $options;
		$this->Attributes = $attributes;
		$this->Required = $required??$this->Required;
		$this->Lock = $lock??$this->Lock;
		$this->PlaceHolder = null;
		return $this;
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
				vertical-align: top;
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
				width: 100%;
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
				opacity: 0.5;
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
				vertical-align: middle;
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
				width: 100%;
				border-radius: 0px 3px 3px 0px;
				<?php echo \MiMFa\Library\Style::DoProperty("border", \_::$TEMPLATE->Border(1)); ?>
				border-color: transparent;
				border-left: 0px solid;
				<?php echo \MiMFa\Library\Style::DoProperty("border-radius", \_::$TEMPLATE->Radius(0)); ?>
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> label.description{
				text-align: initial;
				vertical-align: middle;
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
		$id = $this->Key.getID();
		if(isValid($this->Title)) echo "<label for='$id' class='title'>$this->Title</label>";
		$this->PlaceHolder = $this->PlaceHolder??$this->Title;
		$attrs = Convert::ToString($this->Attributes, " ");
		$type = strtolower($this->Type??"text");
		switch ($type) {
			case 'string':
			case 'multiline':
			case 'textarea':
				echo "<textarea id='$id' name='$this->Key' placeholder='$this->PlaceHolder' class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>$this->Value</textarea>";
				break;
			case 'enum':
			case 'dropdown':
			case 'combobox':
			case 'select':
				echo "<select id='$id' name='$this->Key' placeholder='$this->PlaceHolder' class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
				if(isValid($this->Options))
					foreach($this->Options as $key=>$value)
						echo "<option value='$key'".($key==$this->Value?" selected":"").">$value</option>";
				echo "</select>";
				break;
			case 'radio':
			case 'radiobox':
			case 'radiobutton':
				echo "<input id='$id' name='$this->Key' type='radio' checked='$this->Value' value='$this->PlaceHolder' class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
				break;
			case 'bool':
			case 'boolean':
			case 'check':
			case 'checkbox':
			case 'checkbutton':
				echo "<input id='$id' name='$this->Key' type='checkbox' checked='$this->Value' value='$this->PlaceHolder' class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
				break;
			case 'int':
			case 'long':
			case 'number':
				echo "<input id='$id' name='$this->Key' type='number' value='$this->Value' placeholder='$this->PlaceHolder' class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
				break;
			case 'float':
			case 'double':
			case 'decimal':
				echo "<input id='$id' name='$this->Key' type='number' value='$this->Value' placeholder='$this->PlaceHolder' class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
				break;
			case 'phone':
			case 'tel':
			case 'telephone':
				echo "<input id='$id' name='$this->Key' type='tel' value='$this->Value' placeholder='$this->PlaceHolder' class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
				break;
			case 'url':
			case 'path':
				echo "<input id='$id' name='$this->Key' type='url' value='$this->Value' placeholder='$this->PlaceHolder' class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
				break;
			case 'doc':
			case 'document':
			case 'image':
			case 'audio':
			case 'video':
			case 'file':
				if(isValid($this->Value)){
					MODULE("Player");
                    $mod = new Player();
                    $mod->Source = $this->Value;
                    $mod->Draw();
                }
				$accept = "";
				if(isValid($this->Options))
                    $accept = join("|", array_values($this->Options));
				else switch ($type)
                    {
                        case 'doc':
                        case 'document':
                            $accept = " accept='.doc, .docx, .ppt, .pptx, .sls, .slx, .txt, .csv, .tsv'";
                            break;
                        case "image":
                            $accept = " accept='image/*'";
                            break;
                        case "audio":
                            $accept = " accept='audio/*'";
                            break;
                        case "video":
                            $accept = " accept='video/*'";
							break;
                        default:
                            $accept = "";
                            break;
                    }
				echo "<input id='$id' name='$this->Key' type='file' value='$this->Value' placeholder='$this->PlaceHolder' class='field'$accept".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
				break;
			case 'docs':
			case 'documents':
			case 'images':
			case 'audios':
			case 'videos':
			case 'files':
				if(isValid($this->Options))
                    $accept = join("|", array_values($this->Options));
				else switch ($type)
                    {
                        case 'docs':
                        case 'documents':
                            $accept = " accept='.doc, .docx, .ppt, .pptx, .sls, .slx, .txt, .csv, .tsv'";
                            break;
                        case "images":
                            $accept = " accept='image/*'";
                            break;
                        case "audios":
                            $accept = " accept='audio/*'";
                            break;
                        case "videos":
                            $accept = " accept='video/*'";
                            break;
                        default:
                            $accept = "";
                            break;
                    }
				echo "<input id='$id' name='$this->Key' type='file' value='$this->Value' placeholder='$this->PlaceHolder' class='field'$accept".($this->Lock?" disabled":"").($this->Required?" required":"")." multiple $attrs>";
				break;
            case "dir":
            case "directory":
            case "folder":
				echo "<input id='$id' name='$this->Key' type='file' value='$this->Value' placeholder='$this->PlaceHolder' class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." webkitdirectory multiple $attrs>";
				break;
			case 'imagesubmit':
			case 'imgsubmit':
				echo "<input id='$id' name='$this->Key' type='image' src='$this->Value' value='$this->PlaceHolder' class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
				break;
			default:
				echo "<input id='$id' name='$this->Key' type='$type' value='$this->Value' placeholder='$this->PlaceHolder' class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
				break;
		}
		if(isValid($this->Description)) echo "<label for='$id' class='description'>$this->Description</label>";
	}
}
?>