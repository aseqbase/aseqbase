<?php
namespace MiMFa\Library;
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
    public static $ManageAttributes = true;
    public static $MaxFloatDecimals = 2;
    public static $MaxValueLength = 10;
    public static $NewLine = "<br/>";
    public static $HorizontalBreak = "<hr/>";

    /**
     * Create standard html element
     * @param mixed $content The content of the Tag, send null to create single tag
     * @param string|null $tagName The HTML tag name
     * @param array $tagName Other custom attributes of the single Tag
     * @param array|string|null $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Element($content = null, array|string|null $tagName = null, ...$attributes) {
        $isSingle = (!is_null($content)) && is_string($content) && is_array($tagName);
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
                                    $attrdic[$key] .= PHP_EOL.$value;
                                    break;
                                case "class":
                                    $attrdic[$key] .= " $value";
                                    break;
                                case "onclick":
                                case "ondblclick":
                                case "onchange":
                                case "onload":
                                case "oninput":
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
                //Standardization
                foreach($attrdic as $key=>$value)
                    switch ($key)
                    {
                        case "max":
                            $attrdic["onchange"] = getValid($attrdic,"onchange").PHP_EOL."if(this.value > $value) this.value = $value;";
                            break;
                        case "min":
                            $attrdic["onchange"] = getValid($attrdic,"onchange").PHP_EOL."if(this.value < $value) this.value = $value;";
                            break;
                    }
                //Integration
                foreach($attrdic as $key=>$value)
                    switch ($key)
                    {
                        case "style":
                            if(self::$ManageAttributes){
                                if(!isValid($id)){
                                    $id = "_".getId(true);
                                    $attrs .= " id='$id'";
                                }
                                $attachments .= self::Style("#$id{{$value}}");
                            } else $attrs .= " ".self::Attribute($key, $value);
                            break;
                        case "onclick":
                        case "ondblclick":
                        case "onchange":
                        case "onload":
                        case "oninput":
                        case "onmouseover":
                        case "onmouseout":
                            if(self::$ManageAttributes){
                                if(!isValid($id)){
                                    $id = "_".getId(true);
                                    $attrs .= " id='$id'";
                                }
                                $scripts[] = "document.getElementById('$id').$key = function(e){{$value}};";
                            } else $attrs .= " ".self::Attribute($key, $value);
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
            } else $attrs = Convert::ToString($attributes);
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
    /**
     * The <META> HTML Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Description($content, ...$attributes){
        $srci = self::Meta("abstract",$content, $attributes);
        $srci .= self::Meta("description",$content, $attributes);
        $srci .= self::Meta("twitter:description",$content, $attributes);
        return $srci;
    }
    /**
     * The <META> HTML Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Keywords($content, ...$attributes){
        return self::Meta("keywords", is_array($content)?join(", ", $content):$content, $attributes);
    }
    /**
     * The <META> HTML Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Meta($name, $content, ...$attributes){
        $srci = self::Element("meta", ["name"=>$name, "content"=>$content], $attributes);
        //array_push(self::$Sources, $srci);
        return $srci;
    }
    public static function Script($content, $source = null, ...$attributes){
        $srci = self::Element($content??"", "script", is_null($source) ? ["type"=>"text/javascript"]:[ "src"=> $source ], $attributes);
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
        if(startsWith($source, "fa ", "fa-"))
            return self::Element("", "i", [ "class"=>"image ".strtolower($source)], $attributes);
        elseif(isIdentifier($source))
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
        else return self::Element(__($content??"", styling:false), "div", [ "style"=> "background-image: url('".Local::GetUrl($source)."'); background-position: center; background-repeat: no-repeat; background-size: cover;", "class"=> "media" ], $attributes);
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
        return self::Element(__($content, styling:false),"div",["class"=> "page" ], $attributes);
    }
    /**
     * The <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Part($content, ...$attributes){
        return self::Element(__($content, styling:false),"div",["class"=> "part" ], $attributes);
    }
    /**
     * The <HEADER> HTML Tag
     * @param mixed $content The content of the header Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Header($content, ...$attributes){
        return self::Element(__($content, styling:false),"header",["class"=> "header" ], $attributes);
    }
    /**
     * The <MAIN> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Content($content, ...$attributes){
        return self::Element(__($content, styling:false),"main",["class"=> "content" ], $attributes);
    }
    /**
     * The <FOOTER> HTML Tag
     * @param mixed $content The content of the footer Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Footer($content, ...$attributes){
        return self::Element(__($content, styling:false),"footer",["class"=> "footer" ], $attributes);
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
        return self::Element(__($content, styling:false),"div",["class"=> "container" ], $attributes);
    }
    /**
     * The Container <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function LargeContainer($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::LargeRack($item);
            $content = $res;
        }
        return self::Element(__($content, styling:false),"div",["class"=> "container large-container" ], $attributes);
    }
    /**
     * The Container <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function MediumContainer($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::MediumRack($item);
            $content = $res;
        }
        return self::Element(__($content, styling:false),"div",["class"=> "container medium-container" ], $attributes);
    }
    /**
     * The Container <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function SmallContainer($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::SmallRack($item);
            $content = $res;
        }
        return self::Element(__($content, styling:false),"div",["class"=> "container small-container" ], $attributes);
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
        return self::Element(__($content, styling:false),"div",["class"=> "frame container-fluid" ], $attributes);
    }
    /**
     * The Main Partitioner <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function LargeFrame($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::LargeRack($item);
            $content = $res;
        }
        return self::Element(__($content, styling:false),"div",["class"=> "frame large-frame container-fluid" ], $attributes);
    }
    /**
     * The Main Partitioner <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function MediumFrame($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::MediumRack($item);
            $content = $res;
        }
        return self::Element(__($content, styling:false),"div",["class"=> "frame medium-frame container-fluid" ], $attributes);
    }
    /**
     * The Main Partitioner <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function SmallFrame($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::SmallRack($item);
            $content = $res;
        }
        return self::Element(__($content, styling:false),"div",["class"=> "frame small-frame container-fluid" ], $attributes);
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
        return self::Element(__($content, styling:false),"div",["class"=> "rack row" ], $attributes);
    }
    /**
     * The Row Partitioner <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function LargeRack($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::LargeSlot($item);
            $content = $res;
        }
        return self::Element(__($content, styling:false),"div",["class"=> "rack large-rack row" ], $attributes);
    }
    /**
     * The Row Partitioner <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function MediumRack($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::MediumSlot($item);
            $content = $res;
        }
        return self::Element(__($content, styling:false),"div",["class"=> "rack medium-rack row" ], $attributes);
    }
    /**
     * The Row Partitioner <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function SmallRack($content, ...$attributes){
        if(is_countable($content)){
            $res = [];
            foreach ($content as $item) $res[] = self::SmallSlot($item);
            $content = $res;
        }
        return self::Element(__($content, styling:false),"div",["class"=> "rack small-rack row" ], $attributes);
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
        return self::Element(__($content, styling:false),"div",["class"=> "slot col" ], $attributes);
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
        return self::Element(__($content, styling:false),"div",["class"=> "slot large-slot col-lg" ], $attributes);
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
        return self::Element(__($content, styling:false),"div",["class"=> "slot medium-slot col-md" ], $attributes);
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
        return self::Element(__($content, styling:false),"div",["class"=> "slot small-slot col-sm" ], $attributes);
    }
    /**
     * The <DIV> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Division($content, ...$attributes){
        return self::Element(__($content, styling:false),"div",["class"=> "division" ], $attributes);
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
        return self::Element(__($content, styling:false),"ol",["class"=> "list" ], $attributes);
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
        return self::Element(__($content, styling:false),"ul",["class"=> "items" ], $attributes);
    }
    /**
     * The List Item <LI> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Item($content, ...$attributes){
        return self::Element(__($content, styling:false),"li",["class"=> "item" ], $attributes);
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
                self::Element(__($content, styling:false),"h1",["class"=> "externalheading" ], $attributes)
                , $reference);
        return self::Element(__($content, styling:false),"h1",["class"=> "externalheading" ], $attributes);
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
                self::Element(__($content, styling:false),"h2",["class"=> "superheading" ], $attributes)
                , $reference);
        return self::Element(__($content, styling:false),"h2",["class"=> "superheading" ], $attributes);
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
                self::Element(__($content, styling:false),"h3",["class"=> "heading" ], $attributes)
                , $reference);
        return self::Element(__($content, styling:false),"h3",["class"=> "heading" ], $attributes);
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
                self::Element(__($content, styling:false),"h4",["class"=> "subheading" ], $attributes)
                , $reference);
        return self::Element(__($content, styling:false),"h4",["class"=> "subheading" ], $attributes);
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
                self::Element(__($content, styling:false),"h5",["class"=> "internalheading" ], $attributes)
                , $reference);
        return self::Element(__($content, styling:false),"h5",["class"=> "internalheading" ], $attributes);
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
                self::Element(__($content, styling:false),"p",["class"=> "paragraph" ], $attributes)
                , $reference);
        return self::Element(__($content, styling:false),"p",["class"=> "paragraph" ], $attributes);
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
    public static function Link($content, $reference = null, ...$attributes) {
        if(is_null($reference)) {
            $reference = $content;
            $content = null;
        } elseif(is_array($reference) && count($attributes) === 0) {
            $attributes = Convert::ToIteration($reference);
            $reference = $content;
            $content = null;
        }
        if(is_null($content)) $content = getDomain($reference);
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
        if(isScript($reference) || !isUrl($reference))
            return self::Element(__($content, styling:false), "button",["class"=> "btn button", "type"=>"button", "onclick"=>$reference], $attributes);
        return self::Link($content, $reference, ["class"=> "btn button"], $attributes);
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
        if(isScript($reference) || !isUrl($reference))
            return self::Media("", $content, ["class"=> "icon", "onclick"=>$reference], $attributes);
        return self::Link(self::Media("", $content), $reference, ["class"=> "icon"], $attributes);

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
        elseif(is_array($content) || is_iterable($content))
            $content = function()use($content){ return join(PHP_EOL, loop($content, function($k, $f){
                if(is_integer($k)) return call_user_func("self::Field", $f);
                else return call_user_func("self::Field", null, $k, $f);
            }));};
        else $content = __($content, styling:false);
        return self::Element($content, "form", isValid($reference)?["action"=> $reference]:[], [ "enctype"=>"multipart/form-data", "method"=>"get", "class"=> "form" ], $attributes);
    }
    /**
     * The <LABEL> and any input HTML Tag
     * @param object|string|array|callable|\Closure|\stdClass|null $type Can be a datatype or an input type
     * @param mixed $key The default key and the name of the field
     * @param mixed $value The default value of the field
     * @param mixed $description The more detaled text about the field
     * @param array|iterable|bool|string|null $options The other options of the field
     * @param array|string|null $attributes Other important attributes of the field
     * @param mixed $title The label text of the field
     * @return string
     */
    public static function Field($type = null, $key = null, $value = null, $description = null, $options = null, $attributes = [], $title = null, $scope = true){
        if(is_array($description) && count($attributes) === 0){
            $attributes = Convert::ToIteration($description);
            $description = null;
        }
        if(is_null($type))
            $type = self::InputDetector($type, $value);
        if(is_callable($type) || ($type instanceof \Closure))
            return self::Field(
                type:$type($type, $value),
                key:$key,
                value:$value,
                description:$description,
                options:$options,
                attributes:$attributes,
                title:$title,
                scope:$scope
            );
        elseif(is_object($type) || ($type instanceof \stdClass)){
            $key = getValid($type, "Key", $key);
            $value = getValid($type, "Value", $value);
            $title = getValid($type, "Title", $title);
            $description = getValid($type, "Description", $description);
            $options = getValid($type, "Options", $options);
            $scope = getValid($type, "Scope", $scope);
            $attributes = [...$attributes, ...(isValid($type, "Attributes")?[getValid($type, "Attributes")]:[])];
            $type = self::InputDetector(getValid($type, "Type", null), $value);
        } elseif(is_countable($type)) {
            if(is_null($options)) {
                $options = $type;
                $type = "select";
            } else {
                $key = getValid($type, "key", $key);
                $value = getValid($type, "value", $value);
                $title = getValid($type, "title", $title);
                $description = getValid($type, "description", $description);
                $options = getValid($type, "options", $options);
                $scope = getValid($type, "scope", $scope);
                $attributes = [...$attributes, ...(isValid($type, "Attributes")?[getValid($type, "Attributes")]:[])];
                $type = self::InputDetector(getValid($type, "type", null), $value);
            }
        } else $type = self::InputDetector($type, $value);
        $titleOrKey = $title??Convert::ToTitle(Convert::ToString($key));
        $key = Convert::ToKey(Convert::ToString($key??$title));
        $id = Convert::ToId($key).getID();
        $attributes = [["id"=>$id,"name"=>$key], ...$attributes];
        $titleTag = ($title===false || !isValid($titleOrKey)?"":self::Label($titleOrKey, $id, ["class"=> "title"]));
        $descriptionTag = ($description===false || !isValid($description)?"":self::Label($description, $id, ["class"=> "description"]));
        switch ($type)
        {
            case 'span':
                $content = self::Span($value??$titleOrKey, null, $attributes);
                break;
            case 'div':
            case 'division':
                $content = self::Division($value??$titleOrKey, null, $attributes);
                break;
            case 'p':
            case 'paragraph':
                $content = self::Paragraph($value??$titleOrKey, null, $attributes);
                break;
        	case "disable":
        	case "disabled":
                $content = self::DisabledInput($title, $value, $attributes);
                break;
            case 'label':
            case 'key':
            case 'title':
            case 'description':
                $content = self::Label($value??$titleOrKey, $id, $attributes);
                break;
            case 'object':
                $content = self::ObjectInput($title, Convert::ToString($value), $attributes);
                break;
            case 'collection'://A collection of Base based objects

            case 'countable':
            case 'iterable':
            case 'array':
                $content = self::ArrayInput($title, $value, $options, $attributes);
                break;
            case 'lines':
            case 'texts':
            case 'strings':
            case 'multiline':
            case 'textarea':
                $content = self::TextInput($title, $value, $attributes);
                break;
            case 'content':
                $content = self::ContentInput($title, $value, $attributes);
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
                $content = self::RadioInput($titleOrKey, $value, $attributes);
                break;
            case 'bool':
            case 'boolean':
            case 'check':
            case 'checkbox':
            case 'checkbutton':
                $content = self::CheckInput($titleOrKey, $value, $attributes);
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
                $content = self::FloatInput($title, $value, ["min"=>$min, "max"=>$max], $attributes);
                break;
            case 'phone':
            case 'tel':
            case 'telephone':
                $content = self::TelInput($title, $value, $attributes);
                break;
            case 'url':
                $content = self::UrlInput($title, $value, $attributes);
                break;
            case 'map':
            case 'location':
            case 'path':
                $content = self::ValueInput($title, $value, $attributes);
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
                $content = self::ScriptInput($title, $value, $attributes);
                break;
        	default:
                $content = self::Input($title, $value, $type, $attributes);
                break;
        }
        if($scope) return self::Element($titleTag.$content.$descriptionTag,"div", ["class"=> "field"]);
        else return $titleTag.$content.$descriptionTag;
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function SubmitButton($key = "Submit", $value = null, ...$attributes){
        if(is_array($value) && count($attributes) === 0){
            $attributes = Convert::ToIteration($value);
            $value = null;
        }
        return self::Element(__($value??$key, styling:false), "button", [ "id"=>Convert::ToId($key), "name"=>Convert::ToKey($key), "class"=> "button submitbutton", "type"=>"submit"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ResetButton($key = "Reset", $value = null, ...$attributes){
        if(is_array($value) && count($attributes) === 0){
            $attributes = Convert::ToIteration($value);
            $value = null;
        }
        return self::Element(__($value??$key, styling:false), "button", [ "id"=>Convert::ToId($key), "name"=>Convert::ToKey($key), "class"=> "button resetbutton", "type"=>"reset"], $attributes);
    }
    /**
     * Detect the type of inputed value
     * @param mixed $type The suggestion type of value
     * @param mixed $value The sample value
     * @return string
     */
    public static function InputDetector($type = null, $value = null){
        if(is_null($type))
            if(isEmpty($value)) return "text";
            elseif(is_string($value))
                if(isUrl($value))
                    if(isFile($value)) return "file";
                    else return "url";
                elseif(strlen($value)>100 || count(explode("\r\n\t\f\v",$value))>1)
                    return "textarea";
                else return "text";
            else return strtolower(gettype($value));
        elseif(is_callable($type) || ($type instanceof \Closure))
            return self::InputDetector($type($type, $value), $value);
        elseif(is_object($type) || ($type instanceof \stdClass))
            return self::InputDetector(getValid($type, "Type", null), $value);
        elseif(is_countable($type)) return "select";
        else return strtolower($type);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function Input($key, $value = null, $type = null, ...$attributes){
        if(is_array($type) && count($attributes) === 0){
            $attributes = Convert::ToIteration($type);
            $type = "text";
        }
        return self::Element("input", [ "id"=>Convert::ToId($key), "name"=>Convert::ToKey($key), "placeholder"=> __(Convert::ToTitle($key), styling:false), "type"=> $type, "value"=> $value, "class"=>"input" ], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ValueInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "text", ["class"=>"valueinput"], $attributes);
    }
    /**
     * The <TEXTAREA> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function TextInput($key, $value = null, ...$attributes){
        return self::Element($value??"", "textarea", [  "id"=>Convert::ToId($key), "name"=>Convert::ToKey($key), "placeholder"=>  __(Convert::ToTitle($key), styling:false), "class"=>"input textinput" ], $attributes);
    }
    /**
     * The <TEXTAREA> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ContentInput($key, $value = null, ...$attributes){
        return self::TextInput($key, $value, [ "class"=>"contentinput", "style"=>"font-size: 75%; overflow:scroll; word-wrap: unset;" ], $attributes);
    }
    /**
     * The <TEXTAREA> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ScriptInput($key, $value = null, ...$attributes){
        return self::TextInput($key, $value, [ "class"=>"scriptinput", "style"=>"font-size: 75%; overflow:scroll; word-wrap: unset;" ], $attributes);
    }
    /**
     * The <TEXTAREA> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ObjectInput($key, $value = null, ...$attributes){
        return self::TextInput($key, $value, [ "class"=>"objectinput", "style"=>"font-size: 75%; overflow:scroll; word-wrap: unset;" ], $attributes);
    }
    /**
     * A <DIV> HTML Tag contains an array of Inputs
     * @param mixed $key The tag name, id, or placeholder
     * @param array|iterable|null $value The tag default value
     * @param array|object $options The other options, default are: ["add"=>true, "remove"=>true]
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ArrayInput($key, $value = null, $options = ["type"=>null,"add"=>true,"remove"=>true], ...$attributes){
        if(is_null($value))
            if(is_array($key)) {
                $value = $key;
                $key = "_".getId();
            } else $value = [];
        $key = Convert::ToKey($key);
        return self::Division(function() use($key, $value, $options, $attributes){
            $sample = null;
            $add = getValid($options, "add", true);
            //$edit = getValid($options, "edit", true);
            $rem = getValid($options, "remove", true);
            $sep = getValid($options, "separator", null);
            $type = self::InputDetector(getValid($options, "type", null), getValid($options, "value", null));
            $key = getValid($options, "key", $key);
            $attrs = getValid($options, "attributes", []);
            $options = getValid($options, "options", null);
            if(isEmpty($value)) $value = [];
            elseif(is_string($value)) $value = is_null($sep)&&startsWith($value,"[","{")?json_decode($value):explode($sep??"|", trim($value, $sep??"|"));
            foreach ($value as $k=>$item){
                if(is_null($sample)) $sample = $item;
                $Id = Convert::ToId($key).getId();
                yield self::Field(
                    type:$type,
                    scope:!$rem,
                    key:$key,
                    value:$item,
                    title:false,
                    description:false,
                    options:$options,
                    attributes:[($rem?["ondblclick"=>"this.remove();"]:null), ...$attributes, ["id"=>$Id, "name"=>(is_numeric($k)?"{$key}[]":"{$key}[$k]")], ...$attrs]);
            }
            if($add)
            {
                $id = Convert::ToId($key)."_add_".getId();
                $oc = "
                        let tag = document.getElementById(`$id`).cloneNode(true);
                        tag.id = `$key".getId()."`;
                        tag.name = `{$key}[]`;
                        tag.setAttribute(`class`,`input`);
                        tag.setAttribute(`style`,``);
                        ".($rem?"tag.ondblclick = function(){ this.remove(); };":"")."
                        this.parentElement.appendChild(tag);";
                yield self::Field(
                    type:self::InputDetector($type, $sample),
                    key:$key,
                    value:null,
                    title:false,
                    description:self::Icon("plus", $oc),
                    options:$options,
                    attributes:[...$attributes, "onchange"=>$oc, "id"=>$id, "name"=>"","style"=>"display: none;", ...$attrs]);
            }
        },["id"=>$key, "class"=>"arrayinput"]);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function CheckInput($key, $value = null, ...$attributes){
        $id = "checkinput_".getId(true);
        if($value) return self::Input(null, null, "checkbox", ["class"=>"checkinput", "checked"=>"checked", "name"=>null, "onchange"=>"document.getElementById('$id').value = this.checked?1:0;"]).
            self::HiddenInput($key, "1", ["class"=>"checkinput"], $attributes, ["id"=>$id]);
        else return self::Input(null, null, "checkbox", ["class"=>"checkinput", "name"=>null, "onchange"=>"document.getElementById('$id').value = this.checked?1:0;"]).
            self::HiddenInput($key, "0", ["class"=>"checkinput"], $attributes, ["id"=>$id]);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function RadioInput($key, $value = null, ...$attributes){
        if($value) return self::Input($key, $key, "radio", ["class"=>"radioinput", "checked"=>"checked"], $attributes);
        else return self::Input($key, $key, "radio", ["class"=>"radioinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ColorInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "color", ["class"=>"colorinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function DateTimeInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "datetime-local", ["class"=>"datetimeinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function DateInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "date", ["class"=>"dateinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function TimeInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "time", ["class"=>"timeinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function MonthInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "month", ["class"=>"monthinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function WeekInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "week", ["class"=>"weekinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function PathInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "text", ["class"=>"pathinput", "pattern"=>'[^\<\>\^\`\{\|\}\r\n\t\f\v]*'], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function UrlInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "url", ["class"=>"urlinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function EmailInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "email", ["class"=>"emailinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function FileInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "file", ["class"=>"fileinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function FilesInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "file", ["class"=>"fileinput", "multiple"=>null], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function DirectoryInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "file", ["class"=>"fileinput", "webkitdirectory", "multiple"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function SearchInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "search", ["class"=>"searchinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function TelInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "tel", ["class"=>"telinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
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
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function NumberInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "number", ["class"=>"numberinput", "inputmode"=>"numeric"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function FloatInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "number", ["class"=>"floatinput", "step"=>"0.001", "inputmode"=>"numeric"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function SecretInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "password", ["class"=>"secretinput"], $attributes);
    }
    /**
     * The <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function HiddenInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "hidden", ["class"=>"hiddeninput"], $attributes);
    }
    /**
     * A Disabled <INPUT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function DisabledInput($key, $value = null, ...$attributes){
        return self::Input($key, $value, "text", ["class"=>"disabledinput", "disabled"=>"disabled"], $attributes);
    }
    /**
     * The <SELECT> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $options The tag value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function SelectInput($key, $value = null, $options = [], ...$attributes){
        return self::Element(
            is_iterable($options) || is_array($options)?iterator_to_array((function()use($options, $value, $attributes){
                $value = Convert::ToString($value);
                $f = false;
                if($f = isEmpty($value))
                    yield self::Element("","option",["value"=>"", "selected"=>"true"]);
                else yield self::Element("","option",["value"=>""]);
                foreach ($options as $k=>$v)
                    if(!$f && ($f = ($k == $value)))
                        yield self::Element(__($v??"", styling:false),"option",["value"=>$k, "selected"=>"true"]);
                    else yield self::Element(__($v??"", styling:false),"option",["value"=>$k]);
            })()):Convert::ToString($options, assignFormat:"<option value='{0}'>{1}</option>\r\n")
            ,"select", [ "id"=>Convert::ToId($key), "name"=>Convert::ToKey($key), "placeholder"=>  __(Convert::ToTitle($key), styling:false), "class"=>"input selectinput" ], $attributes);
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
            is_countable($content)?join(PHP_EOL, iterator_to_array((function() use($content){
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

    public static function Chart($type = "column", $content = null, $title = null, $description = null, $axisXTitle = "X", $axisYTitle = "Y", $attributes = [], $options = null, $color = null, $foreColor = null, $backColor = null, $font = "defaultFont", $height = "400px", $width = null, $axisXBegin = null, $axisYBegin = null, $axisXInterval = null, $axisYInterval = null) {
        if ($content === null) {
            $content = $type;
            $type = "column";
        }
        if ($content === null) return null;
        $isen = is_array($content);
        $isobj = is_object($content);
        $datachart = null;
        $id = "Chart".getId();
        if (!$isen && !$isobj) $datachart = $content;
        else {
            if (!$isen && $isobj) {
                $rows = between($content["matrix"], $content["table"], $content["rows"], $content["columns"]);
                if (isEmpty($rows)) $datachart = Convert::ToString($content);
                else{
                    $arr = [];
                    $l = between($content["labels"], $content["label"], -1);
                    $xs = between($content["axisX"], $content["xs"], $content["x"], []);
                    $ys = between($content["axisY"], $content["ys"], $content["y"], []);
                    $ct = 0;
                    if ($l > -1)
                        if (count($xs) > 0)
                            if (count($ys) > 0)
                                foreach ($rows as $row){
                                    $arr[] = getValid($row, $l);
                                    $arr[] = count($xs) == 1 ? floatval(getValid($row, $xs[0])) : loop($xs, function($i) use($row){return floatval(getValid($row, $i));});
                                    $arr[] = count($ys) == 1 ? floatval(getValid($row, $ys[0])) : loop($ys, function($i) use($row){return floatval(getValid($row, $i));});
                                }
                            else
                                foreach ($rows as $row){
                                    $arr[] = getValid($row, $l);
                                        $arr[] = count($xs) == 1 ? floatval(getValid($row, $xs[0])) : loop($xs, function($i) use($row){return floatval(getValid($row, $i));});
                                        $arr[] = $ct++;
                                }
                        else if (count($ys) > 0)
                            foreach ($rows as $row){
                                $arr[] = getValid($row, $l);
                                $arr[] = $ct++;
                                $arr[] = count($ys) == 1 ? floatval(getValid($row, $ys[0])) : loop($ys, function($i) use($row){return floatval(getValid($row, $i));});
                            }
                        else
                            foreach ($rows as $row)
                                $arr[] = getValid($row, $l);
                    else if (count($xs) > 0)
                        if (count($ys) > 0)
                            foreach ($rows as $row){
                                $arr[] = count($xs) == 1 ? floatval(getValid($row, $xs[0])) : loop($xs, function($i) use($row){return floatval(getValid($row, $i));});
                                $arr[] = count($ys) == 1 ? floatval(getValid($row, $ys[0])) : loop($ys, function($i) use($row){return floatval(getValid($row, $i));});
                            }
                        else
                            foreach ($rows as $row){
                                $arr[] = count($xs) == 1 ? floatval(getValid($row, $xs[0])) : loop($xs, function($i) use($row){return floatval(getValid($row, $i));});
                                $arr[] = $ct++;
                            }
                    else if (count($ys) > 0)
                            foreach ($rows as $row){
                                $arr[] = $ct++;
                                $arr[] = count($ys) == 1 ? floatval(getValid($row, $ys[0])) : loop($ys, function($i) use($row){return floatval(getValid($row, $i));});
                            }
                    $content = $arr;
                }
            }
            $arr = array();
            $isten = is_array($type);
            if ($isen && count($content) < 3)
                $arr = loop($content, function($i, $cnt) use($type, $isten){ return join("", ["{", "type: `", $isten ? $type[$i] : $type, "`", ", dataPoints: [", Script::Points($cnt), "]}"]); });
            else if ($isten)
                $arr = loop($type, function($i, $ty) use($content, $isen){ return join("", ["{", strpos($ty, ":") ? $ty : "type: `" + $ty + "`", ", dataPoints: [", Script::Points($isen ? $content[$i] : $content), "]}"]);});
            else $arr[] = join("", ["{type: `$type`, ",($color==null?"":"color: `$color`, "),"dataPoints: [", Script::Points($content), "]}"]);

            $datachart = "[".join(",", $arr)."]";
        }
        $axisXTitle = __($axisXTitle, styling:false);
        $axisYTitle = __($axisYTitle, styling:false);
        $title = __($title, styling:false);
        $description = __($description, styling:false);
        return self::Style(".canvasjs-chart-credit{display:none !important;}").
            self::Division(
               self::Heading($title).
               self::Script(null, getFullUrl("/view/script/canvasjs.min.js")).
               self::Script("
                    window.addEventListener(`load`, function()
                        {
                            var chart = new CanvasJS.Chart(`$id`, {
                                theme: `light2`,
							    zoomEnabled: true,
                                ".($backColor?"backgroundColor:`$backColor`,":"")."
                                ".($height?"height:".intval($height).",":"").($width?"width:intval($width),":"")."
							    legend: {
								    horizontalAlign: `center`,
								    verticalAlign: `top`,
                                    ".($foreColor?"fontColor:`$foreColor`,":"")."
		                            fontFamily: `".($font??"defaultFont")."`
							    },
                                axisX:{
                                    title: `$axisXTitle`,
                                    crosshair: {
                                        enabled: true
                                    },
                                    ".($axisXBegin?"minimum:$axisXBegin,":"")."
                                    ".($axisXInterval?"interval:$axisXInterval,":"")."
		                            labelTextAlign: `".Translate::$Direction."`,
                                    ".($foreColor?"fontColor:`$foreColor`,":"")."
                                    ".($foreColor?"titleFontColor:`$foreColor`,":"")."
                                    ".($foreColor?"labelFontColor:`$foreColor`,":"")."
		                            labelFontFamily: `".($font??"defaultFont")."`,
		                            titleFontFamily: `".($font??"defaultFont")."`
                                },
                                axisY:{
                                    title: `$axisYTitle`,
                                    crosshair: {
                                        enabled: true
                                    },
                                    ".($axisYBegin?"minimum:$axisYBegin,":"")."
                                    ".($axisYInterval?"interval:$axisYInterval,":"")."
		                            labelTextAlign: `".Translate::$Direction."`,
                                    ".($foreColor?"fontColor:`$foreColor`,":"")."
                                    ".($foreColor?"titleFontColor:`$foreColor`,":"")."
                                    ".($foreColor?"labelFontColor:`$foreColor`,":"")."
		                            labelFontFamily: `".($font??"defaultFont")."`,
		                            titleFontFamily: `".($font??"defaultFont")."`
                                },
                                toolTip: {
                                    shared: true,
		                            fontFamily: `defaultFont`
                                },
                                title:{
                                    text: `$title`,
                                    ".($foreColor?"fontColor:`$foreColor`,":"")."
                                    verticalAlign: `top`,
                                    horizontalAlign: `center`,
		                            fontFamily: `".($font??"defaultFont")."`
                                },
                                subtitles:{
                                    text: `$description`,
                                    ".($foreColor?"fontColor:`$foreColor`,":"")."
                                    verticalAlign: `bottom`,
                                    horizontalAlign: `center`,
		                            fontFamily: `".($font??"defaultFont")."`
                                },
                                data: $datachart".($options == null ? "" : ",
                                ".$options)."
                            });
                            chart.render();
                        });"),
                        ["id"=> $id, "style"=>($height?"height:$height;":"").($width?"width:$width;":""), "class"=>"chart" ], $attributes);
    }
}
?>