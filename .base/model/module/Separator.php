<?php namespace MiMFa\Module;

use MiMFa\Library\Html;

/**
 * This module creates a visual separator element, optionally with a background image.
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules#Separator See the Documentation
 */
class Separator extends Module
{
    /**
     * @var string|null Path to the background image.
     */
    public $Image = null;

    /**
     * @var string Width of the separator.
     */
    public $Width = "100%";

    /**
     * @var string Height of the separator.
     */
    public $Height = "100px";

    /**
     * @var bool Whether to merge the top margin with the previous element.
     */
    public $MergeTop = false;

    /**
     * @var bool Whether to merge the bottom margin with the next element.
     */
    public $MergeBottom = false;

    /**
     * @var bool Whether to merge the right margin with the adjacent element.
     */
    public $MergeRight = false;

    /**
     * @var bool Whether to merge the left margin with the adjacent element.
     */
    public $MergeLeft = false;

    /**
     * Generates the CSS styles for the separator.
     * @return string The CSS style string.
     */
    public function GetStyle()
    {
        $backgroundImageStyle = "";
        if (isValid($this->Image)) {
            $backgroundImageStyle = "background-image: url('{$this->Image}');
                                     background-size: cover;
                                     background-position: center;
                                     background-repeat: no-repeat;";
        }

        $marginTopStyle = $this->MergeTop ? "margin-top: calc({$this->Height}/-2);" : "";
        $marginBottomStyle = $this->MergeBottom ? "margin-bottom: calc({$this->Height}/-2);" : "";
        $marginRightStyle = $this->MergeRight ? "margin-right: calc({$this->Width}/-2);" : "";
        $marginLeftStyle = $this->MergeLeft ? "margin-left: calc({$this->Width}/-2);" : "";


        return Html::Style("
            .{$this->Name} {
                {$backgroundImageStyle}
                min-height: {$this->Height};
                min-width: {$this->Width};
                {$marginTopStyle}
                {$marginBottomStyle}
                {$marginRightStyle}
                {$marginLeftStyle}
                position: relative;
                display: grid;
                align-items: center;
                text-align: center;
            }
        ");
    }
}