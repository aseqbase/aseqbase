<?php

namespace MiMFa\Module;

use MiMFa\Library\HTML;

module("Collection");

/**
 * A module to show a collection of cards
 * @copyright All rights are reserved for MiMFa Development Group
 * @author Mohammad Fathi
 * @see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 * @link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Cards extends Collection
{
    public $Class = "container";
    /**
     * The default Content HTML
     * @var string|null
     */
    public $DefaultContent = null;
    /**
     * The label text of More button
     * @var string|null
     */
    public $MoreButtonLabel = "More...";

    public function GetStyle()
    {
        return Html::Style("
            .{$this->Name} .items .item {
                background-color: var(--back-color-0)99;
                color: var(--fore-color-0);
                font-size: var(--size-1);
                text-align: center;
                margin: 3vh;
                padding: 0px;
                border: var(--border-1) var(--fore-color-4);
                border-radius: var(--radius-1);
                box-shadow: var(--shadow-1);
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name} .items .item:hover {
                background-color: var(--back-color-1);
                color: var(--fore-color-1);
                border-radius: var(--radius-2);
                box-shadow: var(--shadow-2);
                " . \MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1)) . ";
            }

            .{$this->Name} .items .item .image {
                margin: 2vmax;
                overflow: hidden;
                height: 10%;
                width: auto !important;
                width: 100%;
                max-width: 100%;
            }

            .{$this->Name} .items .item .image img {
                width: auto !important;
                width: 100%;
                max-width: 100%;
            }

            .{$this->Name} .items .item .details {
                background-color: var(--back-color-0);
                color: var(--fore-color-0);
                text-align: left;
                padding: 2vmin 2vmax;
                margin-bottom: 0px;
            }

            .{$this->Name} .items .item .fa {
                padding: 20px;
                margin-bottom: 3vh;
                border: var(--border-0) var(--fore-color-0);
                border-radius: 50%;
            }

            .{$this->Name} .items .item .btn {
                margin: 2vmax 5px;
            }
        ");
    }

    public function Get()
    {
        module("Image" );
        $img = new \MiMFa\Module\Image();
        $img->Class = "image";
        $img->GetStyle(); // Output the image styles

        $i = 0;
        $itemsHtml = "";
        foreach ($this->Items as $item) {
            if ($i % $this->MaximumColumns === 0)
                $itemsHtml .= Html::OpenTag("div", ["class"=> "row items"]);

            $p_image = findValid($item, 'Image' , $this->DefaultImage);
            $p_name = __(findBetween($item, 'Title', 'Name')??$this->DefaultTitle, true, false);
            $p_description = __(findValid($item, 'Description' , $this->DefaultDescription));
            $p_content = __(findValid($item, 'Content' , $this->DefaultContent));
            $p_link = findBetween($item, 'Link', 'Path')?? $this->DefaultLink;
            $p_buttons = findValid($item, 'ButtonsContent', $this->DefaultButtons);
            $img->Source = $p_image;

            $itemsHtml .= Html::Division(function() use ($img, $p_name, $p_description, $p_content, $p_buttons, $p_link){
                $imageHtml = $img->ReRender();
                $titleHtml = Html::SubHeading( $p_name);
                $detailsHtml = Html::Paragraph($p_description .Html::$NewLine. $p_content, ["class"=> "details"]);
                $buttonsHtml = $p_buttons; // Assuming $p_buttons is already HTML
                $linkHtml = "";
                if (isValid($p_link)) {
                    $linkHtml = Html::Link(__($this->MoreButtonLabel), $p_link, ["class"=> "btn", "target" => "blank"]);
                }
                return $imageHtml . $titleHtml . $detailsHtml . $buttonsHtml . $linkHtml;
            }, ["class"=> "item col-sm", "data-aos" => "fade-up"]);

            if (++$i % $this->MaximumColumns === 0) {
                $itemsHtml .= Html::CloseTag(); // Close the row
            }
        }
        if ($i % $this->MaximumColumns !== 0) {
            $itemsHtml .= Html::CloseTag(); // Close the row if not fully divisible by MaximumColumns
        }

        return Html::Division($itemsHtml, ["class"=> $this->Class]); // Wrap everything in the container
    }
}