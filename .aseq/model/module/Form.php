<?php
namespace MiMFa\Module;

use MiMFa\Library\Html;
use MiMFa\Library\Convert;
use MiMFa\Library\Style;
use MiMFa\Library\User;

class Form extends Module
{
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
	public $Access = 0;
	public $IsActive = true;
	public $Path = null;
	public $Action = null;
	public $Image = null;
	public $Title = "Form";
	public $SubmitLabel = "Submit";
	public $Buttons = null;
	public $ResetLabel = "Reset";
	public $CancelLabel = null;
	public $CancelPath = null;
	public $BackLabel = null;
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
	public $AllowDecoration = true;
	public $AllowAnimate = true;
	public $AllowHeader = true;
	public $AllowContent = true;
	public $AllowFooter = true;
	public $Class = "container";
	public $ContentClass = "col-lg-6";
	public $UseAjax = true;

	/**
	 * A function to check received values before accepting
	 * @template function($key,$value,$allVariables)
	 * @var callable|null
	 */
	public $FieldsChecker = null;
	public $FieldsTypes = [];
	public $SigningLabel = "Sign in or create an account to access this form";
	/**
	 * Allow signing or a callback handler for signing
	 * @var mixed
	 */
	public $Signing = null;

	public $FieldsForeColor = "var(--fore-color-input)";
	public $FieldsBackColor = "var(--back-color-input)";
	public $FieldsBorderColor = "var(--fore-color-special-input)";
	public $FieldsHeight = "auto";
	public $FieldsWidth = "100%";
	public $FieldsMinHeight = "10px";
	public $FieldsMinWidth = "auto";
	public $FieldsMaxHeight = null;
	public $FieldsMaxWidth = "100vw";
	public $Status = null;
	public $Result = null;
	public $SuccessHandler = "The form submitted successfully!";
	public $ErrorHandler = "There a problem is occured!";
	public $WarningHandler = "Please fill all required fields!";

	/**
	 * Create the module
     * @param string|null|array|callable $action The action reference path
	 */
	public function __construct($title = null, $action = null, $method = null, mixed $children = [], $description = null, $image = null)
	{
		parent::__construct();
		$this->Set($title, $action, $method, $children, $description, $image);
		$this->ReCaptchaSiteKey = \_::$Config->ReCaptchaSiteKey;
		$this->Signing = fn() => part(User::$InHandlerPath, ["Router" => ["DefaultMethodIndex" => 1], "AllowHeader" => false, "ContentClass" => "col-lg"], print: false);
		// $this->Router->All(function(){
		// 	if($this->Status && $this->Router->DefaultMethodIndex > 1) \_::Status($this->Status);
		// });
		if (\_::$Back->User->Access(\_::$Config->AdminAccess))
			$this->BlockTimeout = 500;
	}

	public function CheckAccess($access = 0, $blocking = true, $reaction = false, &$message = null)
	{
		if (!auth($access)) {
			$message = $this->GetError("You have not enough access!");
			if ($reaction)
				response($this->GetSigning());
			return false;
		}
		if (($message = $this->CheckBlock()) === false) {
			if ($blocking)
				$this->MakeBlock();
		} else {
			if ($reaction)
				response($message);
			return false;
		}
		if (isValid($this->ReCaptchaSiteKey)) {
			component("reCaptcha");
			if (!\MiMFa\Component\reCaptcha::CheckAnswer($this->ReCaptchaSiteKey)) {
				$message = $this->GetError("Do something to denied access!");
				if ($reaction)
					response($message);
				return false;
			}
		}
		return true;
	}
	public function CheckBlock()
	{
		if ($this->BlockTimeout < 1)
			return false;
		$key = getClientIp() . getDirection();
		if (hasSession($key)) {
			$remains = getSession($key) - time();
			if ($remains <= 0) {
				grabSession($key);
				return false;
			} else {
				$dt = new \DateTime("@0");
				$dt->add(new \DateInterval("PT{$remains}S"));
				$this->Status = 403;
				return $this->GetError("Please try about {$dt->format('H:i:s')} later!");
			}
		}
		return false;
	}
	public function MakeBlock()
	{
		if ($this->BlockTimeout < 1)
			return false;
		return setSession(getClientIp() . getDirection(), time() + max(1, $this->BlockTimeout / 1000));
	}
	public function UnBlock()
	{
		return grabSession(getClientIp() . getDirection());
	}

