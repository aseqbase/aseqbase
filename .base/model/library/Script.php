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
     * @param mixed $object
     * @param array $args needs for some types like php function
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
    public static function Points($content)
    {
        return join(",", loop($content, function ($row, $i) {
            return self::Point($row, $i); }));
    }
    public static function Parameters($items)
    {
        return isEmpty($items) ? "" : self::Convert($items);
    }
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
                        sendFileRequest(null, 'data=' + encodeURIComponent(base64Data), null, null, null, null, null, $timeout);
                    });
                    reader.readAsArrayBuffer(file);":
                    "//URL.createObjectUrl(file);
                    const reader = new FileReader();
                    reader.addEventListener('load', (event) => {
                        sendFileRequest(null, 'data=' + encodeURIComponent(event.target.result), null, null, null, null, null, $timeout);
                    });
                    reader.readAsText(file);")."
                }
            }
            $(input).trigger('click');
            return false;
        ";
    }

    public static function Alert($message = "")
    {
        return "alert(" . self::Convert(__($message)) . ")";
    }
    public static function Confirm($message = "")
    {
        return "confirm(" . self::Convert(__($message)) . ")";
    }
    public static function Prompt($message = "", $default = null)
    {
        return "prompt(" . self::Convert(__($message)) . ", " . self::Convert(__($default)) . ")";
    }
    public static function Log($message = "")
    {
        return "console.log(" . self::Convert(__($message)) . ")";
    }
    public static function Error($message = "")
    {
        return "console.error(" . self::Convert(__($message)) . ")";
    }
    public static function Warning($message = "")
    {
        return "console.warn(" . self::Convert(__($message)) . ")";
    }
}