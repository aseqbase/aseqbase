<?php
namespace MiMFa\Module;

use MiMFa\Library\Convert;
use MiMFa\Library\Struct;
module("ContentCollection");
/**
 * To show data as posts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class CoverCollection extends ContentCollection
{
    public function GetItemInner($item)
    {
        $p_image = popValid($item, 'Image', $this->DefaultImage);
        $im = $this->DefaultImage;
        $this->DefaultImage = null;
        yield Struct::Cover(Convert::ToString($this->GetItemInner($item)), $p_image);
        $this->DefaultImage = $im;
    }
}