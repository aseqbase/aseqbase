<?php
namespace MiMFa\Module;
use \MiMFa\Library\Html;
use \MiMFa\Library\Convert;
class FixedBanner extends Module{
	public $Image = null;
	public $Logo = null;
	public $Slogan = null;
	public $Items = null;
	/**
	 * @options "box","transparent","hybrid"
	 * @var string
	 */
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
				margin: var(--size-max) 0px;
				box-shadow: var(--shadow-2);
				padding: 0px;
				overflow: hidden;
				color: ".(isValid($this->ForeColor)?$this->ForeColor:"var(--fore-color)").";
				".(isValid($this->BorderColor)? "border: var(--border-2) {$this->BorderColor};":"")."
			}
			.{$this->Name}.box>.content{
				display: inline-block;
				background-color: ".(isValid($this->BackColor)?$this->BackColor:"var(--back-color)").";
				border-radius: var(--radius-1);
			}
			.{$this->Name}.hybrid>.content{
				height: 100%;
				width: 100%;
				background-position: center;
				background-repeat: repeat;
				background-image: url('".($this->HeaderBanner??"var(--pattern-0)")."');
			}
			.{$this->Name}:is(.transparent,.hybrid)>.content{
				border: none;
				border-radius: var(--radius-0);
			}

			.{$this->Name}>.content>.top{
				background-color: ".(isValid($this->BackColor)?$this->BackColor:"var(--back-color)").";
				color: ".(isValid($this->ForeColor)?$this->ForeColor:"var(--fore-color-special-input)").";
				padding: var(--size-max);
				padding-bottom: 0px;
				margin-bottom: 0px;
				opacity: 0.8;
			}
			.{$this->Name}.box>.content>.top{
				background-color: ".(isValid($this->BackColor)?$this->BackColor:"var(--back-color-special-input)").";
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover;
				background-image: url('".($this->HeaderBanner??"var(--pattern-0)")."');
			}

			.{$this->Name}>.content>.bottom{
				padding: var(--size-max);
				padding-top: 0px;
				color: ".(isValid($this->ForeColor)?$this->ForeColor:"var(--fore-color)").";
			}
			.{$this->Name}.box>.content>.bottom{
				background-color: ".(isValid($this->BackColor)?$this->BackColor:"var(--back-color)").";
			}
			.{$this->Name}:is(.transparent,.hybrid)>.content>.bottom{
				background-color: ".(isValid($this->BackColor)?$this->BackColor:"var(--back-color)").";
			}

			.{$this->Name}>.content>.top>.image{
				background-position: center;
				background-repeat: no-repeat;
				background-size: auto 100%;
				height: calc(var(--size-max) * 2);
			}
			.{$this->Name}>.content>.top>.title{
				margin-top: var(--size-0);
				margin-bottom: 0px;
				padding-top: 0px;
				padding-bottom: calc(var(--size-0) / 2);
				font-size: var(--size-3);
    			font-weight: bold;
				color: ".(isValid($this->SpecialColor)?$this->SpecialColor:"var(--fore-color-special-input)").";
			}

			.{$this->Name}>.content>.bottom>.description{
				font-size: var(--size-2);
			}

			.{$this->Name}>.content>.bottom>.services a:not(.button),.{$this->Name} .services a:not(.button):visited,.{$this->Name} .services a:not(.button):hover{
				color: unset;
			}
			.{$this->Name}>.content>.bottom>.services .row>div{
				text-align: center;
				margin-top: var(--size-3);
				font-size: var(--size-1);
				display: flex;
				justify-content: center;
				align-items: center;
				align-content: center;
				flex-direction: column;
				flex-wrap: wrap;
				gap: calc(var(--size-0) / 2);
			}
			.{$this->Name}>.content>.bottom>.services .image{
				display: block;
				height: var(--size-3);
			}
			.{$this->Name}>.content>.bottom>.services .icon{
				display: block;
				font-size: var(--size-3);
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
			yield Html::Division("",["class"=>"background", "style"=>"background-image: url('{$this->Image}');"]);
			yield Html::Division(
					Html::Division(
						(isValid($this->Logo)? Html::Media($this->Logo, ["class"=>'image' , "data-aos"=>'flip-up', "data-aos-delay"=>'500']):"").
						(isValid($this->Title)? Html::Heading1($this->Title, null, ["class"=>'title' , "data-aos"=>'zoom-up', "data-aos-delay"=>'1000', "data-aos-offset"=>'-500']):"")
					,["class"=>"top"]).
					Html::Division(
						(isValid($this->Description)? Html::Division(__($this->Description), ["class"=>'description' , "data-aos"=>'flip-right', "data-aos-delay"=>'1500', "data-aos-offset"=>'-500']):"").
						(isValid($this->Items)? Html::Division(
							Convert::ToString(function(){
								$i = 6;
                                yield "<div class='row'>";
								foreach($this->Items as $item){
									yield "<div class='col-md' data-aos='fade-down' data-aos-offset='-500' data-aos-delay='".($i++*300)."'>";
									$p = getBetween($item,'Path');
									if($v = get($item,'Image' )) yield Html::Image(null, $v);
									if($v = get($item,'Icon' )) yield Html::Icon($v);
									if($p) yield Html::OpenTag("a", ["href"=>$p]);
									if($v = getBetween($item, 'Title', 'Name')) yield Html::Division(__($v), ["class"=>"title"]);
									if($p) yield Html::CloseTag();
									if($v = get($item, 'More')) yield Html::Division(__($v), ["class"=>"more"]);
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