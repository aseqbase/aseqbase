<?php
namespace MiMFa\Module;
use MiMFa\Library\Convert;
use MiMFa\Library\HTML;
/**
 * The main and basic module to implement any other collection module
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Collection extends Module{
	public $Capturable = true;
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
     * @var bool
     */
	public $AllowAnimation = true;

	public function GetStyle(){
		return parent::GetStyle().HTML::Style("
			.{$this->Name} .items{
				gap: 3vmax;
				margin-bottom: 3vmax;
			}
			.{$this->Name} .items .item{
				background-color: var(--BackColor-0);
				color: var(--ForeColor-0);
				font-size: var(--Size-1);
				text-align: center;
    			padding: 0px;
				border: var(--Border-1) var(--ForeColor-4);
				border-radius: var(--Radius-1);
				box-shadow: var(--Shadow-1);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name} .items .item:hover{
				background-color: var(--BackColor-1);
				color: var(--ForeColor-1);
				border-radius: var(--Radius-2);
				box-shadow: var(--Shadow-2);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
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
				background-color: var(--BackColor-0);
				color: var(--ForeColor-0);
				text-align: left;
				padding: 2vmin 2vmax;
				margin-bottom: 0px;
			}
			.{$this->Name} .items .item .fa{
				padding: 20px;
				margin-bottom: 3vh;
				border: var(--Border-0) var(--ForeColor-0);
				border-radius: 50%;
			}
			.{$this->Name} .items .item .btn{
				margin: 2vmax 5px;
			}
		");
	}

	public function Get(){
		return parent::Get().join(PHP_EOL, iterator_to_array((function(){
            MODULE("Image");
            $img = new Image();
            $img->Class = "image";
            yield $img->GetStyle();

            $i = 0;
            foreach(Convert::ToItems($this->Items) as $item) {
                if($i % $this->MaximumColumns === 0)  yield "<div class='row items'>";
				if(is_string($item)) yield $item;
				else if(getAccess(getValid($item,'Access', \_::$CONFIG->VisitAccess))){
                    $p_image = getValid($item,'Image', $this->DefaultImage);
                    $p_name = __(getValid($item,'Title')??getValid($item,'Name', $this->DefaultTitle),true,false);
                    $p_description = getValid($item,'Description', $this->DefaultDescription);
                    $p_description = is_null($p_description)?null:__($p_description);
                    $p_link = getValid($item,'Link')??getValid($item,'Path', $this->DefaultLink);
                    $p_buttons = getValid($item,'Buttons', $this->DefaultButtons);
                    $img->Source = $p_image;
					if(is_null($p_description))
                        yield HTML::Button(
                            $img->ReCapture().
                            HTML::SubHeading($p_name).
                            $p_buttons,
                            $p_link,
                            ["class"=>"item col-sm"], $this->AllowAnimation? "data-aos='fade-up'":null);
					else yield HTML::Division(
                            $img->ReCapture().
                            HTML::SubHeading($p_name).
                            HTML::Division($p_description, ["class"=>"description"]).
                            $p_buttons.
                            (isValid($p_link)? HTML::Button($this->MoreButtonLabel, $p_link, ["target"=>"blank"]):""),
                            ["class"=>"item col-sm"], $this->AllowAnimation? "data-aos='fade-up'":null);
                }
				if(++$i % $this->MaximumColumns === 0) yield "</div>";
            }
            if($i % $this->MaximumColumns !== 0) yield "</div>";
        })()));
	}
}
?>