<?php
namespace MiMFa\Module;

use Error;
use MiMFa\Library\HTML;
use MiMFa\Library\Convert;
use MiMFa\Library\Style;
class Form extends Module
{
	public $Capturable = true;
	/**
	 * The name of selected Template
	 *	 'v': vertical
	 *	 'h': horizontal
	 *	 'b': both
	 *	 's': special
	 *	 't': table
	 * @var mixed
	 */
	public $Template = null;
	public $Path = null;
	public $Action = null;
	public $Image = null;
	public $Title = "Form";
	public $SubmitLabel = "Submit";
	public $Buttons = null;
	public $ResetLabel = "Reset";
	public $CancelLabel = null;
	public $CancelPath = null;
	public $BackLabel = "Back to Home";
	public $BackPath = null;
	public $ReCaptchaSiteKey = null;
	public $Method = "POST";
	public $EncType = "multipart/form-data";
	public $MailSubject = null;
	public $SenderEmail = null;
	public $ReceiverEmail = null;
	public $Timeout = 60000;
	public $BlockTimeout = 25000;
	public $SuccessPath = null;
	public $ErrorPath = null;
	public $HasDecoration = true;
	public $ResponseView = "value";
	public $AllowHeader = true;
	public $AllowContent = true;
	public $AllowFooter = true;
	public $Class = "container";
	public $ContentClass = "col-lg-6";
	public $UseAJAX = true;

	public $FieldsTypes = [];

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
	public function __construct($title = "Form", $action = null, $method = "POST", mixed $children = [], $description = null, $image = null)
	{
		parent::__construct();
		$this->Set($title, $action, $method, $children, $description, $image);
		if (!is_null($this->Action)) $this->ResponseView = null;
		if (!is_null($this->ResponseView)) {
			unset($_GET[\_::$CONFIG->ViewHandlerKey]);
			unset($_REQUEST[\_::$CONFIG->ViewHandlerKey]);
		}
		$this->ReCaptchaSiteKey = \_::$CONFIG->ReCaptchaSiteKey;
	}
	/**
	 * Set the main properties of module
	 */
	public function Set($title = null, $action = null, $method = "POST", mixed $children = [], $description = null, $image = null)
	{
		$this->Title = $title??$this->Title;
		$this->Description = $description??$this->Description;
		$this->Image = $image??$this->Image ;
		$this->Action = $action??$this->Action;
		$this->Method = $method??$this->Method;
		$this->Children = $children??$this->Children;
		return $this;
	}

