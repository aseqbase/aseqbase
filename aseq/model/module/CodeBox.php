<?php
namespace MiMFa\Module;

use \MiMFa\Library\Struct;
use \MiMFa\Library\Convert;

/**
 * To show a simple box to show and edit codes
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules#CodeBox See the Documentation
 */
class CodeBox extends Module
{
    public string|null $ContentTagName =  "textarea";
    public $ContentId = null;
    public $CopyButtonLabel = "Copy";
    public $PasteButtonLabel = "Paste";
    public $Columns = 5;
    public $Rows = 5;
    public $ControlBox = null;

    public function GetStyle()
    {
        return Struct::Style("
            .{$this->MainClass} {$this->ContentTagName} {
                background-Color: var(--back-color-special-input);
                Color: var(--fore-color-special-input);
                border-radius: var(--radius-1);
                border: var(--border-1) var(--fore-color-output);
                min-width: 50%;
                resize: both;
                overflow: scroll;
            }
        ");
    }

    public function GetInner()
    {
        $id = $this->ContentId ?? ($this->MainClass . "_" . rand(1, 99999));
        yield $this->GetTitle();  // Handle and yield the title
        yield $this->GetDescription(); // Handle and yield the description
        yield $this->GetContent(["Id" => $id, "rows" => $this->Rows, "cols" => $this->Columns]);
        if (isValid($this->CopyButtonLabel) || isValid($this->PasteButtonLabel)) {
            module("Panel");
            $this->ControlBox = new Panel();
            $this->ControlBox->Content = function () use ($id) {
                if (isValid($this->CopyButtonLabel))
                    return Struct::Button($this->CopyButtonLabel, "copyFrom('$id');", ["class" => "btn"]);
                if (isValid($this->PasteButtonLabel))
                    return Struct::Button($this->PasteButtonLabel, "pasteInto('$id');", ["class" => "btn"]);
                return ""; // Return empty string if neither button is valid
            };
        }
        if (isValid($this->ControlBox))
            yield $this->ControlBox->ToString(); // Handle and yield the panel
    }
}