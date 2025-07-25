<?php
namespace MiMFa\Template;

class Message extends Template{
	public $SupTitle = null;
	public $Title = null;
	public $SubTitle = null;
	public $SupDescription = null;
	public $Description = null;
	public $SubDescription = null;
	public $Image = null;

	public function RenderInitial(){?>
		<!DOCTYPE html>
		<html>
			<head>
				<meta charset="<?php echo \_::$Config->Encoding; ?>">
				<!--[if lt IE 9]>
				<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
				<![endif]-->
				<meta name="viewport" content="width=device-width, initial-scale=1, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" >
				<meta name="poweredby" content="http://www.MiMFa.net">
				<meta name="framework" content="aseqbase">
				<meta name="owner" content="<?php echo \_::$Info->FullOwner; ?>">
				<title><?php echo $this->WindowTitle??\_::$Info->FullName; ?></title>
				<link rel="icon" href="<?php echo getFullUrl($this->WindowLogo??\_::$Info->LogoPath); ?>">
				<?php echo join(PHP_EOL, \_::$Front->Libraries); ?>
				<?php echo \_::$Front->GetInitial(); ?>
				<style>
					body {
						font-family: var(--font-1);
						background-color: var(--back-color-1);
						background-image: url('<?php echo $this->Image; ?>');
						background-size: cover;
						background-position: center;
						background-repeat: no-repeat;
						color: var(--fore-color-1);
						height: 100vh;
						width: 100vw;
						margin: 0px;
						padding: 0px;
						position: fixed;
						z-index: 999999999;
						line-height: var(--size-3);
						font-size: var(--size-3);
					}
					.restriction-view {
						text-align: center;
					}
					.restriction-view .logo{
						position: absolute;
						background-image: url('<?php echo $this->WindowLogo; ?>');
						background-size: auto 100%;
						background-position: center;
						background-repeat: no-repeat;
						bottom: 10vh;
						left: 0px;
						right: 0px;
						height:10vmin;
						width: 100vw;
					}
					.restriction-view .content {
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
					.restriction-view .frame {
						position: relative;
						width: 100vw;
						margin: 0px;
						padding: 0px;
						text-align: center;
						display: inline-block;
					}
					.restriction-view .frame, .restriction-view .frame>* {
						padding: 2vmin 5vmin;
					}
					.restriction-view .frame-1 {
						text-align: unset;
						width: fit-content;
					}
					.restriction-view .frame-1 * {
						text-align: unset;
						width: fit-content;
						padding: 0px;
						margin: 0px;
					}
					.restriction-view .frame-2 {
						font-size: var(--size-1);
					}
					.restriction-view .frame-2 p{
						font-size: var(--size-0);
						color: var(--fore-color-4);
					}
				</style>
        <?php }
	public function RenderMain(){?>
		</head>
		<body>
    <?php }
    public function RenderContent(){?>
			<div class='restriction-view'>
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
				</div>
				<div class="logo"></div>
			</div>
    <?php }
    public function RenderFinal(){?>
		</body>
	</html>
<?php }
}