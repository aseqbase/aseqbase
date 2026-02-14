<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
use MiMFa\Library\Convert;
use MiMFa\Library\Script;

module("Player");
class Modal extends Player{
	public $Class = "hide";
	public $ButtonsContent = null;
	public $FitContent = false;
	public $AllowClose = true;
	public $AllowFocus = true;
	public $Width = "90vw";
	public $Height = "90vh";
	public $BackgroundMask = "#00000099";

	public function GetStyle(){
        yield parent::GetStyle();
        yield Struct::Style("
			.{$this->MainClass} {
				background-Color: var(--back-color-special);
				Color: var(--fore-color-special);
				border-radius: var(--radius-2);
				border: var(--border-1) var(--fore-color);
				".($this->FitContent?
				"max-height: {$this->Height};
				max-width: {$this->Width};":
				"height: {$this->Height};
				width: {$this->Width};
				top: calc((100% - {$this->Height})/2);
				bottom: calc((100% - {$this->Height})/2);
				left: calc((100% - {$this->Width})/2);
				right: calc((100% - {$this->Width})/2);")."
				position: fixed;
				z-index: 999999999;
				overflow:hidden;
				box-shadow: var(--shadow-5);
			}
			/* Expanding image detail */
			.{$this->MainClass}>.body>.detail {
				opacity:0;
				Color: var(--fore-color);
				position: absolute;
				bottom: 0px;
				width: 100%;
				z-index: 1;
				".\MiMFa\Library\Style::UniversalProperty("transition","var(--transition-1)").";
			}

			.{$this->MainClass}:hover>.body>.detail {
				opacity: 1;
				".\MiMFa\Library\Style::UniversalProperty("transition","var(--transition-1)").";
			}

			.{$this->MainClass}>.body>.detail>.title {
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
			.{$this->MainClass}>.body>.detail>.description {
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
			.{$this->MainClass}-background-mask {
				background: {$this->BackgroundMask};
				width: 100%;
				z-index:1;
			}
			":"")
			);
	}

	public function GetInner(){
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
		yield parent::GetScript();
		yield Struct::Script("
			function {$this->MainClass}_Initialize(title = null, description = null, content = null, buttonsContent = null, source = null){
				if(content === null) {
					content = title;
					title = null;
				}
				{$this->MainClass}_Set(content, source);
				if(isEmpty(title)) _('.{$this->MainClass}>.body>.detail>.title').hide();
				else _('.{$this->MainClass}>.body>.detail>.title').show().text(title);
				if(isEmpty(description)) _('.{$this->MainClass}>.body>.detail>.description').hide();
				else _('.{$this->MainClass}>.body>.detail>.description').show().text(description);
				if(buttonsContent !== null) _('.{$this->MainClass}>.buttons').html({$this->ButtonsScript('${buttonsContent}')});
				_('.{$this->MainClass},.{$this->MainClass}-background-mask').removeClass('hide');
				_('.{$this->MainClass},.{$this->MainClass}-background-mask').fadeIn(".\_::$Front->AnimationSpeed.");
				//scrollThere('.{$this->MainClass} .body');
			}
			function {$this->MainClass}_Show(){
				{$this->InitializeScript()}
			}
			function {$this->MainClass}_Hide(){
				{$this->ClearScript()};
				_('.{$this->MainClass},.{$this->MainClass}-background-mask').fadeOut(".\_::$Front->AnimationSpeed.");
				_('.{$this->MainClass}>.body>.detail>.title').text('');
				_('.{$this->MainClass}>.body>.detail>.description').text('');
				_('.{$this->MainClass}>.buttons').html('');
			}
			function {$this->MainClass}_ModalFocus(){
				_('.{$this->MainClass}>.body>.detail').slideToggle(".\_::$Front->AnimationSpeed.");
				{$this->FocusScript()};
			}
			function {$this->MainClass}_ModalInfo(){
				_('.{$this->MainClass}>.body>.detail').slideToggle(".\_::$Front->AnimationSpeed.");
			}
		");
	}


	public function GetContents($content){
		return "<div class=\"content\" ".($this->AllowZoom?("onclick=\"".$this->ModalFocusScript()."\" ondblclick=\"".$this->ZoomScript()."\""):("ondblclick=\"".$this->ModalFocusScript()."\"")).">".
		Convert::ToString($content).
		"</div>";
	}
	public function GetControls(){
		if($this->AllowClose) yield Struct::Icon("close", $this->HideScript(), ["class"=>"button be square circle"]);
		if($this->AllowFocus) yield Struct::Icon("info", $this->ModalInfoScript(), ["class"=>"button"]);
		yield from parent::GetControls();
	}
	public function GetButtons($buttonsContent){
		return "<div class=\"buttons\">".Convert::ToString($buttonsContent)."</div>";
	}

	public function BeforeHandle(){
		if(isValid(object: $this->BackgroundMask)) return "<div class=\"background-mask ".$this->MainClass."-background-mask view hide\" onclick=\"".($this->AllowClose?$this->HideScript():"")."\"></div>";
	}

	public function ButtonsScript($buttonsContent){
		return Script::Convert($buttonsContent);
	}

	public function InitializeScript($title = null, $description = null, $content = null, $buttonsContent = null, $source = null){
		return $this->MainClass."_Initialize(".
		Script::Convert($title??$this->Title).", ".
		Script::Convert($description??$this->Description).", ".
		Script::Convert($content??$this->Content).", ".
		Script::Convert($buttonsContent??$this->ButtonsContent).", ".
		Script::Convert($source??$this->Source).");";
	}
	
	public function Show($content = null){
		return script($this->ShowScript($content));
	}
	public function ShowScript($content = null){
		return ($content?$this->InitializeScript(content: $content):"").$this->MainClass."_Show();";
	}
	public function Hide(){
		return response($this->HideScript());
	}
	public function HideScript(){
		return $this->MainClass."_Hide();";
	}
	public function ModalFocusScript(){
		return $this->MainClass."_ModalFocus();";
	}
	public function ModalInfoScript(){
		return $this->MainClass."_ModalInfo();";
	}
}
?>