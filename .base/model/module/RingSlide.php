<?php
namespace MiMFa\Module;
use MiMFa\Library\User;
use MiMFa\Library\HTML;
use MiMFa\Library\Convert;
class RingSlide extends Module{
	public $Capturable = true;
	public $Name = "RingSlide";
	public $Class = "row";
	public $Image = null;
	public $Items = null;
	public $AllowChangeColor = true;
	public $CenterSize = 150;
	public $ButtonsSize = 100;
	public $Path = null;

	/**
     * Create the module
     * @param array|string|null $source The module source
     */
	public function __construct($items =  null, $path = null){
        parent::__construct();
        $this->Set($items, $path);
    }
	public function Set($items =  null, $path = null){
		$this->Path = $path??(\_::$CONFIG->AllowSigning?User::$InHandlerPath:null);
		$this->Items = $items;
    }

	public function GetStyle(){
		return parent::GetStyle().HTML::Style("
			.{$this->Name} .tabs{
				max-width: 100%;
				margin-top: 15Vmin;
			}

			.{$this->Name} .tab{
				padding: 0px 5vmax;
				text-align: center;
				display:none;
			}
			.{$this->Name} .tab.active{
				display:block;
			}

			.{$this->Name} .tab .btn:hover{
				font-weight: bold;
			}

			.{$this->Name} .sign{
				text-align: center;
			}
			.{$this->Name} .sign .btn{
				font-size: var(--Size-2);
				color: var(--ForeColor-2);
				border-color: transparent;
				margin: 0px 5px;
			}
			.{$this->Name} .sign .btn:hover{
				background-color: var(--BackColor-2);
				font-size: var(--Size-2);
				color: var(--ForeColor-2);
				border-color: var(--ForeColor-2);
				border-radius: var(--Radius-2);
			}
			.{$this->Name} .menu {
				min-height: 60vh;
				display: -webkit-box;
				display: -webkit-flex;
				display: -ms-flexbox;
				display: flex;
				-webkit-box-pack: center;
				-webkit-justify-content: center;
					-ms-flex-pack: center;
						justify-content: center;
				-webkit-box-align: center;
				-webkit-align-items: center;
					-ms-flex-align: center;
						align-items: center;
				line-height: {$this->ButtonsSize}px;
				text-align: center;
				border:none;
			}

			.{$this->Name} .menu>.center {
				width: {$this->CenterSize}px;
				height: {$this->CenterSize}px;
				border-radius: 50%;
				position: relative;
				box-shadow: 0px 0px 20px var(--BackColor-2);
				".\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(2))."
			}

			.{$this->Name} .menu>.center:hover {
				box-shadow: 0px 0px 50px var(--BackColor-2);
				".\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(2))."
			}

			.{$this->Name} .menu>.center:before {
				position: absolute;
				content: '';
				width: {$this->CenterSize}px;
				height: {$this->CenterSize}px;
				font-weight: bold;
				font-size: 180%;
				left: 0px;
				top: 0px;
				background-image: url('".((\_::$INFO->User??$this)->Image??$this->Image)."');
				background-position: center;
				background-repeat: no-repeat;
				background-size: contain;
				background-color: var(--BackColor-2);
				border-radius: 100%;
				cursor: pointer;
				box-shadow: 0px 0px 20px var(--BackColor-2);
				". \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(2))."
			}

			.{$this->Name} .menu>.center>a{
				background-color: var(--BackColor-2);
				color: var(--ForeColor-2);
				position: absolute;
				text-align: center;
				cursor: pointer;
				border: var(--Border-1) var(--BackColor-2);
				border-radius: 100%;
				box-shadow: var(--Shadow-3);
				". \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(2))."
			}

			.{$this->Name} .menu>.center>a:hover {
				box-shadow: var(--Shadow-4);
				border:  var(--Border-1) var(--BackColor-2);
				". \MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(2))."
			}

			.{$this->Name} .menu>.center>a>.button{
				line-height: {$this->ButtonsSize}px;
				width: {$this->ButtonsSize}px;
				height: {$this->ButtonsSize}px;
				border-radius: 100%;
			}
			.{$this->Name} .menu>.center>a>.button>.media{
				background-size: 50% 50% !important;
				width: {$this->ButtonsSize}px;
				height: {$this->ButtonsSize}px;
                ".($this->AllowChangeColor? \MiMFa\Library\Style::DropColor(\_::$TEMPLATE->ForeColor(2)):"")."
			}
			.{$this->Name} .menu>.center>a:hover>.button>.media {
				background-size: 60% 60%;
				".\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(2))."
			}
		");
	}

	public function Get(){
		parent::Get();
		$count = count($this->Items);
		if($count > 0)
			return Convert::ToString(function() use($count){
				$btns = "";
				$tags = "";
				for($i = 0; $i < $count; $i++)
					if(getAccess(getValid($this->Items[$i],"Access",\_::$CONFIG->VisitAccess))) {
						$btns .= HTML::Link(
							HTML::Division(
								HTML::Media("", getValid($this->Items[$i],'Image'))
							,["class"=>"button"])
						, "#tab$i", ["data-target"=>".tab", "data-toggle"=>'tab']);

						$tags .= HTML::Division(
							HTML::ExternalHeading(getValid($this->Items[$i],'Name'), null, ["class"=>"title"]).
							HTML::Division(
								getValid($this->Items[$i], 'Description').
								(getValid($this->Items[$i], "Button")??getValid($this->Items[$i],"More"))
							, ["class"=>"description"])
						, ["class"=>"tab fade".($i===0?' active show':''), "id"=>"tab$i"]);
					}
				yield HTML::Division(HTML::Division(HTML::Division($btns,["class"=>"center"]),["class"=>"menu"]),["class"=>"col-md-5", "data-aos"=>"zoom-out", "data-aos-duration"=>"1000"]);
				yield HTML::Division(HTML::Division($tags,["class"=>"tabs"]),["class"=>"col-md", "data-aos"=>"zoom-in", "data-aos-duration"=>"1500"]);
			});
		else return null;
	}

	public function GetScript(){
		return parent::GetScript().(count($this->Items) > 0? HTML::Script("
			$(document).ready(function(){
				".(isValid($this->Path)?"$('.{$this->Name} .menu>.center:before').click(function () { load('{$this->Path}'); });":"")."
				const bselector = '.{$this->Name} .menu>.center>a';
				$(bselector).click(function(evt){
					const xn = $(this).attr('href');
					const tar = $(this).attr('data-target');
					const x = xn.replace('#', '');
					$(tar).each(function(){
						const y = $(this).attr('id');
						if (x == y) $(this).addClass('active show');
						else $(this).removeClass('active show');
					});
					$(bselector).each(function(){
						const y = $(this).attr('href');
						if (xn == y) $(this).addClass('active');
						else $(this).removeClass('active');
					});
					evt.preventDefault();
				});

				const buttons = Array.from(document.querySelectorAll(bselector));
				const count = buttons.length;
				const increase = Math.PI * 2 / buttons.length;
				const ratio = ({$this->CenterSize} / {$this->ButtonsSize} - 1)/2;
				const radius = {$this->CenterSize} - {$this->ButtonsSize} * ratio;
				const addition = {$this->ButtonsSize} * ratio;
				let angle = 0;

				function move(e) {
					const n = buttons.indexOf(this);
					const endAngle = (n % count) * increase;
					function turn() {
						if (Math.abs(endAngle - angle) > 1/8) {
							const sign = endAngle > angle ? 1 : -1;
							angle = angle + sign/8;
							setTimeout(turn, 20);
						} else angle = endAngle;
						buttons.forEach((button, i) => {
							button.style.top = (addition + Math.sin(Math.PI / 2 + i * increase - angle) * radius) + 'px';
							button.style.left = (addition + Math.cos(Math.PI / 2 + i * increase - angle) * radius) + 'px';
						});
					}
					turn();
				}

				buttons.forEach((button, i) => {
					button.style.top = (addition + Math.sin(Math.PI / 2 + i * increase) * radius) + 'px';
					button.style.left = (addition + Math.cos(Math.PI / 2 + i * increase) * radius) + 'px';
					button.addEventListener('click', move);
				});

			});
			"):"");
    }
}
?>