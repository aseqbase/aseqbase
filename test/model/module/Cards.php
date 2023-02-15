<?php namespace MiMFa\Module;
class Cards extends Module{
	public $Name = "Cards";
	public $Class = "container";
	public $Items = null;
	public $DefaultIcon = null;
	public $DefaultName = null;
	public $DefaultDescription = null;
	public $DefaultDetails = null;
	public $DefaultLink = null;
	public $MaximumColumns = 4;
	public $MoreButtonLabel = "Read More...";

		
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
				border: var(--Border-1) var(--ForeColor-0);
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
				width: auto !important;
				width:  100%;
				max-width: 100%;
			}
			.<?php echo $this->Name; ?> .items .item .image img{
				width: auto !important;
				width:  100%;
				max-width: 100%;
			}
			.<?php echo $this->Name; ?> .items .item .details{
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
		$i = 0;
		foreach($this->Items as $item) { 
			if($i % $this->MaximumColumns === 0)  echo "<div class='row items'>";
			$p_icon = isValid($item,'Image')?$item['Image']:$this->DefaultIcon;
			$p_name = __(isValid($item,'Name')?$item['Name']:$this->DefaultName,true,false);
			$p_description = __(isValid($item,'Description')?$item['Description']:$this->DefaultDescription);
			$p_details = __(isValid($item,'Details')?$item['Details']:$this->DefaultDetails);
			$p_link = isValid($item,'Link')?$item['Link']:$this->DefaultLink;
			?>
			<div class="item col-sm" data-aos="fade-up">
				<div class="image">
					<img src="<?php echo $p_icon; ?>" alt="<?php echo $p_name; ?>" style="width:100%">
				</div>
				<h4><?php echo $p_name; ?></h4>
				<p class="details">
					<?php echo $p_description; ?>
					<br>
					<?php echo $p_details; ?>
				</p>
					<a class="btn" target="blank" href="<?php echo $p_link; ?>"><?php echo __($this->MoreButtonLabel); ?></a>
			</div>
			<?php 
			if(++$i % $this->MaximumColumns === 0) echo "</div>";
		}
		if($i % $this->MaximumColumns !== 0) echo "</div>";
	}
}
?>