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
    public static function Element($content = null, $tagName = null, ...$attributes) {
        $isSingle = !is_null($content) && is_array($tagName);
        if ($isSingle) {
            $attributes = $tagName;
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
                foreach(Convert::ToIteration($attributes) as $key=>$value)
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
                    self::Element($content, "body", ["class"=>"document"], $attributes)
                ],
                "html"
            );
    }

    public static function Title($content, ...$attributes){
        $srci = self::Element(__($content, styling:false), "title", $attributes);
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
        $srci = self::Element($content, "script", is_null($source) ? null:[ "src"=> $source ], $attributes);
        //array_push(self::$Sources, $srci);
        return $srci;
    }
    public static function Style($content, $source = null, ...$attributes){
        if (is_null($source)) $srci = self::Element($content, "style", $attributes);
        else $srci = self::Element(null,"link", [ "rel"=> "stylesheet", "href"=> $source ], $attributes);
        //array_push(self::$Sources, $srci);
        return $srci;
    }

    public static function Result($content, ...$attributes){
        return self::Element(__($content, styling:false), "div", ["class"=> "result"], $attributes);
    }
    public static function Success($content, ...$attributes){
        return self::Element(__($content, styling:false), "div", ["class"=> "result success"], $attributes);
    }
    public static function Warning($content, ...$attributes){
        return self::Element(__($content, styling:false), "div", ["class"=> "result warning"], $attributes);
    }
    public static function Error($content, ...$attributes){
        if(is_a($content, "Exception") || is_subclass_of($content, "Exception"))
            return self::Element(__($content->getMessage(), styling:false), "div", ["class"=> "result error"], $attributes);
        else return self::Element(__($content, styling:false), "div", ["class"=> "result error"], $attributes);
    }

    /**
     * The <VIDEO> HTML Tag
     * @param mixed $content The alternative content of the Tag
     * @param mixed $source The source path to show
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Video($content, $source = null, ...$attributes){
        if(!isValid($source)) $source = $content;
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
        if(!isValid($source)) $source = $content;
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
        if(!isValid($source)) $source = $content;
        elseif(is_array($source) && count($attributes)==0){
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
        if(!isValid($source)) $source = $content;
        elseif(is_array($source) && count($attributes)==0){
            $attributes = Convert::ToIteration($source, $attributes);
            $source = $content;
            $content = null;
        }
        if(!isValid($source)) return null;
        if(isIdentifier($source))
            return self::Element("", "i", [ "class"=>"media fa fa-".strtolower($source)], $attributes);
        else return self::Element($content??"","div", [ "style"=> "background-image: url('$source'); background-position: center; background-repeat: no-repeat; background-size: cover;", "class"=> "media" ], $attributes);
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
        if(!isValid($source)) $source = $content;
        elseif(is_array($source) && count($attributes)==0){
            $attributes = Convert::ToIteration($source, $attributes);
            $source = $content;
            $content = null;
        }
        if(isUrl($source))
            return self::Element($content, "iframe", [ "src"=> $source, "class"=> "embed" ], $attributes);
        return self::Element($content, "iframe", [ "srcdoc"=>str_replace("\"", "&quot;", Convert::ToString($source)), "class"=> "embed" ], $attributes);
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
        return self::Element(__($content),"center",["class"=> "center" ], $attributes);
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
            if(is_array($reference) && count($attributes)==0){
                $attributes = Convert::ToIteration($reference, $attributes);
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
            if(is_array($reference) && count($attributes)==0){
                $attributes = Convert::ToIteration($reference, $attributes);
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
            if(is_array($reference) && count($attributes)==0){
                $attributes = Convert::ToIteration($reference, $attributes);
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
            if(is_array($reference) && count($attributes)==0){
                $attributes = Convert::ToIteration($reference, $attributes);
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
            if(is_array($reference) && count($attributes)==0){
                $attributes = Convert::ToIteration($reference, $attributes);
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
            if(is_array($reference) && count($attributes)==0){
                $attributes = Convert::ToIteration($reference, $attributes);
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
            if(is_array($reference) && count($attributes)==0){
                $attributes = Convert::ToIteration($reference, $attributes);
                $reference = null;
            }
            else return self::Link(
                self::Element(__($content),"span",["class"=> "span" ], $attributes)
                , $reference);
        return self::Element(__($content),"span",["class"=> "span" ], $attributes);
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
        if(!isValid($reference)) $reference = $content;
        elseif(is_array($reference) && count($attributes)==0){
            $attributes = Convert::ToIteration($reference, $attributes);
            $reference = $content;
            $content = null;
        }
        if(!isValid($content)) $content = getDomain($reference);
        return self::Element(__($content), "a", [ "href"=> $reference, "class"=> "link" ], $attributes);
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
            return self::Link($content, $reference,["class"=> "button btn" ], $attributes);
        return self::Element(__($content),"button",["class"=> "button btn", "onclick"=>$reference], $attributes);
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
        return self::Button(self::Media("", $content, ["class"=>"icon"]), $reference, $attributes);
    }
}
?>