<?php
namespace MiMFa\Library;
/**
 * A simple library to convert datatypes to eachother
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#convert See the Library Documentation
 */
class Convert
{
    /**
     * Convert everything through the costum converter
     */
    public static function By($converter, &...$args)
    {
        if (isStatic($converter))
            return $converter;
        if (is_countable($converter) || is_iterable($converter))
            return iterator_to_array((function () use ($converter, &$args) {
                foreach ($converter as $k => $c)
                    yield $k => self::By($c, ...$args);
            })());
        if (is_callable($converter) || $converter instanceof \Closure)
            return $converter(...$args);
        return $converter;
    }

    /**
     * Convert everything to a static format
     * @param mixed $value
     * @return string
     */
    public static function ToStatic($value, $separator = PHP_EOL, $assignFormat = "{0}:{1},", $arrayFormat = "{0}", $default = null)
    {
        if (!is_null($value)) {
            if (is_string($value) || is_numeric($value))
                return $value;
            if ($value instanceof \Base)
                return $value->ToString();
            if (is_countable($value) || is_iterable($value)) {
                $texts = array();
                foreach ($value as $key => $val) {
                    $item = self::ToStatic($val, $separator, $assignFormat);
                    if (is_numeric($key))
                        array_push($texts, $item);
                    elseif (is_countable($val) || is_iterable($val))
                        array_push($texts, str_replace(["{0}", "{1}"], [$key, $item], $assignFormat));
                    else {
                        $sp = "";
                        if (!is_string($item)) $item = self::ToStatic($item, $separator, $assignFormat, $default);
                        if (is_string($item)){
                            if (str_contains($item, '"')) {
                                if (str_contains($item, "'")) {
                                    $item = str_replace("'", "\'", $item);
                                    $sp = "'";
                                } else
                                    $sp = "'";
                            } else
                                $sp = '"';
                            $item = "$sp$item$sp";
                        }
                        array_push($texts, str_replace(["{0}", "{1}"], [$key, $item], $assignFormat));
                    }
                }
                return str_replace("{0}", join($separator, $texts), $arrayFormat);
            }
            if (is_callable($value) || $value instanceof \Closure)
                return self::ToStatic($value(), $separator, $assignFormat);
            if ($value instanceof \DateTime)
                return self::ToShownDateTimeString($value);
            if ($value instanceof \stdClass)
                return self::ToStatic((array)$value);
            return $value;
        }
        return $default;
    }
    /**
     * Convert everything to a simple string format
     * @param mixed $value
     * @return string
     */
    public static function ToString($value, $separator = PHP_EOL, $assignFormat = "{0}:{1},", $arrayFormat = "{0}", $default = null)
    {
        return self::ToStatic($value, $separator, $assignFormat,  $arrayFormat , $default) . "";
    }

    /**
     * Separate a text to multiple paragraphs
     * @param string $text
     * @return array<string>
     */
    public static function ToParagraphs($html, $getText = false)
    {
        if (!$getText)
            $html = strip_tags($html, '<a><input><button><ul><ol><li><select><option>');
        else
            $html = self::ToText($html);
        $out = null;
        preg_match_all(
            "/((<(br|hr|vr|img|input)(\s.)*>)\s*[\w\W]*([\\.\\?\\!\\:]+|(\r\n)+|\n+|[\s\S]$))|((<(\w+)(\s.)*>\s*)[\w\W]*(\s*<\/\9(\s.)*>))|(^[\W\d]+((<(\w+)(\s.)*>\s*)|(\s*<\/\16(\s.)*>)|.)+((\r\n)+|\n+|[\s\S]$))|(((<(\w+)(\s.)*>\s*)|(\s*<\/\25(\s.)*>)|.)+([\\.\\?\\!\\:]+|(\r\n)+|\n+|[\s\S]$))/mi",
            $html,
            $out,
            PREG_PATTERN_ORDER
        );
        return $out[0];
    }

    /**
     * Convert html document to text
     * @param string $html
     * @return string
     */
    public static function ToText($html)
    {
        $html = strip_tags($html??"", '<br><hr><section><content><main><header><footer><p><li><tr><h1><h2><h3><h3><h4><h5><h6>');
        return preg_replace('/(\s*<[^>]*>\s*)+/', PHP_EOL, $html);
    }

