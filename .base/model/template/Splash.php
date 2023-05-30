<?php
namespace MiMFa\Template;
class Splash extends Template{
    public $SupTitle = null;
    public $Title = null;
    public $SubTitle = null;
    public $SupDescription = null;
    public $Description = null;
    public $SubDescription = null;
    public $Phrases = array();
    public $NextPath = null;
    public $Image = null;
    public $Logo = null;

    public function DrawInitial(){
        parent::DrawInitial();?>
	<title><?php echo $this->WindowTitle??\_::$INFO->FullName; ?></title>
	<link rel="icon" href="<?php echo getFullUrl($this->WindowLogo??$this->Logo??\_::$INFO->LogoPath); ?>">
	<script type="text/javascript">
		$(document).ready(function(){
			setTimeout(finishScreen,5000+(<?php echo count($this->Phrases)*1000; ?>));
		});
		function finishScreen(){
			load("<?php echo $this->NextPath??\_::$INFO->HomePath; ?>");
		}
	</script>
	<style>
		body {
			font-family: var(--Font-1);
			color: var(--ForeColor-5);
			background-color: var(--BackColor-5);
			background-image: <?php echo isValid($this->Image)?("url('".$this->Image."')"):"var(--Url-Overlay-0)"; ?>;
			background-size: cover;
			background-position: center;
			background-repeat: no-repeat;
			height: 100vh;
			width: 100vw;
			margin: 0px;
			padding: 0px;
			position: fixed;
			z-index: 999999999;
			line-height: var(--Size-3);
			font-size: 5vmin;
		}
		.splash-view {
			text-align: center;
		}
		.splash-view .logo{
			position: absolute;
			bottom: 10vh;
			left: 0px;
			right: 0px;
			text-align: center;
			width: 100vw;
		}
		.splash-view .logo .image{
			background-image: url('<?php echo $this->Logo; ?>');
			background-size: contain;
			background-position: center;
			background-repeat: no-repeat;
			display: inline-block;
			height:10vmin;
			width: 30vmin;
		}
		.splash-view .content {
			text-align: center;
			position: absolute;
			width: 100vw;
			height: 100vh;
			margin: 0px;
			padding: 0px;
			top: 25vh;
			left: 0px;
			right: 0px;
		}
		.splash-view .frame {
			position: relative;
			width: 100vw;
			margin: 0px;
			padding: 0px;
			text-align: center;
			color: transparent;
			display: inline-block;
			animation: blurFadeInOut 2s ease-in backwards;
		}
		.splash-view .frame, .splash-view .frame>* {
			padding: 2vmin 5vmin;
		}
		.splash-view .frame-1 {
			text-align: unset;
			width: fit-content;
			animation: blurFadeInOut 3s ease-in backwards;
		}
		.splash-view .frame-1 * {
			text-align: unset;
			width: fit-content;
			padding: 0px;
			animation: blurFadeIn 0.5s ease-in backwards;
		}
		.splash-view .frame-1>*:nth-child(1){
			animation-delay: 1s;
		}
		.splash-view .frame-1>*:nth-child(2){
			margin-top: -1vmax;
			animation-delay: 1.5s;
		}
		.splash-view .frame-2 {
			top: -15vh;
			animation-delay: 3s;
		}
		.splash-view .frame-3 {
			top: -25vh;
			animation: none;
			color: transparent;
			text-shadow: 0px 0px 1px  var(--ForeColor-5);
		}
		.splash-view .frame-3>* {
			width: fit-content;
			display: inline-block;
			animation: blurFadeIn 1s ease-in 5s backwards;
			color: transparent;
			text-shadow: 0px 0px 1px var(--ForeColor-5);
		}
		.splash-view .frame-3>*:nth-child(2) {
			animation-delay: 6s;
		}
		.splash-view .frame-3>*:nth-child(3) {
			animation-delay: 7s;
		}
		.splash-view .frame-3>*:nth-child(4) {
			animation-delay: 8s;
		}
		.splash-view .frame-3>*:nth-child(5) {
			animation-delay: 9s;
		}

		@keyframes blurFadeInOut{
			0%{
				opacity: 0;
				display: none;
				text-shadow: 0px 0px 40px var(--ForeColor-5);
				transform: scale(0.9);
			}
			20%,75%{
				opacity: 1;
				text-shadow: 0px 0px 1px var(--ForeColor-5);
				transform: scale(1);
			}
			100%{
				opacity: 0;
				display: none;
				text-shadow: 0px 0px 50px var(--ForeColor-5);
				transform: scale(0);
			}
		}
		@keyframes blurFadeIn{
			0%{
				opacity: 0;
				display: none;
				text-shadow: 0px 0px 40px var(--ForeColor-5);
				transform: scale(1.3);
			}
			50%{
				opacity: 0.5;
				text-shadow: 0px 0px 10px var(--ForeColor-5);
				transform: scale(1.1);
			}
			100%{
				opacity: 1;
				text-shadow: 0px 0px 1px var(--ForeColor-5);
				transform: scale(1);
			}
		}
		@keyframes fadeInBack{
			0%{
				opacity: 0;
				display: none;
				transform: scale(0);
			}
			50%{
				opacity: 0.4;
				transform: scale(2);
			}
			100%{
				opacity: 0.2;
				transform: scale(5);
			}
		}
	</style>
<?php }
    public function DrawMain(){
        parent::DrawMain();?>
	<div class='splash-view'>
		<div class='content'>
			<div class='frame frame-1'>
				<?php if(isValid($this->SupTitle)) echo "<p>".__($this->SupTitle,true,false)."</p>"; ?>
				<?php if(isValid($this->Title)) echo "<h1>".__($this->Title,true,false)."</h1>"; ?>
				<?php if(isValid($this->SubTitle)) echo "<p>".__($this->SubTitle,true,false)."</p>"; ?>
			</div>
			<div class='frame frame-2'>
				<?php if(isValid($this->SupDescription)) echo "<div>".__($this->SupDescription,true,false)."</div>"; ?>
				<?php if(isValid($this->Description)) echo __($this->Description,true,false); ?>
				<?php if(isValid($this->SubDescription)) echo "<div>".__($this->SubDescription,true,false)."</div>"; ?>
			</div>
			<?php if(count($this->Phrases) > 0) { ?>
			<div class='frame frame-3'>
				<?php foreach($this->Phrases as $ser) { echo "<div>".__($ser["Name"],true,false)."</div> "; } ?> 
			</div>
			<?php } ?>
		</div>
		<div class="logo"><div class="image"></div></div>
	</div>
<?php }
}?>