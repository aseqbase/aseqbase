<?php
namespace MiMFa\Module;
use MiMFa\Library\User;
use MiMFa\Library\HTML;
use MiMFa\Library\Convert;
class UserMenu extends Module{
	/**
     * This object is convertable to string and able to embedd anywhere or not
     * @var bool|null
     */
	public $Capturable = true;
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

	public function GetStyle(){
		return parent::GetStyle().HTML::Style("
			.{$this->Name}{
				aspect-ratio: 1;
			}
			.{$this->Name} .menu{
				aspect-ratio: 1;
				max-height: 30vmin;
				padding: calc(var(--Size-0) / 2);
				border-radius: 100%;
				display: inline-flex;
				align-items: center;
			}
			.{$this->Name} .menu>:not(html,head,body,style,script,link,meta,title){
                padding: var(--Size-0);
				aspect-ratio: 1;
				border-radius: 100%;
				align-items: center;
			}
			.{$this->Name} .menu>i{
                padding: calc(var(--Size-0) / 2);
				display: flex;
			}

			.{$this->Name} .submenu{
				display: none;
				position: absolute;
				top: calc(100% - var(--Size-0)/2);
            	left: auto;
				right: 0;
				color: var(--ForeColor-2);
				background-color: var(--BackColor-1);
				min-width: 300px;
				min-width: min(210px, 100%);
				max-width: 90vw;
				max-height: 70vh;
				width: max-content;
				padding: 0px;
				box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
				overflow-x: hidden;
				overflow-y: auto;
				text-align: initial;
				z-index: 1;
            	".\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .submenu .bio>:not(html,head,body,style,script,link,meta,title){
            	font-size: 80%;
				opacity: 0.8;
				width: min-content;
				min-width: 100%;
				padding: 5px var(--Size-1);
				".\MiMFa\Library\Style::UniversalProperty("word-wrap","break-word")."
			}
			.{$this->Name} .submenu .link{
            	width: 100%;
            	text-align: initial;
            	padding: 5px var(--Size-1);
			}
			.{$this->Name}:hover .submenu{
            	display: grid;
			}
		");
	}
	public function Get(){
		if($this->Items == null){
			if(!getAccess(\_::$CONFIG->UserAccess))
				$this->Items = array(
					array("Name"=>"Sign In", "Path"=>User::$InHandlerPath),
					array("Name"=>"Sign Up", "Path"=>User::$UpHandlerPath)
				);
			else
				$this->Items = array(
					array("Name"=>getValid(\_::$INFO->User,"Name","Profile"), "Path"=>User::$ViewHandlerPath),
					array("Name"=>Convert::ToExcerpt(getValid(\_::$INFO->User,"Bio", null)??getValid(\_::$INFO->User->GetValue("Bio"), null, "New User..."))),
					array("Name"=>"Dashboard", "Path"=>User::$DashboardHandlerPath),
					array("Name"=>"Edit Profile", "Path"=>User::$EditHandlerPath),
					array("Name"=>"Sign Out", "Path"=>User::$OutHandlerPath)
				);
        }
		$count = count($this->Items);
		if($count > 0){
            return HTML::Icon(getValid(\_::$INFO->User,"Image","user"), $this->Path,["class"=>"menu"]).
				HTML::Division(function(){
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
            },["class"=>"submenu"]).$this->GetContent();
		}
		return parent::Get();
	}
}
?>
