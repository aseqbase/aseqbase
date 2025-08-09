<?php
namespace MiMFa\Module;
use MiMFa\Library\Convert;
use MiMFa\Library\Html;
module("Collection");
/**
 * To show everythings as a slideshow
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Carousel extends Collection{
	public $Class = "carousel";

	public $IndicatorLabel = "";
	public $PreviousLabel = "<span class='carousel-control-prev-icon'></span>";
	public $NextLabel = "<span class='carousel-control-next-icon'></span>";

	public $Animation = "slide";
	public $CaptionBackColor = null;
	public $CaptionForeColor = null;

	public $ActiveItem = 0;

	public function __construct($items = null){
        parent::__construct();
		$this->Items = $items??$this->Items;
		$this["data-bs-ride"] = "carousel";
		$this->CaptionBackColor = $this->CaptionBackColor??"var(--back-color)";
		$this->CaptionForeColor = $this->CaptionForeColor??"var(--fore-color)";
    }

	public function GetStyle(){
		return Html::Style("
		.{$this->Name} .carousel-inner{
			height: 100%;
			padding: 0px;
		}
		.{$this->Name} .carousel-item.media{
			height: 100%;
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
			padding: 0px;
		}
		.{$this->Name} .carousel-item .carousel-caption{
			background-color: {$this->CaptionBackColor};
			color: {$this->CaptionForeColor};
			text-align: start;
			border-radius: var(--radius-1);
			padding: var(--size-1) var(--size-0);
			left: auto;
			right: auto;
			bottom: 5%;
			margin-inline-start: 2%;
			margin-inline-end: 2%;
		}
		.{$this->Name} .carousel-item .carousel-caption>*{
			margin: initial;
			text-align: start;
		}
		");
	}

	public function Get(){
		return join(PHP_EOL, iterator_to_array((function(){
			$indicators = [];
			$inners = [];
			$target = ".".$this->Name;
			$i = 0;
            foreach(Convert::ToItems($this->Items) as $item) {
				$active = $i==$this->ActiveItem;
				if(is_array($item) && (isValid($item, "Image" ) || isValid($item, "Image" )))
					$inners[] = Html::Media( Html::Division(
							Html::Heading(get($item, "Title" ), get($item, "Path" ),["class"=>"title"]).
							Html::Paragraph(getBetween($item, "Description", "Caption"),["class"=>"description"])
						,["class"=>"carousel-caption"]), get($item, "Image")
					,["data-bs-target"=>$target, "data-bs-slide-to"=>$i, "class"=>"carousel-item".($active?" active":"")]);
                else $inners[] = Html::Division($item, ["data-bs-target"=>$target, "data-bs-slide-to"=>$i, "class"=>"carousel-item".($active?" active":"")]);
                $indicators[] = Html::Element($this->IndicatorLabel, "button", ["data-bs-target"=>$target, "data-bs-slide-to"=>$i, "class"=>$active?"active":""]);
				$i++;
            }
            yield Html::Division($indicators, ["class"=>"carousel-indicators"]);
            yield Html::Division($inners, ["class"=>"carousel-inner"]);
            yield Html::Element($this->PreviousLabel,"button", ["data-bs-target"=>$target, "data-bs-slide"=>"prev", "class"=>"carousel-control-prev"]);
            yield Html::Element($this->NextLabel,"button", ["data-bs-target"=>$target, "data-bs-slide"=>"next", "class"=>"carousel-control-next"]);
        })()));
	}
}
?>