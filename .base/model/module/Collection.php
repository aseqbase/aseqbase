<?php
namespace MiMFa\Module;
use MiMFa\Library\Convert;
use MiMFa\Library\Html;
/**
 * The main and basic module to implement any other collection module
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Collection extends Module{
	public $TitleTag = "h2";
	public $Class = "container";
	/**
     * An array of items, which contains a Key-Value based array of features
     * @var null|array<array<enum-string,mixed>>
     */
	public $Items = null;
	/**
     * The default Image Path
     * @var string|null
     */
	public $DefaultImage = null;
	/**
     * The default Name
     * @var string|null
     */
	public $DefaultTitle = null;
	/**
     * The default Description
     * @var string|null
     */
	public $DefaultDescription = null;
	/**
     * The default Content
     * @var string|null
     */
	public $DefaultContent = null;
	/**
     * The default Link for the source file
     * @var string|null
     */
	public $DefaultLink = null;
	/**
     * Other default buttons
     * @var string|null
     */
	public $DefaultButtons = null;
	/**
     * Maximum shown Columns of items in each row
     * @var int<1,12>
     */
	public $MaximumColumns = 4;
	/**
     * The label text of More button
     * @var string|null
     */
	public $MoreButtonLabel = "Read More...";
	/**
     * Show items with an animation effect
     * @var string|null
     */
	public $Animation = "fade-in";

	public function GetStyle(){
		return parent::GetStyle().Html::Style("
			.{$this->Name} .items{
				gap: 3vmax;
				margin-bottom: 3vmax;
			}
			.{$this->Name} .items .item{
				background-color: var(--back-color-0);
				color: var(--fore-color-0);
				font-size: var(--size-1);
				text-align: center;
    			padding: 0px;
				border: var(--border-1) var(--fore-color-4);
				border-radius: var(--radius-1);
				box-shadow: var(--shadow-1);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name} .items .item:hover{
				background-color: var(--back-color-1);
				color: var(--fore-color-1);
				border-radius: var(--radius-2);
				box-shadow: var(--shadow-2);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$Front->Transition(1)))."
			}
			.{$this->Name} .items .item .image{
				margin: 2vmax;
				overflow: hidden;
				height: 5vmax;
				width: auto !important;
				width: 100%;
				max-width: 100%;
			}
			.{$this->Name} .items .item .image img{
				width: auto !important;
				width:  100%;
				max-width: 100%;
			}
			.{$this->Name} .items .item .description{
				background-color: var(--back-color-0);
				color: var(--fore-color-0);
				text-align: start;
				padding: 2vmin 2vmax;
				margin-bottom: 0px;
			}
			.{$this->Name} .items .item .fa{
				padding: 20px;
				margin-bottom: 3vh;
				border: var(--border-0) var(--fore-color-0);
				border-radius: 50%;
			}
			.{$this->Name} .items .item .btn{
				margin: 2vmax 5px;
			}
		");
	}

	public function Get(){
		return parent::Get().join(PHP_EOL, iterator_to_array((function(){
            module("Image" );
            $img = new Image();
            $img->Class = "image";
            yield $img->GetStyle();

            $i = 0;
            foreach(Convert::ToItems($this->Items) as $item) {
                if($i % $this->MaximumColumns === 0)  yield "<div class='row items'>";
				if(is_string($item)) yield $item;
				else if(auth(getValid($item,'Access' , \_::$Config->VisitAccess))){
					$p_meta = getValid($item,'MetaData' ,null);
					if($p_meta !==null) {
						$p_meta = Convert::FromJson($p_meta);
						swap( $this, $p_meta);
					}
					$p_meta = null;
                    $p_image = getValid($item,'Image' , $this->DefaultImage);
                    $p_name = __(getBetween($item,'Title', 'Name')?? $this->DefaultTitle,true,false);
                    $p_content = getValid($item,'Content' , $this->DefaultContent);
                    $p_description = getValid($item,'Description' , $this->DefaultDescription);
                    $p_description = is_null($p_description)?null:__($p_description);
                    $p_link = getBetween($item,'Link','Path')?? $this->DefaultLink;
                    $p_buttons = getValid($item,'Buttons', $this->DefaultButtons);
                    $img->Source = $p_image;
					if(is_null($p_description))
                        yield Html::Button(
                            (isEmpty($img->Source)?"":$img->ToString()).
                            Html::SubHeading($p_name).
							Convert::ToString($p_content).
                            Convert::ToString($p_buttons),
                            $p_link,
                            ["class"=>"item col-sm"], $this->Animation? " data-aos-delay='".($i % $this->MaximumColumns*\_::$Front->AnimationSpeed)."' data-aos='{$this->Animation}'":null);
					else yield Html::Division(
                            (isEmpty($img->Source)?"":$img->ToString()).
                            Html::SubHeading($p_name).
                            Html::Division($p_description, ["class"=>"description" ]).
							Convert::ToString($p_content).
                            Convert::ToString($p_buttons).
                            (isValid($p_link)? Html::Button($this->MoreButtonLabel, $p_link, ["target"=>"blank"]):""),
                            ["class"=>"item col-sm"], $this->Animation? "data-aos='{$this->Animation}'":null);
                }
				if(++$i % $this->MaximumColumns === 0) yield "</div>";
            }
            if($i % $this->MaximumColumns !== 0) yield "</div>";
        })()));
	}
}
?>