    /**
     * Get an Excerpt text of a bigger one
     * @param string $text
     * @return string
     */
    public static function ToExcerpt($text, $from = 0, $maxlength = 100, $excerptedSign = "...", $reverse = false)
    {
        if (!isValid($text))
            return $text;
        $text = trim(self::ToText($text));
        $len = strlen($text);
        if ($len <= $maxlength)
            return $text;
        if ($reverse)
            return $excerptedSign . substr($text, max(0, $len - $from - $maxlength), max(0, $maxlength - strlen($excerptedSign))) . ($from > strlen($excerptedSign) ? $excerptedSign : "");
        else
            return ($from > strlen($excerptedSign) ? $excerptedSign : "") . substr($text, $from, $maxlength - strlen($excerptedSign)) . $excerptedSign;
    }

    /**
     * Convert a text to an Identifier
     * @param string $text
     * @return string
     */
    public static function ToId($text, $random = false)
    {
        return self::ToKey($text, true, '/[^A-Za-z0-9\_\-\$]/')."_".getId($random);
    }
    /**
     * Convert a text to a Key Name
     * @param string $text
     * @return string
     */
    public static function ToKey($text, $normalize = false, $invalid = '/[^\w\[\]\_\-\$]/')
    {
        if (is_null($text))
            return "_" . getId();
        if (!$normalize && !preg_match($invalid, $text))
            return $text;
        return preg_replace($invalid, "", ucwords($text ?? ""));
    }
    /**
     * Convert everything to a suitable value format in string
     * @param mixed $value
     * @return string
     */
    public static function ToValue($value, ...$types)
    {
        if (is_null($value) || $value == "")
            return "null";
        else {
            if (is_string($value) && (count($types) === 0 || in_array("string", $types) || in_array("mixed", $types)))
                return self::ToStringValue($value);
            if (is_countable($value) || is_iterable($value))
                return self::ToArrayValue($value);
            return trim($value . "");
        }
    }
    public static function ToStringValue($value, $quote = '"')
    {
        return $quote . preg_replace('/(?<=^|[^\\\\])' . $quote . '/', "\\" . $quote, $value) . $quote;
    }
    public static function ToArrayValue($value, $indention = "\n\t")
    {
        $texts = array();
        foreach ($value as $key => $val)
            if (is_numeric($key))
                if (is_countable($val) || is_iterable($val))
                    array_push($texts, self::ToArrayValue($val, $indention . "\t"));
                else
                    array_push($texts, self::ToValue($val));
            elseif (is_countable($val) || is_iterable($val))
                array_push($texts, self::ToValue($key) . "=>" . self::ToArrayValue($val, $indention . "\t"));
            else
                array_push($texts, self::ToValue($key) . "=>" . self::ToValue($val));
        return "{$indention}[{$indention}\t" . join(", ", $texts) . "{$indention}]";
    }

    /**
     * Convert a text to normal Title
     * @param string[] $texts Parts of title
     * @return string
     */
    public static function ToTitle()
    {
        $ls = [];
        foreach (self::ToIteration(func_get_args()) as $text)
            if (!is_null($text))
                $ls[] = ucwords(trim(preg_replace('/(?<=[^A-Z\s]{2})([A-Z])/', " $1", preg_replace('/\W/', " ", $text))));
        return join(" - ", array_unique($ls));
    }

    /**
     * Convert a text to a secreted text
     * @param string $text
     * @return string
     */
    public static function ToSecret($text, $secretChar = "*", $showCharsFromFirst = 0, $showCharsFromLast = 0)
    {
        $txts = str_split($text . "");
        $text = "";
        for ($i = 0; $i < $showCharsFromFirst; $i++)
            $text .= $txts[$i];
        for ($i = $showCharsFromFirst; $i < count($txts) - $showCharsFromLast; $i++)
            $text .= $secretChar;
        for ($i = count($txts) - $showCharsFromLast; $i < count($txts); $i++)
            $text .= $txts[$i];
        return $text;
    }

    /**
     * Get items from an input value
     * @param mixed $values
     */
    public static function ToItems($values, $splitPattern = "/\r?\n\r?/")
    {
        if (is_null($values))
            return [];
        elseif (is_string($values))
            return preg_split($splitPattern, $values);
        elseif (is_subclass_of($values, "\Base"))
            return $values->Children;
        elseif (is_callable($values) || $values instanceof \Closure)
            return self::ToItems($values());
        elseif ($values instanceof \Traversable)
            return iterator_to_array($values);
        else
            return $values;
    }
    /**
     * Get items of all input arrays into a generator array
     * @param mixed $arguments
     */
    public static function ToIteration(...$arguments)
    {
        foreach ($arguments as $key => $val) {
            if (is_countable($val) || is_iterable($val))
                if (is_array($val))
                    yield from self::ToIteration(...$val);
                else
                    yield from self::ToIteration(...iterator_to_array($val));
            else
                yield $key => $val;
        }
    }
    /**
     * Get items of all input arrays into one array
     * @param mixed $arguments
     * @return array
     */
    public static function ToSequence(...$arguments)
    {
        return iterator_to_array(self::ToIteration(...$arguments));
    }