	/**
	 * Set the main properties of module
     * @param string|null|array|callable $action The action reference path
	 */
	public function Set($title = null, $action = null, $method = null, mixed $children = [], $description = null, $image = null)
	{
		$this->Title = $title ?? $this->Title;
		$this->Description = $description ?? $this->Description;
		$this->Image = $image ?? $this->Image;
		$this->Action = $action ?? $this->Action;
		$this->Method = getMethodName($method ?? $this->Method);
		$this->Children = $children ?? $this->Children;
		return $this;
	}

	public function GetStyle()
	{
		if ($this->AllowDecoration) {
			$style = parent::GetStyle() . Html::Style("
				.{$this->Name} .rack {
					align-items: center;
				}
				.{$this->Name} .content {
					background-color: var(--back-color);
					color: var(--fore-color);
				}
				.{$this->Name} form {
					padding-top: var(--size-0);
					padding-bottom: var(--size-0);
    				position: relative;
				}
				.{$this->Name} form .fields {
					display: table;
					min-width: 100%;
				}
				.{$this->Name} .header {
					position: sticky;
					top: var(--size-max);
					bottom: var(--size-max);
					padding: var(--size-1);
					text-align: center;
				}
				.{$this->Name} .header :is(.form-image, .form-image:before) {
					color: var(--fore-color-special);
					font-size: 300%;
					margin: 0px 5%;
            		width: 90%;
					padding: var(--size-1);
					height: auto;
					text-align: center;
				}
				.{$this->Name} .header svg.form-image {
					color: var(--fore-color-special);
					margin: 0;
					padding: 0;
            		width: 10vmin;
					height: unset;
					text-align: center;
				}
				.{$this->Name} .header :not(svg, i):is(.form-image, .form-image:before) {
					background-size: cover;
					background-repeat: no-repeat;
					border-radius: 100%;
					aspect-ratio: 1;
				}
				.{$this->Name} .header .form-title {
					margin: var(--size-0);
					text-align: center;
				}
				.{$this->Name} .header .back-button {
					text-align: center;
					display: block;
					width: 100%;
				}

				.{$this->Name} .content .title {
					white-space: nowrap;
				}
				
				.{$this->Name} .group.buttons {
					gap: calc(var(--size-0) / 2) var(--size-0);
				}
				
				.{$this->Name} .button {
					width: fit-content;
					max-width: 85%;
					" . Style::UniversalProperty("transition", "var(--transition-1)") . "
				}

				.{$this->Name} .group {
					padding: var(--size-0);
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
		return Html::Style("
				.{$this->Name} .field {
					" . Style::DoProperty("min-width", $this->FieldsMinWidth) . "
					" . Style::DoProperty("min-height", $this->FieldsMinHeight) . "
					" . Style::DoProperty("max-width", $this->FieldsMaxWidth) . "
					" . Style::DoProperty("max-height", $this->FieldsMaxHeight) . "
					" . Style::DoProperty("width", $this->FieldsWidth) . "
					" . Style::DoProperty("height", $this->FieldsHeight) . "
					display: flex;
					padding: 0px var(--size-0) var(--size-0);
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
					padding: 0px var(--size-0);
					" . Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
				.{$this->Name} .field .prepend{
					display: inline-flex;
					margin: 0px;
					width: fit-content;
					padding: var(--size-0);
					height: 100%;
					border: none;
					background-color: var(--back-color-input);
					color: var(--fore-color-input);
					border-top: none;
					border-bottom: 1px solid var(--fore-color-special-input);
				}
				.{$this->Name} .field .input {
					" . Style::DoProperty("color", $this->FieldsForeColor) . "
					" . Style::DoProperty("background-color", $this->FieldsBackColor) . "
					font-size: 125%;
					display: inline-flex;
					width: 100%;
					max-width: 85vw;
					max-width: -webkit-fill-available;
					padding-top: calc(var(--size-0) / 2.5);
					padding-bottom: calc(var(--size-0) / 2.5);
					border: none;
					border-bottom: var(--border-1);
					border-color: transparent;
					border-radius: var(--radius-0);
					" . Style::UniversalProperty("transition", "var(--transition-1)") . "
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
					" . Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
				.{$this->Name} .field label.description{
					font-size: 75%;
					line-height: 100%;
					opacity: 0.5;
					text-align: initial;
					display: block;
					padding-top: 5px;
					" . Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
				.{$this->Name} .field:hover label.description{
					opacity: 0.75;
					" . Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
					
				.{$this->Name} .group.buttons {
					text-align: center;
				}
			");
	}
	public function GetFieldsBothStyle()
	{
		return Html::Style("
			.{$this->Name} .field{
				" . Style::DoProperty("min-width", $this->FieldsMinWidth) . "
				" . Style::DoProperty("min-height", $this->FieldsMinHeight) . "
				" . Style::DoProperty("max-width", $this->FieldsMaxWidth) . "
				" . Style::DoProperty("max-height", $this->FieldsMaxHeight) . "
				" . Style::DoProperty("width", $this->FieldsWidth) . "
				" . Style::DoProperty("height", $this->FieldsHeight) . "
				font-size: var(--size-1);
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
				padding: 5px var(--size-0);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
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
				border-bottom: var(--border-1);
				" . Style::DoProperty("border-color", $this->FieldsBorderColor) . "
				border-radius: var(--radius-0);
				margin: 5px;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
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
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .field:hover .input{
				" . Style::DoProperty("outline-color", $this->FieldsBorderColor) . "
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .field:hover label.description{
				opacity: 0.75;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
		");
	}
	public function GetFieldsHorizontalStyle()
	{
		return Html::Style("
			.{$this->Name} .field{
				" . Style::DoProperty("min-width", $this->FieldsMinWidth) . "
				" . Style::DoProperty("min-height", $this->FieldsMinHeight) . "
				" . Style::DoProperty("max-width", $this->FieldsMaxWidth) . "
				" . Style::DoProperty("max-height", $this->FieldsMaxHeight) . "
				" . Style::DoProperty("width", $this->FieldsWidth) . "
				" . Style::DoProperty("height", $this->FieldsHeight) . "
				font-size: var(--size-1);
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
				padding: 3px var(--size-1);
				border-radius: 3px 0px 0px 3px;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
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
				border-bottom: var(--border-1);
				border-color: transparent;
				border-radius: var(--radius-0);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
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
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .field:hover label.title{
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .field:hover .input{
				outline: none;
				" . Style::DoProperty("border-color", $this->FieldsBorderColor) . "
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .field:hover label.description{
				opacity: 0.75;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
		");
	}
	public function GetFieldsVerticalStyle()
	{
		return Html::Style("
			.{$this->Name} .field{
				" . Style::DoProperty("min-width", $this->FieldsMinWidth) . "
				" . Style::DoProperty("min-height", $this->FieldsMinHeight) . "
				" . Style::DoProperty("max-width", $this->FieldsMaxWidth) . "
				" . Style::DoProperty("max-height", $this->FieldsMaxHeight) . "
				" . Style::DoProperty("width", $this->FieldsWidth) . "
				" . Style::DoProperty("height", $this->FieldsHeight) . "
				font-size: var(--size-1);
				text-align: start;
				display: table-row;
			}
			.{$this->Name} .field label.title{
				background-color: var(--back-color);
				color: var(--fore-color);
				width: fit-content;
				display: flex;
				position: relative;
				font-size: 125%;
				text-align: initial;
				margin-bottom: -1px;
				padding: 2px var(--size-0);
				border-radius: 3px 3px 0px 0px;
				border: var(--border-1);
				border-color: transparent;
				border-bottom: 0px solid;
				z-index: 1;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .field .input{
				" . Style::DoProperty("color", $this->FieldsForeColor) . "
				" . Style::DoProperty("background-color", $this->FieldsBackColor) . "
				font-size: 100%;
				width: 100%;
				min-width: min(300px, 40vw);
				max-width: 85vw;
				border-radius: 0px 3px 3px 3px;
				border: var(--border-1);
				border-color: transparent;
				padding: 2px var(--size-0);
				border-radius: var(--radius-0);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
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
				padding: 2px var(--size-0);
				opacity: 0;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .field:hover label.title{
				font-size: 75%;
				" . Style::DoProperty("border-color", $this->FieldsBorderColor) . "
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .field:hover .input{
				font-size: 125%;
				outline: none;
				" . Style::DoProperty("border-color", $this->FieldsBorderColor) . "
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .field:hover label.description{
				font-size: 75%;
				opacity: 0.75;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
		");
	}
	public function GetFieldsTableStyle()
	{
		return Html::Style("
			.{$this->Name} .field{
				" . Style::DoProperty("min-width", $this->FieldsMinWidth) . "
				" . Style::DoProperty("min-height", $this->FieldsMinHeight) . "
				" . Style::DoProperty("max-width", $this->FieldsMaxWidth) . "
				" . Style::DoProperty("max-height", $this->FieldsMaxHeight) . "
				" . Style::DoProperty("width", $this->FieldsWidth) . "
				" . Style::DoProperty("height", $this->FieldsHeight) . "
				font-size: var(--size-1);
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
				padding: 5px var(--size-0);
			    margin: 5px;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
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
				border-bottom: var(--border-1);
				border-radius: var(--radius-0);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
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
				border: var(--border-1) var(--back-color-output);
				background-color: var(--fore-color-output);
				color: var(--back-color-output);
				position: absolute;
				padding: calc(var(--size-0) / 2);
				z-index: 1;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .field:hover .input{
				outline: none;
				" . Style::DoProperty("border-color", $this->FieldsBorderColor) . "
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .field:hover label.description{
				opacity: 1;
				display: block;
				font-size: 75%;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
					
			.{$this->Name} .group.buttons {
				text-align: center;
			}
		");
	}
	public function Get()
	{
		if (!auth($this->Access))
			return null;
		if (($res = $this->CheckBlock()) !== false)
			return $res;
		$name = $this->Name . "_Form";
		$src = $this->Action ?? $this->Path ?? \_::$Path;
		if (is_array($this->Children) && count($this->Children) > 0) {
			module("Field");
			$attr = $this->Method ? [] : ["disabled"];
			$this->Children = isEmpty($this->FieldsTypes)
				? iteration($this->Children, function ($v, $k) use ($attr) {
					if (is_integer($k))
						if ($v instanceof \Base)
							return $v->ToString();
						elseif (is_array($v))
							return Html::Field(
								type: grab($v, "Type"),
								key: grab($v, "Key"),
								value: grab($v, "Value"),
								description: grab($v, "Description"),
								options: grab($v, "Options"),
								title: grab($v, "Title"),
								wrapper: grab($v, "Wrapper") ?? true,
								attributes: [...(grab($v, "Attributes") ?? []), ...$v]
							);
						else
							return $v;
					else
						return Html::Field(
							type: null,
							key: $k,
							value: $v,
							attributes: $attr
						);
				})
				: iteration($this->FieldsTypes, function ($type, $k) use ($attr) {
					$v = getValid($this->Children, $k, null);
					if ($type === false)
						return null;
					if (is_integer($k) && isEmpty($type))
						return $v;
					else
						return Html::Field(
							type: $type,
							key: $k,
							value: $v,
							attributes: $attr
						);
				});
		}

		if (isValid($src)) {
			$this->Status = $this->Status ?? 200;
			if ($this->AllowDecoration)
				return
					Html::Rack(
						($this->AllowHeader ? Html::LargeSlot(
							Html::Media(null, $this->Image, ["class" => "form-image"]) .
							$this->GetHeader() .
							$this->GetTitle(["class" => "form-title"]) .
							$this->GetDescription(["class" => "form-description"]) .
							(isValid($this->BackLabel) ? Html::Link($this->BackLabel, $this->BackPath ?? \_::$Host, ["class" => "back-button"]) : "")
							, ["class" => "header", ...($this->AllowAnimate?["data-aos"=>"fade-left"]:[])]
						) : "") .
						Html::LargeSlot(
							Html::Form(
								Html::Rack(
									($this->AllowContent ? $this->GetContent() : "") .
									Convert::ToString($this->GetFields()),
									["class" => "group fields"]
								) .
								Html::Rack(Convert::ToString($this->GetButtons()), ["class" => "group buttons"])
								,
								$src,
								["Id" => $name, "Name" => $name, "enctype" => $this->EncType, "method" => $this->Method]
							) .
							($this->AllowFooter ? $this->GetFooter() : "")
							,
							["class" => "{$this->ContentClass} content", ...($this->AllowAnimate?["data-aos"=>"fade-right"]:[])]
						)
					);
			else
				return
					(
						$this->AllowHeader ?
						Html::Media(null, $this->Image, ["class" => "image"]) .
						$this->GetHeader() .
						$this->GetTitle(["class" => "form-title"]) .
						$this->GetDescription(["class" => "form-description"]) .
						(isValid($this->BackLabel) ? Html::Link($this->BackLabel, $this->BackPath ?? \_::$Host, ["class" => "back-button"]) : "")
						: ""
					) .
					Html::Form(
						($this->AllowContent ? $this->GetContent() : "") .
						Convert::ToString($this->GetFields()) .
						Html::Rack(Convert::ToString($this->GetButtons()), ["class" => "group buttons"])
						,
						$src,
						["Id" => $name, "Name" => $name, "enctype" => $this->EncType, "method" => $this->Method]
					, ...($this->AllowAnimate?["data-aos"=>"fade-left"]:[])) .
					($this->AllowFooter ? $this->GetFooter() : "");
		}
		return null;
	}
	public function GetHeader()
	{

	}
	public function GetFields()
	{
		if (isValid($this->ReCaptchaSiteKey)) {
			component("reCaptcha");
			yield \MiMFa\Component\reCaptcha::GetHtml($this->ReCaptchaSiteKey);
		}
	}
	public function GetButtons()
	{
		if (isValid($this->Buttons))
			yield $this->Buttons;
		if ($this->Method) {
			if($this->IsActive)yield (isValid($this->SubmitLabel) ? Html::SubmitButton($this->SubmitLabel, ["Name" => "submit", "class"=>"main"]) : "");
			yield (isValid($this->ResetLabel) ? Html::ResetButton($this->ResetLabel, ["Name" => "reset"]) : "");
		}
		yield (isValid($this->CancelLabel) ? Html::Button($this->CancelLabel, $this->CancelPath ?? \_::$Host, ["Name" => "cancel"]) : "");
	}
	public function GetFooter()
	{

	}
	public function GetScript()
	{
		return parent::GetScript() . Html::Script("
			$(document).ready(function () {
				" . ($this->UseAjax ? "handleForm('.{$this->Name} form', null, null, null, null, {$this->Timeout});" : "") . "
				$('.{$this->Name} :is(input, select, textarea)').on('focus', function () {
					$(this).parent().find('.{$this->Name} .input-group .text').css('outline-color', 'var(--fore-color-output)');
				});
				$('.{$this->Name} :is(input, select, textarea)').on('blur', function () {
					$(this).parent().find('.{$this->Name} .input-group .text').css('outline-color', 'var(--fore-color-output)');
				});
			});
		");
	}


	public function GetMessage($msg, ...$attr)
	{
		return Html::Result($msg, attributes: $attr);
	}
	public function GetSuccess($msg = null, ...$attr)
	{
		$this->Result = $this->Result ?? true;
		$this->Status = $this->Status ?? 200;
		return Html::Success($msg ?? $this->SuccessHandler, ...$attr);
	}
	public function GetWarning($msg = null, ...$attr)
	{
		$this->Result = $this->Result ?? null;
		$this->Status = $this->Status ?? 500;
		return Html::Warning($msg ?? $this->WarningHandler, ...$attr);
	}
	public function GetError($msg = null, ...$attr)
	{
		$this->Result = $this->Result ?? false;
		$this->Status = $this->Status ?? 400;
		return Html::Error($msg ?? $this->ErrorHandler, ...$attr);
	}

	public function Post()
	{
		if ($this->CheckAccess(reaction: true))
			return parent::Post();
		return null;
	}
	public function Put()
	{
		if ($this->CheckAccess(reaction: true))
			return parent::Put();
		return null;
	}
	public function Patch()
	{
		if ($this->CheckAccess(reaction: true))
			return parent::Patch();
		return null;
	}
	public function Delete()
	{
		if ($this->CheckAccess(reaction: true))
			return parent::Delete();
		return null;
	}

	public function Handler($received = null)
	{
		if (!isEmpty($received))
			try {
				return $this->Mail($received) ? $this->GetSuccess(null, ["class" => "page"]) : $this->GetError("Could not email the message!");
			} catch (\Exception $ex) {
				return $this->GetError($ex);
			}
		return null;
	}

	public function Mail($data)
	{
		library("Contact");
		$ex = null;
		if (count($data) > 0 && get($data, "ReceiverEmail") ?? $this->ReceiverEmail)
			if (
				\MiMFa\Library\Contact::SendHtmlEmail(
					grab($data, "SenderEmail") ?? $this->SenderEmail ?? \_::$Info->SenderEmail,
					grab($data, "ReceiverEmail") ?? $this->ReceiverEmail,
					grab($data, "MailSubject") ?? $this->MailSubject ?? (\_::$Domain . ": A new form submitted"),
					grab($data, "MailMessage") ?? $data,
					exception: $ex
				)
			)
				return true;
			elseif ($ex)
				throw $ex;
			else
				return false;
		return null;
	}

	public function GetSigning()
	{
		if ($this->Signing === true) {
			module("Modal");
			$module = new \MiMFa\Module\Modal();
			$module->Style = new Style();
			$module->Style->Width =
				$module->Width = "fit-content";
			$module->Style->Height =
				$module->Height = "fit-content";
			$module->Style->Position = "sticky";
			$module->Style->Margin = "auto";
			$module->Style->Top = "initial";
			$module->Style->Left = "initial";
			$module->Style->Right = "initial";
			$module->AllowFocus =
				$module->AllowShare =
				$module->AllowZoom =
				$module->AllowDownload = false;
			$module->Content = part(User::$InHandlerPath, ["ContentClass" => ""], print: false);
			if ($this->SigningLabel)
				return $module->Handle() . Html::Center(
					Html::Button(
						$this->SigningLabel,
						$module->ShowScript()
					),
					["style" => "padding: var(--size-5); background-color: #88888808;"]
				);
		} elseif ($this->Signing)
			return Html::Center(
				($this->SigningLabel ? Html::SubHeading($this->SigningLabel, User::$InHandlerPath) : "")
				. (is_bool($this->Signing) ? "" : Convert::By($this->Signing))
			);
	}
}