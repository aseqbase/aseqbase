<?php
namespace MiMFa\Library;
/**
 * A powerful library to connect and reflect everything for scripts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#reflect See the Library Documentation
 */
class Revise
{
    public function __construct()
    {
        self::Load($this);
    }

    /**
     * @internal
     */
    public static $PutMethod = "REVISE_PUT";
    /**
     * @internal
     */
    public static $DelMethod = "REVISE_DEL";
    /**
     * @internal
     */
    public static $Extension = ".revise";
    /**
     * @internal
     */
    public static $Flags = JSON_ERROR_NONE | JSON_OBJECT_AS_ARRAY | JSON_NUMERIC_CHECK | JSON_BIGINT_AS_STRING | JSON_PRESERVE_ZERO_FRACTION;

    /**
     * To get the Revise file Path of an object
     * @param mixed $object
     */
    public static function Path($object, $category = null, $language = null)
    {
        if(is_null($language) && is_null($category)){
            $path = self::Path($object, null, \_::$Front->Translate->Language);
            if(file_exists($path)) return $path;
        }
        return (new \ReflectionClass($object))->getFileName() . ($category ? ".$category" : "") . ($language ? ".$language" : "") . self::$Extension;
    }

    /**
     * Load object with revised data
     * @param mixed $object
     */
    public static function Load(&$object, $category = null, $language = null, $path = null)
    {
        if ($object === null)
            return $object;
        if ($category) {
            if (file_exists($path = $path ?? self::Path($object, $category, $language))) {
                self::Decode($object, $category, file_get_contents($path));
                return $path;
            }
        } else {
            $pathes = [];
            foreach (self::GetCategories($object) as $category => $count)
                $pathes[] = self::Load($object, $category, $language);
            return $pathes;
        }
        return false;
    }
    /**
     * Store revised object data on a file
     * @param mixed $object
     */
    public static function Store($object, $category = null, $language = null, $path = null)
    {
        if ($object === null)
            return $object;
        if ($category)
            return file_put_contents($path = $path ?? self::Path($object, $category, $language), self::Encode($object, $category)) ? $path : false;
        else {
            $pathes = [];
            foreach (self::GetCategories($object) as $category => $count)
                $pathes[] = self::Store($object, $category, $language);
            return $pathes;
        }
    }
    /**
     * Restore object data to the main values
     * @param mixed $object
     */
    public static function Delete($object, $category = null, $language = null, $path = null)
    {
        if ($object === null)
            return $object;
        if ($category)
            return ((!file_exists($path = $path ?? self::Path($object, $category, $language))) || unlink($path)) ? $path : null;
        else {
            $pathes = [];
            foreach (self::GetCategories($object) as $category => $count)
                $pathes[] = self::Delete($object, $category, $language);
            return $pathes;
        }
    }

    /**
     * To get Features of an object in a string
     * @param mixed $object
     */
    public static function Encode($object, $category = null)
    {
        if ($object === null)
            return "null";
        $res = self::Get(fn($property) => $property->getValue($object), $object, $category);
        $res = json_encode($res, flags: self::$Flags);
        if ($res === false)
            return "{}";
        return $res;
    }
    /**
     * To set Features of an object from a string
     * @param mixed $object
     */
    public static function Decode(&$object, $category = null, string $reviseData = "{}")
    {
        $metadata = json_decode($reviseData, flags: self::$Flags) ?? [];
        foreach (self::Get(fn($property) => self::GetType($object, $property), $object, $category) as $key => $type) {
            if (isset($metadata[$key]) && isset($object->$key))
                switch ($type) {
                    case "string":
                        $object->$key = Convert::ToString($metadata[$key]);
                        break;
                    case "bool":
                    case "boolean":
                        $object->$key = boolval($metadata[$key]);
                        break;
                    case "int":
                    case "integer":
                    case "number":
                        $object->$key = intval($metadata[$key]);
                        break;
                    case "float":
                        $object->$key = floatval($metadata[$key]);
                        break;
                    case "double":
                        $object->$key = doubleval($metadata[$key]);
                        break;
                    default:
                        $object->$key = $metadata[$key];
                        break;
                }
        }
        return $object;
    }

