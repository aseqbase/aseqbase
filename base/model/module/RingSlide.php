<?php namespace MiMFa\Module;
class RingSlide extends Module{
	public $Name = "RingSlide";
	public $Class = "row";
	public $Image = null;
	public $Items = null;
	public $AllowChangeColor = true;
	public $CenterSize = 150;
	public $ButtonsSize = 100;

	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name; ?> .tabs{
				max-width: 100%;
				margin-top: 15Vmin;
			}

			.<?php echo $this->Name; ?> .tab{
				padding: 0px 5vmax;
				text-align: center;
				display:none;
			}
			.<?php echo $this->Name; ?> .tab.active{
				display:block;
			}

			.<?php echo $this->Name; ?> .tab .btn:hover{
				font-weight: bold;
			}

			.<?php echo $this->Name; ?> .sign{
				text-align: center;
			}
			.<?php echo $this->Name; ?> .sign .btn{
				font-size: <?php echo \_::$TEMPLATE->Size(2) ?>;
				color: <?php echo \_::$TEMPLATE->ForeColor(2) ?>;
				border-color: transparent;
				margin: 0px 5px;
			}
			.<?php echo $this->Name; ?> .sign .btn:hover{
				background-color: <?php echo \_::$TEMPLATE->BackColor(2) ?>;
				font-size: <?php echo \_::$TEMPLATE->Size(2) ?>;
				color: <?php echo \_::$TEMPLATE->ForeColor(2) ?>;
				border-color: <?php echo \_::$TEMPLATE->ForeColor(2) ?>;
				border-radius: <?php echo \_::$TEMPLATE->Radius(2) ?>;
			}
			.<?php echo $this->Name; ?> .menu {
				min-height: 60vh;
				display: -webkit-box;
				display: -webkit-flex;
				display: -ms-flexbox;
				display: flex;
				-webkit-box-pack: center;
				-webkit-justify-content: center;
					-ms-flex-pack: center;
						justify-content: center;
				-webkit-box-align: center;
				-webkit-align-items: center;
					-ms-flex-align: center;
						align-items: center;
				line-height: <?php echo $this->ButtonsSize; ?>px;
				text-align: center;
				border:none;
			}

