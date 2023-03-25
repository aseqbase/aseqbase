<?php namespace MiMFa\Module;
class Shortcuts extends Module{
	public $AllowTitle = false;
	public $AllowIcon = true;
	public $AllowImage = false;
	public $Items = null;

	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name; ?>{
				text-align: center;
			}
			
			.<?php echo $this->Name; ?> .item{
				font-size:  var(--Size-2);
			}
			.<?php echo $this->Name; ?> .item.active{
				border: var(--Border-1) var(--ForeColor-1);
				font-size:  var(--Size-2);
			}
		</style>
		<?php
	}

	public function Echo(){
		parent::Echo();
			$count = count($this->Items);
			if($count > 0){
				COMPONENT("FontAwesome");
				$comp = new \MiMFa\Component\FontAwesome();
				$comp->EchoStyle(".".$this->Name);
				$comp->EchoTechnologyStyle(".".$this->Name);
				for($i = 0; $i < $count; $i++){
					$item = $this->Items[$i];
					?>
					<a href="<?php echo isValid($item,'Link')?$item['Link']:''; ?>"
						class="item<?php echo (endsWith(\_::$URL,$item['Link'])?' active':'').(($this->AllowIcon && isValid($item,'Icon'))?' '.$item['Icon']:''); ?>"
						<?php if(isValid($item,"Attributes")) echo $item['Attributes']; ?>>
						<?php echo ($this->AllowTitle && isValid($item,'Title'))?$item['Title']:''; ?>
					</a>
				<?php } 
		}
	}
}
?>