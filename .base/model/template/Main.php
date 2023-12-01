<?php
namespace MiMFa\Template;
use MiMFa\Library\HTML;
TEMPLATE("General");
class Main extends General{
	public $AllowTopMenu = true;
	public $AllowSideMenu = true;
	public $AllowBarMenu = true;

	public function DrawInitial(){
		parent::DrawInitial();
        echo HTML::Style("
            table.dataTable tbody :is(td, tr) {
                text-align: -webkit-auto;
            }
            table.dataTable thead :is(th, tr) {
                text-align: center;
            }
            table.dataTable tbody tr.odd {
                background-color: #8881 !important;
            }
            table.dataTable tbody tr.even td:nth-child(odd) {
                background-color: #88888817 !important;
            }
            table.dataTable tbody tr.odd td:nth-child(odd) {
                background-color: #88888815 !important;
            }
            table.dataTable tbody tr:not(.odd, .even):hover {
                background-color: #8883;
            }
            table.dataTable tbody tr:is(.odd, .even):hover {
                background-color: #8882 !important;
				".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
            }
            table.dataTable tbody tr:is(.odd, .even) td:hover {
                background-color: transparent !important;
                outline: 1px solid var(--ForeColor-0);
                border-radius: var(--Radius-1);
				".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
            }
            table.dataTable tbody tr:is(.odd, .even) th:hover {
                background-color: transparent !important;
                outline: 1px solid var(--ForeColor-1);
                border-radius: var(--Radius-1);
				".\MiMFa\Library\Style::UniversalProperty("transition", "var(--Transition-1)")."
            }
        ");
    }
	public function DrawHeader(){
		parent::DrawHeader();
		if($this->AllowTopMenu) PART("main-menu");
		if($this->AllowSideMenu) PART("side-menu");
    }
	public function DrawFooter(){
			parent::DrawFooter();
			if($this->AllowBarMenu) PART("bar-menu");
    }
} ?>