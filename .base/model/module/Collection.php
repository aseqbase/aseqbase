<?php namespace MiMFa\Module;
/**
 * The main and basic module to implement any other collection module
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Collection extends Module{
	public $Class = "container-fluid";
	/**
     * An array of items, which contains a Key-Value based array of features
     * @var null|array<array<enum-string,mixed>>
     */
	public $Items = null;
	/**
     * The default Image Path
     * @var string|null
     */
	public $DefaultImage = null;
	/**
     * The default Name
     * @var string|null
     */
	public $DefaultTitle = null;
	/**
     * The default Description
     * @var string|null
     */
	public $DefaultDescription = null;
	/**
     * The default Link for the source file
     * @var string|null
     */
	public $DefaultLink = null;
	/**
     * Other default buttons
     * @var string|null
     */
	public $DefaultButtons = null;
	/**
     * Maximum shown Columns of items in each row
     * @var int<1,12>
     */
	public $MaximumColumns = 4;
	/**
     * The label text of More button
     * @var string|null
     */
	public $MoreButtonLabel = "Read More...";
	/**
     * Show items with an animation effect
     * @var bool
     */
	public $AllowAnimation = true;


	public function EchoStyle(){
		parent::EchoStyle();
?>
		<style>
			.<?php echo $this->Name; ?> .items .item{
				background-color: var(--BackColor-0); ?>99;
				color: var(--ForeColor-0);
				font-size: var(--Size-1);
				text-align: center;
				margin: 3vh;
    			padding: 0px;
				border: var(--Border-1) var(--ForeColor-4);
				border-radius: var(--Radius-1);
				box-shadow: var(--Shadow-1);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> .items .item:hover{
				background-color: var(--BackColor-1);
				color: var(--ForeColor-1);
				border-radius: var(--Radius-2);
				box-shadow: var(--Shadow-2);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> .items .item .image{
				margin: 2vmax;
				overflow: hidden;
				height: 5vmax;
				width: auto !important;
				width: 100%;
				max-width: 100%;
			}
			.<?php echo $this->Name; ?> .items .item .image img{
				width: auto !important;
				width:  100%;
				max-width: 100%;
			}
			.<?php echo $this->Name; ?> .items .item .description{
				background-color: var(--BackColor-0);
				color: var(--ForeColor-0);
				text-align: left;
				padding: 2vmin 2vmax;
				margin-bottom: 0px;
			}
			.<?php echo $this->Name; ?> .items .item .fa{
				padding: 20px;
				margin-bottom: 3vh;
				border: var(--Border-0) var(--ForeColor-0);
				border-radius: 50%;
			}
			.<?php echo $this->Name; ?> .items .item .btn{
				margin: 2vmax 5px;
			}
		</style>
		<?php
	}
	public function Echo(){
		parent::Echo();
		MODULE("Image");
		$img = new Image();
		$img->Class = "image";
		$img->EchoStyle();

		$i = 0;
		foreach($this->Items as $item) {
			if($i % $this->MaximumColumns === 0)  echo "<div class='row items'>";
			$p_image = getValid($item,'Image', $this->DefaultImage);
			$p_name = __(getValid($item,'Title')??getValid($item,'Name', $this->DefaultTitle),true,false);
			$p_description = __(getValid($item,'Description', $this->DefaultDescription));
			$p_link = getValid($item,'Link')??getValid($item,'Path', $this->DefaultLink);
			$p_buttons = getValid($item,'Buttons', $this->DefaultButtons);
			$img->Source = $p_image;
			?>
			<div class="item col-sm" <?php if($this->AllowAnimation) echo "data-aos='fade-up'";?>>
				<?php $img->ReDraw(); ?>
				<h4>
					<?php echo $p_name; ?>
				</h4>
				<div class="description">
					<?php echo $p_description; ?>
				</div>
				<?php echo $p_buttons;
				if(isValid($p_link)){ ?>
				<a class="btn" target="blank" href="<?php echo $p_link; ?>">
					<?php echo __($this->MoreButtonLabel); ?>
				</a>
				<?php }; ?>
			</div>
			<?php
			if(++$i % $this->MaximumColumns === 0) echo "</div>";
		}
		if($i % $this->MaximumColumns !== 0) echo "</div>";
	}

}
?>