    public static function ToJson($obj): string
    {
        if (is_null($obj))
            return "null";
        return json_encode($obj, flags: JSON_OBJECT_AS_ARRAY);
    }
    public static function FromJson($json): null|array
    {
        if (isEmpty($json) || trim(strtolower($json)) === "null")
            return null;
        if (isJson($json))
            return json_decode($json, flags: JSON_OBJECT_AS_ARRAY);
        return [$json];
    }

    public static function ToXml($data, \SimpleXMLElement|null $root = null) {
        if ($root === null) {
            $root = new \SimpleXMLElement('<root/>');
        }
        foreach (self::ToIteration($data) as $key => $value) {
            if (is_array($value) || is_object($value)) {
            self::ToXml($value, $root->addChild(is_numeric($key) ? "item$key" : $key));
            } else {
            $root->addChild(is_numeric($key) ? "item$key" : $key, htmlspecialchars((string)$value));
            }
        }
        return $root;
    }
    public static function FromXml(\SimpleXMLElement|null $data, bool $asArray = false): object|array|null
    {
        libxml_use_internal_errors(true); // suppress XML parsing errors
        try {
                return json_decode(json_encode($data), $asArray); 
        } catch (\Exception $e) {
            return null;
        }
    }
    public static function ToXmlString($data, \SimpleXMLElement|null $root = null) {
        return self::ToXml($data, $root)->asXML();
    }
    public static function FromXmlString($data, bool $asArray = false): object|array|null
    {
        libxml_use_internal_errors(true); // suppress XML parsing errors
        try {
                return json_decode(json_encode(new \SimpleXMLElement(self::ToString($data))), $asArray); 
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function FromFormData($data, &$files = [])
    {
        // Define the boundary from the raw data
        preg_match('/^\s*(-+\S+)/', $data, $matches);
        $blocks = preg_split("/$matches[1]/", $data);
        array_pop($blocks); // remove the last block as it is empty
        $res = array();
        foreach ($blocks as $block) {
            if (empty($block))
                continue;
            // Split header and body
            list($header, $body) = explode("\r\n\r\n", $block, 2);

            // Extract key name
            if (preg_match('/name="([^"]*)"/', $header, $matches)) {
                $key = $matches[1];

                if (strpos($header, 'filename') !== false) {
                    // It's a file
                    if (
                        preg_match('/filename="([^"]*)"/i', $header, $filenameMatches) &&
                        preg_match('/Content-Type:\s*([^\s]+)/i', $header, $typeMatches)
                    ) {
                        $filename = $filenameMatches[1];
                        $type = $typeMatches[1];
                        if (isValid($filename)) {
                            // Create file array structure like $_FILES
                            $files[$key] = array(
                                'name' => $filename,
                                'type' => $type,
                                'tmp_name' => Local::CreatePath($filename, ".tmp", \_::$Aseq->TempDirectory),
                                'error' => UPLOAD_ERR_OK,
                                'size' => strlen($body)
                            );

                            // Write file content to the temporary file
                            file_put_contents($files[$key]['tmp_name'], $body);
                        }
                    }
                } elseif(preg_match("/\[(.*)\]/", $key, $matches)) {// It's an array field
                    $key = preg_find("/^.*(?=\[)/", $key);
                    if(!isset($res[$key])) $res[$key] = array();
                    if($v=getValid($matches,1)) $res[$key][$v] = trim($body);
                    else $res[$key][] = trim($body);
                }
                else $res[$key] = trim($body);// It's a regular form field
            }
        }
        return $res;
    }

    public static function ToCells($svString, $delimiter = ',', $enclosure = '"', $eol = "\n"): array
    {
        if (isEmpty($svString))
            return [];
        $rows = [];
        $length = strlen($svString);
        $index = 0;
        while ($index < $length) {
            $row = [];
            $column = '';
            $inEnclosure = false;
            do {
                $char = $svString[$index++];
                if ($inEnclosure) {
                    if ($char == $enclosure) {
                        if ($index < $length) {
                            $char = $svString[$index];
                            if ($char == $enclosure) {
                                $column .= $char;
                                $index++;
                            } else {
                                $inEnclosure = false;
                            }
                        } else {
                            $inEnclosure = false;
                            $row[] = $column;
                            break;
                        }
                    } else {
                        $column .= $char;
                    }
                } else if ($char == $enclosure) {
                    if ($index < $length) {
                        $char = $svString[$index++];
                        if ($char == $enclosure) {
                            $column .= $char;
                        } else {
                            $inEnclosure = true;
                            $column .= $char;
                        }
                    } else {
                        $row[] = $column;
                        break;
                    }
                } else if ($char == $delimiter) {
                    $row[] = $column;
                    $column = '';
                } else if ($char == "\r") {
                    if ($index < $length) {
                        $char = $svString[$index];
                        if ($char == $eol) {
                            $index++;
                        }
                    }
                    $row[] = $column;
                    break;
                } else if ($char == $eol) {
                    $row[] = $column;
                    break;
                } else {
                    $column .= $char;
                }

                if ($index == $length) {
                    $row[] = $column;
                    break;
                }
            } while ($index < $length);
            $rows[] = $row;
        }
        return $rows;
    }
    public static function FromCells($cells, $delimiter = ',', $enclosure = '"', $eol = "\n"): string
    {
        if (isEmpty($cells))
            return "";
        $fstream = fopen('php://temp', 'r+b');
        foreach ($cells as $fields)
            fputcsv($fstream, $fields, $delimiter, $enclosure, "\\", $eol);
        rewind($fstream);
        $data = rtrim(stream_get_contents($fstream), $eol);
        fclose($fstream);
        return $data;
    }


    /**
     * To convert a string to DateTime or compatible it with a DateTimeZone
     */
    public static function ToDateTime(\DateTime|string|null $dateTime = null, \DateTimeZone|null $dateTimeZone = null)
    {
        return (is_string($dateTime) || is_null($dateTime)) ? new \DateTime($dateTime ?? \_::$Config->CurrentDateTime, $dateTimeZone ?? new \DateTimeZone(\_::$Config->DateTimeZone)) : $dateTime;
    }
    /**
     * Get the DateTime as a String type suitable to save or send to the databases
     */
    public static function ToDateTimeString(string|null $dateTimeFormat = null, $dateTime = null, \DateTimeZone|null $dateTimeZone = null)
    {
        return self::ToDateTime($dateTime, $dateTimeZone)->format($dateTimeFormat ?? "Y-m-d H:i:s");
    }
    /**
     * To convert a string to DateTime or compatible it with a DateTimeZone
     * Considering to the toleranse sat on TimeStampOffset
     * @param \DateTime|string|null $dateTime
     * @param \DateTimeZone|null $dateTimeZone
     */
    public static function ToShownDateTime($dateTime = null, \DateTimeZone|null $dateTimeZone = null, $tolerance = null)
    {
        return (new \DateTime())->setTimestamp(self::ToDateTime($dateTime, $dateTimeZone)->getTimestamp() + $tolerance ?? \_::$Config->TimeStampOffset);
    }
    public static function FromShownDateTime($dateTime = null, \DateTimeZone|null $dateTimeZone = null, $tolerance = null)
    {
        return (new \DateTime())->setTimestamp(self::ToDateTime($dateTime, $dateTimeZone)->getTimestamp() - $tolerance ?? \_::$Config->TimeStampOffset);
    }
    /**
     * Get the DateTime as a String type suitable to show on website
     */
    public static function ToShownDateTimeString($dateTime = null, \DateTimeZone|null $dateTimeZone = null, string|null $dateTimeFormat = null, $tolerance = null)
    {
        return (new \DateTime())->setTimestamp(self::ToDateTime($dateTime, $dateTimeZone)->getTimestamp() + $tolerance ?? \_::$Config->TimeStampOffset)
            ->format($dateTimeFormat ?? \_::$Config->DateTimeFormat);
    }
    public static function FromShownDateTimeString($dateTime = null, \DateTimeZone|null $dateTimeZone = null, string|null $dateTimeFormat = null, $tolerance = null)
    {
        return (new \DateTime())->setTimestamp(self::ToDateTime($dateTime, $dateTimeZone)->getTimestamp() - $tolerance ?? \_::$Config->TimeStampOffset)
            ->format($dateTimeFormat ?? \_::$Config->DateTimeFormat);
    }

    public static function ToSeparatedValuesFile($cells, $path = null, $delimiter = ',', $enclosure = '"', $eol = "\n"): string
    {
        $path = $path ?? Local::CreatePath("table", ".csv", random: false);
        $fstream = fopen($path, 'r+b');
        foreach ($cells as $fields)
            fputcsv($fstream, $fields, $delimiter, $enclosure, "\\", $eol);
        fclose($fstream);
        return $path;
    }
    public static function FromSeparatedValuesFile($path, $delimiter = ',', $enclosure = '"', $eol = "\n"): null|array
    {
        if (file_exists($path) && is_readable($path)) {
            return self::ToCells(file_get_contents($path), $delimiter, $enclosure, $eol);
        }
        return null;
    }

    public static function FromDynamicString($text, &$additionalKeys = array(), $addDefaultKeys = true)
    {
        if ($addDefaultKeys) {
            $email = \_::$Info->ReceiverEmail;
            if (!isset($additionalKeys['$HOSTEMAILLINK']))
                $additionalKeys['$HOSTEMAILLINK'] = Html::Link($email, "mailto:$email");
            if (!isset($additionalKeys['$HOSTEMAIL']))
                $additionalKeys['$HOSTEMAIL'] = $email;
            if (!isset($additionalKeys['$HOSTLINK']))
                $additionalKeys['$HOSTLINK'] = Html::Link(\_::$Site, \_::$Host);
            if (!isset($additionalKeys['$HOST']))
                $additionalKeys['$HOST'] = \_::$Host;
            if (!isset($additionalKeys['$URLLINK']))
                $additionalKeys['$URLLINK'] = Html::Link(\_::$Url, \_::$Url);
            if (!isset($additionalKeys['$URL']))
                $additionalKeys['$URL'] = \_::$Url;
            if (!isValid($additionalKeys, '$SIGNATURE'))
                $additionalKeys['$SIGNATURE'] = \_::$User->TemporarySignature;
            if (!isValid($additionalKeys, '$NAME'))
                $additionalKeys['$NAME'] = \_::$User->TemporaryName;
            $email = \_::$User->TemporaryEmail;
            if (!isset($additionalKeys['$EMAILLINK']))
                $additionalKeys['$EMAILLINK'] = Html::Link($email, "mailto:$email");
            if (!isset($additionalKeys['$EMAIL']))
                    $additionalKeys['$EMAIL'] = $email;
            if (!isset($additionalKeys['$IMAGE']))
                    $additionalKeys['$IMAGE'] = \_::$User->TemporaryImage;

            if (isValid(\_::$User->Id)) {
                $person = \_::$User->Get(takeValid($additionalKeys, '$SIGNATURE'));
                $additionalKeys['$SIGNATURE'] = get($person, "Signature") ?? \_::$User->TemporarySignature;
                $additionalKeys['$NAME'] = get($person, "Name") ?? \_::$User->TemporaryName;
                $email = get($person, "Email") ?? \_::$User->TemporaryEmail;
                $additionalKeys['$EMAILLINK'] = Html::Link($email, "mailto:$email");
                $additionalKeys['$EMAIL'] = $email;
                $additionalKeys['$IMAGE'] = get($person, "Image") ?? \_::$User->TemporaryImage;
                if (!isset($additionalKeys['$IMAGETAG']))
                    $additionalKeys['$IMAGETAG'] = Html::Image($additionalKeys['$SIGNATURE'], get($person, "Image"));
                if (!isset($additionalKeys['$ADDRESS']))
                    $additionalKeys['$ADDRESS'] = get($person, "Address");
                if (!isset($additionalKeys['$CONTACT']))
                    $additionalKeys['$CONTACT'] = get($person, "Contact");
                if (!isset($additionalKeys['$ORGANIZATION']))
                    $additionalKeys['$ORGANIZATION'] = get($person, "Organization");
            }
            uksort($additionalKeys, function ($a, $b) {
                return (strlen($a) == strlen($b)) ? 0 : ((strlen($a) < strlen($b)) ? 1 : -1);
            });
        }
        $text = str_replace(array_keys($additionalKeys), array_values($additionalKeys), $text);
        //foreach ($additionalKeys as $key => $value)
        //    $text = str_replace($key, $value??"", $text);
        return $text;
    }

    public static function FromSwitch($obj, $key, $defultValue = null)
    {
        return (
            is_null($obj) || is_string($obj)
            ? $obj
            : (
                is_array($obj) || $obj instanceof \stdClass
                ? (getBetween($obj, $key, "Default") ?? last($obj))
                : (
                    is_callable($obj) || $obj instanceof \Closure
                    ? self::FromSwitch($obj($key), $key, $defultValue)
                    : $obj
                )
            )
        ) ?? $defultValue;
    }
}