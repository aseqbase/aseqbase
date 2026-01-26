<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
module("Article");
/**
 * To show data as posts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Post extends Article
{
     public $Root = "/post/";

     public function GetTitle($attributes = null)
     {
          $p_image = getValid($this->Item, 'Image', $this->Image);
          if (!($this->AllowImage = $this->AllowImage && $p_image)) return parent::GetTitle($attributes);
          $p_id = get($this->Item, 'Id');
          $p_name = getValid($this->Item, 'Name') ?? $p_id ?? $this->Title;
          $nameOrId = $p_id ?? $p_name;
          if (!$this->CompressPath) {
               $catDir = \_::$Back->Query->GetContentCategoryRoute($this->Item);
               if (isValid($catDir))
                    $nameOrId = trim($catDir, "/\\") . "/" . ($p_name ?? $p_id);
          }

          return Struct::Cover(
               Struct::Division("",["style"=>"position:absolute;inset:0;background-color:#0008;"]).
               Struct::Rack(
                    Struct::MediumSlot(
                         ($this->AllowTitle ? Struct::Heading1(getValid($this->Item, 'Title', $this->Title), $this->LinkedTitle ? $this->Root . $nameOrId : null, ['class' => 'heading']) : "") .
                         $this->GetDetails($this->CollectionRoot . $nameOrId),
                         ["style"=>"z-index: 1;"]
                    ) .
                    $this->GetButtons(),
                    ["class" => "title"],
                    $attributes
               ) . Struct::Rack(
                         $this->AllowDescription = ($this->AllowDescription ? $this->GetExcerpt() : null),
                         ["class" => "description"],
                         $attributes
                    ),
               $p_image,[
                    "class"=>"qb-post-header"
               ]
          );
     }
     public function GetDescription($attributes = null)
     {
          $p_image = getValid($this->Item, 'Image', $this->Image);
          if (!($this->AllowImage = $this->AllowImage && $p_image)) return parent::GetDescription($attributes);
          return "";
     }
}