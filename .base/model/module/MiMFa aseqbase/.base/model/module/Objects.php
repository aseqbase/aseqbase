<?php namespace MiMFa\Module;
class Objects extends Module{
	public $Class = "container";
	public $Items = null;
	public $ColumnsCount = 2;

	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
		.<?php echo $this->Name; ?>>.col{
			padding:1vmax;
		}
		</style>
		<?php 
	}
	public function Echo(){
			parent::Echo();
			$count = count($this->Items);
			if($count > 0){
				?>
				<?php for($i = 0; $i < $count; $i++){
					$item = $this->Items[$i];
					?>
					<?php if($i%$this->ColumnsCount == 0) echo"<div class='row'>"; ?>
						<div class="col">
							<?php if(isValid($item,'Image')) { ?>
								<img src="<?php echo $item['Image']; ?>" class="image">
							<?php } ?>
							<i <?php if(isValid($item,'Icon')) echo "class=\"".$item['Icon']."\""; ?> aria-hidden="true">
								<?php if(isValid($item,'Name')) echo __($item['Name']); ?>
							</i>
							<?php if(isValid($item,'Title')) { ?>
								<span class="title"><?php echo __($item['Title']); ?></span>
							<?php } ?>
							<?php if(isValid($item,'Description')) { ?>
								<p class="description"><?php echo __($item['Description']); ?></p>
							<?php } ?>
							<?php if(isValid($item,'Content')) echo $item['Content']; ?>
							<?php if(isValid($item,'Link')) { ?>
							<a <?php echo "href=\"".$item['Link']."\""; ?> target="_blank" class="btn btn-block btn-outline button">
								<?php if(isValid($item,'Value')) echo __($item['Value']); ?>
							</a>
							<?php } ?>
						</div>
					<?php if($i%$this->ColumnsCount == 0) echo"</div>"; ?>
				<?php } ?>
		<?php }
	}
}
?>
