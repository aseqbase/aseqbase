<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
module("Content");
/**
 * To show data as posts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Article extends Content
{
     public $Class = null;
     public $Root = "/post/";

     public function GetStyle()
     {
          return parent::GetStyle() . Struct::Style("
          .{$this->Name}{
               position: relative;
               inset: 0;
               display: contents;
               height: fit-content;
               width: 100%;
               width: -webkit-fill-available;
          }
          .{$this->Name} .heading{
               text-align: start;
          }
          .{$this->Name} .header.cover{
               position: relative;
               width: 100%;
          }
          .{$this->Name} .header.cover :is(.title, .description, .title a:not(.button), .description a:not(.button)) {
               color: var(--color-white);
          }
          .{$this->Name} .header.cover>.division{
               position: initial !important;
          }
          .{$this->Name} .header.cover>*>.container{
               padding: max(calc(4 * var(--size-max)), 150px) var(--size-3) var(--size-0);
          }
          .{$this->Name} .header.cover>*>.container *{
               text-align: start;
          }
          .{$this->Name} .header.cover .title{
               margin-bottom: 0px;
          }
          .{$this->Name} .header.cover .description{
               margin-top: var(--size-0);
               font-size: var(--size-1);
               font-weight: normal;
               max-width: 500px;
               text-align: start;
          }
          .{$this->Name} .header.cover .description *{
               margin: 0px;
               text-align: start;
          }
          .{$this->Name} .content{
               padding: var(--size-max) var(--size-3);
               background-color: var(--back-color-special);
               box-shadow: var(--shadow-2);
          }
          ");

     }
     public function GetTitle($attributes = null)
     {
          $p_image = getValid($this->Item, 'Image', $this->Image);
          if (!($this->AllowImage = $this->AllowImage && $p_image))
               return parent::GetTitle($attributes);
          $p_id = get($this->Item, 'Id');
          $p_name = getValid($this->Item, 'Name') ?? $p_id ?? $this->Title;
          $nameOrId = $p_id ?? $p_name;
          if (!$this->CompressPath) {
               $catDir = \_::$Back->Query->GetContentCategoryRoute($this->Item);
               if (isValid($catDir))
                    $nameOrId = trim($catDir, "/\\") . "/" . ($p_name ?? $p_id);
          }

          return Struct::Cover(
               Struct::Division("", ["style" => "position:absolute;inset:0;background-color:#0008;"]) .
               Struct::Container(Struct::Rack(
                    Struct::MediumSlot(
                         ($this->AllowTitle ? Struct::Heading1(getValid($this->Item, 'Title', $this->Title), $this->LinkedTitle ? $this->Root . $nameOrId : null, ['class' => 'heading']) : "") .
                         $this->GetDetails($this->CollectionRoot . $nameOrId),
                         ["style" => "z-index: 1;"]
                    ) .
                    $this->GetButtons(),
                    ["class" => "title"],
                    $attributes
               ) . Struct::Rack(
                              $this->AllowDescription = ($this->AllowDescription ? $this->GetExcerpt() : null),
                              ["class" => "description"],
                              $attributes
                         )),
               $p_image,
               [
                    "class" => "header"
               ]
          );
     }
     public function GetDescription($attributes = null)
     {
          $p_image = getValid($this->Item, 'Image', $this->Image);
          if (!($this->AllowImage = $this->AllowImage && $p_image))
               return parent::GetDescription($attributes);
          return "";
     }
     public function GetScript(){
          return parent::GetScript().Struct::Script("
               _('.{$this->Name}>:not(.header, .container-fluid)').addClass('container');
          ");
     }
}