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
     * @param mixed $object
     * @return string
     */
    public static function ToStatic($object, ...$args)
    {
        if (isStatic($object))
            return $object;
        if ($object instanceof \Base)
            return $object->ToString();
        if (is_countable($object) || is_iterable($object))
            return self::ToString($object);
        //return self::ToJson($object);
        if (is_callable($object) || $object instanceof \Closure)
            return self::ToStatic($object(...$args));
        if ($object instanceof \DateTime)
            return self::ToShownDateTimeString($object);
        if ($object instanceof \stdClass)
            return self::ToStatic((array) $object, ...$args);
        return $object;
    }

    /**
     * Convert everything to a simple string format
     * @param mixed $object
     * @return string
     */
    public static function ToString($object, $separator = PHP_EOL, $assignFormat = "{0}:{1},", $arrayFormat = "{0}", string $default = "")
    {
        if (is_null($object))
            return $default;
        if (isStatic($object))
            return "$object";
        if ($object instanceof \Base)
            return $object->ToString();
        if (is_countable($object) || is_iterable($object)) {
            $texts = array();
            foreach ($object as $key => $val) {
                $item = self::ToString($val, $separator, $assignFormat, $arrayFormat, $default);
                if (is_numeric($key))
                    array_push($texts, $item);
                elseif (is_countable($val) || is_iterable($val))
                    array_push($texts, str_replace(["{0}", "{1}"], [$key, $item], $assignFormat));
                else {
                    $sp = "";
                    if (!is_string($item))
                        $item = self::ToString($item, $separator, $assignFormat, $arrayFormat, $default);
                    if (is_string($item)) {
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
        return (self::ToStatic($object) ?? $default) . "";
    }

    public static function ToHtml($object, ...$args)
    {
        return Struct::Convert($object, ...$args);
    }
    public static function ToStyle($object, ...$args)
    {
        return Style::Convert($object, ...$args);
    }
    public static function ToScript($object, ...$args)
    {
        return Script::Convert($object, ...$args);
    }

    /**
     * Separate a text to multiple paragraphs
     * @param string $string
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
        $html = strip_tags($html ?? "", '<br><hr><section><content><main><header><footer><p><li><tr><h1><h2><h3><h3><h4><h5><h6>');
        return preg_replace('/(\s*<[^>]*>\s*)+/', PHP_EOL, $html);
    }

    /**
     * Get an Excerpt text of a bigger one
     * @param string $string
     * @return string
     */
    public static function ToExcerpt($string, $from = 0, $maxlength = 100, $excerptedSign = "...", $reverse = false)
    {
        if (!isValid($string))
            return $string;
        $string = trim(self::ToText($string));
        $len = strlen($string);
        if ($len <= $maxlength)
            return $string;
        if ($reverse)
            return $excerptedSign . substr($string, max(0, $len - $from - $maxlength + strlen($excerptedSign)), max(0, $maxlength)) . ($from > strlen($excerptedSign) ? $excerptedSign : "");
        else
            return ($from > strlen($excerptedSign) ? $excerptedSign : "") . substr($string, $from, $maxlength - strlen($excerptedSign)) . $excerptedSign;
    }

    /**
     * Convert a text to an Identifier
     * @param string $string
     * @return string
     */
    public static function ToId($string, $random = false)
    {
        return "_" . self::ToKey($string, true, '/[^A-Za-z0-9\_\-\$]/') . "_" . getId($random);
    }
    /**
     * Convert a text to a Key Name
     * @param string $string
     * @return string
     */
    public static function ToKey($string, $normalize = false, $invalid = '/[^\w\[\]\_\-\$]/')
    {
        if (is_null($string))
            return "_" . getId();
        if (!$normalize && !preg_match($invalid, $string))
            return $string;
        return preg_replace($invalid, "", ucwords($string ?? ""));
    }
    /**
     * Convert everything to a suitable value format in string
     * @param mixed $object
     * @return string
     */
    public static function ToValue($object, ...$types)
    {
        if (is_null($object) || $object == "")
            return "null";
        else {
            if (is_string($object) && (count($types) === 0 || in_array("string", $types) || in_array("mixed", $types)))
                return self::ToStringValue($object);
            if (is_countable($object) || is_iterable($object))
                return self::ToArrayValue($object);
            return trim($object . "");
        }
    }
    public static function ToStringValue($object, $quote = '"')
    {
        return $quote . preg_replace('/(?<=^|[^\\\\])' . $quote . '/', "\\" . $quote, $object) . $quote;
    }
    public static function ToArrayValue($object, $indention = "\n\t")
    {
        $texts = array();
        foreach ($object as $key => $val)
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
        foreach (self::ToIteration(func_get_args()) as $string)
            if (!is_null($string))
                $ls[] = ucwords(trim(preg_replace('/(?<=[^A-Z\s]{2})([A-Z])/', " $1", preg_replace('/\W/', " ", $string))));
        return join(" - ", array_unique($ls));
    }

    /**
     * Convert a text to a secreted text
     * @param string $string
     * @return string
     */
    public static function ToSecret($string, $secretChar = "*", $showCharsFromFirst = 0, $showCharsFromLast = 0)
    {
        $txts = str_split($string . "");
        $string = "";
        for ($i = 0; $i < $showCharsFromFirst; $i++)
            $string .= $txts[$i];
        for ($i = $showCharsFromFirst; $i < count($txts) - $showCharsFromLast; $i++)
            $string .= $secretChar;
        for ($i = count($txts) - $showCharsFromLast; $i < count($txts); $i++)
            $string .= $txts[$i];
        return $string;
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
     * @param mixed $args
     */
    public static function ToIteration(...$args)
    {
        foreach ($args as $key => $val) {
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
     * @param mixed $args
     * @return array
     */
    public static function ToSequence(...$args)
    {
        return iterator_to_array(self::ToIteration(...$args));
    }
    /**
     * To convert a value to Imag, and write text to the image using TrueType fonts
     * @param mixed $object
     * @param int $width To Pixels
     * @param int $height To Pixels
     * @param array $backColor An array of one byte (0-255) values for [Red, Green, Blue, Alpha (A value between 0 and 127. 0 indicates completely opaque while 127 indicates completely transparent)] 
     * @param array $foreColor An array of one byte (0-255) values for [Red, Green, Blue, Alpha (A value between 0 and 127. 0 indicates completely opaque while 127 indicates completely transparent)]  
     * @param int $fontSize Font size in points
     * @param string|null $fontPath A TrueType font (Leave null for default)
     * @return - The image/png data ready to convert to Data URI
     */
    public static function ToImage($object, $width = 100, $height = 100, $backColor = [0, 0, 0, 127], $foreColor = [255, 255, 255, 0], $fontSize = 50, $fontPath = null, $angle = 0, $multipleX = 0.1, $multipleY = 0.75)
    {
        if (!function_exists('imagecreatetruecolor'))
            return null;
        $image = imagecreatetruecolor($width, $height);
        imagesavealpha($image, true);
        $background = imagecolorallocatealpha($image, $backColor[0] ?? 0, $backColor[1] ?? 0, $backColor[2] ?? 0, $backColor[3] ?? 127);
        $textColor = imagecolorallocatealpha($image, $foreColor[0] ?? 255, $foreColor[1] ?? 255, $foreColor[2] ?? 255, $foreColor[3] ?? 0);
        imagefilledrectangle($image, 0, 0, $width, $height, $background);
        $fontPath = $fontPath ?? path("/asset/font/Default.ttf");
        imagettftext($image, $fontSize, $angle, intval($multipleX * $width), intval($multipleY * $height), $textColor, $fontPath, self::ToString($object ?? ""));
        ob_start();
        imagepng($image);
        return ob_get_clean();
    }
    /**
     * To convert a value to its useable Data URI
     * @param mixed $object
     * @param mixed $mime
     */
    public static function ToDataUri($object, $mime)
    {
        return $object ? 'data:' . $mime . ';base64,' . base64_encode(self::ToString($object)) : null;
    }

    public static function ToJson($object, $flags = null)
    {
        if (is_null($object))
            return "null";
        return json_encode($object, flags: $flags ?? (JSON_ERROR_NONE | JSON_OBJECT_AS_ARRAY | JSON_NUMERIC_CHECK | JSON_BIGINT_AS_STRING | JSON_PRESERVE_ZERO_FRACTION));
    }
    public static function FromJson($json, $flags = null): null|array
    {
        if (!isStatic($json))
            return $json;
        if (isEmpty($json) || (trim(strtolower($json)) === "null"))
            return null;
        if (isJson($json))
            return json_decode($json, flags: $flags ?? (JSON_ERROR_NONE | JSON_OBJECT_AS_ARRAY | JSON_NUMERIC_CHECK | JSON_BIGINT_AS_STRING | JSON_PRESERVE_ZERO_FRACTION));
        return preg_split('/\r?\n/', $json . "");
    }

    public static function ToXml($object, \SimpleXMLElement|null $root = null)
    {
        if ($root === null) {
            $root = new \SimpleXMLElement('<root/>');
        }
        foreach (self::ToIteration($object) as $key => $value) {
            if (is_array($value) || is_object($value)) {
                self::ToXml($value, $root->addChild(is_numeric($key) ? "item$key" : $key));
            } else {
                $root->addChild(is_numeric($key) ? "item$key" : $key, htmlspecialchars((string) $value));
            }
        }
        return $root;
    }
    public static function FromXml(\SimpleXMLElement|null $object, bool $asArray = false): object|array|null
    {
        libxml_use_internal_errors(true); // suppress XML parsing errors
        try {
            return json_decode(json_encode($object), $asArray);
        } catch (\Exception $e) {
            return null;
        }
    }
    public static function ToXmlString($object, \SimpleXMLElement|null $root = null)
    {
        return self::ToXml($object, $root)->asXML();
    }
    public static function FromXmlString($object, bool $asArray = false): object|array|null
    {
        libxml_use_internal_errors(true); // suppress XML parsing errors
        try {
            return json_decode(json_encode(new \SimpleXMLElement(self::ToString($object))), $asArray);
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function FromFormData($object, &$files = [])
    {
        // Define the boundary from the raw data
        preg_match('/^\s*(-+\S+)/', $object, $matches);
        $blocks = preg_split("/$matches[1]/", $object);
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
                                'temp_name' => Storage::GenerateAddress($filename, ".tmp", \_::$Address->GlobalTempDirectory),
                                'error' => UPLOAD_ERR_OK,
                                'size' => strlen($body)
                            );

                            // Write file content to the temporary file
                            file_put_contents($files[$key]['temp_name'], $body);
                        }
                    }
                } elseif (preg_match("/\[(.*)\]/", $key, $matches)) {// It's an array field
                    $key = preg_find("/^.*(?=\[)/", $key);
                    if (!isset($res[$key]))
                        $res[$key] = array();
                    if ($v = getValid($matches, 1))
                        $res[$key][$v] = trim($body);
                    else
                        $res[$key][] = trim($body);
                } else
                    $res[$key] = trim($body);// It's a regular form field
            }
        }
        return $res;
    }

    public static function ToFields($string, $delimiter = ',', $enclosure = '"', $eol = "\n")
    {
        return iterator_to_array(self::ToFieldsIterator($string, $delimiter, $enclosure, $eol));
    }
    public static function ToFieldsIterator($string, $delimiter = ',', $enclosure = '"', $eol = "\n")
    {
        return self::CellsToFieldsIterator(self::ToCellsIterator($string, $delimiter, $enclosure, $eol));
    }
    public static function FromFields($fields, $delimiter = ',', $enclosure = '"', $eol = "\n")
    {
        return self::FromCells(self::FieldsToCells($fields), $delimiter, $enclosure, $eol);
    }

    public static function CellsToFields($cells)
    {
        if (!$cells)
            return [];
        $c = 0;
        $fields = [];
        foreach ($cells as $row) {
            if ($c === 0) {
                $keys = $row;
                // $length = count($row);
                // for ($i = 0; $i < $length; $i++)
                //     $keys[$i] = $row[$i];
            } else {
                $cols = [];
                foreach ($row as $i => $value)
                    if (isset($keys[$i]))
                        $cols[$keys[$i]] = $value;
                //else $cols[$keys[$i] = $i] = $value;
                $fields[] = $cols;
            }
            $c++;
        }
        return $fields;
    }
    public static function CellsToFieldsIterator($cells)
    {
        if (!$cells)
            return [];
        $c = 0;
        foreach ($cells as $row) {
            if ($c === 0) {
                $keys = $row;
                // $length = count($row);
                // for ($i = 0; $i < $length; $i++)
                //     $keys[$i] = $row[$i];
            } else {
                $cols = [];
                foreach ($row as $i => $value)
                    if (isset($keys[$i]))
                        $cols[$keys[$i]] = $value;
                //else $cols[$keys[$i] = $i] = $value;
                yield $cols;
            }
            $c++;
        }
    }
    /**
     * Convert Key Value parameters to a flat Table
     * @param mixed $fields A key value pairs array
     * @return array<array|string>
     */
    public static function FieldsToCells($fields)
    {
        if (!$fields)
            return [];
        $cells = [""];
        $dic = [];
        foreach ($fields as $value) {
            foreach ($value as $k => $v)
                $dic[$k] = $v;
            $cells[] = loop($dic, function ($v) {
                return $v;
            });
            foreach ($dic as $k => $v)
                $dic[$k] = null;
        }
        $cells[0] = loop($dic, function ($v, $k) {
            return $k;
        });
        return $cells;
    }
    public static function ToCells($string, $delimiter = ',', $enclosure = '"', $eol = "\n")
    {
        return iterator_to_array(self::ToCellsIterator($string, $delimiter, $enclosure, $eol));
    }
    public static function ToCellsIterator($string, $delimiter = ',', $enclosure = '"', $eol = "\n")
    {
        if (isEmpty($string))
            return [];
        $length = strlen($string);
        $index = 0;
        $normalize = function ($v) {
            if (($v . "") === "")
                return null;
            else if (preg_match("/^\d+$/", $v))
                return intval($v);
            else if (preg_match("/^\d*.?\d+$/", $v))
                return floatval($v);
            else
                return $v;
        };
        while ($index < $length) {
            $row = [];
            $column = '';
            $inEnclosure = false;
            do {
                $char = $string[$index++];
                if ($inEnclosure) {
                    if ($char == $enclosure) {
                        if ($index < $length) {
                            $char = $string[$index];
                            if ($char == $enclosure) {
                                $column .= $char;
                                $index++;
                            } else {
                                $inEnclosure = false;
                            }
                        } else {
                            $inEnclosure = false;
                            $row[] = $normalize($column);
                            break;
                        }
                    } else {
                        $column .= $char;
                    }
                } else if ($char == $enclosure) {
                    if ($index < $length) {
                        $char = $string[$index++];
                        if ($char == $enclosure) {
                            $column .= $char;
                        } else {
                            $inEnclosure = true;
                            $column .= $char;
                        }
                    } else {
                        $row[] = $normalize($column);
                        break;
                    }
                } else if ($char == $delimiter) {
                    $row[] = $normalize($column);
                    $column = '';
                } else if ($char == "\r") {
                    if ($index < $length) {
                        $char = $string[$index];
                        if ($char == $eol) {
                            $index++;
                        }
                    }
                    $row[] = $normalize($column);
                    break;
                } else if ($char == $eol) {
                    $row[] = $normalize($column);
                    break;
                } else {
                    $column .= $char;
                }

                if ($index == $length) {
                    $row[] = $normalize($column);
                    break;
                }
            } while ($index < $length);
            yield $row;
        }
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

    public static function ToCompactNumber($number, $divisors = ['T' => 1000000000000, 'G' => 1000000000, 'M' => 1000000, 'K' => 1000])
    {
        foreach ($divisors as $suffix => $divisor) {
            if ($number >= $divisor)
                return round($number / $divisor, 1) . $suffix;
        }
        return $number;
    }

    /**
     * To convert a string to DateTime or compatible it with a DateTimeZone
     */
    public static function ToDateTime(\DateTime|string|null $dateTime = null, \DateTimeZone|null $dateTimeZone = null)
    {
        return (is_string($dateTime) || is_null($dateTime)) ? new \DateTime($dateTime ?? \_::$Front->CurrentDateTime, $dateTimeZone ?? new \DateTimeZone(\_::$Front->DateTimeZone)) : $dateTime;
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
        return (new \DateTime())->setTimestamp(self::ToDateTime($dateTime, $dateTimeZone)->getTimestamp() + ($tolerance ?? \_::$Front->TimeStampOffset));
    }
    public static function FromShownDateTime($dateTime = null, \DateTimeZone|null $dateTimeZone = null, $tolerance = null)
    {
        return (new \DateTime())->setTimestamp(self::ToDateTime($dateTime, $dateTimeZone)->getTimestamp() - ($tolerance ?? \_::$Front->TimeStampOffset));
    }
    /**
     * Get the DateTime as a String type suitable to show on website
     */
    public static function ToShownDateTimeString($dateTime = null, \DateTimeZone|null $dateTimeZone = null, string|null $dateTimeFormat = null, $tolerance = null)
    {
        return (new \DateTime())->setTimestamp(self::ToDateTime($dateTime, $dateTimeZone)->getTimestamp() + ($tolerance ?? \_::$Front->TimeStampOffset))
            ->format($dateTimeFormat ?? \_::$Front->DateTimeFormat);
    }
    public static function FromShownDateTimeString($dateTime = null, \DateTimeZone|null $dateTimeZone = null, string|null $dateTimeFormat = null, $tolerance = null)
    {
        return (new \DateTime())->setTimestamp(self::ToDateTime($dateTime, $dateTimeZone)->getTimestamp() - ($tolerance ?? \_::$Front->TimeStampOffset))
            ->format($dateTimeFormat ?? \_::$Front->DateTimeFormat);
    }

    public static function ToSeparatedValuesFile($cells, $path = null, $delimiter = ',', $enclosure = '"', $eol = "\n"): string
    {
        $path = $path ?? Storage::GenerateAddress("table", ".csv", random: false);
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

    public static function FromDynamicString($string, &$additionalKeys = array(), $addDefaultKeys = true)
    {
        if ($addDefaultKeys) {
            $email = \_::$Front->ReceiverEmail;
            if (!isset($additionalKeys['$HOSTEMAILLINK']))
                $additionalKeys['$HOSTEMAILLINK'] = Struct::Link($email, "mailto:$email");
            if (!isset($additionalKeys['$HOSTEMAIL']))
                $additionalKeys['$HOSTEMAIL'] = $email;
            if (!isset($additionalKeys['$HOSTLINK']))
                $additionalKeys['$HOSTLINK'] = Struct::Link(\_::$Address->UrlHost, \_::$Address->UrlOrigin);
            if (!isset($additionalKeys['$HOST']))
                $additionalKeys['$HOST'] = \_::$Address->UrlOrigin;
            if (!isset($additionalKeys['$URLLINK']))
                $additionalKeys['$URLLINK'] = Struct::Link(\_::$Address->Url, \_::$Address->Url);
            if (!isset($additionalKeys['$URL']))
                $additionalKeys['$URL'] = \_::$Address->Url;
            if (!isValid($additionalKeys, '$SIGNATURE'))
                $additionalKeys['$SIGNATURE'] = \_::$User->TemporarySignature;
            if (!isValid($additionalKeys, '$NAME'))
                $additionalKeys['$NAME'] = \_::$User->TemporaryName;
            $email = \_::$User->TemporaryEmail;
            if (!isset($additionalKeys['$EMAILLINK']))
                $additionalKeys['$EMAILLINK'] = Struct::Link($email, "mailto:$email");
            if (!isset($additionalKeys['$EMAIL']))
                $additionalKeys['$EMAIL'] = $email;
            if (!isset($additionalKeys['$IMAGE']))
                $additionalKeys['$IMAGE'] = \_::$User->TemporaryImage;

            if (isValid(\_::$User->Id)) {
                $person = \_::$User->Get(takeValid($additionalKeys, '$SIGNATURE'));
                $additionalKeys['$SIGNATURE'] = get($person, "Signature") ?? \_::$User->TemporarySignature;
                $additionalKeys['$NAME'] = get($person, "Name") ?? \_::$User->TemporaryName;
                $email = get($person, "Email") ?? \_::$User->TemporaryEmail;
                $additionalKeys['$EMAILLINK'] = Struct::Link($email, "mailto:$email");
                $additionalKeys['$EMAIL'] = $email;
                $additionalKeys['$IMAGE'] = get($person, "Image") ?? \_::$User->TemporaryImage;
                if (!isset($additionalKeys['$IMAGETAG']))
                    $additionalKeys['$IMAGETAG'] = Struct::Image($additionalKeys['$SIGNATURE'], get($person, "Image"));
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
        $string = str_replace(array_keys($additionalKeys), array_values($additionalKeys), $string);
        //foreach ($additionalKeys as $key => $value)
        //    $string = str_replace($key, $value??"", $string);
        return $string;
    }

    public static function FromSwitch($object, $key, $defultValue = null)
    {
        return (
            is_null($object) || is_string($object)
            ? $object
            : (
                is_array($object) || $object instanceof \stdClass
                ? (getBetween($object, $key, "Default") ?? last($object))
                : (
                    is_callable($object) || $object instanceof \Closure
                    ? self::FromSwitch($object($key), $key, $defultValue)
                    : $object
                )
            )
        ) ?? $defultValue;
    }
}