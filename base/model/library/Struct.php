<?php
namespace MiMFa\Library;

use ReflectionFiber;

/**
 * A simple library to create default and standard HTML tags
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#html See the Library Documentation
 */
class Struct
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
    public static $BreakWarp = "<div style='border-left:1px solid; height:100%; display:inline;'></div>";

    /**
     * Convert everything to a simple HTML format,
     * Supports all MarkDown markups, and custom markups such as:
     * - Inline code with backticks (`code`)
     * - Code blocks with greater-than sign (>)
     * - All type of multimedia embedding with ![alt](url)
     * - Add custom attributes to any tag with @{} after the markup
     * - Footnotes with [^note sign] and [note sign]: url or note
     * - Hashtags with #tag
     * - Mentions with @username
     * - Auto-detect text direction
     * - And more...
     * @param mixed $object
     */
    public static function Convert($object, ...$args)
    {
        $translate = \_::$Front->AllowTranslate;
        if (!is_null($object)) try {
            \_::$Front->AllowTranslate = false;
            if (is_string($object)) {
                if (preg_match("/(?<!\\\)\<[^\>]+(?<!\\\)\>/i", $object))
                    return $object;
                else {
                    $pre = '(?<!\\\\)'; // Ensure delimiter isn't escaped with a backslash
                    $attrPatt = "(?:\s*@\{(?:#([\w\-]+))?([\w\- \t]*(?=[\r\n\}]))?([^\}]*)?\})?";
                    $patt = "/(=\s*((\"[^\"]*\")|(\'[^\']*\')))|((<([a-z\w?:][a-z0-9-_.?:\/\\\]*)[^>]*((>[^<]*<\/\7>)|(\/?>))))/iu";
                    $tagPatt = "/(\"\S+[^\"]*\")|(\'\S+[^\']*\')|(<\S+[\w\W]*[^\\\\]>)/iUu";
                    $object = preg_replace('/\\\(?=[<>])/s', "", $object); // Remove Escapes
                    // Define common lookahead/lookbehind to prevent matching inside URLs or escaping

                    $fdir = Translate::DetectDirection($object);

                    $object = encode($object, $dic, pattern: $tagPatt);// To keep all previous tags unchanged

                    // Codes
                    $object = preg_replace("/{$pre}(`{2,4})(\w*)\s?([\s\S]+)\s?{$pre}\1{$attrPatt}/iu", self::CodeBlock("$3", ["id" => "$4", "class" => "$5", "title" => "$2"], "$6"), $object) ?? $object; // Code blocks
                    $object = encode($object, $dic, pattern: $patt);// To keep all previous tags unchanged
                    $object = preg_replace_callback("/{$pre}(?<!`)(`)([^`\r\n]+)\1(?!`){$attrPatt}/u", function ($m) {
                        return self::Code($m[2], ["id" => ($m[3] ?? null), "class" => ($m[4] ?? null)], $m[5] ?? []);
                    }, $object) ?? $object;
                    $object = encode($object, $dic, pattern: $patt);// To keep all previous tags unchanged
                    $object = preg_replace_callback("/((?:^[ \t]*>.*(?:\R|$))+){$attrPatt}/m", function ($m) {
                        //$m[1] = preg_replace('/^[ \t]*>[ \t]?/m', '', $m[1]);
                        return self::CodeBlock($m[1], ["id" => ($m[2] ?? null), "class" => ($m[3] ?? null)], $m[4] ?? []);
                    }, $object) ?? $object;
                    $object = encode($object, $dic, pattern: $patt);// To keep all previous tags unchanged

                    // Custom
                    $object = preg_replace_callback("/{$pre}!(\w+)\{(([^\}]*)|(\"[^\"]\")|({$pre}\{([^\}]*)|({$pre}\{[^\}]*{$pre}\}){$pre}\})){$pre}\}/iu", function ($m) {
                        $func = "\\MiMFa\\Library\\Struct::" . ($m[1] ?: "Division");
                        $args = Convert::FromJson(isJson($m[2]) ? $m[2] : "[" . $m[2] . "]") ?: [null];
                        return ($func)(...$args);
                    }, $object) ?? $object;
                    $object = preg_replace_callback("/{$pre}!\{([^\}]+){$pre}\}{$attrPatt}/iu", fn($m) => self::Division($m[1], ["id" => $m[2] ?? null, "class" => $m[3] ?? null], $m[4] ?? []), $object) ?? $object;
                    $object = encode($object, $dic, pattern: $patt);// To keep all previous tags unchanged

                    // Quotes
                    $object = preg_replace("/{$pre}(\"{2,4})([^\"\r\n]*)\s?([\s\S]+)\s?{$pre}\1{$attrPatt}/iu", self::QuoteBlock("$3", ["id" => "$4", "class" => "$5", "title" => "$2"], "$6"), $object) ?? $object; // Code blocks
                    $object = encode($object, $dic, pattern: $patt);// To keep all previous tags unchanged
                    $object = preg_replace_callback(
                        "/{$pre}(?<!\")\"(?=\S)(.+?)(?<=\S)\"(?!\"){$attrPatt}/us",
                        fn($m) => self::Quote($m[1], ["id" => $m[2] ?? null, "class" => ($m[3] ?? null)], $m[4] ?? []),
                        $object
                    ) ?? $object;
                    // $object = preg_replace_callback(
                    //     "/{$pre}\"\"\"{1,}\s*([\s\S]+?)\s*\"\"\"{1,}{$attrPatt}/us",
                    //     function ($m) use ($fdir) {
                    //         $content = trim($m[1]);
                    //         $dir = Translate::DetectDirection($content);
                    //         $classes = ($dir == $fdir) ? ($m[3] ?? null) : "be $dir " . ($m[3] ?? null);

                    //         return self::QuoteBlock($content, ["id" => $m[2] ?? null, "class" => $classes], $m[4] ?? []);
                    //     },
                    //     $object
                    // ) ?? $object;
                    $object = encode($object, $dic, pattern: $patt);// To keep all previous tags unchanged

                    // Headings
                    $object = preg_replace_callback("/\s?^[ \t]*\#\s(.*){$attrPatt}$/imu", fn($m) => self::Heading1($m[1], null, ["id" => $m[2] ?? null, "class" => ($m[3] ?? null)], $m[4] ?? []), $object) ?? $object;
                    $object = preg_replace_callback("/\s?^[ \t]*\#{2}\s(.*){$attrPatt}$/imu", fn($m) => self::Heading2($m[1], null, ["id" => $m[2] ?? null, "class" => ($m[3] ?? null)], $m[4] ?? []), $object) ?? $object;
                    $object = preg_replace_callback("/\s?^[ \t]*\#{3}\s(.*){$attrPatt}$/imu", fn($m) => self::Heading3($m[1], null, ["id" => $m[2] ?? null, "class" => ($m[3] ?? null)], $m[4] ?? []), $object) ?? $object;
                    $object = preg_replace_callback("/\s?^[ \t]*\#{4}\s(.*){$attrPatt}$/imu", fn($m) => self::Heading4($m[1], null, ["id" => $m[2] ?? null, "class" => ($m[3] ?? null)], $m[4] ?? []), $object) ?? $object;
                    $object = preg_replace_callback("/\s?^[ \t]*\#{5}\s(.*){$attrPatt}$/imu", fn($m) => self::Heading5($m[1], null, ["id" => $m[2] ?? null, "class" => ($m[3] ?? null)], $m[4] ?? []), $object) ?? $object;
                    $object = preg_replace_callback("/\s?^[ \t]*\#{6}\s(.*){$attrPatt}$/imu", fn($m) => self::Heading6($m[1], null, ["id" => $m[2] ?? null, "class" => ($m[3] ?? null)], $m[4] ?? []), $object) ?? $object;

                    // Tables
                    $object = preg_replace_callback(
                        "/((?:\s?^[ \t]*\|.*\|?[ \t]*$)+)/mu",
                        function ($matches) use ($fdir) {
                            return self::Table(
                                preg_replace_callback(
                                    "/\s?^[ \t]*\|\|?(.*)\|?[ \t]*$/mu",
                                    function ($rmatches) {
                                        return self::Row(
                                            preg_replace_callback(
                                                "/[ \t]*([^|\r\n]+)[ \t]*(((\|\|?$)|(\|\|?)|$))/u",
                                                function ($cmatches) {
                                                    return self::Cell($cmatches[1], strlen($cmatches[2]) > 1 ? ["Type" => "head"] : []);
                                                },
                                                $rmatches[1]
                                            ) ?? $rmatches[1]
                                        );
                                    },
                                    $matches[1]
                                ) ?? $matches[1],
                                [],
                                ($dir = Translate::DetectDirection($matches[1])) == $fdir ? [] : ["class" => "be $dir"]
                            );
                        },
                        $object
                    ) ?? $object;

                    // Media
                    $object = preg_replace_callback("/{$pre}!(\w+)\[([^\]]*)\](?:\(\s*([^\s]+)?(?:\s*\"([^\"]*)\")?\s*\))?{$attrPatt}/iu", fn($m) => ("\\MiMFa\\Library\\Struct::" . ($m[1] ?: "Division"))($m[2] ?? null, $m[3] ?? null, ($m[4] ?? null) ? ["tooltip" => $m[4]] : [], ["id" => $m[5] ?? null], ["class" => $m[6] ?? null], $m[7] ?? []), $object) ?? $object;
                    $object = preg_replace_callback("/!\[([^\]]+)\]\(\s*([^\s]+)(?:\s*\"([^\"]*)\")?\s*\){$attrPatt}/iu", fn($m) => self::Media($m[1] ?? null, $m[2] ?? null, ($m[3] ?? null) ? ["tooltip" => $m[3]] : [], ["id" => $m[4] ?? null, "class" => $m[5] ?? null, "controls" => null], $m[6] ?? []), $object) ?? $object;
                    $object = preg_replace_callback("/\[([^\]]+)\]\(\s*([^\s]+)\s*\){$attrPatt}/iu", fn($m) => self::Link($m[1], $m[2], ["id" => $m[3] ?? null, "class" => ($m[4] ?? null)], $m[5] ?? []), $object) ?? $object;

                    // Refer
                    $object = preg_replace_callback("/{$pre}\[([\w\-]+)\](?!\(|:){$attrPatt}/iu", fn($m) => self::Span("[$m[1]]", "#fn-$m[1]", ["id" => $m[3] ?? null], ($dir = Translate::DetectDirection($m[1])) == $fdir ? ["class" => ($m[4] ?? null)] : ["class" => "be $dir " . ($m[4] ?? null)], $m[5] ?? []), $object) ?? $object;
                    $object = preg_replace_callback("/{$pre}\[\^([^\]]+)\](?!\(|:){$attrPatt}/iu", fn($m) => self::Super("[$m[1]]", "#fn-$m[1]", ["id" => $m[3] ?? null], ($dir = Translate::DetectDirection($m[1])) == $fdir ? ["class" => ($m[4] ?? null)] : ["class" => "be $dir " . ($m[4] ?? null)], $m[5] ?? []), $object) ?? $object;
                    $object = preg_replace_callback("/{$pre}\[~([^\]]+)\](?!\(:){$attrPatt}/iu", fn($m) => self::Sub("[$m[1]]", "#fn-$m[1]", ["id" => $m[3] ?? null], ($dir = Translate::DetectDirection($m[1])) == $fdir ? ["class" => ($m[4] ?? null)] : ["class" => "be $dir " . ($m[4] ?? null)], $m[5] ?? []), $object) ?? $object;
                    $object = preg_replace_callback("/\s?^[ \t]*\[([\w\-]+)\]:\s*(.*){$attrPatt}$/imu", fn($m) => self::Division("[$m[1]] " . __($m[2]), ["class" => "footnote", "id" => $m[3] ?? "fn-$m[1]"], ($dir = Translate::DetectDirection($m[2])) == $fdir ? ["class" => ($m[4] ?? null)] : ["class" => "be $dir " . ($m[4] ?? null)], $m[5] ?? []), $object) ?? $object;
                    $object = preg_replace_callback("/\s?^[ \t]*\[([\^~])([^\]]+)\]:\s*(.*){$attrPatt}$/imu", fn($m) => self::Division($m[1] . __($m[2]) . " " . __($m[3]), ["class" => "footnote", "id" => $m[3] ?? "fn-$m[2]"], ($dir = Translate::DetectDirection($m[2])) == $fdir ? ["class" => ($m[4] ?? null)] : ["class" => "be $dir " . ($m[4] ?? null)], $m[5] ?? []), $object) ?? $object;

                    // Lists
                    $lc = 0;
                    do {
                        $object = preg_replace_callback(
                            "/((\s?^([ \t]*)([\-\*]|(\+|(?:(\w|(?:\d+))[\.\-\)\(])))[ \t]+(.*)$)+)/mu",
                            function ($wholematches) use ($attrPatt, $fdir) {
                                $lines = preg_split("/\r?\n\n?\r?/", trim($wholematches[1], "\r\n"));
                                $linePattern = "/\s?^([ \t]*)([\-\*]|(\+|(?:(\w|(?:\d+))[\.\-\)\(])))[ \t]+(.*){$attrPatt}$/miu";
                                preg_match($linePattern, reset($lines), $matches);
                                $linePattern = "/\s?^(" . $matches[1] . ")([\-\*]|(\+|(?:(\w|(?:\d+))[\.\-\)\(])))[ \t]+(.*){$attrPatt}$/miu";
                                $ordered = empty($matches[3]) ? false : true;
                                $list = [];
                                $inlines = [];
                                foreach ($lines as $line) {
                                    if (preg_match($linePattern, $line, $ms)) {
                                        if ($inlines && $list) {
                                            $list[count($list) - 1] .= self::Convert(join("\n", $inlines));
                                            $inlines = [];
                                        }
                                        $list[] = self::Item($ms[5], ["id" => ($ms[6] ?? null)], empty($ms[4]) ? [] : ["number" => $ms[4], "class" => ($ms[7] ?? null)], $ms[8] ?? []);
                                    } else
                                        $inlines[] = $line;
                                }
                                if ($inlines && $list) {
                                    $list[count($list) - 1] .= self::Convert(join("\n", $inlines));
                                    $inlines = [];
                                }
                                return $ordered
                                    ? self::List(join("", $list), empty($matches[4]) ? [] : ["start" => $matches[4]], ($dir = Translate::DetectDirection($wholematches[1])) == $fdir ? [] : ["class" => "be $dir"])
                                    : self::Items(join("", $list), ($dir = Translate::DetectDirection($wholematches[1])) == $fdir ? [] : ["class" => "be $dir"]);
                            },
                            $object,
                            -1,
                            $lc
                        ) ?? $object;
                    } while ($lc);

                    $object = encode($object, $dic, pattern: $tagPatt);// To keep all previous tags unchanged

                    // Links
                    $object = preg_replace_callback("/\b(?<![\"\'`])([a-z]{2,10}\:\/{2}[\/a-z_0-9\?\=\&\#\%\.\(\)\[\]\+\-\!\~\$]+)/iu", fn($m) => self::Link($m[1], $m[1]), $object) ?? $object;
                    $object = preg_replace_callback("/\b(?<![\"\'`])([a-z_0-9.\-]+\@[a-z_0-9.\-]+)/iu", fn($m) => self::Link($m[1], "mailto:{$m[1]}"), $object) ?? $object;
                    $object = preg_replace_callback("/\B{$pre}\#(\w+){$attrPatt}/iu", fn($m) => self::Link("#" . $m[1], \_::$Address->SearchRoot . urlencode("#" . $m[1]), ["id" => $m[2] ?? null, "class" => ($m[3] ?? null)], $m[4] ?? []), $object) ?? $object;
                    $object = preg_replace_callback("/\B{$pre}\@(\w+){$attrPatt}/iu", fn($m) => self::Link("@" . $m[1], \_::$Address->UserRoot . urlencode($m[1]), ["id" => $m[2] ?? null, "class" => ($m[3] ?? null)], $m[4] ?? []), $object) ?? $object;

                    $object = preg_replace_callback("/\s?(^[^\W].*){$attrPatt}$/miu", fn($m) => self::Element($m[1], "p", ["id" => ($m[2] ?? null)], ($dir = Translate::DetectDirection($m[1])) == $fdir ? ["class" => ($m[3] ?? null)] : ["class" => "be $dir " . ($m[3] ?? null)], $m[4] ?? []), $object) ?? $object;

                    $object = encode($object, $dic, pattern: $tagPatt);// To keep all previous tags unchanged

                    // Lines
                    $object = preg_replace("/\s?^(\-{3,})(.+)\1{$attrPatt}(?=<|$)/imu", self::BreakLine("\$2", null, ["id" => "\$3", "class" => "\$4"], "\$5"), $object) ?? $object;
                    $object = preg_replace("/\s?^\-{3,}{$attrPatt}(?=<|$)/im", self::Element(null, "hr", ["id" => "\$1", "class" => "\$2"], "\$3"), $object) ?? $object;

                    // Texts
                    // Strong: **text**
                    $object = preg_replace_callback(
                        "/{$pre}\B\*\*(?=\S)(.+?)(?<=\S)\*\*\B{$attrPatt}/us",
                        fn($m) => self::Strong($m[1], null, ["id" => ($m[2] ?? null), "class" => ($m[3] ?? null)], $m[4] ?? []),
                        $object
                    ) ?? $object;
                    // Bold: *text*
                    $object = preg_replace_callback(
                        "/{$pre}\B\*(?=\S)(.+?)(?<=\S)\*\B{$attrPatt}/us",
                        fn($m) => self::Bold($m[1], null, ["id" => ($m[2] ?? null), "class" => ($m[3] ?? null)], $m[4] ?? []),
                        $object
                    ) ?? $object;
                    // Underline: _text_
                    $object = preg_replace_callback(
                        "/{$pre}\b_(?=\S)(.+?)(?<=\S)_\b{$attrPatt}/us",
                        fn($m) => self::Underline($m[1], null, ["id" => ($m[2] ?? null), "class" => ($m[3] ?? null)], $m[4] ?? []),
                        $object
                    ) ?? $object;
                    // Italic: +text+
                    $object = preg_replace_callback(
                        "/{$pre}\B\+(?=\S)(.+?)(?<=\S)\+\B{$attrPatt}/us",
                        fn($m) => self::Italic($m[1], null, ["id" => ($m[2] ?? null), "class" => ($m[3] ?? null)], $m[4] ?? []),
                        $object
                    ) ?? $object;
                    // Strike: -text-
                    $object = preg_replace_callback(
                        "/{$pre}\B\-(?=\S)(.+?)(?<=\S)\-\B{$attrPatt}/us",
                        fn($m) => self::Strike($m[1], null, ["id" => ($m[2] ?? null), "class" => ($m[3] ?? null)], $m[4] ?? []),
                        $object
                    ) ?? $object;
                    // Superscript: ^(text) or ^text
                    $object = preg_replace_callback(
                        "/{$pre}\^\((.+?)\){$attrPatt}/us",
                        fn($m) => self::Super($m[1], null, ["id" => ($m[2] ?? null), "class" => ($m[3] ?? null)], $m[4] ?? []),
                        $object
                    ) ?? $object;
                    $object = preg_replace_callback(
                        "/{$pre}\^([^\s\^]+){$attrPatt}/us",
                        fn($m) => self::Super($m[1], null, ["id" => ($m[2] ?? null), "class" => ($m[3] ?? null)], $m[4] ?? []),
                        $object
                    ) ?? $object;
                    // Subscript: ~(text) or ~text
                    $object = preg_replace_callback(
                        "/{$pre}\~\((.+?)\){$attrPatt}/us",
                        fn($m) => self::Sub($m[1], null, ["id" => ($m[2] ?? null), "class" => ($m[3] ?? null)], $m[4] ?? []),
                        $object
                    ) ?? $object;
                    $object = preg_replace_callback(
                        "/{$pre}\~([^\s\~]+){$attrPatt}/us",
                        fn($m) => self::Sub($m[1], null, ["id" => ($m[2] ?? null), "class" => ($m[3] ?? null)], $m[4] ?? []),
                        $object
                    ) ?? $object;
                    $object = encode($object, $dic, pattern: $tagPatt);// To keep all previous tags unchanged

                    // Signs
                    $object = preg_replace("/(\r\n)|(\n\r)|((?<!>))\r?\n\r?/", self::$Break, $object) ?? $object;

                    return $fdir == \_::$Front->Translate->Direction ? decode($object, $dic) : self::Division(decode($object, $dic), ["class" => "be $fdir"]);
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
                        $texts[] = self::Item(self::Convert($val, ...$args));
                    return self::List(join(PHP_EOL, $texts));
                } elseif ($nArgs = getBetween($object, "Arguments", "Item")) {
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
                        attributes: $nArgs
                    );
                } else {
                    foreach ($object as $key => $val)
                        $texts[] = self::Item(self::Bold($key) . ": " . (is_string($val) ? (isUrl($val) ? self::Span(strlen($val) < 50 ? $val : Convert::ToExcerpt($val, 0, 20) . Convert::ToExcerpt($val, 0, 20, "", true), $val) : self::Span($val)) : self::Convert($val, ...$args)));
                    return self::Items(join(PHP_EOL, $texts));
                }
            }
            if (is_callable($object) || $object instanceof \Closure)
                return self::Convert($object(...$args));
            return self::Division(Convert::ToString($object));
        } finally {
            \_::$Front->AllowTranslate = $translate;
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
        $attrs = self::Attributes($attributes, $attachments, $inners, $outers, $allowMA);
        if ($isSingle)
            return "$outers<$tagName$attrs/>$inners$attachments";
        else
            switch ($tagName) {
                case "textarea":
                case "code":
                case "blockcode":
                case "xmp":
                    return join("", ["$outers<$tagName$attrs>", Convert::ToString($content), "</$tagName>$inners$attachments"]);
                default:
                    return join("", ["$outers<$tagName$attrs>", Convert::ToString($content), "$inners</$tagName>$attachments"]);
            }
    }
    public static function Attributes($attributes, &$attachments = "", &$inners = "", &$outers = "", $optimization = false)
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
                            $attrdic["$value"] = null;
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
                            $inners .= self::Tooltip(__($value));
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
    public static function PopAttribute(&$attributes, $key, $default = null): mixed
    {
        if (is_array($attributes)) {
            if ($res = pop($attributes, $key))
                return $res;
            foreach ($attributes as $ke => $val)
                if (($res = self::PopAttribute($val, $key, $default)) && $res !== $default) {
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
                            self::Element("meta", ["charset" => \_::$Front->Translate->Encoding]),
                            self::Element("meta", ["lang" => \_::$Front->Translate->Language]),
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
        $attrs = self::Attributes($attributes, $attachments, $inners, $outers, $allowMA);
        self::$TagStack[] = $tagName;
        return "$attachments<$tagName$attrs>$outers$inners";
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
    public static function Script($content = null, $source = null, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "script", is_null($source) ? ["type" => "text/javascript"] : ["src" => $source], $attributes);
    }
    public static function Style($content = null, $source = null, ...$attributes)
    {
        if (isValid($content))
            return self::Element(Convert::ToString($content), "style", $attributes);
        else
            return self::Relation("stylesheet", $source, $attributes);
    }
    #endregion


    #region NOTIFICATION

    /**
     * To show a modal tag
     * @param string $content The modal text or html tags
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Modal($content, ...$attributes)
    {
        $id = "_" . getId(true);
        return self::Division(
            self::Style("
            #$id.modal-overlay {
                position: fixed;
                inset: 0;
                width: 100%; height: 100%;
                background: rgba(0,0,0, 0.6);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 999999999;
            }
            #$id.modal-overlay>.modal {
                background-color: var(--back-color-special);
                color: var(--fore-color-special);
                position: fixed;
                padding: var(--size-3);
                border-radius: var(--radius-3);
                max-width: 90%;
                max-height: 90%;
                overflow: auto;
                box-shadow: var(--shadow-max);
            }
            #$id.modal-overlay>.modal>.modal-close {
                position: absolute;
                top: 0px; right: 0px;
            }
        ") .
            self::Division(
                [
                    self::Icon("close", "this.closest('#$id.modal-overlay').remove();event.preventDefault();", ["class" => 'modal-close']),
                    $content
                ]
                ,
                ["class" => "modal"],
                ...$attributes
            ),
            [
                "id" => $id,
                "class" => 'modal-overlay',
                "onclick" => "if(event.target.classList.contains('modal-overlay')) event.target.remove();event.preventDefault();"
            ]
        ) . self::Script("_('#$id').appendTo(_('body'));");
    }
    public static function Result($content, $icon = "bell", $wait = 10000, ...$attributes)
    {
        $id = "_" . getId(true);
        return self::Element(
            self::Icon($icon) . self::Division(__($content, referring: true) . self::Tooltip("Double click to hide")) . self::Icon("close", "document.getElementById('$id')?.remove()"),
            "div",
            [
                "id" => $id,
                "class" => "result $id",
                "ondblclick" => "this.style.display = 'none'",
                "onmouseenter" => "this.classList.remove('$id');",
            ],
            $attributes
        ) . self::Script("setTimeout(function(){ return document.querySelector('#$id.$id')?.remove();}, $wait); scrollThere('body>#$id');");
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
        $type = self::PopAttribute($attributes, "Type");
        if (is_array($source))
            foreach ($source as $key => $value)
                if (is_int($key) && !($key = $type))
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
        $type = self::PopAttribute($attributes, "Type");
        if (is_array($source))
            foreach ($source as $key => $value)
                if (is_int($key) && !($key = $type))
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
            return self::Element("", "i", ["class" => "image " . strtolower($source)], $attributes) . ($content ? self::Tooltip($content) : "");
        elseif (isIdentifier($source))
            return self::Element("", "i", ["class" => "image icon fa fa-" . strtolower($source)], $attributes) . ($content ? self::Tooltip($content) : "");
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
     * The \<VIDEO\> or \<AUDIO\> or \<IMAGE\> or \<I\> HTML Tag
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
        if (!$source)
            return null;
        if (isIdentifier($source))
            return self::Icon($source, null, ["class" => "media"], $attributes);
        if (isUrl($source))
            switch (strtolower(preg_find("/\.\w+$/", $source) ?? "")) {
                case ".ogg":
                case ".mp3":
                case ".wav":
                case ".flac":
                    return self::Audio($content, $source, ["class" => "media"], $attributes);
                case ".3gp":
                case ".avi":
                case ".mp4":
                case ".mov":
                case ".mkv":
                case ".webm":
                    return self::Video($content, $source, ["class" => "media"], $attributes);
                default:
                    return self::Image($content, $source, ["class" => "media"], $attributes);
            }
        return self::Element(self::Convert($content ?? ""), "div", ["style" => "background-image: url('" . \MiMFa\Library\Local::GetUrl($source) . "');", "class" => "media"], ...$attributes);
    }
    /**
     * A \<DIV\> HTML Tag with a media as the background
     * @param mixed $content The content of the Cover
     * @param string|null|array $source The source path to show at the background
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Cover($content, $source = null, ...$attributes)
    {
        $id = self::PopAttribute($attributes, "Id") ?? ("_" . getId());
        return self::Style("
            #$id{
                position:relative;
            }
            #$id>.media{
                position:absolute;
                top:0;
                left:0;
                width:100%;
                height:100%;
                object-fit:cover;
                z-index:0;
                overflow:hidden;
            }
            #$id>.media>*{
                width:100%;
                height:100%;
                object-fit:cover;
            }
            #$id>.division{
                position:relative;
                z-index:1;
            }
        ") .
            self::Element(
                self::Media(null, $source, [
                    "autoplay" => null,
                    "loop" => null,
                    "muted" => null,
                ]) .
                self::Division($content),
                "div",
                ["id" => $id, "class" => "cover"],
                ...$attributes
            );
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
     * @param array $sources The source paths or documents to show
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
        return self::Element(is_countable($content) ? loop($content, fn($v) => self::Part($v)) : $content, "div", ["class" => "page"], $attributes);
    }
    /**
     * The \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Part($content, ...$attributes)
    {
        return self::Element(is_countable($content) ? loop($content, fn($v) => self::Part($v)) : $content, "div", ["class" => "part"], $attributes);
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
            return self::Element($res, "dl", ["class" => "collection"], $attributes);
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
                $res[] = self::Element((is_numeric($k) ? "" : self::Heading6($k)) . __($item), "li", ["class" => "item"]);
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
                $res[] = self::Element((is_numeric($k) ? "" : self::Heading6($k)) . __($item), "li", ["class" => "item"]);
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
     * A Tiles Collection of evertythings \<DIV\> HTML Tag
     * @param mixed $content The Tiles of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Tiles($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $k => $item)
                $res[] = is_numeric($k) ? self::Tile($item) : self::Tile($k, $item);
            $content = $res;
            return self::Element($content, "div", ["class" => "tiles"], $attributes);
        }
        return self::Element(__($content), "div", ["class" => "tiles"], $attributes);
    }
    /**
     * The Tile Item \<ACTION\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array|callable $action The source path or onclick event script, (Use class names with full namespaces in callable references)
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Tile($content, $action = null, ...$attributes)
    {
        return self::Action($action ? self::Media($content) : $content, $action, ["class" => "tile"], $attributes);
    }
    /**
     * A Menus Collection of evertythings \<NAV\> HTML Tag
     * @param mixed $content The menus of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Menu($content, ...$attributes)
    {
        if (is_countable($content)) {
            $res = [];
            foreach ($content as $k => $item)
                $res[] = is_numeric($k) ? __($item) : self::Action(__($k), $item);
            $content = $res;
            return self::Element($content, "div", ["class" => "menu"], $attributes);
        }
        return self::Element($content, "div", ["class" => "menu"], $attributes);
    }
    /**
     * A Menus Collection of evertythings \<NAV\> HTML Tag
     * @param mixed $content The menus of the Tag
     * @param string $selector The css selector to bind the context menu
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function ContextMenu($content, $selector = null, ...$attributes)
    {
        $id = self::PopAttribute($attributes, "Id") ?? ("_" . getId());
        return self::Style("
            #$id {
                position: absolute;
                display: none;
                font-size: var(--size-0);
                font-weight: lighter;
                width: max-content;
                box-shadow: var(--shadow-max);
                z-index: -9999;
                transition: var(--transition-0);
                flex-direction: column;
            }
            #$id.active {
                display: flex;
                z-index: 9999;
            }
        ") . self::Menu($content, ["id" => $id, "class" => "contextmenu"], ...$attributes) .
            script("
                _(document).on('contextmenu', function(e) {
                    _('.menu.contextmenu:not(#$id)').removeClass('active');
                    var menu = document.getElementById('$id');
                    if(menu && ".($selector?("_(".Script::Convert($selector).").contains(e.target)"):"_(e.target).contains(menu)")."){
                        e.preventDefault();
                        if(e.pageY + menu.offsetHeight > window.innerHeight) menu.style.top = e.pageY - menu.offsetHeight + 'px';
                        else menu.style.top = e.pageY + 'px';
                        if(e.pageX + menu.offsetWidth > window.innerWidth || e.pageX < 0 || e.pageX + menu.offsetWidth < 0 || e.pageX > window.innerWidth) menu.style.left = e.pageX - menu.offsetWidth + 'px';
                        else menu.style.left = e.pageX + 'px';
                        menu.classList.add('active');
                    }
                });
                _('.menu.contextmenu>*:has(.menu.contextmenu)').on('mouseenter', function(e) {
                    _(e.target).matches('>.menu.contextmenu').each(function(menu){
                        menu.style.display = 'absolute';
                        if(e.target.offsetTop + menu.offsetHeight > window.innerHeight) menu.style.top = e.target.offsetTop - menu.offsetHeight + 'px';
                        else menu.style.top = e.target.offsetTop + 'px';
                        if(e.target.offsetLeft + menu.offsetWidth > window.innerWidth || e.target.offsetLeft < 0 || e.target.offsetLeft + menu.offsetWidth < 0 || e.target.offsetLeft > window.innerWidth) menu.style.left = e.target.offsetLeft - menu.offsetWidth + 'px';
                        else menu.style.left = e.target.offsetLeft + e.target.offsetWidth + 'px';
                        menu.classList.add('active');
                    });
                });
                _('.menu.contextmenu>*:has(.menu.contextmenu)').on('mouseleave', function(e) {
                    _(e.target).matches('>.menu.contextmenu').removeClass('active');
                });
                _(document).on('click', function(e) {
                    _('.menu.contextmenu').removeClass('active');
                    _('#$id').removeClass('active');
                });
            ");
    }

    /**
     * The \<TABLE\> HTML Tag
     * @param mixed $content The rows array or table content
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Table($content = "", $options = [], ...$attributes)
    {
        $rowHeaders = pop($options, "RowHeaders") ?? [intval(pop($options, "RowHeader") ?? 0)];
        $colHeaders = pop($options, "ColHeaders") ?? [intval(pop($options, "ColHeader") ?? 0)];
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
        $head = pop($options, "Type");
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
        if (strtolower(pop($options, "Type") ?? "") === "head")
            return self::Element(__($content ?? ""), "th", $options, ["class" => "table-cell"], $attributes);
        else
            return self::Element(__($content ?? ""), "td", $options, ["class" => "table-cell"], $attributes);
    }
    #endregion


    #region HEADING
    /**
     * The \<H3\> HTML Tag
     * @param mixed $content The heading text
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Heading($content, $reference = null, ...$attributes)
    {
        return self::Heading3($content, $reference, ...$attributes);
    }

    /*
     * The \<H1\> HTML Tag
     * @param mixed $content The heading text
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Heading1($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h1", ["class" => "heading heading1"], $attributes);
        return self::Element(__($content), "h1", ["class" => "heading heading1"], $attributes);
    }
    /**
     * The \<H2\> HTML Tag
     * @param mixed $content The heading text
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Heading2($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h2", ["class" => "heading heading2"], $attributes);
        return self::Element(__($content), "h2", ["class" => "heading heading2"], $attributes);
    }
    /**
     * The \<H3\> HTML Tag
     * @param mixed $content The heading text
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Heading3($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h3", ["class" => "heading heading3"], $attributes);
        return self::Element(__($content), "h3", ["class" => "heading heading3"], $attributes);
    }
    /**
     * The \<H4\> HTML Tag
     * @param mixed $content The heading text
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Heading4($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h4", ["class" => "heading heading4"], $attributes);
        return self::Element(__($content), "h4", ["class" => "heading heading4"], $attributes);
    }
    /**
     * The \<H5\> HTML Tag
     * @param mixed $content The heading text
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Heading5($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h5", ["class" => "heading heading5"], $attributes);
        return self::Element(__($content), "h5", ["class" => "heading heading5"], $attributes);
    }
    /**
     * The \<H6\> HTML Tag
     * @param mixed $content The heading text
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Heading6($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h6", ["class" => "heading heading6"], $attributes);
        return self::Element(__($content), "h6", ["class" => "heading heading6"], $attributes);
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
            return self::Element("hr", ["class" => "breakline"], $attributes);
        $attr = [
            "class" => "breakline",
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
                padding: calc(var(--size-0) / 2);
                margin: 0px;
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
     * The \<U\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Underline($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference)) {
                $attributes = Convert::ToIteration($reference, ...$attributes);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "u", ["class" => "underline"], $attributes);
        return self::Element(__($content), "u", ["class" => "underline"], $attributes);
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
     * The \<Data\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag,
     * "DecimalSeparator"=>".",
     * "Separator"=>","
     * @return string
     */
    public static function Number($content, ...$attributes)
    {
        $content = $content + 0;
        $decimalPoint = self::PopAttribute($attributes, "DecimalSeparator") ?? ".";
        $thousandSep = self::PopAttribute($attributes, "Separator") ?? ",";
        $decimals = is_float($content) ? strlen(substr(strrchr($content, "."), 1)) : 0;
        return self::Element(number_format($content, $decimals, $decimalPoint, $thousandSep), "data", ["class" => "number", "value" => $content], $attributes);
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
     * The \<PROGRESS\> HTML Tag
     * @param float|null $value A number between 0 to 1
     * @param mixed $content Main content of the tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Progress($value, $content = null, ...$attributes)
    {
        return self::Element($content ?? "", "progress", ["class" => "progress"], is_null($value) ? [] : ["value" => $value], $attributes);
    }
    /**
     * The \<HIDDEN\> HTML Tag
     * @param mixed $value A machine readable value
     * @param mixed $content Main content of the hidden tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Hidden($value, $content = null, ...$attributes)
    {
        return self::Element($content ?? "", "hidden", ["class" => "hidden", "value" => $value], $attributes);
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
    /**
     * The \<DATALIST\> HTML Tag
     * @param mixed $content Available options
     * @param mixed $value Default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function DataList($content, $value = null, ...$attributes)
    {
        return self::Element(
            is_iterable($content) || is_array($content) ?
            iterator_to_array((function () use ($content, $value, $attributes) {
                $value = is_null($value) ? [] : (is_array($value) ? $value : [Convert::ToString($value)]);
                $f = false;
                if ($f = !$value)
                    yield self::Element(__(self::PopAttribute($attributes, "PlaceHolder", "")), "option", ["value" => "", "selected" => "true"]);
                // else yield self::Element("", "option", ["value" => ""]);
                foreach ($content as $k => $v)
                    if (!$f && ($f = in_array($k, $value)))
                        yield self::Element(__($v ?? ""), "option", ["value" => $k, "selected" => "true"]);
                    else
                        yield self::Element(__($v ?? ""), "option", ["value" => $k]);
            })())
            : Convert::ToString($content, assignFormat: "<option value='{0}'>{1}</option>\r\n"),
            "datalist",
            ["class" => "datalist"],
            $attributes
        );
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
            $content = $reference;//getDomain($reference);
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
            //$action = "load(" . Script::Convert($action) . ", ".Script::Convert(self::PopAttribute($attributes, "Target")??"_self").")";
            return self::Element(__($content), "a", ["class" => "button", "type" => "button", "href" => $action], $attributes);
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
        if (!$content)
            return null;
        if (!$action)
            return self::Element("", "i", ["class" => "icon fa " . (preg_match("/\bfa-/", $content) ? "" : "fa-") . strtolower($content)], $attributes);
        return self::Action(self::Icon($content, null), $action, ["class" => "icon"], $attributes);
    }
    /**
     * The \<ACTION\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array|callable $action The source path or onclick event script, (Use class names with full namespaces in callable references)
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Action($content, $action = null, ...$attributes)
    {
        if (!isEmpty($action) && (!isScript($action) && isUrl($action)))
            $action = "load(" . Script::Convert($action) . ")";
        return self::Element($content, "action", ["class" => "action"], $action ? ["onclick" => $action] : [], $attributes);
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
        $Interaction = self::PopAttribute($attributes, "Interactive");
        $id = self::PopAttribute($attributes, "Id") ?? ("_" . getId());
        if (!isValid($content))
            $content = self::SubmitButton();
        elseif (is_array($content) || is_iterable($content))
            $content = function () use ($content) {
                return join(PHP_EOL, loop($content, function ($f, $k) {
                    if (is_int($k))
                        if (is_array($f))
                            return self::Field(
                                type: pop($f, "Type"),
                                key: pop($f, "Key"),
                                value: pop($f, "Value"),
                                description: pop($f, "Description"),
                                options: pop($f, "Option"),
                                title: pop($f, "Title"),
                                wrapper: pop($f, "Wrapper") ?? [],
                                attributes: [...(pop($f, "Attributes") ?? []), ...$f]
                            );
                        elseif (is_string($f))
                            return $f;
                        else
                            return self::Field($f);
                    else
                        return self::Field(null, $k, $f);
                }));
            };
        return self::Element($content, "form", $action ? ((isScript($action) && !isUrl($action)) ? ["onsubmit" => $action] : ["action" => $action]) : [], ["enctype" => "multipart/form-data", "method" => "get", "Id" => $id, "class" => "form"], $attributes)
            . (empty($Interaction) ? "" : self::Script($Interaction === true ? "handleForm('form#$id');" : $Interaction));
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
            return false;
        if (is_null($type))
            if (isEmpty(object: $value))
                return "text";
            elseif (is_string($value))
                if (isUrl($value))
                    if (isFile($value))
                        return "file";
                    else
                        return "url";
                elseif (preg_match("/<[a-z].*>/i", $value))
                    return null;
                elseif (strlen($value) > 100 || count(explode("\r\n\t\f\v", $value)) > 1)
                    return "textarea";
                else
                    return "text";
            else
                return strtolower(gettype($value));
        elseif (is_string($type)) {
            $type = preg_replace("/(\bnull\s*\|)|(\|\s*null\b)|\?/i", "", $type);
            if (str_contains($type, "|"))
                return "object";
            else
                return strtolower($type);
            //return strtolower(first(preg_split("/\|/", $type)));
        } elseif (is_callable($type) || ($type instanceof \Closure))
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
     * The \<LABEL\> and related input HTML Tag
     * @param object|string|array|callable|\Closure|\stdClass|null $type Can be a datatype or an input type
     *     'Span' => "Inline text display (<span>), non-editable label.",
     *     'Division' => "Block container (<div>) for arbitrary content.",
     *     'Link' => "Anchor (<a>) that navigates to value or url.",
     *     'Action' => "Clickable element that triggers JS action or load.",
     *     'Paragraph' => "Paragraph (<p>) block of text.",
     *     'Label' => "Form label tied to an input (for attribute).",
     *     'Disabled' => "Read-only / disabled input presentation.",
     *     'Input' => "Generic single-line input (text by default).",
     *     'Collection' => "Collection of items (dl/list) or repeated inputs.",
     *     'Text' => "Single-line text input.",
     *     'Texts' => "Multi-line textarea input.",
     *     'Content' => "Rich content editor (textarea + live preview).",
     *     'Script' => "Code/script editor textarea (JS/HTML/CSS).",
     *     'Object' => "JSON/object editor (formatted textarea).",
     *     'Search' => "Search input (type=search).",
     *     'Find' => "Find/combo input with visible text + hidden value (datalist).",
     *     'Color' => "Color picker input (type=color).",
     *     'Dropdown' => "Single-select dropdown (<select>).",
     *     'Dropdowns' => "Multi-select dropdown (<select multiple>).",
     *     'Radio' => "Single radio input.",
     *     'Radios' => "Group of radio buttons (multiple choices).",
     *     'Switch' => "Boolean toggle (visual switch).",
     *     'Switches' => "Multiple boolean toggles.",
     *     'Check' => "Single checkbox input.",
     *     'Checks' => "Multiple checkboxes collection.",
     *     'Integer' => "Integer number input (min/max supported).",
     *     'Short' => "Small-range integer input (bounded short int).",
     *     'Long' => "Numeric input for larger integer values.",
     *     'Range' => "Slider input with min/max (range).",
     *     'Code' => "Numeric code input (digits, optional fixed length).",
     *     'Symbolic' => "Symbolic selector (visual symbols as choices).",
     *     'Float' => "Floating-point number input with precision/step.",
     *     'Tel' => "Telephone input (type=tel).",
     *     'Mask' => "Text input validated by regex pattern / mask.",
     *     'Url' => "URL input (validated, accepts absolute or root paths).",
     *     'Map' => "Map picker (interactive Leaflet map) producing lat,lng.",
     *     'Path' => "File-or-path input (text fallback + file chooser).",
     *     'Address' => "Multi-line address textarea.",
     *     'Calendar' => "Calendar widget with date/time selection + hidden value.",
     *     'Datetime' => "Datetime-local input control.",
     *     'Date' => "Date-only input control.",
     *     'Time' => "Time-only input control.",
     *     'Week' => "Week input control.",
     *     'Month' => "Month input control.",
     *     'Hidden' => "Hidden input field (type=hidden).",
     *     'Secret' => "Password input (type=password).",
     *     'Document' => "Single document file uploader (document formats).",
     *     'Documents' => "Multiple document uploader.",
     *     'Image' => "Single image file uploader (image formats).",
     *     'Images' => "Multiple image uploader.",
     *     'Audio' => "Single audio file uploader.",
     *     'Audios' => "Multiple audio uploader.",
     *     'Video' => "Single video file uploader.",
     *     'Videos' => "Multiple video uploader.",
     *     'File' => "Generic single file uploader.",
     *     'Files' => "Multiple file uploader.",
     *     'Directory' => "Directory selector (webkitdirectory / multiple).",
     *     'Email' => "Email input (type=email), validated format.",
     *     'Submit' => "Form submit button.",
     *     'Reset' => "Form reset button."
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
        $isRequired = self::HasAttribute($attributes, "required");
        // $isDisabled = self::HasAttribute($attributes, "disabled");
        // if ($isDisabled)
        //     $content = self::Division($value, ["class" => "input"], $attributes);
        $id = self::GetAttribute($attributes, "Id") ?? Convert::ToId($key);
        $titleOrKey = $title ?? Convert::ToTitle(Convert::ToString($key));
        $titleTag = ($title === false || !isValid($titleOrKey) ? "" : self::Label(__($titleOrKey) . ($isRequired ? self::Span("*", null, ["class" => "required"]) : ""), $id, ["class" => "title", "placeholder" => self::PopAttribute($attributes, "PlaceHolder")]));
        $descriptionTag = ($description === false || !isValid($description) ? "" : self::Label($description, $id, ["class" => "description", "placeholder" => self::PopAttribute($attributes, "PlaceHolder")]));
        $wrapperAttr = self::PopAttribute($attributes, "Wrapper") ?? [];
        switch ($type) {
            // case null:
            // case 'null':
            case false:
            case 'false':
                return null;
            case 'icon':
            case 'button':
            case 'submitbutton':
            case 'submit':
            case 'resetbutton':
            case 'reset':
            case 'imagesubmit':
            case 'imgsubmit':
                $titleTag = null;
                $wrapper = false;
                break;
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
        if (!is_null($type))
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
            if (!isEmpty($mt)) {
                $pos = strpos($type, "<");
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
        $id = self::PopAttribute($attributes, "Id") ?? Convert::ToId($key);
        $attributes = ["id" => $id, "name" => $key, ...$attributes];
        $options = $options ?? [];
        $dataOptions = function ($options, &$attributes) {
            if ($options && !self::HasAttribute($attributes, "list")) {
                $id = "dl_" . getId();
                $attributes["list"] = $id;
                return self::DataList($options, null, ["Id" => $id]);
            }
            return null;
        };
        $content = null;
        switch ($type) {
            case null:
            case 'null':
                $content = $value;
                break;
            case false:
            case 'false':
                return null;
            //case true:
            case 'true':
                $content = $dataOptions($options, $attributes) . self::Input($key, $value, null, $attributes);
                break;
            case 'br':
            case 'break':
                $content = self::Element(null, "br", $attributes);
                break;
            case 'hr':
            case 'breakline':
                $content = self::Element(null, "hr", $attributes);
                break;
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
            case 'button':
                $content = self::Button($titleOrKey, $value, $attributes);
                break;
            case 'icon':
                $content = self::Icon($titleOrKey, $value, $attributes);
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
            case 'json':
                $content = $dataOptions($options, $attributes) . self::JsonInput($key, Convert::ToString($value), $attributes);
                break;
            case 'mixed':
            case 'object':
                $content = $dataOptions($options, $attributes) . self::ObjectInput($key, Convert::ToString($value), $attributes);
                break;
            case 'countable':
            case 'iterable':
            case 'array':
            case 'collection'://A collection of Base based objects
                $content = self::CollectionInput($key, $value, $options, $attributes);
                break;
            case 'longtext':
            case 'content':
                $content = self::ContentInput($key, $value, $options, $attributes);
                break;
            case 'size':
            case 'font':
            case 'input':
                $content = $dataOptions($options, $attributes) . self::Input($key, $value, null, $attributes);
                break;
            case 'line':
            case 'value':
            case 'text':
            case 'singleline':
            case 'shorttext':
            case 'tinytext':
            case 'varchar':
            case 'char':
                $content = $dataOptions($options, $attributes) . self::TextInput($key, $value, $attributes);
                break;
            case 'address':
                $content = $dataOptions($options, $attributes) . self::AddressInput($key, $value, $attributes);
                break;
            case 'lines':
            case 'texts':
            case 'mediumtext':
            case 'string':
            case 'strings':
            case 'multiline':
            case 'textarea':
                $content = $dataOptions($options, $attributes) . self::TextsInput($key, $value, $attributes);
                break;
            case 'tag':
            case 'combobox':
            case 'find':
            case 'findbox':
                $content = self::FindInput($key, $value, $options, $attributes);
                break;
            case 'type':
            case 'single':
            case 'enum':
            case 'dropdown':
            case 'select':
                $content = self::SelectInput($key, $value, $options, $attributes);
                break;
            case 'types':
            case 'multiple':
            case 'enums':
            case 'dropdowns':
            case 'selects':
                $content = self::SelectsInput($key, $value, $options, ["multiple" => null], $attributes);
                break;
            case 'choice':
            case 'choicebox':
            case 'choicebutton':
            case 'radio':
            case 'radiobox':
            case 'radiobutton':
                $content = self::RadioInput($key, $value, $options, $attributes);
                break;
            case 'choices':
            case 'choiceboxes':
            case 'choicebuttons':
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
            case 'switch':
                $content = $dataOptions($options, $attributes) . self::SwitchInput($key, $value, $attributes);
                break;
            case 'check':
            case 'checkbox':
            case 'checkbutton':
                $content = self::CheckInput($key, $value, $options, $attributes);
                break;
            case 'bools':
            case 'booleans':
            case 'switchs':
            case 'switches':
                $content = self::SwitchesInput($key, $value, $options, $attributes);
                break;
            case 'checks':
            case 'checkboxes':
            case 'checkbuttons':
                $content = self::ChecksInput($key, $value, $options, $attributes);
                break;
            case 'int':
            case 'integer':
                $min = null;
                $max = null;
                if (is_array($options) && count($options)) {
                    $min = min($options);
                    $max = max($options);
                }
                $content = self::IntInput($key, $value, [...(is_null($min) ? [] : ['min' => $min]), ...(is_null($max) ? [] : ['max' => $max])], $attributes);
                break;
            case 'short':
                $min = -255;
                $max = +255;
                if (is_array($options) && count($options)) {
                    $min = min($options);
                    $max = max($options);
                }
                $content = self::IntInput($key, $value, ['min' => $min, 'max' => $max], $attributes);
                break;
            case 'number':
            case 'long':
                $min = null;
                $max = null;
                if (is_array($options) && count($options)) {
                    $min = min($options);
                    $max = max($options);
                }
                $content = self::NumberInput($key, $value, [...(is_null($min) ? [] : ['min' => $min]), ...(is_null($max) ? [] : ['max' => $max])], $attributes);
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
            case 'symbols':
            case 'symbolic':
            case 'symbolicrange':
                $content = self::SymbolicInput($key, $value, $options, $attributes);
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
                $content = $dataOptions($options, $attributes) . self::TelInput($key, $value, $attributes);
                break;
            case 'mask':
                $content = self::MaskInput($key, $value, $options, $attributes);
                break;
            case 'url':
                $content = $dataOptions($options, $attributes) . self::UrlInput($key, $value, $attributes);
                break;
            case 'map':
            case 'location':
                $content = $dataOptions($options, $attributes) . self::MapInput($key, $value, $attributes);
                break;
            case 'path':
                $content = $dataOptions($options, $attributes) . self::PathInput($key, $value, $attributes);
                break;
            case 'calendar':
            case 'calendarinput':
            case 'datetime-local':
            case 'cal':
                $content = $dataOptions($options, $attributes) . self::CalendarInput($key, $value, $attributes);
                break;
            case 'datetime':
                $content = $dataOptions($options, $attributes) . self::DateTimeInput($key, $value, $attributes);
                break;
            case 'date':
                $content = $dataOptions($options, $attributes) . self::DateInput($key, $value, $attributes);
                break;
            case 'time':
                $content = $dataOptions($options, $attributes) . self::TimeInput($key, $value, $attributes);
                break;
            case 'week':
                $content = $dataOptions($options, $attributes) . self::WeekInput($key, $value, $attributes);
                break;
            case 'month':
                $content = $dataOptions($options, $attributes) . self::MonthInput($key, $value, $attributes);
                break;
            case 'hidden':
            case 'hide':
                $content = $dataOptions($options, $attributes) . self::HiddenInput($key, $value, $attributes);
                break;
            case 'secret':
            case 'pass':
            case 'password':
                $content = $dataOptions($options, $attributes) . self::SecretInput($key, $value, $attributes);
                break;
            case 'doc':
            case 'document':
                $content = $dataOptions($options, $attributes) . self::DocumentInput($key, $value, $attributes);
                break;
            case 'img':
            case 'image':
                $content = $dataOptions($options, $attributes) . self::ImageInput($key, $value, $attributes);
                break;
            case 'audio':
                $content = $dataOptions($options, $attributes) . self::AudioInput($key, $value, $attributes);
                break;
            case 'video':
                $content = $dataOptions($options, $attributes) . self::VideoInput($key, $value, $attributes);
                break;
            case 'file':
                $content = $dataOptions($options, $attributes) . self::FileInput($key, $value, $attributes);
                break;
            case 'docs':
            case 'documents':
                $content = $dataOptions($options, $attributes) . self::DocumentInput($key, $value, "multiple", $attributes);
                break;
            case 'imgs':
            case 'images':
                $content = $dataOptions($options, $attributes) . self::ImageInput($key, $value, "multiple", $attributes);
                break;
            case 'audios':
                $content = $dataOptions($options, $attributes) . self::AudioInput($key, $value, "multiple", $attributes);
                break;
            case 'videos':
                $content = $dataOptions($options, $attributes) . self::VideoInput($key, $value, "multiple", $attributes);
                break;
            case 'files':
                $content = $dataOptions($options, $attributes) . self::FilesInput($key, $value, $attributes);
                break;
            case 'dir':
            case 'directory':
            case 'folder':
                $content = $dataOptions($options, $attributes) . self::DirectoryInput($key, $value, $attributes);
                break;
            case 'submitbutton':
            case 'submit':
                $content = $dataOptions($options, $attributes) . self::SubmitButton($key, $value, $attributes);
                break;
            case 'resetbutton':
            case 'reset':
                $content = $dataOptions($options, $attributes) . self::ResetButton($key, $value, $attributes);
                break;
            case 'imagesubmit':
            case 'imgsubmit':
                $content = $dataOptions($options, $attributes) . self::Input($key, $title, 'image', ['src' => Convert::ToString($value)], $attributes);
                break;
            case 'code':
                $content = self::CodeInput($key, $value, $options, $attributes);
                break;
            case 'javascript':
            case 'script':
            case 'scripts':
            case 'js':
            case 'html':
            case 'css':
            case 'codes':
                $content = $dataOptions($options, $attributes) . self::ScriptInput($key, $value, $attributes);
                break;
            case 'mail':
            case 'email':
                $content = $dataOptions($options, $attributes) . self::EmailInput($key, $value, $attributes);
                break;
            case 'color':
                $content = $dataOptions($options, $attributes) . self::ColorInput($key, $value, $attributes);
                break;
            case 'search':
                $content = $dataOptions($options, $attributes) . self::SearchInput($key, $value, $attributes);
                break;
            case 'progress':
            case 'progressbar':
                $content = self::Progress($value, $key, $attributes);
                break;
            default:
                if (is_string($type))
                    if (preg_match("/[^a-z\d\-_:\/\\\]/i", $type))
                        $content = $dataOptions($options, $attributes) . self::Element($value, $type, $attributes);
                    else {
                        $content = $type;
                        $type = null;
                    } else
                    $content = $dataOptions($options, $attributes) . self::Input($key, $value, $type, $attributes);
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
        return self::Element(__($value ?? $key), "button", ["id" => self::PopAttribute($attributes, "Id") ?? Convert::ToId($key), "name" => Convert::ToKey($key), "class" => "button submitbutton", "type" => "submit", "value" => $value], $attributes);
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
        return self::Element(__($value ?? $key), "button", ["id" => self::PopAttribute($attributes, "Id") ?? Convert::ToId($key), "name" => Convert::ToKey($key), "class" => "button resetbutton", "type" => "reset", "value" => $value], $attributes);
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
            $type = self::PopAttribute($attributes, "Type") ?? "text";
        }
        return self::Element("input", [
            "id" => self::PopAttribute($attributes, "Id") ?? Convert::ToId($key),
            "name" => self::PopAttribute($attributes, "name") ?? ($key ? Convert::ToKey($key) : null),
            ...($key ? ["autocomplete" => self::PopAttribute($attributes, "AutoComplete") ?? $key] : []),
            "placeholder" => self::PopAttribute($attributes, "placeholder") ?? Convert::ToTitle($key),
            "type" => $type,
            "value" => $value,
            "class" => "input"
        ], $attributes);
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
        return self::Input($key, $value, "text", ["class" => "textinput"], $attributes);
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
        return self::Element($value ?? "", "textarea", ["id" => self::PopAttribute($attributes, "Id") ?? Convert::ToId($key), "name" => Convert::ToKey($key), "placeholder" => Convert::ToTitle($key), "class" => "input textsinput"], $attributes);
    }
    /**
     * The \<TEXTAREA\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ContentInput($key, $value = null, $options = [], ...$attributes)
    {
        $si = self::PopAttribute($attributes, "SelectedIndex") ?? 0;
        $style = self::PopAttribute($attributes, "Style");
        $class = self::PopAttribute($attributes, "Class");
        $eid = self::PopAttribute($attributes, "Id") ?? Convert::ToId($key);
        $sid = "_" . getId();
        return self::Style("
            .contentinput:has(#$eid){
                background-color: var(--back-color);
                display: flex;
                padding: 0px;
            }
            .contentinput:has(#$eid)>.tab-titles{
                position: sticky;
                top: var(--size-0);
                padding: var(--size-0);
                border: var(--size-0);
                height: fit-content;
                width: min-content;
            }
            .contentinput:has(#$eid)>.tab-contents{
                height: 100%;
                width: 100%;
            }
            .contentinput:has(#$eid)>.tab-contents>.tab-content>.markdowninput{
                display: contents;
                min-height: 100%;
                min-width: 100%;
                min-height: -webkit-fill-available;
                min-width: -webkit-fill-available;
            }
            .contentinput:has(#$eid)>.tab-contents, .contentinput:has(#$eid)>.tab-contents>.tab-content{
                padding: 0px;
                margin: 0px;
            }
            .contentinput:has(#$eid)>.tab-contents, .contentinput:has(#$eid)>.tab-contents>.tab-content .input{
                border: none;
                border-radius: 0px;
            }
            .contentinput:has(#$eid).maximize{
                position: fixed;
                inset: 0;
                height: 100vh;
                width: 100vw;
                max-height: 100%;
                max-width: 100%;
                max-height: -webkit-fill-available;
                max-width: -webkit-fill-available;
                z-index: 999999999;
                overflow: hidden;
            }
            .contentinput:has(#$eid).maximize>.tab-contents{
                overflow: auto;
            }
            .contentinput:has(#$eid).maximize>.tab-contents>.tab-content{
                /*position: absolute;*/
                height: 100%;
                width: 100%;
                min-height: 100%;
                min-width: 100%;
                min-height: -webkit-fill-available;
                min-width: -webkit-fill-available;
            }
            .contentinput:has(#$eid).maximize #$eid{
                min-height: 100%;
                min-width: 100%;
                min-height: -webkit-fill-available;
                min-width: -webkit-fill-available;
            }
        ") . self::Tabs(
                    [
                        self::Action(self::Icon("edit")) =>
                            self::MarkDownInput($key, $value, $options, ["id" => $eid], $attributes),
                        self::Action(
                            self::Icon("eye"),
                            Internal::MakeScript(
                                function ($nArgs) {
                                    return \MiMFa\Library\Struct::Convert($nArgs);
                                },
                                ["\${_('#$eid').val()}"],
                                "function(data,err){ return _('#$sid').html(data??err);}",
                                direct: true,
                                encrypt: false
                            )
                        ) =>
                            self::Division(self::Center(self::Icon("spinner fa-spin")), ["id" => $sid]),
                        self::Action(self::Icon("expand"), "_('.contentinput:has(#$eid)').toggleClass('maximize');") => null
                    ],
                    ["class" => "input contentinput $class", "style" => $style, "SelectedIndex" => $si]
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
     * A \<DIV\> HTML Tag contains a MarkDown Editor
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function MarkDownInput($key, $value = null, $options = [], ...$attributes)
    {
        $id = self::PopAttribute($attributes, "Id") ?? Convert::ToId($key);
        $style = self::PopAttribute($attributes, "Style");
        $class = self::PopAttribute($attributes, "Class");
        return self::Style("
            .markdowninput>#$id{
                width: 100%;
                font-size: 75%;
                overflow:scroll;
                word-wrap: unset;
                margin:0px;
                min-height: -webkit-fill-available;
                min-width: -webkit-fill-available;
            }
            .markdowninput:has(#$id)>.tools{
                background-color: var(--back-color);
                border-bottom: var(--border-1) var(--fore-color);
            }
            .markdowninput:has(#$id)>.tools>.tile>*{
                font-size: var(--size-0);
                padding: calc(var(--size-0) / 4);
                line-height: 1em;
                border: none;
            }
            .markdowninput:has(#$id)>.tools>.tile>.button{
                font-size: var(--size-2);
            }
        ") . self::Script("
            function {$id}_injectInText(prepend = '', append = ''){
                return _('#$id').selectedText((_('#$id').selectedText() || '').split(/\\n/g).map(l=>isEmpty(l)?l:l.replace(/^(\s*)([\s\S]*\S)(\s*)$/g, '\$1'+prepend+'\$2'+append+'\$3')));
            }
            function {$id}_injectOutText(prepend = '', append = ''){
                return _('#$id').selectedText(prepend+_('#$id').selectedText()+append);
            }
            function {$id}_injectInLines(prepend = '', append = ''){
                return _('#$id').selectedLines((_('#$id').selectedLines() || ['']).map(l=>prepend+l+append));
            }
            function {$id}_injectOutLines(prepend = '', append = ''){
                return _('#$id').selectedLines([prepend, ...(_('#$id').selectedLines() || ['']), append]);
            }
            function {$id}_replaceInLines(pattern = /.*/, replacement = ''){
                return _('#$id').selectedLines((_('#$id').selectedLines() || ['']).map(l=>l.replace(pattern, replacement)));
            }
        ") . self::Division(
                    self::Tiles($options ?: [
                        self::Icon("align-left", "{$id}_injectInLines('!{','} @{be align left}')", ["Tooltip" => "Align Left"]),
                        self::Icon("align-center", "{$id}_injectInLines('!{','} @{be align center}')", ["Tooltip" => "Align Center"]),
                        self::Icon("align-right", "{$id}_injectInLines('!{','} @{be align right}')", ["Tooltip" => "Align Right"]),
                        self::Icon("align-justify", "{$id}_injectInLines('!{','} @{be align justify}')", ["Tooltip" => "Align Justify"]),
                        self::Icon("chevron-left", "{$id}_injectInLines('!{','} @{be rtl}')", ["Tooltip" => "Right to Left Direction"]),
                        self::Icon("chevron-right", "{$id}_injectInLines('!{','} @{be ltr}')", ["Tooltip" => "Left to Right Direction"]),
                        self::Icon("list-ul", "{$id}_injectInLines('\\t- ')", ["Tooltip" => "Unordered List"]),
                        self::Icon("list-ol", "{$id}_injectInLines( '\\t+ ')", ["Tooltip" => "Ordered List"]),
                        self::Icon("indent", "{$id}_injectInLines('\\t')", ["Tooltip" => "Indention"]),
                        self::Icon("outdent", "{$id}_replaceInLines(/^\\t/, '')", ["Tooltip" => "Outdention"]),
                        self::Icon("bold", "{$id}_injectInText('*','*')", ["Tooltip" => "Bold"]),
                        self::Icon("italic", "{$id}_injectInText('+','+')", ["Tooltip" => "Italic"]),
                        self::Icon("underline", "{$id}_injectInText('_','_')", ["Tooltip" => "Underline"]),
                        self::Icon("strikethrough", "{$id}_injectInText('-','-')", ["Tooltip" => "Strikethrough"]),
                        self::Icon("superscript", "{$id}_injectInText('^(',')')", ["Tooltip" => "Superscript"]),
                        self::Icon("subscript", "{$id}_injectInText('~(',')')", ["Tooltip" => "Subscript"]),
                        self::Icon("paragraph", "{$id}_injectOutLines('', '')", ["Tooltip" => "Paragraph"]),
                        self::Button("H1", "{$id}_injectInLines('# ')", ["Tooltip" => "Heading 1"]),
                        self::Button("H2", "{$id}_injectInLines('## ')", ["Tooltip" => "Heading 2"]),
                        self::Button("H3", "{$id}_injectInLines('### ')", ["Tooltip" => "Heading 3"]),
                        self::Button("H4", "{$id}_injectInLines('#### ')", ["Tooltip" => "Heading 4"]),
                        self::Button("H5", "{$id}_injectInLines('##### ')", ["Tooltip" => "Heading 5"]),
                        self::Button("H6", "{$id}_injectInLines('###### ')", ["Tooltip" => "Heading 6"]),
                        self::Icon("quote-right", "{$id}_injectOutLines('\"\"\"', '\"\"\"')", ["Tooltip" => "Block Quote"]),
                        self::Icon("file-code", "{$id}_injectOutLines('```', '```')", ["Tooltip" => "Code Block"]),
                        self::Icon("code", "{$id}_injectInLines(' > ')", ["Tooltip" => "Code Script"]),
                        self::Icon("link", "var url=prompt('Enter the URL:','https://'); if(url){ {$id}_injectInText('[',']('+url+')');}", ["Tooltip" => "Link"]),
                        self::Icon("square", "var act=prompt('Enter the JS Action or URL:','https://'); if(act){ {$id}_injectInText('!Button[',']('+act+')');}", ["Tooltip" => "Button"]),
                        self::Icon("image", "var url=prompt('Enter the Media URL or Icon name:','https://'); if(url){ {$id}_injectInText('![',']('+url+')');}", ["Tooltip" => "Image, Video, Audio, Icon, ..."]),
                        self::Icon("minus", "{$id}_injectOutText('\\n---','---\\n')", ["Tooltip" => "Horizontal Rule"]),
                    ], ["class" => "tools"]) .
                    self::Element($value ?? "", "textarea", [
                        "id" => $id,
                        "name" => Convert::ToKey($key),
                        "placeholder" => Convert::ToTitle($key),
                        "class" => "input",
                        "rows" => "10"
                    ], ...$attributes),
                    ["class" => "markdowninput $class", "style" => $style]
                );
    }
    /**
     * The \<TEXTAREA\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function JsonInput($key, $value = null, ...$attributes)
    {
        $jvalue = Convert::ToStatic($value);
        $si = self::PopAttribute($attributes, "SelectedIndex") ?? 0;
        $style = self::PopAttribute($attributes, "Style");
        $class = self::PopAttribute($attributes, "Class");
        $eid = self::PopAttribute($attributes, "Id") ?? Convert::ToId($key);
        $sid = "_" . getId();
        return self::Tabs(
            [
                self::Action(self::Icon("edit")) =>
                    self::Element(isJson($jvalue) ? json_encode(json_decode($jvalue), JSON_PRETTY_PRINT) : $jvalue, "textarea", [
                        "id" => $eid,
                        "name" => Convert::ToKey($key),
                        "placeholder" => Convert::ToTitle($key),
                        "class" => "input",
                        "rows" => "10",
                        "style" => "width: 100%; font-size: 75%; overflow:scroll; word-wrap: unset; direction: ltr; margin:0px;"
                    ], ...$attributes),
                self::Action(
                    self::Icon("eye"),
                    Internal::MakeScript(
                        function ($nArgs) {
                            return \MiMFa\Library\Struct::Convert(\MiMFa\Library\Convert::FromJson($nArgs));
                        },
                        ["\${_('#$eid').val()}"],
                        "function(data,err){ return _('#$sid').html(data??err);}",
                        direct: true,
                        encrypt: false
                    )
                ) =>
                    self::Division(self::Convert(isJson($value) ? Convert::FromJson($value) : $value), ["id" => $sid, "style" => "padding-bottom:var(--size-2);"])
            ],
            ["class" => "jsoninput $class", "style" => $style, "SelectedIndex" => $si]
        );
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
        return self::JsonInput($key, $value, ["class" => "objectinput", "SelectedIndex" => $value ? 1 : 0], $attributes);
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
            $attrs = popValid($attributes, "Attributes", []);
            $disabled = self::HasAttribute($attributes, "Disabled") || self::HasAttribute($attrs, "Disabled");
            $add = $disabled ? false : popValid($attributes, "Add", true);
            //$edit = $disabled?false:popValid($attributes, "edit", true);
            $rem = $disabled ? false : popValid($attributes, "Remove", true);
            $sep = popValid($attributes, "Separator", null);
            $type = self::InputDetector(popValid($attributes, "Type"), popValid($attributes, "Value"));
            $key = popValid($attributes, "Key", $key);
            $options = popValid($attributes, "Options", null);
            if (isEmpty($value))
                $value = [];
            elseif (is_string($value)) {
                $value = is_null($sep) && startsWith($value, "[", "{") ? Convert::FromJson($value) ?? [] : explode($sep ?? "|", trim($value, $sep ?? "|"));
            }

            foreach ($value as $k => $item) {
                $id = self::PopAttribute($attributes, "Id") ?? Convert::ToId($key);
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
                $aid = self::PopAttribute($attributes, "Id") ?? self::PopAttribute($attrs, "Id") ?? Convert::ToId($key) . "_add";
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
    public static function SwitchInput($key, $value = null, ...$attributes)
    {
        $class = "_" . getId();
        return self::Action(
            self::Icon($value ? "toggle-on" : "toggle-off", null, ["class" => $class]),
            "icon_$class = document.querySelector('.icon.$class');
            cb_$class = document.querySelector('.switchinput.$class');
                    if(cb_$class.value == '0'){
                        icon_$class.classList.remove('fa-toggle-off');
                        icon_$class.classList.add('fa-toggle-on');
                        cb_$class.value = 1;
                    } else {
                        icon_$class.classList.remove('fa-toggle-on');
                        icon_$class.classList.add('fa-toggle-off');
                        cb_$class.value = 0;
                    }",
            ["class" => "switchinput"],
            [
                "Class" => self::GetAttribute($attributes, "Class"),
                "Style" => self::PopAttribute($attributes, "Style")
            ]
        ) .
            self::Input($key, $value ? 1 : 0, "hidden", [
                "class" => "switchinput $class",
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
    public static function SwitchesInput($key, $value = null, $options = [], ...$attributes)
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
                    $ops[$k] = $v ?? $value;
        return join(PHP_EOL, loop($ops, function ($v, $k) use ($key, $attributes) {
            return self::Label($k, self::SwitchInput("{$key}[]", $v, ...$attributes), ["class" => "switchesinput"]);
        }));
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
        return self::Input($key, $value, "checkbox", ["class" => "checkinput"], $attributes);
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
                    $ops[$k] = $v ?? $value;
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
        return self::Input($key, $value, "radio", ["class" => "radioinput"], $attributes);
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
                    $ops[$k] = $v ?? $value;
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
                    yield self::Element(__(self::PopAttribute($attributes, "PlaceHolder", "")), "option", ["value" => "", "selected" => "true"]);
                // else yield self::Element("", "option", ["value" => ""]);
                foreach ($options as $k => $v)
                    if (!$f && ($f = in_array($k, $value)))
                        yield self::Element(__($v ?? ""), "option", ["value" => $k, "selected" => "true"]);
                    else
                        yield self::Element(__($v ?? ""), "option", ["value" => $k]);
            })())
            : Convert::ToString($options, assignFormat: "<option value='{0}'>{1}</option>\r\n"),
            "select",
            ["id" => self::PopAttribute($attributes, "Id") ?? Convert::ToId($key), "name" => Convert::ToKey($key), "placeholder" => Convert::ToTitle($key), "class" => "input selectinput"],
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
     * @param mixed $value Default value
     * @param mixed $options Available options
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function FindInput($key, $value = null, $options = [], ...$attributes)
    {
        $ph = self::PopAttribute($attributes, "PlaceHolder") ?? Convert::ToTitle($key);
        $id = self::PopAttribute($attributes, "Id") ?? Convert::ToId($key);
        $name = self::PopAttribute($attributes, "Name");
        $id_fore = "{$id}_fore";
        $id_options = "{$id}_options";
        return
            self::HiddenInput(
                $key,
                $value,
                [
                    "id" => $id,
                    "Name" => $name
                ]
            ) .
            self::Element(
                null,
                "input",
                [
                    "id" => $id_fore,
                    "value" => $value,
                    "placeholder" => $ph,
                    "oninput" => "const val = this.value;
                    const option = Array.from(document.querySelectorAll('#$id_options>option')).find(opt => opt.value === val);
                    if (option) {
                        this.value = option.textContent;
                        document.getElementById('$id').value = option.value;
                    }",
                    "class" => "input findinput",
                    "list" => $id_options
                ],
                $attributes
            ) . self::DataList($options, $value, ["id" => $id_options]);
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
            "class" => "input calendarinput",
            "style" => "
                font-size: 80%;
                display: flex;
                align-items: center;
                justify-content: center;
                align-content: center;
                flex-wrap: wrap;",
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
        $m = preg_replace("/(^\/)|((\/[iugmpxs]*)$)/i", "", $mask ?? "");
        if ($mask && $m == $mask)
            $mask = "/$mask/i";
        return self::Input($key, $value, "text", [
            "class" => "maskinput",
            ...(isPattern($mask ?? "") ? ["onblur" => "this.value = ((this.value.match($mask)??[''])[0]??'')"] : ["pattern" => $m, "title" => "Please complete field by correct format..."])
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
        $name = Convert::ToKey($key);
        $id1 = self::PopAttribute($attributes, "Id") ?? Convert::ToId($key);
        $id2 = Convert::ToId($key);
        $key = Convert::ToKey($key);
        return self::Input($name, null, "file", $attributes, [
            "class" => "pathinput",
            "id" => $id1,
            "accept" => self::PopAttribute($attributes, "Accept") ?? join(", ", \_::$Back->GetAcceptableFormats()),
            "style" => $value ? "display:none;" : "",
            ...($value ? ["name" => ""] : ["name" => "$name"]),
            "oninput" => "
                elem = document.getElementById('$id2');
                if(this.files.length>0){
                    this.setAttribute('name', '$name');
                    elem.removeAttribute('name');
                    elem.setAttribute('disabled', 'disabled');
                } else {
                    this.removeAttribute('name');
                    elem.setAttribute('name', '$name');
                    elem.removeAttribute('disabled');
                }"
        ]) .
            self::MaskInput($name, $value, '[^\<\>\^\`\{\|\}\r\n\t\f\v]*', $attributes, [
                "class" => "pathinput",
                "id" => $id2,
                ...($value ? ["name" => "$name"] : ["name" => ""]),
                "oninput" => "
                elem = document.getElementById('$id1');
                if(!isEmpty(this.value)){
                    this.setAttribute('name', '$name');
                    elem.removeAttribute('name');
                    elem.setAttribute('disabled', 'disabled');
                    elem.style.display='none';
                } else {
                    this.removeAttribute('name');
                    elem.setAttribute('name', '$name');
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
        $name = Convert::ToKey($key);
        $id = self::PopAttribute($attributes, "Id") ?? ("fi_" . getId());
        $d = self::HasAttribute($attributes, "WebkitDirectory");
        $ph = self::PopAttribute($attributes, "PlaceHolder");
        $class = self::GetAttribute($attributes, "Class");
        $style = self::PopAttribute($attributes, "Style");
        $accept = self::PopAttribute($attributes, "Accept") ?? join(", ", \_::$Back->GetAcceptableFormats());
        return self::Style("
        #$id {
            display: flex;
            align-items: center;
            align-content: center;
            justify-content: space-between;
            gap: var(--size-0);
        }
        #$id>*>:is(.browse,.close) {
            aspect-ratio: 1;
            border-radius: var(--radius-3);
        }
        #$id.dragover {
            background-color: #01b64622;
        }
        #$id>input[type='text'] {
            background-color: transparent;
            color: inherit;
            text-align:left;
            width:100%;
            border:none;
            outline:none;
            margin:0;
            padding:0;
        }
        #$id>input[type='text']:hover {
            border:none;
            outline:none;
        }
        #$id>.collection>div{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            align-content: center;
            text-align: center;
            max-width: calc(2 * var(--size-max));
        }
        #$id>.collection>div>.icon{
            opacity: 0.7;
            font-size: var(--size-max);
        }
        #$id>.collection>div>span{
            font-size: var(--size-0);
        }
        ") . self::Division([
                        self::Input($value ? null : $name, null, "file", [
                            "class" => "input",
                            "style" => "display:none!important;",
                            "accept" => $accept,
                            "oninput" => "{$id}_Update(true);"
                        ], $attributes),
                        self::Element(null, "input", [
                            "name" => $value ? $name : null,
                            "type" => "text",
                            "value" => $value,
                            "placeholder" => $ph ?? "'Drag & drop files here' or 'click to select'",
                            "oninput" => "{$id}_Update(false);",
                            "ondblclick" => "document.querySelector('#$id>input[type=\"file\"]').click();"
                        ]),
                        "<div class='collection'></div>",
                        self::Division([
                            self::Icon("close", "document.querySelector('#$id>input[type=\"file\"]').value='';{$id}_Update();", ["class" => "close", "style" => "display:none"]),
                            self::Icon("folder-open", "document.querySelector('#$id>input[type=\"file\"]').click();", ["class" => "browse"])
                        ])
                    ], ["id" => $id, "class" => "input fileinput $class", "style" => $style]) .
            self::Script("
        const {$id}dropzone = document.querySelector('#$id');
        const {$id}fileInput = document.querySelector('#$id>input[type=\"file\"]');
        const {$id}fileList = document.querySelector('#$id>.collection');

        {$id}fileInput.addEventListener('change', function(){
            {$id}_DisplayFiles({$id}fileInput.files);
        });
        {$id}dropzone.addEventListener('dragover', function(e){
            e.preventDefault();
            {$id}dropzone.classList.add('dragover');
        });
        {$id}dropzone.addEventListener('dragleave', function(){
            {$id}dropzone.classList.remove('dragover');
        });
        {$id}dropzone.addEventListener('drop', function(e){
            e.preventDefault();
            {$id}dropzone.classList.remove('dragover');
            const files = e.dataTransfer.files;
            {$id}fileInput.files = files;
            {$id}_DisplayFiles(files);
            {$id}_Update();
        });
        
        function {$id}_Update(isInput = true) {
            elem = document.querySelector('#$id>input[type=\"file\"]');
            telem = document.querySelector('#$id>input[type=\"text\"]');
            felem = document.querySelector('#$id>.collection');
            celem = document.querySelector('#$id>*>.close');
            if(elem.files.length>0){
                elem.setAttribute('name', '$name');
                telem.removeAttribute('name');
                telem.setAttribute('disabled', 'disabled');
                telem.style.display='none';
                celem.style.display='inherit';
            } else {
                elem.removeAttribute('name');
                telem.setAttribute('name', '$name');
                telem.removeAttribute('disabled');
                telem.style.display='inherit';
                celem.style.display='none';
                felem.innerHTML = '';
            }
        }

        function {$id}_DisplayFiles(files) {
            {$id}fileList.innerHTML = '';
            Array.from(files).forEach(file => {
                const item = document.createElement('div');
                item.innerHTML = " . Script::Convert(self::Icon($d ? "folder" : "file")) . "+'<span>'+file.name+'<br><small>('+(file.size / 1024).toFixed(1)+' KB)</small></span>';
                {$id}fileList.appendChild(item);
            });
        }");
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
    public static function DocumentInput($key, $value = null, ...$attributes)
    {
        return self::FileInput($key, $value, ["accept" => join(", ", \_::$Back->AcceptableDocumentFormats)], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function ImageInput($key, $value = null, ...$attributes)
    {
        return self::FileInput($key, $value, ["accept" => join(", ", \_::$Back->AcceptableImageFormats)], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function AudioInput($key, $value = null, ...$attributes)
    {
        return self::FileInput($key, $value, ["accept" => join(", ", \_::$Back->AcceptableAudioFormats)], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function VideoInput($key, $value = null, ...$attributes)
    {
        return self::FileInput($key, $value, ["accept" => join(", ", \_::$Back->AcceptableVideoFormats)], $attributes);
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
     * The \<TEXTAREA\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function AddressInput($key, $value = null, ...$attributes)
    {
        return self::Element($value ?? "", "textarea", ["id" => self::PopAttribute($attributes, "Id") ?? Convert::ToId($key), "name" => Convert::ToKey($key), "placeholder" => Convert::ToTitle($key), "class" => "input addressinput"], $attributes);
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function MapInput($key, $value = null, ...$attributes)
    {
        $id = self::PopAttribute($attributes, "Id") ?? ("_" . getId());
        $name = self::PopAttribute($attributes, "Name");
        $onChange = self::PopAttribute($attributes, "OnChange");
        return self::Script("
        {$id}=null;
        function {$id}_update(){
            const inp = document.getElementById('$id');
            if(inp.value === {$id}) return;
            ll = inp.value?inp.value.split(/[\,;]/g):[0,0];
            latlng = {lat:ll[0]??0,lng:ll[1]??0};
            if(map_marker_input$id) map_marker_input$id.setLatLng(latlng).addTo(map_input$id);
            else map_marker_input$id = L.marker(latlng, {draggable: true}).addTo(map_input$id);
            map_input$id.flyTo(latlng);
            {$id} = inp.value;
        }
        ") . self::Map($value, null, [
                        "id" => "_input$id",
                        "class" => "input",
                        "onchange" => "{$id}=document.getElementById('$id').value = e.latlng.lat+','+e.latlng.lng;" .
                            Convert::ToString($onChange, ";
            ")
                    ], $attributes) .
            self::HiddenInput($key, Convert::ToString($value, ","), [
                "id" => $id,
                "class" => "mapinput",
                "onchange" => "setTimeout(function(){{$id}_update();}, 2000)",
                "name" => $name
            ]) . self::Script("setInterval(function(){{$id}_update();}, 2000)");
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param array $symbols An array of symbols from The minimum key to The maximum key (for example [0=>self::Icon('star')])
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function SymbolicInput($key, $value = null, $symbols = [], ...$attributes)
    {
        $id = self::PopAttribute($attributes, "Id") ?? ("_" . getId());
        $class = self::PopAttribute($attributes, "Class");
        $style = self::PopAttribute($attributes, "Style");
        $disabled = self::PopAttribute($attributes, "Disabled");
        return self::Style("
            #{$id}>.action {
                opacity: 1;
                " . ($disabled ? "" : "cursor: pointer;") . "
                transition: var(--transition-1);
            }
            #{$id}>.action.selected~.action {
                opacity: 0.5;
            }
        ") .
            self::Division(
                [
                    self::HiddenInput($key, $value, ...$attributes),
                    loop($symbols, fn($v, $k) => self::Action($v, null, [
                        "class" => (($value == $k) ? "selected" : ""),
                        ...($disabled ? [] : [
                            "onclick" => "document.querySelector('#{$id}>.hiddeninput').value = '$k';
                        let buttons = document.querySelectorAll('#{$id}>.action');
                        buttons.forEach(btn => { btn.classList.remove('selected'); });
                        this.classList.add('selected');"
                        ])
                    ]))
                ],
                ["class" => "symbolicinput", "id" => $id],
                [
                    "class" => $class,
                    "style" => $style
                ]
            );
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
        $class = self::GetAttribute($attributes, "Class");
        $style = self::PopAttribute($attributes, "Style");
        $id = self::PopAttribute($attributes, "Id") ?? Convert::ToId($key);
        return self::Element(null, "input", [
            "name" => self::PopAttribute($attributes, "Name") ?? ($key ? Convert::ToKey($key) : null),
            "type" => "number",
            "value" => $value,
            "class" => "input",
            "style" => "display:none!important;"
        ], $attributes) .
            self::Element(
                null,
                "input",
                [
                    "id" => $id,
                    "type" => "text",
                    "value" => number_format($value ?? 0, self::PopAttribute($attributes, "Decimals") ?: 0, '.', ','),
                    ...($key ? ["autocomplete" => self::PopAttribute($attributes, "AutoComplete") ?? $key] : []),
                    "placeholder" => self::PopAttribute($attributes, "placeholder") ?? Convert::ToTitle($key),
                    "class" => "input numberinput",
                    "inputmode" => "numeric",
                    "oninput" => "
                    let se = this.selectionEnd+1;
                    this.value = Number(this.previousElementSibling.value = Number((this.value?this.value:'0').match(/[\d\.]+/gi).join(''))).toLocaleString('en-US');
                    this.selectionEnd = se;",
                ],
                [
                    "class" => $class,
                    "style" => $style
                ]
            );
    }
    /**
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function IntInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "number", ["class" => "intinput", "inputmode" => "numeric"], $attributes);
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
        $id = self::PopAttribute($attributes, "Id") ?? ("c_" . getId());
        $countDown = $from >= $to;
        $counter = $id;
        $interval = $id . "_i";
        return self::Element(self::Span($from) . self::Script(
            "var $counter = " . ($countDown ? $from : $to) . ";" .
            "var $interval = setInterval(function(){
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
				elem = document.querySelector(\".counter#$id>.span\");
			    if(elem) elem.innerHTML = {$updateValueFunction}($counter);
                else $counter = $to;
			}, {$period});"
        ), "span", ["id" => $id, "class" => "counter"], $attributes);
    }
    /**
     * A \<SPAN\> HTML Tag contains a timer
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function Timer($from, $to = 0, $action = null, $step = 1, $period = 1000, ...$attributes)
    {
        return self::Counter($from, $to, $action, $step, $period, "(function(sec){ return (new Date(sec * 1000)).toISOString().substring(11, 19);})", ["class" => "timer"], $attributes);
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
                    background-color: var(--back-color-output);
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
            script("
            function {$uniq}_Click(day = null){
                const tso = " . (\_::$Back->TimeStampOffset * 1000) . ";
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
                    if(" . (\_::$Back->DateTimeZone == "UTC" ? "false" : "true") . ")
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
    public static function Tabs($content = [], ...$attributes)
    {
        $content = Convert::ToSequence($content);
        $active = self::PopAttribute($attributes, "SelectedIndex") ?? 0;
        $id = self::PopAttribute($attributes, "Id") ?? ("_" . getId());
        return self::Style("
            #$id>.tab-contents{
                min-height: 100%;
            }
            #$id>.tab-contents>.tab-content{
                height: 100%;
                min-height: -webkit-fill-available;
                width: 100%;
                min-width: -webkit-fill-available;
            }
        ") .
            self::Division(
                self::Division(
                    join("", loop(
                        $content,
                        function ($v, $k, $i) use ($active, $id) {
                            return self::Division(__($k), ["class" => "tab-title" . ($k === $active || $i === $active ? " active" : ""), "onclick" => is_null($v) ? "" : "{$id}_openTab(this, '$id-tab-$i')"]);
                        }
                    )),
                    ["class" => "tab-titles"]
                ) .
                self::Division(
                    join("", loop(
                        $content,
                        function ($v, $k, $i) use ($active, $id) {
                            if ($v)
                                return self::Element($v, "div", ["class" => "tab-content" . ($k === $active || $i === $active ? " active show" : " hide"), "id" => "$id-tab-$i"]);
                        }
                    )),
                    ["class" => "tab-contents"]
                ),
                ["class" => "tabs", "id" => $id],
                $attributes
            ) .
            script("function {$id}_openTab(tab, tabId){
            document.querySelectorAll('#$id>.tab-contents>.tab-content').forEach(content => content.classList.remove('active') & content.classList.remove('show') & content.classList.add('hide'));
            document.querySelectorAll('#$id>.tab-titles>.tab-title').forEach(title => title.classList.remove('active'));
            content = document.getElementById(tabId);
            content.classList.remove('hide');
            content.classList.add('show');
            content.classList.add('active');
            tab.classList.add('active');
        }");
    }
    /**
     * To create a chart
     * @param mixed $type
     * @param mixed $content
     * @param mixed $title
     * @param mixed $description
     * @param mixed $axisXTitle
     * @param mixed $axisYTitle
     * @param mixed $color
     * @param mixed $foreColor
     * @param mixed $backColor
     * @param mixed $font
     * @param mixed $height
     * @param mixed $width
     * @param mixed $axisXBegin
     * @param mixed $axisYBegin
     * @param mixed $axisXInterval
     * @param mixed $axisYInterval
     * @param mixed $options
     * @param array $attributes
     * @return string|null
     */
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
                self::Heading3($title) .
                script(null, asset(\_::$Address->StructDirectory, "Chart/Chart.js")) .
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
		                            labelTextAlign: `" . \_::$Front->Translate->Direction . "`,
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
		                            labelTextAlign: `" . \_::$Front->Translate->Direction . "`,
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
                            chart.response();
                        });"),
                ["id" => $id, "style" => ($height ? "height:$height;" : "") . ($width ? "width:$width;" : ""), "class" => "chart"],
                $attributes
            );
    }
    /**
     * A \<DIV\> HTML Tag contains a map
     * @param mixed $content The location ([lat,lon], "lat,lon") or an array of locations for markers (["Marker1 Icon"=>[lat,lon],"Marker2 Icon"=>[lat,lon]])
     * @param string|null|array|callable $action The source path or onclick event script, (Use class names with full namespaces in callable references)
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function Map($content = [0, 0], $action = null, ...$attributes)
    {
        if (!isEmpty($action) && !isScript($action) && isUrl($action))
            $action = "load(" . Script::Convert($action) . ")";
        if ($action)
            $action = ".on('click', (e)=>{{$action}})";

        $id = self::PopAttribute($attributes, "Id") ?? ("_" . getId());

        style(null, "https://unpkg.com/leaflet/dist/leaflet.css");
        style(null, "https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css");
        script(null, "https://unpkg.com/leaflet/dist/leaflet.js");
        script(null, "https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js");

        // Normalize content
        $markersJS = "";
        $center = [0, 0];
        if (is_string($content)) {
            $center = preg_split("/[,;]/", $content);
            $markersJS .= "map_marker$id = L.marker([{$center[0]}, {$center[1]}]).addTo(map$id);
            map_marker$id$action;";
        } elseif (is_array($content) && isset($content[0]) && isset($content[1]) && is_numeric($content[0])) {
            $center = $content;
            $markersJS .= "map_marker$id = L.marker([{$center[0]}, {$center[1]}]).addTo(map$id);
            map_marker$id$action;";
        } elseif (is_array($content)) {
            $first = reset($content);
            $center = is_array($first) ? $first : preg_split("/[,;]/", $first);
            foreach ($content as $label => $center) {
                if (is_int($label))
                    $markersJS .= "map_marker$id = L.marker([{$center[0]}, {$center[1]}]).addTo(map$id);
                map_marker$id$action;";
                else {
                    $escapedLabel = htmlspecialchars($label, ENT_QUOTES);
                    $markersJS .= "map_marker$id = L.marker([{$center[0]}, {$center[1]}]).addTo(map$id);
                    map_marker$id$action;
                    map_marker$id.bindPopup('$escapedLabel');\n";
                }
            }
        }

        $onchange = self::PopAttribute($attributes, "OnChange");
        $onclick = self::PopAttribute($attributes, "OnClick");

        $changeable = $onchange ? "true" : "false";
        return
            // self::Style(null, "https://unpkg.com/leaflet/dist/leaflet.css") .
            // self::Style(null, "https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css") .
            // self::Script(null, "https://unpkg.com/leaflet/dist/leaflet.js") .
            // self::Script(null, "https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js") .
            self::Style("#$id{height:" . (self::GetAttribute($attributes, "height") ?? "300px") . ";}") .
            self::Division("", ["id" => $id, "class" => "map"], $attributes) .
            script("map$id = L.map('$id').setView([$center[0], $center[1]], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
        //, {attribution: '&copy; OpenStreetMap contributors'}
    ).addTo(map$id);

    map_marker$id = null;
    $markersJS
    map_onClick_$id = null;
    if ($changeable) {
        map$id.on('click', map_onClick_$id = function(e) {
            if (map_marker$id) {
                map_marker$id.setLatLng(e.latlng);
                $onchange;
            } else {
                map_marker$id = L.marker(e.latlng, {draggable: true}).addTo(map$id);
                $onchange;
            }
            document.getElementById('$id').value = map_marker$id;
            $onclick;
        });
    }
    else {
        map$id.on('click', map_onClick_$id = function(e) {
            $onclick;
        });
    }
    L.control.locate({
            position: 'bottomright',  drawCircle: false,
            drawCircle: false,
            follow: true,
            setView: true,
            keepCurrentZoomLevel: false,
            icon: 'icon fa fa-crosshairs',
            iconLoading: 'icon fa fa-spinner fa-spin',
            showPopup: false,
            onLocationOutsideMapBounds:map_onClick_$id,
        }).addTo(map$id);
    ");
    }
    #endregion
}

/**
 * @deprecated - Use MiMFa\Library\Struct insted of this class
 */
class Html extends Struct
{
}