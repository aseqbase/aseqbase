<?php
namespace MiMFa\Module;
use \MiMFa\Library\HTML;
use \MiMFa\Library\Convert;
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

	public function GetStyle(){
		$this->Class = $this->Class." ".$this->Type;
		return parent::GetStyle().
			Html::Style("
			.{$this->Name}{
				text-align: center;
			}
			.{$this->Name}>.background{
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
				".(\MiMFa\Library\Style::UniversalProperty("filter","blur(".$this->BlurSize.")"))."
			}

			.{$this->Name}>.content{
				text-align: center;
				justify-content: center;
				min-height: 50vh;
				margin: var(--size-5) 0px;
				box-shadow: var(--shadow-2);
				padding: 0px;
				overflow: hidden;
				color: ".(isValid($this->ForeColor)?$this->ForeColor:(\_::$Front->ForeColor(0))).";
				".(isValid($this->BorderColor)? ("border: ".(\_::$Front->Border(2)." ".$this->BorderColor).";"):"")."
			}
			.{$this->Name}.box>.content{
				display: inline-block;
				background-color: ".(isValid($this->BackColor)?$this->BackColor:(\_::$Front->BackColor(0))).";
				border-radius: var(--radius-1);
			}
			.{$this->Name}.hybrid>.content{
				height: 100%;
				width: 100%;
				background-position: center;
				background-repeat: repeat;
				background-image: url('".($this->HeaderBanner??\_::$Front->Pattern(0))."');
			}
			.{$this->Name}:is(.transparent,.hybrid)>.content{
				background-color: ".(isValid($this->BackColor)?$this->BackColor:(\_::$Front->BackColor(0)."77")).";
				border: none;
				border-radius: var(--radius-0);
			}

			.{$this->Name}>.content>.top{
				padding: 10vmin;
				padding-bottom: 0px;
				color: ".(isValid($this->ForeColor)?$this->ForeColor:(\_::$Front->ForeColor(4))).";
			}
			.{$this->Name}.box>.content>.top{
				background-color: ".(isValid($this->BackColor)?$this->BackColor:(\_::$Front->BackColor(4))).";
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover;
				background-image: url('".($this->HeaderBanner??\_::$Front->Pattern(0))."');
			}

			.{$this->Name}>.content>.bottom{
				padding: 10vmin;
				padding-top: 0px;
				color: ".(isValid($this->ForeColor)?$this->ForeColor:(\_::$Front->ForeColor(0))).";
			}
			.{$this->Name}.box>.content>.bottom{
				background-color: ".(isValid($this->BackColor)?$this->BackColor:(\_::$Front->BackColor(0))).";
			}
			.{$this->Name}:is(.transparent,.hybrid)>.content>.bottom{
				background-color: ".(isValid($this->BackColor)?$this->BackColor:(\_::$Front->BackColor(0)))."77;
			}

			.{$this->Name}>.content>.top>.image{
				background-position: center;
				background-repeat: no-repeat;
				background-size: auto 100%;
				height: 7.5vmax;
			}
			.{$this->Name}>.content>.top>.title{
				padding-top: 0px;
				font-size: var(--size-3);
    			font-weight: bold;
				color: ".(isValid($this->SpecialColor)?$this->SpecialColor:(\_::$Front->ForeColor(4))).";
			}

			.{$this->Name}>.content>.bottom>.description{
				font-size: var(--size-2);
			}

			.{$this->Name}>.content>.bottom>.services a:not(.btn),.{$this->Name} .services a:not(.btn):visited,.{$this->Name} .services a:not(.btn):hover{
				color: unset;
			}
			.{$this->Name}>.content>.bottom>.services .row>div{
				text-align: center;
				margin-top: 3vmin;
				font-size: var(--size-1);
			}
			.{$this->Name}>.content>.bottom>.services .image{
				display: block;
				height: 3vmin;
			}
			.{$this->Name}>.content>.bottom>.services .icon{
				display: block;
			}
			.{$this->Name}>.content>.bottom>.services .title{
				display: inline-block;
			}
			.{$this->Name}>.content>.bottom>.services .more{
				display: inline-block;
				font-size: var(--size-1);
			}
		");
	}

	public function Get(){
		return Convert::ToString(function(){
			yield Html::Division(null,["class"=>"background", "style"=>"background-image: url('{$this->Image}');"]);
			yield Html::Division(
					Html::Division(
						(isValid($this->Logo)? Html::Media($this->Logo, ["class"=>'image' , "data-aos"=>'flip-up', "data-aos-delay"=>'500']):"").
						(isValid($this->Title)? Html::ExternalHeading($this->Title, null, ["class"=>'title' , "data-aos"=>'zoom-up', "data-aos-delay"=>'1000', "data-aos-offset"=>'-500']):"")
					,["class"=>"top"]).
					Html::Division(
						(isValid($this->Description)? Html::Division(__($this->Description,true,false), ["class"=>'description' , "data-aos"=>'flip-right', "data-aos-delay"=>'1500', "data-aos-offset"=>'-500']):"").
						(isValid($this->Items)? Html::Division(
							Convert::ToString(function(){
								$i = 6;
                                yield "<div class='row'>";
								foreach($this->Items as $item){
									yield "<div class='col-md' data-aos='fade-down' data-aos-offset='-500' data-aos-delay='".($i++*300)."'>";
									if(isValid($item,'Link')) yield "<a href='{$item['Link']}'>";
									if(isValid($item,'Image' )) yield "<img class='image' src='{$item['Image' ]}'>";
									if(isValid($item,'Icon')) yield "<i class='icon {$item['Icon']}' aria-hidden='true'></i>";
									if(isValid($item,'Name' )) yield "<div class='title'>".__($item['Name' ],true,false)."</div>";
									if(isValid($item,'More')) yield "<div class='more'>".__($item['More'],true,false)."</div>";
									if(isValid($item,'Link')) yield "</a>";
									yield "</div>";
								}
								yield "</div>";
								if(isValid($this->Content))
									yield "<div class='row' data-aos='flip-down' data-aos-offset='-500' data-aos-delay='".($i*300)."'>
										<div class='col'>{$this->Content}</div>
									</div>";
                            }), ["class"=>'container services']):"")
					,["class"=>"bottom"])
				,["class"=>"content" ]);
		});
	}
}
?>