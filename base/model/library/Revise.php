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
    public static $PutMethod = "REVISE_PUT";
    public static $DelMethod = "REVISE_DEL";
    public static $Extension = ".revise.json";
    public static $Flags = JSON_ERROR_NONE | JSON_OBJECT_AS_ARRAY | JSON_NUMERIC_CHECK | JSON_BIGINT_AS_STRING | JSON_PRESERVE_ZERO_FRACTION;

    /**
     * To get the Revise file Path of an object
     * @param mixed $object
     */
    public static function Path($object)
    {
        return (new \ReflectionClass($object))->getFileName() . self::$Extension;
    }
    /**
     * Load object with revised data
     * @param mixed $object
     */
    public static function Load(&$object, &$path = null)
    {
        if ($object === null)
            return $object;
        if (file_exists($path = $path ?? self::Path($object))) {
            self::Decode($object, file_get_contents($path));
            return $path;
        }
        return false;
    }
    /**
     * Store revised object data on a file
     * @param mixed $object
     */
    public static function Store($object, &$path = null)
    {
        if ($object === null)
            return $object;
        return file_put_contents($path = $path ?? self::Path($object), self::Encode($object)) ? $path : false;
    }
    /**
     * Restore object data to the main values
     * @param mixed $object
     */
    public static function Delete($object, &$path = null)
    {
        if ($object === null)
            return $object;
        return (!file_exists($path = $path ?? self::Path($object))) || unlink($path);
    }

    /**
     * To get Features of an object in a string
     * @param mixed $object
     */
    public static function Encode($object)
    {
        if ($object === null)
            return "null";
        $res = [];
        foreach (self::Properties($object) as $value)
            $res[$value["Name"]] = $value["Value"];
        $res = json_encode($res, flags: self::$Flags);
        if ($res === false)
            return "{}";
        return $res;
    }
    /**
     * To set Features of an object from a string
     * @param mixed $object
     */
    public static function Decode(&$object, string $reviseData = "{}")
    {
        $metadata = json_decode($reviseData, flags: self::$Flags) ?? [];
        foreach (self::Properties($object) as $value) {
            $key = $value["Name"];
            if (isset($metadata[$key]) && isset($object->$key))
                switch ($value["Type"]) {
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
     * To get each of Features of a Class as an array
     * @param mixed $object
     */
    public static function Properties($object, \ReflectionClass|null $reflection = null)
    {
        $reflection = $reflection ?? new \ReflectionClass($object);
        foreach ($reflection->getProperties() as $value) {
            $pars = self::Parameters($value->getDocComment());
            $modifires = $value->getModifiers();
            if (
                $modifires != T_PRIVATE &&
                $modifires != T_PROTECTED &&
                !isValid($pars, "internal") &&
                !isValid($pars, "private")
            ) {
                $dval = $value->getDefaultValue();
                $name = $value->getName();
                $val = $value->getValue($object);
                $type = popBetween($pars, "field", "type") ?: ((is_null($dval) ? null : gettype($dval)) ?: ((isset($value->getSettableType)?$value->getSettableType():$value->getType() )?: pop($pars, "var")));
                $type = strtolower((str_replace(["object", "<array", "<[", ","], "", $type ?? "") !== ($type ?? "")) ? "object" : ($type ?: "mixed"));
                if(in_array($type, ["string"])){
                    if(endsWith($name, "Script", "Tag")) $type = "script";
                    elseif(endsWith($name, "Label")) $type = "string";
                    elseif(endsWith($name, "Content")) $type = "content";
                    elseif(endsWith($name, "Description")) $type = "texts";
                    elseif(str_contains("$val$dval", "\n")) $type = "texts";
                    elseif(startsWith($name, "Allow")) $type = "bool";
                    elseif(endsWith($name, "Password","SecretKey","SoftKey","HardKey")) $type = "password";
                    elseif(endsWith($name, "Amount", "Price")) $type = "number";
                    elseif(endsWith($name, "Path")) $type = "path";
                    elseif(endsWith($name, "Address")) $type = "address";
                    elseif(endsWith($name, "Url")) $type = "url";
                    elseif(endsWith($name, "Media")) $type = "media";
                    elseif(endsWith($name, "File")) $type = "file";
                    elseif(endsWith($name, "Image")) $type = "image";
                    elseif(endsWith($name, "Video")) $type = "video";
                    elseif(endsWith($name, "Audio")) $type = "audio";
                    //elseif(endsWith($name, ["Name", "Class", "Unit", "Currency", "Key"])) $type = "value";
                    else $type = "value";
                }
                $desc = pop($pars, "description");
                yield [
                    "Type" => $type,
                    "Name" => $name,
                    "Value" => $val,
                    "Title" => pop($pars, "title"),
                    "Description" => $desc = __(pop($pars, "abstract") ?? $desc ?? "", separator: Struct::$Break),
                    "Category" => pop($pars, "category"),
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
    public static function Parameters(string|null $comment)
    {
        if (is_null($comment))
            return [];
        $key = null;
        $res = ["description" => []];
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
                        $res[$key] .= trim(preg_replace("/^\@\w+\W+/", "", $value));
                        break;
                    case "field":
                    case "required":
                        $res[$key] = trim(preg_replace("/^\@\w+\W+/", "", $value));
                        break;
                    case "abstract":
                    case "example":
                        if (!isset($res[$key]))
                            $res[$key] = [];
                        $res[$key][] = trim(preg_replace("/^\@\w+\W+/", "", $value));
                        break;
                    case "template":
                    case "templates":
                    case "option":
                    case "options":
                    case "attribute":
                    case "attributes":
                        if (!isset($res[$key]))
                            $res[$key] = [];
                        $val = trim(preg_replace("/^\@\w+\W+/", "", $value));
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
                        $res[$key] .= trim(preg_replace("/^\@\w+\W+/", "", $value));
                        break;
                }
            } else
                $res["description"][] = $value;
        }
        return $res;
    }

    /**
     * To get all Features of a Class as a HTML Form
     * @param mixed $object
     */
    public static function GetForm($object)
    {
        if (is_null($object))
            return Struct::Warning("There is no object!");
        module("Form");
        module("Field");
        $reflection = new \ReflectionClass($object);

        $tabs = [];
        foreach (self::Properties($object, $reflection) as $prop) {
            $t = strtoupper($prop["Category"] ?: "General");
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
        $name = $reflection->getShortName();
        $tid = "{$name}Tab";
        $form = new \MiMFa\Module\Form(
            title: "Edit $name}",
            description: getBetween(self::Parameters($reflection->getDocComment()), "Abstract", "Description"),
            method: self::$PutMethod,
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
        $form->Buttons = Struct::Button("Recover to Defaults", "send('" . self::$DelMethod . "', null, {Name:'{$form->MainClass}'})");
        return Struct::Style("
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
        ") . $form->ToString();
    }
    /**
     * To handle all Features received of a Class HTML Form
     * @param mixed $object
     */
    public static function HandleForm($object)
    {
        try {
            if (receive(self::$DelMethod))
                return self::Delete($object)
                    ? Struct::Success("Data Recoverred to default Successfully!" . Struct::Script("load()"))
                    : Struct::Warning("Data were set to default!");
            elseif ($newValues = receive(self::$PutMethod))
                try {
                    \_::$User->Active = false;
                    $res = [];
                    foreach (self::Properties($object) as $value)
                        $res[$value["Name"]] = $value["Type"];
                    foreach ($newValues as $key => $value)
                        if (isset($object->$key))
                            if ($res[$key] === "object" && $value)
                                $object->$key = json_decode($value, flags: self::$Flags);
                            elseif (startsWith($res[$key] ?? "", "bool"))
                                $object->$key = boolval($value);
                            else
                                $object->$key = $value;
                    if (self::Store($object))
                        return Struct::Success("Data updated successfully!" . Struct::Script("load()"));
                    else
                        return Struct::Error("Something went wrong!");
                } catch (\Exception $ex) {
                    return Struct::Error($ex);
                } finally {
                    \_::$User->Active = true;
                } else
                return self::GetForm($object);
        } catch (\Exception $ex) {
            return Struct::Error($ex);
        }
    }

    public static function ToString($object)
    {
        return self::HandleForm($object);
    }
    public static function Render($object)
    {
        return response(self::ToString($object));
    }
}