<?php
namespace MiMFa\Library;

/**
 * A powerfull class to handle internal requests for RPCs (Remote Procedure Call)
 * Remote Procedure Call (RPC) is a communication protocol that allows a program
 * to execute a procedure on a remote server as if it were a local function.
 * It abstracts the complexities of network communication, enabling developers
 * to focus on function calls rather than the underlying network details.
 * RPC is widely used in distributed systems and client-server architectures.
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#Internal See the Library Documentation
 */
class Internal
{
    public static $Directory = null;
    public static $Selector = 'getQuery(this)??"body"';
    public static $Target = 'after';

    /**
     * To handle and return internal requests
     * @param string $name The handler encripted name
     * @param mixed default arguments of the selected handler
     */
    public static function Handle($name = null)
    {
        $received = $name ? [$name => receiveInternal($name)] : receiveInternal();
        $reses = [];
        foreach ($received as $k => $v) {
            $args = (is_string($v)?json_decode(preg_match("/^\{[\w\W]*\}$/",$v)?$v:decrypt($v), true, flags: JSON_OBJECT_AS_ARRAY):$v) ?? [];
            $args = is_array($args) ? $args : [$args];
            $reses[] = Convert::By(self::Get($k, fn($a = null) => $a), ...$args);
        }
        return join("", $reses);
    }
    /**
     * To handle and render internal requests
     * @param string $name The handler encripted name
     * @param mixed default arguments of the selected handler
     */
    public static function Render($name = null)
    {
        response(self::Handle($name));
    }

    public static function MakeStartScript($multilines = false, $direct = false)
    {
        if (\_::$Router->DefaultMethodName === "GET" && !headers_sent() && !$direct) {
            return "document.addEventListener('DOMContentLoaded',()=>" . ($multilines ? "{" : "");
        }
        return "";
    }
    public static function MakeEndScript($multilines = false, $direct = false)
    {
        if (\_::$Router->DefaultMethodName === "GET" && !headers_sent() && !$direct) {
            return ($multilines ? "}" : "") . ");";
        }
        return "";
    }
    /**
     * 
     * To convert a PHP handler to a JS codes
     * @param mixed $handler A handler function or data wants to print
     * @param mixed $args Handler input arguments
     * @param mixed $callbackScript A JS code to handle received data // (data,err)=> received procedure
     * @param mixed $progressScript A JS code to apply while getting data // (data,err)=> progrecing procedure
     * @param mixed $timeout The interaction timeout
     * @param bool $direct To get the direct executable script, or execute when the document is ready
     * @param bool $encryot To encrypt the $args for more protection
     * @return string The interaction JS codes
     */
    public static function MakeScript($handler, $args = null, $callbackScript = null, $progressScript = null, $timeout = 60000, $direct = false, $encrypt = true)
    {
        $callbackScript = $callbackScript ?? "(data,err)=>document.querySelector(".(self::$Selector).").".(self::$Target)."(...((html)=>{el=document.createElement('qb');el.innerHTML=html;el.querySelectorAll('script').forEach(script => eval(script.textContent));return el.childNodes;})(data??err))";
        $progressScript = $progressScript ?? "null";
        $start = self::MakeStartScript(direct: $direct);
        $end = self::MakeEndScript(direct: $direct);
        $args = Script::Convert($args);
        if($encrypt) $args = Script::Convert(encrypt($args));
        if (isStatic($handler))
            return "$start($callbackScript)(" . Script::Convert($handler) . ",$args)$end";
        return $start .
            'sendInternal(null,{"' .
            self::Set($handler) . "\":$args},".
            (self::$Selector).",$callbackScript,$callbackScript,null,$progressScript,$timeout)$end";
    }
    /**
     * Get the handler encripted name
     * @param mixed $handler A handler function or data wants to print
     * @return string The handler encripted name
     */
    public static function Name($handler)
    {
        return encrypt(md5(self::Content($handler)));
    }
    /**
     * Get the handler content
     * @param mixed $handler A handler function or data wants to print
     * @return string The handler content
     */
    public static function Content($handler)
    {
        return '<?php return ' . self::Code($handler) . ';?>';
    }
    /**
     * Convert Objects to source PHP codes
     * @param mixed $handler Objects
     * @return string
     */
    public static function Code($handler)
    {
        if (is_null($handler))
            return 'fn($args=null)=>$args';
        if (is_string($handler))
            return 'fn($args=null)=>"' . str_replace("\"", "\\\"", $handler) . '"';
        if (is_numeric($handler))
            return "fn(\$args=null)=>$handler";
        if (is_bool($handler))
            return $handler ? 'fn($args=null)=>true' : 'fn($args=null)=>false';
        if (is_array($handler) || is_iterable($handler))
            return 'fn($args=null)=>' . Convert::ToString($handler, ", ", "\"{0}\"=>{1}", "[{0}]", "null");
        $source = '';
        if (is_callable($handler) || $handler instanceof \Closure) {
            $reflection = new \ReflectionFunction($handler);
            $file = new \SplFileObject($reflection->getFileName());
            $file->seek($reflection->getStartLine() - 1);
            while ($file->key() < $reflection->getEndLine()) {
                $source .= $file->current();
                $file->next();
            }
            $code = preg_find("/(fn|function)\s*\([^\)]*\)\s*(\=\>)?\s*\{\s*(('[^']*')*|(\"[^\"]*\")*|(\{[^\}]+\})*|([^\}]+)*)*\s*\}/U", trim($source, ";(),= \n\r\t\v\x00"));
            $sl = strlen(preg_replace("/('[^']*')*|(\"[^\"]*\")*|\{/","", $code));
            $el = strlen(preg_replace("/('[^']*')*|(\"[^\"]*\")*|\}/","", $code));
            if($sl < $el) return $code."}";
            return $code;
        } elseif (is_object($handler)) {
            $reflection = new \ReflectionObject($handler);
            $source = 'fn($args=null)=>';
            $file = new \SplFileObject($reflection->getFileName());
            $file->seek($reflection->getStartLine() - 1);
            while ($file->key() < $reflection->getEndLine()) {
                $source .= $file->current();
                $file->next();
            }
            return trim($source);
        } else
            return Convert::ToStatic($handler);
    }
    /**
     * To set a new internal Handler and get the encripted name
     * @param mixed $handler A handler function or data wants to print
     * @return string The handler encripted name
     */
    public static function Set($handler, $default = null)
    {
        $s = self::Content($handler);
        $default = $default ?? md5($s);
        $path = self::$Directory . $default;
        if (!file_exists($path))
            file_put_contents($path, $s);
        return encrypt($default);
    }
    /**
     * To set a new internal Handler and get the encripted name
     * @param string $name The handler encripted name
     * @return mixed A handler function or data wants to print
     */
    public static function Get($name, $default = null)
    {
        $file = self::$Directory . decrypt($name);
        if ($file)
            return including($file);
        return $default;
    }
    /**
     * To clear all internal handlers of this client
     * @param string $name The handler encripted name
     * @return mixed A handler function or data wants to print
     */
    public static function Pop($name)
    {
        $path = self::$Directory . decrypt($name);
        if (!file_exists($path))
            unlink($path);
    }
    /**
     * To clear all internal handlers of this client
     * @return mixed A handler function or data wants to print
     */
    public static function Clear()
    {
        return cleanup(self::$Directory);
    }
}

Internal::$Directory = \_::$Router->PrivateDirectory . "internal" . DIRECTORY_SEPARATOR;
Local::CreateDirectory(\_::$Router->PrivateDirectory);
Local::CreateDirectory(Internal::$Directory);