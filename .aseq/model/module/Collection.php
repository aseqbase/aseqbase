<?php
namespace MiMFa\Module;
use MiMFa\Library\Convert;
use MiMFa\Library\Struct;
/**
 * The main and basic module to implement any other collection module
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Collection extends Module
{
	public $TitleTag = "h2";
	public $Class = "container";
	/**.
	 * An array of items, which contains a Key-Value based array of features
	 * @var null|array<array<enum-string,mixed>>
	 */
	public $Items = null;
	public $Root = null;
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
	public $DefaultPath = null;
	/**
	 * Other default buttons
	 * @var string|null
	 */
	public $DefaultButtons = null;
	/**
	 * Maximum shown Columns of items in each row (a positive integer between 1-12)
	 * @var int
	 */
	public $MaximumColumns = 4;
	public $ExcerptLength = 150;
	/**
	 * The label text of More button
	 * @var string|null
	 */
	public $MoreButtonLabel = "More...";
	/**
	 * Show items with an animation effect
	 * @var string|null
	 */
	public $Animation = "fade-in";

	public function __construct($items = null)
	{
		parent::__construct();
		$this->Items = $items ?? $this->Items;
	}

	public function GetStyle()
	{
		return parent::GetStyle() . Struct::Style("
			.{$this->Name}{
				display: grid;
				gap: 3vmax;
			}
			.{$this->Name}>.row{
				gap: 3vmax;
			}
			.{$this->Name} .item{
				background-color: var(--back-color);
				color: var(--fore-color);
				font-size: var(--size-1);
				text-align: center;
    			padding: 0px;
				border: var(--border-1) var(--fore-color-special-input);
				border-radius: var(--radius-2);
				box-shadow: var(--shadow-1);
				" . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			.{$this->Name} .item:hover{
				background-color: var(--back-color-input);
				color: var(--fore-color-input);
				border-radius: var(--radius-1);
				box-shadow: var(--shadow-2);
				" . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
			}
			.{$this->Name} .item .image{
				margin: 2vmax;
				overflow: hidden;
				height: 5vmax;
				width: auto !important;
				width: 100%;
				max-width: 100%;
			}
			.{$this->Name} .item .image img{
				width: auto !important;
				width:  100%;
				max-width: 100%;
			}
			.{$this->Name} .item .description{
				background-color: var(--back-color);
				color: var(--fore-color);
				text-align: start;
				padding: 2vmin 2vmax;
				margin-bottom: 0px;
			}
			.{$this->Name} .item .icon{
				padding: 20px;
				margin-bottom: 3vh;
				border: var(--border-0) var(--fore-color);
				border-radius: 50%;
			}
			.{$this->Name} .item .btn{
				margin: 2vmax 5px;
			}
		");
	}

	public function Get()
	{
		return parent::Get() . join(PHP_EOL, iterator_to_array((function () {
			module("Image");
			$img = new Image();
			$img->AllowOrigin = false;
			$img->Class = "image";
			yield $img->GetStyle();
			$i = 0;
			foreach (Convert::ToItems($this->Items) as $item) {
				if ($i % $this->MaximumColumns === 0)
					yield "<div class='row'>";
				if (is_string($item))
					yield $item;
				else if (\_::$User->HasAccess(getValid($item, 'Access', \_::$User->VisitAccess))) {
					$p_meta = getValid($item, 'MetaData', null);
					if ($p_meta !== null) {
						$p_meta = Convert::FromJson($p_meta);
						pod($this, $p_meta);
					}
					$p_meta = null;
					$p_image = getValid($item, 'Image', $this->DefaultImage);
					$p_name = __(getBetween($item, 'Title', 'Name') ?? $this->DefaultTitle, true, false);
					$p_description = getValid($item, 'Description', $this->DefaultDescription);
					$p_description = is_null($p_description) ? null : __($p_description, styling:true, referring:true);
					$p_link = ($l = get($item, 'Path')) ? $l : ($this->Root ? $this->Root . getBetween($item, 'Id', 'Name') : $this->DefaultPath);
					$p_buttons = getValid($item, 'Buttons', $this->DefaultButtons);
					$img->Source = $p_image;
					if (is_null($p_description))
						if (is_null($p_buttons))
							yield Struct::Button(
								(isEmpty($img->Source) ? "" : $img->ToString()) .
								Struct::Heading4($p_name),
								$p_link,
								["class" => "item col-sm"],
								$this->Animation ? " data-aos-delay='" . ($i % $this->MaximumColumns * \_::$Front->AnimationSpeed) . "' data-aos='{$this->Animation}'" : null
							);
						else
							yield Struct::Division(
								Struct::Link(
									(isEmpty($img->Source) ? "" : $img->ToString()) .
									Struct::Heading4($p_name),
									$p_link
								) .
								Convert::ToString($p_buttons) .
								(isValid($p_link) ? Struct::Button($this->MoreButtonLabel, $p_link, ["target" => "blank"]) : ""),
								["class" => "item col-sm"],
								$this->Animation ? "data-aos='{$this->Animation}'" : null
							);
					else
						yield Struct::Division(
							Struct::Link(
								(isEmpty($img->Source) ? "" : $img->ToString()) .
								Struct::Heading4($p_name),
								$p_link
							) .
							Struct::Division(Convert::ToExcerpt($p_description, 0, $this->ExcerptLength), ["class" => "description"]) .
							Convert::ToString($p_buttons) .
							(isValid($p_link) ? Struct::Button($this->MoreButtonLabel, $p_link, ["target" => "blank"]) : ""),
							["class" => "item col-sm"],
							$this->Animation ? "data-aos='{$this->Animation}'" : null
						);
				}
				if (++$i % $this->MaximumColumns === 0)
					yield "</div>";
			}
			if ($i % $this->MaximumColumns !== 0)
				yield "</div>";
		})()));
	}
}
?>