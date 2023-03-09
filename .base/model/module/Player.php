<?php namespace MiMFa\Module;
class Player extends Module{
	public $Content = null;
	public $Source = null;
	public $AllowZoom = true;
	public $AllowShare = true;
	public $AllowDownload = true;


	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name; ?>>.controls{
				opacity: 0;
				position: absolute;
				top: 5px;
				right: 5px;
				font-size: var(--Size-1);
				color: var(--ForeColor-3);
				z-index: 1;
				<?php echo \MiMFa\Library\Style::UniversalProperty("text-stroke","1px var(--BackColor-3)"); ?>;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>>.controls>.button {
				text-align: center;
				display: block;
				padding: 1vh;
				cursor: pointer;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>>.controls>.button:hover {
				background-color: var(--BackColor-3);
				<?php echo \MiMFa\Library\Style::UniversalProperty("text-stroke","0px"); ?>;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}

			.<?php echo $this->Name; ?>:hover>.controls{
				opacity: 1;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}

			.<?php echo $this->Name; ?>>.content {
				height: 100%;
				height: -webkit-fill-available;
				width: 100%;
				padding: 0px;
				position: relative;
				text-align: center;
				overflow:auto;
			}
			
			.<?php echo $this->Name; ?>>.content::-webkit-scrollbar {
				background: var(--BackColor-3);
				width: 0px;
				height: 0px;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>:hover>.content::-webkit-scrollbar {
				width: 5px;
				height: 5px;
			}
			.<?php echo $this->Name; ?>>.content::-webkit-scrollbar:hover {
				//background: var(--BackColor-1);
				width: 10px;
				height: 10px;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>>.content::-webkit-scrollbar-track {
				border-radius: 1px;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>>.content::-webkit-scrollbar-track:hover {
				border-radius: 0px;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>>.content::-webkit-scrollbar-thumb {
				background: var(--ForeColor-3); 
				border-radius: 5px;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>>.content::-webkit-scrollbar-thumb:hover {
				background: var(--ForeColor-1);
				<?php if($this->AllowZoom) ?> cursor: "grab";
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>>.content>* {
				width: 100%;
				height: auto;
				min-height: fit-content;
				min-width: fit-content;
				z-index: 0;
			}

		</style>
		<?php
	}

	public function Echo(){
		$this->EchoElements($this->Content, $this->Source);
	}

	public function EchoElements($content = null, $source = null){
		$this->Source = $source;
		echo "<div class=\"controls\">";
		foreach($this->GetControls() as $btn) echo $btn;
		echo "</div>";
		echo $this->GetContent($content);
	}

	public function EchoScript(){
		parent::EchoScript();
		?>
		<script>
		<?php if($this->AllowZoom){ ?>
			let <?php echo $this->Name; ?>slider = null;
			let <?php echo $this->Name; ?>mouseDown = false;
			let <?php echo $this->Name; ?>startX, <?php echo $this->Name; ?>scrollLeft;
			let <?php echo $this->Name; ?>startY, <?php echo $this->Name; ?>scrollTop;

			let <?php echo $this->Name; ?>startDragging = function (e) {
				<?php echo $this->Name; ?>mouseDown = true;
				<?php echo $this->Name; ?>startX = e.pageX - <?php echo $this->Name; ?>slider.offsetLeft;
				<?php echo $this->Name; ?>startY = e.pageY - <?php echo $this->Name; ?>slider.offsetTop;
				<?php echo $this->Name; ?>scrollLeft = <?php echo $this->Name; ?>slider.scrollLeft;
				<?php echo $this->Name; ?>scrollTop = <?php echo $this->Name; ?>slider.scrollTop;
			};
			let <?php echo $this->Name; ?>stopDragging = function (event) {
				<?php echo $this->Name; ?>slider.style.cursor = "grab";
				<?php echo $this->Name; ?>mouseDown = false;
			};
			$(document).ready(
				function(){
					<?php echo $this->Name; ?>slider = document.querySelector('.<?php echo $this->Name; ?>>.content');

					<?php echo $this->Name; ?>slider.addEventListener('mousemove', (e) => {
						e.preventDefault();
						if(!<?php echo $this->Name; ?>mouseDown) { return; }
						<?php echo $this->Name; ?>slider.style.cursor = "grabbing";
						const x = e.pageX - <?php echo $this->Name; ?>slider.offsetLeft;
						const y = e.pageY - <?php echo $this->Name; ?>slider.offsetTop;
						const scrollx = x - <?php echo $this->Name; ?>startX;
						const scrolly = y - <?php echo $this->Name; ?>startY;
						<?php echo $this->Name; ?>slider.scrollLeft = <?php echo $this->Name; ?>scrollLeft - scrollx;
						<?php echo $this->Name; ?>slider.scrollTop = <?php echo $this->Name; ?>scrollTop - scrolly;
					});

					// Add the event listeners
					<?php echo $this->Name; ?>slider.addEventListener('mousedown', <?php echo $this->Name; ?>startDragging, false);
					<?php echo $this->Name; ?>slider.addEventListener('mouseup', <?php echo $this->Name; ?>stopDragging, false);
					<?php echo $this->Name; ?>slider.addEventListener('mouseleave', <?php echo $this->Name; ?>stopDragging, false);
				}
			);
		<?php } ?>

			let <?php echo $this->Name; ?>_Source = <?php echo isValid($this->Source)?("`".$this->Source."`"):"null"; ?>;

			function <?php echo $this->Name; ?>_Set(content, source = null){
				<?php echo $this->Name; ?>_Source = source??<?php echo $this->Name; ?>_Source??content;
				if(content !== null) $('.<?php echo $this->Name; ?>>.content').html(<?php echo $this->ContentScript("content"); ?>);
			}
			function <?php echo $this->Name; ?>_Clear(){
				$('.<?php echo $this->Name; ?>>.content').html('');
			}
			function <?php echo $this->Name; ?>_Focus(){
				$('.<?php echo $this->Name; ?>>.controls').toggle(<?php echo \_::$TEMPLATE->AnimationSpeed; ?>);
			}
			function <?php echo $this->Name; ?>_Reset(){
				let box = document.querySelector('.<?php echo $this->Name; ?>>.content>*');
				box.style.width = "inherit";
				box.style.height = "inherit";
				box.style.left = "inherit";
				box.style.top = "inherit";
				zoomX = 0;
			}
			let zoomX = 0;
			function <?php echo $this->Name; ?>_Zoom(){
				if(zoomX > 0) <?php echo $this->Name; ?>_Reset();
				else <?php echo $this->Name; ?>_ZoomIn(2);
			}
			function <?php echo $this->Name; ?>_ZoomIn(x = 1){
				let box = document.querySelector('.<?php echo $this->Name; ?>>.content>*');
				let width = box.offsetWidth;
				let height = box.offsetHeight;
				box.style.width = (width*(x*1.2))+"px";
				box.style.height = (height*(x*1.2))+"px";
				box.parentElement.scrollLeft += width*1.1;
				box.parentElement.scrollTop += height*1.1;
				zoomX+=x;
			}
			function <?php echo $this->Name; ?>_ZoomOut(x = 1){
				let box = document.querySelector('.<?php echo $this->Name; ?>>.content>*');
				let width = box.offsetWidth;
				let height = box.offsetHeight;
				box.style.width = (width/(x*1.2))+"px";
				box.style.height = (height/(x*1.2))+"px";
				box.parentElement.scrollLeft -= width*1.1;
				box.parentElement.scrollTop -= height*1.1;
				zoomX-=x;
			}
			function <?php echo $this->Name; ?>_Download(){
				open(<?php echo $this->Name; ?>_Source, "_blank");
			}
			function <?php echo $this->Name; ?>_Share(){
				share(<?php echo $this->Name; ?>_Source);
			}
		</script>
		<?php
	}

	public function GetContent($content){
		return "<div class=\"content\" ondblclick=\"".$this->FocusScript()."\">".$content."</div>";
	}
	public function GetControls(){
		if($this->AllowZoom)  yield  '<div class="fa fa-refresh button" onclick="'.$this->ResetScript().'"></div>';
		if($this->AllowZoom)  yield  '<div class="fa fa-plus button" onclick="'.$this->ZoomInScript().'"></div>';
		if($this->AllowZoom)  yield  '<div class="fa fa-minus button" onclick="'.$this->ZoomOutScript().'"></div>';
		if($this->AllowShare)  yield  '<div class="fa fa-share-alt button" onclick="'.$this->ShareScript().'"></div>';
		if($this->AllowDownload)  yield  '<div class="fa fa-download button" onclick="'.$this->DownloadScript().'"></div>';
	}
	
	public function ContentScript($content){
		return $content;
	}

	public function SetScript($content = "``", $source = "null"){
		return $this->Name."_Set(".
		$this->ReadyToScript($content).", ".
		$this->ReadyToScript($source).");";
	}
	public function ClearScript(){
		return $this->Name."_Clear();";
	}
	public function FocusScript(){
		return $this->Name."_Focus();";
	}
	public function ResetScript(){
		return $this->Name."_Reset();";
	}
	public function ZoomScript(){
		return $this->Name."_Zoom();";
	}
	public function ZoomInScript(){
		return $this->Name."_ZoomIn();";
	}
	public function ZoomOutScript(){
		return $this->Name."_ZoomOut();";
	}
	public function DownloadScript(){
		return $this->Name."_Download();";
	}
	public function ShareScript(){
		return $this->Name."_Share();";
	}

	public function ReadyToScript($text){
		return str_replace("'","&#8216;", str_replace("\"","&#8220;", $text??""));
	}
}
?>