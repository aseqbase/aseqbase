<?php
namespace MiMFa\Library;
/**
 * A simple library to create default and standard HTML tags
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#html See the Library Documentation
 */
class Html
{
    #region MAIN
    public static $AttributesOptimization = false;
    public static $MaxDecimalPrecision = 2;
    public static $MaxValueLength = 10;
    /**
     * A custom Break Line (\<BR\/> Tag)
     * @var string
     */
    public static $Break = "<br/>";
    /**
     * A custom Horizontal Row (\<HR\/> Tag)
     * @var string
     */
    public static $BreakLine = "<hr/>";

    /**
     * Convert everything to a simple HTML format,
     * Supports all MarkDown markups
     * @param mixed $object
     */
    public static function Convert($object)
    {
        if (!is_null($object)) {
            if (is_string($object)) {
                if (preg_match("/(?<!\\\)\<[^\>]+(?<!\\\)\>/i", $object))
                    return $object;
                else {
                    $patt = '/(=\s*(("[^"]*")|(\'[^\']*\')))|((<([A-Z\w?:][A-Za-z0-9-_.?:\/\\\]*)[^>]*((>[^<]*<\/\7>)|(\/?>))))/i';
                    $tagPatt = '/("\S+[^"]*")|(\'\S+[^\']*\')|(<\S+[\w\W]*[^\\\\]>)/iU';
                    $object = preg_replace('/\\\(?=[<>])/s', "", $object); // Remove Escapes

                    $object = encode($object, $dic, pattern: $tagPatt);// To keep all previous tags unchanged

                    // Codes
                    $object = preg_replace("/`?``\s?([\s\S]+?)\s?```?(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", self::CodeBlock("$1", ["id"=>"$2", "class"=>"$3"], "$4"), $object); // Code blocks
                    $object = encode($object, $dic, pattern: $patt);// To keep all previous tags unchanged
                    $object = preg_replace("/(?<!`)`([^`\r\n]+)`(?!`)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", self::Code("$1", ["id"=>"$2", "class"=>"$3"], "$4"), $object); // Inline code
                    $object = encode($object, $dic, pattern: $patt);// To keep all previous tags unchanged
                    $object = preg_replace("/((^[ \t]*>.*$\s?)+)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/im", self::CodeBlock("$1", ["id"=>"$2", "class"=>"$3"], "$4"), $object); // Blockquotes
                    $object = encode($object, $dic, pattern: $patt);// To keep all previous tags unchanged

                    // Quotes
                    $object = preg_replace_callback('/(?<!")"(\S[\r\n"]+?\S)"(?!")(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i', fn($m) => self::Quote($m[1], ["id"=>$m[2]??null], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object);
                    $object = preg_replace_callback('/"?""\s?([\s\S]+?)\s?"""?(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/si', fn($m) => self::QuoteBlock($m[1], ["id"=>$m[2]??null], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object); // Blockquotes

                    // Headings
                    $object = preg_replace_callback("/\s?^[ \t]*\#\s(.*)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?$/im", fn($m) => self::ExternalHeading($m[1], null, ["id"=>$m[2]??null], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object);
                    $object = preg_replace_callback("/\s?^[ \t]*\#{2}\s(.*)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?$/im", fn($m) => self::SuperHeading($m[1], null, ["id"=>$m[2]??null], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object);
                    $object = preg_replace_callback("/\s?^[ \t]*\#{3}\s(.*)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?$/im", fn($m) => self::Heading($m[1], null, ["id"=>$m[2]??null], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object);
                    $object = preg_replace_callback("/\s?^[ \t]*\#{4}\s(.*)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?$/im", fn($m) => self::SubHeading($m[1], null, ["id"=>$m[2]??null], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object);
                    $object = preg_replace_callback("/\s?^[ \t]*\#{5}\s(.*)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?$/im", fn($m) => self::InternalHeading($m[1], null, ["id"=>$m[2]??null], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object);
                    $object = preg_replace_callback("/\s?^[ \t]*\#{6}\s(.*)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?$/im", fn($m) => self::InlineHeading($m[1], null, ["id"=>$m[2]??null], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object);

                    // Tables
                    $object = preg_replace_callback(
                        "/((?:\s?^[ \t]*\|.*\|?[ \t]*$)+)/m",
                        function ($matches) {
                            return self::Table(
                                preg_replace_callback(
                                    "/\s?^[ \t]*\|\|?(.*)\|?[ \t]*$/m",
                                    function ($rmatches) {
                                        return self::Row(
                                            preg_replace_callback(
                                                "/[ \t]*([^|\r\n]+)[ \t]*(((\|\|?$)|(\|\|?)|$))/",
                                                function ($cmatches) {
                                                    return self::Cell($cmatches[1], strlen($cmatches[2]) > 1 ? ["Type" => "head"] : [], ($dir = Translate::GetDirection($cmatches[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]);
                                                },
                                                $rmatches[1]
                                            )
                                        );
                                    },
                                    $matches[1]
                                )
                            );
                        },
                        $object
                    );
                    
                    // Media
                    $object = preg_replace_callback("/!\{([^\}]+)\}(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", fn($m) => self::Division($m[1], ["id"=>$m[2]??null], ["class"=>$m[3]??null], $m[4]??[]), $object);
                    $object = preg_replace_callback("/!(\w*)\[([^\]]+)\]\(\s*([^\s]+)\s*\)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", fn($m) =>("\\MiMFa\\Library\\Html::".($m[1]?:"Media"))($m[2]??null, $m[3]??null, ["id"=>$m[4]??null], ["class"=>$m[5]??null], $m[6]??[]), $object);
                    $object = preg_replace_callback("/\[([^\]]+)\]\(\s*([^\s]+)\s*\)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", fn($m) => self::Link($m[1], $m[2], ["id"=>$m[3]??null], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[4]??null)] : ["class" => "be $dir ".($m[4]??null)], $m[5]??[]), $object);

                    // Refer
                    $object = preg_replace_callback("/\[([\w\-]+)\](?!\(|:)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", fn($m) => self::Span("[$m[1]]", "#fn-$m[1]", ["id"=>$m[3]??null], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[4]??null)] : ["class" => "be $dir ".($m[4]??null)], $m[5]??[]), $object);
                    $object = preg_replace_callback("/\[\^([^\]]+)\](?!\(|:)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", fn($m) => self::Super("[$m[1]]", "#fn-$m[1]", ["id"=>$m[3]??null], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[4]??null)] : ["class" => "be $dir ".($m[4]??null)], $m[5]??[]), $object);
                    $object = preg_replace_callback("/\[~([^\]]+)\](?!\(:)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", fn($m) => self::Sub("[$m[1]]", "#fn-$m[1]", ["id"=>$m[3]??null], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[4]??null)] : ["class" => "be $dir ".($m[4]??null)], $m[5]??[]), $object);
                    $object = preg_replace_callback("/\s?^[ \t]*\[([\w\-]+)\]:\s*(.*)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?$/im", fn($m) => self::Division("[$m[1]] " . __($m[2]), ["class" => "footnote", "id" => $m[3]??"fn-$m[1]"], ($dir = Translate::GetDirection($m[2])) == \_::$Back->Translate->Direction ? ["class" => ($m[4]??null)] : ["class" => "be $dir ".($m[4]??null)], $m[5]??[]), $object);
                    $object = preg_replace_callback("/\s?^[ \t]*\[([\^~])([^\]]+)\]:\s*(.*)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?$/im", fn($m) => self::Division($m[1] . __($m[2]) . " " . __($m[3]), ["class" => "footnote", "id" => $m[3]??"fn-$m[2]"], ($dir = Translate::GetDirection($m[2])) == \_::$Back->Translate->Direction ? ["class" => ($m[4]??null)] : ["class" => "be $dir ".($m[4]??null)], $m[5]??[]), $object);

                    // Lists
                    $lc = 0;
                    do {
                        $object = preg_replace_callback(
                            "/((\s?^([ \t]*)([^\s\w#@!<\d+]|(\+|(\d+))\W?)[ \t]+(.*)$)+)/m",
                            function ($wholematches) {
                                $lines = preg_split("/\r?\n\n?\r?/", trim($wholematches[1], "\r\n"));
                                $linePattern = "/\s?^([ \t]*)([^\s\w#@!<\d+]|(\+|(\d+))\W?)[ \t]+(.*)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?$/mi";
                                preg_match($linePattern, reset($lines), $matches);
                                $linePattern = "/\s?^(" . $matches[1] . ")([^\s\w#@!<\d+]|(\+|(\d+))\W?)[ \t]+(.*)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?$/mi";
                                $ordered = empty($matches[3]) ? false : true;
                                $list = [];
                                $inlines = [];
                                foreach ($lines as $line) {
                                    if (preg_match($linePattern, $line, $ms)) {
                                        if ($inlines && $list) {
                                            $list[count($list) - 1] .= self::Convert(join("\n", $inlines));
                                            $inlines = [];
                                        }
                                        $list[] = self::Item($ms[5], ["id"=>($ms[6]??null)], empty($ms[4]) ? [] : ["number" => $ms[4]], ($dir = Translate::GetDirection($ms[5])) == \_::$Back->Translate->Direction ? ["class"=>($ms[7]??null)] : ["class" => "be $dir ".($ms[7]??null)], $ms[8]??[]);
                                    } else
                                        $inlines[] = $line;
                                }
                                if ($inlines && $list) {
                                    $list[count($list) - 1] .= self::Convert(join("\n", $inlines));
                                    $inlines = [];
                                }
                                return $ordered
                                    ? self::List(join("", $list), empty($matches[4]) ? [] : ["start" => $matches[4]])
                                    : self::Items(join("", $list));
                            },
                            $object,
                            -1,
                            $lc
                        );
                    } while ($lc);

                    // Texts
                    $object = preg_replace_callback("/\*\*(\S[^\*\r\n\v]+)\*\*(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", fn($m) => self::Strong($m[1], null, ["id"=>($m[2]??null)], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object);
                    $object = preg_replace_callback("/\*([^\*\r\n\v]+)\*(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", fn($m) => self::Bold($m[1], null, ["id"=>($m[2]??null)], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object);
                    $object = preg_replace_callback("/_?_([^\r\n\v]+)__?(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", fn($m) => self::Italic($m[1], null, ["id"=>($m[2]??null)], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object);
                    $object = preg_replace_callback("/~?~([^\r\n\v]+)~~?(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", fn($m) => self::Strike($m[1], null, ["id"=>($m[2]??null)], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object);
                    $object = preg_replace_callback("/(?<!\[)\^\(([^\)]+)\)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", fn($m) => self::Super($m[1], null, ["id"=>($m[2]??null)], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object); // Superscript
                    $object = preg_replace_callback("/(?<!\[)\~\(([^\)]+)\)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", fn($m) => self::Sub($m[1], null, ["id"=>($m[2]??null)], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object); // Superscript
                    $object = preg_replace_callback("/(?<!\[)\^([^\s\-+*\/\/\\\()\[\]{}$#@!~\"'`%^&=+]+)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", fn($m) => self::Super($m[1], null, ["id"=>($m[2]??null)], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object); // Superscript
                    $object = preg_replace_callback("/(?<!\[)~([^\s\-+*\/\/\\\()\[\]{}$#@!~\"'`%^&=+]+)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?/i", fn($m) => self::Sub($m[1], null, ["id"=>($m[2]??null)], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object); // Subscript

                    $object = encode($object, $dic, pattern: $tagPatt);// To keep all previous tags unchanged

                    // Links
                    $object = preg_replace_callback("/\b(?<![\"\'`])([a-z]{2,10}\:\/{2}[\/a-z_0-9\?\=\&\#\%\.\(\)\[\]\+\-\!\~\$]+)\b/i", fn($m) => self::Link($m[1], $m[1]), $object);
                    $object = preg_replace_callback("/\b(?<![\"\'`])([a-z_0-9.\-]+\@[a-z_0-9.\-]+)\b/i", fn($m) => self::Link($m[1], "mailto:{$m[1]}"), $object);

                    $object = preg_replace_callback("/\s?(^[^\W].*)(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?$/mi", fn($m) => self::Element($m[1], "p", ["id"=>($m[2]??null)], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? ["class" => ($m[3]??null)] : ["class" => "be $dir ".($m[3]??null)], $m[4]??[]), $object);

                    // Signs
                    $object = preg_replace("/\s?^\-{3,6}(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n}]))?([^\}]*)?\})?(?=<|$)/mi", Html::Element(null,"hr", ["id"=>"$1", "class"=>"$2"], "$3"), $object);
                    $object = preg_replace("/(\r\n)|(\n\r)|((?<!>))\r?\n\r?/", self::$Break, $object);

                    return ($dir = Translate::GetDirection($object)) == \_::$Back->Translate->Direction ? decode($object, $dic) : self::Division(decode($object, $dic), ["class" => "be $dir"]);
                }
            }
            if (is_subclass_of($object, "\Base"))
                return $object->ToString();
            if (is_countable($object) || is_iterable($object)) {
                if (!is_array($object))
                    $object = iterator_to_array($object);
                $texts = array();
                if (is_numeric(array_key_first($object))) {
                    foreach ($object as $val)
                        $texts[] = self::Item(self::Convert($val));
                    return self::List(join(PHP_EOL, $texts));
                } elseif ($args = getBetween($object, "Arguments", "Item")) {
                    $key = get($object, "Key");
                    $val = get($object, "Value");
                    $ops = get($object, "Options");
                    $type = get($object, "Type");
                    $title = get($object, "Title");
                    return self::Interactor(
                        key: $key,
                        value: $val,
                        type: $type,
                        title: $title,
                        options: $ops,
                        attributes: $args
                    );
                } else {
                    foreach ($object as $key => $val)
                        $texts[] = self::Item(self::Span($key . ": ") . self::Convert($val));
                    return self::Items(join(PHP_EOL, $texts));
                }
            }
            if (is_callable(value: $object) || $object instanceof \Closure)
                return self::Convert($object());
            return self::Division(Convert::ToString($object));
        }
        return "";
    }

    /**
     * Create standard html element
     * @param mixed $content The content of the Tag, send tagname to create single tag
     * @param string|null|array $tagName The HTML tag name, send attributes to create single tag, Or other custom attributes of the single Tag
     * @param array|string|null $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Element($content = null, string|array|null $tagName = null, ...$attributes)
    {
        $isSingle = false;
        if ($isSingle = is_null($content) && is_string($tagName)) {
            $content = null;
        } elseif ($isSingle = is_string($content) && (is_array($tagName) || (is_null($tagName) && empty($attributes)))) {
            $attributes = [$tagName, $attributes];
            $tagName = $content;
            $content = null;
        } elseif ($content === null)
            return null;
        $tagName = trim(strtolower($tagName));
        $allowMA = true;
        switch ($tagName) {
            case "label":
            case "thead":
            case "tbody":
            case "tr":
            case "th":
            case "td":
                $allowMA = false;
                break;
        }
        $attachments = "";
        $attrs = self::Attributes($attributes, $attachments, $prepends, $appends, $allowMA);
        if ($isSingle)
            return "$prepends<$tagName$attrs data-single/>$appends$attachments";
        else
            return join("", ["<$tagName$attrs>$prepends", Convert::ToString($content), "$appends</$tagName>$attachments"]);
    }
    public static function Attributes($attributes, &$attachments = "", &$prepends = "", &$appends = "", $optimization = false)
    {
        $attrs = "";
        if ($attributes) {
            if (is_countable($attributes) || is_iterable($attributes)) {
                $attrdic = [];
                $scripts = [];
                $id = null;
                foreach (Convert::ToIteration($attributes) as $key => $value) {
                    if (isEmpty($key) || is_int($key))
                        if (isEmpty($value))
                            continue;
                        else
                            $attrdic[$value] = null;
                    else {
                        $key = trim(strtolower($key));
                        //Detection
                        if ($key == "id")
                            $id = $value;
                        if (isset($attrdic[$key]))
                            switch ($key) {
                                case "style":
                                    $attrdic[$key] .= PHP_EOL . $value;
                                    break;
                                case "class":
                                    $attrdic[$key] .= " " . $value;
                                    break;
                                default:
                                    if (startsWith($key, "on")) {
                                        if (isValid($value)) {
                                            if (is_callable($value) || $value instanceof \Closure)
                                                $value = Internal::MakeScript($value);
                                            $attrdic[$key] .= PHP_EOL . $value;
                                        }
                                    } else
                                        $attrdic[$key] = $value;
                                    break;
                            }
                        else
                            $attrdic[$key] = $value;
                    }
                }
                //Standardization
                foreach ($attrdic as $key => $value)
                    if (!isEmpty($value))
                        switch ($key) {
                            case "max":
                                $attrdic["onchange"] = get($attrdic, "onchange") . PHP_EOL . "if(this.value > $value) this.value = $value;";
                                break;
                            case "min":
                                $attrdic["onchange"] = get($attrdic, "onchange") . PHP_EOL . "if(this.value < $value) this.value = $value;";
                                break;
                        }
                //Integration
                foreach ($attrdic as $key => $value)
                    switch ($key) {
                        case "tooltip":
                            $appends .= Html::Tooltip(__($value));
                            break;
                        case "style":
                            if (self::$AttributesOptimization && $optimization) {
                                if (!isValid($id)) {
                                    $id = "_" . getId(true);
                                    $attrs .= " id='$id'";
                                }
                                $attachments .= self::Style("#$id{{$value}}");
                            } else
                                $attrs .= " " . self::Attribute($key, $value);
                            break;
                        case "alt":
                        case "content":
                        case "text":
                        case "title":
                        case "description":
                        case "placeholder":
                            $attrs .= " " . self::Attribute($key, __($value));
                            break;
                        case "href":
                            if (get($attrdic, "rel"))
                                $attrs .= " " . self::Attribute($key, \MiMFa\Library\Local::GetUrl($value));
                            else
                                $attrs .= " " . self::Attribute($key, $value);
                            break;
                        case "src":
                            $attrs .= " " . self::Attribute($key, \MiMFa\Library\Local::GetUrl($value));
                            break;
                        default:
                            if (startsWith($key, "on")) {
                                if (isValid($value)) {
                                    if (is_callable($value) || $value instanceof \Closure)
                                        $value = Internal::MakeScript($value);
                                    $onpress = preg_find("/onpress/i", $key);
                                    if ($onpress || self::$AttributesOptimization && $optimization) {
                                        if (!isValid($id)) {
                                            $id = "_" . getId(true);
                                            $attrs .= " id='$id'";
                                        }
                                        if ($onpress)
                                            $scripts[] = "document.getElementById('$id').addEventListener('keyup', function(event) {if (event.key === '" . strToProper(str_replace($onpress, "", $key)) . "'){{$value}}});";
                                        else
                                            $scripts[] = "document.getElementById('$id').$key = function(event){{$value}};";
                                    } else
                                        $attrs .= " " . self::Attribute($key, $value);
                                }
                            } else
                                $attrs .= " " . self::Attribute($key, $value);
                            break;
                    }
                if (count($scripts) > 0)
                    $attachments .= self::Script($scripts);
            } else
                $attrs = Convert::ToString($attributes);
        }
        return $attrs;
    }
    public static function Attribute($key, $value = null): mixed
    {
        if (is_null($value)) {
            return $key;
        } else {
            $value = Convert::ToString($value);
            $sp = '"';
            if (str_contains($value, '"'))
                if (str_contains($value, "'")) {
                    $value = str_replace("'", "&apos;", $value);
                    $sp = "'";
                } else
                    $sp = "'";
            else
                $sp = '"';
            return "$key=$sp$value$sp";
        }
    }
    public static function HasAttribute($attributes, $key, $default = false): mixed
    {
        if (is_array($attributes)) {
            if ($res = has($attributes, $key))
                return $res;
            foreach ($attributes as $ke => $val)
                if (($res = self::HasAttribute($val, $key, $default)) && $res !== $default)
                    return $res;
        }
        return $default;
    }
    public static function GetAttribute($attributes, $key, $default = null): mixed
    {
        if (is_array($attributes)) {
            if ($res = get($attributes, $key))
                return $res;
            foreach ($attributes as $ke => $val)
                if (($res = self::GetAttribute($val, $key, $default)) && $res !== $default)
                    return $res;
        }
        return $default;
    }
    public static function GrabAttribute(&$attributes, $key, $default = null): mixed
    {
        if (is_array($attributes)) {
            if ($res = grab($attributes, $key))
                return $res;
            foreach ($attributes as $ke => $val)
                if (($res = self::GrabAttribute($val, $key, $default)) && $res !== $default) {
                    $attributes[$ke] = $val;
                    return $res;
                }
        }
        return $default;
    }
    public static function FilterAttributes(&$attributes, callable $filter, $default = []): mixed
    {

        if (is_array($attributes)) {
            if ($res = filter($attributes, $filter))
                return $res;
            foreach ($attributes as $ke => $val)
                if (($res = self::FilterAttributes($val, $filter, $default)) && $res !== $default) {
                    $attributes[$ke] = $val;
                    return $res;
                }
        }
        return $default;
    }
    public static function Documents($title, $content = null, $description = null, $sources = [], ...$attributes)
    {
        if ($content === null) {
            $content = $title;
            $title = null;
        }
        if ($content === null)
            return null;
        if (!is_array($content))
            return self::Document($title, $content, $description, $attributes, $sources);
        $c = count($content);
        if ($c == 0)
            return self::Document($title, null, $description, $sources, $attributes);
        if ($c == 1)
            return self::Document($title, $content[0], $description, $sources, $attributes);
        return self::Document($title, self::Embeds($content), $description, $sources, $attributes);
    }
    public static function Document($title, $content = null, $description = null, $sources = [], ...$attributes)
    {
        if ($content === null) {
            $content = $title;
            $title = null;
        }
        if ($content === null)
            return null;
        $head = join("\r\n", array_unique($sources));

        return "<!DOCTYPE HTML>" .
            self::Element(
                [
                    self::Element(
                        [
                            self::Title($title),
                            self::Element("meta", ["charset" => \_::$Back->Translate->Encoding]),
                            self::Element("meta", ["lang" => \_::$Back->Translate->Language]),
                            self::Description($description),
                            $head
                        ],
                        "head"
                    ),
                    self::Element($content ?? "", "body", ["class" => "document"], ...$attributes)
                ],
                "html"
            );
    }

    /**
     * Create standard html tag element
     * @param string|null $tagName The HTML tag name, send attributes to create single tag
     * @param mixed $content The content of the Tag, send false to create single tag
     * @param bool $content Send false to create single tag otherwise send your content of double tag
     * @param array|string|null $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Tag(string|array|null $tagName = null, $content = null, ...$attributes)
    {
        return $content === false ?
            self::Element($tagName, ...$attributes) :
            self::Element($content, $tagName, ...$attributes);
    }
    private static array $TagStack = [];
    /**
     * Create standard html open tag element
     * @param string|null $tagName The HTML tag name, send attributes to create single tag
     * @param array|string|null $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function OpenTag(string|array|null $tagName = null, ...$attributes)
    {
        $tagName = trim(strtolower($tagName));
        $allowMA = true;
        switch ($tagName) {
            case "label":
            case "thead":
            case "tbody":
            case "tr":
            case "th":
            case "td":
                $allowMA = false;
                break;
        }
        $attachments = "";
        $attrs = self::Attributes($attributes, $attachments, $prepends, $appends, $allowMA);
        self::$TagStack[] = $tagName;
        return "$attachments<$tagName$attrs>$prepends$appends";
    }
    /**
     * Create standard html close tag element
     * @param string|null $tagName The HTML tag name, send attributes to create single tag
     * @return string
     */
    public static function CloseTag(string|null $tagName = null)
    {
        if ($tagName === null)
            $tagName = array_pop(self::$TagStack);
        if ($tagName === null)
            return null;
        return "</$tagName>";
    }
    #endregion


    #region META
    /**
     * The \<TITLE\> HTML Tag
     * @param string|null $content The window title
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Title($content, ...$attributes)
    {
        return self::Element(__($content), "title", $attributes);
    }
    /**
     * The \<META\> HTML Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Description($content, ...$attributes)
    {
        return self::Meta("abstract", $content, ...$attributes) .
            self::Meta("description", $content, ...$attributes) .
            self::Meta("twitter:description", $content, ...$attributes);
    }
    /**
     * The \<LINK\> HTML Tag
     * @param string $name The type of the relation
     * @param string|null|array $source The source path of the relation
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Relation($name, $source, ...$attributes)
    {
        return self::Element("link", ["rel" => $name, "href" => $source], $attributes);
    }
    /**
     * The \<META\> HTML Tag
     * @param string $name The subject of the metadata
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Meta($name, $content, ...$attributes)
    {
        return self::Element("meta", ["name" => $name, "content" => $content], $attributes);
    }
    /**
     * The \<LINK\> icon HTML Tag
     * @param string|null|array $source The source path of window icon
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Logo($source, ...$attributes)
    {
        return self::Relation("icon", $source, $attributes);
    }
    /**
     * The \<META\> HTML Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Keywords($content, ...$attributes)
    {
        return self::Meta("keywords", is_array($content) ? join(", ", $content) : $content, $attributes);
    }
    public static function Script($content, $source = null, ...$attributes)
    {
        return self::Element($content ?? "", "script", is_null($source) ? ["type" => "text/javascript"] : ["src" => $source], $attributes);
    }
    public static function Style($content, $source = null, ...$attributes)
    {
        if (isValid($content))
            return self::Element($content ?? "", "style", $attributes);
        else
            return self::Relation("stylesheet", $source, $attributes);
    }
    #endregion


    #region NOTIFICATION
    public static function Result($content, $icon = "bell", $wait = 10000, ...$attributes)
    {
        $id = "_" . getId(true);
        return self::Element(
            Html::Icon($icon) . Html::Division(__($content, referring: true) . self::Tooltip("Double click to hide")) . Html::Icon("close", "document.getElementById('$id')?.remove()"),
            "div",
            [
                "id" => $id,
                "class" => "result $id",
                "ondblclick" => "this.style.display = 'none'",
                "onmouseenter" => "this.classList.remove('$id');",
            ],
            $attributes
        ) . self::Script("setTimeout(() => document.querySelector('#$id.$id')?.remove(), $wait);");
    }
    public static function Success($content, ...$attributes)
    {
        return self::Result($content, "circle-check", 10000, ["class" => "success"], $attributes);
    }
    public static function Warning($content, ...$attributes)
    {
        return self::Result($content, "triangle-exclamation", 10000, ["class" => "warning"], $attributes);
    }
    public static function Error($content, ...$attributes)
    {
        if (is_a($content, "Exception") || is_subclass_of($content, "Exception"))
            return self::Result($content->getMessage(), "circle-exclamation", 10000, ["class" => "error"], $attributes);
        else
            return self::Result($content, "circle-exclamation", 10000, ["class" => "error"], $attributes);
    }
    #endregion


    #region MEDIA
    /**
     * The \<VIDEO\> HTML Tag
     * @param mixed $content The alternative content of the Tag
     * @param mixed $source The source path to show
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Video($content, $source = null, ...$attributes)
    {
        if (!isValid($source)) {
            $source = $content;
            $content = null;
        }
        if (!isValid($source))
            return null;
        $srcs = [];
        if (is_array($source))
            foreach ($source as $key => $value)
                if (is_int($key))
                    $srcs[] = self::Element("Source", ["src" => $value]);
                else
                    $srcs[] = self::Element("Source", ["src" => $value, "type" => $key]);
        else
            $srcs[] = self::Element("Source", ["src" => $source]);
        return self::Element(join(PHP_EOL, $srcs) . __($content), "video", ["class" => "video"], $attributes);
    }
    /**
     * The \<AUDIO\> HTML Tag
     * @param mixed $content The alternative content of the Tag
     * @param array|string|null $source The source path to show
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Audio($content, $source = null, ...$attributes)
    {
        if (!isValid($source)) {
            $source = $content;
            $content = null;
        }
        if (!isValid($source))
            return null;
        $srcs = [];
        if (is_array($source))
            foreach ($source as $key => $value)
                if (is_int($key))
                    $srcs[] = self::Element("Source", ["src" => $value]);
                else
                    $srcs[] = self::Element("Source", ["src" => $value, "type" => $key]);
        else
            $srcs[] = self::Element("Source", ["src" => $source]);
        return self::Element(join(PHP_EOL, $srcs) . __($content), "audio", ["class" => "audio"], $attributes);
    }
    /**
     * The \<IMG\> or \<I\> HTML Tag
     * @param mixed $content The alternative content of the Tag
     * @param array|string|null $source The source path to show, Or other custom attributes of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Image($content, $source = null, ...$attributes)
    {
        if (!$source) {
            $source = $content;
            $content = null;
        } elseif (is_array($source)) {
            $attributes = Convert::ToIteration($source, ...$attributes);
            $source = $content;
            $content = null;
        }
        if (!$source)
            return null;
        if (startsWith($source, "icon", "fa", "fa-"))
            return self::Element("", "i", ["class" => "image " . strtolower($source)], $attributes) . ($content ? Html::Tooltip($content) : "");
        elseif (isIdentifier($source))
            return self::Element("", "i", ["class" => "image icon fa fa-" . strtolower($source)], $attributes) . ($content ? Html::Tooltip($content) : "");
        else {
            $srcs = [];
            $src = $source;
            if (is_array($source)) {
                $src = array_shift($source);
                foreach ($source as $key => $value)
                    if (is_int($key))
                        $srcs[] = self::Element("source", ["srcset" => $value]);
                    else
                        $srcs[] = self::Element("source", ["srcset" => $value, "media" => $key]);
            }
            $srcs[] = self::Element("img", ["src" => $src, "alt" => $content], self::FilterAttributes($attributes, fn($v, $k) => preg_match("/^((on\w+)|alt|src|id)$/i", $k)));
            return self::Element(join(PHP_EOL, $srcs), "picture", ["class" => "image"], $attributes);
        }
        //return self::Element("img", ["src" => $source, "alt" => __($content ?? ""), "class" => "image"], $attributes);
    }
    /**
     * The \<IMAGE\> or \<I\> HTML Tag
     * @param mixed $content The alternative content of the Tag
     * @param string|null|array $source The source path to show, Or other custom attributes of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Media($content, $source = null, ...$attributes)
    {
        if (!isValid($source)) {
            $source = $content;
            $content = null;
        } elseif (is_array($source)) {
            $attributes = Convert::ToIteration($source, ...$attributes);
            $source = $content;
            $content = null;
        }
        if (!isValid($source))
            return null;
        if (isIdentifier($source))
            return self::Icon($source, null, ["class" => "media"], $attributes);
        if (isUrl($source))
            return self::Image($content, $source, ["class" => "media"], $attributes);
        return self::Element(__($content ?? ""), "div", ["style" => "background-image: url('" . \MiMFa\Library\Local::GetUrl($source) . "');", "class" => "media"], ...$attributes);
    }
    /**
     * The \<IFRAME\> or \<EMBED\> HTML Tag
     * @param mixed $content The default content of the Tag
     * @param array $source other custom attributes of the Tag
     * @param string|null|array $source The source path or document to show
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Embed($content, $source = null, ...$attributes)
    {
        if (!isValid($source)) {
            $source = $content;
            $content = null;
        } elseif (is_array($source)) {
            $attributes = Convert::ToIteration($source, ...$attributes);
            $source = $content;
            $content = null;
        }
        if (isUrl($source))
            if (isFormat($source, ".svg"))
                return self::Element($content ?? "", "embed", ["src" => $source, "class" => "embed"], $attributes);
            else
                return self::Element($content ?? "", "iframe", ["src" => $source, "class" => "embed"], $attributes);
        return self::Element($content ?? $source ?? "", "embed", ["class" => "embed"], $attributes);
        //return self::Element($content ?? "", "iframe", ["srcdoc" => str_replace("\"", "&quot;", Convert::ToString($source)), "class" => "embed"], ...$attributes);
    }
    /**
     * The \<IFRAME\> HTML Tag devided in a page
     * @param array $sources The source pathes or documents to show
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Embeds(array $sources, ...$attributes)
    {
        library("Math");
        $c = count($sources);
        $p = Math::Slice($c, 100, 100, 20, 33);
        $ls = [];
        $atts = [
            "style" => "width:{$p['X']}vw;height:{$p['Y']}vh;",
            "marginwidth" => "0",
            "marginheight" => "0",
            "frameborder" => "0",
            "hspace" => "0",
            "vspace" => "0",
            "align" => "top",
        ];
        foreach ($sources as $item)
            $ls[] = self::Embed(null, $item, $atts, ...$attributes);
        return Convert::ToString($ls);
    }
    #endregion


    #region PARTITIONING
    /**
     * The \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Page($content, ...$attributes)
    {
        return self::Element($content, "div", ["class" => "page"], $attributes);
    }
    /**
     * The \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Part($content, ...$attributes)
    {
        return self::Element($content, "div", ["class" => "part"], $attributes);
    }
    /**
     * The \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @deprecated
     * @return string
     */
    public static function Panel($content, ...$attributes)
    {
        return self::Element($content, "div", ["class" => "panel", "style" => "display: inline-block; width: fit-content; height: fit-content;"], ...$attributes);
    }
    /**
     * The \<HEADER\> HTML Tag
     * @param mixed $content The content of the header Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Header($content, ...$attributes)
    {
        return self::Element($content, "header", ["class" => "header"], $attributes);
    }
    /**
     * The \<NAV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Nav($content, ...$attributes)
    {
        return self::Element($content, "nav", ["class" => "nav"], $attributes);
    }
    /**
     * The \<MAIN\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Content($content, ...$attributes)
    {
        return self::Element($content, "main", ["class" => "content"], $attributes);
    }
    /**
     * The \<SECTION\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Section($content, ...$attributes)
    {
        return self::Element($content, "section", ["class" => "section"], $attributes);
    }
    /**
     * The \<ASIDE\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Aside($content, ...$attributes)
    {
        return self::Element($content, "aside", ["class" => "aside"], $attributes);
    }
    /**
     * The \<FOOTER\> HTML Tag
     * @param mixed $content The content of the footer Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Footer($content, ...$attributes)
    {
        return self::Element($content, "footer", ["class" => "footer"], $attributes);
    }
    /**
     * The Container \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Container($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::Rack($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "container"], $attributes);
    }
    /**
     * The Container \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function LargeContainer($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::LargeRack($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "container large-container"], $attributes);
    }
    /**
     * The Container \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function MediumContainer($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::MediumRack($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "container medium-container"], $attributes);
    }
    /**
     * The Container \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function SmallContainer($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::SmallRack($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "container small-container"], $attributes);
    }
    /**
     * The Main Partitioner \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Frame($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::Rack($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "frame container-fluid"], $attributes);
    }
    /**
     * The Main Partitioner \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function LargeFrame($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::LargeRack($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "frame large-frame container-fluid"], $attributes);
    }
    /**
     * The Main Partitioner \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function MediumFrame($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::MediumRack($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "frame medium-frame container-fluid"], $attributes);
    }
    /**
     * The Main Partitioner \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function SmallFrame($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::SmallRack($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "frame small-frame container-fluid"], $attributes);
    }
    /**
     * The Row Partitioner \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Rack($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::Slot($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "rack row"], $attributes);
    }
    /**
     * The Row Partitioner \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function LargeRack($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::LargeSlot($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "rack large-rack row"], $attributes);
    }
    /**
     * The Row Partitioner \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function MediumRack($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::MediumSlot($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "rack medium-rack row"], $attributes);
    }
    /**
     * The Row Partitioner \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function SmallRack($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::SmallSlot($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "rack small-rack row"], $attributes);
    }
    /**
     * The Column Partitioner \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Slot($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::Slot($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "slot col"], $attributes);
    }
    /**
     * The Column Partitioner \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function LargeSlot($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::Slot($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "slot large-slot col-lg"], $attributes);
    }
    /**
     * The Column Partitioner \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function MediumSlot($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::Slot($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "slot medium-slot col-md"], $attributes);
    }
    /**
     * The Column Partitioner \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function SmallSlot($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $item)
                $res[] = self::Slot($item);
            $content = $res;
        }
        return self::Element($content, "div", ["class" => "slot small-slot col-sm"], $attributes);
    }

    /**
     * The \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Division($content, ...$attributes)
    {
        return self::Element($content ?? "", "div", ["class" => "division"], $attributes);
    }
    /**
     * The \<CENTER\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Center($content, ...$attributes)
    {
        return self::Element($content ?? "", "center", ["class" => "center"], $attributes);
    }
    /**
     * The \<PRE\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Pre($content, ...$attributes)
    {
        return self::Element($content ?? "", "pre", ["class" => "pre", "ondblclick" => 'copy(this.innerText)'], $attributes);
    }
    /**
     * The \<QOUTE\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Quote($content, ...$attributes)
    {
        return self::Element($content ?? "", "quote", ["class" => "quote", "ondblclick" => 'copy(this.innerText)'], $attributes);
    }
    /**
     * The \<BLOCKQOUTE\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function QuoteBlock($content, ...$attributes)
    {
        return self::Element($content ?? "", "blockquote", ["class" => "quoteblock", "ondblclick" => 'copy(this.innerText)'], $attributes);
    }
    /**
     * The \<CODE\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Code($content, ...$attributes)
    {
        return self::Element($content ?? "", "code", ["class" => "code", "ondblclick" => 'copy(this.innerText)'], $attributes);
    }
    /**
     * The \<BLOCKCODE\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function CodeBlock($content, ...$attributes)
    {
        return self::Element(self::Element($content ?? "", "blockcode", ["ondblclick" => 'copy(this.innerText)']), "pre", ["class" => "codeblock"], $attributes);
    }
    #endregion


    #region COLLECTING
    /**
     * The Unordered List \<DL\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Collection($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $k => $item)
                if (is_numeric($k))
                    $res[] = $item;
                else
                    $res[] = self::Element(__($k), "dt") . self::Element(__($item), "dd");
            $content = $res;
            return self::Element($content, "dl", ["class" => "collection"], $attributes);
        }
        return self::Element(__($content), "dl", ["class" => "collection"], $attributes);
    }
    /**
     * The Ordered List \<OL\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function List($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $k => $item)
                //$res[] = self::Item($item);
                $res[] = self::Element((is_numeric($k) ? "" : self::InlineHeading($k)) . __($item), "li", ["class" => "item"]);
            $content = $res;
            return self::Element($content, "ol", ["class" => "list"], $attributes);
        }
        return self::Element(__($content), "ol", ["class" => "list"], $attributes);
    }
    /**
     * The Unordered List \<UL\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Items($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $k => $item)
                //$res[] = self::Item($item);
                $res[] = self::Element((is_numeric($k) ? "" : self::InlineHeading($k)) . __($item), "li", ["class" => "item"]);
            $content = $res;
            return self::Element($content, "ul", ["class" => "items"], $attributes);
        }
        return self::Element(__($content), "ul", ["class" => "items"], $attributes);
    }
    /**
     * The List Item \<LI\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Item($content, ...$attributes)
    {
        return self::Element(__($content), "li", ["class" => "item"], $attributes);
    }

    /**
     * The \<TABLE\> HTML Tag
     * @param mixed $content The rows array or table content
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Table($content = "", $options = [], ...$attributes)
    {
        $rowHeaders = grab($options, "RowHeaders") ?? [intval(grab($options, "RowHeader") ?? 0)];
        $colHeaders = grab($options, "ColHeaders") ?? [intval(grab($options, "ColHeader") ?? 0)];
        return self::Element(
            is_countable($content) ? join(PHP_EOL, iterator_to_array((function () use ($content, $rowHeaders, $colHeaders) {
                foreach ($content as $k => $v) {
                    if (in_array($k, $rowHeaders))
                        yield self::Column($v);
                    else
                        yield self::Row($v, ["Type" => in_array($k, $colHeaders) ? "head" : "cell"]);
                }
            })())) : $content ?? "",
            "table",
            $options,
            ["class" => "table"],
            $attributes
        );
    }
    /**
     * The \<THEAD\> HTML Tag
     * @param mixed $content The column labels array or content
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Column($content = "", ...$attributes)
    {
        return self::Element(
            is_countable($content) ? join(PHP_EOL, iterator_to_array((function () use ($content) {
                yield self::Row($content, ["type" => "head"]);
            })())) : __($content ?? ""),
            "thead",
            ["class" => "table-column"],
            $attributes
        );
    }
    /**
     * The \<TR\> HTML Tag
     * @param mixed $content The row array or content
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Row($content = "", $options = [], ...$attributes)
    {
        $head = grab($options, "Type");
        return self::Element(
            is_countable($content) ? join(PHP_EOL, iterator_to_array((function () use ($content, $head) {
                foreach ($content as $k => $v)
                    yield self::Cell($v, ["Type" => $head]);
            })())) : $content ?? "",
            "tr",
            $options,
            ["class" => "table-row"],
            $attributes
        );
    }
    /**
     * The \<TD\> or \<TH\> HTML Tag
     * @param mixed $content The cell array or content
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Cell($content = "", $options = [], ...$attributes)
    {
        if (strtolower(grab($options, "Type") ?? "") === "head")
            return self::Element(__($content ?? ""), "th", $options, ["class" => "table-cell"], $attributes);
        else
            return self::Element(__($content ?? "", referring: true), "td", $options, ["class" => "table-cell"], $attributes);
    }
    #endregion


    #region HEADING
    /**
     * The \<H1\> HTML Tag
     * @param mixed $content The heading text
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function ExternalHeading($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h1", ["class" => "externalheading"], $attributes);
        return self::Element(__($content), "h1", ["class" => "heading externalheading"], $attributes);
    }
    /**
     * The \<H2\> HTML Tag
     * @param mixed $content The heading text
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function SuperHeading($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h2", ["class" => "superheading"], $attributes);
        return self::Element(__($content), "h2", ["class" => "heading superheading"], $attributes);
    }
    /**
     * The \<H3\> HTML Tag
     * @param mixed $content The heading text
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Heading($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h3", ["class" => "heading"], $attributes);
        return self::Element(__($content), "h3", ["class" => "heading"], $attributes);
    }
    /**
     * The \<H4\> HTML Tag
     * @param mixed $content The heading text
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function SubHeading($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h4", ["class" => "subheading"], $attributes);
        return self::Element(__($content), "h4", ["class" => "heading subheading"], $attributes);
    }
    /**
     * The \<H5\> HTML Tag
     * @param mixed $content The heading text
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function InternalHeading($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h5", ["class" => "internalheading"], $attributes);
        return self::Element(__($content), "h5", ["class" => "heading internalheading"], $attributes);
    }
    /**
     * The \<H6\> HTML Tag
     * @param mixed $content The heading text
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function InlineHeading($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h6", ["class" => "inlineheading"], $attributes);
        return self::Element(__($content), "h6", ["class" => "heading inlineheading"], $attributes);
    }
    #endregion


    #region TEXTS
    /**
     * The \<HR\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array|callable $reference The hyper destination tag id, (Use class names with full namespaces in callable references)
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function BreakLine($content, $reference = null, ...$attributes)
    {
        if (is_null($content))
            return self::Element("hr", ["class" => "break"], $attributes);
        $attr = [
            "class" => "break",
            "style" =>
                "display:flex;
                flex-direction: column;
                flex-wrap: nowrap;
                justify-content: center;
                align-content: center;
                text-align: center;
                align-items: center;"
        ];
        $btnattr = [
            "style" =>
                "background-color: var(--back-color);
                font-size: 75%;
                padding: calc(var(--size-0) * .5);
                margin: 0px;
                aspect-ratio: 1;
                border: var(--border-1) #8883;
                border-radius: var(--radius-5);
                box-shadow: var(--shadow-2);
                z-index:0;"
        ];
        $hr = self::Element("hr", ["style" => "width: 100%; margin-bottom: calc(-" . (strlen($content)) . " * 0.5 * 0.75 * var(--size-0));"]);
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element($hr . self::Button($content, $reference, $btnattr), "div", $attr, $attributes);
        return self::Element($hr . self::Span($content, null, $btnattr), "div", $attr, $attributes);
    }
    /**
     * The \<P\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Paragraph($content, ...$attributes)
    {
        return self::Element(__($content, styling: true, referring: true), "p", ["class" => "paragraph"], $attributes);
    }
    /**
     * The \<Data\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag,
     * "Splitter"=>".",
     * "ThousandSplitter"=>",",
     * @return string
     */
    public static function Number($content, ...$attributes)
    {
        $content = $content + 0;
        $decimalPoint = self::GrabAttribute($attributes, "Splitter") ?? ".";
        $thousandSep = self::GrabAttribute($attributes, "ThousandSplitter") ?? ",";
        $decimals = is_float($content) ? strlen(substr(strrchr($content, "."), 1)) : 0;
        return self::Element(number_format($content, $decimals, $decimalPoint, $thousandSep), "data", ["class" => "number", "value" => $content], $attributes);
    }
    /**
     * The \<SPAN\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Span($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "span", ["class" => "span"], $attributes);
        return self::Element(__($content), "span", ["class" => "span"], $attributes);
    }
    /**
     * The \<STRONG\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Strong($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "strong", ["class" => "strong"], $attributes);
        return self::Element(__($content), "strong", ["class" => "strong"], $attributes);
    }
    /**
     * The \<B\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Bold($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "b", ["class" => "bold"], $attributes);
        return self::Element(__($content), "b", ["class" => "bold"], $attributes);
    }
    /**
     * The \<BIG\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Big($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "big", ["class" => "big"], $attributes);
        return self::Element(__($content), "big", ["class" => "big"], $attributes);
    }
    /**
     * The \<i\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Italic($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "i", ["class" => "italic"], $attributes);
        return self::Element(__($content), "i", ["class" => "italic"], $attributes);
    }
    /**
     * The \<STRIKE\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Strike($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "strike", ["class" => "strike"], $attributes);
        return self::Element(__($content), "strike", ["class" => "strike"], $attributes);
    }
    /**
     * The \<SMALL\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Small($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "small", ["class" => "small"], $attributes);
        return self::Element(__($content), "small", ["class" => "small"], $attributes);
    }
    /**
     * The \<sup\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Super($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "sup", ["class" => "super"], $attributes);
        return self::Element(__($content), "sup", ["class" => "super"], $attributes);
    }
    /**
     * The \<sub\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Sub($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "sub", ["class" => "sub"], $attributes);
        return self::Element(__($content), "sub", ["class" => "sub"], $attributes);
    }

    /**
     * The \<LABEL\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array $reference The hyper destination tag id
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Label($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } elseif (isIdentifier($reference))
                return self::Element(__($content), "label", ["class" => "label", "for" => $reference], $attributes);
            else
                return self::Element(__($content) . __($reference), "label", ["class" => "label"], $attributes);
        return self::Element(__($content), "label", ["class" => "label"], $attributes);
    }
    /**
     * The \<SPAN\> HTML Tag
     * @param mixed $content Main content of tag tooltip
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Tooltip($content, ...$attributes)
    {
        return self::Element(__($content), "div", ["class" => "tooltip"], $attributes);
    }
    /**
     * The \<DATA\> HTML Tag
     * @param mixed $content Main content of tag
     * @param mixed $value A machine readable value
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Data($content, $value, ...$attributes)
    {
        return self::Element(__($content), "data", ["class" => "data", "value" => $value], $attributes);
    }
    #endregion


    #region OUTPUT
    /**
     * The \<A\> HTML Tag
     * @param mixed $content The anchor text of the Tag
     * @param string|null|array $reference The hyper reference path
     * @param array $source Other custom attributes of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Link($content, $reference = null, ...$attributes)
    {
        if (is_null($reference)) {
            $reference = $content;
            $content = null;
        } elseif (is_array($reference)) {
            $attributes = Convert::ToIteration($reference, ...$attributes);
            $reference = $content;
            $content = null;
        }
        if (is_null($content))
            $content = getDomain($reference);
        return self::Element(__($content), "a", ["href" => $reference, "class" => "link"], $attributes);
    }
    /**
     * The \<BUTTON\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array|callable $action The source path or onclick event script, (Use class names with full namespaces in callable references)
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Button($content, $action = null, ...$attributes)
    {
        if (!isEmpty($action) && (!isScript($action) && isUrl($action)))
            $action = "load(" . Script::Convert($action) . ")";
        return self::Element(__($content), "button", ["class" => "button", "type" => "button"], $action ? ["onclick" => $action] : [], $attributes);
    }
    /**
     * The \<BUTTON\> or \<A\> HTML Tag
     * @param mixed $content The source icon image or the regular name
     * @param string|null|array|callable $action The source path or onclick event script, (Use class names with full namespaces in callable references)
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Icon($content, $action = null, ...$attributes)
    {
        if (!isValid($content))
            return null;
        if (isEmpty($action))
            return self::Element("", "i", ["class" => "icon fa fa-" . strtolower($content)], $attributes);
        return self::Action(self::Icon($content, null), $action, ["class" => "icon"], $attributes);
    }
    /**
     * The \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array|callable $action The source path or onclick event script, (Use class names with full namespaces in callable references)
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Action($content, $action = null, ...$attributes)
    {
        if (!isEmpty($action) && (!isScript($action) && isUrl($action)))
            $action = "load(" . Script::Convert($action) . ")";
        return self::Element($content, "div", ["class" => "action", "type" => "action"], $action ? ["onclick" => $action] : [], $attributes);
    }
    #endregion


    #region INPUT
    /**
     * The \<FORM\> HTML Tag (the default method is GET)
     * @param mixed $content The form fields
     * @param string|null|array|callable $action The action reference path
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function Form($content, $action = null, ...$attributes)
    {
        if (!isValid($content))
            $content = self::SubmitButton();
        elseif (is_array($content) || is_iterable($content))
            $content = function () use ($content) {
                return join(PHP_EOL, loop($content, function ($f, $k) {
                    if (is_int($k))
                        if (is_array($f))
                            return self::Field(
                                type: grab($f, "Type"),
                                key: grab($f, "Key"),
                                value: grab($f, "Value"),
                                description: grab($f, "Description"),
                                options: grab($f, "Option"),
                                title: grab($f, "Title"),
                                wrapper: grab($f, "Wrapper") ?? [],
                                attributes: [...(grab($f, "Attributes") ?? []), ...$f]
                            );
                        elseif (is_string($f))
                            return $f;
                        else
                            return self::Field($f);
                    else
                        return self::Field(null, $k, $f);
                }));
            };
        return self::Element($content, "form", $action ? ((isScript($action) || !isUrl($action)) ? ["onsubmit" => $action] : ["action" => $action]) : [], ["enctype" => "multipart/form-data", "method" => "get", "class" => "form"], $attributes);
    }
    /**
     * Detect the type of inputed value
     * @param mixed $type The suggestion type of value
     * @param mixed $value The sample value
     * @return string
     */
    public static function InputDetector($type = null, $value = null)
    {
        if ($type === false)
            return null;
        if (is_null($type))
            if (isEmpty($value))
                return "text";
            elseif (is_string($value))
                if (isUrl($value))
                    if (isFile($value))
                        return "file";
                    else
                        return "url";
                elseif (strlen($value) > 100 || count(explode("\r\n\t\f\v", $value)) > 1)
                    return "textarea";
                else
                    return "text";
            else
                return strtolower(gettype($value));
        elseif (is_string($type))
            return strtolower($type);
        elseif (is_callable($type) || ($type instanceof \Closure))
            return self::InputDetector($type($type, $value), $value);
        elseif (is_object($type) || ($type instanceof \stdClass))
            return self::InputDetector(getValid($type, "Type", null), $value);
        elseif (is_countable($type))
            return "select";
        elseif ($type === true)
            return "text";
        elseif (is_int($type))
            return "number";
        else
            return $type;
    }
    /**
     * The \<LABEL\> and any input HTML Tag
     * @param object|string|array|callable|\Closure|\stdClass|null $type Can be a datatype or an input type
     * @param mixed $key The default key and the name of the field
     * @param mixed $value The default value of the field
     * @param mixed $description The more detaled text about the field
     * @param array|iterable|bool|string|null $options The other options of the field
     * @param array|string|null $attributes Other important attributes of the field
     * @param mixed $title The label text of the field
     * @return string
     */
    public static function Field($type = null, $key = null, $value = null, $description = null, $title = null, $wrapper = true, $options = null, ...$attributes)
    {
        if (is_array($description)) {
            $attributes = Convert::ToIteration($description, ...$attributes);
            $description = null;
        }
        if ($type === false)
            return null;
        if (!is_null($type)) {
            if (
                !(is_callable($type) || ($type instanceof \Closure)) &&
                (is_object($type) || ($type instanceof \stdClass))
            ) {
                $description = get($type, "Description") ?? $description;
                $wrapper = get($type, "Wrapper") ?? $wrapper;
            } elseif (is_countable($type) && !is_null($options)) {
                $description = get($type, "Description") ?? $description;
                $wrapper = get($type, "Wrapper") ?? $wrapper;
            }
        }
        $content = self::Interactor(
            key: $key,
            value: $value,
            type: $type,
            options: $options,
            title: $title,
            attributes: $attributes
        );
        if (is_null($content))
            return null;
        // $isRequired = $isDisabled = false;
        // if (!is_null($attributes))
        //     if (is_string($attributes)) {
        //         $isRequired = preg_match("/\brequired\b/i", ...$attributes);
        //         $isDisabled = preg_match("/\bdisabled\b/i", ...$attributes);
        //     } elseif (is_countable($attributes))
        //         foreach ($attributes as $k => $v) {
        //             $a = is_string($k) ? $k : (is_string($v) ? $v : "");
        //             $isRequired = $isRequired || preg_match("/\brequired\b/i", $a);
        //             $isDisabled = $isDisabled || preg_match("/\bdisabled\b/i", $a);
        //             if ($isRequired && $isDisabled)
        //                 break;
        //         }
        $isRequired = self::HasAttribute($attributes, "required");
        // $isDisabled = self::HasAttribute($attributes, "disabled");
        // if ($isDisabled)
        //     $content = Html::Division($value, ["class" => "input"], $attributes);
        $id = self::GetAttribute($attributes, "Id") ?? Convert::ToId($key);
        $titleOrKey = $title ?? Convert::ToTitle(Convert::ToString($key));
        $titleTag = ($title === false || !isValid($titleOrKey) ? "" : self::Label(__($titleOrKey) . ($isRequired ? self::Span("*", null, ["class" => "required"]) : ""), $id, ["class" => "title"]));
        $descriptionTag = ($description === false || !isValid($description) ? "" : self::Label($description, $id, ["class" => "description"]));
        $wrapperAttr = self::GrabAttribute($attributes, "WrapperAttributes") ?? [];
        switch ($type) {
            case null:
            case false:
            case 'null':
            case 'false':
                return null;
            case 'hidden':
            case 'hide':
                $titleTag = $descriptionTag = null;
                break;
        }
        if ($wrapper)
            return self::Element(join('', [$titleTag, $content, $descriptionTag]), 'div', ['class' => 'field'], $wrapperAttr);
        else
            return join('', [$titleTag, $content, $descriptionTag]);
    }
    /**
     * The any type of input HTML Tag
     * @param object|string|array|callable|\Closure|\stdClass|null $type Can be a datatype or an input type
     * @param mixed $key The default key and the name of the field
     * @param mixed $value The default value of the field
     * @param array|iterable|bool|string|null $options The other options of the field
     * @param array|string|null $attributes Other important attributes of the field
     * @return string
     */
    public static function Interactor(&$key = null, &$value = null, &$type = null, &$title = null, &$options = null, &$attributes = [])
    {
        if ($type === false)
            return null;
        $prepend = $append = null;
        if (is_null($type))
            $type = self::InputDetector($type, $value);
        if (is_string($type)) {
            if (isPattern($type)) {
                $options = $type;
                $type = "mask";
            } else
                $type = self::InputDetector($type, $value);
        } elseif (is_callable($type) || ($type instanceof \Closure)) {
            $type = $type($type, $value);
            return self::Interactor(
                key: $key,
                value: $value,
                type: $type,
                options: $options,
                title: $title,
                attributes: $attributes
            );
        } elseif (is_object($type) || ($type instanceof \stdClass)) {
            $key = get($type, "Key") ?? $key;
            $value = get($type, "Value") ?? $value;
            $title = get($type, "Title") ?? $title;
            $options = get($type, "Options") ?? $options;
            $prepend = get($type, "Prepend") ?? $prepend;
            $append = get($type, "Append") ?? $append;
            $attributes = [...$attributes, ...(getValid($type, "Attributes", []))];
            $type = self::InputDetector(get($type, "Type"), $value);
        } elseif (is_countable($type)) {
            if (is_null($options)) {
                $options = $type;
                $type = "select";
            } else {
                $key = get($type, "Key") ?? $key;
                $value = get($type, "Value") ?? $value;
                $options = get($type, "Options") ?? $options;
                $prepend = get($type, "Prepend") ?? $prepend;
                $append = get($type, "Append") ?? $append;
                $attributes = [...$attributes, ...(getValid($type, "Attributes", []))];
                $type = self::InputDetector(get($type, "Type"), $value);
            }
        } elseif (is_int($type)) {
            $options = [10 ** ($type - 1), 10 ** $type - 1];
            $type = "number";
        } elseif (is_string($type) && isPattern($type)) {
            $options = $type;
            $type = "mask";
        } else
            $type = self::InputDetector($type, $value);
        if (!is_null($type) && is_string($type)) {
            if (preg_match("/^\s*((\{[\w\W]*\})|(\[[\w\W]*\]))\s*$/", $type)) {
                try {
                    $types = Convert::FromJson($type);
                    return join(
                        '',
                        loop(
                            $types,
                            function ($t) use (&$key, &$value, &$options, &$title, &$attributes) {
                                return self::Interactor(
                                    key: $key,
                                    value: $value,
                                    type: $t,
                                    options: $options,
                                    title: $title,
                                    attributes: $attributes
                                );
                            }
                        )
                    );
                } catch (\Exception $ex) {
                    $type = "text";
                }
            }
            $mt = preg_find("/(?<=[\w\W]\<)[\w\W]+(?=\>$)/i", trim($type), null);
            $pos = strpos($type, "<");
            if (!isEmpty($mt)) {
                $options = ["Type" => $mt, ...($options ?? [])];
                $type = $pos > 0 ? first(str_split($type, $pos)) : "";
                return self::Interactor(
                    key: $key,
                    value: $value,
                    type: $type,
                    options: $options,
                    title: $title,
                    attributes: $attributes
                );
            }
            if (($pos = strpos($type, "|")) > 0) {
                $type = first(str_split($type, $pos));
                return self::Interactor(
                    key: $key,
                    value: $value,
                    type: $type,
                    options: $options,
                    title: $title,
                    attributes: $attributes
                );
            }
        }
        $titleOrKey = $title ?? Convert::ToTitle(Convert::ToString($key));
        $key = Convert::ToKey(Convert::ToString($key ?? $title));
        $id = self::GrabAttribute($attributes, "Id") ?? Convert::ToId($key);
        $attributes = ["id" => $id, "name" => $key, ...$attributes];
        $options = $options ?? [];
        switch ($type) {
            case null:
            case false:
            case 'null':
            case 'false':
                return null;
            case 'span':
                $content = self::Span($value ?? $titleOrKey, null, $attributes);
                break;
            case 'div':
            case 'division':
                $content = self::Division($value ?? $titleOrKey, null, $attributes);
                break;
            case 'a':
            case 'link':
            case 'hyperlink':
                $content = self::Link($titleOrKey, $value, $attributes);
                break;
            case 'action':
                $content = self::Action($titleOrKey, $value, $attributes);
                break;
            case 'p':
            case 'paragraph':
                $content = self::Paragraph($value ?? $titleOrKey, null, $attributes);
                break;
            case 'disable':
            case 'disabled':
                $content = self::DisabledInput($key, $value, $attributes);
                break;
            case 'label':
            case 'key':
            case 'title':
            case 'description':
                $content = self::Label($value ?? $titleOrKey, $id, $attributes);
                break;
            case 'object':
                $content = self::ObjectInput($key, Convert::ToString($value), $attributes);
                break;
            case 'countable':
            case 'iterable':
            case 'array':
            case 'collection'://A collection of Base based objects
                $content = self::CollectionInput($key, $value, $options, $attributes);
                break;
            case 'longtext':
            case 'content':
                $content = self::ContentInput($key, $value, $attributes);
                break;
            case 'size':
            case 'font':
            case 'mixed':

            case 'line':
            case 'value':
            case 'string':
            case 'text':
            case 'singleline':
            case 'shorttext':
            case 'tinytext':
            case 'varchar':
            case 'char':
                $content = self::TextInput($key, $value, $attributes);
                break;
            case 'address':

            case 'lines':
            case 'texts':
            case 'mediumtext':
            case 'strings':
            case 'multiline':
            case 'textarea':
                $content = self::TextsInput($key, $value, $attributes);
                break;
            case 'type':
            case 'enum':
            case 'dropdown':
            case 'combobox':
            case 'select':
                $content = self::SelectInput($key, $value, $options, $attributes);
                break;
            case 'types':
            case 'multiple':
            case 'enums':
            case 'selects':
                $content = self::SelectsInput($key, $value, $options, ["multiple" => null], $attributes);
                break;
            case 'choice':
            case 'choicebox':
            case 'choicebutton':
            case 'radio':
            case 'radiobox':
            case 'radiobutton':
                $content = self::RadioInput($key, $value, $attributes);
                break;
            case 'multiplechoice':
            case 'multiplechoicebox':
            case 'multiplechoicebutton':
            case 'radios':
            case 'radioes':
            case 'radioboxes':
            case 'radiobuttons':
                $content = self::RadiosInput($key, $value, $options, $attributes);
                break;
            case '1':
            case '0':
            case 'bool':
            case 'boolean':
            case 'check':
            case 'checkbox':
            case 'checkbutton':
                $content = self::CheckInput($key, $value, $attributes);
                break;
            case 'bools':
            case 'booleans':
            case 'checks':
            case 'checkboxes':
            case 'checkbuttons':
                $content = self::ChecksInput($key, $value, $options, $attributes);
                break;
            case 'int':
            case 'integer':
                $min = PHP_INT_MIN;
                $max = PHP_INT_MAX;
                if (is_array($options) && count($options)) {
                    $min = min($options);
                    $max = max($options);
                }
                $content = self::NumberInput($key, $value, ['min' => $min, 'max' => $max], $attributes);
                break;
            case 'short':
                $min = -255;
                $max = +255;
                if (is_array($options) && count($options)) {
                    $min = min($options);
                    $max = max($options);
                }
                $content = self::NumberInput($key, $value, ['min' => $min, 'max' => $max], $attributes);
                break;
            case 'number':
            case 'long':
                $min = null;
                $max = null;
                if (is_array($options) && count($options)) {
                    $min = min($options);
                    $max = max($options);
                }
                $content = self::NumberInput($key, $value, ['min' => $min, 'max' => $max], $attributes);
                break;
            case 'range':
                $min = 0;
                $max = 100;
                if (is_array($options) && count($options)) {
                    $min = min($options);
                    $max = max($options);
                }
                $content = self::RangeInput($key, $value, $min, $max, $attributes);
                break;
            case 'float':
            case 'double':
            case 'decimal':
                $min = PHP_FLOAT_MIN;
                $max = PHP_FLOAT_MAX;
                if (is_array($options) && count($options)) {
                    $min = min($options);
                    $max = max($options);
                }
                $content = self::FloatInput($key, $value, ['min' => $min, 'max' => $max], $attributes);
                break;
            case 'phone':
            case 'tel':
            case 'telephone':
                $content = self::TelInput($key, $value, $attributes);
                break;
            case 'mask':
                $content = self::MaskInput($key, $value, $options, $attributes);
                break;
            case 'url':
                $content = self::UrlInput($key, $value, $attributes);
                break;
            case 'map':
            case 'location':
                $content = self::TextInput($key, $value = Convert::ToString($value, ","), $attributes);
            case 'path':
                $content = self::TextInput($key, $value, $attributes);
                break;
            case 'calendar':
            case 'calendarinput':
            case 'datetime-local':
            case 'cal':
                $content = self::CalendarInput($key, $value, $attributes);
                break;
            case 'datetime':
                $content = self::DateTimeInput($key, $value, $attributes);
                break;
            case 'date':
                $content = self::DateInput($key, $value, $attributes);
                break;
            case 'time':
                $content = self::TimeInput($key, $value, $attributes);
                break;
            case 'week':
                $content = self::WeekInput($key, $value, $attributes);
                break;
            case 'month':
                $content = self::MonthInput($key, $value, $attributes);
                break;
            case 'hidden':
            case 'hide':
                $content = self::HiddenInput($key, $value, $attributes);
                break;
            case 'secret':
            case 'pass':
            case 'password':
                $content = self::SecretInput($key, $value, $attributes);
                break;
            case 'doc':
            case 'document':
            case 'image':
            case 'audio':
            case 'video':
            case 'file':
                $content = self::FileInput($key, $value, $attributes);
                break;
            case 'docs':
            case 'documents':
            case 'images':
            case 'audios':
            case 'videos':
            case 'files':
                $content = self::FilesInput($key, $value, $attributes);
                break;
            case 'dir':
            case 'directory':
            case 'folder':
                $content = self::DirectoryInput($key, $value, $attributes);
                break;
            case 'submitbutton':
            case 'submit':
                $content = self::SubmitButton($key, $value, $attributes);
                break;
            case 'resetbutton':
            case 'reset':
                $content = self::ResetButton($key, $value, $attributes);
                break;
            case 'imagesubmit':
            case 'imgsubmit':
                $content = self::Input($key, $title, 'image', ['src' => Convert::ToString($value)], $attributes);
                break;
            case 'code':
                $content = self::CodeInput($key, $value, $options, $attributes);
                break;
            case 'json':
            case 'javascript':
            case 'js':
            case 'html':
            case 'css':
            case 'codes':
                $content = self::ScriptInput($key, $value, $attributes);
                break;
            case 'mail':
            case 'email':
                $content = self::EmailInput($key, $value, $attributes);
                break;
            case 'color':
                $content = self::ColorInput($key, $value, $attributes);
                break;
            case 'search':
                $content = self::SearchInput($key, $value, $attributes);
                break;
            default:
                if (is_string($type))
                    $content = self::Element($value, $type, $attributes);
                else
                    $content = self::Input($key, $value, $type, $attributes);
                break;
        }
        if (is_null($prepend) && is_null($content) && is_null($append))
            return null;
        return join('', [Convert::By($prepend, $type, $value), $content, Convert::By($append, $type, $value)]);
    }
    /**
     * The \<BUTTON\ TYPE="SUBMIT"> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function SubmitButton($key = "Submit", $value = null, ...$attributes)
    {
        if (is_array($value)) {
            $attributes = Convert::ToIteration($value, ...$attributes);
            $value = null;
        }
        return self::Element(__($value ?? $key), "button", ["id" => self::GrabAttribute($attributes, "Id") ?? Convert::ToId($key), "name" => Convert::ToKey($key), "class" => "button submitbutton", "type" => "submit"], $attributes);
    }
    /**
     * The \<BUTTON\ TYPE="RESET"> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ResetButton($key = "Reset", $value = null, ...$attributes)
    {
        if (is_array($value)) {
            $attributes = Convert::ToIteration($value, ...$attributes);
            $value = null;
        }
        return self::Element(__($value ?? $key), "button", ["id" => self::GrabAttribute($attributes, "Id") ?? Convert::ToId($key), "name" => Convert::ToKey($key), "class" => "button resetbutton", "type" => "reset"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function Input($key, $value = null, $type = null, ...$attributes)
    {
        if (is_array($type)) {
            $attributes = Convert::ToIteration($type, ...$attributes);
            $type = "text";
        }
        return self::Element("input", ["id" => self::GrabAttribute($attributes, "Id") ?? Convert::ToId($key), "name" => self::GrabAttribute($attributes, "name") ?? Convert::ToKey($key), "placeholder" => self::GrabAttribute($attributes, "placeholder") ?? Convert::ToTitle($key), "type" => $type, "value" => $value, "class" => "input"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function TextInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "text", ["class" => "valueinput"], $attributes);
    }
    /**
     * The \<TEXTAREA\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function TextsInput($key, $value = null, ...$attributes)
    {
        return self::Element($value ?? "", "textarea", ["id" => self::GrabAttribute($attributes, "Id") ?? Convert::ToId($key), "name" => Convert::ToKey($key), "placeholder" => Convert::ToTitle($key), "class" => "input textinput"], $attributes);
    }
    /**
     * The \<TEXTAREA\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ContentInput($key, $value = null, ...$attributes)
    {
        $eid = self::GrabAttribute($attributes, "Id") ?? Convert::ToId($key);
        $sid = "_" . getId();
        return self::Tabs(
            [
                self::Icon("edit") =>
                    self::Element($value ?? "", "textarea", [
                        "id" => $eid,
                        "name" => Convert::ToKey($key),
                        "placeholder" => Convert::ToTitle($key),
                        "class" => "input contentinput",
                        "rows" => "20",
                        // "role"=>"textbox", "contenteditable"=>"true",
                        // "aria-label"=>"comment-box", "aria-multiline"=>"true", "aria-readonly"=>"false",
                        "style" => "font-size: 75%; overflow:scroll; word-wrap: unset;"
                    ], ...$attributes),
                self::Icon(
                    "eye",
                    Internal::MakeScript(
                        function ($args) {
                            return \MiMFa\Library\Html::Convert($args);
                        }
                        ,
                        "\${document.getElementById('$eid').value}",
                        "(data,err)=>document.getElementById('$sid').innerHTML=data??err"
                    )
                ) =>
                    self::Division(self::Center(self::Media("spinner")), ["id" => $sid])
            ],
            ["class" => "contentinput"]
        );
    }
    /**
     * The \<TEXTAREA\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ScriptInput($key, $value = null, ...$attributes)
    {
        return self::TextsInput($key, $value, ["class" => "scriptinput", "rows" => "10", "style" => "font-size: 75%; overflow:scroll; word-wrap: unset; direction: ltr;"], ...$attributes);
    }
    /**
     * The \<TEXTAREA\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ObjectInput($key, $value = null, ...$attributes)
    {
        return self::TextsInput($key, $value, ["class" => "objectinput", "style" => "font-size: 75%; overflow:scroll; word-wrap: unset;"], ...$attributes);
    }
    /**
     * A \<DIV\> HTML Tag contains an array of Inputs
     * @param mixed $key The tag name, id, or placeholder
     * @param array|iterable|null $value The tag default value
     * @param array|object $options The other options, default are: ["add"=>true, "remove"=>true]
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function CollectionInput($key, $value = null, $options = ["Type" => null, "Add" => true, "Remove" => true], ...$attributes)
    {
        if (is_null($value))
            if (is_array($key)) {
                $value = $key;
                $key = "_" . getId();
            } else
                $value = [];
        $key = Convert::ToKey($key);
        return self::Division(function () use ($key, $value, $options, $attributes) {
            $sample = null;
            $attributes = [...($options ?? []), ...($attributes ?? [])];
            $add = grabValid($attributes, "Add", true);
            //$edit = grabValid($attributes, "edit", true);
            $rem = grabValid($attributes, "Remove", true);
            $sep = grabValid($attributes, "Separator", null);
            $type = self::InputDetector(grabValid($attributes, "Type"), grabValid($attributes, "Value"));
            $key = grabValid($attributes, "Key", $key);
            $attrs = grabValid($attributes, "Attributes", []);
            $options = grabValid($attributes, "Options", null);
            if (isEmpty($value))
                $value = [];
            elseif (is_string($value)) {
                $value = is_null($sep) && startsWith($value, "[", "{") ? Convert::FromJson($value) ?? [] : explode($sep ?? "|", trim($value, $sep ?? "|"));
            }

            foreach ($value as $k => $item) {
                $id = self::GrabAttribute($attributes, "Id") ?? Convert::ToId($key);
                if (is_null($sample))
                    $sample = $item;
                yield self::Field(
                    type: $type,
                    wrapper: !$rem,
                    key: $key,
                    value: $item,
                    title: false,
                    description: false,
                    options: $options,
                    attributes: [($rem ? ["ondblclick" => "this.remove();"] : null), ...$attributes, ["id" => $id, "name" => (is_numeric($k) ? "{$key}[]" : "{$key}[$k]")], ...$attrs]
                );
            }
            if ($add) {
                $aid = self::GrabAttribute($attributes, "Id") ?? self::GrabAttribute($attrs, "Id") ?? Convert::ToId($key) . "_add";
                $oc = "
                        let tag = document.getElementById(`$aid`).cloneNode(true);
                        tag.id = `$key" . getId() . "`;
                        tag.name = `{$key}[]`;
                        tag.removeAttribute(`disabled`);
                        tag.setAttribute(`class`,`input`);
                        tag.setAttribute(`style`,``);
                        " . ($rem ? "tag.ondblclick = function(){ this.remove(); };" : "") . "
                        this.parentElement.appendChild(tag);";
                yield self::Icon("plus", $oc);
                yield self::Field(
                    type: self::InputDetector($type, $sample),
                    key: $key,
                    value: null,
                    title: false,
                    options: $options,
                    attributes: [...$attributes, "Id" => $aid, "name" => "", "disabled" => "disabled", "style" => "display: none;", ...$attrs]
                );
            }
        }, ["id" => $key, "class" => "collectioninput"]);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function CheckInput($key, $value = null, ...$attributes)
    {
        //return self::Input($key, $value ? "1" : "0", "checkbox", ["class" => "checkinput", ...($value ? ["checked" => "checked"] : []), "onchange" => "this.value = this.checked?1:0;"], $attributes);
        $class = "_" . getId();
        return self::Action(
            Html::Icon($value ? "turn-on" : "toggle-off", null, ["class" => $class]),
            "icon_$class = document.querySelector('.icon.$class');
            cb_$class = document.querySelector('.checkinput.$class');
                    if(cb_$class.checked){
                        icon_$class.classList.remove('fa-toggle-on');
                        icon_$class.classList.add('fa-toggle-off');
                        cb_$class.click();
                    } else {
                        icon_$class.classList.remove('fa-toggle-off');
                        icon_$class.classList.add('fa-toggle-on');
                        cb_$class.click();
                    }",
            ["class" => "checkinput"]
        ) .
            self::Input($key, $value ? "1" : "0", "checkbox", [
                "class" => "checkinput $class hide",
                ...($value ? ["checked" => "checked"] : []),
                "onchange" => "icon_$class = document.querySelector('.icon.$class');
                    if(this.checked){
                        icon_$class.classList.remove('fa-toggle-off');
                        icon_$class.classList.add('fa-toggle-on');
                        this.value = 1;
                    } else {
                        icon_$class.classList.remove('fa-toggle-on');
                        icon_$class.classList.add('fa-toggle-off');
                        this.value = 0;
                    }"
            ], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag Collection
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value Default value
     * @param array $options Available options
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ChecksInput($key, $value = null, $options = [], ...$attributes)
    {
        $ops = [];
        if (is_array($value))
            foreach ($options as $k => $v)
                if (is_int($k))
                    $ops[$v] = in_array($v, $value);
                else
                    $ops[$k] = in_array($k, $value);
        else
            foreach ($options as $k => $v)
                if (is_int($k))
                    $ops[$v] = $value;
                else
                    $ops[$k] = $v??$value;
        return join(PHP_EOL, loop($ops, function ($v, $k) use ($key, $attributes) {
            return self::Label($k, self::CheckInput("{$key}[]", $v, ...$attributes), ["class" => "checksinput"]);
        }));
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function RadioInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $key, "radio", ["class" => "radioinput", ...($value ? ["checked" => "checked"] : [])], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag Collection
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value Default value
     * @param array $options Available options
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function RadiosInput($key, $value = null, $options = [], ...$attributes)
    {
        $ops = [];
        if (is_array($value))
            foreach ($options as $k => $v)
                if (is_int($k))
                    $ops[$v] = in_array($v, $value);
                else
                    $ops[$k] = in_array($k, $value);
        else
            foreach ($options as $k => $v)
                if (is_int($k))
                    $ops[$v] = $value;
                else
                    $ops[$k] = $v??$value;
        return join(PHP_EOL, loop($ops, function ($v, $k) use ($key, $attributes) {
            return self::Label($k, self::RadioInput("{$key}[]", $v, ...$attributes), ["class" => "radiosinput"]);
        }));
    }
    /**
     * The \<SELECT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value Default value
     * @param mixed $options Available options
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function SelectInput($key, $value = null, $options = [], ...$attributes)
    {
        return self::Element(
            is_iterable($options) || is_array($options) ?
            iterator_to_array((function () use ($options, $value, $attributes) {
                $value = is_null($value) ? [] : (is_array($value) ? $value : [Convert::ToString($value)]);
                $f = false;
                if ($f = !$value)
                    yield self::Element(__(self::GrabAttribute($attributes, "PlaceHolder", "")), "option", ["value" => "", "selected" => "true"]);
                // else yield self::Element("", "option", ["value" => ""]);
                foreach ($options as $k => $v)
                    if (!$f && ($f = in_array($k, $value)))
                        yield self::Element(__($v ?? ""), "option", ["value" => $k, "selected" => "true"]);
                    else
                        yield self::Element(__($v ?? ""), "option", ["value" => $k]);
            })())
            : Convert::ToString($options, assignFormat: "<option value='{0}'>{1}</option>\r\n"),
            "select",
            ["id" => self::GrabAttribute($attributes, "Id") ?? Convert::ToId($key), "name" => Convert::ToKey($key), "placeholder" => Convert::ToTitle($key), "class" => "input selectinput"],
            $attributes
        );
    }
    /**
     * The \<SELECT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value Default value
     * @param mixed $options Available options
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function SelectsInput($key, $value = null, $options = [], ...$attributes)
    {
        return self::SelectInput("{$key}[]", $value, $options, ["class" => "selectsinput", "multiple" => null], ...$attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ColorInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "color", ["class" => "colorinput"], $attributes);
    }
    /**
     * The Calendar \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function CalendarInput($key, $value = null, ...$attributes)
    {
        $id = Convert::ToId($key);
        $name = $key;
        $attrs = [];
        foreach (Convert::ToIteration(...$attributes) as $k => $v) {
            switch (strtolower(trim($k))) {
                case "name":
                    $name = $v;
                    break;
                case "id":
                    $id = $v;
                    break;
                default:
                    $attrs[$k] = $v;
                    break;
            }
        }
        return self::Calendar($value, [
            "class" => "calendarinput",
            "for" => $id,
            "onchange" => "
                dt = new Date(parseInt(this.getAttribute('value' )));
                let inp = document.querySelector('input#$id');
                val = dt.getFullYear()+'-'+(dt.getMonth()<9?'0':'')+(dt.getMonth()+1)+'-'+(dt.getDate()<10?'0':'')+dt.getDate()+' '+(dt.getHours()<10?'0':'')+dt.getHours()+':'+(dt.getMinutes()<10?'0':'')+dt.getMinutes()+':'+(dt.getSeconds()<10?'0':'')+dt.getSeconds();
                inp.setAttribute('value' , val);
                inp.value = val;
            "
        ], $attrs) .
            self::Input($key, $value, "datetime-local", ["class" => "calendarinput", "id" => $id, "name" => $name, "style" => "display: none;"]);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function DateTimeInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "datetime-local", ["class" => "datetimeinput"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function DateInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "date", ["class" => "dateinput"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function TimeInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "time", ["class" => "timeinput"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function MonthInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "month", ["class" => "monthinput"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function WeekInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "week", ["class" => "weekinput"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag by a pattern for input
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $mask A RegEx pattern without wrap slashes
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function MaskInput($key, $value = null, $mask = "\\w+", ...$attributes)
    {
        return self::Input($key, $value, "text", [
            "class" => "maskinput",
            ...(isPattern(text: $mask ?? "") ? ["onblur" => "this.value = ((this.value.match($mask)??[''])[0]??'')"] : ["pattern" => $mask, "title" => "Please complete field by correct format..."])
        ], ...$attributes);
    }
    /**
     * The \<INPUT\> HTML Tag to input a code number
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param int|null $length The length of value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function CodeInput($key, $value = null, $length = null, ...$attributes)
    {
        return self::MaskInput($key, $value, "\\d" . ($length ? "{{$length}}" : "+"), ["class" => "codeinput"], ...$attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function PathInput($key, $value = null, ...$attributes)
    {
        return self::MaskInput($key, $value, '[^\<\>\^\`\{\|\}\r\n\t\f\v]*', ["class" => "pathinput"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function UrlInput($key, $value = null, ...$attributes)
    {
        return self::MaskInput($key, $value, "(^\/[^:]*$)|(^https?:\/\/.*$)", ["class" => "urlinput"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function EmailInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "email", ["class" => "emailinput"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function FileInput($key, $value = null, ...$attributes)
    {
        $id1 = self::GrabAttribute($attributes, "Id") ?? Convert::ToId($key);
        $id2 = Convert::ToId($key);
        $key = Convert::ToKey($key);
        return self::Input($key, null, "file", $attributes, [
            "class" => "fileinput",
            "id" => $id1,
            "style" => $value ? "display:none;" : "",
            ...($value ? ["name" => ""] : ["name" => "$key"]),
            "oninput" => "
            elem = document.getElementById('$id2');
            if(this.files.length>0){
                this.setAttribute('name', '$key');
                elem.removeAttribute('name');
                elem.setAttribute('disabled', 'disabled');
            } else {
                this.removeAttribute('name');
                elem.setAttribute('name', '$key');
                elem.removeAttribute('disabled');
            }"
        ]) .
            self::Input($key, $value, "text", $attributes, [
                "class" => "fileinput",
                "id" => $id2,
                ...($value ? ["name" => "$key"] : ["name" => ""]),
                "oninput" => "
            elem = document.getElementById('$id1');
            if(!isEmpty(this.value)){
                this.setAttribute('name', '$key');
                elem.removeAttribute('name');
                elem.setAttribute('disabled', 'disabled');
                elem.style.display='none';
            } else {
                this.removeAttribute('name');
                elem.setAttribute('name', '$key');
                elem.removeAttribute('disabled');
                elem.style.display='inherit';
            }"
            ]);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function FilesInput($key, $value = null, ...$attributes)
    {
        return self::FileInput($key, $value, "multiple", ["class" => "directoryinput"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function DirectoryInput($key, $value = null, ...$attributes)
    {
        return self::FileInput($key, $value, "webkitdirectory multiple", ["class" => "directoryinput"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function SearchInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "search", ["class" => "searchinput"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function TelInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "tel", ["class" => "telinput"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param int $min The minimum value
     * @param int $max The maximum value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function RangeInput($key, $value = null, $min = 0, $max = 100, ...$attributes)
    {
        $id = "_" . getId();
        return self::Input($key, $value, "range", ["min" => $min, "max" => $max, "class" => "rangeinput", "oninput" => "document.getElementById('$id').value = this.value;"], ...$attributes) .
            self::Element($value ?? "", "output", ["class" => "tooltip", "id" => $id]);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function NumberInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "number", ["class" => "numberinput", "inputmode" => "numeric"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function FloatInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "number", ["class" => "floatinput", "step" => "" . (1 / pow(10, self::$MaxDecimalPrecision)), "inputmode" => "numeric"], $attributes);
        //return self::Input($key, isset($attributes["step"])?$value:round($value, self::$MaxDecimalPrecision), "number", ["class"=>"floatinput", "step"=>"".(1/pow(10,self::$MaxDecimalPrecision)), "inputmode"=>"numeric"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function SecretInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "password", ["class" => "secretinput"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function HiddenInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "hidden", ["class" => "hiddeninput"], $attributes);
    }
    /**
     * A Disabled \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function DisabledInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "text", ["class" => "disabledinput", "disabled" => "disabled"], $attributes);
    }
    #endregion


    #region MODULE
    /**
     * A \<SPAN\> HTML Tag contains a counter
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function Counter($from, $to = 0, $action = null, $step = 1, $period = 1000, $updateValueFunction = null, ...$attributes)
    {
        $id = "c_" . getId();
        $countDown = $from >= $to;
        $counter = $id;
        $interval = $id . "_i";
        return Html::Element($from, "span", ["id" => $id, "class" => "counter"], $attributes) .
            Html::Script(
                "let $counter = " . ($countDown ? $from : $to) . ";" .
                "$interval = setInterval(() => {
			    if($counter " . ($countDown ? "<" : ">") . "= {$to}) {" . (
                    $action ? (
                        (isScript($action) || !isUrl($action)) ?
                        $action :
                        ("load(" . Script::Convert($action) . ");")
                    ) : ""
                ) . "
					clearInterval($interval);
					return;
		        }
				$counter += " . ($countDown ? -$step : $step) . ";
				elem = document.querySelector('.counter#$id');
			    if(elem) elem.innerHTML = {$updateValueFunction}($counter);
			}, {$period});"
            );
    }
    /**
     * A \<SPAN\> HTML Tag contains a timer
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function Timer($from, $to = 0, $action = null, $step = 1, $period = 1000, ...$attributes)
    {
        return self::Counter($from, $to, $action, $step, $period, "((sec)=>(new Date(sec * 1000)).toISOString().substring(11, 19))", ["class" => "timer"], $attributes);
    }
    /**
     * The Calendar HTML Tag
     * @param mixed $content The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function Calendar($content = null, ...$attributes)
    {
        if (!isValid($content))
            $content = Convert::ToDateTime();
        $dt = Convert::ToShownDateTime($content);
        $uniq = "_" . getId(true);
        $update = "{$uniq}_Click();";
        $weekDays = ["Sa.", "Su.", "Mo.", "Tu.", "We.", "Th.", "Fr."];
        return
            self::Style("
                .$uniq{
                    text-align: center;
                    display: flex;
                    align-content: space-around;
                    justify-content: space-around;
                    align-items: stretch;
                    flex-wrap: wrap;
                    flex-direction: row-reverse;
                }
                .$uniq *{
                    direction: ltr;
                }
                .$uniq .deactived{
                    cursor: default;
                    opacity: 0.5;
                }
                .$uniq .selected{
                    cursor: pointer;
                    background-color: var(--fore-color-output);
                    color: var(--back-color-output);
                }
                .$uniq :is(span.clickable, .media).media{
                    cursor: pointer;
                    color: var(--fore-color-input);
                    padding: 1px 3px;
                    margin: 0px;
				    " . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
                }
                .$uniq :is(span.clickable, .media):hover{
                    cursor: pointer;
                    color: var(--fore-color-output);
				    " . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
                }
                .$uniq :is(div, i, td).clickable{
                    cursor: pointer;
                    border-radius: var(--radius-0);
				    " . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
                }
                .$uniq :is(div, i, td).clickable:hover{
                    outline: var(--border-1) var(--color-yellow);
				    " . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
                }

                .$uniq :is(.grid$uniq, .select$uniq).shown{
                    position: absolute;
                    min-height: max-content;
                    background-color: var(--back-color-input);
                    color: var(--fore-color-input);
                    z-index: 999;
				    " . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
                }
                .$uniq .grid$uniq.shown{
                    display: flex;
                    align-content: space-around;
                    justify-content: space-around;
                    align-items: stretch;
                    flex-wrap: wrap;
                    flex-direction: row;
				    " . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
                }

                .$uniq :is(.grid$uniq, .select$uniq).hidden{
                    display: none;
				    " . (\MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)")) . "
                }

                .$uniq .grid$uniq th{
                    padding-bottom: 5px;
                    opacity: 0.8;
                }
                .$uniq .grid$uniq td{
                    padding: 1px 3px;
                }
                .$uniq .select$uniq :is(#OptionsBefore$uniq, #OptionsAfter$uniq){
                    cursor: pointer;
                    text-align: center;
                    display: block;
                    width: 100%;
                    height: var(--size-4);
                }
            ") .
            self::Script("
            function {$uniq}_Click(day = null){
                const tso = " . (\_::$Config->TimeStampOffset * 1000) . ";
                dt = new Date(
                    document.querySelector('.$uniq .Y$uniq').innerText+'-'+
                    document.querySelector('.$uniq .M$uniq').innerText+'-'+
                    (day??document.querySelector('.$uniq .D$uniq')).innerText+' '+
                    document.querySelector('.$uniq .h$uniq').innerText+':'+
                    document.querySelector('.$uniq .m$uniq').innerText+':'+
                    document.querySelector('.$uniq .s$uniq').innerText+' UTC');
                gdt = new Date(dt.getTime() - tso);
                $uniq = document.querySelector('.$uniq');
                $uniq.setAttribute('value' , gdt.getTime());
                if(day == null) {
                    cd = dt.getDate();
                    sd = 1;
                    ed = new Date(gdt.getFullYear(), gdt.getMonth()+1, 0).getDate();
                    sw = (new Date(dt.getFullYear(), dt.getMonth(), sd).getDay()+1)%7;
                    if(" . (\_::$Config->DateTimeZone == "UTC" ? "false" : "true") . ")
                        switch(dt.getMonth() + 1){
                            case 1:
                            case 2:
                                ed = 31;
                                sw -= 3;
                            break;
                            case 3:
                            case 4:
                                ed = 31;
                            break;
                            case 5:
                            case 6:
                                ed = 31;
                                sw += 1;
                            break;
                            case 7:
                                ed = 30;
                                sw += 2;
                            break;
                            case 8:
                                ed = 30;
                                sw += 1;
                            break;
                            case 9:
                            case 10:
                                ed = 30;
                            break;
                            case 11:
                                ed = 30;
                                sw -= 1;
                            break;
                            case 12:
                                ed = 29;
                                sw -= 1;
                            break;
                        }
                    if(sw < 0) sw += 7;
                    let w = 0;
                    for(item of document.querySelectorAll('.$uniq .week$uniq td'))
                        if(w++ >= sw && sd <= ed) {
                            item.innerText = sd;
                            item.setAttribute('class','clickable');
                            if(sd == cd) item.setAttribute('class','clickable D$uniq selected');
                            sd++;
                        } else {
                            item.innerText = '';
                            item.setAttribute('class','');
                        }
                } else {
                    for(item of document.querySelectorAll('.$uniq td.clickable'))
                        item.setAttribute('class','clickable');
                    day.setAttribute('class','clickable D$uniq selected');
                    document.querySelector('.$uniq span.D$uniq').innerText = day.innerText;
                    {$uniq}_ToggleWeek(false);
                }
                $uniq.dispatchEvent(new Event('change'));
            }
            function {$uniq}_ShowOptions(destSelector, current, min = 0, max = 9999){
                dv = parseInt(document.querySelector(destSelector).innerText);
                c = Math.abs(current);
                rc = 4;
                pc = 16;
                if(c > 9999999) {
                    rc = 1;
                    pc = 4;
                }
                else if(c > 999999) {
                    rc = 1;
                    pc = 4;
                }
                else if(c > 99999) {
                    rc = 2;
                    pc = 8;
                }
                else if(c > 9999) {
                    rc = 3;
                    pc = 12;
                }
                else if(c > 999) {
                    rc = 3;
                    pc = 12;
                }
                else if(c > 99) {
                    rc = 4;
                    pc = 16;
                }
                // else {
                //     let nc = Math.abs(max - min) + 1;
                //     for(let i = 4; i > 0; i--)
                //         if(nc%i == 0) {
                //             rc = i;
                //             pc = Math.min(nc, i * 4);
                //             break;
                //         }
                // }
                mn = Math.max(min, current - rc + 1);
                mx = Math.min(max, mn + pc - 1);
                obefore = document.querySelector('.$uniq .select$uniq #OptionsBefore$uniq');
                oafter = document.querySelector('.$uniq .select$uniq #OptionsAfter$uniq');
                if(mn <= min) {
                    obefore.setAttribute('class','icon fa fa-angle-up hide');
                    obefore.setAttribute('onclick','');
                }
                else {
                    obefore.setAttribute('class','icon fa fa-angle-up clickable');
                    obefore.setAttribute('onclick','{$uniq}_ShowOptions(`'+destSelector+'`, '+ (current - rc) +', '+ min +', '+ max +')');
                }
                if(mx >= max) {
                    oafter.setAttribute('class','icon fa fa-angle-down hide');
                    oafter.setAttribute('onclick','');
                }
                else {
                    oafter.setAttribute('class','icon fa fa-angle-down clickable');
                    oafter.setAttribute('onclick','{$uniq}_ShowOptions(`'+destSelector+'`, '+ (current + rc) +', '+ min +', '+ max +')');
                }
                opts = `<div class='row'>`;
                for(let i = 0; mn <= mx; i++) {
                    if(i%rc==0 && i != 0) opts += `</div><div class='row'>`;
                    opts += `<div class='col-sm clickable`+(dv == mn?' selected':'')+`' onclick=\"{$uniq}_SelectOption(this, document.querySelector(\``+destSelector+`\`))\">`+(mn++)+`</div>`;
                }
                opts += `</div>`;
                document.querySelector('.$uniq .select$uniq .options$uniq').innerHTML = opts;
                document.querySelector('.$uniq .grid$uniq').setAttribute('class','grid$uniq hidden');
                document.querySelector('.$uniq .select$uniq').setAttribute('class','select$uniq shown');
            }
            function {$uniq}_SelectOption(newElement, oldElement){
                oldElement.innerText = newElement.innerText;
                document.querySelector('.$uniq .select$uniq').setAttribute('class','select$uniq hidden');
                $update
            }
            ShowWeeks = false;
            function {$uniq}_ToggleWeek(show = null){
                if(show == null) show = !ShowWeeks;
                ShowWeeks = show;
                document.querySelector('.$uniq .grid$uniq').setAttribute('class','grid$uniq '+(show?'shown':'hidden'));
            }
            ") .
            self::Division(
                self::Division([
                    self::Span(self::Media("", "calendar", ["onclick" => "{$uniq}_ToggleWeek();"])) .
                    self::Span($dt->format("Y"), ["class" => "Y$uniq clickable", "onclick" => "{$uniq}_ShowOptions('.$uniq .Y$uniq', parseInt(this.innerText), 0, 9999)"]) .
                    self::Span("/") .
                    self::Span($dt->format("m"), ["class" => "M$uniq clickable", "onclick" => "{$uniq}_ShowOptions('.$uniq .M$uniq', parseInt(this.innerText), 1, 12)"]) .
                    self::Span("/") .
                    self::Span($dt->format("d"), ["class" => "D$uniq clickable", "onclick" => "{$uniq}_ToggleWeek(true);"])
                ]) .
                self::Division(
                    [
                        self::Media(" ", "angle-up", ["id" => "OptionsBefore$uniq"]),
                        [self::SmallFrame("", ["class" => "options$uniq"])],
                        self::Media(" ", "angle-down", ["id" => "OptionsAfter$uniq"])
                    ]
                    ,
                    ["class" => "select$uniq hidden"]
                ) .
                "<table class='grid$uniq hidden'>" .
                "<tr>" . join(PHP_EOL, [
                        self::Cell($weekDays[0], ["Type" => "head"]),
                        self::Cell($weekDays[1], ["Type" => "head"]),
                        self::Cell($weekDays[2], ["Type" => "head"]),
                        self::Cell($weekDays[3], ["Type" => "head"]),
                        self::Cell($weekDays[4], ["Type" => "head"]),
                        self::Cell($weekDays[5], ["Type" => "head"]),
                        self::Cell($weekDays[6], ["Type" => "head"])
                    ]) . "</tr>" .
                join(
                    PHP_EOL,
                    iterator_to_array((function () use ($uniq, $dt, $update, $weekDays) {
                        $week = [];
                        $cd = intval($dt->format("d"));
                        $sw = (intval($dt->setDate(intval($dt->format("Y")), intval($dt->format("m")), 1)->format("w")) + 1) % 7;
                        $ed = intval($dt->format("t"));
                        $d = -$sw;
                        for ($i = 1; $i <= 49; $i++) {
                            if (++$d > 0 && $d <= $ed)
                                $cel = self::Cell($d, ["Type" => "cell", "class" => "clickable" . ($cd == $d ? " D$uniq selected" : ""), "onclick" => "{$uniq}_Click(this);"]);
                            else
                                $cel = self::Cell("", ["Type" => "cell"]);
                            if (count($week) == 7) {
                                yield "<tr class='week$uniq'>" . join(PHP_EOL, $week) . "</tr>";
                                $week = [$cel];
                            } else
                                $week[] = $cel;
                        }
                    })())
                ) .
                "</table> &nbsp; " .
                self::Division([
                    self::Span(self::Media("", "clock", ["onclick" => "{$uniq}_ToggleWeek();"])) .
                    self::Span($dt->format("H"), ["class" => "h$uniq clickable", "onclick" => "{$uniq}_ShowOptions('.$uniq .h$uniq', parseInt(this.innerText), 0, 23)"]) .
                    self::Span(":") .
                    self::Span($dt->format("i"), ["class" => "m$uniq clickable", "onclick" => "{$uniq}_ShowOptions('.$uniq .m$uniq', parseInt(this.innerText), 0, 59)"]) .
                    self::Span(":") .
                    self::Span($dt->format("s"), ["class" => "s$uniq clickable", "onclick" => "{$uniq}_ShowOptions('.$uniq .s$uniq', parseInt(this.innerText), 0, 59)"])
                ])
                ,
                ["class" => "calendar $uniq", "value" => Convert::ToDateTime($content)->format('Uv')/*Get the miliseconds of the Time*/],
                $attributes
            );
    }
    /**
     * The \<DIV\> HTML Tag to make a tab control
     * @param array|callable $content The tabs array with the format [tab1Name=>tab1Content,tab2Name=>tab2Content]
     * @param mixed $attributes Other custom attributes of the Tag, send ["SelectedIndex"=>KeyOrIndex] to set first active tab (default is 0)
     * @return string
     */
    public static function Tabs($content = [], $options = [], ...$attributes)
    {
        $content = Convert::ToSequence($content);
        $active = grab($options, "SelectedIndex") ?? 0;
        $id = self::GrabAttribute($attributes, "Id") ?? "_" . getId();
        return self::Style("
        #$id>.tab-titles>.tab-title>:is(*,*:hover){border:none; outline:none;}
        #$id>.tab-titles>.tab-title{display:inline-block; padding:calc(var(--size-1) / 5) calc(var(--size-1) / 2); border-bottom: var(--border-1) #8885;}
        #$id>.tab-titles>.tab-title.active{border: var(--border-1) #8888; border-bottom: none;}
        ") .
            self::Division(
                self::Division(
                    join("", loop(
                        $content,
                        function ($v, $k, $i) use ($active, $id) {
                            return self::Division($k, ["class" => "tab-title" . ($k === $active || $i === $active ? " active" : ""), "onclick" => "{$id}_openTab(this, '$id-tab-$i')"]);
                        }
                    )),
                    ["class" => "tab-titles"]
                ) .
                self::Division(
                    join("", loop(
                        $content,
                        function ($v, $k, $i) use ($active, $id) {
                            return self::Element($v, "div", ["class" => "tab-content" . ($k === $active || $i === $active ? " show" : " hide"), "id" => "$id-tab-$i"]);
                        }
                    )),
                    ["class" => "tab-contents"]
                ),
                $options,
                ["class" => "tabs", "id" => $id],
                $attributes
            ) .
            self::Script("function {$id}_openTab(tab, tabId){
            let contents = document.querySelectorAll('#$id>.tab-contents>.tab-content');
            contents.forEach(content => content.classList.remove('show') & content.classList.add('hide'));
            let titles = document.querySelectorAll('#$id>.tab-titles>.tab-title');
            titles.forEach(title => title.classList.remove('active'));
            document.getElementById(tabId).classList.remove('hide');
            document.getElementById(tabId).classList.add('show');
            tab.classList.add('active');
        }");
    }

    public static function Chart($type = "column", $content = null, $title = null, $description = null, $axisXTitle = "X", $axisYTitle = "Y", $color = null, $foreColor = null, $backColor = null, $font = "defaultFont", $height = "400px", $width = null, $axisXBegin = null, $axisYBegin = null, $axisXInterval = null, $axisYInterval = null, $options = [], ...$attributes)
    {
        if ($content === null) {
            $content = $type;
            $type = "column";
        }
        if ($content === null)
            return null;
        $isen = is_array($content);
        $isobj = is_object($content);
        $datachart = null;
        $id = "chart" . getId();
        if (!$isen && !$isobj)
            $datachart = $content;
        else {
            if (!$isen && $isobj) {
                $rows = between($content["matrix"], $content["table"], $content["rows"], $content["columns"]);
                if (isEmpty($rows))
                    $datachart = Convert::ToString($content);
                else {
                    $arr = [];
                    $l = between($content["labels"], $content["label"], -1);
                    $xs = between($content["axisX"], $content["xs"], $content["x"], []);
                    $ys = between($content["axisY"], $content["ys"], $content["y"], []);
                    $ct = 0;
                    if ($l > -1)
                        if (count($xs) > 0)
                            if (count($ys) > 0)
                                foreach ($rows as $row) {
                                    $arr[] = takeValid($row, $l);
                                    $arr[] = count($xs) == 1 ? floatval(takeValid($row, $xs[0])) : loop($xs, function ($v, $i) use ($row) {
                                        return floatval(takeValid($row, $i));
                                    });
                                    $arr[] = count($ys) == 1 ? floatval(takeValid($row, $ys[0])) : loop($ys, function ($v, $i) use ($row) {
                                        return floatval(takeValid($row, $i));
                                    });
                                } else
                                foreach ($rows as $row) {
                                    $arr[] = takeValid($row, $l);
                                    $arr[] = count($xs) == 1 ? floatval(takeValid($row, $xs[0])) : loop($xs, function ($v, $i) use ($row) {
                                        return floatval(takeValid($row, $i));
                                    });
                                    $arr[] = $ct++;
                                } else if (count($ys) > 0)
                            foreach ($rows as $row) {
                                $arr[] = takeValid($row, $l);
                                $arr[] = $ct++;
                                $arr[] = count($ys) == 1 ? floatval(takeValid($row, $ys[0])) : loop($ys, function ($v, $i) use ($row) {
                                    return floatval(takeValid($row, $i));
                                });
                            } else
                            foreach ($rows as $row)
                                $arr[] = takeValid($row, $l);
                    else if (count($xs) > 0)
                        if (count($ys) > 0)
                            foreach ($rows as $row) {
                                $arr[] = count($xs) == 1 ? floatval(takeValid($row, $xs[0])) : loop($xs, function ($v, $i) use ($row) {
                                    return floatval(takeValid($row, $i));
                                });
                                $arr[] = count($ys) == 1 ? floatval(takeValid($row, $ys[0])) : loop($ys, function ($v, $i) use ($row) {
                                    return floatval(takeValid($row, $i));
                                });
                            } else
                            foreach ($rows as $row) {
                                $arr[] = count($xs) == 1 ? floatval(takeValid($row, $xs[0])) : loop($xs, function ($v, $i) use ($row) {
                                    return floatval(takeValid($row, $i));
                                });
                                $arr[] = $ct++;
                            } else if (count($ys) > 0)
                        foreach ($rows as $row) {
                            $arr[] = $ct++;
                            $arr[] = count($ys) == 1 ? floatval(takeValid($row, $ys[0])) : loop($ys, function ($v, $i) use ($row) {
                                return floatval(takeValid($row, $i));
                            });
                        }
                    $content = $arr;
                }
            }
            $arr = array();
            $isten = is_array($type);
            if ($isen && count($content) < 3)
                $arr = loop($content, function ($cnt, $i) use ($type, $isten) {
                    return join("", ["{", "type: `", $isten ? $type[$i] : $type, "`", ", dataPoints: [", Script::Points($cnt), "]}"]);
                });
            else if ($isten)
                $arr = loop($type, function ($ty, $i) use ($content, $isen) {
                    return join("", ["{", strpos($ty, ":") ? $ty : "type: `" + $ty + "`", ", dataPoints: [", Script::Points($isen ? $content[$i] : $content), "]}"]);
                });
            else
                $arr[] = join("", ["{type: `$type`, ", ($color == null ? "" : "color: `$color`, "), "dataPoints: [", Script::Points($content), "]}"]);

            $datachart = "[" . join(",", $arr) . "]";
        }
        $axisXTitle = __($axisXTitle);
        $axisYTitle = __($axisYTitle);
        $title = __($title);
        $description = __($description);
        return self::Style(".canvasjs-chart-credit{display:none !important;}") .
            self::Division(
                self::Heading($title) .
                renderScript(null, asset(\_::$Address->ScriptDirectory, "Canvas.js")) .
                self::Script("
                    window.addEventListener(`load`, function()
                        {
                            var chart = new CanvasJS.Chart(`$id`, {
                                theme: `light2`,
							    zoomEnabled: true,
                                " . ($backColor ? "backgroundColor:`$backColor`," : "") . "
                                " . ($height ? "height:" . intval($height) . "," : "") . ($width ? "width:intval($width)," : "") . "
							    legend: {
								    horizontalAlign: `center`,
								    verticalAlign: `top`,
                                    " . ($foreColor ? "fontColor:`$foreColor`," : "") . "
		                            fontFamily: `" . ($font ?? "defaultFont") . "`
							    },
                                axisX:{
                                    title: `$axisXTitle`,
                                    crosshair: {
                                        enabled: true
                                    },
                                    " . ($axisXBegin ? "minimum:$axisXBegin," : "") . "
                                    " . ($axisXInterval ? "interval:$axisXInterval," : "") . "
		                            labelTextAlign: `" . \_::$Back->Translate->Direction . "`,
                                    " . ($foreColor ? "fontColor:`$foreColor`," : "") . "
                                    " . ($foreColor ? "titleFontColor:`$foreColor`," : "") . "
                                    " . ($foreColor ? "labelFontColor:`$foreColor`," : "") . "
		                            labelFontFamily: `" . ($font ?? "defaultFont") . "`,
		                            titleFontFamily: `" . ($font ?? "defaultFont") . "`
                                },
                                axisY:{
                                    title: `$axisYTitle`,
                                    crosshair: {
                                        enabled: true
                                    },
                                    " . ($axisYBegin ? "minimum:$axisYBegin," : "") . "
                                    " . ($axisYInterval ? "interval:$axisYInterval," : "") . "
		                            labelTextAlign: `" . \_::$Back->Translate->Direction . "`,
                                    " . ($foreColor ? "fontColor:`$foreColor`," : "") . "
                                    " . ($foreColor ? "titleFontColor:`$foreColor`," : "") . "
                                    " . ($foreColor ? "labelFontColor:`$foreColor`," : "") . "
		                            labelFontFamily: `" . ($font ?? "defaultFont") . "`,
		                            titleFontFamily: `" . ($font ?? "defaultFont") . "`
                                },
                                toolTip: {
                                    shared: true,
		                            fontFamily: `defaultFont`
                                },
                                title:{
                                    text: `$title`,
                                    " . ($foreColor ? "fontColor:`$foreColor`," : "") . "
                                    verticalAlign: `top`,
                                    horizontalAlign: `center`,
		                            fontFamily: `" . ($font ?? "defaultFont") . "`
                                },
                                subtitles:{
                                    text: `$description`,
                                    " . ($foreColor ? "fontColor:`$foreColor`," : "") . "
                                    verticalAlign: `bottom`,
                                    horizontalAlign: `center`,
		                            fontFamily: `" . ($font ?? "defaultFont") . "`
                                },
                                data: $datachart" . ($options == null ? "" : ",
                                " . $options) . "
                            });
                            chart.render();
                        });"),
                ["id" => $id, "style" => ($height ? "height:$height;" : "") . ($width ? "width:$width;" : ""), "class" => "chart"],
                $attributes
            );
    }
    #endregion
}