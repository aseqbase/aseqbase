<?php namespace MiMFa\Module;
MODULE("Player");
class Modal extends Player{
	public $Class = "hide";
	public $ButtonsContent = null;
	public $AllowClose = true;
	public $AllowFocus = true;
	public $Width = "90vw";
	public $Height = "90vh";
	public $BackgroundShadow = "#00000099";

	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name; ?> {
				background-Color: var(--BackColor-3);
				Color: var(--ForeColor-3);
				border-radius: var(--Radius-2);
				border: var(--Border-1) var(--ForeColor-0);
				height: <?php echo $this->Height; ?>;
				width: <?php echo $this->Width; ?>;
				position: fixed;
				top: calc((100% - <?php echo $this->Height; ?>)/2);
				bottom: calc((100% - <?php echo $this->Height; ?>)/2);
				left: calc((100% - <?php echo $this->Width; ?>)/2);
				right: calc((100% - <?php echo $this->Width; ?>)/2);
				z-index: 999999999;
				overflow:hidden;
				box-shadow: var(--Shadow-5);
			}
			/* Expanding image detail */
			.<?php echo $this->Name; ?>>.body>.detail {
				opacity:0;
				Color: var(--ForeColor-0);
				position: absolute;
				bottom: 0px;
				width: 100%;
				z-index: 1;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}

			.<?php echo $this->Name; ?>:hover>.body>.detail {
				opacity: 1;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}

			.<?php echo $this->Name; ?>>.body>.detail>.title {
				border-top: var(--Border-1) var(--ForeColor-0);
				border-right: var(--Border-1) var(--ForeColor-0);
				background-Color: <?php echo \_::$TEMPLATE->BackColor(0);?>ee;
				Color: var(--ForeColor-0);
				font-size: var(--Size-1);
				text-align: unset;
			    margin-bottom: -2vmin;
 			    width: fit-content;
				position:relative;
 			    padding: 1vmin 3vmax;
				box-shadow: var(--Shadow-1);
			}

			/* Expanding image text */
			.<?php echo $this->Name; ?>>.body>.detail>.description {
				background-Color: <?php echo \_::$TEMPLATE->BackColor(0); ?>ee;
				Color: var(--ForeColor-0);
				font-size: var(--Size-1);
				border-top: var(--Border-2) var(--ForeColor-0);
				border-radius: var(--Radius-2);
				width: 100%;
				padding: 3vmin;
				text-align: justify;
			}
			<?php if(isValid($this->BackgroundShadow)){ ?>
			.<?php echo $this->Name; ?>-background-screen {
				background-color: <?php echo $this->BackgroundShadow; ?>;
				z-index:0;
			}
			<?php } ?>
		</style>
		<?php
	}

	public function Echo(){
		$this->EchoElements($this->Title, $this->Description, $this->Content, $this->ButtonsContent, $this->Source);
	}

	public function EchoElements($title = null, $description = null, $content = null, $buttonsContent = null, $source = null){
		parent::EchoElements($content, $source);
		echo "<div class=\"body\">";
			echo "<div class='detail'>";
				echo "<h4 class=\"title\" ondblclick=\"".$this->ModalFocusScript()."\">".__($title)."</h4>";
				echo "<div class=\"description\" ondblclick=\"".$this->ModalFocusScript()."\">".__($description)."</div>";
			echo "</div>";
		echo $this->GetButtons($buttonsContent);
		echo "</div>";
	}

	public function EchoScript(){
		parent::EchoScript();
		?>
		<script>
			function <?php echo $this->Name; ?>_Show(title = null, description = null, content = null, buttonsContent = null, source = null){
				if(content === null) {
					content = title;
					title = null;
				}
				<?php echo $this->Name; ?>_Set(content, source);
				if(isEmpty(title)) $('.<?php echo $this->Name; ?>>.body>.detail>.title').hide();
				else $('.<?php echo $this->Name; ?>>.body>.detail>.title').show().text(title);
				if(isEmpty(description)) $('.<?php echo $this->Name; ?>>.body>.detail>.description').hide();
				else $('.<?php echo $this->Name; ?>>.body>.detail>.description').show().text(description);
				if(buttonsContent !== null) $('.<?php echo $this->Name; ?>>.buttons').html(<?php echo $this->ButtonsScript("buttonsContent"); ?>);
				$('.<?php echo $this->Name; ?>,.<?php echo $this->Name; ?>-background-screen').removeClass('hide');
				$('.<?php echo $this->Name; ?>,.<?php echo $this->Name; ?>-background-screen').fadeIn(<?php echo \_::$TEMPLATE->AnimationSpeed; ?>);
				scrollTo('.<?php echo $this->Name; ?>');
			}
			function <?php echo $this->Name; ?>_Hide(){
				<?php echo $this->ClearScript(); ?>;
				$('.<?php echo $this->Name; ?>,.<?php echo $this->Name; ?>-background-screen').fadeOut(<?php echo \_::$TEMPLATE->AnimationSpeed; ?>);
				$('.<?php echo $this->Name; ?>>.body>.detail>.title').text('');
				$('.<?php echo $this->Name; ?>>.body>.detail>.description').text('');
				$('.<?php echo $this->Name; ?>>.buttons').html('');
			}
			function <?php echo $this->Name; ?>_ModalFocus(){
				$('.<?php echo $this->Name; ?>>.body>.detail').slideToggle(<?php echo \_::$TEMPLATE->AnimationSpeed; ?>);
				<?php echo $this->FocusScript(); ?>;
			}
			function <?php echo $this->Name; ?>_ModalInfo(){
				$('.<?php echo $this->Name; ?>>.body>.detail').slideToggle(<?php echo \_::$TEMPLATE->AnimationSpeed; ?>);
			}
		</script>
		<?php
	}


	public function GetContent($content){
		return "<div class=\"content\" ".($this->AllowZoom?("onclick=\"".$this->ModalFocusScript()."\" ondblclick=\"".$this->ZoomScript()."\""):("ondblclick=\"".$this->ModalFocusScript()."\"")).">".$content."</div>";
	}
	public function GetControls(){
		yield from parent::GetControls();
		if($this->AllowFocus) yield'<div class="fa fa-info button" onclick="'.$this->ModalInfoScript().'"></div>';
		if($this->AllowClose) yield '<div class="fa fa-close button" onclick="'.$this->HideScript().'"></div>';
	}
	public function GetButtons($buttonsContent){
		return "<div class=\"buttons\">".$buttonsContent."</div>";
	}

	public function PreDraw(){
		if(isValid($this->BackgroundShadow)) echo "<div class=\"background-screen ".$this->Name."-background-screen hide\" onclick=\"".($this->AllowClose?$this->HideScript():"")."\"></div>";
	}

	public function ButtonsScript($buttonsContent){
		return $buttonsContent;
	}

	public function ShowScript($title = "``", $description = "``", $content = "``", $buttonsContent = "``", $source = "null"){
		return $this->Name."_Show(".
		$this->ReadyToScript($title).", ".
		$this->ReadyToScript($description).", ".
		$this->ReadyToScript($content).", ".
		$this->ReadyToScript($buttonsContent).", ".
		$this->ReadyToScript($source).");";
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