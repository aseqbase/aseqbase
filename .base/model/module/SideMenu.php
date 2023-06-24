<?php
namespace MiMFa\Module;
MODULE("SearchForm");
MODULE("UserMenu");
MODULE("TemplateButton");
class SideMenu extends Module{
	public $Image = null;
	public $Items = null;
	public $Shortcuts = null;
	public $Direction = "ltr";
	public SearchForm|null $SearchForm = null;
	public UserMenu|null $UserMenu = null;
	public TemplateButton|null $TemplateButton = null;
	public $HasBranding = true;
	public $HasItems = true;
	public $HasOthers = true;
	public $AllowSignButton = true;
	public $SignButtonText = "&#9776;";
	public $SignButtonScreenSize = "md";

	public function __construct(){
        parent::__construct();
		$this->SearchForm = new SearchForm();
		$this->UserMenu = new UserMenu();
		$this->TemplateButton = new TemplateButton();
    }

	public function EchoStyle(){
		parent::EchoStyle();
		$dir = $this->Direction=="rtl"?"right":"left";
		$sdir = $this->Direction=="rtl"?"left":"right";
		?>
		<style>
			.<?php echo $this->Name; ?>{
				background-color:  var(--ForeColor-2);
				color:  var(--BackColor-2);
				font-size:  var(--Size-1);
				margin-<?php echo $dir; ?>: -100vmax;
				width: 50vmax;
				max-width: 70%;
				max-height: 100%;
				height: 100vh;
				top: 0px;
				overflow-y: auto;
				position: fixed;
				z-index: 999;
				padding-bottom: 40px;
				box-shadow: var(--Shadow-2);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(2)); ?>;
			}
			.<?php echo $this->Name; ?>{
			}
			.<?php echo $this->Name; ?>.active{
				margin-<?php echo $dir; ?>: 0;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(2)); ?>;
			}
			.<?php echo $this->Name; ?> .container{
				padding: 0px;
			}
			
			.<?php echo $this->Name; ?> .header{
				background-color:  var(--BackColor-2);
				padding: 5px;
			}
			.<?php echo $this->Name; ?> .header,.<?php echo $this->Name; ?> .header a,.<?php echo $this->Name; ?> .header a:visited{
				color: var(--ForeColor-2);
			}
			.<?php echo $this->Name; ?> .header .title{
				font-size: var(--Size-2);
				padding: 0px 10px;
				<?php if(isValid($this->Description)) echo "line-height: var(--Size-2);"; ?>
			}
			.<?php echo $this->Name; ?> .header .description{
				font-size: var(--Size-0);
				padding: 0px 10px;
			}
			.<?php echo $this->Name; ?> .header .image{
				background-position: center;
				background-repeat: no-repeat;
				background-size: 80% auto;
				background-color: transparent;
				width: 50px;
				display: table-cell;
				font-size: var(--Size-0);
			}

			.<?php echo $this->Name; ?> .items{
				color:  var(--BackColor-2);
				text-transform: uppercase;
				padding: 0px;
				margin: 0vmax 0px 3vmax 0px;
			}
			.<?php echo $this->Name; ?> .item{
				padding: 1.5vmin 1.5vmax;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> a,.<?php echo $this->Name; ?> a:visited{
				color:  var(--BackColor-2);
			}
			.<?php echo $this->Name; ?> .row{
				margin: 0px;
			}
			.<?php echo $this->Name; ?> .item:hover{
				background-color:  var(--BackColor-2);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> .item:hover a,.<?php echo $this->Name; ?> .item:hover a:visited{
				color:  var(--ForeColor-2);
			}
			.<?php echo $this->Name; ?> .item.active{
				border: none;
				border-<?php echo $dir; ?>: 2vmin solid var(--BackColor-2);
			}
			.<?php echo $this->Name; ?> .item.active a,.<?php echo $this->Name; ?> .item.active a:visited{
			}
			.<?php echo $this->Name; ?> .box{
				width: 100%;
			}
			.<?php echo $this->Name; ?> .fa{
				font-size:  var(--Size-2);
			}
			<?php if($this->AllowSignButton) { ?>
				.<?php echo $this->Name; ?>-sign-button-menu{
					font-size:  var(--Size-3);
					cursor: pointer;
					margin: auto;
					<?php echo $sdir; ?>: 2px;
					top: 0px;
					padding: 0px 5px;
					position: fixed;
					z-index: 9999;
					color:  var(--ForeColor-2);
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
				}
				.<?php echo $this->Name; ?>-sign-button-menu:hover{
					color:  var(--ForeColor-0);
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
				}

				
			.<?php echo $this->Name; ?> .other{
				text-align: center;
			}
			
			.<?php echo $this->Name; ?> .other>div{
				width: fit-content;
				display: inline-flex;
			}
			
			.<?php echo $this->Name; ?> .other .btn{
				color: unset;
				background-color: unset;
				border: none;
			}
			
			.<?php echo $this->Name; ?> form{
				text-decoration: none;
				padding: 4px 10px;
				margin: 10px;
				display: block;
				color: var(--ForeColor-2);
				background-color: var(--BackColor-2);
				border: var(--Border-1) var(--BackColor-5);
				border-radius: var(--Radius-3);
				box-shadow: var(--Shadow-1);
				overflow: hidden;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> form:is(:hover, :active, :focus) {
				font-weight: bold;
				color: var(--ForeColor-1);
				background-color: var(--BackColor-1);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> form *{
				padding: 0px;
				margin: 0px;
				display: inline-block;
				color: var(--ForeColor-2);
				background-color: transparent;
				outline: none;
				border: none;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> form:is(:hover, :active, :focus) * {
				font-weight: bold;
				outline: none;
				border: none;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> form:is(:hover, :active, :focus) :is(button, button *)  {
				color: var(--BackColor-2);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> form input[type="search"]{
				max-width: 90%;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> form:is(:hover, :active, :focus) input[type="search"], .<?php echo $this->Name; ?> form input[type="search"]:is(:hover, :active, :focus){
				color: var(--ForeColor-1);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			<?php } ?>
		</style>
		<?php
	}

	public function Echo(){
		if($this->HasBranding): ?>
			<div class="header td row" >
					<?php if(isValid($this->Image)) echo "<div class='td image' rowspan='2' style='background-image: url(\"".$this->Image."\");'></div>"; ?>
				<div class="td">
					<?php if(isValid($this->Description)) echo "<div class='td description'>".__($this->Description,true,false)."</div>"; ?>
					<?php if(isValid($this->Title)) echo "<div class='td title'><a href='/'>".__($this->Title,true,false)."</a></div>"; ?>
				</div>
			</div>
			<?php endif;

			if($this->HasOthers):
				echo "<div class='other'>";
					if($this->SearchForm != null) $this->SearchForm->Draw();
					if($this->UserMenu != null) $this->UserMenu->Draw();
					if($this->TemplateButton != null) $this->TemplateButton->Draw();
				echo "</div>";
			endif;

			if($this->HasItems):
			$count = count($this->Items);
			if($count > 0){
			?>
				<div class="container" onclick="<?php echo $this->Name; ?>_ViewSideMenu(false)">
					<?php if(isValid($this->Content)) echo "<div class='content'>".__($this->Content,true,false)."</div>"; ?>
					<ul class="items" >
					<?php
					$ll = 999999999;
					for($i = 0; $i < $count; $i++){
						$sl = getValid($this->Items[$i],'Layer',1);
						if($sl <= $ll) {
							if($sl <= $ll && $i !== 0) { echo "</div>"; } ?>
							<div class="row">
                            <?php }
							$ll = $sl;
                            ?>
                            <li class="item col-sm <?php echo (endsWith(\_::$URL,getValid($this->Items[$i]['Link'])??getValid($this->Items[$i],'Path')??"")?'active':''); ?>">
                                <a <?php echo getValid($this->Items[$i],'Attributes'); ?> href="<?php echo getValid($this->Items[$i]['Link'])??getValid($this->Items[$i],'Path'); ?>">
                                    <div class="box">
                                        <?php echo __(getValid($this->Items[$i],'Name')??getValid($this->Items[$i],'Title'),true,false); ?>
                                    </div>
                                </a>
                            </li>
                            <?php } ?>
                        </div>
					</ul>
				</div>
			<?php }
			endif;
			MODULE("Shortcuts");
			$module = new Shortcuts();
			$module->Items = $this->Shortcuts;
			$module->Draw();
	}
	
	public function PostDraw(){
		parent::PostDraw();
		if($this->AllowSignButton) { ?>
			<div class="<?php echo $this->Name; ?>-sign-button-menu <?php echo $this->SignButtonScreenSize; ?>-show" onclick='<?php echo $this->Name; ?>_ViewSideMenu()'><?php echo $this->SignButtonText; ?></div>
		<?php }
	}

	public function EchoScript(){
		parent::EchoScript();
		?>
		<script type="text/javascript">
			function <?php echo $this->Name; ?>_ViewSideMenu(show){
				if(show === undefined) $('.<?php echo $this->Name; ?>').toggleClass('active');
				else if(show) $('.<?php echo $this->Name; ?>').addClass('active');
				else $('.<?php echo $this->Name; ?>').removeClass('active');
			}
		</script>
		<?php
	}
}
?>