			.<?php echo $this->Name; ?> .menu>.center {
				width: <?php echo $this->CenterSize; ?>px;
				height: <?php echo $this->CenterSize; ?>px;
				border-radius: 50%;
				position: relative;
				box-shadow: 0px 0px 20px <?php echo \_::$TEMPLATE->BackColor(2) ?>;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(2)); ?>
			}
		
			.<?php echo $this->Name; ?> .menu>.center:hover {
				box-shadow: 0px 0px 50px <?php echo \_::$TEMPLATE->BackColor(2) ?>;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(2)); ?>
			}
			
			.<?php echo $this->Name; ?> .menu>.center:before {
				position: absolute;
				content: "";
				width: <?php echo $this->CenterSize; ?>px;
				height: <?php echo $this->CenterSize; ?>px;
				font-weight: bold;
				font-size: 180%;
				left: 0px;
				top: 0px;
				background-image: <?php echo "url('".((\_::$INFO->User??$this)->Image??$this->Image)."')" ?>;
				background-position: center;
				background-repeat: no-repeat;
				background-size: 100% 100%;
				background-color: var(--BackColor-2);
				border-radius: 100%;
				cursor: pointer;
				box-shadow: 0px 0px 20px var(--BackColor-2);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(2)); ?>
			}

			.<?php echo $this->Name; ?> .menu>.center>a{
				background-color: var(--BackColor-2);
				color: var(--ForeColor-2);
				position: absolute;
				text-align: center;
				cursor: pointer;
				border: var(--Border-1) var(--BackColor-2);
				border-radius: 100%;
				box-shadow: var(--Shadow-3);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(2)); ?>
			}

			.<?php echo $this->Name; ?> .menu>.center>a:hover {
				box-shadow: var(--Shadow-4);
				border:  var(--Border-1) var(--BackColor-2);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(2)); ?>
			}

			.<?php echo $this->Name; ?> .menu>.center>a>.button{
				line-height: <?php echo $this->ButtonsSize; ?>px;
				width: <?php echo $this->ButtonsSize; ?>px;
				height: <?php echo $this->ButtonsSize; ?>px;
			}
			.<?php echo $this->Name; ?> .menu>.center>a>.button>.image{
				background-position: center;
				background-repeat: no-repeat;
				background-size: 50% 50%;
				width: <?php echo $this->ButtonsSize; ?>px;
				height: <?php echo $this->ButtonsSize; ?>px;
                                                                        				<?php if($this->AllowChangeColor) echo \MiMFa\Library\Style::DropColor(\_::$TEMPLATE->ForeColor(2)); ?>
			}
			.<?php echo $this->Name; ?> .menu>.center>a:hover>.button>.image {
				background-size: 60% 60%;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(2)); ?>
			}
		</style>
		<?php
	}

	public function Echo(){
		parent::Echo();
		$count = count($this->Items);
		if($count > 0){
		?>
			<div class="col-md-5" data-aos="zoom-out" data-aos-duration="1000" >
				<div class="menu">
					<div class="center">
						<?php for($i = 0; $i < $count; $i++){ ?>
							<a data-target=".tab" data-toggle='tab' href="#tab<?php echo $i; ?>">
								<div class="button">
									<div class="image" style="background-image: url('<?php echo $this->Items[$i]['Image']; ?>');">
									</div>
								</div>
							</a>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="col-md-7" data-aos="zoom-in" data-aos-duration="1500" >
				<div class="tabs">
					<?php for($i = 0; $i < $count; $i++){ ?>
					<div class="tab fade <?php echo $i===0?'active show':''; ?>" id="tab<?php echo $i; ?>">
						<h1 class="title"><?php echo $this->Items[$i]['Name']; ?></h1>
						<div class="description">
							<?php echo $this->Items[$i]['Description']; ?>
							<?php echo $this->Items[$i]['More']; ?>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		<?php
		}
	}
	
	public function EchoScript(){
		parent::EchoScript();
		if(count($this->Items) > 0){
		?>
		<script>
			$(document).ready(function(){
				const bselector = '.<?php echo $this->Name; ?> .menu>.center>a';
				$(bselector).click(function(evt){
					const xn = $(this).attr("href");
					const tar = $(this).attr("data-target");
					const x = xn.replace("#", "");
					$(tar).each(function(){
						const y = $(this).attr("id");
						if (x == y) $(this).addClass("active show");
						else $(this).removeClass("active show");
					});
					$(bselector).each(function(){
						const y = $(this).attr("href");
						if (xn == y) $(this).addClass("active");
						else $(this).removeClass("active");
					});
					evt.preventDefault();
				});

				const buttons = Array.from(document.querySelectorAll(bselector));
				const count = buttons.length;
				const increase = Math.PI * 2 / buttons.length;
				const ratio = (<?php echo $this->CenterSize; ?>/<?php echo $this->ButtonsSize; ?> - 1)/2;
				const radius = <?php echo $this->CenterSize; ?>-<?php echo $this->ButtonsSize; ?>*ratio;
				const addition = <?php echo $this->ButtonsSize; ?>*ratio;
				let angle = 0;

				function move(e) {
					const n = buttons.indexOf(this);
					const endAngle = (n % count) * increase;
					function turn() {
						if (Math.abs(endAngle - angle) > 1/8) {
							const sign = endAngle > angle ? 1 : -1;
							angle = angle + sign/8;
							setTimeout(turn, 20);
						} else angle = endAngle;
						buttons.forEach((button, i) => {
							button.style.top = (addition + Math.sin(Math.PI / 2 + i * increase - angle) * radius) + 'px';
							button.style.left = (addition + Math.cos(Math.PI / 2 + i * increase - angle) * radius) + 'px';
						});
					}
					turn();
				}
				
				buttons.forEach((button, i) => {
					button.style.top = (addition + Math.sin(Math.PI / 2 + i * increase) * radius) + 'px';
					button.style.left = (addition + Math.cos(Math.PI / 2 + i * increase) * radius) + 'px';
					button.addEventListener('click', move);
				});

			});
		</script>
		<?php
		}
	}
}
?>