<?php namespace MiMFa\Module;
class Copyright extends Module{
	public $Title = "MiMFa";
	public $Description = "Powered By: ";
	public $Source = "http://mimfa.net";

	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name; ?>, .<?php echo $this->Name; ?> a, .<?php echo $this->Name; ?> a:visited{
				text-align: center;
				font-size: var(--Size-0);
				text-decoration: none;
			}
		</style>
		<?php 
	}

	public function Echo(){
		echo "<span>".$this->Description."</span><a href=\"".$this->Source."\">".$this->Title."</a>";
	}
}
?>
