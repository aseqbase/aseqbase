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
     * @param mixed $value
     * @return string
     */
    public static function Convert($value)
    {
        if (!is_null($value)) {
            if (is_string($value)) {
                if (preg_match("/(?<!\\\)\<[^\>]+(?<!\\\)\>/i", $value))
                    return $value;
                else {
                    $patt = '/((?<==)\s*([\"\'])[\w\W]*\2)|((\<(\/?[A-Za-z0-9-_.?:\/\\\]+)[^>\\\]*[^\\\]?\>)([\w\W]*(\<\/\5[^\\\]?\>))?)/i';
                    $value = preg_replace('/\\\(?=[<>])/s', "", $value); // Remove Escapes
                    // Codes
                    $value = preg_replace('/`?``\s?([\s\S]+?)\s?```?/', self::CodeBlock("$1"), $value); // Code blocks
                    $value = code($value, $dic, pattern: $patt);// To keep all previous tags unchanged
                    $value = preg_replace('/`([^`\r\n]+)`/', self::Code("$1"), $value); // Inline code
                    $value = code($value, $dic, pattern: $patt);// To keep all previous tags unchanged
                    $value = preg_replace("/((^[ \t]?>.*\s?)+)/m", self::CodeBlock("$1"), $value); // Blockquotes
                    $value = code($value, $dic, pattern: $patt);// To keep all previous tags unchanged
                    // Quotes
                    $value = preg_replace_callback('/(?<!")"(\S[\r\n"]+?\S)"(?!")/', fn($m) => self::Quote($m[1], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    $value = preg_replace_callback('/"?""\s?([\s\S]+?)\s?"""?/s', fn($m) => self::QuoteBlock($m[1], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value); // Blockquotes
                    // Headings
                    $value = preg_replace_callback("/^\s?[ \t]*\#\s(.*)\s?/im", fn($m) => self::ExternalHeading($m[1], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    $value = preg_replace_callback("/^\s?[ \t]*\#{2}\s(.*)\s?/im", fn($m) => self::SuperHeading($m[1], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    $value = preg_replace_callback("/^\s?[ \t]*\#{3}\s(.*)\s?/im", fn($m) => self::Heading($m[1], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    $value = preg_replace_callback("/^\s?[ \t]*\#{4}\s(.*)\s?/im", fn($m) => self::SubHeading($m[1], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    $value = preg_replace_callback("/^\s?[ \t]*\#{5}\s(.*)\s?/im", fn($m) => self::InternalHeading($m[1], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    $value = preg_replace_callback("/^\s?[ \t]*\#{6}\s(.*)\s?/im", fn($m) => self::Element($m[1], "h6", ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    // Footnotes
                    $value = preg_replace_callback("/\[([a-z0-9_\-]+)\](?!\(|:)/i", fn($m) => self::Span("[$m[1]]", "#fn-$1", ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    $value = preg_replace_callback("/\[\^([^\]]+)\](?!\(|:)/i", fn($m) => self::Super("[$m[1]]", "#fn-$1", ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    $value = preg_replace_callback("/\[~([^\]]+)\](?!\(|:)/i", fn($m) => self::Sub("[$m[1]]", "#fn-$1", ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    $value = preg_replace_callback("/^[ \t]*\[([a-z0-9_\-]+)\]:\s*(.*)$\s?/im", fn($m) => self::Division("[$m[1]] $m[2]", ["class" => "footnote", "id" => "fn-$1"], ($dir = Translate::GetDirection($m[2])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    $value = preg_replace_callback("/^[ \t]*\[([\^~])([^\]]+)\]:\s*(.*)$\s?/im", fn($m) => self::Division("$m[1]$m[2] $m[3]", ["class" => "footnote", "id" => "fn-$2"], ($dir = Translate::GetDirection($m[2])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    // Medias
                    $value = preg_replace("/\!\[([^\]]+)\]\(\s*([^\s]+)\s*\)/i", self::Image("$1", "$2"), $value);
                    $value = preg_replace("/@\[([^\]]+)\]\(\s*([^\s]+)\s*\)/i", self::Media("$1", "$2"), $value);
                    $value = preg_replace_callback("/\[([^\]]+)\]\(\s*([^\s]+)\s*\)/i", fn($m) => self::Link($m[1], $m[2], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    $value = preg_replace("/\b(?<![\"\'`])([a-z]{2,10}\:\/{2}[\/a-z_0-9\?\=\&\#\%\.\(\)\[\]\+\-\!\~\$]+)\b/i", self::Link("$1", "$1"), $value);
                    $value = preg_replace("/\b(?<![\"\'`])([a-z_0-9.\-]+\@[a-z_0-9.\-]+)\b/i", self::Link("$1", "mailto:$1"), $value);

                    // Tables
                    $value = preg_replace_callback(
                        "/((?:^[ \t]*\|.*\|?[ \t]*$\s?)+)/m",
                        function ($matches) {
                            return self::Table(
                                preg_replace_callback(
                                    "/^[ \t]*\|\|?(.*)\|?[ \t]*$\s?/m",
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
                        $value
                    );
                    // Lists
                    $lc = 0;
                    do {
                        $value = preg_replace_callback(
                            "/((^([ \t]*)([^\s\w\d+]|(\+|(\d+))\W?)[ \t]+(.*)$\s?)+)/m",
                            function ($wholematches) {
                                $lines = preg_split("/\r?\n\n?\r?/", trim($wholematches[1], "\r\n"));
                                $linePattern = "/^([\t ]*)([^\s\w\d+]|(\+|(\d+))\W?)[\t ]+(.*)$\s?/m";
                                preg_match($linePattern, reset($lines), $matches);
                                $linePattern = "/^(" . $matches[1] . ")([^\s\w\d+]|(\+|(\d+))\W?)[\t ]+(.*)$\s?/m";
                                $ordered = empty($matches[3]) ? false : true;
                                $list = [];
                                foreach ($lines as $line) {
                                    if (preg_match($linePattern, $line, $ms)) {
                                        $list[] = self::Item($ms[5], empty($ms[4]) ? [] : ["number" => $ms[4]], ($dir = Translate::GetDirection($ms[5])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]);
                                    } else {
                                        $list[count($list) - 1] .= "\n" . $line;
                                    }
                                }
                                return $ordered
                                    ? self::List(join("\n", $list), empty($matches[4]) ? [] : ["start" => $matches[4]])
                                    : self::Items(join("\n", $list));
                            },
                            $value,
                            -1,
                            $lc
                        );
                    } while ($lc);

                    // Texts
                    $value = preg_replace_callback("/\*\*(\S[^\*\r\n\v]+)\*\*/i", fn($m) => self::Strong($m[1], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    $value = preg_replace_callback("/\*([^\*\r\n\v]+)\*/i", fn($m) => self::Bold($m[1], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    $value = preg_replace_callback("/__([^\r\n\v]+)__/i", fn($m) => self::Italic($m[1], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    $value = preg_replace_callback("/~~([^\r\n\v]+)~~/i", fn($m) => self::Strike($m[1], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value);
                    $value = preg_replace_callback("/(?<!\[)\^\(([^\)]+)\)/i", fn($m) => self::Super($m[1], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value); // Superscript
                    $value = preg_replace_callback("/(?<!\[)\~\(([^\)]+)\)/i", fn($m) => self::Sub($m[1], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value); // Superscript
                    $value = preg_replace_callback("/(?<!\[)\^([^\s\-+*\/\/\\\()\[\]{}$#@!~\"'`%^&=+]+)/i", fn($m) => self::Super($m[1], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value); // Superscript
                    $value = preg_replace_callback("/(?<!\[)~([^\s\-+*\/\/\\\()\[\]{}$#@!~\"'`%^&=+]+)/i", fn($m) => self::Sub($m[1], ($dir = Translate::GetDirection($m[1])) == \_::$Back->Translate->Direction ? [] : ["class" => "be $dir"]), $value); // Subscript
                    // Signs
                    $value = preg_replace("/^[ \t]*\-{6,}$\s?/im", self::$BreakLine, $value);
                    $value = preg_replace("/((\r\n)|(\n\r)|(\r?\n\n\r?))/i", self::$Break, trim($value));

                    return ($dir = Translate::GetDirection($value)) == \_::$Back->Translate->Direction ? decode($value, $dic) : self::Division(decode($value, $dic), ["class" => "be $dir"]);
                }
            }
            if (is_subclass_of($value, "\Base"))
                return $value->ToString();
            if (is_countable($value) || is_iterable($value)) {
                $texts = array();
                if (is_numeric(array_key_first($value))) {
                    foreach ($value as $val)
                        $texts[] = self::Item(self::Convert($val));
                    return self::List(join(PHP_EOL, $texts));
                } elseif ($args = getBetween($value, "Arguments", "Item")) {
                    $key = get($value, "Key");
                    $val = get($value, "Value");
                    $ops = get($value, "Options");
                    $type = get($value, "Type");
                    $title = get($value, "Title");
                    return self::Interactor(
                        key: $key,
                        value: $val,
                        type: $type,
                        title: $title,
                        options: $ops,
                        attributes: $args
                    );
                } else {
                    foreach ($value as $key => $val)
                        $texts[] = self::Item(self::Span($key . ": ") . self::Convert($val));
                    return self::Items(join(PHP_EOL, $texts));
                }
            }
            if (is_callable($value) || $value instanceof \Closure)
                return self::Convert($value());
            return self::Division(Convert::ToString($value));
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
        $attrs = self::Attributes($attributes, $attachments, $allowMA);
        if ($isSingle)
            return "<$tagName$attrs data-single/>$attachments";
        else
            return join("", ["<$tagName$attrs>", Convert::ToString($content), "</$tagName>$attachments"]);
    }
    public static function Attributes($attributes, &$attachments = "", $optimization = false)
    {
        $attrs = "";
        if ($attributes) {
            if (is_countable($attributes) || is_iterable($attributes)) {
                $attrdic = [];
                $scripts = [];
                $id = null;
                foreach (Convert::ToIteration($attributes) as $key => $value) {
                    if (isEmpty($key) || is_integer($key))
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
                            $attrs .= " " . self::Attribute($key, __($value, styling: false));
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
                                        if($onpress) $scripts[] = "document.getElementById('$id').addEventListener('keydown', function(event) {if (event.key === '".strToProper(str_replace($onpress, "", $key))."') $value});";
                                        else $scripts[] = "document.getElementById('$id').$key = function(event){{$value}};";
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
            if (str_contains($value, '"'))
                if (str_contains($value, "'")) {
                    $value = str_replace("'", "`", $value);
                    $sp = "'";
                } else
                    $sp = "'";
            else
                $sp = '"';
            return "$key=$sp$value$sp";
        }
    }
    public static function HasAttribute(&$attributes, $key, $default = false): mixed
    {
        return has($attributes, $key) ?? has(first($attributes), $key) ?? $default;
    }
    public static function GetAttribute(&$attributes, $key, $default = null): mixed
    {
        return get($attributes, $key) ?? get(first($attributes), $key) ?? $default;
    }
    public static function GrabAttribute(&$attributes, $key, $default = null): mixed
    {
        return grab($attributes, $key) ?? get(first($attributes), $key) ?? $default;
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
                    self::Element($content ?? "", "body", ["class" => "document"], $attributes)
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
            self::Element($tagName, $attributes) :
            self::Element($content, $tagName, $attributes);
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
        $attrs = self::Attributes($attributes, $attachments, $allowMA);
        self::$TagStack[] = $tagName;
        return "$attachments<$tagName$attrs>";
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

    /**
     * The \<TITLE\> HTML Tag
     * @param string|null $content The window title
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Title($content, ...$attributes)
    {
        return self::Element(__($content, styling: false), "title", $attributes);
    }
    /**
     * The \<META\> HTML Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Description($content, ...$attributes)
    {
        return self::Meta("abstract", $content, $attributes) .
            self::Meta("description", $content, $attributes) .
            self::Meta("twitter:description", $content, $attributes);
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
        else return self::Relation("stylesheet", $source, $attributes);
    }

    public static function Result($content, ...$attributes)
    {
        $id = "_" . getId(true);
        return self::Element(
            __($content, styling: false) . self::Tooltip("Double click to hide"),
            "div",
            [
                "id" => $id,
                "class" => "result",
                "ondblclick" => "this.style.display = 'none'",
                "onmouseenter" => "this.id = ''",
            ],
            $attributes
        ) . self::Script("setTimeout(() => document.getElementById('$id')?.remove(), 5000);")
        ;
    }
    public static function Success($content, ...$attributes)
    {
        return self::Result($content, ["class" => "success"], $attributes);
    }
    public static function Warning($content, ...$attributes)
    {
        return self::Result($content, ["class" => "warning"], $attributes);
    }
    public static function Error($content, ...$attributes)
    {
        if (is_a($content, "Exception") || is_subclass_of($content, "Exception"))
            return self::Result($content->getMessage(), ["class" => "error"], $attributes);
        else
            return self::Result($content, ["class" => "error"], $attributes);
    }

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
                if (is_integer($key))
                    $srcs[] = self::Element("Source", ["src" => $value]);
                else
                    $srcs[] = self::Element("Source", ["src" => $value, "type" => $key]);
        else
            $srcs[] = self::Element("Source", ["src" => $source]);
        return self::Element(join(PHP_EOL, $srcs) . __($content, styling: false), "video", ["class" => "video"], $attributes);
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
                if (is_integer($key))
                    $srcs[] = self::Element("Source", ["src" => $value]);
                else
                    $srcs[] = self::Element("Source", ["src" => $value, "type" => $key]);
        else
            $srcs[] = self::Element("Source", ["src" => $source]);
        return self::Element(join(PHP_EOL, $srcs) . __($content, styling: false), "audio", ["class" => "audio"], $attributes);
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
        if (!isValid($source)) {
            $source = $content;
            $content = null;
        } elseif (is_array($source) && count($attributes) === 0) {
            $attributes = Convert::ToIteration($source, $attributes);
            $source = $content;
            $content = null;
        }
        if (!isValid($source))
            return null;
        if (startsWith($source, "fa ", "fa-"))
            return self::Element(__($content ?? "", styling: false), "i", ["class" => "image " . strtolower($source)], $attributes);
        elseif (isIdentifier($source))
            return self::Element(__($content ?? "", styling: false), "i", ["class" => "image fa fa-" . strtolower($source)], $attributes);
        else
            return self::Element("img", ["src" => $source, "alt" => __($content ?? "", styling: false), "class" => "image"], $attributes);
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
        } elseif (is_array($source) && count($attributes) === 0) {
            $attributes = Convert::ToIteration($source, $attributes);
            $source = $content;
            $content = null;
        }
        if (!isValid($source))
            return null;
        if (isIdentifier($source))
            return self::Element("", "i", ["class" => "media fa fa-" . strtolower($source)], $attributes);
        else
            return self::Element(__($content ?? "", styling: false), "div", ["style" => "background-image: url('" . \MiMFa\Library\Local::GetUrl($source) . "');", "class" => "media"], $attributes);
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
        } elseif (is_array($source) && count($attributes) === 0) {
            $attributes = Convert::ToIteration($source, $attributes);
            $source = $content;
            $content = null;
        }
        if (isUrl($source))
            if (isFormat($source, ".svg"))
                return self::Element($content ?? "", "embed", ["src" => $source, "class" => "embed"], $attributes);
            else
                return self::Element($content ?? "", "iframe", ["src" => $source, "class" => "embed"], $attributes);
        return self::Element($content ?? $source ?? "", "embed", ["class" => "embed"], $attributes);
        //return self::Element($content ?? "", "iframe", ["srcdoc" => str_replace("\"", "&quot;", Convert::ToString($source)), "class" => "embed"], $attributes);
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
            $ls[] = self::Embed(null, $item, $atts, $attributes);
        return Convert::ToString($ls);
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
        $weekDays = ["Sat", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri"];
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
                    background-color: var(--fore-color-2);
                    color: var(--back-color-2);
                }
                .$uniq :is(span.clickable, .media).media{
                    cursor: pointer;
                    color: var(--fore-color-1);
                    padding: 1px 3px;
                    margin: 0px;
				    " . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
                }
                .$uniq :is(span.clickable, .media):hover{
                    cursor: pointer;
                    color: var(--fore-color-2);
				    " . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
                }
                .$uniq :is(div, i, td).clickable{
                    cursor: pointer;
                    border-radius: var(--radius-0);
				    " . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
                }
                .$uniq :is(div, i, td).clickable:hover{
                    outline: var(--border-1) var(--color-4);
				    " . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
                }

                .$uniq :is(.grid$uniq, .select$uniq).shown{
                    position: absolute;
                    min-height: max-content;
                    background-color: var(--back-color-1);
                    color: var(--fore-color-1);
                    z-index: 999;
				    " . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
                }
                .$uniq .grid$uniq.shown{
                    display: flex;
                    align-content: space-around;
                    justify-content: space-around;
                    align-items: stretch;
                    flex-wrap: wrap;
                    flex-direction: row;
				    " . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
                }

                .$uniq :is(.grid$uniq, .select$uniq).hidden{
                    display: none;
				    " . (\MiMFa\Library\Style::UniversalProperty("transition", \_::$Front->Transition(1))) . "
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
                rc = 7;
                pc = 42;
                if(c > 9999999) {
                    rc = 1;
                    pc = 3;
                }
                else if(c > 999999) {
                    rc = 1;
                    pc = 6;
                }
                else if(c > 99999) {
                    rc = 2;
                    pc = 12;
                }
                else if(c > 9999) {
                    rc = 3;
                    pc = 18;
                }
                else if(c > 999) {
                    rc = 4;
                    pc = 24;
                }
                else if(c > 99) {
                    rc = 5;
                    pc = 30;
                }
                else {
                    let nc = Math.abs(max - min) + 1;
                    for(let i = 6; i > 0; i--)
                        if(nc%i == 0) {
                            rc = i;
                            pc = Math.min(nc, i * 6);
                            break;
                        }
                }
                mn = Math.max(min, current - pc + 1);
                mx = Math.min(max, mn + pc - 1);
                obefore = document.querySelector('.$uniq .select$uniq #OptionsBefore$uniq');
                oafter = document.querySelector('.$uniq .select$uniq #OptionsAfter$uniq');
                if(mn <= min) {
                    obefore.setAttribute('class','fa fa-angle-up deactived');
                    obefore.setAttribute('onclick','');
                }
                else {
                    obefore.setAttribute('class','fa fa-angle-up clickable');
                    obefore.setAttribute('onclick','{$uniq}_ShowOptions(`'+destSelector+'`, '+ (current - pc) +', '+ min +', '+ max +')');
                }
                if(mx >= max) {
                    oafter.setAttribute('class','fa fa-angle-down deactived');
                    oafter.setAttribute('onclick','');
                }
                else {
                    oafter.setAttribute('class','fa fa-angle-down clickable');
                    oafter.setAttribute('onclick','{$uniq}_ShowOptions(`'+destSelector+'`, '+ (current + pc) +', '+ min +', '+ max +')');
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
                self::SmallFrame(
                    [
                        [self::Media(" ", "angle-up", ["id" => "OptionsBefore$uniq"])],
                        [self::SmallFrame("", ["class" => "options$uniq"])],
                        [self::Media(" ", "angle-down", ["id" => "OptionsAfter$uniq"])]
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
     * The \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Page($content, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "div", ["class" => "page"], $attributes);
    }
    /**
     * The \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Part($content, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "div", ["class" => "part"], $attributes);
    }
    /**
     * The \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Panel($content, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "div", ["class" => "panel", "style" => "display: inline-block; width: fit-content; height: fit-content;"], $attributes);
    }
    /**
     * The \<HEADER\> HTML Tag
     * @param mixed $content The content of the header Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Header($content, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "header", ["class" => "header"], $attributes);
    }
    /**
     * The \<NAV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Nav($content, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "nav", ["class" => "nav"], $attributes);
    }
    /**
     * The \<MAIN\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Content($content, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "main", ["class" => "content"], $attributes);
    }
    /**
     * The \<SECTION\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Section($content, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "section", ["class" => "section"], $attributes);
    }
    /**
     * The \<ASIDE\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Aside($content, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "aside", ["class" => "aside"], $attributes);
    }
    /**
     * The \<FOOTER\> HTML Tag
     * @param mixed $content The content of the footer Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Footer($content, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "footer", ["class" => "footer"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "container"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "container large-container"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "container medium-container"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "container small-container"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "frame container-fluid"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "frame large-frame container-fluid"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "frame medium-frame container-fluid"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "frame small-frame container-fluid"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "rack row"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "rack large-rack row"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "rack medium-rack row"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "rack small-rack row"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "slot col"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "slot large-slot col-lg"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "slot medium-slot col-md"], $attributes);
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
        return self::Element(Convert::ToString($content), "div", ["class" => "slot small-slot col-sm"], $attributes);
    }
    /**
     * The \<DIV\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Division($content, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "div", ["class" => "division"], $attributes);
    }
    /**
     * The \<CENTER\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Center($content, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "center", ["class" => "center"], $attributes);
    }
    /**
     * The \<PRE\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Pre($content, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "pre", ["class" => "pre", "ondblclick" => 'copy(this.innerText)'], $attributes);
    }
    /**
     * The \<QOUTE\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Quote($content, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "quote", ["class" => "quote", "ondblclick" => 'copy(this.innerText)'], $attributes);
    }
    /**
     * The \<BLOCKQOUTE\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function QuoteBlock($content, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "blockquote", ["class" => "quoteblock", "ondblclick" => 'copy(this.innerText)'], $attributes);
    }
    /**
     * The \<CODE\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Code($content, ...$attributes)
    {
        return self::Element(Convert::ToString($content), "code", ["class" => "code", "ondblclick" => 'copy(this.innerText)'], $attributes);
    }
    /**
     * The \<BLOCKCODE\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function CodeBlock($content, ...$attributes)
    {
        return self::Element(self::Element(Convert::ToString($content), "blockcode", ["ondblclick" => 'copy(this.innerText)']), "pre", ["class" => "codeblock"], $attributes);
    }

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
                    $res[] = self::Element(__($k, styling: false), "dt") . self::Element(__($item, styling: false), "dd");
            $content = $res;
        }
        return self::Element(__($content, styling: false), "dl", ["class" => "collection"], $attributes);
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
                $res[] = self::Item((is_numeric($k) ? "" : self::InlineHeading($k)) . Convert::ToString($item));
            $content = $res;
        }
        return self::Element(__($content, styling: false), "ol", ["class" => "list"], $attributes);
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
                $res[] = self::Item((is_numeric($k) ? "" : self::InlineHeading($k)) . Convert::ToString($item));
            $content = $res;
        }
        return self::Element(__($content, styling: false), "ul", ["class" => "items"], $attributes);
    }
    /**
     * The List Item \<LI\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Item($content, ...$attributes)
    {
        return self::Element(__($content, styling: false), "li", ["class" => "item"], $attributes);
    }

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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h1", ["class" => "externalheading"], $attributes);
        return self::Element(__($content, styling: false), "h1", ["class" => "externalheading"], $attributes);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h2", ["class" => "superheading"], $attributes);
        return self::Element(__($content, styling: false), "h2", ["class" => "superheading"], $attributes);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h3", ["class" => "heading"], $attributes);
        return self::Element(__($content, styling: false), "h3", ["class" => "heading"], $attributes);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h4", ["class" => "subheading"], $attributes);
        return self::Element(__($content, styling: false), "h4", ["class" => "subheading"], $attributes);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h5", ["class" => "internalheading"], $attributes);
        return self::Element(__($content, styling: false), "h5", ["class" => "internalheading"], $attributes);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "h6", ["class" => "inlineheading"], $attributes);
        return self::Element(__($content, styling: false), "h6", ["class" => "inlineheading"], $attributes);
    }

    /**
     * The \<HR\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array|callable $reference The hyper destination tag id, (Use class names with full namespaces in callable references)
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Break($content, $reference = null, ...$attributes)
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
                "background-color: var(--back-color-0);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element($hr . self::Button($content, $reference, $btnattr), "div", $attr, $attributes);
        return self::Element($hr . self::Span($content, null, $btnattr), "div", $attr, $attributes);
    }

    /**
     * The \<P\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array $reference The hyper reference path
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Paragraph($content, $reference = null, ...$attributes)
    {
        if (!is_null($reference))
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "p", ["class" => "paragraph"], $attributes);
        return self::Element(__($content, styling: false), "p", ["class" => "paragraph"], $attributes);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "span", ["class" => "span"], $attributes);
        return self::Element(__($content, styling: false), "span", ["class" => "span"], $attributes);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "strong", ["class" => "strong"], $attributes);
        return self::Element(__($content, styling: false), "strong", ["class" => "strong"], $attributes);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "b", ["class" => "bold"], $attributes);
        return self::Element(__($content, styling: false), "b", ["class" => "bold"], $attributes);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "big", ["class" => "big"], $attributes);
        return self::Element(__($content, styling: false), "big", ["class" => "big"], $attributes);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "i", ["class" => "italic"], $attributes);
        return self::Element(__($content, styling: false), "i", ["class" => "italic"], $attributes);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "strike", ["class" => "strike"], $attributes);
        return self::Element(__($content, styling: false), "strike", ["class" => "strike"], $attributes);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "small", ["class" => "small"], $attributes);
        return self::Element(__($content, styling: false), "small", ["class" => "small"], $attributes);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "sup", ["class" => "super"], $attributes);
        return self::Element(__($content, styling: false), "sup", ["class" => "super"], $attributes);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } else
                return self::Element(self::Link($content, $reference), "sub", ["class" => "sub"], $attributes);
        return self::Element(__($content, styling: false), "sub", ["class" => "sub"], $attributes);
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
            if (is_array($reference) && count($attributes) === 0) {
                $attributes = Convert::ToIteration($reference);
                $reference = null;
            } elseif (isIdentifier($reference))
                return self::Element(__($content, styling: false), "label", ["class" => "label", "for" => $reference], $attributes);
            else
                return self::Element(__($content, styling: false) . __($reference, styling: false), "label", ["class" => "label"], $attributes);
        return self::Element(__($content, styling: false), "label", ["class" => "label"], $attributes);
    }
    /**
     * The \<SPAN\> HTML Tag
     * @param mixed $content Main content of tag tooltip
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Tooltip($content, ...$attributes)
    {
        return self::Element(__($content, styling: false), "div", ["class" => "tooltip"], $attributes);
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
        return self::Element(__($content, styling: false), "data", ["class" => "data", "value" => $value], $attributes);
    }

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
        } elseif (is_array($reference) && count($attributes) === 0) {
            $attributes = Convert::ToIteration($reference);
            $reference = $content;
            $content = null;
        }
        if (is_null($content))
            $content = getDomain($reference);
        return self::Element(__($content, styling: false), "a", ["href" => $reference, "class" => "link"], $attributes);
    }
    /**
     * The \<BUTTON\> or \<A\> HTML Tag
     * @param mixed $content The content of the Tag
     * @param string|null|array|callable $action The source path or onclick event script, (Use class names with full namespaces in callable references)
     * @param mixed $attributes Other custom attributes of the Tag
     * @return string
     */
    public static function Button($content, $action = null, ...$attributes)
    {
        if (!isEmpty($action) && (!isScript($action) && isUrl($action)))
            $action = "load(" . Script::Convert($action) . ")";
        return self::Element(__($content, styling: false), "button", ["class" => "btn button", "type" => "button"], $action ? ["onclick" => $action] : [], $attributes);
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
        if (!isEmpty($action) && (!isScript($action) && isUrl($action)))
            $action = "load(" . Script::Convert($action) . ")";
        return self::Media("", $content, ["class" => "icon"], $action ? ["onclick" => $action] : [], $attributes);
    }

    /**
     * The \<FORM\> HTML Tag
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
                    if (is_integer($k))
                        if (is_array($f))
                            return self::Field(
                                type: grab($f, "Type"),
                                key: grab($f, "Key"),
                                value: grab($f, "Value"),
                                description: grab($f, "Description"),
                                options: grab($f, "Option"),
                                title: grab($f, "Title"),
                                scope: grab($f, "Scope") ?? [],
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
        else
            $content = Convert::ToString($content);
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
        elseif (is_callable($type) || ($type instanceof \Closure))
            return self::InputDetector($type($type, $value), $value);
        elseif (is_object($type) || ($type instanceof \stdClass))
            return self::InputDetector(getValid($type, "Type", null), $value);
        elseif (is_countable($type))
            return "select";
        elseif ($type === true)
            return "text";
        elseif (is_string($type))
            return strtolower($type);
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
    public static function Field($type = null, $key = null, $value = null, $description = null, $title = null, $scope = true, $options = null, ...$attributes)
    {
        if (is_array($description) && count($attributes) === 0) {
            $attributes = Convert::ToIteration($description);
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
                $scope = get($type, "Scope") ?? $scope;
            } elseif (is_countable($type) && !is_null($options)) {
                $description = get($type, "Description") ?? $description;
                $scope = get($type, "Scope") ?? $scope;
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
        //         $isRequired = preg_match("/\brequired\b/i", $attributes);
        //         $isDisabled = preg_match("/\bdisabled\b/i", $attributes);
        //     } elseif (is_countable($attributes))
        //         foreach ($attributes as $k => $v) {
        //             $a = is_string($k) ? $k : (is_string($v) ? $v : "");
        //             $isRequired = $isRequired || preg_match("/\brequired\b/i", $a);
        //             $isDisabled = $isDisabled || preg_match("/\bdisabled\b/i", $a);
        //             if ($isRequired && $isDisabled)
        //                 break;
        //         }
        $isRequired = self::HasAttribute($attributes, "required");
        $isDisabled = self::HasAttribute($attributes, "disabled");
        if ($isDisabled)
            $content = Html::Division($value, ["class" => "input"], $attributes);
        $id = self::GetAttribute($attributes, "Id") ?? Convert::ToId($key);
        $titleOrKey = $title ?? Convert::ToTitle(Convert::ToString($key));
        $titleTag = ($title === false || !isValid($titleOrKey) ? "" : self::Label(__($titleOrKey, styling: false) . ($isRequired ? self::Span("*") : ""), $id, ["class" => "title"]));
        $descriptionTag = ($description === false || !isValid($description) ? "" : self::Label($description, $id, ["class" => "description"]));
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
        if ($scope)
            return self::Element(join('', [$titleTag, $content, $descriptionTag]), 'div', ['class' => 'field']);
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
        if (is_callable(value: $type) || ($type instanceof \Closure)) {
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
            if ($pos = strpos($type, "|") > 0) {
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
            case 'lines':
            case 'texts':
            case 'mediumtext':
            case 'strings':
            case 'multiline':
            case 'textarea':
                $content = self::TextInput($key, $value, $attributes);
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
            case 'singleline':
            case 'text':
            case 'shorttext':
            case 'tinytext':
            case 'varchar':
            case 'char':
                $content = self::ValueInput($key, $value, $attributes);
                break;
            case 'type':
            case 'types':
            case 'enum':
            case 'enums':
            case 'dropdown':
            case 'combobox':
            case 'select':
                $content = self::SelectInput($key, $value, $options, $attributes);
                break;
            case 'radio':
            case 'radiobox':
            case 'radiobutton':
                $content = self::RadioInput($key, $value, $attributes);
                break;
            case 'choice':
            case 'choicebox':
            case 'choicebutton':
                $content = self::RadioInput($key, $value, $attributes);
                break;

            case 'multiplechoice':
            case 'multiplechoicebox':
            case 'multiplechoicebutton':
                $content = self::CheckInput($key, $value, $attributes);
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
            case 'url':
                $content = self::UrlInput($key, $value, $attributes);
                break;
            case 'map':
            case 'location':
            case 'path':
                $content = self::ValueInput($key, $value, $attributes);
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
                $content = self::FileInput($key, $value, 'multiple', $attributes);
                break;
            case 'dir':
            case 'directory':
            case 'folder':
                $content = self::FileInput($key, $value, 'webkitdirectory multiple', $attributes);
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
        if (is_array($value) && count($attributes) === 0) {
            $attributes = Convert::ToIteration($value);
            $value = null;
        }
        return self::Element(__($value ?? $key, styling: false), "button", ["id" => self::GrabAttribute($attributes, "Id") ?? Convert::ToId($key), "name" => Convert::ToKey($key), "class" => "button submitbutton", "type" => "submit"], $attributes);
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
        if (is_array($value) && count($attributes) === 0) {
            $attributes = Convert::ToIteration($value);
            $value = null;
        }
        return self::Element(__($value ?? $key, styling: false), "button", ["id" => self::GrabAttribute($attributes, "Id") ?? Convert::ToId($key), "name" => Convert::ToKey($key), "class" => "button resetbutton", "type" => "reset"], $attributes);
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
        if (is_array($type) && count($attributes) === 0) {
            $attributes = Convert::ToIteration($type);
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
    public static function ValueInput($key, $value = null, ...$attributes)
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
    public static function TextInput($key, $value = null, ...$attributes)
    {
        return self::Element($value ?? "", "textarea", ["id" => self::GrabAttribute($attributes, "Id") ?? Convert::ToId($key), "name" => Convert::ToKey($key), "placeholder" => __(Convert::ToTitle($key), styling: false), "class" => "input textinput"], $attributes);
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
                        "placeholder" => __(Convert::ToTitle($key), styling: false),
                        "class" => "input contentinput",
                        "rows" => "20",
                        // "role"=>"textbox", "contenteditable"=>"true",
                        // "aria-label"=>"comment-box", "aria-multiline"=>"true", "aria-readonly"=>"false",
                        "style" => "font-size: 75%; overflow:scroll; word-wrap: unset;"
                    ], $attributes),
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
        return self::TextInput($key, $value, ["class" => "scriptinput", "rows" => "10", "style" => "font-size: 75%; overflow:scroll; word-wrap: unset; direction: ltr;"], $attributes);
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
        return self::TextInput($key, $value, ["class" => "objectinput", "style" => "font-size: 75%; overflow:scroll; word-wrap: unset;"], $attributes);
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
                    scope: !$rem,
                    key: $key,
                    value: $item,
                    title: false,
                    description: false,
                    options: $options,
                    attributes: [($rem ? ["ondblclick" => "this.remove();"] : null), ...$attributes, ["id" => $id, "name" => (is_numeric($k) ? "{$key}[]" : "{$key}[$k]")], ...$attrs]
                );
            }
            if ($add) {
                $id = Convert::ToId($key) . "_add";
                $oc = "
                        let tag = document.getElementById(`$id`).cloneNode(true);
                        tag.id = `$key" . getId() . "`;
                        tag.name = `{$key}[]`;
                        tag.removeAttribute(`disabled`);
                        tag.setAttribute(`class`,`input`);
                        tag.setAttribute(`style`,``);
                        " . ($rem ? "tag.ondblclick = function(){ this.remove(); };" : "") . "
                        this.parentElement.appendChild(tag);";
                yield self::Field(
                    type: self::InputDetector($type, $sample),
                    key: $key,
                    value: null,
                    title: false,
                    description: self::Icon("plus", $oc),
                    options: $options,
                    attributes: [...$attributes, "onchange" => $oc, "id" => $id, "name" => "", "disabled" => "disabled", "style" => "display: none;", ...$attrs]
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
        $id = "checkinput_" . getId(true);
        return self::Input(null, null, "checkbox", ["class" => "checkinput", ...($value ? ["checked" => "checked"] : []), "name" => null, "onchange" => "document.getElementById('$id').value = this.checked?1:0;"]) .
            self::HiddenInput($key, $value ? "1" : "0", ["class" => "checkinput"], $attributes, ["id" => $id]);
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
     * The \<INPUT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function PathInput($key, $value = null, ...$attributes)
    {
        return self::Input($key, $value, "text", ["class" => "pathinput", "pattern" => '[^\<\>\^\`\{\|\}\r\n\t\f\v]*'], $attributes);
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
        return self::Input($key, $value, "text", [
            "class" => "urlinput",
            "pattern" => "(^\/[^:]*$)|(^https?:\/\/.*$)",
            "title" => "Please enter a valid relative or absolute URL (e.g., /about or https://example.com)"
        ], $attributes);
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
        return self::CollectionInput($key, $value, [
            "Type" => "file",
            "Add" => true,
            "Remove" => true
        ], ["class" => "fileinput", "multiple" => null], $attributes);
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
        return self::Input($key, $value, "file", ["class" => "fileinput", "webkitdirectory", "multiple"], $attributes);
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
        return self::Input($key, $value, "range", ["min" => $min, "max" => $max, "class" => "rangeinput", "oninput" => "document.getElementById('$id').value = this.value;"], $attributes) .
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
    /**
     * The \<SELECT\> HTML Tag
     * @param mixed $key The tag name, id, or placeholder
     * @param mixed $value The tag default value
     * @param mixed $options The tag value
     * @param mixed $attributes The custom attributes of the Tag
     * @return string
     */
    public static function SelectInput($key, $value = null, $options = [], ...$attributes)
    {
        $ma = first($attributes);
        return self::Element(
            is_iterable($options) || is_array($options) ?
            iterator_to_array((function () use ($options, $value) {
                $value = Convert::ToString($value);
                $f = false;
                if ($f = isEmpty($value))
                    yield self::Element("", "option", ["value" => "", "selected" => "true"]);
                // else
                //     yield self::Element("", "option", ["value" => ""]);
                foreach ($options as $k => $v)
                    if (!$f && ($f = ($k == $value)))
                        yield self::Element(__($v ?? "", styling: false), "option", ["value" => $k, "selected" => "true"]);
                    else
                        yield self::Element(__($v ?? "", styling: false), "option", ["value" => $k]);
            })())
            : Convert::ToString($options, assignFormat: "<option value='{0}'>{1}</option>\r\n")
            ,
            "select",
            ["id" => self::GrabAttribute($attributes, "Id") ?? Convert::ToId($key), "name" => Convert::ToKey($key), "placeholder" => __(Convert::ToTitle($key), styling: false), "class" => "input selectinput"],
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
            })())) : Convert::ToString($content),
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
            })())) : __($content, styling: false),
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
            })())) : Convert::ToString($content),
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
        $tagName = strtolower(grab($options, "Type") ?? "") === "head" ? "th" : "td";
        return self::Element(__($content, styling: false), $tagName, $options, ["class" => "table-cell"], $attributes);
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
        $axisXTitle = __($axisXTitle, styling: false);
        $axisYTitle = __($axisYTitle, styling: false);
        $title = __($title, styling: false);
        $description = __($description, styling: false);
        return self::Style(".canvasjs-chart-credit{display:none !important;}") .
            self::Division(
                self::Heading($title) .
                \Res::Script(null, asset(\_::$Address->ScriptDirectory,"CanvasJS.min.js")) .
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
}