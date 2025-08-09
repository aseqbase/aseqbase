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
		echo Html::Style("
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
					background-color: var(--back-color-inside);
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
					//border: var(--border-1) var(--back-color-outside);
					background-color: #8884;
					border-radius: 2px;
					box-shadow: var(--shadow-2);
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
				::-webkit-scrollbar-thumb:hover{
					background-color: var(--back-color-special-outside);
					border-radius: 0px;
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
				body>.background-screen{
					background-image: " . (isValid($this->BackgroundImage) ? "url('" . $this->BackgroundImage . "')" : "var(--overlay-url-0)") . "
				}
				.page{
				    flex-grow: 1;
				}
				:is(h1,h2,h3,h4,h5,h6) strong{
					font-weight: normal;
				}
				h1{
					font-size: var(--size-5);
					text-align: center;
					text-transform: uppercase;
				}
				h2{
					font-size: var(--size-4);
					text-align: center;
					text-transform: uppercase;
				}
				h3{
					font-size: var(--size-3);
					text-transform: uppercase;
				}
				h4{
					font-size: var(--size-2);
				}
				h5{
					font-size: var(--size-1);
				}
				h6{
					font-size: var(--size-1);
					display: inline-block;
				}
				h6:before{
					display: block;
				}
				
				.heading{
					margin-top: var(--size-3);
				}	
				.externalheading{
					margin-top: max(4vmax, var(--size-5));
					margin-bottom: var(--size-2);
				}
				.superheading{
					margin-top: var(--size-4);
				}
				.subheading{
					margin-top: var(--size-2);
				}
				.internalheading{
					margin-top: var(--size-1);
				}
				.inlineheading{
					margin-top: var(--size-1);
					padding-inline-end: var(--size-1);
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
					border-inline-start: 3px solid var(--back-color-outside);
				}
				blockcode{
					background-color: #8883;
					padding: var(--size-0);
					display: block;
					max-height: 95vh;
					overflow-y: scroll;
					text-wrap: auto;
					border-inline-start: 3px solid var(--back-color-outside);
				}

				.table :is(thead,tbody,tfoot,tr,td,th) {
					color: var(--fore-color-inside);
					background-color: var(--back-color-inside);
				}

				:not(ol,ul,ll,header,footer,.items,.header,.footer,li,lt,ld):hover>:is(a,a:visited,a:active):not(.button,.icon,.image,.media,.item,.fa){
					font-weight: bold;
				}

				a, a:visited, a:active, a:hover{
					color: inherit;
					text-decoration: none;
				}
				a:not(.button, .icon):hover{
					text-decoration: underline;
				}

				:is(.button, .icon[onclick]), :is(.button, .icon[onclick]):is(:visited, :active){
					border: var(--border-1) transparent;
				}
				:is(.button, .icon[onclick]):is(:hover, :focus){
					border-color: var(--fore-color-outside);
				}

				.icon[onclick]{
					text-decoration: none;
					display: initial;
					padding: calc(var(--size-0) / 2);
					border: var(--border-1) transparent;
					cursor: pointer;
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
                .icon[onclick]:hover {
                    background-color:var(--back-color-special-outside);
                	color:var(--fore-color-special-outside);
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
					background-color: var(--back-color-outside);
					color: var(--fore-color-outside);
                	text-decoration: none;
					border-color: var(--fore-color-outside);
					border-radius: var(--radius-0);
					box-shadow: var(--shadow-2);
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
				.button.block {
					width: 100%;
				}
				.button.main, .button.main:is(:visited, :active) {
					background-color: var(--back-color-5);
					color: var(--fore-color-5);
					border-color: var(--back-color-5);
				}
				.button.main:hover{
					background-color: var(--back-color-2);
					color: var(--fore-color-2);
					border-color: var(--back-color-2);
					border-radius: var(--radius-0);
					box-shadow: var(--shadow-3);
				}
				.button.primary, .button.primary:is(:visited, :active) {
					background-color: var(--back-color-special-outside);
					color: var(--fore-color-special-outside);
					border-color: var(--back-color-special-outside);
				}
				.button.primary:hover{
					background-color: var(--fore-color-special-outside);
					color: var(--back-color-special-outside);
					border-color: var(--back-color-special-outside);
				}
				.button.secondary, .button.secondary:is(:visited, :active) {
					background-color: var(--back-color-outside);
					color: var(--fore-color-outside);
					border-color: var(--back-color-outside);
				}

				.button.outline, .button.outline:is(:visited, :active){
					background-color: var(--back-color);
					color: var(--fore-color);
					border-color: var(--back-color);
				}
				.button.outline:hover{
					background-color: var(--fore-color);
					color: var(--back-color-outside);
					border-color: var(--back-color-outside);
				}
				.button.outline.primary, .button.outline.primary:is(:visited, :active) {
					border-color: var(--fore-color-special-outside);
				}
				.button.outline.primary:hover{
					border-color: var(--back-color-special-outside);
				}
				.button.outline.secondary, .button.outline.secondary:is(:visited, :active) {
					border-color: var(--fore-color-outside);
				}
				.button.outline.secondary:hover, .button.outline.secondary:is(:visited, :active):hover {
					border-color: var(--back-color-outside);
				}
				:is(a, .button, .icon):deactive {
					" . \MiMFa\Library\Style::UniversalProperty("filter", "grayscale(100)") . "
				}

				input[type='range'].rangeinput {
					height: calc(var(--size-0) / 2);
					outline: none;
					-webkit-appearance: none;
					border: var(--border-1) var(--fore-color-outside);
					border-radius: var(--radius-1);
				}
				input[type='range'].rangeinput::-webkit-slider-thumb {
					width: var(--size-1);
					height: var(--size-1);
					cursor: ew-resize;
					-webkit-appearance: none;
					background-color: var(--back-color-outside);
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
					color: var(--fore-color-inside);
					background-color: var(--back-color-inside);
					border: var(--border-1) var(--back-color-inside);
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

				.main-bullet{
					list-style-type: none;
					text-align: left;
					padding-left: 25px;
					line-height: 1.3rem;
					font-size: 0.9rem;
				}

				.main-bullet li:before{
					content: '○';
					color: var(--fore-color-outside);
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
					border-color: var(--back-color-special-inside);
					background-color: var(--fore-color-special-inside);
					color: var(--back-color-special-inside);
					box-shadow: var(--shadow-2);
					" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
				}
		");
		echo Html::Script("
			for(const slider of document.querySelectorAll(`input[type='range'].rangeinput`)){
				const min = slider.min
				const max = slider.max
				const value = slider.value
				slider.style.background = `linear-gradient(to right, var(--fore-color-inside) 0%, var(--fore-color-inside) \${(value-min)/(max-min)*100}%, var(--back-color-inside) \${(value-min)/(max-min)*100}%, var(--back-color-inside) 100%)`
				slider.oninput = function() {
					this.style.background = `linear-gradient(to right, var(--fore-color-inside) 0%, var(--fore-color-inside) \${(this.value-this.min)/(this.max-this.min)*100}%, var(--back-color-inside) \${(this.value-this.min)/(this.max-this.min)*100}%, var(--back-color-inside) 100%)`
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