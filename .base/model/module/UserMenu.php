<?php
namespace MiMFa\Module;
use MiMFa\Library\User;
use MiMFa\Library\Html;
use MiMFa\Library\Convert;
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

	public function GetStyle(){
		return parent::GetStyle().Html::Style("
			.{$this->Name}{
				aspect-ratio: 1;
			}
			.{$this->Name} .menu{
				aspect-ratio: 1;
				max-height: 30vmin;
				padding: calc(var(--size-0) / 2);
				border-radius: 100%;
				display: inline-flex;
				align-items: center;
			}
			.{$this->Name} .menu>:not(html,head,body,style,script,link,meta,title){
                padding: var(--size-0);
				aspect-ratio: 1;
				border-radius: 100%;
				align-items: center;
			}
			.{$this->Name} .menu>i{
                padding: calc(var(--size-0) / 2);
				display: flex;
			}

			.{$this->Name} .submenu{
				display: none;
				position: absolute;
				top: calc(100% - var(--size-0)/2);
            	left: auto;
				right: 0;
				color: var(--fore-color-2);
				background-color: var(--back-color-1);
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
				z-index: 9;
            	".\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1))."
			}
			.{$this->Name} .submenu .bio>:not(html,head,body,style,script,link,meta,title){
            	font-size: 80%;
				opacity: 0.8;
				color: var(--fore-color-1);
				width: min-content;
				min-width: 100%;
				padding: 5px var(--size-1);
				".\MiMFa\Library\Style::UniversalProperty("word-wrap","break-word")."
			}
			.{$this->Name} .submenu :is(.link, .button){
            	width: 100%;
            	text-align: initial;
            	padding: 5px var(--size-1);
			}
			.{$this->Name}:hover .submenu{
            	display: grid;
			}
		");
	}
	public function Get(){
		if($this->Items == null){
			if(!auth(\_::$Config->UserAccess))
				$this->Items = array(
					array("Name" =>"Sign In", "Path" =>User::$InHandlerPath),
					array("Name" =>"Sign Up", "Path" =>User::$UpHandlerPath)
				);
			else
				$this->Items = array(
					array("Name" =>takeValid(\_::$Back->User,"Name" ,"Profile"), "Path" =>User::$RouteHandlerPath),
					array("Name" =>Convert::ToExcerpt(Convert::ToText(takeValid(\_::$Back->User,"Bio" , null)??between(\_::$Back->User->GetValue("Bio" ), "New User..."))), "Attributes"=>["class"=>"bio"]),
					array("Name" =>"Dashboard", "Path" =>User::$DashboardHandlerPath),
					array("Name" =>"Edit Profile", "Path" =>User::$EditHandlerPath),
					array("Name" =>"Sign Out", "Path" =>"sendDelete(`".User::$OutHandlerPath."`, null, 'body');")
				);
        }
		$count = count($this->Items);
		if($count > 0){
            return Html::Icon(takeValid(\_::$Back->User,"Image" ,"user"), $this->Path,["class"=>"menu"]).
				Html::Division(function(){
				foreach($this->Items as $item)
                    if(isValid($item,'Path' ))
						yield Html::Button(
							Html::Division(__(getBetween($item,"Name" , "Title" ), styling:false),["style"=>(isValid($item,'Image' )?("background-image: url('".$item['Image' ]."')"):"")]),
							get($item,'Path'),
							get($item,"Attributes"));
					else
						yield Html::Span(
							Html::Division(__(getBetween($item,"Name" , "Title" ), styling:false),["style"=>(isValid($item,'Image' )?("background-image: url('".$item['Image' ]."')"):"")]),
							null,
							get($item,"Attributes"));
            },["class"=>"submenu"]).$this->GetContent();
		}
		return parent::Get();
	}
}
?>
