<?php
namespace MiMFa\Module;
class TemplateButton extends Module{
	public $LightIcon = "fa fa-sun";
	public $DarkIcon = "fa fa-moon";
	public $LightLabel = "";
	public $DarkLabel = "";
	public $LightRequest = "LightMode";
	public $DarkRequest = "DarkMode";

	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
            .<?php echo $this->Name; ?> i {
				cursor: pointer;
				padding: 8px;
            }
		</style>
		<?php
	}
	
	public function Echo(){
		$this->EchoTitle();
		$this->EchoDescription();
		if(\_::$TEMPLATE->DarkMode) : ?>
	            <i class="<?php echo $this->LightIcon; ?>" onclick="load(`?<?php echo $this->LightRequest; ?>=true&<?php echo $this->DarkRequest; ?>=!`)"><?php echo $this->LightLabel; ?></i>
	            <?php else: ?>
	            <i class="<?php echo $this->DarkIcon; ?>" onclick="load(`?<?php echo $this->DarkRequest; ?>=true&<?php echo $this->LightRequest; ?>=!`)"><?php echo $this->DarkLabel; ?></i>
	            <?php endif;
		$this->EchoContent();
		return true;
    }
}
?>