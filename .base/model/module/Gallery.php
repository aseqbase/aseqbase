<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
MODULE("Collection");
/**
 * To show a gallery of images
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Gallery extends Collection{
	public $Capturable = true;
	public $Class = "container";
	/**
     * The default Content HTML
     * @var string|null
     */
	public $DefaultContent = null;
	/**
     * The default Path for more button reference
     * @var string|null
     */
	public $DefaultPath = null;
	/**
     * The size of Blur effect
     * @var string
     */
	public $BlurSize = "5px";
	/**
     * The label text of More button
     * @var string|null
     */
	public $MoreButtonLabel = "View";

	/**
     * The Width of thumbnail preshow
     * @var string
     */
	public $ThumbnailWidth = "100%";
	/**
     * The Height of thumbnail preshow
     * @var string
     */
	public $ThumbnailHeight = "fit-content";
	/**
     * The Minimum Width of thumbnail preshow
     * @var string
     */
	public $ThumbnailMinWidth = "100%";
	/**
     * The Minimum Height of thumbnail preshow
     * @var string
     */
	public $ThumbnailMinHeight = "11vmax";
    /**
     * The Maximum Width of thumbnail preshow
     * @var string
     */
	public $ThumbnailMaxWidth = "100%";
	/**
     * The Maximum Height of thumbnail preshow
     * @var string
     */
	public $ThumbnailMaxHeight = "50vh";

	public function GetStyle(){
		return HTML::Style("
			.{$this->Name} {
			}

			.{$this->Name}>*>.item {
				height: fit-content;
				background-Color: var(--BackColor-0);
				color: var(--ForeColor-0);
				margin: 3vmin 2vmin;
				padding: 0px;
				font-size:  var(--Size-0);
				box-shadow:  var(--Shadow-1);
				border-radius:  var(--Radius-1);
				border:  var(--Border-1) var(--ForeColor-2);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover{
				box-shadow:  var(--Shadow-2);
				border-radius:  var(--Radius-2);
				border:  var(--Border-1) var(--ForeColor-0);
				background-Color: var(--BackColor-1);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}

			/* Style the images inside the grid */
			.{$this->Name}>*>.item>.image {
				margin: 0px;
				opacity: 1;
				cursor: pointer;
				width: {$this->ThumbnailWidth};
				height: {$this->ThumbnailHeight};
				min-height: {$this->ThumbnailMinHeight};
				min-width: {$this->ThumbnailMinWidth};
				max-height: {$this->ThumbnailMaxHeight};
				max-width: {$this->ThumbnailMaxWidth};
				overflow: hidden;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover>.image{
				background-Color: var(--BackColor-0);
				opacity: 0.6;
				".(\MiMFa\Library\Style::UniversalProperty("filter","blur(".$this->BlurSize.")"))."
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item>.description{
				background-Color: var(--BackColor-0);
				padding: 0px 1vmax;
				position: relative;
				bottom: 0px;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover>.description{
				background-Color: var(--BackColor-0);
				padding: 3vmin 1vmax;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item>.description>h4{
				margin: 0px;
				font-size: var(--Size-0);
				line-height: normal;
				text-align: unset;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover>.description>h4{
				font-size: var(--Size-1);
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item>.description>*:not(h4){
				display: none;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
			.{$this->Name}>*>.item:hover>.description>*:not(h4){
				display: block;
				".(\MiMFa\Library\Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1)))."
			}
		");
	}

	public function Get(){
		return join(PHP_EOL, iterator_to_array((function(){
            MODULE("Image");
            $img = new Image();
            $img->Class = "image";
            yield $img->GetStyle();

            MODULE("ImageModal");
            $viewer = new ImageModal();
            $viewer->Name = $this->Name."_".$viewer->Name;

            $i = 0;
            foreach($this->Items as $item) {
                if($i % $this->MaximumColumns === 0)  yield "<div class='row'>";
                $p_image = getValid($item,'Image', $this->DefaultImage);
                $p_name = getValid($item,'Name')??getValid($item,'Title', $this->DefaultTitle);
                $p_title = getValid($item,'Title', $p_name);
                $p_description = getValid($item,'Description', $this->DefaultDescription);
                $p_content = getValid($item,'Content',$this->DefaultContent)??$p_description;
                $p_download = getValid($item,'Download');
                $p_link = getValid($item,'Link',$this->DefaultLink)??(isEmpty($this->MoreButtonLabel)?null:$p_download??$p_image);
                $p_path = getValid($item,'Path', $this->DefaultPath)??$p_link;
                $p_buttons = getValid($item,'ButtonsContent', $this->DefaultButtons);
                $img->Source = $p_image;
                $clickact = $viewer->ShowScript("`$p_title`","$(`.".$this->Name.">*>.item-$i>.description>:last-child`).html()","`".($p_link??$p_path??$p_image)."`","`$p_buttons`", "`".getFullUrl($p_download??$p_path??$p_link??$p_image)."`");
                $img->Attributes = ["onclick"=>$clickact];
			yield HTML::Division(
				$img->ReCapture().
				HTML::Division(
					HTML::SubHeading(__($p_name,true,false)).
					HTML::Paragraph(__($p_description,true,false)),
				["class"=>"description"]).
				(isValid($p_path)? HTML::Button($this->MoreButtonLabel, $clickact, ["class"=>"btn-outline btn-block"]):null).
				HTML::Division(__($p_content??$p_description,true,false),["class"=>"hide"]),
				["class"=>"item item-$i col-md"], $this->AllowAnimation? " data-aos='zoom-up' data-aos-offset='-500'":null);
                if(++$i % $this->MaximumColumns === 0) yield "</div>";
            }
            if($i % $this->MaximumColumns !== 0)  yield "</div>";

           yield $viewer->Capture();
        })()));
	}
}
?>