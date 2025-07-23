<?php
namespace MiMFa\Module;

use MiMFa\Library\Convert;
use MiMFa\Library\Html;
/**
 * To make a tab control
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Tabs extends Module
{
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
    public $TitlesClass = "";
    public $TitleClass = "";
    public $ContentsClass = "";
    public $ContentClass = "";
    public $AllowTooltip = false;

    /**
     * Create the module
     * @param array|string|null $source The module source
     */
    public function __construct($items = null)
    {
        parent::__construct();
        $this->Set($items);
    }
    public function Set($items = null)
    {
        $this->Items = $items;
    }

    public function GetStyle()
    {
        return parent::GetStyle() . Html::Style("
        .$this->Name>.tab-titles>.tab-title>:is(*,*:hover){border:none; outline:none;}
        .$this->Name>.tab-titles>.tab-title{display:inline-block; padding:calc(var(--size-1) / 5) calc(var(--size-1) / 2); border-bottom: var(--border-1) #8885;}
        .$this->Name>.tab-titles>.tab-title.active{border: var(--border-1) #8888; border-bottom: none;}
        ");
    }
    public function Get()
    {
        return Html::Division($this->GetTitle() . 
                    join("", loop(
                    $this->Items,
                    function ($v, $k, $i) {
                        $name = get($v, 'Name');
                        $image = getBetween($v, "Image", "Icon");
                        $desc = null;
                        if ($k === $i) {
                            $k = $name??get($v, "Title");
                            $desc = $this->AllowTooltip?getBetween($v, "Tooltip", 'Description'):null;
                        }
                        return Html::Button(
                            Html::Span($k) . ($image ? Html::Media("", $image) : ""),
                            "{$this->Name}_openTab(this, '$this->Name-tab-$i')",
                            $name?["name"=>$name]:[], 
                            ["class" => "tab-title $this->TitleClass" . ($k === $this->SelectedIndex || $i === $this->SelectedIndex ? " active" : "")]). ($desc?Html::Tooltip($desc):"");
                    }
                )),
                ["class" => "tab-titles $this->TitlesClass"]
            ) . $this->GetDescription() .
            Html::Division(
                join("", loop(
                    $this->Items,
                    function ($v, $k, $i) {
                        if (is_array($v)) {
                            $name = get($v, 'Name');
                            $content = get($v, 'Content');
                            $v = Html::ExternalHeading(get($v, 'Title')??$this->Title, getBetween($v, 'Path', 'Url', 'Link'), ["class" => "title"]) .
                                Html::Division(Convert::ToString($content) . getBetween($v, "Button", "More"), ["class" => "content"]);
                        }return Html::Element($v, "div", $name?["name"=>$name]:[], ["class" => "tab-content $this->ContentClass" . ($k === $this->SelectedIndex || $i === $this->SelectedIndex ? " show" : " hide"), "id" => "$this->Name-tab-$i"]);
                    }
                )). $this->GetContent(),
                ["class" => "tab-contents $this->ContentsClass"]
            );
    }
    public function GetScript()
    {
        return parent::GetScript() . Html::Script("function {$this->Name}_openTab(tab, tabId){
            let contents = document.querySelectorAll('.$this->Name>.tab-contents>.tab-content');
            contents.forEach(content => content.classList.remove('show') & content.classList.add('hide'));
            let titles = document.querySelectorAll('.$this->Name>.tab-titles>.tab-title');
            titles.forEach(title => title.classList.remove('active'));
            document.getElementById(tabId).classList.remove('hide');
            document.getElementById(tabId).classList.add('show');
            tab.classList.add('active');
        }");
    }
}
?>