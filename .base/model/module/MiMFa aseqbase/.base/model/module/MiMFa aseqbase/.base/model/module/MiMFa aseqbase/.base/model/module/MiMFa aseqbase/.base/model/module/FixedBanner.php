<?php namespace MiMFa\Module;
class FixedBanner extends Module{
	public $Image = null;
	public $Logo = null;
	public $Slogan = null;
	public $Items = null;
	///$Type:	Options = array("box","transparent","hybrid");
	public $Type = "transparent";
	public $SpecialColor = null;
	public $ForeColor = null;
	public $BackColor = null;
	public $BorderColor = null;
	public $HeaderBanner = null;
	public $BlurSize = "10px";
	
	public function EchoStyle(){
		$this->Class = $this->Class." ".$this->Type;
		parent::EchoStyle();
		?>
		<style>
			.<?php echo $this->Name; ?>{
				text-align: center;
			}
			.<?php echo $this->Name; ?>>.background{
				height: 100vh;
				width: 100vw;
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover;
				position: fixed;
    			top: 0px;
    			bottom: 0px;
    			left: 0px;
    			right: 0px;
				z-index: -999999999;
				<?php echo \MiMFa\Library\Style::UniversalProperty("filter","blur(".$this->BlurSize.")");?>
			}
			
			.<?php echo $this->Name; ?>>.content{
				text-align: center;
				justify-content: center;
				min-height: 50vh;
				margin: var(--size-5) 0px;
				box-shadow: var(--Shadow-2);
				padding: 0px;
				overflow: hidden;
				color: <?php echo isValid($this->ForeColor)?$this->ForeColor:(\_::$TEMPLATE->ForeColor(0)); ?>;
				<?php if(isValid($this->BorderColor)) { ?>
				border: <?php echo \_::$TEMPLATE->Border(2)." ".$this->BorderColor; ?>;
				<?php } ?>
			}
			.<?php echo $this->Name; ?>.box>.content{
				display: inline-block;
				background-color: <?php echo isValid($this->BackColor)?$this->BackColor:(\_::$TEMPLATE->BackColor(0)); ?>;
				border-radius: var(--Radius-1);
			}
			.<?php echo $this->Name; ?>.hybrid>.content{
				height: 100%;
				width: 100%;
				background-position: center;
				background-repeat: repeat;
				background-image: url('<?php echo $this->HeaderBanner??\_::$TEMPLATE->Pattern(0); ?>');
			}
			.<?php echo $this->Name; ?>:is(.transparent,.hybrid)>.content{
				background-color: <?php echo isValid($this->BackColor)?$this->BackColor:(\_::$TEMPLATE->BackColor(0)."77"); ?>;
				border: none;
				border-radius: var(--Radius-0);
			}

			.<?php echo $this->Name; ?>>.content>.top{
				padding: 10vmin;
				padding-bottom: 0px;
				color: <?php echo isValid($this->ForeColor)?$this->ForeColor:(\_::$TEMPLATE->ForeColor(4)); ?>;
			}
			.<?php echo $this->Name; ?>.box>.content>.top{
				background-color: <?php echo isValid($this->BackColor)?$this->BackColor:(\_::$TEMPLATE->BackColor(4)); ?>;
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover;
				background-image: url('<?php echo $this->HeaderBanner??\_::$TEMPLATE->Pattern(0); ?>');
			}

			.<?php echo $this->Name; ?>>.content>.bottom{
				padding: 10vmin;
				padding-top: 0px;
				color: <?php echo isValid($this->ForeColor)?$this->ForeColor:(\_::$TEMPLATE->ForeColor(0)); ?>;
			}
			.<?php echo $this->Name; ?>.box>.content>.bottom{
				background-color: <?php echo isValid($this->BackColor)?$this->BackColor:(\_::$TEMPLATE->BackColor(0)); ?>;
			}
			.<?php echo $this->Name; ?>:is(.transparent,.hybrid)>.content>.bottom{
				background-color: <?php echo isValid($this->BackColor)?$this->BackColor:(\_::$TEMPLATE->BackColor(0)); ?>77;
			}

			.<?php echo $this->Name; ?>>.content>.top>.image{
				background-position: center;
				background-repeat: no-repeat;
				background-size: auto 100%;
				height: 7.5vmax;
			}
			.<?php echo $this->Name; ?>>.content>.top>.title{
				padding-top: 0px;
				font-size: var(--Size-3);
    			font-weight: bold;
				color: <?php echo isValid($this->SpecialColor)?$this->SpecialColor:(\_::$TEMPLATE->ForeColor(4)); ?>;
			}

			.<?php echo $this->Name; ?>>.content>.bottom>.description{
				font-size: var(--Size-2);
			}
			
			.<?php echo $this->Name; ?>>.content>.bottom>.services a:not(.btn),.<?php echo $this->Name; ?> .services a:not(.btn):visited,.<?php echo $this->Name; ?> .services a:not(.btn):hover{
				color: unset;
			}
			.<?php echo $this->Name; ?>>.content>.bottom>.services .row>div{
				text-align: center;
				margin-top: 3vmin;
				font-size: var(--Size-1);
			}
			.<?php echo $this->Name; ?>>.content>.bottom>.services .image{
				display: block;
				height: 3vmin;
			}
			.<?php echo $this->Name; ?>>.content>.bottom>.services .icon{
				display: block;
			}
			.<?php echo $this->Name; ?>>.content>.bottom>.services .title{
				display: inline-block;
			}
			.<?php echo $this->Name; ?>>.content>.bottom>.services .more{
				display: inline-block;
				font-size: var(--Size-1);
			}
		</style>
		<?php
	}

