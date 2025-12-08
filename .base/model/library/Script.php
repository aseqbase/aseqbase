<?php namespace MiMFa\Library;
/**
 * A simple library to create default and standard Script parts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#Script See the Library Documentation
 */
class Script
{
    /**
     * To convert everything to scripts
     * @param mixed $object Send a PHP function to give its RPC scripts
     * @param array $args Needs for some $object like PHP function, It encrypts them for more protection
     */
    public static function Convert($object, ...$args)
    {
        if (is_null($object))
            return "null";
        else {
            if (is_string($object)) {
                if($res = preg_find('/(?<=^\\$\{)[\W\w]+(?=\}$)/', $object)) return $res;
                $sp = "`";
                $object = str_replace("\\", "\\\\", $object);
                // if(preg_match("/\n|(\\$\\{[\w\W]*\\})/",$object))
                //     $object = str_replace(["`", '$'], ["\\`", '\\$'], $object);
                if(preg_match("/\n|(\\$\{[\w\W]*\})/",$object))
                    $object = str_replace("`", "\\`", $object);
                else
                    $object = str_replace($sp = "\"", "\\\"", $object);
                $object = str_replace("</script>", "<\/script>", $object);
                return "$sp$object$sp";
            }
            if (is_numeric($object))
                return $object;
            if (is_subclass_of($object, "\Base"))
                return $object->ToString();
            if (is_array($object) && count($object) > 0 && !is_int(array_key_first($object))) 
                return join("", ["{", join(", ", loop($object, function ($v, $k) use($args){ return  Convert::ToStatic($k).":".self::Convert($v, ...$args);})), "}"]);
            if (is_countable($object) || is_iterable($object)) 
                return join("", ["[", join(", ", loop($object, function ($o) use($args){ return self::Convert($o, ...$args);})), "]"]);
            if (is_callable($object) || $object instanceof \Closure)
                return Internal::MakeScript($object, $args, direct:true);
            return json_encode($object, flags: JSON_OBJECT_AS_ARRAY);
        }
    }

    /**
     * To convert a point to scripts
     * @param mixed $row The point content
     * @param int $index The index of the point
     * @return string The script part
     */
    public static function Point($row, $index = 0)
    {
        $cc = is_array($row) ? count($row) : 0;
        if ($cc > 3) {
            $res = [];
            $res[] = "{ ";
            $res[] = "label: " . self::Parameters($row[0]);
            $res[] = ",x:" . self::Numbers($row[1]);
            $res[] = ",y:" . self::Numbers($row[2]);
            $len = count($row);
            for ($i = 3; $i < $len; $i++)
                $res[] = ",{$row[$i]}";
            $res[] = " }";
            return join("", $res);
        } else if ($cc > 2)
            return join("", [
                "{ label: ",
                self::Parameters(first($row)),
                ",x:",
                self::Numbers($row[1]),
                ",y:",
                self::Numbers(last($row)),
                "}"
            ]);
        else if ($cc > 1)
            return join("", [
                "{x:",
                self::Numbers(first($row)),
                ",y:",
                self::Numbers(last($row)),
                "}"
            ]);
        else {
            return join("", [
                "{x:",
                self::Numbers($index),
                ",y:",
                self::Numbers($row),
                "}"
            ]);
        }
    }
    /**
     * To convert multiple points to scripts
     * @param mixed $content The points content
     * @return string The script part
     */
    public static function Points($content)
    {
        return join(",", loop($content, function ($row, $i) {
            return self::Point($row, $i); }));
    }
    /**
     * To convert parameters to scripts
     * @param mixed $items The items to convert
     * @return string The script part
     */
    public static function Parameters($items)
    {
        return isEmpty($items) ? "" : self::Convert($items);
    }
    /**
     * To convert numbers or array of numbers to scripts
     * @param mixed $items A number or an array of numbers
     * @return string The script part
     */
    public static function Numbers($items)
    {
        $isarr = is_iterable($items);
        $val = $isarr ?
            (preg_replace(
                "/,\s+,/m",
                ", 0,",
                (preg_replace(
                    "/,\s*$/m",
                    ", 0",
                    (preg_replace(
                        "/^\s*,\s+/m",
                        "0, ",
                        join(", ", is_array($items) ? $items : Convert::ToSequence($items))
                    ))
                ))
            )) : $items;
        return isEmpty($val) ? "0" : ($isarr ? "[" + $val + "]" : $val);
    }
    /**
     * To import a file from the client device
     * @param mixed $formats The acceptable formats like ".jpg,.png" or "image/*"
     * @param bool $multiple Allow to select multiple files
     * @param bool $binary To read the file as binary data
     * @param int $timeout The timeout for uploading the file in milliseconds
     * @return string The script part
     */
    public static function ImportFile($formats = null, $multiple = false, $binary = false, $timeout = 60000){
        return "            var input = document.createElement('input');
            input.setAttribute('Type' , 'file');
            input.setAttribute('accept', ".self::Convert($formats).");
            input.onchange = evt => {
                ".($multiple?"const files = input.files;
                for(const file of files)":"const [file] = input.files;")."
                if (file) {
                    ".($binary?"const reader = new FileReader();
                    reader.addEventListener('load', (event) => {
                        const binaryData = event.target.result;
                        // send binary data as a base64 string
                        const base64Data = btoa(String.fromCharCode(...new Uint8Array(binaryData)));
                        sendFile(null, 'data=' + encodeURIComponent(base64Data), null, null, null, null, null, $timeout);
                    });
                    reader.readAsArrayBuffer(file);":
                    "//URL.createObjectUrl(file);
                    const reader = new FileReader();
                    reader.addEventListener('load', (event) => {
                        sendFile(null, 'data=' + encodeURIComponent(event.target.result), null, null, null, null, null, $timeout);
                    });
                    reader.readAsText(file);")."
                }
            }
            $(input).trigger('click');
            return false;
        ";
    }
    /**
     * Show an alert dialog
     * @param mixed $message
     * @return string
     */
    public static function Alert($message = "")
    {
        return "alert(" . self::Convert(__($message)) . ")";
    }
    /**
     * Show a confirmation dialog
     * @param mixed $message
     * @return string
     */
    public static function Confirm($message = "")
    {
        return "confirm(" . self::Convert(__($message)) . ")";
    }
    /**
     * To prompt a message and get user input
     * @param mixed $message The message
     * @param mixed $default The default value
     * @return string The script part
     */
    public static function Prompt($message = "", $default = null)
    {
        return "prompt(" . self::Convert(__($message)) . ", " . self::Convert(__($default)) . ")";
    }
    /**
     * Show message on the client side console
     * @param mixed $message The data that is ready to print
     * @param mixed $type The type of the message: log, info, warn, error
     * @return string The script part
     */
    public static function Log($message = "", $type = null)
    {
        switch($type = strtolower($type??"")){
            case "message":
                $type = "info";
                break;
            case "warning":
                $type = "warn";
                break;
            case "":
            case "success":
                $type = "log";
                break;
        }
        return "console.$type(" . self::Convert(__($message)) . ")";
    }
}