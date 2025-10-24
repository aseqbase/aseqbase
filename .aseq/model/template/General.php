<?php namespace MiMFa\Template;
use MiMFa\Library\Html;
class General extends Template
{
	public $WindowTitle = null;
	public $WindowLogo = null;
	public $BackgroundImage = null;

	public function __construct($setDefaults = true, $setRevises = true)
	{
		parent::__construct($setDefaults, $setRevises);
        component("Manifest");
		component("Global");
        component("JsonLD");
        component("View");
        component("Be");
		component("Live");
		component("Evaluate");
		component("FontFace");
		component("Icons");
        component("ShortcutKey");
		component("JQuery");
		component("Bootstrap");
		component("AOS");
	}

	public function RenderInitial()
	{
		parent::RenderInitial();
		style("
				body {
				  	display: flex;
					flex-direction: column;
    				justify-content: space-between;
					margin: 0;
					overflow-x: hidden;
					min-height: 100vh;
					background-color: var(--back-color);
					color: var(--fore-color);
					" . \MiMFa\Library\Style::UniversalProperty("font-smoothing", "antialiased") . "
				}

				::-webkit-scrollbar {
					background-color: transparent;
					width: 10px;
					height: 10px;
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
				::-webkit-scrollbar:hover {
					background-color: var(--back-color-input);
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
				::-webkit-scrollbar-track {
					background-color: transparent;
					box-shadow: var(--shadow-2);
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
				::-webkit-scrollbar-track:hover {
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
				::-webkit-scrollbar-thumb {
					//border: var(--border-1) var(--back-color-output);
					background-color: #8884;
					border-radius: 2px;
					box-shadow: var(--shadow-2);
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
				::-webkit-scrollbar-thumb:hover{
					background-color: var(--back-color-special-output);
					border-radius: 0px;
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
				body>.background-screen{
					background-image: " . (isValid($this->BackgroundImage) ? "url('" . $this->BackgroundImage . "')" : "var(--overlay-url-0)") . "
				}
				body>.result{
					position: fixed;
					top: var(--size-max);
					left: 0px;
					right: 0px;
					font-size: var(--size-min);
					background-color: var(--back-color-special);
					border: var(--border-2);
					padding: calc(var(--size-min) / 2) var(--size-min);
					display: flex;
					align-content: center;
					align-items: center;
					gap: var(--size-min);
					z-index: 1111;
				}
				body>.result{
					position: fixed;
					top: var(--size-max);
					left: 0px;
					right: 0px;
					z-index: 1111;
				}
				body .result>.division{
				    width: -webkit-fill-available;
				}
				body .result.message{
					background-color: var(--color-blue);
					color: var(--color-white);
				}
				body .result.success{
					background-color: var(--color-green);
					color: var(--color-white);
				}
				body .result.error{
					background-color: var(--color-red);
					color: var(--color-white);
				}
				body .result.warning{
					background-color: var(--color-yellow);
					color: var(--color-white);
				}
				.page{
				    flex-grow: 1;
				}
				:is(h1,h2,h3,h4,h5,h6) strong{
					font-weight: normal;
				}
				
				.heading,h1,h2,h3,h4,h5,h6{
					margin-top: var(--size-3);
				}	
				.heading1, h1{
					text-align: center;
					text-transform: uppercase;
					margin-top: max(4vmax, var(--size-5));
					margin-bottom: var(--size-2);
				}
				.heading2, h2{
					text-align: center;
					text-transform: uppercase;
					margin-top: var(--size-4);
				}
				.heading4, h4{
					margin-top: var(--size-2);
				}
				.heading5, h5{
					margin-top: var(--size-1);
				}
				.heading6, h6{
					margin-top: var(--size-1);
					padding-inline-end: var(--size-1);
				}
				:is(.heading6, h6):before{
					display: block;
				}

				p{
					text-align: justify;
				}

				quote{
					background-color: #8881;
					padding-left: 3px;
					padding-right: 3px;
				}
				quote:hover{
                    display: inline;
					background-color: #8883;
					font-weight: bold;
				}
				blockquote{
					background-color: #8883;
					padding: var(--size-1);
				}

				code{
                    display: inline;
					background-color: #8883;
					padding-left: var(--size-0);
					padding-right: var(--size-0);
					border-inline-start: 3px solid var(--back-color-output);
				}
				blockcode{
					background-color: #8883;
					padding: var(--size-0);
					display: block;
					max-height: 95vh;
					overflow-y: scroll;
					text-wrap: auto;
					border-inline-start: 3px solid var(--back-color-output);
				}

				.table :is(thead,tbody,tfoot,tr,td,th) {
					color: var(--fore-color-input);
					background-color: var(--back-color-input);
				}

				a, a:visited, a:active, a:hover{
					color: inherit;
					text-decoration: none;
				}
				:not(ol,ul,ll,header,footer,.items,.header,.footer,li,lt,ld):hover>:is(a,a:visited,a:active):not(.button,.icon,.image,.media,.item,.fa){
					text-decoration: underline;
				}
				a:not(.button, .icon):hover{
					font-weight: bold;
				}

				:is(.button, .icon[onclick]), :is(.button, .icon[onclick]):is(:visited, :active){
					border: var(--border-1) transparent;
				}
				:is(.button, .icon[onclick]):is(:hover, :focus){
					border-color: var(--fore-color-output);
				}

				.icon[onclick]{
					text-decoration: none;
					display: inline-flex;
					padding: calc(var(--size-0) / 2);
					border: var(--border-1) transparent;
					cursor: pointer;
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
                .icon[onclick]:hover {
                    background-color:var(--back-color-special-output);
                	color:var(--fore-color-special-output);
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}

				.button{
					text-decoration: none;
					display: inline-flex;
					flex-wrap: wrap;
					justify-content: center;
					flex-direction: column;
					align-items: center;
					border-radius: var(--radius-1);
					padding: calc(var(--size-3) / 2) var(--size-3);
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
				.button:hover{
					background-color: var(--back-color-output);
					color: var(--fore-color-output);
                	text-decoration: none;
					border-color: var(--fore-color-output);
					border-radius: var(--radius-0);
					box-shadow: var(--shadow-2);
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
				.button.outline{
					border: var(--border-2) var(--fore-color);
				}
				.button.block {
					width: 100%;
				}
				.button.main, .button.main:is(:visited, :active) {
					background-color: var(--back-color-5);
					color: var(--fore-color-5);
				}
				.button.main:hover{
					background-color: var(--fore-color-5);
					color: var(--back-color-5);
					box-shadow: var(--shadow-3);
				}
				.button.alt, .button.alt:is(:visited, :active) {
					background-color: var(--back-color-output);
					color: var(--fore-color-output);
				}
				.button.alt:hover{
					background-color: var(--fore-color-output);
					color: var(--back-color-output);
				}

				.button.outline, .button.outline:is(:visited, :active){
					border-color: var(--back-color);
				}
				.button.outline:hover{
					border-color: var(--fore-color);
				}
				.button.outline.main, .button.outline.main:is(:visited, :active) {
					border-color: var(--fore-color-5);
				}
				.button.outline.main:hover{
					border-color: var(--back-color-5);
				}
				.button.outline.alt, .button.outline.alt:is(:visited, :active) {
					border-color: var(--fore-color-output);
				}
				.button.outline.alt:hover, .button.outline.alt:is(:visited, :active):hover {
					border-color: var(--back-color-output);
				}

				input[type='range'].rangeinput {
					height: calc(var(--size-0) / 2);
					outline: none;
					-webkit-appearance: none;
					border: var(--border-1) var(--fore-color-output);
					border-radius: var(--radius-1);
				}
				input[type='range'].rangeinput::-webkit-slider-thumb {
					width: var(--size-1);
					height: var(--size-1);
					cursor: ew-resize;
					-webkit-appearance: none;
					background-color: var(--back-color-output);
					border-radius: var(--radius-5);
				}

				.media{
					background-position: center;
					background-repeat: no-repeat;
					background-size: contain;
				}

				.page{
					line-height: 1.5;
					padding: var(--size-3);
				}
				content>.page{
					margin-top: 5vmax;
				}
				content>.page:is(.external-page, .embed-page)>.frame{
					min-Height: calc(100vh - 70px);
				}
				content>.page h1 {
					font-weight: bold;
				}
				content>.page h1 sub {
					font-weight: normal;
				}
				content>.page p {
					margin: var(--size-1) 0;
				}
				content>.page :is(ul, ol) {
					margin: var(--size-2) 0;
					margin-inline-start: var(--size-5);
				}
				content>.page li {
					margin: var(--size-0) 0;
				}
				content>.page>.frame>.page{
					color: var(--fore-color-input);
					background-color: var(--back-color-input);
					border: var(--border-1) var(--back-color-input);
					box-shadow: var(--shadow-2);
					border-radius: var(--radius-1);
					padding: var(--size-0) var(--size-3) var(--size-4) var(--size-3);
					margin: var(--size-2) var(--size-3) var(--size-1) var(--size-3);
				}

				footer{
					color: var(--fore-color-special);
					background-color: var(--back-color-special);
					margin-top: var(--size-4);
					padding-top: var(--size-5);
					padding-bottom: var(--size-5);
				}
				body>footer :is(button, .button){
					box-shadow: var(--shadow-0);
				}
	
				.tabs>.tab-titles>.tab-title>:is(*,*:hover){
					border:none;
					outline:none;
				}
				.tabs>.tab-titles>.tab-title{
					display:inline-block;
					padding:calc(var(--size-1) / 5) calc(var(--size-1) / 2);
					border-bottom: var(--border-1) #8885;
				}
				.tabs>.tab-titles>.tab-title.active{
					border: var(--border-1) #8888;
					border-bottom: none;
				}
        
				.main-bullet{
					list-style-type: none;
					text-align: left;
					padding-left: 25px;
					line-height: 1.3rem;
					font-size: 0.9rem;
				}

				.main-bullet li:before{
					content: '○';
					color: var(--fore-color-output);
					padding-right: 12px;
				}
				.main-bullet li{
					text-indent: -25px;
					padding: 5px 0px;
				}

				.interactive-table{
					margin-left: auto;
					margin-right: auto;
					border-collapse: separate;
				}
				.interactive-table td{
					padding: 10px;
				}
				.interactive-table tr{
					padding: 10px 20px;
					border: var(--border-1) transparent;
					border-radius: var(--radius-1);
					cursor: pointer;
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
				.interactive-table tr:hover{
					border-color: var(--back-color-special-input);
					background-color: var(--fore-color-special-input);
					color: var(--back-color-special-input);
					box-shadow: var(--shadow-2);
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
		");
		echo Html::Script("
			for(const slider of document.querySelectorAll(`input[type='range'].rangeinput`)){
				const min = slider.min
				const max = slider.max
				const value = slider.value
				slider.style.background = `linear-gradient(to right, var(--fore-color-input) 0%, var(--fore-color-input) \${(value-min)/(max-min)*100}%, var(--back-color-input) \${(value-min)/(max-min)*100}%, var(--back-color-input) 100%)`
				slider.oninput = function() {
					this.style.background = `linear-gradient(to right, var(--fore-color-input) 0%, var(--fore-color-input) \${(this.value-this.min)/(this.max-this.min)*100}%, var(--back-color-input) \${(this.value-this.min)/(this.max-this.min)*100}%, var(--back-color-input) 100%)`
				};
			}
		");
	}
	public function RenderMain()
	{
		parent::RenderMain();
		if (isValid($this->BackgroundImage))
			echo "<div class='background-screen'></div>";
	}
}