    /**
     * To get any pairs of accessible properties
     * @param callable $action fn($property) => $property->getValue()
     * @param mixed $object
     * @param mixed $category
     * @param \ReflectionClass|null $reflection
     * @return array
     */
    public static function Get($action, $object, $category = null, \ReflectionClass|null $reflection = null)
    {
        if (is_null($object))
            return [];
        $category = strtoupper($category ?? "");
        $pairs = [];
        $reflection = $reflection ?? new \ReflectionClass($object);
        foreach ($reflection->getProperties() as $value) {
            $pars = self::GetParameters($value->getDocComment());
            $modifires = $value->getModifiers();
            if (
                $modifires != T_PRIVATE &&
                $modifires != T_PROTECTED &&
                !has($pars, "internal") &&
                !has($pars, "private")
            ) {
                if (!$category || (strtoupper(get($pars, "Category") ?: "General") === $category))
                    $pairs[$value->getName()] = $action($value);
            }
        }
        return $pairs;
    }
    public static function GetCategories($object, \ReflectionClass|null $reflection = null)
    {
        if (is_null($object))
            return [];
        $cats = [];
        $reflection = $reflection ?? new \ReflectionClass($object);
        foreach ($reflection->getProperties() as $value) {
            $pars = self::GetParameters($value->getDocComment());
            $modifires = $value->getModifiers();
            if (
                $modifires != T_PRIVATE &&
                $modifires != T_PROTECTED &&
                !has($pars, "internal") &&
                !has($pars, "private")
            ) {
                $cat = get($pars, "Category") ?: "General";
                $cats[$cat] = ($cats[$cat] ?? 0) + 1;
            }
        }
        return $cats;
    }
    /**
     * To get each of Features of a Class as an array
     * @param mixed $object
     */
    public static function GetProperties($object, \ReflectionClass|null $reflection = null)
    {
        $reflection = $reflection ?? new \ReflectionClass($object);
        foreach ($reflection->getProperties() as $value) {
            $pars = self::GetParameters($value->getDocComment());
            $modifires = $value->getModifiers();
            if (
                $modifires != T_PRIVATE &&
                $modifires != T_PROTECTED &&
                !has($pars, "internal") &&
                !has($pars, "private")
            ) {
                $desc = pop($pars, "description");
                yield [
                    "Type" => self::GetType($object, $value),
                    "Name" => $value->getName(),
                    "Value" => $value->getValue($object),
                    "Title" => pop($pars, "title"),
                    "Description" => $desc = __(pop($pars, "abstract") ?? $desc ?? "", separator: Struct::$Break),
                    "Category" => pop($pars, "category") ?: "General",
                    "Required" => pop($pars, "required"),
                    "Options" => pop($pars, "options"),
                    "Attributes" => pop($pars, "attributes") ?? [],
                    "Content" => [
                        ...($desc ? [$desc] : []),
                        ...loop($pars, fn($v, $k) => [__(strToProper($k)) . ": ", is_array($v) ? Struct::Convert($v) : $v])
                    ]
                ];
            }
        }
    }
    public static function GetType($object, \ReflectionProperty $property)
    {
        $pars = self::GetParameters($property->getDocComment());
        $dval = $property->getDefaultValue();
        $name = $property->getName();
        $val = $property->getValue($object);
        $type = popBetween($pars, "field", "type") ?: ((is_null($dval) ? null : gettype($dval)) ?: ((isset($property->getSettableType) ? $property->getSettableType() : $property->getType()) ?: pop($pars, "var")));
        $type = strtolower((str_replace(["object", "<array", "<[", ","], "", $type ?? "") !== ($type ?? "")) ? "object" : ($type ?: "mixed"));
        if (in_array($type, ["string"])) {
            if (endsWith($name, "Script", "Tag"))
                $type = "script";
            elseif (endsWith($name, "Label"))
                $type = "string";
            elseif (endsWith($name, "Content"))
                $type = "content";
            elseif (endsWith($name, "Description"))
                $type = "texts";
            elseif (str_contains("$val$dval", "\n"))
                $type = "texts";
            elseif (startsWith($name, "Allow"))
                $type = "bool";
            elseif (endsWith($name, "Password", "SecretKey", "SoftKey", "HardKey"))
                $type = "password";
            elseif (endsWith($name, "Amount", "Price"))
                $type = "number";
            elseif (endsWith($name, "Path"))
                $type = "path";
            elseif (endsWith($name, "Address"))
                $type = "address";
            elseif (endsWith($name, "Url"))
                $type = "url";
            elseif (endsWith($name, "Media"))
                $type = "media";
            elseif (endsWith($name, "File"))
                $type = "file";
            elseif (endsWith($name, "Image"))
                $type = "image";
            elseif (endsWith($name, "Video"))
                $type = "video";
            elseif (endsWith($name, "Audio"))
                $type = "audio";
            //elseif(endsWith($name, ["Name", "Class", "Unit", "Currency", "Key"])) $type = "value";
            else
                $type = "value";
        }
        return $type;
    }
    public static function GetParameters(string|null $comment)
    {
        if (is_null($comment))
            return [];
        $key = null;
        $res = ["description" => "", "category" => "General"];
        $reppat = "/(^\@\w+\W?)|(^\s*\*\s?)/";
        foreach (preg_split("/\r?\n\r?\s*\*\s*/", preg_replace("/(\s*\/[\s\*]+)|([\s\*]+\/\s*)/", "", $comment)) as $value) {
            if ($f = preg_find("/(?<=^\@)\w+\b/", $value))
                $key = strtolower($f);
            if ($key) {
                switch ($key) {
                    case "type":
                    case "var":
                        if (isset($res[$key]))
                            $res[$key] .= "|";
                        else
                            $res[$key] = "";
                        $res[$key] .= trim(preg_replace($reppat, "", $value));
                        break;
                    case "field":
                    case "required":
                    case "category":
                        $res[$key] = trim(preg_replace($reppat, "", $value));
                        break;
                    case "abstract":
                        if (!isset($res[$key]))
                            $res[$key] = "";
                        if ($v = trim(preg_replace($reppat, "", $value)))
                            $res[$key] .= "$v\n";
                        break;
                    case "example":
                        if (!isset($res[$key]))
                            $res[$key] = "";
                        if ($v = trim(preg_replace($reppat, "", $value), "\r\n[]"))
                            $res[$key] .= "$v\n";
                        break;
                    case "template":
                    case "templates":
                    case "option":
                    case "options":
                    case "attribute":
                    case "attributes":
                        if (!isset($res[$key]))
                            $res[$key] = [];
                        $val = trim(preg_replace($reppat, "", $value));
                        $k = preg_find("/^\s*\w+\s*(?=:)/u", $val);
                        if ($k)
                            $res[$key][trim($k, " \n\r\t\v\x00\"'")] = preg_replace("/(^\s*\w+\s*:\s*([\"\']))|(\2$)/u", "", $val);
                        else
                            $res[$key][] = $val;
                        break;
                    default:
                        if (isset($res[$key]))
                            $res[$key] .= ", ";
                        else
                            $res[$key] = "";
                        $res[$key] .= trim(preg_replace($reppat, "", $value));
                        break;
                }
            } elseif ($value)
                $res["description"] .= "$value\n";
        }
        return $res;
    }

