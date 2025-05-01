<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Convert;
class BarMenu extends Module{
	public $Items = null;
	public $AllowLabels = false;
	public $AllowAnimate = true;
	public $AllowMiddle = true;
	public $AllowSides = false;
	public $AllowChangeColor = true;
	public $VisibleFromScreenSize = "sm";
	public $Height = 40;

	public function GetStyle(){
		return parent::GetStyle().Html::Style("
			.{$this->Name}{
				color: var(--fore-color-2);
				text-transform: uppercase;
				text-align: center;
				width:100vw;
				height: {$this->Height}px;
				".((!$this->AllowMiddle)?"overflow: hidden;":"")."
				position: fixed;
				margin: 0px;
				bottom: 0px;
				left: 0px;
				right: 0px;
				border: none;
				display: flex;
				justify-content: space-evenly;
				flex-direction: column-reverse;
				align-items: stretch;
				flex-wrap: wrap;
				z-index: 999999;
				".\MiMFa\Library\Style::UniversalProperty("filter","drop-shadow(0px 0px 20px #00000044)")."
				".\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1))."
			}

			.{$this->Name}:hover{
				".\MiMFa\Library\Style::UniversalProperty("filter","drop-shadow(0px 0px 30px #00000088)")."
				".\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1))."
			}

			.{$this->Name}>.button {
				background-color: ".\_::$Front->BackColor(2)."dd;
				text-align: center;
				align-content: center;
				border: none;
				display: flex;
				justify-content: center;
				align-items: stretch;
			}
			.{$this->Name}>.button:hover {
				cursor: pointer;
				border: none;
				".(($this->AllowAnimate)?"background-color: var(--fore-color-2);
				color: var(--back-color-2);":"").
				\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1))."
			}
			.{$this->Name}>.button>.division {
				background-image: var(--overlay-url-0);
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover;
				color: var(--fore-color-2);
				aspect-ratio: 1;
				height: {$this->Height}px;
			}

			.{$this->Name}>.button>.division>.media{
				height: 55%;
    			margin: 15%;
				background-position: center;
				background-repeat: no-repeat;
				background-size: auto 60%;
				color: var(--back-color-2);".
				(($this->AllowChangeColor)? \MiMFa\Library\Style::ToggleFilter():"").
				\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1))."
			}
			.{$this->Name}>.button:hover>.division>.media{
				background-size: auto 70%;".
				(($this->AllowAnimate)? \MiMFa\Library\Style::UniversalProperty("filter","none"):"").
				\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1))."
			}

			.{$this->Name}>.button>.division>.media>span {
				text-shadow: 0px 5px 10px #000000aa;
				display:none;
			}
			".(($this->AllowLabels)?"
			.{$this->Name}>.button>.division:hover>.media>span {
				display:block;
			}
			":"")."

			.{$this->Name}>.button>.division:not(:last-child) {
				border-right: none; /* Prevent double borders */
			}
			".
			(($this->AllowMiddle)?"
				.{$this->Name}>.button.middle {
					margin-top: -".($this->Height*0.25)."px;
					height: ".($this->Height*1.25)."px;
					border-radius: 100% 100% 0px 0px;
					box-shadow: var(--shadow-1);
					border-left: none !important;
					border-right: none !important;
					border-bottom: none !important;
					outline: none !important;
				}
				.{$this->Name}>.button.middle:hover{
					box-shadow:var(--shadow-2);
				}

				.{$this->Name}>.button.right{
					border-radius: 35% 0px 0px 0px;
				}
				.{$this->Name}>.button.left{
					border-radius: 0px 35% 0px 0px;
				}":""
			).($this->AllowSides?"
				.{$this->Name}>.button.first{
					border-radius: 50% 0px 0px 0px;
				}
				.{$this->Name}>.button.last{
					border-radius: 0px 50% 0px 0px;
				}":"")
		);
	}

	public function Get(){
		return Convert::ToString(function(){
            $rtl = \_::$Back->Translate->Direction == "rtl";
            yield parent::Get();
            $count = 0;
            foreach ($this->Items as $item)
                if(auth(getValid($item,"Access" ,\_::$Config->VisitAccess)))
					$count++;
            if($count > 0){
                $size = 100 / $count;
                $msize = 100 - $size * ($count-1);
				$i = 1;
                foreach ($this->Items as $item)
					if(auth(getValid($item,"Access" ,\_::$Config->VisitAccess))) {
                        $m = $count/floatval(2.0);
                        $cls = "";
                        $ism = false;
                        if($i === 1) $cls = $rtl?"last":"first";
                        elseif($i === $count) $cls = $rtl?"first":"last";
                        elseif(($i <= $m) && (($i+1) >= $m)) $cls = $rtl?"right":"left";
                        elseif((($i-1) >= $m) && ($i >= $m)) $cls = $rtl?"left":"right";
                        elseif($ism =((($i-1) <= $m) && (($i+1) >= $m))) $cls = "middle";
                        yield Html::Button(
							Html::Division(
								Html::Media(
									Html::Span(getBetween($item, "Title" , 'Name' )),
									getBetween($item, "Image" , 'Icon')
								)
							)
							, getBetween($item, 'Path', "Link" )??""
						, ['class'=>$cls], get($item,"Attributes")??[]);
						$i++;
                    }
            }
        });
	}
}
?>