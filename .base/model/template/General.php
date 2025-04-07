<?php
namespace MiMFa\Template;
use MiMFa\Library\Html;
class General extends Template{
	public $WindowTitle = null;
	public $WindowLogo = null;
	public $BackgroundImage = null;

	public function __construct($setDefaults = true, $setRevises = true){
        parent::__construct($setDefaults, $setRevises);
		component("JQuery");
		component("Bootstrap");
		component("AOS");
    }

	public function RenderInitial(){
		parent::RenderInitial();
		echo Html::Style("
				body {
					font: var(--size-1) var(--font-0), var(--font-1), var(--font-2);
					overflow-x: hidden;
					min-height: 100vh;
					background-color: var(--back-color-0);
					color: var(--fore-color-0);
					".\MiMFa\Library\Style::UniversalProperty("font-smoothing","antialiased")."
				}

				::-webkit-scrollbar {
					background-color: transparent;
					width: 10px;
					height: 10px;
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				::-webkit-scrollbar:hover {
					background-color: var(--back-color-1);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				::-webkit-scrollbar-track {
					background-color: transparent;
					box-shadow: var(--shadow-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				::-webkit-scrollbar-track:hover {
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				::-webkit-scrollbar-thumb {
					//border: var(--border-1) var(--back-color-2);
					background-color: ".\_::$Front->BackColor(2)."33;
					border-radius: 2px;
					box-shadow: var(--shadow-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				::-webkit-scrollbar-thumb:hover {
					background-color: var(--back-color-2);
					border-radius: 0px;
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				body>.background-screen{
					background-image: ".(isValid($this->BackgroundImage)? "url('".$this->BackgroundImage."')" : "var(--overlay-url-0)")."
				}

				:is(h1,h2,h3,h4,h5,h6) strong{
					font-weight: normal;
				}
				h1{
					font-size: var(--size-5);
					text-align: center;
					text-transform: uppercase;
					margin-top: max(4vmax, var(--size-5));
					margin-bottom: var(--size-2);
				}
				h2{
					font-size: var(--size-4);
					text-align: center;
					text-transform: uppercase;
					margin-top: var(--size-4);
				}
				h3{
					font-size: var(--size-3);
					text-transform: uppercase;
					margin-top: var(--size-3);
				}
				h4{
					font-size: var(--size-2);
					margin-top: var(--size-2);
				}
				h5{
					font-size: var(--size-1);
					margin-top: var(--size-1);
				}
				h6{
					font-size: var(--size-1);
					display: inline-block;
					margin-top: var(--size-1);
				}
				h6:before{
					display: block;
				}

				p{
					text-align: justify;
				}

				quote{
					background-color: #8881;
					padding-left: 3px;
					padding-right: 3px;
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
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
					border-inline-start: 3px solid var(--back-color-2);
				}
				blockcode{
					background-color: #8883;
					padding: var(--size-0);
					display: block;
					max-height: 95vh;
					overflow-y: scroll;
					text-wrap: auto;
					border-inline-start: 3px solid var(--back-color-2);
				}


				a, a:visited, a:active, a:hover{
					color: inherit;
					text-decoration: none;
				}
				a:not(.button, .icon, .btn):hover{
					text-decoration: underline;
				}
				:not(ol,ul,ll,header,footer,.items,.header,.footer,li,lt,ld):hover>:is(a,a:visited,a:active):not(.button,.icon,.btn,.image,.media,.item,.fa){
					font-weight: bold;
				}
				:is(.button, .icon, .btn), :is(.button, .icon, .btn):is(:visited, :active){
					border: var(--border-1) transparent;
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				:is(.button, .icon, .btn):is(:hover, :focus){
					border-color: var(--fore-color-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
                :is(a.btn, .btn), :is(a.btn, .btn):is(:visited, :active) {
					text-decoration: none;
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				.btn, .btn:is(:visited, :active){
					display: inline-grid;
					align-items: center;
					background-color: var(--back-color-1);
					color: var(--fore-color-1);
					border-color: var(--back-color-1);
					font-size: var(--size-1);
					border-radius: var(--radius-1);
					padding: calc(var(--size-0) / 3) var(--size-1);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				a .icon{
					text-decoration: none;
					display: initial;
					padding: calc(var(--size-0) / 2);
					border: var(--border-1) transparent;
				}
                a .icon:hover {
                    background-color:var(--back-color-5);
                	color:var(--fore-color-5);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				.btn:hover{
                	text-decoration: none;
					background-color: var(--back-color-2);
					color: var(--fore-color-2);
					border-color: var(--fore-color-2);
					border-radius: var(--radius-0);
					box-shadow: var(--shadow-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				.btn-block {
					width: 100%;
				}
				.btn-main, .btn-main:is(:visited, :active) {
					background-color: var(--back-color-2);
					color: var(--fore-color-2);
					border-color: var(--back-color-2);
				}
				.btn-main:hover{
					background-color: var(--back-color-4);
					color: var(--fore-color-4);
					border-color: var(--back-color-4);
					border-radius: var(--radius-0);
					box-shadow: var(--shadow-3);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				.btn-primary, .btn-primary:is(:visited, :active) {
					background-color: var(--back-color-2);
					color: var(--fore-color-2);
					border-color: var(--back-color-2);
				}
				.btn-primary:hover{
					background-color: var(--back-color-4);
					color: var(--fore-color-4);
					border-color: var(--back-color-4);
					border-radius: var(--radius-0);
					box-shadow: var(--shadow-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				.btn-secondary, .btn-secondary:is(:visited, :active) {
					background-color: var(--back-color-4);
					color: var(--fore-color-4);
					border-color: var(--back-color-4);
				}

				.btn-outline, .btn-outline:is(:visited, :active){
					background-color:  var(--back-color-1);
					color: var(--fore-color-1);
					border-color: var(--fore-color-1);
					font-size: var(--size-1);
					border-radius: var(--radius-1);
					padding: calc(var(--size-0) / 3) var(--size-1);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				.btn-outline:hover{
					background-color:  var(--back-color-2);
					color: var(--fore-color-2);
					border-color: var(--fore-color-2);
					border-radius: var(--radius-0);
					box-shadow: var(--shadow-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				.btn-outline-primary, .btn-outline-primary:is(:visited, :active) {
					background-color: var(--back-color-2);
					color: var(--fore-color-2);
					border-color: var(--fore-color-2);
				}
				.btn-outline-primary:hover{
					background-color: var(--back-color-4);
					color: var(--fore-color-4);
					border-color: var(--fore-color-4);
					border-radius: var(--radius-0);
					box-shadow: var(--shadow-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				.btn-outline-secondary, .btn-outline-secondary:is(:visited, :active) {
					background-color: var(--back-color-4);
					color: var(--fore-color-4);
					border-color: var(--fore-color-4);
				}
				:is(a, .button, .icon, .btn):deactive {
					".\MiMFa\Library\Style::UniversalProperty("filter","grayscale(100)")."
				}

				input[type='range'].rangeinput {
					height: calc(var(--size-0) / 2);
					outline: none;
					-webkit-appearance: none;
					border: var(--border-1) var(--fore-color-2);
					border-radius: var(--radius-1);
				}
				input[type='range'].rangeinput::-webkit-slider-thumb {
					width: var(--size-1);
					height: var(--size-1);
					cursor: ew-resize;
					-webkit-appearance: none;
					background-color: var(--back-color-2);
					border-radius: var(--radius-5);
				}

				.side-image{
					background-position: center;
					background-repeat: no-repeat;
					background-size: 70% auto;
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
					color: var(--fore-color-1);
					background-color: ".\_::$Front->BackColor(1)."88;
					border: var(--border-1) var(--back-color-1);
					box-shadow: var(--shadow-2);
					border-radius: var(--radius-1);
					padding: var(--size-0) var(--size-3) var(--size-4) var(--size-3);
					margin: var(--size-2) var(--size-3) var(--size-1) var(--size-3);
				}

				footer{
					padding-bottom: 50px;
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
					color: var(--fore-color-2);
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
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
				.interactive-table tr:hover{
					border-color: var(--back-color-4);
					background-color: var(--fore-color-4);
					color: var(--back-color-4);
					box-shadow: var(--shadow-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")."
				}
		");
		echo Html::Script("
			for(const slider of document.querySelectorAll(`input[type='range'].rangeinput`)){
				const min = slider.min
				const max = slider.max
				const value = slider.value
				slider.style.background = `linear-gradient(to right, var(--fore-color-1) 0%, var(--fore-color-1) \${(value-min)/(max-min)*100}%, var(--back-color-1) \${(value-min)/(max-min)*100}%, var(--back-color-1) 100%)`
				slider.oninput = function() {
					this.style.background = `linear-gradient(to right, var(--fore-color-1) 0%, var(--fore-color-1) \${(this.value-this.min)/(this.max-this.min)*100}%, var(--back-color-1) \${(this.value-this.min)/(this.max-this.min)*100}%, var(--back-color-1) 100%)`
				};
			}
		");
	}
	public function RenderMain(){
		parent::RenderMain();
		if(isValid($this->BackgroundImage)) echo "<div class='background-screen'></div>";
	}
} ?>