    /**
     * To get all Features of a Class as a HTML Form
     * @param mixed $object
     */
    public static function GetForm($object, $category = null, $language = null)
    {
        if (is_null($object))
            return Struct::Warning("There is no object!");
        module("Form");
        module("Field");
        $reflection = new \ReflectionClass($object);

        $params = [
            ...($category ? ["category" => $category] : []),
            ...($language ? ["language" => $language] : []),
        ];
        $cat = strtoupper($category ?? "");
        $tabs = [];
        foreach (self::GetProperties($object, $reflection) as $prop) {
            $t = strtoupper($prop["Category"]);
            if (!$cat || ($cat === $t)) {
                if (!isset($tabs[$t]))
                    $tabs[$t] = "";
                try {
                    $tabs[$t] .= (new \MiMFa\Module\Field(
                        type: $prop["Type"],
                        key: $prop["Name"],
                        value: $prop["Type"] === "object" ? json_encode($prop["Value"], flags: self::$Flags) : $prop["Value"],
                        title: $prop["Title"],
                        description: __($prop["Content"], separator: Struct::$Break),
                        required: $prop["Required"],
                        options: $prop["Options"],
                        attributes: $prop["Attributes"]
                    ))->ToString();
                } catch (\Exception) {
                }
            }
        }
        $name = $reflection->getShortName();
        $tid = "{$name}Tab";
        $form = new \MiMFa\Module\Form(
            title: "Edit $name}",
            description: getBetween(self::GetParameters($reflection->getDocComment()), "Abstract", "Description"),
            method: self::$PutMethod,
            action: \_::$Address->UrlBase . ($params ? "?" . join("&", loop($params, fn($v, $k) => "$k=" . urlencode($v))) : ""),
            image: "edit",
            items: Struct::Tabs($tabs, ["Id" => $tid])
        );
        $form->Id = "{$name}Form";
        $form->ContentClass = "col-lg-12";
        $form->Template = "b";
        $form->Timeout = 60;
        $form->SubmitLabel = "Update";
        $form->ResetLabel = "Reset";
        $form->AllowHeader = false;
        $form->Buttons = Struct::Button("Recover to Defaults", "if(".Script::Confirm("All of your special values will be reset to the default values.\rAre you sure you want to reset all manual values?").") send('" . self::$DelMethod . "', null, {Name:'{$form->MainClass}'})");
        return Struct::Style("
            #{$name}Form>*>*>.box{
                width: 100%;
            }
            #$tid>.tab-titles{
                margin-bottom: var(--size-1);
                border-bottom: var(--border-1);
            }
            #$tid>.tab-titles>.tab-title{
                padding: calc(var(--size-1) / 2) var(--size-0);
                border-radius: var(--radius-1);
            }
            #$tid>.tab-titles>.tab-title.active{
                font-weight: bold;
                border: var(--border-2);
                border-bottom: none;
                outline: var(--border-2) var(--back-color-special);
            }
        ") .
            Struct::Center(
                "Specialized for the " . Struct::SelectInput(
                    "language",
                    $language,
                    ["" => "all", ...loop(\_::$Front->Translate->GetLanguages(), fn($v, $k) => [$k => $v["Title"] ?? $k], pair: true)],
                    ["onchange" => Script::Load(\_::$Address->UrlBase . "?category=" . urlencode($category) . "&language=\${this.value}")]
                ) . " language"
            ) .
            $form->ToString();
    }
    /**
     * To handle all Features received of a Class HTML Form
     * @param mixed $object
     */
    public static function HandleForm($object, $category = null, $language = null)
    {
        try {
            if (receive(self::$DelMethod))
                return self::Delete($object, $category, $language)
                    ? Struct::Success("Data Recoverred to default Successfully!" . Struct::Script("load()"))
                    : Struct::Warning("Data were set to default!");
            elseif ($newValues = receive(self::$PutMethod))
                try {
                    \_::$User->Active = false;

                    $types = self::Get(
                        fn($property) => self::GetType($object, $property),
                        $object,
                        $category
                    );

                    if ($files = receiveFile())
                        foreach ($files as $key => $value)
                            if (isset($types[$key]) && isset($object->$key) && Storage::IsFileObject($value))
                                if ($val = Storage::GetUrl(Storage::Store($value)))
                                    $newValues[$key] = $val;

                    foreach ($newValues as $key => $value)
                        if (isset($object->$key) && isset($types[$key]))
                            if ($types[$key] === "object" && $value)
                                $object->$key = json_decode($value, flags: self::$Flags);
                            elseif (startsWith($types[$key] ?? "", "bool"))
                                $object->$key = boolval($value);
                            else
                                $object->$key = $value;

                    if (self::Store($object, $category, $language))
                        return Struct::Success("Data updated successfully!" . Struct::Script("load()"));
                    else
                        return Struct::Error("Something went wrong!");
                } catch (\Exception $ex) {
                    return Struct::Error($ex);
                } finally {
                    \_::$User->Active = true;
                } else
                return self::GetForm($object, $category, $language);
        } catch (\Exception $ex) {
            return Struct::Error($ex);
        }
    }

    public static function ToString($object, $category = null, $language = null)
    {
        return self::HandleForm($object, $category ?? receiveGet("Category") ?? "", $language ?? receiveGet("language") ?? "");
    }
    public static function Render($object, $category = null, $language = null)
    {
        return response(self::ToString($object, $category ?? receiveGet("Category") ?? "", $language ?? receiveGet("language") ?? ""));
    }
}