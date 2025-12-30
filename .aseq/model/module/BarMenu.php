<?php namespace MiMFa\Module;
use MiMFa\Library\Struct;
use MiMFa\Library\Convert;
class BarMenu extends Module{
	public $Tag = "nav";
	public $Items = null;
	public $AllowLabels = false;
	public $AllowAnimate = true;
	public $AllowMiddle = false;
	public $AllowSides = false;
	public $AllowChangeColor = true;
	public $ShowFromScreenSize = "sm";
	public $Height = 40;
    public $Printable = false;

	public function GetStyle(){
		return parent::GetStyle().Struct::Style("
			.{$this->Name}{
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
				".\MiMFa\Library\Style::UniversalProperty("transition","var(--transition-1)")."
			}

			.{$this->Name}:hover{
				".\MiMFa\Library\Style::UniversalProperty("filter", "drop-shadow(0px 0px 30px #00000088)")."
				".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
			}

			.{$this->Name} :is(button, .button, .icon[onclick]){
				border: var(--border-0);
				border-radius: var(--radius-0);
				box-shadow: var(--shadow-0);
			}
			.{$this->Name} :is(button, .button, .icon[onclick]):hover{
				box-shadow: var(--shadow-2);
			}
			
			.{$this->Name} .button {
				border: none;
				display: flex;
				border-radius: var(--radius-0);
				text-align: center;
				align-content: center;
				justify-content: center;
				align-items: center;
    			flex-direction: column;
				height: {$this->Height}px;
				aspect-ratio: 1;
			}
			.{$this->Name} .button:hover {
				cursor: pointer;
				border: none;
				".(($this->AllowAnimate)?"background-color: var(--fore-color-output);
				color: var(--back-color-output);":"").
				\MiMFa\Library\Style::UniversalProperty("transition","var(--transition-1)")."
			}

			.{$this->Name} .button>.media{
				height: 55%;
    			margin: 15%;".
				\MiMFa\Library\Style::UniversalProperty("transition","var(--transition-1)")."
			}
			.{$this->Name} .button>.media:not(.icon){
				background-position: center;
				background-repeat: no-repeat;
				background-size: auto 60%;
				". (($this->AllowChangeColor)? \MiMFa\Library\Style::ToggleFilter():"")."
			}
			.{$this->Name} .button:hover>.media{
				background-size: auto 70%;".
				(($this->AllowAnimate)? \MiMFa\Library\Style::UniversalProperty("filter","none"):"").
				\MiMFa\Library\Style::UniversalProperty("transition","var(--transition-1)")."
			}

			.{$this->Name} .button>.media>span {
				text-shadow: 0px 5px 10px #000000aa;
				display:none;
			}
			".(($this->AllowLabels)?"
			.{$this->Name} .button:hover>.media>span {
				display:block;
			}
			":"")."

			.{$this->Name} .button:not(:last-child) {
				border-right: none; /* Prevent double borders */
			}
			".
			(($this->AllowMiddle)?"
				.{$this->Name} .button.middle {
					margin-top: -".($this->Height*0.25)."px;
					height: ".($this->Height*1.25)."px;
					border-radius: 100% 100% 0px 0px;
					box-shadow: var(--shadow-1);
					border-left: none !important;
					border-right: none !important;
					border-bottom: none !important;
					outline: none !important;
				}
				.{$this->Name} .button.middle:hover{
					box-shadow:var(--shadow-2);
				}

				.{$this->Name} .button.right{
					border-radius: 35% 0px 0px 0px;
				}
				.{$this->Name} .button.left{
					border-radius: 0px 35% 0px 0px;
				}":""
			).($this->AllowSides?"
				.{$this->Name} .button.first{
					border-radius: 50% 0px 0px 0px;
				}
				.{$this->Name} .button.last{
					border-radius: 0px 50% 0px 0px;
				}":"")
		);
	}

	public function Get(){
		return Convert::ToString(function(){
            $rtl = \_::$Front->Translate->Direction == "rtl";
            yield parent::Get();
            $count = 0;
            foreach ($this->Items as $item)
                if(\_::$User->HasAccess(getValid($item,"Access" ,\_::$User->VisitAccess)))
					$count++;
            if($count > 0){
                $size = 100 / $count;
                $msize = 100 - $size * ($count-1);
				$i = 1;
                foreach ($this->Items as $item)
					if(\_::$User->HasAccess(getValid($item,"Access" ,\_::$User->VisitAccess))) {
                        $m = $count/floatval(2.0);
                        $cls = "";
                        $ism = false;
                        if($i === 1) $cls = $rtl?"last":"first";
                        elseif($i === $count) $cls = $rtl?"first":"last";
                        elseif(($i <= $m) && (($i+1) >= $m)) $cls = $rtl?"right":"left";
                        elseif((($i-1) >= $m) && ($i >= $m)) $cls = $rtl?"left":"right";
                        elseif($ism =((($i-1) <= $m) && (($i+1) >= $m))) $cls = "middle";
                        yield Struct::Button(
							Struct::Media(
								$this->AllowLabels?Struct::Span(getBetween($item, "Title" , 'Name' )):null,
								getBetween($item, "Image" , 'Icon')
							)
							, get($item, 'Path')??""
						, ['class'=>"item $cls"], get($item,"Attributes")??[]);
						$i++;
                    }
            }
        });
	}
}