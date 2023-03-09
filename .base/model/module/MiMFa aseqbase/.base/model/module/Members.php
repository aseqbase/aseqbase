<?php namespace MiMFa\Module;
class Members extends Module{
	public $Name = "Members";
	public $Items = null;
	public $DefaultIcon = null;
	public $DefaultName = null;
	public $DefaultDescription = null;
	public $DefaultDetails = null;
	public $DefaultLink = null;
	public $MoreButtonLabel = "Read More...";

	public function EchoStyle(){
		parent::EchoStyle();
?>
<style>
			.<?php echo $this->Name; ?> .teammember{
				background-color: <?php echo \_::$TEMPLATE->BackColor(0) ?>99 var(--Url-Overlay-0); no-repeat center;
				background-size: 100% auto;
				text-align: center;
				border: var(--Border-1) var(--ForeColor-2);
				border-radius: var(--Radius-1);
				box-shadow: var(--Shadow-1);
				margin-top: 150px;
				margin-bottom: 50px;
				padding-bottom: 15px;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> .teammember:hover{
				background-color: <?php echo \_::$TEMPLATE->BackColor(1) ?>99;
				box-shadow: var(--Shadow-2);
				border: var(--Border-1) var(--ForeColor-1);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> .teammember .image{
				text-align: center;
			}
			.<?php echo $this->Name; ?> .teammember .image img{
				background-color: <?php echo \_::$TEMPLATE->BackColor(0) ?>99;
				border-radius: 100%;
				width: 200px;
				max-width: 75vmin;
				border: var(--Border-1) var(--ForeColor-2);
				margin-top: -100px;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> .teammember:hover .image img{
				background-color: var(--BackColor-1);
				border: var(--Border-1) var(--ForeColor-1);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> .teammember .title{
				text-align: center;
				margin-top: 30px !important;
				margin-bottom: 30px !important;
			}
			.<?php echo $this->Name; ?> .teammember .title *{
				font-size: var(--Size-1);
				margin: -5px !important;
			}
			.<?php echo $this->Name; ?> .teammember .title div{
				font-size: var(--Size-2);
				font-size: 150%;
			}
			.<?php echo $this->Name; ?> .teammember .features{
				text-align: center;
				margin-top: 10px;
				margin-bottom: 10px;
			}
			.<?php echo $this->Name; ?> .teammember .features div{
				background-color: var(--BackColor-0);
				border-radius: var(--Radius-2);
				border: var(--Border-1) var(--ForeColor-2);
				margin: 5px;
				padding: 0px 10px;
				display: inline-block;
			}
			.<?php echo $this->Name; ?> .teammember .list-group{
				margin: 2vmax 3vmax;
				border: none;
			}
			.<?php echo $this->Name; ?> .teammember .list-item {
				background-color: transparent;
				padding: 1vmin 0px;
				border: none;
			}

			.<?php echo $this->Name; ?> .teammember .badge-primary {
				background-color: var(--ForeColor-0);
				color: var(--BackColor-0);
			}
			.<?php echo $this->Name; ?> .teammember .badge-primary:hover {
				background-color: var(--BackColor-2);
				color: var(--ForeColor-2);
			}
</style>
<?php
	}

	public function Echo(){
		parent::Echo();
?>
<div class="container">
    <?php
			$menu = $this->Items;
			$count = count($menu);
			if($count > 0){
    ?>
    <div class="row">
        <?php for($i = 0; $i < $count; $i++){ ?>
        <div class="col-md">
            <div class="teammember" data-aos="down">
                <div class="image">
                    <img src="<?php echo getValid($menu[$i],'Image'); ?>" />
                </div>
                <div class="title">
                    <sup>
                        <?php echo __(getValid($menu[$i],'PreName')); ?>
                    </sup>
                    <div>
                        <strong>
                            <?php echo __(getValid($menu[$i],'FirstName'))." ".__(getValid($menu[$i],'MiddleName'))." ".__(getValid($menu[$i],'LastName')); ?>
                        </strong>
                    </div>
                    <sub>
                        <?php echo __(getValid($menu[$i],'PostName')); ?>
                    </sub>
                </div>
                <div class="features">
                    <?php foreach(getValid($menu[$i],'Assignees', array()) as $assignee) { ?>
                    <div>
                        <?php echo __($assignee,true,false); ?>
                    </div><br />
                    <?php } ?>
                </div>
                <ul class="list-group">
                    <?php foreach(getValid($menu[$i],'Items',array()) as $item) { ?>
                    <li class="list-item d-flex justify-content-between align-items-center">
                        <i class="fa <?php echo getValid($item,'Class') ?>" aria-hidden="true">
                            <?php echo __(getValid($item,'Key'),true,false).__(":",true,false); ?>
                        </i>
                        <?php echo __(getValid($item,'Value'),true,false); ?>
                    </li>
                    <?php } ?>
                </ul>
                <a class="btn" target="blank" href="<?php echo $menu[$i]['Link']; ?>">
                    <?php echo __($this->MoreButtonLabel); ?>
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<?php
	}
}
?>
