<?php namespace MiMFa\Module;
class Gallery extends Module{
	public $Class = "container";
	public $Items = null;
	public $DefaultImage = null;
	public $DefaultName = null;
	public $DefaultDescription = null;
	public $DefaultDetails = null;
	public $DefaultLink = null;
	public $MaximumColumns = 4;
	public $BlurSize = "5px";
	public $MoreButtonLabel = "View";
	public $ThumbnailWidth = "100%";
	public $ThumbnailHeight = "fit-content";
	public $ThumbnailMinWidth = "100%";
	public $ThumbnailMinHeight = "11vmax";
	public $ThumbnailMaxWidth = "100%";
	public $ThumbnailMaxHeight = "50vh";

		
	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name; ?> {
			}

			.<?php echo $this->Name; ?>>*>.item {
				height: fit-content;
				background-Color: var(--BackColor-0);
				color: var(--ForeColor-0);
				margin: 3vmin 2vmin;
				padding: 0px;
				font-size:  var(--Size-0);
				box-shadow:  var(--Shadow-1);
				border-radius:  var(--Radius-1);
				border:  var(--Border-1) var(--ForeColor-2);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>>*>.item:hover{
				box-shadow:  var(--Shadow-2);
				border-radius:  var(--Radius-2);
				border:  var(--Border-1) var(--ForeColor-0);
				background-Color: var(--BackColor-1);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}

			/* Style the images inside the grid */
			.<?php echo $this->Name; ?>>*>.item>.image {
				margin: 0px;
				opacity: 1; 
				cursor: pointer; 
				width: <?php echo $this->ThumbnailWidth; ?>;
				height: <?php echo $this->ThumbnailHeight; ?>;
				min-height: <?php echo $this->ThumbnailMinHeight; ?>;
				min-width: <?php echo $this->ThumbnailMinWidth; ?>;
				max-height: <?php echo $this->ThumbnailMaxHeight; ?>;
				max-width: <?php echo $this->ThumbnailMaxWidth; ?>;
				overflow: hidden;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>>*>.item:hover>.image{
				background-Color: var(--BackColor-0);
				opacity: 0.6;
				<?php echo \MiMFa\Library\Style::UniversalProperty("filter","blur(".$this->BlurSize.")"); ?>;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>>*>.item>.description{
				background-Color: var(--BackColor-0);
				padding: 0px 1vmax;
				position: relative;
				bottom: 0px;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>>*>.item:hover>.description{
				background-Color: var(--BackColor-0);
				padding: 3vmin 1vmax;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>>*>.item>.description>h4{
				margin: 0px;
				font-size: var(--Size-0);
				line-height: normal;
				text-align: unset;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>>*>.item:hover>.description>h4{
				font-size: var(--Size-1);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>>*>.item>.description>*:not(h4){
				display: none;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?>>*>.item:hover>.description>*:not(h4){
				display: block;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
		</style>
		<?php
	}

	public function Echo(){
		MODULE("Image");
		$img = new Image();
		$img->Class = "image";
		$img->EchoStyle();

		MODULE("ImageModal");
		$viewer = new ImageModal();
		$viewer->Name = $this->Name."_".$viewer->Name;
		
		$i = 0;
		//echo "<div class='row'>";
		foreach($this->Items as $item) { 
			if($i % $this->MaximumColumns === 0)  echo "<div class='row'>";
			$p_icon = isValid($item,'Image')?$item['Image']:$this->DefaultImage;
			$p_name = isValid($item,'Name')?$item['Name']:(isValid($item,'Title')?$item['Title']:$this->DefaultName);
			$p_title = isValid($item,'Title')?$item['Title']:(isValid($item,'Name')?$item['Name']:$this->DefaultName);
			$p_description = isValid($item,'Description')?$item['Description']:$this->DefaultDescription;
			$p_details = (isValid($item,'Details')?$item['Details']:$this->DefaultDetails)??$p_description;
			$p_download = isValid($item,'Download')?$item['Download']:null;
			$p_link = (isValid($item,'Link')?$item['Link']:$this->DefaultLink)??(isEmpty($this->MoreButtonLabel)?null:$p_download??$p_icon);
			$img->Source = $p_icon;
			$clickact = "onclick=\"".$viewer->ShowScript("`$p_name`","$(`.".$this->Name.">*>.item-$i>.description>:last-child`).html()","`".($p_link??$p_icon)."`","``", "`".getFullUrl($p_download??$p_link??$p_icon)."`")."\"";
			$img->Attributes = $clickact;
			?>
			<div class="item item-<?php echo $i; ?> col-md" data-aos="zoom-up" data-aos-offset="-500">
				<?php $img->ReDraw(); ?>
				<div class="description">
					<h4><?php echo __($p_title,true,false); ?></h4>
					<p><?php echo __($p_description,true,false); ?></p>
					<?php if(isValid($p_link)) {?><button class="btn btn-outline btn-block" <?php echo $clickact; ?>><?php echo __($this->MoreButtonLabel); ?></button><?php } ?>
					<p class="hide"><?php echo __(($p_details??$p_description),true,false); ?></p>
				</div>
			</div>
			<?php 
				if(++$i % $this->MaximumColumns === 0) echo "</div>";
			}
			if($i % $this->MaximumColumns !== 0) 
				echo "</div>";
			
		$viewer->Draw();
	}

}
?>