	public function Echo(){
		?>
		<div class="background" style="background-image: url('<?php echo $this->Image;?>');">
		</div>
		<div class="content">
			<div class="top">
				<?php if(isValid($this->Logo)){ ?>
					<div class="image" style="background-image: url('<?php echo $this->Logo; ?>');" data-aos="flip-up" data-aos-delay="500"></div>
				<?php } ?>
				<?php if(isValid($this->Title)){ ?>
					<h1 class="title" data-aos="zoom-up" data-aos-offset="-500" data-aos-delay="1000"><?php echo __($this->Title,true,false);?></h1>
				<?php } ?>
			</div>
			<div class="bottom">
				<?php if(isValid($this->Description)){ ?>
					<div class="description" data-aos="flip-right" data-aos-offset="-500" data-aos-delay="1500"><?php echo __($this->Description,true,false);?></div>
				<?php } ?>
				<?php if(isValid($this->Items)){ ?>
				<div class="container services">
					<div class="row">
					<?php $i = 6;
					foreach($this->Items as $item){?>
						<div class="col-md" data-aos="fade-down" data-aos-offset="-500" data-aos-delay="<?php echo $i++*300; ?>">
							<?php if(isValid($item,'Link')){ ?>
								<a href="<?php echo $item['Link'];?>">
							<?php } ?>
								<?php if(isValid($item,'Image')){ ?>
									<img class="image" src="<?php echo $item['Image'];?>">
								<?php } ?>
								<?php if(isValid($item,'Icon')){ ?>
									<i class="icon <?php echo $item['Icon'];?>" aria-hidden="true"></i>
								<?php } ?>
								<?php if(isValid($item,'Name')){ ?>
									<div class="title"><?php echo __($item['Name'],true,false);?></div>
								<?php } ?>
								<?php if(isValid($item,'More')){ ?>
									<div class="more"><?php echo __($item['More'],true,false);?></div>
								<?php } ?>
							<?php if(isValid($item,'Link')){ ?>
								</a>
							<?php } ?>
						</div>
					<?php } 
					} ?>
					</div>
					<?php if(isValid($this->Content)){ ?>
						<div class="row" data-aos="flip-down" data-aos-offset="-500" data-aos-delay="<?php echo $i++*300; ?>"><div class="col"><?php echo $this->Content;?></div></div>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php
	}
}
?>