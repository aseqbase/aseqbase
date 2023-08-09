<?php
namespace MiMFa\Module;
MODULE("Collection");
/**
 * To show a gallery of images
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Gallery extends Collection{
	public $Class = "container";
	/**
     * The default Content HTML
     * @var string|null
     */
	public $DefaultContent = null;
	/**
     * The default Path for more button reference
     * @var string|null
     */
	public $DefaultPath = null;
	/**
     * The size of Blur effect
     * @var string
     */
	public $BlurSize = "5px";
	/**
     * The label text of More button
     * @var string|null
     */
	public $MoreButtonLabel = "View";

	/**
     * The Width of thumbnail preshow
     * @var string
     */
	public $ThumbnailWidth = "100%";
	/**
     * The Height of thumbnail preshow
     * @var string
     */
	public $ThumbnailHeight = "fit-content";
	/**
     * The Minimum Width of thumbnail preshow
     * @var string
     */
	public $ThumbnailMinWidth = "100%";
	/**
     * The Minimum Height of thumbnail preshow
     * @var string
     */
	public $ThumbnailMinHeight = "11vmax";
    /**
     * The Maximum Width of thumbnail preshow
     * @var string
     */
	public $ThumbnailMaxWidth = "100%";
	/**
     * The Maximum Height of thumbnail preshow
     * @var string
     */
	public $ThumbnailMaxHeight = "50vh";

	public function EchoStyle(){
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
			$p_image = getValid($item,'Image', $this->DefaultImage);
			$p_name = getValid($item,'Name')??getValid($item,'Title', $this->DefaultTitle);
			$p_title = getValid($item,'Title', $p_name);
			$p_description = getValid($item,'Description', $this->DefaultDescription);
			$p_content = getValid($item,'Content',$this->DefaultContent)??$p_description;
			$p_download = getValid($item,'Download');
			$p_link = getValid($item,'Link',$this->DefaultLink)??(isEmpty($this->MoreButtonLabel)?null:$p_download??$p_image);
			$p_path = getValid($item,'Path', $this->DefaultPath)??$p_link;
			$p_buttons = getValid($item,'ButtonsContent', $this->DefaultButtons);
			$img->Source = $p_image;
			$clickact = "onclick=\"".$viewer->ShowScript("`$p_title`","$(`.".$this->Name.">*>.item-$i>.description>:last-child`).html()","`".($p_link??$p_path??$p_image)."`","`$p_buttons`", "`".getFullUrl($p_download??$p_path??$p_link??$p_image)."`")."\"";
			$img->Attributes = $clickact;
			?>
			<div class="item item-<?php echo $i; ?> col-md" <?php if($this->AllowAnimation) echo " data-aos='zoom-up' data-aos-offset='-500'";?>>
    <?php $img->ReDraw(); ?>
    <div class="description">
        <h4>
            <?php echo __($p_name,true,false); ?>
        </h4>
        <p>
            <?php echo __($p_description,true,false); ?>
        </p>
        <?php if(isValid($p_path)) {?><button class="btn btn-outline btn-block" <?php echo $clickact; ?>>
            <?php echo __($this->MoreButtonLabel); ?>
        </button><?php } ?>
        <div class="hide">
            <?php echo __($p_content??$p_description,true,false); ?>
        </div>
    </div>
</div>
			<?php 
			if(++$i % $this->MaximumColumns === 0) echo "</div>";
		}

		if($i % $this->MaximumColumns !== 0)  echo "</div>";
			
		$viewer->Draw();
	}
}
?>