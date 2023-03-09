<?php namespace MiMFa\Module;
class Contacts extends Module{
	public $Class = "container";
	public $Items = null;
	public $Location = null;

	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name; ?> ul.contacts li{
				padding: 10px;
			}
			.<?php echo $this->Name; ?> a.badge, a.badge:visited {
				background-color: var(--BackColor-1);
				color: var(--ForeColor-1);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> a.badge:hover {
				background-color: var(--ForeColor-1);
				color: var(--BackColor-1);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> .map{
				border: 10px solid var(--BackColor-3);
				box-shadow: var(--Shadow-2);
				border-radius: 5px;
			}
			.<?php echo $this->Name; ?> .map>iframe{
				display: inline-block;
				height: 100%;
				width:100%;
				min-height: 200px;
				padding:0px;
				margin:0px;
				<?php if(\_::$TEMPLATE->DarkMode) {
					echo \MiMFa\Library\Style::UniversalProperty("filter","invert(90%)");
				} ?>
			}
		</style>
		<?php 
	}
	public function Echo(){
			parent::Echo();
			COMPONENT("FontAwesome");
			$comp = new \MiMFa\Component\FontAwesome();
			$comp->EchoStyle(".shortcuts-list");
			$comp->EchoTechnologyStyle(".shortcuts-list");

			$count = count($this->Items);
			if($count > 0){
				?>
				<div class="row">
					<ul class="contacts col-lg-4">
						<?php for($i = 0; $i < $count; $i++){
							$item = $this->Items[$i];
							?>
							<li class="d-flex justify-content-between align-items-center">
								<i <?php if(isValid($item,'Icon')) echo "class=\"".$item['Icon']."\""; ?> aria-hidden="true">
									<?php if(isValid($item,'Name')) echo $item['Name'] ?>:
								</i>
								<a <?php if(isValid($item,'Link')) echo "href=\"".$item['Link']."\""; ?> target="_blank" class="badge badge-pill">
									<?php if(isValid($item,'Value')) echo $item['Value'] ?>:
								</a>
							</li>
						<?php } ?>
					</ul>
					<?php if(isValid($this->Location)) { ?>
					<div class="col-lg-8 map">
						<iframe src="<?php echo $this->Location; ?>"
							data-aos="filp-left" 
							data-src="<?php echo $this->Location; ?>"
							frameborder="0" 
							allowfullscreen="true"
							>
						</iframe>
					</div>
				<?php } ?>
				</div>
		<?php }
	}
}
?>