	public function GetStyle()
	{
		if ($this->HasDecoration) {
			$style = parent::GetStyle() . HTML::Style("
				.{$this->Name} .rack {
					align-items: center;
				}
				.{$this->Name} form {
					padding-top: var(--Size-0);
					padding-bottom: var(--Size-0);
				}
				.{$this->Name} form .fields {
					display: table;
					min-width: 100%;
				}
				.{$this->Name} .header {
					position: sticky;
					top: 0px;
					bottom: 0px;
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
				.{$this->Name} .header  .form-title {
					text-align: center;
				}
				".($this->Description?"":
					".{$this->Name} .header  .back-button {
					text-align: center;
					display: block;
					width: 100%;
				}"
				)."
				.{$this->Name} .content {
					background-color: var(--BackColor-0);
					color: var(--ForeColor-0);
				}

				.{$this->Name} .content .title {
					white-space: nowrap;
				}
				
				.{$this->Name} .group.buttons {
					gap: calc(var(--Size-0) / 2) var(--Size-0);
				}
				
				.{$this->Name} .button {
					background-color: inherit;
					color: inherit;
					min-width: fit-content;
					max-width: 85vw;
					border-radius: var(--Radius-1);
					padding: var(--Size-0) var(--Size-1);
					box-shadow: var(--Shadow-0);
					" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
				}
				.{$this->Name} .button:hover {
					font-weight: bold;
					box-shadow: var(--Shadow-2);
				}
				.{$this->Name} .submitbutton {
					background-color: var(--ForeColor-2);
					color: var(--BackColor-2);
				}
				.{$this->Name} .submitbutton:hover {
					background-color: var(--BackColor-5);
					color: var(--ForeColor-5);
				}

				.{$this->Name} .group {
					padding: var(--Size-0);
				}
			");
			switch (strtolower($this->Template ?? "")) {
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
		} else
			return parent::GetStyle();
	}
	public function GetFieldsDefaultStyle()
	{
		return HTML::Style("
				.{$this->Name} .field {
					" . Style::DoProperty("min-width", $this->FieldsMinWidth) . "
					" . Style::DoProperty("min-height", $this->FieldsMinHeight) . "
					" . Style::DoProperty("max-width", $this->FieldsMaxWidth) . "
					" . Style::DoProperty("max-height", $this->FieldsMaxHeight) . "
					" . Style::DoProperty("width", $this->FieldsWidth) . "
					" . Style::DoProperty("height", $this->FieldsHeight) . "
					display: flex;
					padding: 0px var(--Size-0) var(--Size-0);
					padding: 3px 0px;
				}

				.{$this->Name} .field .title{
					font-size: 90%;
					opacity: 80%;
					min-width: fit-content;
					display: inline-flex;
					vertical-align: middle;
					align-items: center;
					margin: 0px;
					padding: 0px var(--Size-0);
					" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
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
					" . Style::DoProperty("color", $this->FieldsForeColor) . "
					" . Style::DoProperty("background-color", $this->FieldsBackColor) . "
					font-size: 125%;
					display: inline-flex;
					width: 100%;
					max-width: 85vw;
					max-width: -webkit-fill-available;
					padding-top: calc(var(--Size-0) / 2.5);
					padding-bottom: calc(var(--Size-0) / 2.5);
					border: none;
					border-bottom: var(--Border-1);
					border-color: transparent;
					border-radius: var(--Radius-0);
					" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
				}
				.{$this->Name} .field .input[type='color'] {
					min-width: auto;
					width: initial;
					aspect-ratio: 1;
				}
				.{$this->Name} .field .input::placeholder {
					color: #888;
					font-weight: bold;
					font-size: 0.9rem;
				}
				.{$this->Name} .field .input:focus {
					box-shadow: none;
					" . Style::DoProperty("border-color", $this->FieldsBorderColor) . "
					" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
				}
				.{$this->Name} .field label.description{
					font-size: 75%;
					line-height: 100%;
					opacity: 0.5;
					text-align: initial;
					display: block;
					padding-top: 5px;
					" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
				}
				.{$this->Name} .field:hover label.description{
					opacity: 0.75;
					" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
				}
			");
	}
	public function GetFieldsBothStyle()
	{
		return HTML::Style("
			.{$this->Name} .field{
				" . Style::DoProperty("min-width", $this->FieldsMinWidth) . "
				" . Style::DoProperty("min-height", $this->FieldsMinHeight) . "
				" . Style::DoProperty("max-width", $this->FieldsMaxWidth) . "
				" . Style::DoProperty("max-height", $this->FieldsMaxHeight) . "
				" . Style::DoProperty("width", $this->FieldsWidth) . "
				" . Style::DoProperty("height", $this->FieldsHeight) . "
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
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field .input{
				" . Style::DoProperty("color", $this->FieldsForeColor) . "
				" . Style::DoProperty("background-color", $this->FieldsBackColor) . "
				display: table-cell;
				font-size: 125%;
				width: 100%;
				min-width: min(300px, 40vw);
				max-width: 85vw;
				border: none;
				border-bottom: var(--Border-1);
				" . Style::DoProperty("border-color", $this->FieldsBorderColor) . "
				border-radius: var(--Radius-0);
				margin: 5px;
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field .input[type='color'] {
				min-width: auto;
				width: initial;
				aspect-ratio: 1;
			}
			.{$this->Name} .field label.description{
				text-align: initial;
				display: block;
				font-size: 75%;
				line-height: 100%;
				padding: 5px 2px;
				opacity: 0.5;
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field:hover .input{
				" . Style::DoProperty("outline-color", $this->FieldsBorderColor) . "
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field:hover label.description{
				opacity: 0.75;
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
		");
	}
	public function GetFieldsHorizontalStyle()
	{
		return HTML::Style("
			.{$this->Name} .field{
				" . Style::DoProperty("min-width", $this->FieldsMinWidth) . "
				" . Style::DoProperty("min-height", $this->FieldsMinHeight) . "
				" . Style::DoProperty("max-width", $this->FieldsMaxWidth) . "
				" . Style::DoProperty("max-height", $this->FieldsMaxHeight) . "
				" . Style::DoProperty("width", $this->FieldsWidth) . "
				" . Style::DoProperty("height", $this->FieldsHeight) . "
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
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field .input{
				" . Style::DoProperty("color", $this->FieldsForeColor) . "
				" . Style::DoProperty("background-color", $this->FieldsBackColor) . "
				display: table-cell;
				font-size: 125%;
				width: 100%;
				min-width: min(300px, 40vw);
				max-width: 85vw;
				height: 100%;
				border-radius: 0px 3px 3px 0px;
				outline: none;
				border: none;
				padding: 3px;
				border-bottom: var(--Border-1);
				border-color: transparent;
				border-radius: var(--Radius-0);
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field .input[type='color'] {
				min-width: auto;
				width: initial;
				aspect-ratio: 1;
			}
			.{$this->Name} .field label.description{
				text-align: initial;
				vertical-align: middle;
				display: table-cell;
				font-size: 75%;
				line-height: 100%;
				padding: 3px;
				opacity: 0;
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field:hover label.title{
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field:hover .input{
				outline: none;
				" . Style::DoProperty("border-color", $this->FieldsBorderColor) . "
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field:hover label.description{
				opacity: 0.75;
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
		");
	}
	public function GetFieldsVerticalStyle()
	{
		return HTML::Style("
			.{$this->Name} .field{
				" . Style::DoProperty("min-width", $this->FieldsMinWidth) . "
				" . Style::DoProperty("min-height", $this->FieldsMinHeight) . "
				" . Style::DoProperty("max-width", $this->FieldsMaxWidth) . "
				" . Style::DoProperty("max-height", $this->FieldsMaxHeight) . "
				" . Style::DoProperty("width", $this->FieldsWidth) . "
				" . Style::DoProperty("height", $this->FieldsHeight) . "
				font-size: var(--Size-1);
				text-align: start;
				display: table-row;
			}
			.{$this->Name} .field label.title{
				" . Style::DoProperty("color", $this->FieldsForeColor) . "
				" . Style::DoProperty("background-color", $this->FieldsBackColor) . "
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
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field .input{
				" . Style::DoProperty("color", $this->FieldsForeColor) . "
				" . Style::DoProperty("background-color", $this->FieldsBackColor) . "
				font-size: 100%;
				width: 100%;
				min-width: min(300px, 40vw);
				max-width: 85vw;
				border-radius: 0px 3px 3px 3px;
				border: var(--Border-1);
				border-color: transparent;
				padding: 2px var(--Size-0);
				border-radius: var(--Radius-0);
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field .input[type='color'] {
				min-width: auto;
				width: initial;
				aspect-ratio: 1;
			}
			.{$this->Name} .field label.description{
				text-align: initial;
				display: block;
				font-size: 50%;
				line-height: 100%;
				margin-top: -1px;
				padding: 2px var(--Size-0);
				opacity: 0;
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field:hover label.title{
				font-size: 75%;
				" . Style::DoProperty("border-color", $this->FieldsBorderColor) . "
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field:hover .input{
				font-size: 125%;
				outline: none;
				" . Style::DoProperty("border-color", $this->FieldsBorderColor) . "
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field:hover label.description{
				font-size: 75%;
				opacity: 0.75;
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
		");
	}
	public function GetFieldsTableStyle()
	{
		return HTML::Style("
			.{$this->Name} .field{
				" . Style::DoProperty("min-width", $this->FieldsMinWidth) . "
				" . Style::DoProperty("min-height", $this->FieldsMinHeight) . "
				" . Style::DoProperty("max-width", $this->FieldsMaxWidth) . "
				" . Style::DoProperty("max-height", $this->FieldsMaxHeight) . "
				" . Style::DoProperty("width", $this->FieldsWidth) . "
				" . Style::DoProperty("height", $this->FieldsHeight) . "
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
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field .input{
				" . Style::DoProperty("color", $this->FieldsForeColor) . "
				" . Style::DoProperty("background-color", $this->FieldsBackColor) . "
				display: table-cell;
				font-size: 125%;
				width: 100%;
				min-width: min(300px, 40vw);
				max-width: 85vw;
			    margin: 5px;
				border: none;
				border-color: transparent;
				border-bottom: var(--Border-1);
				border-radius: var(--Radius-0);
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field .input[type='color'] {
				min-width: auto;
				width: initial;
				aspect-ratio: 1;
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
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field:hover .input{
				outline: none;
				" . Style::DoProperty("border-color", $this->FieldsBorderColor) . "
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
			.{$this->Name} .field:hover label.description{
				opacity: 1;
				display: block;
				font-size: 75%;
				" . Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)) . "
			}
		");
	}

	public function Get()
	{
		if($this->CheckBlock()) return null;
		$name = $this->Name . "_Form";
		$src = $this->Action ?? $this->Path ?? \_::$PATH;
		$src .= (is_null($this->ResponseView) ? null : ((strpos($src, "?") ? "&" : "?") . \_::$CONFIG->ViewHandlerKey . "=" . $this->ResponseView));
		if (is_array($this->Children) && count($this->Children) > 0) {
			$attr = $this->Method ? [] : ["disabled"];
			$this->Children = isEmpty($this->FieldsTypes)
				? iteration($this->Children, function ($k, $v) use ($attr) {
					if (is_integer($k))
						return $v;
					else
						return HTML::Field(
							type: null,
							key: $k,
							value: $v,
							attributes: $attr
						);
				})
				: iteration($this->FieldsTypes, function ($k, $type) use ($attr) {
					$v = getValid($this->Children, $k, null);
					if ($type === false)
						return null;
					if (is_integer($k) && isEmpty($type))
						return $v;
					else
						return HTML::Field(
							type: $type,
							key: $k,
							value: $v,
							attributes: $attr
						);
				});
		}

		if (isValid($src))
			if ($this->HasDecoration)
				return
					HTML::Rack(
						($this->AllowHeader ? HTML::LargeSlot(
							HTML::Media(null, $this->Image, ["class" => "image"]) .
							$this->GetHeader() .
							$this->GetTitle(["class"=>"form-title"]) .
							$this->GetDescription(["class"=>"form-description"]) .
							(isValid($this->BackLabel) ? HTML::Link($this->BackLabel, $this->BackPath ?? \_::$HOST, ["class"=>"back-button"]) : "")
							,
							["class" => "header"]
						) : "") .
						HTML::LargeSlot(
							HTML::Form(
								HTML::Rack(
									($this->AllowContent ? $this->GetContent() : "") .
									Convert::ToString($this->GetFields()),
									["class" => "group fields"]
								) .
								HTML::Rack(Convert::ToString($this->GetButtons()), ["class" => "group buttons"])
								,
								$src,
								["id" => $name, "name" => $name, "enctype" => $this->EncType, "method" => $this->Method]
							) .
							($this->AllowFooter ? $this->GetFooter() : "")
							,
							["class" => "{$this->ContentClass} content"]
						)
					);
			else
				return
					($this->AllowHeader ?
						HTML::Media(null, $this->Image, ["class" => "image"]) .
						$this->GetHeader() .
						$this->GetTitle(["class"=>"form-title"]) .
						$this->GetDescription(["class"=>"form-description"]) .
						(isValid($this->BackLabel) ? HTML::Link($this->BackLabel, $this->BackPath ?? \_::$HOST, ["class"=>"back-button"]) : "")
						: "") .
					HTML::Form(
						($this->AllowContent ? $this->GetContent() : "") .
						Convert::ToString($this->GetFields()) .
						HTML::Rack(Convert::ToString($this->GetButtons()), ["class" => "group buttons"])
						,
						$src,
						["id" => $name, "name" => $name, "enctype" => $this->EncType, "method" => $this->Method]
					) .
					($this->AllowFooter ? $this->GetFooter() : "");
		return null;
	}
	public function GetHeader()
	{

	}
	public function GetFields()
	{
		if (isValid($this->ReCaptchaSiteKey)) {
			LIBRARY("reCaptcha");
			yield \MiMFa\Library\reCaptcha::GetHtml($this->ReCaptchaSiteKey);
		}
	}
	public function GetButtons()
	{
		if (isValid($this->Buttons))
			yield $this->Buttons;
		if ($this->Method) {
			yield (isValid($this->SubmitLabel) ? HTML::SubmitButton($this->SubmitLabel, ["name" => "submit", "class" => "col-md"]) : "");
			yield (isValid($this->ResetLabel) ? HTML::ResetButton($this->ResetLabel, ["name" => "reset", "class" => "col-md-4"]) : "");
		}
		yield (isValid($this->CancelLabel) ? HTML::Button($this->CancelLabel, $this->CancelPath ?? \_::$HOST, ["name" => "cancel", "class" => "col-lg-3"]) : "");
	}
	public function GetFooter()
	{

	}

	public function GetScript()
	{
		return parent::GetScript() . HTML::Script("
			$(function () {
				" . ($this->UseAJAX ? "handleForm('.{$this->Name} form',
					function (data, selector)  {//success
						if (data.includes('result success')) {
							$(`.{$this->Name} form .result`).remove();
							$(`.{$this->Name} form`).append(data);
							" . (isValid($this->SuccessPath) ? "load(`{$this->SuccessPath}`);" : "") . "
						}
						else {
							$(`.{$this->Name} form .result`).remove();
							$(`.{$this->Name} form`).append(data);
            				" . (isValid($this->ErrorPath) ? "load(`{$this->ErrorPath}`);" : "") . "
						}
					},
					{ timeout: {$this->Timeout} }
				);" : "") . "
				$(`.{$this->Name} :is(input, select, textarea)`).on('focus', function () {
					$(this).parent().find(`.{$this->Name} .input-group .text`).css('border-color', 'var(--ForeColor-2)');
				});
				$(`.{$this->Name} :is(input, select, textarea)`).on('blur', function () {
					$(this).parent().find(`.{$this->Name} .input-group .text`).css('border-color', 'var(--ForeColor-2)');
				});
			});
		");
	}

	public function CheckBlock() {
		if ($this->BlockTimeout < 1) return false;
		$key = getClientIP() . getDirection();
		if (hasSession($key)) {
			$remains = getSession($key) - time();
			if ($remains <= 0) {
				popSession($key);
				return false;
			} else {
				$dt = new \DateTime("@0");
				$dt->add(new \DateInterval("PT{$remains}S"));
				SEND($this->GetError("Please try about {$dt->format('H:i:s')} later!"));
				return true;
			}
		}
		return false;
	}
	public function MakeBlock() {
		if ($this->BlockTimeout < 1) return false;
		return setSession(getClientIP() . getDirection(), time()+($this->BlockTimeout/1000));
	}

	public function Handle()
	{
		if($this->CheckBlock()) return;
		else $this->MakeBlock();
		if (isValid($this->ReCaptchaSiteKey)) {
			LIBRARY("reCaptcha");
			if (\MiMFa\Library\reCaptcha::CheckAnswer($this->ReCaptchaSiteKey))
				return SET($this->Handler());
			else return SET($this->GetError("Do something to denied access!"));
		} else return SET($this->Handler());
	}
	public function Handler()
	{
		$_req = $_REQUEST;
		switch (strtolower($this->Method)) {
			case "get":
				$_req = $_GET;
				break;
			case "post":
				$_req = $_POST;
				break;
		}
		try {
			if (count($_req) > 0) {
				if(isValid($this->ReceiverEmail)){
					if(!mail(
						$this->ReceiverEmail,
						$this->MailSubject??\_::$DOMAIN.": A new form submitted", 
						\MiMFa\Library\Convert::ToString($_req),
						$this->SenderEmail?["from"=>$this->SenderEmail]:[]))
							return $this->GetWarning("Could not send data successfully!");
				}
				return $this->GetSuccess("The form submitted successfully!", ["class" => "page"]);
			}
			else
				return $this->GetWarning("There a problem is occured!");
		} catch (\Exception $ex) {
			return $this->GetError($ex);
		}
	}

	public function GetSuccess($msg, ...$attr)
	{
		return HTML::Success($msg, ...$attr);
	}
	public function GetWarning($msg, ...$attr)
	{
		return HTML::Warning($msg, ...$attr);
	}
	public function GetError($msg, ...$attr)
	{
		return HTML::Error($msg, ...$attr);
	}
}
?>