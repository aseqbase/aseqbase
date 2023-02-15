<?php namespace MiMFa\Template;
class Main extends Template{
	public $PageTitle = null;
	public $Logo = null;
	public $BackgroundImage = null;
	public $AllowFooter = true;
	public $AllowTopMenu = true;
	public $AllowSideMenu = true;
	public $AllowBarMenu = true;

	public function Draw(){
		parent::Draw();?>
		<?php REGION("initial"); ?>
			<title><?php echo $this->PageTitle??\_::$INFO->FullName; ?></title>
			<link rel="icon" href="<?php echo getFullUrl($this->Logo??\_::$INFO->LogoPath); ?>">
			<style>
				body {
					font: var(--Size-1) var(--Font-0), var(--Font-1), var(--Font-2);
					overflow-x: hidden; 
					min-height: 100vh;
					background-color: var(--BackColor-0);
					color: var(--ForeColor-0);
					<?php echo \MiMFa\Library\Style::UniversalProperty("font-smoothing","antialiased"); ?>;
				}
				
				::-webkit-scrollbar {
					background-color: transparent;
					width: 10px;
					height: 10px;
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)"); ?>
				}
				::-webkit-scrollbar:hover {
					background-color: var(--BackColor-1);
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)"); ?>
				}
				::-webkit-scrollbar-track {
					background-color: transparent;
					box-shadow: var(--Shadow-2);
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)"); ?>
				}
				::-webkit-scrollbar-track:hover {
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)"); ?>
				}
				::-webkit-scrollbar-thumb {
					//border: var(--Border-1) var(--BackColor-2);
					background-color: <?php echo \_::$TEMPLATE->BackColor(2); ?>33;
					border-radius: 2px;
					box-shadow: var(--Shadow-2);
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)"); ?>
				}
				::-webkit-scrollbar-thumb:hover {
					background-color: var(--BackColor-2);
					border-radius: 0px;
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)"); ?>
				}
				body>.background-screen{
					background-image: <?php echo isValid($this->BackgroundImage)? "url('".$this->BackgroundImage."')" : "var(--Url-Overlay-0)"; ?>;
				}
				footer{
					padding-bottom: 50px;
				}
				content>.page>.frame>.page{
					color: var(--ForeColor-1);
					background-color: <?php echo \_::$TEMPLATE->BackColor(1); ?>88;
					border: var(--Border-1) var(--BackColor-1);
					box-shadow: var(--Shadow-2);
					border-radius: var(--Radius-1);
					padding: var(--Size-0) var(--Size-3) var(--Size-4) var(--Size-3);
					margin: var(--Size-2) var(--Size-3) var(--Size-1) var(--Size-3);
				}
				content>.page:is(.external-page, .embed-page)>.frame{
					min-Height: calc(100vh - 70px);
				}
				
				h1,h2,h3,h4,h5,h6{
					text-align: center;
					text-transform: uppercase;
					margin-top: 2vmax;
				}
				h1{
					font-size: var(--Size-5);
				}
				h2{
					font-size: var(--Size-4);
				}
				h3{
					font-size: var(--Size-3);
				}
				h4{
					font-size: var(--Size-2);
				}
				h5{
					font-size: var(--Size-1);
				}
				h6{
					font-size: var(--Size-0);
				}

				p{
					text-align: justify;
				}

				a {
					text-decoration: none;
					color:var(--ForeColor-1);
				}
				a:visited {
					color:var(--ForeColor-1);
				}
				a:hover {
					color:var(--ForeColor-0);
				}

				a:active {
					color:var(--ForeColor-1);
				}
				a:deactive {
					<?php echo \MiMFa\Library\Style::UniversalProperty("filter","grayscale(100)"); ?>;
				}
				.btn, .btn:visited{
					background-color: var(--BackColor-1);
					color: var(--ForeColor-1);
					border-color: var(--BackColor-1);
					font-size: var(--Size-1);
					border-radius: var(--Radius-1);
					padding: 1vmin 3vmin;
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)"); ?>
				}
				.btn:hover{
					background-color: var(--BackColor-2);
					color: var(--ForeColor-2);
					border-color: var(--ForeColor-2);
					border-radius: var(--Radius-0);
					box-shadow: var(--Shadow-2);
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)"); ?>
				}
				.btn-primary, .btn-primary:visited {
					background-color: var(--BackColor-2);
					color: var(--ForeColor-2);
					border-color: var(--BackColor-2);
				}
				.btn-primary:hover{
					background-color: var(--BackColor-4);
					color: var(--ForeColor-4);
					border-color: var(--BackColor-4);
					border-radius: var(--Radius-0);
					box-shadow: var(--Shadow-2);
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)"); ?>
				}
				.btn-secondary, .btn-secondary:visited {
					background-color: var(--BackColor-4);
					color: var(--ForeColor-4);
					border-color: var(--BackColor-4);
				}

				.btn-outline, .btn-outline:visited{
					background-color:  var(--BackColor-1);
					color: var(--ForeColor-1);
					border-color: var(--ForeColor-1);
					font-size: var(--Size-1);
					border-radius: var(--Radius-1);
					padding: 1vmin 3vmin;
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)"); ?>
				}
				.btn-outline:hover{
					background-color:  var(--BackColor-2);
					color: var(--ForeColor-2);
					border-color: var(--ForeColor-2);
					border-radius: var(--Radius-0);
					box-shadow: var(--Shadow-2);
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)"); ?>
				}
				.btn-outline-primary, .btn-outline-primary:visited {
					background-color: var(--BackColor-2);
					color: var(--ForeColor-2);
					border-color: var(--ForeColor-2);
				}
				.btn-outline-primary:hover{
					background-color: var(--BackColor-4);
					color: var(--ForeColor-4);
					border-color: var(--ForeColor-4);
					border-radius: var(--Radius-0);
					box-shadow: var(--Shadow-2);
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)"); ?>
				}
				.btn-outline-secondary, .btn-outline-secondary:visited {
					background-color: var(--BackColor-4);
					color: var(--ForeColor-4);
					border-color: var(--ForeColor-4);
				}

				.side-image{
					background-position: center;
					background-repeat: no-repeat;
					background-size: 70% auto;
				}


				.main-bullet{
					list-style-type: none;
					text-align: left;
					padding-left: 25px;
					line-height: 1.3rem;
					font-size: 0.9rem;
				}

				.main-bullet li:before{
					content: "○";
					color: var(--ForeColor-2);
					padding-right: 12px;
				}
				.main-bullet li{
					text-indent: -25px;
					padding: 5px 0px;
				}

				.interactive-table{
					margin-left: auto;
					margin-right: auto;
					border-collapse: separate;
				}
				.interactive-table  td{
					padding: 10px;
				}
				.interactive-table tr{
					padding: 10px 20px;
					border: var(--Border-1) transparent;
					border-radius: var(--Radius-1);
					cursor: pointer;
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)"); ?>
				}
				.interactive-table tr:hover{
					border-color: var(--BackColor-4);
					background-color: var(--ForeColor-4);
					color: var(--BackColor-4);
					box-shadow: var(--Shadow-2);
					<?php echo \MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)"); ?>
				}
			</style>
		<?php REGION("body"); ?>
			<div class='background-screen'></div>
			<?php if($this->AllowTopMenu) PART("main-menu"); ?>
			<?php if($this->AllowSideMenu) PART("side-menu"); ?>
			<?php PART("content"); ?>
			<?php if($this->AllowFooter) PART("footer"); ?>
			<?php if($this->AllowBarMenu) PART("bar-menu"); ?>
		<?php REGION("final"); ?>
<?php }
} ?>