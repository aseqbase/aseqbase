<?php
namespace MiMFa\Template;
use MiMFa\Library\HTML;
class General extends Template{
	public $WindowTitle = null;
	public $WindowLogo = null;
	public $BackgroundImage = null;
	public $AllowHeader = true;
	public $AllowContent = true;
	public $AllowFooter = true;

	public function DrawInitial(){
		parent::DrawInitial();
		echo HTML::Style("
				body {
					font: var(--Size-1) var(--Font-0), var(--Font-1), var(--Font-2);
					overflow-x: hidden;
					min-height: 100vh;
					background-color: var(--BackColor-0);
					color: var(--ForeColor-0);
					".\MiMFa\Library\Style::UniversalProperty("font-smoothing","antialiased")."
				}

				::-webkit-scrollbar {
					background-color: transparent;
					width: 10px;
					height: 10px;
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				::-webkit-scrollbar:hover {
					background-color: var(--BackColor-1);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				::-webkit-scrollbar-track {
					background-color: transparent;
					box-shadow: var(--Shadow-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				::-webkit-scrollbar-track:hover {
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				::-webkit-scrollbar-thumb {
					//border: var(--Border-1) var(--BackColor-2);
					background-color: ".\_::$TEMPLATE->BackColor(2)."33;
					border-radius: 2px;
					box-shadow: var(--Shadow-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				::-webkit-scrollbar-thumb:hover {
					background-color: var(--BackColor-2);
					border-radius: 0px;
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				body>.background-screen{
					background-image: ".(isValid($this->BackgroundImage)? "url('".$this->BackgroundImage."')" : "var(--Url-Overlay-0)")."
				}

				:is(h1,h2,h3,h4,h5,h6) strong{
					font-weight: normal;
				}
				h1{
					font-size: var(--Size-5);
					text-align: center;
					text-transform: uppercase;
					margin-top: 2vmax;
					margin-bottom: var(--Size-4);
				}
				h2{
					font-size: var(--Size-4);
					text-align: center;
					text-transform: uppercase;
					margin-top: 2vmax;
				}
				h3{
					font-size: var(--Size-3);
					text-transform: uppercase;
					margin-top: 2vmax;
				}
				h4{
					font-size: var(--Size-2);
					margin-top: 2vmax;
				}
				h5{
					font-size: var(--Size-1);
					margin-top: 2vmax;
				}
				h6{
					font-size: var(--Size-1);
					display: inline-block;
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
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				quote:hover{
					background-color: #8882;
					font-weight: bold;
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
					border: var(--Border-1) transparent;
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				:is(.button, .icon, .btn):is(:hover, :focus){
					border-color: var(--ForeColor-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
                :is(a.btn, .btn), :is(a.btn, .btn):is(:visited, :active) {
					text-decoration: none;
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				.btn, .btn:is(:visited, :active){
					display: inline-grid;
					align-items: center;
					background-color: var(--BackColor-1);
					color: var(--ForeColor-1);
					border-color: var(--BackColor-1);
					font-size: var(--Size-1);
					border-radius: var(--Radius-1);
					padding: calc(var(--Size-0) / 3) var(--Size-1);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				a .icon{
					text-decoration: none;
					display: initial;
					padding: calc(var(--Size-0) / 2);
					border: var(--Border-1) transparent;
				}
                a .icon:hover {
                    background-color:var(--BackColor-5);
                	color:var(--ForeColor-5);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				.btn:hover{
                	text-decoration: none;
					background-color: var(--BackColor-2);
					color: var(--ForeColor-2);
					border-color: var(--ForeColor-2);
					border-radius: var(--Radius-0);
					box-shadow: var(--Shadow-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				.btn-block {
					width: 100%;
				}
				.btn-main, .btn-main:is(:visited, :active) {
					background-color: var(--BackColor-2);
					color: var(--ForeColor-2);
					border-color: var(--BackColor-2);
				}
				.btn-main:hover{
					background-color: var(--BackColor-4);
					color: var(--ForeColor-4);
					border-color: var(--BackColor-4);
					border-radius: var(--Radius-0);
					box-shadow: var(--Shadow-3);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				.btn-primary, .btn-primary:is(:visited, :active) {
					background-color: var(--BackColor-2);
					color: var(--ForeColor-2);
					border-color: var(--BackColor-2);
				}
				.btn-primary:hover{
					background-color: var(--BackColor-4);
					color: var(--ForeColor-4);
					border-color: var(--BackColor-4);
					border-radius: var(--Radius-0);
					box-shadow: var(--Shadow-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				.btn-secondary, .btn-secondary:is(:visited, :active) {
					background-color: var(--BackColor-4);
					color: var(--ForeColor-4);
					border-color: var(--BackColor-4);
				}

				.btn-outline, .btn-outline:is(:visited, :active){
					background-color:  var(--BackColor-1);
					color: var(--ForeColor-1);
					border-color: var(--ForeColor-1);
					font-size: var(--Size-1);
					border-radius: var(--Radius-1);
					padding: calc(var(--Size-0) / 3) var(--Size-1);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				.btn-outline:hover{
					background-color:  var(--BackColor-2);
					color: var(--ForeColor-2);
					border-color: var(--ForeColor-2);
					border-radius: var(--Radius-0);
					box-shadow: var(--Shadow-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				.btn-outline-primary, .btn-outline-primary:is(:visited, :active) {
					background-color: var(--BackColor-2);
					color: var(--ForeColor-2);
					border-color: var(--ForeColor-2);
				}
				.btn-outline-primary:hover{
					background-color: var(--BackColor-4);
					color: var(--ForeColor-4);
					border-color: var(--ForeColor-4);
					border-radius: var(--Radius-0);
					box-shadow: var(--Shadow-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				.btn-outline-secondary, .btn-outline-secondary:is(:visited, :active) {
					background-color: var(--BackColor-4);
					color: var(--ForeColor-4);
					border-color: var(--ForeColor-4);
				}
				:is(a, .button, .icon, .btn):deactive {
					".\MiMFa\Library\Style::UniversalProperty("filter","grayscale(100)")."
				}

				input[type='range'].rangeinput {
					height: calc(var(--Size-0) / 2);
					outline: none;
					-webkit-appearance: none;
					border: var(--Border-1) var(--ForeColor-2);
					border-radius: var(--Radius-1);
				}
				input[type='range'].rangeinput::-webkit-slider-thumb {
					width: var(--Size-1);
					height: var(--Size-1);
					cursor: ew-resize;
					-webkit-appearance: none;
					background-color: var(--BackColor-2);
					border-radius: var(--Radius-5);
				}

				.side-image{
					background-position: center;
					background-repeat: no-repeat;
					background-size: 70% auto;
				}

				.page{
					line-height: 1.5;
					padding: var(--Size-3);
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
					margin: var(--Size-1) 0;
				}
				content>.page :is(ul, ol) {
					margin: var(--Size-2) 0;
					margin-inline-start: var(--Size-5);
				}
				content>.page li {
					margin: var(--Size-0) 0;
				}
				content>.page>.frame>.page{
					color: var(--ForeColor-1);
					background-color: ".\_::$TEMPLATE->BackColor(1)."88;
					border: var(--Border-1) var(--BackColor-1);
					box-shadow: var(--Shadow-2);
					border-radius: var(--Radius-1);
					padding: var(--Size-0) var(--Size-3) var(--Size-4) var(--Size-3);
					margin: var(--Size-2) var(--Size-3) var(--Size-1) var(--Size-3);
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
					color: var(--ForeColor-2);
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
					border: var(--Border-1) transparent;
					border-radius: var(--Radius-1);
					cursor: pointer;
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
				.interactive-table tr:hover{
					border-color: var(--BackColor-4);
					background-color: var(--ForeColor-4);
					color: var(--BackColor-4);
					box-shadow: var(--Shadow-2);
					".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
				}
		");
		echo HTML::Script("
			for(const slider of document.querySelectorAll(`input[type='range'].rangeinput`)){
				const min = slider.min
				const max = slider.max
				const value = slider.value
				slider.style.background = `linear-gradient(to right, var(--ForeColor-1) 0%, var(--ForeColor-1) \${(value-min)/(max-min)*100}%, var(--BackColor-1) \${(value-min)/(max-min)*100}%, var(--BackColor-1) 100%)`
				slider.oninput = function() {
					this.style.background = `linear-gradient(to right, var(--ForeColor-1) 0%, var(--ForeColor-1) \${(this.value-this.min)/(this.max-this.min)*100}%, var(--BackColor-1) \${(this.value-this.min)/(this.max-this.min)*100}%, var(--BackColor-1) 100%)`
				};
			}
		");
	}
	public function DrawMain(){
		parent::DrawMain();
		if(isValid($this->BackgroundImage)) echo "<div class='background-screen'></div>";
	}
	public function DrawHeader(){
		parent::DrawHeader();
		if($this->AllowHeader) PART("header");
	}
	public function DrawContent(){
		parent::DrawContent();
		if($this->AllowContent) PART("content");
	}
	public function DrawFooter(){
		parent::DrawFooter();
		if($this->AllowFooter) PART("footer");
    }
} ?>