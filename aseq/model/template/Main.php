<?php namespace MiMFa\Template;
template("General");
class Main extends General{
	public bool $AllowHeader = true;
	public bool $AllowContent = true;
	public bool $AllowFooter = true;
	public bool $AllowTopMenu = true;
	public bool $AllowSideMenu = true;
	public bool $AllowBarMenu = true;
	
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
}