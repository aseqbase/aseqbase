<?php
namespace MiMFa\Module;
MODULE("SearchForm");
MODULE("UserMenu");
MODULE("TemplateButton");
class MainMenu extends Module{
	public $Class = "row";
	public $Image = null;
	public $Items = null;
	public $Shortcuts = null;
	public SearchForm|null $SearchForm = null;
	public UserMenu|null $UserMenu = null;
	public TemplateButton|null $TemplateButton = null;
	public $HasBranding = true;
	public $HasItems = true;
	public $HasOthers = true;
	public $AllowFixed = false;
	public $HideItemsScreenSize = 'md';
	public $ShowItemsScreenSize = null;
	public $HideOthersScreenSize = 'md';
	public $ShowOthersScreenSize = null;

	public function __construct(){
        parent::__construct();
		$this->SearchForm = new SearchForm();
		if(\_::$CONFIG->AllowSigning) $this->UserMenu = new UserMenu();
		$this->TemplateButton = new TemplateButton();
    }

	public function EchoStyle(){
		$rtl = (\MiMFa\Library\Translate::$Direction??\_::$CONFIG->DefaultDirection) == "RTL";
		parent::EchoStyle();
?>
		<style>
			.<?php echo $this->Name; ?> {
				margin: 0;
				padding: 0;
				display: flex;
				overflow: hidden;
				background-color: <?php echo \_::$TEMPLATE->BackColor(2).($this->AllowFixed?"ee":""); ?>;
				color: var(--ForeColor-2);
				<?php if($this->AllowFixed){?>
				position:fixed;
				top:0;
				left:0;
				right:0;
				z-index: 999;
            	<?php }?>
				box-shadow: var(--Shadow-2);
			}
			.<?php echo $this->Name; ?>-margin{
				<?php if($this->AllowFixed){?>
				height: 75px;
				background: transparent;
				<?php }?>
			}
			
			.<?php echo $this->Name; ?> .header{
				margin: 0;
				width: fit-content;
				padding: 5px 10px;
				display: inline-table;
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

			.<?php echo $this->Name; ?> li .fa{
				font-size: var(--Size-2);
			}

			.<?php echo $this->Name; ?> ul.Items {
				list-style-type: none;
				margin: 0;
				padding: 0;
				overflow: hidden;
				display: inline-table;
				<?php if($this->SearchForm != null): ?>
				min-width: fit-content;
				max-width: 70% !important;
				<?php endif; ?>
			}

			.<?php echo $this->Name; ?> ul.Items>li {
				display: inline-block;
			}
			.<?php echo $this->Name; ?> ul.Items>li.active{
				border-top: var(--Border-2) var(--BackColor-2);
				border-radius: <?php echo \_::$TEMPLATE->Radius(2); ?> <?php echo \_::$TEMPLATE->Radius(2); ?> 0px 0px;
				color:  <?php echo \_::$TEMPLATE->ForeColor(0)."88"; ?>;
				background-color: var(--BackColor-0);
				box-shadow: var(--Shadow-2);
			}
			.<?php echo $this->Name; ?> ul.Items>li>a, .<?php echo $this->Name; ?> ul.Items>li>a:visited{
				text-decoration: none;
				padding: 14px 16px;
				display: block;
				color: var(--ForeColor-2);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> ul.Items>li:hover>a, .<?php echo $this->Name; ?> ul.Items>li:hover>a:visited {
				font-weight: bold;
				background-color: var(--BackColor-2);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> ul.Items>li.active>a, .<?php echo $this->Name; ?> ul.Items>li.active>a:visited{
				color:  <?php echo \_::$TEMPLATE->ForeColor(0)."88"; ?>;
			}
			.<?php echo $this->Name; ?> ul.Items>li.active:hover>a, .<?php echo $this->Name; ?> ul.Items>li.active:hover>a:visited{
				color: var(--ForeColor-0);
			}

			.<?php echo $this->Name; ?> ul.Sub-Items {
				display: none;
				position: absolute;
				color: var(--ForeColor-2);
				background-color: var(--BackColor-1);
				min-width: 160px;
				max-width: 90vw;
				max-height: 70vh;
				padding: 0px;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
				overflow-x: hidden;
				overflow-y: auto;
				z-index: 99;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> ul.Sub-Items>li {
				display: block;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> ul.Sub-Items>li.active{
				color: var(--ForeColor-2);
				background-color: var(--BackColor-2);
				box-shadow: var(--Shadow-2);
			}
			.<?php echo $this->Name; ?> ul.Sub-Items>li>a, .<?php echo $this->Name; ?> ul.Sub-Items>li>a:visited{
				text-decoration: none;
				padding: 12px 16px;
				display: block;
				color: var(--ForeColor-1);
			}
			.<?php echo $this->Name; ?> ul.Sub-Items>li:hover>a, .<?php echo $this->Name; ?> ul.Sub-Items>li:hover>a:visited{
				font-weight: bold;
				color: var(--ForeColor-2);
				background-color: var(--BackColor-2);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> ul.Sub-Items>li.active a, .<?php echo $this->Name; ?> ul.Sub-Items>li.active a:visited{
				color: var(--ForeColor-2);
			}

			.<?php echo $this->Name; ?> ul.Items>li.DropDown:hover>a,.<?php echo $this->Name; ?> ul.Items>li.DropDown:hover>a:visited {
				color: var(--ForeColor-1);
				background-color: var(--BackColor-1);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> ul.Items>.DropDown:hover>ul.Sub-Items {
				display: block;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> ul.Sub-Items>.DropDown:hover>ul.Sub-Items {
				display: contents;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition", \_::$TEMPLATE->Transition(1)); ?>;
			}
			

			.<?php echo $this->Name; ?> .other{
				text-align: end;
				width: fit-content;
				position: absolute;
				clear: both;
				display: flex;
				align-items: center;
				<?php echo $rtl?"left":"right" ?>: var(--Size-2);
			}
			
			.<?php echo $this->Name; ?> .other>div{
				width: fit-content;
				display: inline-flex;
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
			.<?php echo $this->Name; ?> form :not(html,head,body,style,script,link,meta,title){
				padding: 0px;
				margin: 0px;
				display: inline-block;
				color: var(--ForeColor-2);
				background-color: transparent;
				outline: none;
				border: none;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> form:is(:hover, :active, :focus) :not(html,head,body,style,script,link,meta,title) {
				font-weight: bold;
				outline: none;
				border: none;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> form:is(:hover, :active, :focus) :is(button, button :not(html,head,body,style,script,link,meta,title))  {
				color: var(--BackColor-2);
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> form input[type="search"]{
				max-width: 100%;
				width: 80%;
				width: 0px;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
			.<?php echo $this->Name; ?> form:is(:hover, :active, :focus) input[type="search"], .<?php echo $this->Name; ?> form input[type="search"]:is(:hover, :active, :focus){
				color: var(--ForeColor-1);
				width: 200px;
				<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>;
			}
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
		if($this->HasItems):
			$count = count($this->Items);
			if($count > 0){
				echo "<ul class='Items td ".(isValid($this->ShowItemsScreenSize)?$this->ShowItemsScreenSize.'-show':'').' '.(isValid($this->HideItemsScreenSize)?$this->HideItemsScreenSize.'-hide':'')."'>";
				foreach($this->Items as $item){
					 echo $this->CreateItem($item,1);
				}
				echo "</ul>";
			}
		endif;
		if($this->HasOthers):
		echo "<div class='other ".((isValid($this->ShowOthersScreenSize)?$this->ShowOthersScreenSize.'-show':'').' '.(isValid($this->HideOthersScreenSize)?$this->HideOthersScreenSize.'-hide':''))."'>";
			if($this->SearchForm != null) $this->SearchForm->Draw();
			if($this->UserMenu != null) $this->UserMenu->Draw();
			if($this->TemplateButton != null) $this->TemplateButton->Draw();
			if(isValid($this->Content)) echo $this->Content;
            echo "</div>";
		endif;
	}
	public function PostDraw(){
		parent::PostDraw();
		if($this->AllowFixed){?>
			<div class="<?php echo $this->Name; ?>-margin"></div>
		<?php }
	}

	protected function CreateItem($item, $ind = 1){
		if(!getAccess(getValid($item,"Access",\_::$CONFIG->VisitAccess))) return null;
		$path = getValid($item,"Path",null)??getValid($item,"Link");
		$act = (endsWith($_SERVER['REQUEST_URI'],$path)?'active':'');
		$ret = "";
		$ind++;
		if(isValid($item, "Items")) {
			$ret .= "<li class='DropDown $act'>
				<a ".(isValid($item,"Attributes")?$item['Attributes']:"")." href='$path'>
					<div class='box'>".(isValid($item,"Name")?__($item['Name'],true,false):"")."</div>
				</a>";
			$count = count($item["Items"]);
			if($count > 0){
				$ret .= "<ul class='Sub-Items Sub-Items-$ind'>";
				foreach($item["Items"] as $itm){
					$ret .= $this->CreateItem($itm, $ind);
				}
				$ret .= "</ul>";
			}
			$ret .= "</li>";
		} else  {
			$ret .= "<li class='$act'>
				<a ".(isValid($item,"Attributes")?$item['Attributes']:"")." href='$path'>
					<div class='box'>".(isValid($item,"Name")?__($item['Name'],true,false):(isValid($item,"Title")?__($item['Title'],true,false):""))."</div>
				</a>
			</li>";
		}
		return $ret;
	}

	public function EchoScript(){
		parent::EchoScript();?>
		<script type="text/javascript">
			function ViewSideMenu(show){
				if(show === undefined) $('.<?php echo $this->Name; ?>').toggleClass('active');
				else if(show) $('.<?php echo $this->Name; ?>').addClass('active');
				else $('.<?php echo $this->Name; ?>').removeClass('active');
			}
		</script>
		<?php
	}
}
?>