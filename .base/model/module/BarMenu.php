<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
use MiMFa\Library\Convert;
class BarMenu extends Module{
	public $Capturable = true;
	public $Items = null;
	public $AllowLabels = false;
	public $AllowAnimate = true;
	public $AllowMiddle = true;
	public $AllowSides = false;
	public $AllowChangeColor = true;
	public $VisibleFromScreenSize = "sm";
	public $Height = 40;

	public function GetStyle(){
		return parent::GetStyle().HTML::Style("
			.{$this->Name}{
				color: var(--ForeColor-2);
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
				box-shadow: -5px 0px 20px #00000025;
				border: none;
				line-height: 100%;
				z-index: 999999;
			}

			.{$this->Name}:hover{
				box-shadow: -5px 0px 20px #00000045;
				".\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}

			.{$this->Name}>a {
				text-align: center;
				display: table-cell;
			}
			.{$this->Name}>a>.button {
				background-color: ".\_::$TEMPLATE->BackColor(2)."dd;
				background-image: var(--Url-Overlay-0);
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover;
				color: var(--ForeColor-2);
				height: {$this->Height}px;
				display: inline-table;
				cursor: pointer;
			}

			.{$this->Name}>a>.button:hover{
				".(($this->AllowAnimate)?"background-color: var(--ForeColor-2);
				color: var(--BackColor-2);":"").
				\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}

			.{$this->Name}>a>.button>div{
				height: 100%;
				background-position: center;
				background-repeat: no-repeat;
				background-size: auto 60%;
				color: var(--ForeColor-2);".
				(($this->AllowChangeColor)? \MiMFa\Library\Style::ToggleFilter():"").
				\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name}>a>.button:hover>div{
				background-size: auto 70%;".
				(($this->AllowAnimate)? \MiMFa\Library\Style::UniversalProperty("filter","none"):"").
				\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}

			.{$this->Name}>a>.button>div>span {
				text-shadow: 0px 5px 10px #000000aa;
				display:none;
			}
			".(($this->AllowLabels)?"
			.{$this->Name}>a>.button:hover>div>span {
				display:block;
			}
			":"")."

			.{$this->Name}>a>.button:not(:last-child) {
				border-right: none; /* Prevent double borders */
			}
			".
			(($this->AllowMiddle)?"
				.{$this->Name}>a>.button.middle {
					margin-top: -".($this->Height*0.25)."px;
					height: ".($this->Height*1.25)."px;
					border-radius: 100% 100% 0px 0px;
					box-shadow: var(--Shadow-1);
					border-left: none !important;
					border-right: none !important;
					border-bottom: none !important;
					outline: none !important;
				}
				.{$this->Name}>a>.button.middle:hover{
					box-shadow:var(--Shadow-2);
				}

				.{$this->Name}>a>.button.right{
					border-radius: 35% 0px 0px 0px;
				}
				.{$this->Name}>a>.button.left{
					border-radius: 0px 35% 0px 0px;
				}":""
			).($this->AllowSides?"
				.{$this->Name}>a>.button.first{
					border-radius: 50% 0px 0px 0px;
				}
				.{$this->Name}>a>.button.last{
					border-radius: 0px 50% 0px 0px;
				}":"")
		);
	}

	public function Get(){
		return Convert::ToString(function(){
            $rtl = \MiMFa\Library\Translate::$Direction == "RTL";
            yield parent::Get();
            $count = 0;
            foreach ($this->Items as $item)
                if(getAccess(getValid($item,"Access",\_::$CONFIG->VisitAccess)))
					$count++;
            if($count > 0){
                $size = 100 / $count;
                $msize = 100 - $size * ($count-1);
				$i = 1;
                foreach ($this->Items as $item)
					if(getAccess(getValid($item,"Access",\_::$CONFIG->VisitAccess))) {
                        $m = $count/floatval(2.0);
                        $cls = "";
                        $ism = false;
                        if($i === 1) $cls = $rtl?"last":"first";
                        elseif($i === $count) $cls = $rtl?"first":"last";
                        elseif(($i <= $m) && (($i+1) >= $m)) $cls = $rtl?"right":"left";
                        elseif((($i-1) >= $m) && ($i >= $m)) $cls = $rtl?"left":"right";
                        elseif($ism =((($i-1) <= $m) && (($i+1) >= $m))) $cls = "middle";
                        //yield HTML::Link(
                        //    HTML::Division(
                        //        HTML::Image(
                        //            HTML::Span(
                        //                __(getValid($this->Items[$i],'Name'), styling:false)
                        //            ),
                        //            getValid($this->Items[$i],'Image')??getValid($this->Items[$i],'Icon')
                        //        )
                        //        ,["class"=>"button $cls", "style"=>"width:".($ism?$msize:$size)."vw;"]
                        //    )
                        //    , getValid($this->Items[$i],'Path')??getValid($this->Items[$i],'Link'), getValid($this->Items[$i],"Attributes")
                        //);
						$hr = getBetween($item, "Link", 'Path');
                        yield
                            "<a ".getValid($item,"Attributes").(isValid($hr)?" href='$hr'":"").">
								<div class='button $cls' style='width:".($ism?$msize:$size)."vw;'>
									<div style=\"background-image: url('".getBetween($item, "Image", 'Icon')."')\">
										<span>".__(getBetween($item, "Title", 'Name'))."</span>
									</div>
								</div>
							</a>";
						$i++;
                    }
            }
        });
	}
}
?>