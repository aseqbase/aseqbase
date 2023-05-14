<?php namespace MiMFa\Module;
class BarMenu extends Module{
	public $Items = null;
	public $AllowLabels = false;
	public $AllowAnimate = true;
	public $AllowMiddle = true;
	public $AllowChangeColor = true;
	public $ShowFromScreenSize = "sm";
		
	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name; ?>{
				text-transform: uppercase;
				text-align: center;
				width:100vw;
				height: 40px;
				<?php if(!$this->AllowMiddle){ ?>
					overflow: hidden;
				<?php } ?>
				position: fixed;
				margin: 0px;
				bottom: 0px;
				left: 0px;
				right: 0px;
				box-shadow: -5px 0px 20px #00000025;
				border: none;
				z-index: 999999;
			}

			.<?php echo $this->Name; ?>:hover{
				box-shadow: -5px 0px 20px #00000045;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)) ?>
			}

			.<?php echo $this->Name; ?>:after {
				content: "";
				clear: both;
				display: table;
			}

			.<?php echo $this->Name; ?>>a>.button {
				background-color: <?php echo \_::$TEMPLATE->BackColor(2)."dd"; ?>;
				background-image: var(--Url-Overlay-0);
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover;
				color: var(--ForeColor-2);
				height: 40px;
				cursor: pointer; /* Pointer/hand icon */
				float: left; /* Float the buttons side by side */
			}

			.<?php echo $this->Name; ?>>a>.button:hover{
				<?php if($this->AllowAnimate) { ?>
					background-color: var(--ForeColor-2);
					color: var(--BackColor-2);;
				<?php 
				} ?>
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)) ?>
			}
			
			.<?php echo $this->Name; ?>>a>.button>div{
				height: 100%;
				background-position: center;
				background-repeat: no-repeat;
				background-size: auto 60%;
				color: var(--ForeColor-2);
				padding: 10px 24px; /* Some padding */
				<?php if($this->AllowChangeColor) echo \MiMFa\Library\Style::DropColor(\_::$TEMPLATE->ForeColor(2)); ?>
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)) ?>
			}
			.<?php echo $this->Name; ?>>a>.button:hover>div{
				background-size: auto 70%;
				<?php if($this->AllowAnimate) echo \MiMFa\Library\Style::UniversalProperty("filter","none"); ?>
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)) ?>
			}

			.<?php echo $this->Name; ?>>a>.button>div>span {
				text-shadow: 0px 5px 10px #000000aa;
				display:none;
			}
			<?php if($this->AllowLabels){ ?>
			.<?php echo $this->Name; ?>>a>.button:hover>div>span {
				display:block;
			}
			<?php } ?>

			.<?php echo $this->Name; ?>>a>.button:not(:last-child) {
				border-right: none; /* Prevent double borders */
			}

			<?php if($this->AllowMiddle){ ?>
				.<?php echo $this->Name; ?>>a>.button.middle {
					margin-top: -10px;
					height: 50px;
					border-radius: 100% 100% 0px 0px;
					box-shadow: var(--Shadow-1);
				}
				.<?php echo $this->Name; ?>>a>.button.middle:hover{
					box-shadow:var(--Shadow-2);
				}
				
				.<?php echo $this->Name; ?>>a>.button.right{
					border-radius: 35% 0px 0px 0px;
				}
				.<?php echo $this->Name; ?>>a>.button.left{
					border-radius: 0px 35% 0px 0px;
				}
				.<?php echo $this->Name; ?>>a>.button.first{
					border-radius: 50% 0px 0px 0px;
				}
				.<?php echo $this->Name; ?>>a>.button.last{
					border-radius: 0px 50% 0px 0px;
				}
			<?php } ?>
		</style>
		<?php
	}
	public function Echo(){
		parent::Echo();
		$count = count($this->Items);
		if($count > 0){
			$size = 100 / $count;
			$msize = 100 - $size * ($count-1);
			for($i = 0; $i < $count; $i++){
				$m = $count/2;
				$cls = "";
				$ism = false;
				if((($i+1) <= $m) && (($i+2) >= $m)) $cls = "left";
				elseif($ism =(($i <= $m) && (($i+1) >= $m))) $cls = "middle";
				elseif((($i-1) <= $m) && ($i >= $m)) $cls = "right";
				elseif($i == 0) $cls = "first";
				elseif($i == $count - 1) $cls = "last";
				?>
				<a <?php echo isValid($this->Items[$i],"Attributes")?$this->Items[$i]["Attributes"]:"" ?> <?php echo isValid($this->Items[$i],'Link')?"href=\"".$this->Items[$i]['Link']."\"":"" ?>>
					<div class="button <?php echo $cls; ?>" style="width:<?php echo $ism?$msize:$size ?>vw;">
						<div style="background-image: url('<?php echo $this->Items[$i]['Image'] ?>')">
							<span><?php echo __($this->Items[$i]['Name']) ?></span>
						</div>
					</div>
				</a>
			<?php 
			}
		}
	}
}
?>