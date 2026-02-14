<?php
namespace MiMFa\Module;

use MiMFa\Library\Convert;
use MiMFa\Library\Struct;
/**
 * To make a tab control
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Tabs extends Module
{
    public $Image = null;
    public $PrependTitles = null;
    public $AppendTitles = null;
    public $PrependContents = null;
    public $AppendContents = null;
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
    public $AllowTitlesLabel = true;
    public $AllowTitlesImage = true;
    public $AllowTitlesDescription = true;
    public $AllowTitle = false;
    public $AllowImage = false;

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
        yield parent::GetStyle();
        yield Struct::Style("
        .$this->MainClass>.tab-titles>.tab-title>:is(*,*:hover){border:none; outline:none;}
        .$this->MainClass>.tab-titles>.tab-title{display:inline-block; padding:calc(var(--size-1) / 5) calc(var(--size-1) / 2); border-bottom: var(--border-1) #8885;}
        .$this->MainClass>.tab-titles>.tab-title.active{border: var(--border-1) #8888; border-bottom: none;}
        ");
    }
    public function GetInner()
    {
        return Struct::Division(
                $this->PrependTitles .
                    join("", loop(
                    $this->Items,
                    function ($v, $k, $i) {
                        $name = get($v, 'Name');
                        $tooltip = $this->AllowTitlesDescription?(get($v, "Description")??$this->Description):null;
                        return Struct::Button(
                            ($this->AllowTitlesLabel?Struct::Span(get($v, 'Title')??$this->Title):null) . ($this->AllowTitlesImage ? Struct::Media("", getBetween($v, "Image", "Icon")??$this->Image) : ""),
                            getBetween($v, "Path", "Action")??"{$this->MainClass}_openTab(this, '$this->MainClass-tab-$i')",
                            $name?["name"=>$name]:[], 
                            get($v, "Attributes")??[],
                            ["class" => "tab-title $this->TitleClass" . ($k === $this->SelectedIndex || $i === $this->SelectedIndex ? " active" : "")]). 
                            ($tooltip?Struct::Tooltip($tooltip):"");
                    }
                ))
                . $this->AppendTitles,
                ["class" => "tab-titles $this->TitlesClass"]
            ) .
            Struct::Division(
                $this->PrependContents .
                join("", loop(
                    $this->Items,
                    function ($v, $k, $i) {
                        if (is_array($v)) {
                            $name = get($v, 'Name');
                            $content = get($v, 'Content')??$this->Content;
                            $v = 
                                ($this->AllowImage?Struct::Media(get($v, 'Title')??$this->Title, getBetween($v, "Image", "Icon")??$this->Image, ["class" => "image"]):"") .
                                ($this->AllowTitle?Struct::Heading1(get($v, 'Title')??$this->Title, ["class" => "title"]):"") .
                                Struct::Division(Convert::ToString($content), ["class" => "content"]);
                        }return Struct::Element($v, "div", $name?["name"=>$name]:[], ["class" => "tab-content $this->ContentClass" . ($k === $this->SelectedIndex || $i === $this->SelectedIndex ? " view show" : " view hide"), "id" => "$this->MainClass-tab-$i"]);
                    }
                )). $this->AppendContents,
                ["class" => "tab-contents $this->ContentsClass"]
            );
    }
    public function GetScript()
    {
		yield parent::GetScript();
		yield Struct::Script("function {$this->MainClass}_openTab({$this->MainClass}_tab, {$this->MainClass}_tabId){
            document.querySelectorAll('.$this->MainClass>.tab-contents>.tab-content').forEach(content => content.classList.remove('show') & content.classList.add('hide'));
            document.querySelectorAll('.$this->MainClass>.tab-titles>.tab-title').forEach(title => title.classList.remove('active'));
            document.getElementById({$this->MainClass}_tabId).classList.remove('hide');
            document.getElementById({$this->MainClass}_tabId).classList.add('show');
            {$this->MainClass}_tab.classList.add('active');
        }");
    }
}
?>