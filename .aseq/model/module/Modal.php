<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Convert;
use MiMFa\Library\Script;

module("Player");
class Modal extends Player{
	public $Class = "hide";
	public $ButtonsContent = null;
	public $AllowClose = true;
	public $AllowFocus = true;
	public $Width = "90vw";
	public $Height = "90vh";
	public $BackgroundMask = "#00000099";

	public function GetStyle(){
		return parent::GetStyle().Html::Style("
			.{$this->Name} {
				background-Color: var(--back-color-special);
				Color: var(--fore-color-special);
				border-radius: var(--radius-2);
				border: var(--border-1) var(--fore-color);
				height: {$this->Height};
				width: {$this->Width};
				position: fixed;
				top: calc((100% - {$this->Height})/2);
				bottom: calc((100% - {$this->Height})/2);
				left: calc((100% - {$this->Width})/2);
				right: calc((100% - {$this->Width})/2);
				z-index: 999999999;
				overflow:hidden;
				box-shadow: var(--shadow-5);
			}
			/* Expanding image detail */
			.{$this->Name}>.body>.detail {
				opacity:0;
				Color: var(--fore-color);
				position: absolute;
				bottom: 0px;
				width: 100%;
				z-index: 1;
				".\MiMFa\Library\Style::UniversalProperty("transition","var(--transition-1)").";
			}

			.{$this->Name}:hover>.body>.detail {
				opacity: 1;
				".\MiMFa\Library\Style::UniversalProperty("transition","var(--transition-1)").";
			}

			.{$this->Name}>.body>.detail>.title {
				border-top: var(--border-1) var(--fore-color);
				border-right: var(--border-1) var(--fore-color);
				background-Color: var(--back-color);
				Color: var(--fore-color);
				font-size: var(--size-1);
				text-align: unset;
			    margin-bottom: -2vmin;
 			    width: fit-content;
				position:relative;
 			    padding: 1vmin 3vmax;
				box-shadow: var(--shadow-1);
			}

			/* Expanding image text */
			.{$this->Name}>.body>.detail>.description {
				background-Color: var(--back-color);
				Color: var(--fore-color);
				font-size: var(--size-1);
				border-top: var(--border-2) var(--fore-color);
				border-radius: var(--radius-2);
				width: 100%;
				padding: 3vmin;
				text-align: justify;
			}
			".(
			isValid($this->BackgroundMask)?"
			.{$this->Name}-background-mask {
				background: {$this->BackgroundMask};
				width: 100%;
				z-index:1;
			}
			":"")
			);
	}

	public function Get(){
		return Convert::ToString($this->GetElements($this->Title, $this->Description, $this->Content, $this->ButtonsContent, $this->Source));
	}

	public function GetElements($title = null, $description = null, $content = null, $buttonsContent = null, $source = null){
		yield from parent::GetElements($content, $source);
		yield "<div class=\"body\">
			<div class='detail'>
				<h4 class=\"title\" ondblclick=\"{$this->ModalFocusScript()}\">".__($title)."</h4>
				<div class=\"description\" ondblclick=\"{$this->ModalFocusScript()}\">".__($description)."</div>
			</div>
			{$this->GetButtons($buttonsContent)}
		</div>";
	}

	public function GetScript(){
		return parent::GetScript().Html::Script("
			function {$this->Name}_Initialize(title = null, description = null, content = null, buttonsContent = null, source = null){
				if(content === null) {
					content = title;
					title = null;
				}
				{$this->Name}_Set(content, source);
				if(isEmpty(title)) $('.{$this->Name}>.body>.detail>.title').hide();
				else $('.{$this->Name}>.body>.detail>.title').show().text(title);
				if(isEmpty(description)) $('.{$this->Name}>.body>.detail>.description').hide();
				else $('.{$this->Name}>.body>.detail>.description').show().text(description);
				if(buttonsContent !== null) $('.{$this->Name}>.buttons').html({$this->ButtonsScript('${buttonsContent}')});
				$('.{$this->Name},.{$this->Name}-background-mask').removeClass('hide');
				$('.{$this->Name},.{$this->Name}-background-mask').fadeIn(".\_::$Front->AnimationSpeed.");
				scrollThere('.{$this->Name}');
			}
			function {$this->Name}_Show(){
				{$this->InitializeScript()}
			}
			function {$this->Name}_Hide(){
				{$this->ClearScript()};
				$('.{$this->Name},.{$this->Name}-background-mask').fadeOut(".\_::$Front->AnimationSpeed.");
				$('.{$this->Name}>.body>.detail>.title').text('');
				$('.{$this->Name}>.body>.detail>.description').text('');
				$('.{$this->Name}>.buttons').html('');
			}
			function {$this->Name}_ModalFocus(){
				$('.{$this->Name}>.body>.detail').slideToggle(".\_::$Front->AnimationSpeed.");
				{$this->FocusScript()};
			}
			function {$this->Name}_ModalInfo(){
				$('.{$this->Name}>.body>.detail').slideToggle(".\_::$Front->AnimationSpeed.");
			}
		");
	}


	public function GetContents($content){
		return "<div class=\"content\" ".($this->AllowZoom?("onclick=\"".$this->ModalFocusScript()."\" ondblclick=\"".$this->ZoomScript()."\""):("ondblclick=\"".$this->ModalFocusScript()."\"")).">".
		Convert::ToString($content).
		"</div>";
	}
	public function GetControls(){
		if($this->AllowClose) yield Html::Icon("close", $this->HideScript(), ["class"=>"button be square circle"]);
		if($this->AllowFocus) yield Html::Icon("info", $this->ModalInfoScript(), ["class"=>"button"]);
		yield from parent::GetControls();
	}
	public function GetButtons($buttonsContent){
		return "<div class=\"buttons\">".Convert::ToString($buttonsContent)."</div>";
	}

	public function BeforeHandle(){
		if(isValid(object: $this->BackgroundMask)) return "<div class=\"background-mask ".$this->Name."-background-mask view hide\" onclick=\"".($this->AllowClose?$this->HideScript():"")."\"></div>";
	}

	public function ButtonsScript($buttonsContent){
		return Script::Convert($buttonsContent);
	}

	public function InitializeScript($title = null, $description = null, $content = null, $buttonsContent = null, $source = null){
		return $this->Name."_Initialize(".
		Script::Convert(obj: $title??$this->Title).", ".
		Script::Convert($description??$this->Description).", ".
		Script::Convert($content??$this->Content).", ".
		Script::Convert($buttonsContent??$this->ButtonsContent).", ".
		Script::Convert($source??$this->Source).");";
	}
	public function ShowScript(){
		return $this->Name."_Show();";
	}
	public function HideScript(){
		return $this->Name."_Hide();";
	}
	public function ModalFocusScript(){
		return $this->Name."_ModalFocus();";
	}
	public function ModalInfoScript(){
		return $this->Name."_ModalInfo();";
	}
}
?>