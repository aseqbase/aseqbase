<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Convert;
module("Tabs");
class SideTabs extends Tabs{
	public $Class = "row";
	public $TitlesClass = "col-md";
	public $TitleClass = "col col-md";
	public $ContentsClass = "col-md-8";


	public function GetStyle(){
		return parent::GetStyle().Html::Style("
			.{$this->Name} {
				min-height: 50vh;
				display: flex;
				align-content: center;
				justify-content: center;
				align-items: center;
				overflow: hidden;
			}

			.{$this->Name} .tab-contents{
				max-width: 100%;
			}
			.{$this->Name} .tab-content{
				text-align: center;
			}

			.{$this->Name} .tab-titles {
				display: flex;
				flex-direction: row;
				align-items: center;
				justify-content: center;
    			flex-wrap: wrap;
			}
			.{$this->Name} .tab-titles .tab-title{
				margin: calc(var(--size-0) / 2);
				padding: calc(var(--size-0) / 2) var(--size-0);
				width: calc(100% - var(--size-0));
				text-align: center;
				cursor: pointer;
				border: var(--border-1) var(--back-color-2);
				border-radius: var(--radius-1);
				box-shadow: var(--shadow-1);
				display: flex;
				flex-direction: row;
				align-items: center;
				justify-content: space-between;
				align-content: center;
				gap: var(--size-0);
				". \MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(2))."
			}
			.{$this->Name} .tab-titles .tab-title.active{
				background-color: inherit;
				color: inherit;
				box-shadow: none;
			}
				
			@media screen and (min-width:760px){
				.{$this->Name} .tab-titles {
					flex-direction: column;
					flex-wrap: wrap;
				}
			}
		");
	}
}
?>