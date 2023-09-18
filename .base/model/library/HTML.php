<?php
namespace MiMFa\Library;
use MongoDB\BSON\Type;
/**
 * A simple library to create default and standard HTML tags
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#html See the Library Documentation
 */
class HTML
{
    //public static $Sources = [];
    public static $MaxFloatDecimals = 2;
    public static $MaxValueLength = 10;
    public static $NewLine = "<br/>";

    /**
     * Create standard html element
     * @param mixed $content The content of the Tag, send null to create single tag
     * @param string|null $tagName The HTML tag name
     * @param array $tagName Other custom attributes of the single Tag
     * @param array|string|null $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Element($content = null, array|string|null $tagName = null, ...$attributes) {
        $isSingle = !is_null($content) && is_string($content) && is_array($tagName);
        if ($isSingle) {
            $attributes = Convert::ToIteration($tagName, $attributes);
            $tagName = $content;
            $content = null;
        }
        elseif($content===null) return null;
        $tagName = trim(strtolower($tagName));

        $attrs = "";
        $attachments = "";
        if($attributes){
            if(is_countable($attributes) || is_iterable($attributes)){
                $attrdic = [];
                $scripts = [];
                $id = null;
                foreach($isSingle?$attributes:Convert::ToIteration($attributes) as $key=>$value)
                    if(isEmpty($key) || is_integer($key))
                        if(isEmpty($value)) continue;
                    else $attrdic[$value] = null;
                    else {
                        $key = trim(strtolower($key));
                        if($key == "id") $id = $value;
                        if(isset($attrdic[$key]))
                            switch ($key)
                            {
                                case "style":
                                case "class":
                                    $attrdic[$key] .= " $value";
                                    break;
                                case "onclick":
                                case "ondblclick":
                                case "onchange":
                                case "onload":
                                case "onmouseover":
                                case "onmouseout":
                                    $attrdic[$key] .= PHP_EOL.$value;
                                    break;
                                default:
                                    $attrdic[$key] = $value;
                                    break;
                            }
                        else $attrdic[$key] = $value;
                    }
                foreach($attrdic as $key=>$value)
                    switch ($key)
                    {
                        case "style":
                            if(!isValid($id)){
                                $id = "_".getId(true);
                                $attrs .= " id='$id'";
                            }
                            $attachments .= self::Style("#$id{{$value}}");
                            break;
                        case "onclick":
                        case "ondblclick":
                        case "onchange":
                        case "onload":
                        case "onmouseover":
                        case "onmouseout":
                            if(!isValid($id)){
                                $id = "_".getId(true);
                                $attrs .= " id='$id'";
                            }
                            $scripts[] = "document.getElementById('$id').$key = function(e){{$value}};";
                            break;
                        case "alt":
                        case "content":
                        case "text":
                            $attrs .= " ".self::Attribute($key, __($value, styling:false));
                            break;
                        case "href":
                        case "src":
                            $attrs .= " ".self::Attribute($key,  Local::GetUrl($value));
                            break;
                        default:
                            $attrs .= " ".self::Attribute($key, $value);
                            break;
                    }
                if(count($scripts) > 0) $attachments .= self::Script($scripts);
            }
            else $attrs = Convert::ToString($attributes);
        }

        if ($isSingle) return "<$tagName$attrs data-single/>
$attachments";
        else return join("",["<$tagName$attrs>", Convert::ToString($content), "</$tagName>
$attachments"]);
    }
    public static function Attribute($key, $value=null){
        if(is_null($value)){
            return $key;
        } else {
            if(str_contains($value,'"'))
                if(str_contains($value,"'")){
                    $value = str_replace("'","`",$value);
                    $sp ="'";
                }
            else $sp = "'";
            else $sp ='"';
            return "$key=$sp$value$sp";
        }
    }

    public static function Documents($title, $content = null, $description = null, $sources = [], ...$attributes){
        if ($content === null) {
            $content = $title;
            $title = null;
        }
        if ($content === null) return null;
        if (!is_array($content)) return self::Document($title, $content, $description, $attributes, $sources);
        $c = count($content);
        if ($c == 0) return self::Document($title, null, $description, $sources, $attributes);
        if ($c == 1) return self::Document($title, $content[0], $description, $sources, $attributes);
        return self::Document($title, self::Embeds($content), $description, $sources, $attributes);
    }
    public static function Document($title, $content = null, $description = null, $sources = [], ...$attributes){
        if ($content === null) {
            $content = $title;
            $title = null;
        }
        if ($content === null) return null;
        $head = join("\r\n",array_unique(/*self::$Sources,...$sources]*/$sources));
        //self::$Sources = [];

        return "<!DOCTYPE HTML>" +
            self::Element([
                    self::Element([
                        self::Title($title),
                        self::Element("meta", [ "charset"=> Translate::$Encoding ]),
                        self::Element("meta", [ "lang"=> Translate::$Language ]),
                        self::Description($description),
                        $head
                    ],
                    "head"
                    ),
                    self::Element($content??"", "body", ["class"=>"document"], $attributes)
                ],
                "html"
            );
    }

    /**
     * The <TITLE> HTML Tag
     * @param string|null $content The window title
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Title($content, ...$attributes){
        $srci = self::Element(__($content, styling:false), "title", $attributes);
        //array_push(self::$Sources, $srci);
        return $srci;
    }
    /**
     * The <LINK> icon HTML Tag
     * @param string|null $source The source path of window icon
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Logo($source, ...$attributes){
        $srci = self::Element("link",["rel"=>"icon", "href"=>$source], $attributes);
        //array_push(self::$Sources, $srci);
        return $srci;
    }
    public static function Description($content, ...$attributes){
        $srci = self::Meta("abstract",$content, $attributes);
        $srci .= self::Meta("description",$content, $attributes);
        $srci .= self::Meta("twitter:description",$content, $attributes);
        return $srci;
    }
    public static function Keywords($content, ...$attributes){
        return self::Meta("keywords", is_array($content)?join(", ", $content):$content, $attributes);
    }
    public static function Meta($name, $content, ...$attributes){
        $srci = self::Element("meta", ["name"=>$name, "content"=>$content], $attributes);
        //array_push(self::$Sources, $srci);
        return $srci;
    }
    public static function Script($content, $source = null, ...$attributes){
        $srci = self::Element($content, "script", is_null($source) ? ["type"=>"text/javascript"]:[ "src"=> $source ], $attributes);
        //array_push(self::$Sources, $srci);
        return $srci;
    }
    public static function Style($content, $source = null, ...$attributes){
        if (is_null($source)) $srci = self::Element($content??"", "style", $attributes);
        else $srci = self::Element(null,"link", [ "rel"=> "stylesheet", "href"=> $source ], $attributes);
        //array_push(self::$Sources, $srci);
        return $srci;
    }

    public static function Result($content, ...$attributes){
        return self::Element(__($content, styling:false).self::Tooltip("Double click to hide"), "div", ["class"=> "result", "ondblclick"=>"this.style.display = 'none'"], $attributes);
    }
    public static function Success($content, ...$attributes){
        return self::Result($content, ["class"=> "success"], $attributes);
    }
    public static function Warning($content, ...$attributes){
        return self::Result($content, ["class"=> "warning"], $attributes);
    }
    public static function Error($content, ...$attributes){
        if(is_a($content, "Exception") || is_subclass_of($content, "Exception"))
            return self::Result($content->getMessage(), ["class"=> "error"], $attributes);
        else return self::Result($content, ["class"=> "error"], $attributes);
    }

    /**
     * The <VIDEO> HTML Tag
     * @param mixed $content The alternative content of the Tag
     * @param mixed $source The source path to show
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Video($content, $source = null, ...$attributes){
        if(!isValid($source)) {
            $source = $content;
            $content = null;
        }
        if(!isValid($source)) return null;
        $srcs = [];
        if(is_array($source)) foreach ($source as $key=>$value)
                if(is_integer($key)) $srcs[] = self::Element("source", ["src"=> $value]);
            else $srcs[] = self::Element("source", ["src"=> $value,"type"=> $key ]);
        else $srcs[] = self::Element("source", ["src"=> $source ]);
        return self::Element(join(PHP_EOL, $srcs).__($content, styling:false), "video", ["class"=> "video"], $attributes);
    }
    /**
     * The <AUDIO> HTML Tag
     * @param mixed $content The alternative content of the Tag
     * @param array|string|null $source The source path to show
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Audio($content, $source = null, ...$attributes){
        if(!isValid($source)) {
            $source = $content;
            $content = null;
        }
        if(!isValid($source)) return null;
        $srcs = [];
        if(is_array($source)) foreach ($source as $key=>$value)
            if(is_integer($key)) $srcs[] = self::Element("source", ["src"=> $value ]);
            else $srcs[] = self::Element("source", ["src"=> $value,"type"=> $key ]);
        else $srcs[] = self::Element("source", ["src"=> $source ]);
        return self::Element(join(PHP_EOL, $srcs).__($content, styling:false), "audio", ["class"=> "audio"], $attributes);
    }
    /**
     * The <IMAGE> or <I> HTML Tag
     * @param mixed $content The alternative content of the Tag
     * @param string|null $source The source path to show
     * @param array $source Other custom attributes of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Image($content, $source = null, ...$attributes){
        if(!isValid($source)){
            $source = $content;
            $content = null;
        }
        elseif(is_array($source) && count($attributes) === 0){
            $attributes = Convert::ToIteration($source, $attributes);
            $source = $content;
            $content = null;
        }
        if(!isValid($source)) return null;
        if(isIdentifier($source))
            return self::Element("", "i", [ "class"=>"image fa fa-".strtolower($source)], $attributes);
        else return self::Element("img", [ "src"=> $source, "alt"=>$content, "class"=> "image" ], $attributes);
    }
    /**
     * The <IMAGE> or <I> HTML Tag
     * @param mixed $content The alternative content of the Tag
     * @param string|null $source The source path to show
     * @param array $source Other custom attributes of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Media($content, $source = null, ...$attributes){
        if(!isValid($source)) {
            $source = $content;
            $content = null;
        }
        elseif(is_array($source) && count($attributes) === 0){
            $attributes = Convert::ToIteration($source, $attributes);
            $source = $content;
            $content = null;
        }
        if(!isValid($source)) return null;
        if(isIdentifier($source))
            return self::Element("", "i", [ "class"=>"media fa fa-".strtolower($source)], $attributes);
        else return self::Element(__($content??"", styling:false),"div", [ "style"=> "background-image: url('$source'); background-position: center; background-repeat: no-repeat; background-size: cover;", "class"=> "media" ], $attributes);
    }
    /**
     * The <IFRAME> HTML Tag
     * @param mixed $content The default content of the Tag
     * @param string|null $source The source path or document to show
     * @param array $source Other custom attributes of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Embed($content, $source = null, ...$attributes){
        if(!isValid($source)) {
            $source = $content;
            $content = null;
        }
        elseif(is_array($source) && count($attributes) === 0){
            $attributes = Convert::ToIteration($source, $attributes);
            $source = $content;
            $content = null;
        }
        if(isUrl($source))
            return self::Element($content??"", "iframe", [ "src"=> $source, "class"=> "embed" ], $attributes);
        return self::Element($content??"", "iframe", [ "srcdoc"=>str_replace("\"", "&quot;", Convert::ToString($source)), "class"=> "embed" ], $attributes);
    }
    /**
     * The <IFRAME> HTML Tag devided in a page
     * @param array $sources The source pathes or documents to show
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Embeds(array $sources, ...$attributes){
        $c = count($sources);
        $p = Math::Slice($c, 100, 100, 20, 33);
        $ls = [];
        $atts = ["style"=> "width:{$p['X']}vw;height:{$p['Y']}vh;",
                "marginwidth"=>"0", "marginheight"=>"0", "frameborder"=>"0",
                "hspace"=>"0", "vspace"=>"0", "align"=>"top",
            ];
        foreach($sources as $item)
            $ls[] = self::Embed(null, $item, $atts, $attributes);
        return Convert::ToString($ls);
    }
    
    /**
     * The <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Page($content, ...$attributes){
        return self::Element(__($content),"div",["class"=> "page" ], $attributes);
    }
    /**
     * The <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Part($content, ...$attributes){
        return self::Element(__($content),"div",["class"=> "part" ], $attributes);
    }
    /**
     * The <HEADER> HTML Tag
     * @param mixed $content The content of the header Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Header($content, ...$attributes){
        return self::Element(__($content),"header",["class"=> "header" ], $attributes);
    }
    /**
     * The <CONTENT> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Content($content, ...$attributes){
        return self::Element(__($content),"div",["class"=> "content" ], $attributes);
    }
    /**
     * The <FOOTER> HTML Tag
     * @param mixed $content The content of the footer Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Footer($content, ...$attributes){
        return self::Element(__($content),"footer",["class"=> "footer" ], $attributes);
    }
    /**
     * The Container <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Container($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::Rack($item);
            $content = $res;
        }
        return self::Element(__($content),"div",["class"=> "container" ], $attributes);
    }
    /**
     * The Main Partitioner <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Frame($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::Rack($item);
            $content = $res;
        }
        return self::Element(__($content),"div",["class"=> "frame container-fluid" ], $attributes);
    }
    /**
     * The Row Partitioner <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Rack($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::Slot($item);
            $content = $res;
        }
        return self::Element(__($content),"div",["class"=> "rack row" ], $attributes);
    }
    /**
     * The Column Partitioner <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Slot($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::Slot($item);
            $content = $res;
        }
        return self::Element(__($content),"div",["class"=> "slot col" ], $attributes);
    }
    /**
     * The Column Partitioner <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function LargeSlot($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::Slot($item);
            $content = $res;
        }
        return self::Element(__($content),"div",["class"=> "slot col-lg" ], $attributes);
    }
    /**
     * The Column Partitioner <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function MediumSlot($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::Slot($item);
            $content = $res;
        }
        return self::Element(__($content),"div",["class"=> "slot col-md" ], $attributes);
    }
    /**
     * The Column Partitioner <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function SmallSlot($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::Slot($item);
            $content = $res;
        }
        return self::Element(__($content),"div",["class"=> "slot col-sm" ], $attributes);
    }
    /**
     * The <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Division($content, ...$attributes){
        return self::Element(__($content),"div",["class"=> "division" ], $attributes);
    }
    /**
     * The <CENTER> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Center($content, ...$attributes){
        return self::Element(__($content, styling:false),"center",["class"=> "center" ], $attributes);
    }
    
    /**
     * The Ordered List <OL> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function List($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::Item($item);
            $content = $res;
        }
        return self::Element(__($content),"ol",["class"=> "list" ], $attributes);
    }
    /**
     * The Unordered List <UL> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Items($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::Item($item);
            $content = $res;
        }
        return self::Element(__($content),"ul",["class"=> "items" ], $attributes);
    }
    /**
     * The List Item <LI> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Item($content, ...$attributes){
        return self::Element(__($content),"li",["class"=> "item" ], $attributes);
    }

    /**
     * The <H1> HTML Tag
     * @param mixed $content The heading text
     * @param string|null $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function ExternalHeading($content, $reference = null, ...$attributes){
        if(!is_null($reference))
            if(is_array($reference) && count($attributes) === 0){
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            }
            else return self::Link(
                self::Element(__($content),"h1",["class"=> "externalheading" ], $attributes)
                , $reference);
        return self::Element(__($content),"h1",["class"=> "externalheading" ], $attributes);
    }
    /**
     * The <H2> HTML Tag
     * @param mixed $content The heading text
     * @param string|null $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function SuperHeading($content, $reference = null, ...$attributes){
        if(!is_null($reference))
            if(is_array($reference) && count($attributes) === 0){
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            }
            else return self::Link(
                self::Element(__($content),"h2",["class"=> "superheading" ], $attributes)
                , $reference);
        return self::Element(__($content),"h2",["class"=> "superheading" ], $attributes);
    }
    /**
     * The <H3> HTML Tag
     * @param mixed $content The heading text
     * @param string|null $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Heading($content, $reference = null, ...$attributes){
        if(!is_null($reference))
            if(is_array($reference) && count($attributes) === 0){
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            }
            else return self::Link(
                self::Element(__($content),"h3",["class"=> "heading" ], $attributes)
                , $reference);
        return self::Element(__($content),"h3",["class"=> "heading" ], $attributes);
    }
    /**
     * The <H4> HTML Tag
     * @param mixed $content The heading text
     * @param string|null $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function SubHeading($content, $reference = null, ...$attributes){
        if(!is_null($reference))
            if(is_array($reference) && count($attributes) === 0){
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            }
            else return self::Link(
                self::Element(__($content),"h4",["class"=> "subheading" ], $attributes)
                , $reference);
        return self::Element(__($content),"h4",["class"=> "subheading" ], $attributes);
    }
    /**
     * The <H5> HTML Tag
     * @param mixed $content The heading text
     * @param string|null $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function InternalHeading($content, $reference = null, ...$attributes){
        if(!is_null($reference))
            if(is_array($reference) && count($attributes) === 0){
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            }
            else return self::Link(
                self::Element(__($content),"h5",["class"=> "internalheading" ], $attributes)
                , $reference);
        return self::Element(__($content),"h5",["class"=> "internalheading" ], $attributes);
    }

    /**
     * The <P> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Paragraph($content, $reference = null, ...$attributes){
        if(!is_null($reference))
            if(is_array($reference) && count($attributes) === 0){
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            }
            else return self::Link(
                self::Element(__($content),"p",["class"=> "paragraph" ], $attributes)
                , $reference);
            return self::Element(__($content),"p",["class"=> "paragraph" ], $attributes);
    }
    /**
     * The <SPAN> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Span($content, $reference = null, ...$attributes){
        if(!is_null($reference))
            if(is_array($reference) && count($attributes) === 0){
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            }
            else return self::Link(
                self::Element(__($content, styling:false),"span",["class"=> "span" ], $attributes)
                , $reference);
        return self::Element(__($content, styling:false),"span",["class"=> "span" ], $attributes);
    }

    /**
     * The <LABEL> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null $reference The hyper destination tag id
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Label($content, $reference = null, ...$attributes){
        if(!is_null($reference))
            if(is_array($reference) && count($attributes) === 0){
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            }
            else return self::Element(__($content, styling:false),"label",["class"=> "label", "for"=>$reference ], $attributes);
        return self::Element(__($content, styling:false),"label",["class"=> "label"], $attributes);
    }
    /**
     * The <SPAN> HTML Tag
     * @param mixed $content The in tag tooltip
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Tooltip($content, ...$attributes){
        return self::Element(__($content, styling:false),"div",["class"=> "tooltip" ], $attributes);
    }

    /**
     * The <A> HTML Tag
     * @param mixed $content The anchor text of the Tag
     * @param string|null $reference The hyper reference path
     * @param array $source Other custom attributes of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Link($content, $reference = null, ...$attributes){
        if(is_null($reference)) {
            $reference = $content;
            $content = null;
        }
        elseif(is_array($reference) && count($attributes) === 0){
            $attributes = Convert::ToIteration($reference);
            $reference = $content;
            $content = null;
        }
        if(!isValid($content)) $content = getDomain($reference);
        return self::Element(__($content, styling:false), "a", [ "href"=> $reference, "class"=> "link" ], $attributes);
    }
    /**
     * The <BUTTON> or <A> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null $reference The source path or onclick event script
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Button($content, $reference = null, ...$attributes){
        if(isUrl($reference))
            return self::Link(__($content), $reference,["class"=> "btn button"], $attributes);
        return self::Element(__($content, styling:false),"button",["class"=> "btn button", "type"=>"button", "onclick"=>$reference], $attributes);
    }
    /**
     * The <BUTTON> or <A> HTML Tag
     * @param mixed $content The source icon image or the regular name
     * @param string|null $reference The source path or onclick event script
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Icon($content, $reference = null, ...$attributes){
        if(!isValid($content)) return null;
        if(isUrl($reference))
            return self::Link(self::Media("", $content), $reference, ["class"=> "icon"], $attributes);
        return self::Media("", $content, ["class"=> "icon", "onclick"=>$reference], $attributes);

    }

    /**
     * The <FORM> HTML Tag
     * @param mixed $content The form fields
     * @param string|null $reference The action reference path
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function Form($content, $reference = null, ...$attributes){
        if(!isValid($content)) $content = self::SubmitButton();
        return self::Element(__($content, styling:false), "form",isValid($reference)?["action"=> $reference]:[], [ "enctype"=>"multipart/form-data", "method"=>"get", "class"=> "form" ], $attributes);
    }
    /**
     * The <LABEL> and any input HTML Tag
	 * @param object|string|null $type Can be a datatype or an input type
	 * @param string|null $title The label text of the field
     * @param mixed $value The default value of the field
     * @param mixed $description The more detaled text about the field
     * @param array|string|null $options The other options of the field
     * @param array|string|null $attributes Other important attributes of the field
     * @param string|null $key The default key and the name of the field
     * @return string
     */
    public static function Field($type = null, $title = null, $value = null, $description = null, $options = null, $attributes = [], $key = null){
        if(is_array($description) && count($attributes) === 0){
            $attributes = Convert::ToIteration($description);
            $description = null;
        }
        if(is_null($type)){
            if(isEmpty($value)) $type = "text";
            elseif(is_string($value)){
                if(isUrl($value)) {
                    if(isFile($value)) $type = "file";
                    else $type = "url";
                }
                elseif(strlen($value)>100 || count(explode("\r\n\t\f",$value))>1)
                    $type = "strings";
                else $type = "string";
            } else $type = strtolower(gettype($value));
        } elseif(is_object($type) || ($type instanceof \stdClass)){
            $type = getValid($type, "Type", "string");
            $key = getValid($type, "Key", $key);
            $value = getValid($type, "Value", $value);
            $title = getValid($type, "Title", $title);
            $description = getValid($type, "Description", $description);
            $options = getValid($type, "Options", $options);
            $attributes = getValid($type, "Attributes", $attributes);
        } elseif(is_countable($type)){
            if(is_null($options)) $options = $type;
            $type = "select";
        } else $type = strtolower($type);
        $titleTag = (is_null($title)?"":self::Label($title, $key, ["class"=> "title"]));
        $descriptionTag = (is_null($description)?"":self::Label($description, $key, ["class"=> "description"]));
        $key = $key??Convert::ToName($title);
        switch ($type)
        {
            case 'label':
            case 'key':
            case 'span':
                $content = self::Label($title, $value, $attributes);
                break;
            case 'object':
            case 'array':
            case 'lines':
            case 'texts':
            case 'strings':
            case 'multiline':
            case 'textarea':
                $content = self::TextInput($title, $value, $attributes);
                break;
            case 'line':
            case 'value':
            case 'string':
            case 'singleline':
        	case "text":
                $content = self::ValueInput($title, $value, $attributes);
                break;
            case 'enum':
            case 'dropdown':
            case 'combobox':
            case 'select':
                $content = self::SelectInput($title, $value, $options, $attributes);
                break;
            case 'radio':
            case 'radiobox':
            case 'radiobutton':
                $content = self::RadioInput($title, $value, $attributes);
                break;
            case 'bool':
            case 'boolean':
            case 'check':
            case 'checkbox':
            case 'checkbutton':
                $content = self::CheckInput($title, $value, $attributes);
                break;
            case 'int':
            case 'integer':
            case 'short':
            case 'long':
            case 'number':
                $min = is_array($options)?min($options):-999999999;
                $max = is_array($options)?max($options):999999999;
                $content = self::NumberInput($title, $value, ["min"=>$min, "max"=>$max], $attributes);
                break;
            case 'range':
                $min = is_array($options)?min($options):0;
                $max = is_array($options)?max($options):100;
                $content = self::RangeInput($title, $value, $min, $max, $attributes);
                break;
            case 'float':
            case 'double':
            case 'decimal':
                $min = is_array($options)?min($options):-999999999;
                $max = is_array($options)?max($options):999999999;
                $content = self::NumberInput($title, $value, ["min"=>$min, "max"=>$max], $attributes);
                break;
            case 'phone':
            case 'tel':
            case 'telephone':
                $content = self::TelInput($title, $value, $attributes);
                break;
            case 'url':
            case 'path':
                $content = self::UrlInput($title, $value, $attributes);
                break;
            case "datetime":
                $content = self::DateTimeInput($title, $value, $attributes);
                break;
            case "date":
                $content = self::DateInput($title, $value, $attributes);
                break;
            case "time":
                $content = self::TimeInput($title, $value, $attributes);
                break;
            case "week":
                $content = self::WeekInput($title, $value, $attributes);
                break;
            case "month":
                $content = self::MonthInput($title, $value, $attributes);
                break;
            case "hidden":
            case "hide":
                $titleTag = $descriptionTag = null;
                $content = self::HiddenInput($title, $value, $attributes);
                break;
            case "secret":
            case "pass":
            case "password":
                $content = self::SecretInput($title, $value, $attributes);
                break;
            case 'doc':
            case 'document':
            case 'image':
            case 'audio':
            case 'video':
            case 'file':
                $content = self::FileInput($title, $value, $attributes);
                break;
            case 'docs':
            case 'documents':
            case 'images':
            case 'audios':
            case 'videos':
            case 'files':
                $content = self::FileInput($title, $value, "multiple", $attributes);
                break;
            case "dir":
            case "directory":
            case "folder":
                $content = self::FileInput($title, $value, "webkitdirectory multiple", $attributes);
                break;
            case "submitbutton":
            case "submit":
                $content = self::SubmitButton($title, $value, $attributes);
                break;
            case "resetbutton":
            case "reset":
                $content = self::ResetButton($title, $value, $attributes);
                break;
            case 'imagesubmit':
            case 'imgsubmit':
                $content = self::Input($title, $title, "image", ["src"=>Convert::ToString($value)], $attributes);
                break;
            case 'json':
            case 'javascript':
            case 'js':
            case 'html':
            case 'css':
            case 'codes':
                $content = self::TextInput($title, $value, $attributes);
                break;
        	default:
                $content = self::Input($title, $value, $type, $attributes);
                break;
        }

        return self::Element(
            $titleTag.$content.$descriptionTag
            ,"div", ["class"=> "field"]);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function SubmitButton($key = "Submit", $value = null, ...$attributes){
        if(is_array($value) && count($attributes) === 0){
            $attributes = Convert::ToIteration($value);
            $value = null;
        }
        $Id = Convert::ToName($key);
        return self::Element(__($value??$key, styling:false), "button", [ "id"=>"$Id", "name"=> $Id, "class"=> "button submitbutton", "type"=>"submit"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ResetButton($key = "Reset", $value = null, ...$attributes){
        if(is_array($value) && count($attributes) === 0){
            $attributes = Convert::ToIteration($value);
            $value = null;
        }
        $Id = Convert::ToName($key);
        return self::Element(__($value??$key, styling:false), "button", [ "id"=>"$Id", "name"=> $Id, "class"=> "button resetbutton", "type"=>"reset"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function Input($key, $value = null, $type = null, ...$attributes){
        if(is_array($type) && count($attributes) === 0){
            $attributes = Convert::ToIteration($type);
            $type = "text";
        }
        $Id = Convert::ToName($key);
        return self::Element("input", [ "id"=>"$Id", "name"=> $Id, "placeholder"=> $key, "type"=> $type, "value"=> $value, "class"=>"input" ], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ValueInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "text", ["class"=>"valueinput"], $attributes);
    }
    /**
     * The <TEXTAREA> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function TextInput($key, $value = null, ...$attributes){
        $Id = Convert::ToName($key);
        return self::Element($value??"", "textarea", [  "id"=>"$Id", "name"=> $Id, "placeholder"=> $key, "class"=>"input textinput" ], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function CheckInput($key, $value = null, ...$attributes){
        if($value) return self::Input($key, $key, "radio", ["class"=>"radioinput", "checked"=>"checked"], $attributes);
        else return self::Input($key, $key, "radio", ["class"=>"radioinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function RadioInput($key, $value = null, ...$attributes){
        if($value) return self::Input($key, $key, "radio", ["class"=>"radioinput", "checked"=>"checked"], $attributes);
        else return self::Input($key, $key, "radio", ["class"=>"radioinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ColorInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "color", ["class"=>"colorinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function DateTimeInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "datetime-local", ["class"=>"datetimeinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function DateInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "date", ["class"=>"dateinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function TimeInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "time", ["class"=>"timeinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function MonthInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "month", ["class"=>"monthinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function WeekInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "week", ["class"=>"weekinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function UrlInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "url", ["class"=>"urlinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function EmailInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "email", ["class"=>"emailinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function FileInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "file", ["class"=>"fileinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function SearchInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "search", ["class"=>"searchinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function TelInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "tel", ["class"=>"telinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param int $min The minimum value
     * @param int $max The maximum value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function RangeInput($key, $value = null, $min=0, $max=100, ...$attributes){
        return self::Input($key, $value, "range", [ "min"=>$min, "max"=>$max, "class"=>"rangeinput", "oninput"=>"this.nextElementSibling.value = this.value"], $attributes).
            self::Element($value??"","output", ["class"=>"tooltip"]);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function NumberInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "number", ["class"=>"numberinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name or placeholder
     * @param mixed $reference The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function SecretInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "password", ["class"=>"secretinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $content The tag name
     * @param mixed $reference The tag value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function HiddenInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "hidden", ["class"=>"hiddeninput"], $attributes);
    }
    /**
     * The <SELECT> HTML Tag
     * @param mixed $content The tag name
     * @param mixed $reference The tag value
     * @param mixed $options The tag value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function SelectInput($key, $value = null, $options = [], ...$attributes){
        $Id = Convert::ToName($key);
        return self::Element(
            is_countable($options)?iterator_to_array((function()use($options, $value){
                foreach ($options as $k=>$v)
                    if($k == $value) yield self::Element($v??"","option",["value"=>$k, "selected"=>"true"]);
                    else yield self::Element($v??"","option",["value"=>$k]);
            })()):Convert::ToString($options)
            ,"select", [ "id"=>"$Id", "name"=> $Id, "placeholder"=> $key, "class"=>"input selectinput" ], $attributes);
    }


    /**
     * The <TABLE> HTML Tag
     * @param mixed $content The rows array or table content
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Table($content, $rowHeads = [0], $colHeads = [0], ...$attributes){
        return self::Element(
            is_countable($content)?join(PHP_EOL, iterator_to_array((function() use($content, $rowHeads, $colHeads){
                foreach ($content as $k=>$v) {
                    if(in_array($k, $rowHeads))
                        yield self::Column($v);
                    else yield self::Row($v, false, $colHeads);
                }
            })())):__($content, styling:false),
            "table",["class"=> "table"], $attributes);
    }
    /**
     * The <THEAD> HTML Tag
     * @param mixed $content The column labels array or content
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Column($content, ...$attributes){
        return self::Element(
            is_countable($content)?join(PHP_EOL, iterator_to_array((function() use($content, $head, $colHeads){
                yield self::Row($content, true);
            })())):__($content, styling:false),
            "thead",["class"=> "column"], $attributes);
    }
    /**
     * The <TR> HTML Tag
     * @param mixed $content The row array or content
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Row($content, $head = false, $colHeads = [0], ...$attributes){
        return self::Element(
            is_countable($content)?join(PHP_EOL, iterator_to_array((function() use($content, $head, $colHeads){
                foreach ($content as $k=>$v) yield self::Cell($v, $head?$head:in_array($k, $colHeads));
            })())):__($content, styling:false),
            "tr",["class"=> "row"], $attributes);
    }
    /**
     * The <TD> or <TH> HTML Tag
     * @param mixed $content The cell array or content
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Cell($content, $head = false, ...$attributes){
        return self::Element(__($content, styling:false), $head?"th":"td",["class"=> "cell"], $attributes);
    }
}
?>