<?php
namespace MiMFa\Module;
use MiMFa\Library\User;
use MiMFa\Library\HTML;
class UserMenu extends Module{
	public $Items = null;
	public $AllowLabels = false;
	public $AllowAnimate = true;
	public $AllowMiddle = true;
	public $AllowChangeColor = true;
	public $Path = null;

	public function __construct(){
        parent::__construct();
		$this->Path = User::$InHandlerPath;
    }

	public function EchoStyle(){
		parent::EchoStyle();?>
		<style>
			.<?php echo $this->Name; ?>{
				aspect-ratio: 1;
			}
			.<?php echo $this->Name; ?> .menu{
				aspect-ratio: 1;
				max-height: inherit;
				padding: 3px;
				display: flex;
				align-items: center;
			}
			.<?php echo $this->Name; ?> .menu>*{
                padding: 0px var(--Size-0);
				aspect-ratio: 1;
				border-radius: 100%;
				align-items: center;
			}
			.<?php echo $this->Name; ?> .menu>i{
				display: flex;
			}

			.<?php echo $this->Name; ?> .submenu{
				display: none;
				position: absolute;
				top: calc(100% - var(--Size-0)/2);
            	left: auto;
				right: 0;
				color: var(--ForeColor-2);
				background-color: var(--BackColor-1);
				min-width: 160px;
				max-width: 90vw;
				max-height: 70vh;
				width: max-content;
				padding: 0px;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
				overflow-x: hidden;
				overflow-y: auto;
				text-align: initial;
				z-index: 1;
            	<?php echo \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)); ?>
			}
			.<?php echo $this->Name; ?> .submenu .bio>*{
            	font-size: 80%;
				opacity: 0.8;
				width: min-content;
				min-width: 100%;
				padding: 5px var(--Size-1);
				<?php echo \MiMFa\Library\Style::UniversalProperty("word-wrap","break-word"); ?>
			}
			.<?php echo $this->Name; ?> .submenu .btn{
            	width: 100%;
            	text-align: initial;
            	padding: 5px var(--Size-1);
			}
			.<?php echo $this->Name; ?>:hover .submenu{
            	display: grid;
			}
		</style>
		<?php
	}
	public function Echo(){
		parent::Echo();
		if($this->Items == null){
			$acc = getAccess();
			if($acc < \_::$CONFIG->UserAccess)
				$this->Items = array(
					array("Name"=>"Sign In", "Path"=>User::$InHandlerPath),
					array("Name"=>"Sign Up", "Path"=>User::$UpHandlerPath)
				);
			else
				$this->Items = array(
					array("Name"=>getValid(\_::$INFO->User,"Name","Profile"), "Path"=>User::$ViewHandlerPath),
					array("Name"=>getValid(\_::$INFO->User,"Bio","New User")),
					array("Name"=>"Dashboard", "Path"=>User::$DashboardHandlerPath),
					array("Name"=>"Edit Profile", "Path"=>User::$EditHandlerPath),
					array("Name"=>"Sign Out", "Path"=>User::$OutHandlerPath)
				);
        }
		$count = count($this->Items);
		if($count > 0){
            echo HTML::Icon(getValid(\_::$INFO->User,"Image","user"), $this->Path,["class"=>"menu"]);
			echo HTML::Division(function(){
				foreach($this->Items as $item)
                    if(isValid($item,'Path'))
						yield HTML::Link(
							HTML::Division(getValid($item,'Name'),["style"=>(isValid($item,'Image')?("background-image: url('".$item['Image']."')"):"")]),
							getValid($item,'Path'),
							["class"=>"btn btn-primary"],
							getValid($item,"Attributes"));
					else
						yield HTML::Span(
							HTML::Division(getValid($item,'Name'),["style"=>(isValid($item,'Image')?("background-image: url('".$item['Image']."')"):"")]),
							null,
							["class"=>"bio"],
							getValid($item,"Attributes"));
            },["class"=>"submenu"]);
		}
	}
}
?>
