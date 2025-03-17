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
	public $Capturable = true;
	public $Class = "carousel";

	public $IndicatorLabel = "";
	public $PreviousLabel = "<span class='carousel-control-prev-icon'></span>";
	public $NextLabel = "<span class='carousel-control-next-icon'></span>";

	public $Animation = "slide";

	public $ActiveItem = 0;

	public function __construct(){
        parent::__construct();
		$this["data-bs-ride"] = "carousel";
    }

	public function Get(){
		return join(PHP_EOL, iterator_to_array((function(){
			$indicators = [];
			$inners = [];
			$target = ".".$this->Name;
			$i = 0;
            foreach(Convert::ToItems($this->Items) as $item) {
				if(is_array($item) && (isValid($item, "Image" ) || isValid($item, "Image" )))
                    $item = Html::Image(null, get($item, "Image"))."
						<div class='carousel-caption'>
							<h3>".get($item, "Title" )."</h3>
							<p>".getBetween($item, "Description", "Caption")."</p>
						</div>";
				$active = $i==$this->ActiveItem;
                $indicators[] = Html::Element($this->IndicatorLabel, "button", ["data-bs-target"=>$target, "data-bs-slide-to"=>$i, "class"=>$active?"active":""]);
                $inners[] = Html::Division($item, ["data-bs-target"=>$target, "data-bs-slide-to"=>$i, "class"=>"carousel-item".($active?" active":"")]);
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