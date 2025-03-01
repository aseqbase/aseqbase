<?php
namespace MiMFa\Module;
module("Collection");
use \MiMFa\Library\Html;
use \MiMFa\Library\Convert;
/**
 * To show a gallery of images
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Buttons extends Collection
{
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

	public function GetStyle()
	{
		return Html::Style("
			.$this->Name {
			}

			.$this->Name>*>.item {
				height: fit-content;
				background-Color: var(--back-color-0);
				color: var(--fore-color-0);
				margin: 3vmin 2vmin;
				padding: 0px;
				font-size:  var(--size-0);
				box-shadow:  var(--shadow-1);
				border-radius:  var(--radius-1);
				border:  var(--border-1) var(--fore-color-2);
				" . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.$this->Name>*>.item:hover{
				box-shadow:  var(--shadow-2);
				border-radius:  var(--radius-2);
				border:  var(--border-1) var(--fore-color-0);
				background-Color: var(--back-color-1);
				" . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}

			/* Style the images inside the grid */
			.$this->Name>*>.item>.image {
				margin: 0px;
				opacity: 1; 
				cursor: pointer; 
				width: $this->ThumbnailWidth;
				height: $this->ThumbnailHeight;
				min-height: $this->ThumbnailMinHeight;
				min-width: $this->ThumbnailMinWidth;
				max-height: $this->ThumbnailMaxHeight;
				max-width: $this->ThumbnailMaxWidth;
				overflow: hidden;
				" . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.$this->Name>*>.item:hover>.image{
				background-Color: var(--back-color-0);
				opacity: 0.6;
				" . \MiMFa\Library\Style::UniversalProperty("filter", "blur({$this->BlurSize})") . "
				" . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.$this->Name>*>.item>.description{
				background-Color: var(--back-color-0);
				padding: 0px 1vmax;
				position: relative;
				bottom: 0px;
				" . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.$this->Name>*>.item:hover>.description{
				background-Color: var(--back-color-0);
				padding: 3vmin 1vmax;
				" . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.$this->Name>*>.item>.description>h4{
				margin: 0px;
				font-size: var(--size-0);
				line-height: normal;
				text-align: unset;
				" . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.$this->Name>*>.item:hover>.description>h4{
				font-size: var(--size-1);
				" . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.$this->Name>*>.item>.description>*:not(h4){
				display: none;
				" . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
			.$this->Name>*>.item:hover>.description>*:not(h4){
				display: block;
				" . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . "
			}
		");
	}

	public function Get()
	{
		return Convert::ToString(function () {
			module("Image" );
			$img = new Image();
			$img->Class = "image";
			yield $img->GetStyle();

			module("ImageModal");
			$viewer = new ImageModal();
			$viewer->Name = $this->Name . "_" . $viewer->Name;

			$i = 0;
			//yield  "<div class='row'>";
			foreach ($this->Items as $item) {
				if ($i % $this->MaximumColumns === 0)
					yield "<div class='row'>";
				$p_image = findValid($item, 'Image' , $this->DefaultImage);
				$p_name = findBetween($item, 'Name', 'Title')?? $this->DefaultTitle;
				$p_title = findValid($item, 'Title' , $p_name);
				$p_description = findValid($item, 'Description' , $this->DefaultDescription);
				$p_content = findValid($item, 'Content' , $this->DefaultContent) ?? $p_description;
				$p_download = get($item, 'Download');
				$p_link = findValid($item, 'Link', $this->DefaultLink) ?? (isEmpty($this->MoreButtonLabel) ? null : $p_download ?? $p_image);
				$p_path = findValid($item, 'Path' , $this->DefaultPath) ?? $p_link;
				$p_buttons = findValid($item, 'ButtonsContent', $this->DefaultButtons);
				$img->Source = $p_image;
				$clickact = $viewer->ShowScript("`$p_title`", "$(`." . $this->Name . ">*>.item-$i>.description>:last-child`).html()", "`" . ($p_link ?? $p_path ?? $p_image) . "`", "`$p_buttons`", "`" . getFullUrl($p_download ?? $p_path ?? $p_link ?? $p_image) . "`");
				$img->Attributes = ["onclick" => $clickact];
				yield "<div class='item item-$i  col-md' " . ($this->Animation ? ("data-aos-delay='" . ($i % $this->MaximumColumns * \_::$Front->AnimationSpeed / 2) . "' data-aos='{$this->Animation}'") : null) . ">";
				yield $img->ToString();
				yield "<div class='description'>";
				yield Html::SubHeading(__($p_name, true, false));
				yield Html::Paragraph(__($p_description, true, false));
				if (isValid($p_path))
					yield Html::Button(__($this->MoreButtonLabel), $clickact, ["class"=> "btn btn-outline btn-block"]);
				yield Html::Division(__($p_content ?? $p_description, true, false), ["class"=> "hide"]);
				yield "</div>
			</div>";
				if (++$i % $this->MaximumColumns === 0)
					yield "</div>";
			}

			if ($i % $this->MaximumColumns !== 0)
				yield "</div>";

			yield $viewer->ToString();
		});
	}
}
?>