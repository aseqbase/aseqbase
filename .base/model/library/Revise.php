<?php
namespace MiMFa\Library;
/**
 * A powerful library to connect and reflect everything for scripts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#reflect See the Library Documentation
*/
class Revise{
    public static $Extension = ".revise";
    public static $Flags = JSON_OBJECT_AS_ARRAY;

    /**
     * Load object with revised data
     * @param mixed $object
     */
    public static function Load(&$object, &$path = null){
        if($object === null) return $object;
        $path = $path??(self::GetPath($object).self::$Extension);
        if(file_exists($path)){
            self::Decode($object, file_get_contents($path));
            return $path;
        }
        return false;
    }
    /**
     * Store revised object data on a file
     * @param mixed $object
     */
    public static function Store($object, &$path = null){
        if($object === null) return $object;
        $path = $path??(self::GetPath($object).self::$Extension);
        return file_put_contents($path, self::Encode($object))?$path:false;
    }
    /**
     * Restore object data to the main values
     * @param mixed $object
     */
    public static function Restore($object, &$path = null){
        if($object === null) return $object;
        $path = $path??(self::GetPath($object).self::$Extension);
        return !file_exists($path) || unlink($path);
    }

    /**
     * To get the Path of a Class
     * @param mixed $object
     */
    public static function GetPath($object){
        return (new \ReflectionClass($object))->getFileName();
    }
    /**
     * To get Features of an object in a string
     * @param mixed $object
     */
    public static function Encode($object){
        if($object === null) return "null";
        $res = json_encode($object, flags:self::$Flags);
        if($res === false) return "{}";
        return $res;
    }
    /**
     * To set Features of an object from a string
     * @param mixed $object
     */
    public static function Decode(&$object, string $reviseData = "{}"){
        $metadata = json_decode($reviseData, flags:self::$Flags)??[];
        foreach ($metadata as $key=>$value)
            if(isset($object->$key)) $object->$key = $value;
        return $object;
    }

    public static function GetCommentParameters(string|null $comment){
        if(is_null($comment)) return [];
        $matches = preg_find_all("/\@\w+\s*.*/", $comment);
        $res = [];
        $res["Abstract"] = ltrim(Convert::ToString(preg_find_all("/^\s*\*\s*[^@\/][\s\w].*/mi", $comment)), "* \t\r\n\f\v");
        foreach ($matches as $value)
            $res[strtolower(preg_find("/(?<=\@)\w+/", $value))] = trim(preg_replace("/^\@\w+\s*/", "", $value));
        return $res;
    }

    /**
     * To get all Features of a Class as a HTML Form
     * @param mixed $object
     */
    public static function GetForm($object):\MiMFa\Module\Form{
        module("Form");
        if(is_null($object)) return new \MiMFa\Module\Form();
        $reflection = new \ReflectionClass($object);
        $form = new \MiMFa\Module\Form(
            title: "Edit {$reflection->getName()}",
            description: getBetween(self::GetCommentParameters($reflection->getDocComment()),"Abstract","Description" ),
            method: "POST",
            image:"edit",
            children: self::GetFields($reflection, $object)
        );
        $form->Id = "{$reflection->getName()}EditForm";
        $form->Template = "both";
        $form->Timeout = 60000;
        $form->SubmitLabel = "Update";
        $form->ResetLabel = "Reset";
        $form->AllowHeader = false;
        return $form;
    }
    /**
     * To get each of Features of a Class as a form HTML Field
     * @param mixed $object
     */
    public static function GetFields(\ReflectionClass $reflection, $object){
        module("Field");
        foreach ($reflection->getProperties() as $value){
            $pars = self::GetCommentParameters($value->getDocComment());
            $module = $value->getModifiers();
            if(
                $module != T_PRIVATE &&
                $module != T_PROTECTED &&
                !isValid($pars,"internal") &&
                !isValid($pars,"private")
            )
                yield new \MiMFa\Module\Field(
                    type:getBetween($pars,"Field","Type" )??($value->getType()??get($pars,"Var")),
                    key:$value->getName(),
                    value:$value->getValue($object),
                    title:getValid($pars,"Title" , null),
                    description:getBetween($pars,"Abstract","Description" ),
                    required:getValid($pars,"Required", null),
                    options:getValid($pars,"Options", null),
                    attributes:getValid($pars,"Attributes", null)
            );
        }
    }
    /**
     * To handle all Features received of a Class HTML Form
     * @param mixed $object
     */
    public static function HandleForm($object, array $newValues = null){
        try {
            if(is_null($newValues)) $newValues = receivePost(null);
            foreach ($newValues as $key=>$value)
                if(isset($object->$key)) $object->$key = $value;
            if(self::Store($object)) return Html::Success("Data updated successfully!");
            else return Html::Error("There a problem is occured!");
        } catch(\Exception $ex) { return Html::Error($ex); }
    }
}