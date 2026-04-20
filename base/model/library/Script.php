<?php
namespace MiMFa\Library;
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
                if ($res = preg_find('/(?<=^\\$\{)[\W\w]+(?=\}$)/', $object))
                    return $res;
                $sp = "`";
                $object = str_replace("\\", "\\\\", $object);
                // if(preg_match("/\n|(\\$\\{[\w\W]*\\})/",$object))
                //     $object = str_replace(["`", '$'], ["\\`", '\\$'], $object);
                if (preg_match("/[\n\r]|(\\$\{[\w\W]*\})/", $object))
                    $object = str_replace("`", "\\`", $object);
                else
                    $object = str_replace($sp = "\"", "\\\"", $object);
                $object = str_replace("</script>", "<\/script>", $object);
                return "$sp$object$sp";
            }
            if (is_bool($object))
                return $object ? "true" : "false";
            if (is_numeric($object))
                return $object;
            if (is_subclass_of($object, "\Base"))
                return $object->ToString();
            if (is_array($object) && count($object) > 0 && !is_int(array_key_first($object)))
                return join("", [
                    "{",
                    join(", ", loop($object, function ($v, $k) use ($args) {
                        return Convert::ToStatic($k) . ":" . self::Convert($v, ...$args);
                    })),
                    "}"
                ]);
            if (is_countable($object) || is_iterable($object))
                return join("", [
                    "[",
                    join(", ", loop($object, function ($o) use ($args) {
                        return self::Convert($o, ...$args);
                    })),
                    "]"
                ]);
            if (is_callable($object) || $object instanceof \Closure)
                return Internal::MakeScript($object, $args, direct: true);
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
            return self::Point($row, $i);
        }));
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

    public static function Send($method = null, $target = null, $data = null, $selector = 'body', $successScript = null, $errorScript = null, $messageScript = null, $progressScript = null, $timeout = null, $async = true)
    {
        return "send(" .
            self::Convert($method) . "," .
            self::Convert($target) . "," .
            self::Convert($data) . "," .
            self::Convert($selector) . "," .
            (is_string($successScript) ? $successScript : (is_null($successScript) ? "null" : ("(data,err)=>" . self::Convert($successScript)))) . "," .
            (is_string($errorScript) ? $errorScript : (is_null($errorScript) ? "null" : ("(data,err)=>" . self::Convert($errorScript)))) . "," .
            (is_string($messageScript) ? $messageScript : (is_null($messageScript) ? "null" : ("(data,err)=>" . self::Convert($messageScript)))) . "," .
            (is_string($progressScript) ? $progressScript : (is_null($progressScript) ? "null" : ("(data,err)=>" . self::Convert($progressScript)))) . "," .
            self::Convert($timeout) . "," .
            self::Convert($async)
            . ")";
    }

    /**
     * To upload a file from the client device
     * @param mixed $extensions The acceptable formats like ".jpg,.png" or "image/*"
     * @param bool $multiple Allow to select multiple files
     * @param int $timeout The timeout for uploading the file in milliseconds
     * @return string The script part
     */
    public static function Upload($target = null, $extensions = null, $minSize = null, $maxSize = null, $successScript = null, $errorScript = null, $messageScript = null, $progressScript = null, $timeout = null, $multiple = false, $method = "FILE")
    {
        return "
            var input = document.createElement('input');
            input.setAttribute('Type' , 'file');
            input.setAttribute('accept', " . self::Convert($extensions ?? \_::$Back->GetAcceptableFormats()) . ");
            input.setAttribute('multiple', " . ($multiple ? "true" : "false") . ");
            input.onchange = evt => {
                try{
                    number = 1;
                    count = 1;
                    " . ($multiple ? "const files = input.files;
                    count = files.length;
                    for(const file of files)" : "const [file] = input.files;") . "
                        if (file) {
                            if(file.size < " . self::Convert($minSize = $minSize ?? \_::$Back->MinimumFileSize) . ") {
                                " . self::Alert("The 'file size' is 'smaller than' " . Convert::ToCompactNumber($minSize) . "B!") . ";
                                return;
                            }
                            if(file.size > " . self::Convert($maxSize = $maxSize ?? \_::$Back->MaximumFileSize) . ") {
                                " . self::Alert("The 'file size' is 'bigger than' " . Convert::ToCompactNumber($maxSize) . "B!") . ";
                                return;
                            }
                            const reader = new FileReader();
                            data = event.target.result;
                            data = new Uint8Array(data);
                            // for(let j = 0; j < data.length; j++)
                            //     data[j] = String.fromCharCode(data[j]);
                            data = String.fromCharCode(...data);
                            data = btoa(data);
                            reader.addEventListener('load', (event) => {
                            " . self::Send($method, $target, [
                        "name" => "\${file.name}",
                        "size" => "\${file.size}",
                        "count" => "\${count}",
                        "number" => "\${number}",
                        "data" => "\${encodeURIComponent(data)}"
                    ], null, $successScript, $errorScript, $messageScript, $progressScript, $timeout, false) . ";
                            });
                            reader.readAsArrayBuffer(file);
                            number++;
                        }
                } catch(ex) { " . self::Alert("\${ex}") . "; }
            }
            _(input).trigger('click');
            return false;
        ";
    }
    public static function Download($target = null, $name = null, $type = null)
    {
        if (isUrl($target))
            return "load('" . self::Convert($target) . "', true)";
        else
            return self::Convert(function ($target, $name, $type) {
                upload($target, $name, $type);
            }, $target, $name, $type);
    }

    /**
     * To upload any types of files from the client device
     * This method sends files by a chunks-based algorithm
     * @param mixed $extensions The acceptable extensions like [".jpg",".png",...]
     * @param bool $multiple Allow to select multiple files
     * @param int|null $timeout The timeout for uploading the file in milliseconds
     * @param int|null $speed The chunk size in bytes, default is 100KB
     * @return string The script part
     */
    public static function UploadStream($target = null, $extensions = null, $minSize = null, $maxSize = null, $successScript = null, $errorScript = null, $messageScript = null, $progressScript = null, $timeout = 999999999, $speed = null, $multiple = false, $method = "STREAM")
    {
        return "
            var input = document.createElement('input');
            input.setAttribute('Type' , 'file');
            " . (count($extensions = $extensions ?? \_::$Back->GetAcceptableFormats()) > 0 ? "input.setAttribute('accept', " . self::Convert($extensions) . ");" : "") . "
            input.setAttribute('multiple', " . ($multiple ? "true" : "false") . ");
            input.onchange = evt => {
                try{
                    number = 1;
                    count = 1;
                    " . ($multiple ? "const files = input.files;
                    count = files.length;
                    for(const file of files)" : "const [file] = input.files;") . "
                        if (file) {
                            if(file.size < " . self::Convert($minSize = $minSize ?? \_::$Back->MinimumFileSize) . ") {
                                " . self::Alert("The 'file size' is 'smaller than' " . Convert::ToCompactNumber($minSize) . "B!") . ";
                                return;
                            }
                            if(file.size > " . self::Convert($maxSize = $maxSize ?? \_::$Back->MaximumFileSize) . ") {
                                " . self::Alert("The 'file size' is 'bigger than' " . Convert::ToCompactNumber($maxSize) . "B!") . ";
                                return;
                            }
                            var chunksSize = " . ($speed ?? 100000) . ";
                            var currentChunk = 0;
                            const reader = new FileReader();
                            reader.addEventListener('load', (event) => {
                                const data = event.target.result;
                                const totalChunks = Math.ceil(data.byteLength / chunksSize);
                                for(let i = 0; i < totalChunks; i++) {
                                    chunkData = data.slice(i * chunksSize, (i + 1) * chunksSize);
                                    chunkData = new Uint8Array(chunkData);
                                    // for(let j = 0; j < chunkData.length; j++)
                                    //     chunkData[j] = String.fromCharCode(chunkData[j]);
                                    chunkData = String.fromCharCode(...chunkData);
                                    //chunkData = encrypt(chunkData, " . self::Convert(getClientCode()) . ");
                                    chunkData = btoa(chunkData);
                                    " . self::Send($method, $target, [
                        "name" => "\${file.name}",
                        "size" => "\${file.size}",
                        "chunk" => "\${i}",
                        "total" => "\${totalChunks}",
                        "number" => "\${number}",
                        "count" => "\${count}",
                        "data" => "\${encodeURIComponent(chunkData)}"
                    ], null, $successScript, $errorScript, $messageScript, $progressScript, $timeout, true) . ";
                                }
                            });
                            reader.readAsArrayBuffer(file);
                            number++;
                        }
                } catch(ex) { " . self::Alert("\${ex}") . "; }
            }
            _(input).trigger('click');
            return false;
        ";
    }

    public static function SetMemo($key = "", $value = null, $expires = 0, $path = "/", $secure = false)
    {
        return "setMemo(" . self::Convert($key) . ", " . self::Convert($value) . ", " . self::Convert($expires) . ", " . self::Convert($path) . ", " . self::Convert($secure) . ")";
    }
    public static function GetMemo($key = "")
    {
        return "getMemo(" . self::Convert($key) . ")";
    }

    /**
     * Show an message dialog
     * @param mixed $message
     * @return string
     */
    public static function Message($message = "")
    {
        return "Struct.message(" . self::Convert($message) . ")";
    }
    /**
     * Show an success dialog
     * @param mixed $message
     * @return string
     */
    public static function Success($message = "")
    {
        return "Struct.success(" . self::Convert($message) . ")";
    }
    /**
     * Show an warning dialog
     * @param mixed $message
     * @return string
     */
    public static function Warning($message = "")
    {
        return "Struct.warning(" . self::Convert($message) . ")";
    }
    /**
     * Show an error dialog
     * @param mixed $message
     * @return string
     */
    public static function Error($message = "")
    {
        return "Struct.error(" . self::Convert($message) . ")";
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
        return "prompt(" . self::Convert(__($message)) . ($default?", " . self::Convert($default):null) . ")";
    }
    /**
     * Show message on the client side console
     * @param mixed $message The data that is ready to print
     * @param mixed $type The type of the message: log, info, warn, error
     * @return string The script part
     */
    public static function Log($message = "", $type = null)
    {
        switch ($type = strtolower($type ?? "")) {
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
        return "console.$type(" . self::Convert($message) . ")";
    }
    /**
     * To copy on clipboard
     * @param mixed $text
     * @return string
     */
    public static function Copy($text = "")
    {
        return "copy(" . self::Convert($text) . ")";
    }
    /**
     * To cut on clipboard
     * @param mixed $text
     * @return string
     */
    public static function Cut($text = "")
    {
        return "cut(" . self::Convert($text) . ")";
    }
    /**
     * To paste from the clipboard
     * @return string
     */
    public static function Paste()
    {
        return "paste()";
    }
    /**
     * To open print preview
     * @return string
     */
    public static function Print()
    {
        return "window.print()";
    }

    public static function Load($url = null, $target = null)
    {
        return "load(" . self::Convert($url) . "," . self::Convert($target) . ")";
    }
    public static function Reload()
    {
        return "reload()";
    }
    public static function Locate($url = null)
    {
        return "locate(" . self::Convert($url) . ")";
    }
    public static function Relocate($url = null)
    {
        return "relocate(" . self::Convert($url) . ")";
    }

    /**
     * @field value
     * @category Template
     * @var string
     */
    public $DefaultSourceSelector = "body";
    /**
     * @field value
     * @category Template
     * @var string
     */
    public $DefaultDestinationSelector = "body";

    /**
     * RPC (Remote Procedure Call) Request
     * @param mixed $script The front JS codes to collect requested thing from the client side 
     * @param mixed $handler The call back handler
     * @example request('_("body").html', function(selectedHtml)=>{ //do somework })
     */
    public static function Action($script = null, $handler = null, &$wrapperId = null)
    {
        $callbackScript = "(data,err)=>_('body').after(data??err)";
        $progressScript = "null";
        $timeout = 60000;
        $start = Internal::MakeStartScript(true);
        $end = Internal::MakeEndScript(true);
        $wrapperId = $wrapperId ?? ("S_" . getID(true));
        if (isStatic($handler))
            return "$start(" . $callbackScript . ")(" .
                self::Convert($handler) . ",$script);try{_('#$wrapperId').remove();}catch{}$end";
        else
            return $handler ? $start .
                'sendInternal(null,{"' . Internal::Set($handler) . '":JSON.stringify(' . $script . ")}, null,$callbackScript,$callbackScript, null,$progressScript,$timeout);try{_('#$wrapperId').remove();}catch{}$end"
                : $script;
    }
    /**
     * Make a script to
     * Get all specific parts of the client side
     * @param mixed $selector The source selector
     * @param mixed $callback The call back handler
     * @example get("body", function(selectedHtml)=>{ //do somework })
     */
    public static function Get($selector = null, $callback = null)
    {
        return self::Action("Array.from(document.querySelectorAll(" . self::Convert($selector ?? self::$DefaultSourceSelector) . ").values().map(el=>el.outerHTML))", $callback);
    }
    /**
     * Make a script to
     * Replace the output with a special part of client side
     * @param mixed $selector The destination selector
     * @param mixed $handler The data that is ready to print
     * @param mixed $args Handler input arguments
     */
    public static function Set($selector = null, $handler = null, $args = [], $direct = true)
    {
        return Internal::MakeScript(
            $handler,
            $args,
            "(data,err)=>_(" . self::Convert($selector ?? self::$DefaultDestinationSelector) . ").replace(data??err)",
            direct: $direct,
            encrypt: false
        );
    }
    /**
     * Make a script to Delete a special part of client side
     * @param mixed $selector The destination selector
     */
    public static function Delete($selector = "body", $direct = true)
    {
        return Internal::MakeStartScript(direct: $direct) . "document.querySelectorAll(" . self::Convert($selector ?? self::$DefaultSourceSelector) . ").forEach(el=>el.remove())" . Internal::MakeEndScript(direct: $direct);
    }
    /**
     * Make a script to
     * Insert output before a special part of client side
     * @param mixed $selector The destination selector
     * @param mixed $handler The data that is ready to print
     * @param mixed $args Handler input arguments
     */
    public static function Before($selector = "body", $handler = null, $args = [], $direct = true)
    {
        return Internal::MakeScript(
            $handler,
            $args,
            "(data,err)=>_(" . self::Convert($selector ?? self::$DefaultDestinationSelector) . ").before(data??err)"
            ,
            direct: $direct,
            encrypt: false
        );
    }
    /**
     * Make a script to
     * Insert output after a special part of client side
     * @param mixed $selector The destination selector
     * @param mixed $handler The data that is ready to print
     * @param mixed $args Handler input arguments
     */
    public static function After($selector = "body", $handler = null, $args = [], $direct = true)
    {
        return Internal::MakeScript(
            $handler,
            $args,
            "(data,err)=>_(" . self::Convert($selector ?? self::$DefaultDestinationSelector) . ").after(data??err)",
            direct: $direct,
            encrypt: false
        );
    }
    /**
     * Make a script to
     * Print output inside a special part of client side
     * @param mixed $selector The destination selector
     * @param mixed $handler The data that is ready to print
     * @param mixed $args Handler input arguments
     */
    public static function Fill($selector = "body", $handler = null, $args = [], $direct = true)
    {
        return Internal::MakeScript(
            $handler,
            $args,
            //"(data,err)=>document.querySelectorAll(" . self::Convert($selector ?? self::$DefaultDestinationSelector) . ").forEach(l=>l.replaceChildren(...((html)=>{el=document.createElement('qb');el.innerHTML=html;return el.childNodes;})(data??err)))"
            "(data,err)=>_(" . self::Convert($selector ?? self::$DefaultDestinationSelector) . ").html(data??err)",
            direct: $direct,
            encrypt: false
        );
    }
    /**
     * Make a script to
     * Prepend output on a special part of client side
     * @param mixed $selector The destination selector
     * @param mixed $handler The data that is ready to print
     * @param mixed $args Handler input arguments
     */
    public static function Prepend($selector = "body", $handler = null, $args = [], $direct = true)
    {
        return Internal::MakeScript(
            $handler,
            $args,
            "(data,err)=>_(" . self::Convert($selector ?? self::$DefaultDestinationSelector) . ").prepend(data??err)",
            direct: $direct,
            encrypt: false
        );
    }
    /**
     * Make a script to
     * Append output on a special part of client side
     * @param mixed $selector The destination selector
     * @param mixed $handler The data that is ready to print
     * @param mixed $args Handler input arguments
     */
    public static function Append($selector = "body", $handler = null, $args = [], $direct = true)
    {
        return Internal::MakeScript(
            $handler,
            $args,
            "(data,err)=>_(" . self::Convert($selector ?? self::$DefaultDestinationSelector) . ").append(data??err)",
            direct: $direct,
            encrypt: false
        );
    }
}