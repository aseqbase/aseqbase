<?php
namespace MiMFa\Module;

use MiMFa\Library\Convert;
use MiMFa\Library\Struct;
use MiMFa\Library\Style;
use Override;

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
    public string|null $CoverLtrShade = "linear-gradient(90deg, var(--back-color-special) 40%, transparent)";
    public string|null $HoverLtrShade = "linear-gradient(75deg, var(--back-color-special) 50%, transparent)";
    public string|null $CoverRtlShade = "linear-gradient(-90deg, var(--back-color-special) 40%, transparent)";
    public string|null $HoverRtlShade = "linear-gradient(-75deg, var(--back-color-special) 50%, transparent)";
    public string|null $CoverSize = "cover";
    public string|null $HoverSize = "cover";
    public string|null $CoverFilter = "blur(0px)";
    public string|null $HoverFilter = "blur(10px)";
    public string|null $CoverMask = "linear-gradient(to top, #0000, #000, #000)";
    public string|null $HoverMask = "none";

    #[Override]
    public function GetStyle()
    {
        return Struct::Style("
            .{$this->MainClass} {
                display: grid;
                gap: var(--size-max);
            }
            .{$this->MainClass} .row {
                gap: var(--size-max);
            }
            .{$this->MainClass} .heading {
                text-align: start;
                margin-top: 0px;
            }
            .{$this->MainClass} article.item{
                padding: 0px;
            }
            .{$this->MainClass} article.item{
                background-repeat: no-repeat;
                background-position: center;
                " . ($this->CoverSize ? "background-size: {$this->CoverSize};" : "") . "
                " . ($this->CoverFilter ? Style::UniversalProperty("backdrop-filter", $this->CoverFilter) : "") . "
                " . ($this->CoverMask ? Style::UniversalProperty("mask", $this->CoverMask) : "") . "
                padding: 0px;
                ".Style::UniversalProperty("transition", "var(--transition-1)")."
            }
            .{$this->MainClass} article.item:hover{
                " . ($this->HoverSize ? "background-size: {$this->HoverSize};" : "") . "
                " . ($this->HoverFilter ? Style::UniversalProperty("backdrop-filter", $this->HoverFilter) : "") . "
                " . ($this->HoverMask ? Style::UniversalProperty("mask", $this->HoverMask) : "") . "
                ".Style::UniversalProperty("transition", "var(--transition-1)")."
            }
            .{$this->MainClass} article.item > .inside{
                padding: var(--size-max) var(--size-4);
                width: stretch;
                height: stretch;
            }
            .{$this->MainClass} article.item > .inside:dir(ltr){
                ".($this->CoverLtrShade?"background-image: {$this->CoverLtrShade};":"")."
                ".Style::UniversalProperty("transition", "var(--transition-1)")."
            }
            .{$this->MainClass} article.item > .inside:dir(rtl){
                ".($this->CoverRtlShade?"background-image: {$this->CoverRtlShade};":"")."
                ".Style::UniversalProperty("transition", "var(--transition-1)")."
            }
            .{$this->MainClass} article.item:hover > .inside:dir(ltr){
                ".($this->HoverLtrShade?"background-image: {$this->HoverLtrShade};":"")."
                ".Style::UniversalProperty("transition", "var(--transition-1)")."
            }
            .{$this->MainClass} article.item:hover > .inside:dir(rtl){
                ".($this->HoverRtlShade?"background-image: {$this->HoverRtlShade};":"")."
                ".Style::UniversalProperty("transition", "var(--transition-1)")."
            }
            .{$this->MainClass} article.item > .inside .head {
                display: flex;
                gap: var(--size-0);
                flex-direction: row;
                justify-content: space-between;
                align-content: flex-start;
                align-items: flex-start;
            }
            .{$this->MainClass} article.item > .inside .more{
                opacity: 0;
                text-align: end;
                display: flex;
                gap: calc(var(--size-0) / 2);
                flex-direction: row-reverse;
                flex-wrap: wrap;
                ".Style::UniversalProperty("transition", "var(--transition-1)")."
            }
            .{$this->MainClass} article.item:hover > .inside .more{
                opacity: 0.9;
                ".Style::UniversalProperty("transition", "var(--transition-1)")."
            }
            .{$this->MainClass} article.item > .inside .more.md-show{
                width: 100%;
            }
        ");
    }
    #[Override]
    public function GetItemInner(array $item, int $index)
    {
        $rout = null;
        if ($this->AllowRoot) {
            module("Route");
            $rout = new \MiMFa\Module\Route();
            $rout->Class = "route";
            $rout->TagName = "span";
        }
        $p_meta = getValid($item, 'MetaData', null);
        if ($p_meta !== null) {
            $p_meta = Convert::FromJson($p_meta);
            pod($this, $p_meta);
        }
        $p_meta = null;
        $p_id = get($item, 'Id');
        $p_type = get($item, 'Type');
        $p_class = get($item, 'Class');
        $p_image = getValid($item, 'Image', $this->DefaultImage);
        $p_name = getBetween($item, 'Name', 'Title') ?? $this->DefaultTitle;
        $p_title = getValid($item, 'Title', $p_name);
        $p_description = getValid($item, 'Description', $this->DefaultDescription);
        $p_content = getValid($item, 'Content', $this->DefaultContent);

        $p_showexcerpt = $this->AllowExcerpt;
        $p_showcontent = $this->AllowContent;
        $p_showdescription = $this->AllowDescription;
        $p_showimage = $this->AllowImage;
        $p_showtitle = $this->AllowTitle;
        $p_showmeta = $this->AllowMetaData;
        $p_referring = $this->AutoReferring;
        $p_inselflink = (!$p_showcontent && (!$p_showexcerpt || !$p_showdescription)) ? (getBetween($item, "Reference") ?? $this->Root . getValid($item, 'Name', $p_id)) : null;
        if (!$this->CompressPath) {
            $catDir = \_::$Back->Query->GetContentCategoryRoute($item);
            if (isValid($catDir))
                $p_inselflink = $this->CollectionRoot . trim($catDir, "/\\") . "/" . ($p_name ?? $p_id);
        }
        $p_path = first(Convert::FromJson(getValid($item, 'Path', $this->DefaultPath)));
        if ($this->AllowRoot)
            $rout->Set($p_inselflink);
        $hasl = isValid($p_inselflink);
        $p_showmorebutton = $hasl && $this->AllowMoreButton;
        $p_morebuttontext = Convert::FromSwitch($this->MoreButtonLabel, $p_type);
        $p_showpathbutton = isValid($p_path) && $this->AllowPathButton;
        $p_pathbuttontext = Convert::FromSwitch($this->PathButtonLabel, $p_type);

        $p_excerpt = null;
        if ($this->AutoExcerpt) {
            $p_description = __(Convert::ToExcerpt(
                Convert::ToText($p_description),
                0,
                $this->ExcerptLength,
                $this->ExcerptSign
            ), styling: false, referring: $p_referring);
            if ($p_showexcerpt)
                $p_excerpt = __(Convert::ToExcerpt(
                    Convert::ToText($p_content),
                    0,
                    $this->ExcerptLength,
                    $this->ExcerptSign
                ), styling: false, referring: $p_referring);
        } else
            $p_description = __($p_description, styling: false, referring: $p_referring);

        if ($p_showmeta) {
            if ($this->AllowAuthor)
                doValid(
                    function ($val) use (&$p_meta) {
                        $authorName = table("User")->SelectRow("Signature , Name", "Id=:Id", [":Id" => $val]);
                        if (!isEmpty($authorName))
                            $p_meta .= " " . Struct::Link($authorName["Name"], \_::$Address->UserRootUrlPath . $authorName["Signature"], ["class" => "author"]);
                    },
                    $item,
                    'AuthorId'
                );
            if ($this->AllowCreateTime)
                doValid(
                    function ($val) use (&$p_meta) {
                        if (isValid($val))
                            $p_meta .= Struct::Span(Convert::ToShownDateTimeString($val), ["class" => 'createtime']);
                    },
                    $item,
                    'CreateTime'
                );
            if ($this->AllowUpdateTime)
                doValid(
                    function ($val) use (&$p_meta) {
                        if (isValid($val))
                            $p_meta .= Struct::Span(Convert::ToShownDateTimeString($val), ["class" => 'updatetime']);
                    },
                    $item,
                    'UpdateTime'
                );
            if ($this->AllowStatus)
                doValid(
                    function ($val) use (&$p_meta) {
                        if (isValid($val))
                            $p_meta .= " <span class='status'>$val</span>";
                    },
                    $item,
                    'Status'
                );
            if ($this->AllowButtons)
                doValid(
                    function ($val) use (&$p_meta) {
                        if (isValid($val))
                            $p_meta .= " " . $val;
                        else
                            $p_meta .= " " . $this->DefaultButtons;
                    },
                    $item,
                    'Buttons'
                );
        }
        yield Struct::OpenTag("article", [
            "class" => "item $p_type $p_class col-lg",
            ...($p_showimage ? ["style" => "background-image: url(\"$p_image\");"] : []),
            ...($this->Animation ? [
                "data-aos-delay" => ($index % $this->MaximumColumns * \_::$Front->AnimationSpeed),
                "data-aos" => $this->Animation
            ] : [])
        ]);
        yield Struct::OpenTag("div", ["class" => "inside"]);
        yield "<div class='head'>";
            yield "<div>";
            $lt = $this->LinkedTitle && $hasl;
            if ($p_showtitle)
                yield Struct::Heading($p_title, $lt ? $p_inselflink : null, ['class' => 'title']);
            if ($p_showmeta && isValid($p_meta)) {
                yield "<sub class='metadata'>";
                if ($this->AllowRoot)
                    yield $rout->ToString();
                yield $p_meta . "</sub>";
            }
            yield "</div>";
            if ($p_showmorebutton || $p_showpathbutton) {
                yield "<div class='more view md-hide'>";
                if ($p_showmorebutton)
                    yield Struct::Button($p_morebuttontext, $p_inselflink, ["class" => 'alt']);
                if ($p_showpathbutton)
                    yield Struct::Button($p_pathbuttontext, $p_path, ["class" => '']);
                yield "</div>";
            }
        yield "</div>";
        yield "<div class='description row'>";
        yield "<div class='excerpt col-md'>";
        if ($p_showdescription && !isEmpty($p_description))
            yield $p_description;
        if ($p_showexcerpt)
            yield $p_excerpt;
        if ($p_showcontent && isValid($p_content))
            yield "<div class='content'>" . __($p_content, styling: true, referring: $p_referring) . "</div>";
        if ($p_showmorebutton || $p_showpathbutton) {
            yield "<div class='more view md-show'>";
            if ($p_showmorebutton)
                yield Struct::Button($p_morebuttontext, $p_inselflink, ["class" => 'alt']);
            if ($p_showpathbutton)
                yield Struct::Button($p_pathbuttontext, $p_path, ["class" => '']);
            yield "</div>";
        }
        yield Struct::CloseTag("div");
        yield Struct::CloseTag("article");
    }
}