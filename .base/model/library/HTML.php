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
    public static $Sources = array();
    public static $MaxFloatDecimals = 2;
    public static $MaxValueLength = 10;
    public static $NewLine = "<br/>";

    /**
     * Create standard html element
     * @param mixed $content
     * @param mixed $tagName
     * @param mixed $attributes
     * @return string
     */
    public static function Element($content = null, $tagName = "html", $attributes = array()) {
        if (is_array($tagName)) {
            $attributes = $tagName;
            $tagName = $content;
            $content = null;
        }
        $isSingle = is_null($content);
        $tagName = trim(strtolower($tagName));

        $attrs = "";
        if(!is_null($attributes))
            foreach($attributes as $key=>$value)
                if(is_null($value)){
                    $attrs .= join("",array(" ", trim(strtolower($key)),""));
                } else {
                    $sp = str_contains($value,"'") ? '"' : "'";
                    $attrs .= join("",array(" ", trim(strtolower($key)), "=", $sp, $value, $sp));
                }
        if ($isSingle) return join("",array("<", $tagName, $attrs, "/>"));
        else return join("",array("<", $tagName, $attrs, ">", Convert::ToString($content), "</", $tagName, ">"));
    }


    public static function Title($content, $attributes = array()){
        $srci = self::Element($content, "title", $attributes);
        array_push(self::$Sources, $srci);
        return $srci;
    }
    public static function Script($content, $source = null, $attributes = array()){
        $srci = self::Element($content, "script", is_null($source) ? $attributes : [ ...[ "src"=> $source ], ...$attributes ]);
        array_push(self::$Sources, $srci);
        return $srci;
    }
    public static function Style($content, $source = null, $attributes = array()){
        if (is_null($source)) $srci = self::Element($content, "style", $attributes);
        else $srci = self::Element("link", [ ...[ "rel"=> "stylesheet", "href"=> $source ?? $content ], ...$attributes ]);
        array_push(self::$Sources, $srci);
        return $srci;
    }

    public static function Documents($title, $content = null, $description = null, $attributes = array(), $sources = array()){
        if ($content === null) {
            $content = $title;
            $title = null;
        }
        if ($content === null) return null;
        if (!is_array($content)) return self::Document($title, $content, $description, $attributes, $sources);
        $c = count($content);
        if ($c == 0) return self::Document($title, null, $description, $attributes, $sources);
        if ($c == 1) return self::Document($title, $content[0], $description, $attributes, $sources);

        $p = Math::Slice($c, 100, 100, 20, 33);
        $ls = [];
        foreach($content as $item) {
            array_push($ls, join("",[`<iframe src="" srcdoc="`,
                str_replace("\"", "&quot;",Convert::ToString($item)),
                `" class="embed" style="width:`, $p["X"], `vw;height:`, $p["Y"],
                `vh;" marginwidth="0" marginheight="0" align="top" frameborder="0" hspace="0" vspace="0"></iframe>`]));
        }
        return self::Document($title, join("\r\n\r\n", $ls), $description, $attributes, $sources);
    }
    public static function Document($title, $content = null, $description = null, $attributes = array(), $sources = array()){
        if ($content === null) {
            $content = $title;
            $title = null;
        }
        if ($content === null) return null;
        $head = join("\r\n",array_unique([...self::$Sources,...$sources]));
        self::$Sources = array();

        return "<!DOCTYPE HTML>" +
            self::Element([
                    self::Element([
                        self::Title($title),
                        self::Element("meta", [ "charset"=> Translate::$Encoding ]),
                        self::Element("meta", [ "lang"=> Translate::$Language ]),
                        $head
                    ],
                    "head"
                    ),
                    self::Element( Convert::ToString($content), "body")
                ],
                "html"
            );
    }

    public static function Success($content, $attributes = array("class"=>'result success')){
        return self::Element(__($content), "div", $attributes);
    }
    public static function Error($content, $attributes = array("class"=>'result error')){
        if(is_a($content, "Exception") || is_subclass_of($content, "Exception"))
            return self::Element(__($content->getMessage()), "div", $attributes);
        else return self::Element(__($content), "div", $attributes);
    }
    public static function Warning($content, $attributes = array("class"=>'result warning')){
        return self::Element(__($content), "div", $attributes);
    }

    public static function Video($content, $source = null, $attributes = ["controls"=>null]){
        if(!isValid($source)) $source = $content;
        if(!isValid($content)) $content = $source;
        return self::Element(self::Element("source", ["src"=> $source ]).$content, "video", $attributes);
    }
    public static function Audio($content, $source = null,  $attributes = ["controls"=>null]){
        if(!isValid($source)) $source = $content;
        if(!isValid($content)) $content = $source;
        return self::Element(self::Element("source", ["src"=> $source ]).$content, "audio", $attributes);
    }
    public static function Image($content, $source = null, $attributes = array()){
        if(!isValid($source)) $source = $content;
        if(!isValid($content)) $content = getDomain($source);
        return self::Element("img", [ ...[ "src"=> $source, "alt"=> $content ], ...$attributes ]);
    }
    public static function Link($content, $source = null, $attributes = array()){
        if(!isValid($source)) $source = $content;
        if(!isValid($content)) $content = getDomain($source);
        return self::Element($content, "a", [ ...[ "href"=> $source ], ...$attributes ]);
    }
    public static function Frame($content, $source = null, $attributes = array()){
        if(!isValid($source)) $source = $content;
        return self::Element($content, "iframe", [ ...[ "src"=> $source ], ...$attributes ]);
    }

}