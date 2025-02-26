<?php

namespace MiMFa\Module;

use MiMFa\Library\HTML;

module("Separator"); // Assuming this function is defined elsewhere

class Part extends Module
{
    public $Image = null;
    public $TitleTag = "h2";

    public function GetStyle()
    {
        return Html::Style("
            .{$this->Name} {
                font-size: var(--size-1);
                padding: 3vmax; /* Simplified padding */
            }

            .{$this->Name} .description {
                font-size: var(--size-2);
            }

            .{$this->Name} .image {
                min-height: 20vh;
                background-size: contain;
                background-position: center;
                background-repeat: no-repeat;
                font-size: var(--size-1);
            }
        ");
    }

    public function Get()
    {
        $titleHtml = $this->GetTitle(); // Assuming GetTitle() returns HTML

        $rowContent = Html::Rack(function () {
            $descriptionHtml = $this->GetDescription(["class"=> "col-md description"]); // Assuming GetDescription() returns HTML

            $imageHtml = "";
            if (isValid($this->Image)) {
                $imageHtml = Html::Division("", ["class"=> "col-md-4 image", "style" => "background-image: url('{$this->Image}')"]);
            }

            return $descriptionHtml . $imageHtml;
        });

        $contentHtml = $this->GetContent(["class"=> "content"]); // Assuming GetContent() returns HTML

        return $titleHtml . $rowContent . $contentHtml;
    }
}