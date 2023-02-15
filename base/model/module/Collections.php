<?php namespace MiMFa\Module;
class Collections extends Module{
	public $Name = "Collections";
	public $Class = "container";
	public $Items = array();
	public $MoreButtonLabel = "More";

	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name; ?> .item{
				display: inline-flex;
				background-color: var(--BackColor-0);
				color: var(--ForeColor-0);
				details-shadow: var(--Shadow-1);
				border-radius: 3px 100px 100px 3px;
				border: var(--Border-1) var(--BackColor-1);
				padding: 10px 20px 10px 0px;
				margin:10px;
				margin-left: 60px;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> .item:hover{
				border-color:var(--ForeColor-2);
				background-color: var(--BackColor-1);
				color: var(--ForeColor-1);
				details-shadow: var(--Shadow-2);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> .item .image{
				background-color: var(--BackColor-1);
				vertical-align:middle;
				width: 100px;
				height: 100px;
				margin-left: -50px;
				border-left: var(--Border-1) var(--ForeColor-2);
				border-radius: 50%;
			}
			.<?php echo $this->Name; ?> .item .details {
				width: 100%;
				margin-left: 20px;
			}
			.<?php echo $this->Name; ?> .item .buttons {
				width: auto;
				text-align: right;
			}
			.<?php echo $this->Name; ?> .buttons a{
				details-shadow: var(--Shadow-1);
				border: none;
				padding: 5px 10px;
				border-radius: 3px;
			}
			.<?php echo $this->Name; ?> .buttons a:hover{
				details-shadow: var(--Shadow-2);
			}
		</style>
		<?php 
	}
	public function Echo(){
		parent::Echo();
		?>
			<?php
				$i = true;
				foreach($this->Items as $item) { ?>
					<?php if($i) { ?> <div class="row"><?php } ?>
						<div class="col-md item">
							<?php if(isValid($item,'Image')){ ?>
								<img class="image" src="<?php echo $item['Image']; ?>">
							<?php } ?>
							<div class="details">
								<?php if(isValid($item,'Name')){ ?>
									<b class="title"><?php echo __($item['Name'],true,false); ?></b>
								<?php } ?>
								<div class="row">
									<?php if(isValid($item,'Description')){ ?>
										<div class="description col-sm">
												<?php echo __($item['Description'],true,false); ?>
										</div>
									<?php } ?>
									<?php if(isValid($item,'Link')){ ?>
										<div class="buttons col-sm-4">
											<a href="<?php echo $item['Link']; ?>" Target="blank" class="btn" ><?php echo __($this->MoreButtonLabel); ?>More</a>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php if(!$i) { ?> </div><?php } ?>
			<?php 
					$i = !$i;
				} ?>
			<?php if(!$i) { ?> </div><?php } ?>
		<?php
	}
}
?>