<?php
namespace MiMFa\Template;
TEMPLATE("General");
class Main extends General{
	public $AllowTopMenu = true;
	public $AllowSideMenu = true;
	public $AllowBarMenu = true;

	public function DrawMain(){
		parent::DrawMain();
		if($this->AllowTopMenu) PART("main-menu");
		if($this->AllowSideMenu) PART("side-menu");
    }
	public function DrawFooter(){
			parent::DrawFooter();
			if($this->AllowBarMenu) PART("bar-menu");
    }
} ?>