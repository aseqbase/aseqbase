<?php

namespace MiMFa\Module;

use MiMFa\Library\Struct;

module("Separator"); // Assuming this function is defined elsewhere

class Part extends Module
{
    public $Image = null;
    public string|null $TitleTagName = "h2";
	public string|null $TagName = "section";
	public $Class = "part";

    public function GetStyle()
    {
        return Struct::Style("
            .{$this->MainClass} {
                font-size: var(--size-1);
                padding: 3vmax; /* Simplified padding */
            }

            .{$this->MainClass} .description {
                font-size: var(--size-2);
            }

            .{$this->MainClass} .image {
                min-height: 20vh;
                background-size: contain;
                background-position: center;
                background-repeat: no-repeat;
                font-size: var(--size-1);
            }
        ");
    }

    public function GetInner()
    {
        $titleHtml = $this->GetTitle(); // Assuming getTitle() returns HTML

        $rowContent = Struct::Rack(function () {
            $descriptionHtml = $this->GetDescription(["class"=> "col-md description"]); // Assuming getDescription() returns HTML

            $imageHtml = "";
            if (isValid($this->Image)) {
                $imageHtml = Struct::Division("", ["class"=> "col-md-4 image", "style" => "background-image: url('{$this->Image}')"]);
            }

            return $descriptionHtml . $imageHtml;
        });

        $contentHtml = $this->GetContent(["class"=> "content"]); // Assuming getContent() returns HTML

        return $titleHtml . $rowContent . $contentHtml;
    }
}