<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
/**
 * To make a tab control
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Tabs extends Module{
	/**
	 * The tabs array with the format [tab1Name=>tab1Content,tab2Name=>tab2Content]
	 * @var array
	 */
	public $Items = [];
	/**
	 * To set first active tab (default is 0)
	 * @var int|string|null
	 */
	public $SelectedIndex = 0;

	public function __construct() {
		$this->Id = "_".getId();
	}

	public function GetStyle(){
		return parent::GetStyle().Html::Style("
        #$this->Id>.tab-titles>.tab-title>:is(*,*:hover){border:none; outline:none;}
        #$this->Id>.tab-titles>.tab-title{display:inline-block; padding:calc(var(--size-1) / 5) calc(var(--size-1) / 2); border-bottom: var(--border-1) #8885;}
        #$this->Id>.tab-titles>.tab-title.active{border: var(--border-1) #8888; border-bottom: none;}
        ");
	}
	public function Get(){
		return $this->GetTitle().$this->GetDescription().$this->GetContent().
            Html::Division(
                join("", loop(
                    $this->Items,
                    function ($v, $k, $i) {
                        return Html::Division($k, ["class" => "tab-title" . ($k === $this->SelectedIndex || $i === $this->SelectedIndex ? " active" : ""), "onclick" => "{$this->Id}_openTab(this, '$this->Id-tab-$i')"]);
                    }
                )),
                ["class" => "tab-titles"]
            ) .
            Html::Division(
                join("", loop(
                    $this->Items,
                    function ($v, $k, $i) {
                        return Html::Element($v, "div", ["class" => "tab-content" . ($k === $this->SelectedIndex || $i === $this->SelectedIndex ? " show" : " hide"), "id" => "$this->Id-tab-$i"]);
                    }
                )),
                ["class" => "tab-contents"]
            );
    }
	public function GetScript(){
		return parent::GetScript().Html::Script("function {$this->Id}_openTab(tab, tabId){
            let contents = document.querySelectorAll('#$this->Id>.tab-contents>.tab-content');
            contents.forEach(content => content.classList.remove('show') & content.classList.add('hide'));
            let titles = document.querySelectorAll('#$this->Id>.tab-titles>.tab-title');
            titles.forEach(title => title.classList.remove('active'));
            document.getElementById(tabId).classList.remove('hide');
            document.getElementById(tabId).classList.add('show');
            tab.classList.add('active');
        }");
	}
}
?>
