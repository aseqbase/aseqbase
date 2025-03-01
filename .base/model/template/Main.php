<?php
namespace MiMFa\Template;
use MiMFa\Library\Html;
template("General");
class Main extends General{
	public $AllowHeader = true;
	public $AllowContent = true;
	public $AllowFooter = true;
	public $AllowTopMenu = true;
	public $AllowSideMenu = true;
	public $AllowBarMenu = true;
	
	public function RenderHeader(){
		parent::RenderHeader();
		if($this->AllowHeader) part("header");
		if($this->AllowTopMenu) part("menu/main");
		if($this->AllowSideMenu) part("menu/side");
    }
	public function RenderContent(){
		parent::RenderContent();
		if($this->AllowContent) part("content" );
	}
	public function RenderFooter(){
		parent::RenderFooter();
		if($this->AllowFooter) part("footer");
		if($this->AllowBarMenu) part("menu/bar");
    }
} ?>