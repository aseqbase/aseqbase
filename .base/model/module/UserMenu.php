<?php
namespace MiMFa\Module;
use MiMFa\Library\User;
class UserMenu extends Module{
	public $Items = null;
	public $AllowLabels = false;
	public $AllowAnimate = true;
	public $AllowMiddle = true;
	public $AllowChangeColor = true;
		
	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<!--<style>
			.<?php echo $this->Name; ?>{
			}
		</style>-->
		<?php
	}
	public function Echo(){
		parent::Echo();
		if($this->Items == null){
			$acc = getAccess();
			if($acc < 1)
				$this->Items = array(
					array("Name"=>"Sign In", "Link"=>User::$InHandlerPath),
					array("Name"=>"Sign Up", "Link"=>User::$UpHandlerPath)
				);
			else
				$this->Items = array(
					array("Name"=>"Profile", "Link"=>User::$ProfileHandlerPath),
					array("Name"=>"Sign Out", "Link"=>User::$OutHandlerPath)
				);
        }
		$count = count($this->Items);
		if($count > 0){
			for($i = 0; $i < $count; $i++){
				?>
				<a class="btn btn-primary" <?php echo isValid($this->Items[$i],"Attributes")?$this->Items[$i]["Attributes"]:"" ?> <?php echo isValid($this->Items[$i],'Link')?"href=\"".$this->Items[$i]['Link']."\"":"" ?>>
					<div style="<?php echo isValid($this->Items[$i],'Image')?("background-image: url('".$this->Items[$i]['Image']."')"):""; ?>">
						<?php echo __($this->Items[$i]['Name']); ?>
					</div>
				</a>
			<?php 
			}
		}
	}
}
?>
