<?php
namespace MiMFa\Library;
/**
 * A powerful library to connect and reflect everything for scripts
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#reflect See the Library Documentation
*/
class Reflect{
    public static $DefaultSign = "[[default]]";

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
     * To get each Features of a Class
     * @param mixed $object
     */
    public static function Get($objectOrReflection){
        if($objectOrReflection === null) return new Reflected();
        if($objectOrReflection instanceof Reflected) return $objectOrReflection;
        if($objectOrReflection instanceof \ReflectionClass) return new Reflected($objectOrReflection);
        return new Reflected(new \ReflectionClass($objectOrReflection), $objectOrReflection);
    }
    /**
     * To set each Features of a Class
     * @param mixed $object
     */
    public static function Set($objectOrReflection, array $newData = []){
        if($objectOrReflection === null) return null;
        if($objectOrReflection instanceof Reflected || $objectOrReflection instanceof \ReflectionClass){
            $reflection = self::Get($objectOrReflection);
            foreach ($newData as $key=>$value)
                if(isset($reflection[$key])) $reflection[$key]->Value = $value;
            return $reflection;
        }
        foreach ($newData as $key=>$value)
            if(isset($objectOrReflection->$key)) $objectOrReflection->$key = $value;
        return $objectOrReflection;
    }
    /**
     * To get fill Features by an array
     * @param mixed $object
     */
    public static function Fill($objectOrReflection, array $newValues = []){
        $objectOrReflection = self::Get($objectOrReflection);
        foreach ($newValues as $key=>$value)
            if(isset($objectOrReflection[$key])) $objectOrReflection[$key]->Value = $value??$objectOrReflection[$key]->DefaultValue;
        return $objectOrReflection;
    }
    /**
     * To get fill Features by an array
     * @param mixed $object
     */
    public static function Update($objectOrReflection, array $newValues = []){
        $objectOrReflection = self::Get($objectOrReflection);
        foreach ($newValues as $key=>$value)
            if(isset($objectOrReflection[$key]))
                if($objectOrReflection[$key]->DefaultValue == $value || $value === null || $value === self::$DefaultSign) $objectOrReflection[$key] = null;
                elseif($objectOrReflection[$key]->Value == $value) unset($objectOrReflection[$key]);
                else $objectOrReflection[$key]->Value = $value??$objectOrReflection[$key]->DefaultValue;
        return $objectOrReflection;
    }
    /**
     * To get each Features of a Class
     * @param mixed $object
     */
    public static function Write($objectOrReflection){
        $objectOrReflection = self::Get($objectOrReflection);
        $path = $objectOrReflection->Path;
        $content = file_get_contents($path);
        $uc = 0;
        foreach (array_reverse($objectOrReflection->getArrayCopy()) as $name => $prop)
            if($prop === null || $prop->Value === $prop->DefaultValue || $prop->Value === self::$DefaultSign ){
                $start = preg_find('/^[\w\W]+(\s*class[\s\b]+\w+[\s\b]*[\w\W]*\{[\w\W]*\s+?)\$'.$name.'[^;]*(?:(?:("|\')[\W\w]*\1[^;]*)|(?:[^;"\']*);)/', $content);
                if(!is_null($start) && strlen($start) > 8)
                    $content = strrev(preg_replace('/^;(?:(?:("|\')[\W\w]*\1)|(?:\/\/.*\r?\n?\r?)|(?:\/\*[\W\w]*\*\/)|[^\};"\'])+(?=\}|;)/', "", strrev($start))).
                        substr($content, strlen($start));
                $uc++;
            }
            else {
                $start = preg_find('/^[\w\W]+(\s*class[\s\b]+\w+[\s\b]*[\w\W]*\{[\w\W]*\s+?)(?=\$'.$name.'\W)/', $content);
                if(!is_null($start) && strlen($start) > 8) {
                    $content = $start.
                        ("$".$name." = ".Convert::ToValue($prop->Value, $prop->Type??$prop->Field??takeValid($prop->Vars, 0)??$prop->Var).";").
                        preg_replace('/^\s*\$'.$name.'\s*\=?(?:(?:(\"|\')[\W\w]*\1)|(?:\/\/.*\r?\n\r?)|(?:\/\*[\W\w]*\*\/)|[^;"\']|\s)*;/U',
                            "",
                            substr($content, strlen($start))
                        );
                    $uc++;
                }
                else {
                    $start = preg_find('/^[\w\W]+\s*class[\s\:]+([^\{]+)*\{\s*/', $content);
                    if(!is_null($start) && strlen($start) > 8){
                        $indention = preg_find('/\s*$/', $start);
                        $content = $start.
                            (isEmpty($prop->Comment)?null:($prop->Comment.$indention)).
                            //"//$prop->Type;$prop->Var:".implode("|", $prop->Vars).$indention.
                            (count($prop->Modifires)<1?null:(implode(" ", $prop->Modifires)." ")).
                            //(count($prop->Vars)<1?null:(implode("|", $prop->Vars)." ")).
                            (is_null($prop->Name)?null:("$".$prop->Name)).
                            (is_null($prop->Value)?null:(" = ".Convert::ToValue($prop->Value, $prop->Type??$prop->Field??takeValid($prop->Vars, 0)??$prop->Var))).
                            ";".PHP_EOL.
                            $indention.substr($content, strlen($start));
                        $uc++;
                    } else {
                        var_dump($content);
                        throw new \SilentException("Here could not find a destination to set the '$name'!");
                    }
                }
            }
        file_put_contents($path, $content);
        return $uc;
    }

    /**
     * To get the Path of a Class
     * @param mixed $object
     */
    public static function GetPath($objectOrReflection){
        if($objectOrReflection === null) return null;
        if($objectOrReflection instanceof Reflected) return $objectOrReflection->Path;
        if($objectOrReflection instanceof \ReflectionClass) return (new Reflected($objectOrReflection))->Path;
        return (new Reflected(new \ReflectionClass($objectOrReflection)))->Path;
    }

    /**
     * To get all Features of a Class as a HTML Form
     * @param mixed $object
     */
    public static function GetForm($objectOrReflection):\MiMFa\Module\Form{
        module("Form");
        $reflection = self::Get($objectOrReflection);
        $form = new \MiMFa\Module\Form(
            title: "Edit {$reflection->Title}",
            description: $reflection->Description,
            method: "POST",
            image:"edit",
            children: self::GetFields($objectOrReflection)
        );
        $form->Id = "{$reflection->Name}EditForm";
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
    public static function GetFields($objectOrReflection){
        module("Field");
        foreach (self::Get($objectOrReflection) as $key=>$value)
            yield new \MiMFa\Module\Field(
                key:$key,
                value:($value->Value===null?null:Convert::ToValue($value->Value, false)),
                title:$value->Title,
                //description:"$value->Field;$value->Type;$value->Var:".implode("|", $value->Vars),
                description:$value->Description,
                type:($value->Field??$value->Type??takeValid($value->Vars, 0)??$value->Var)
            );
    }
    /**
     * To handle all Features received of a Class HTML Form
     * @param mixed $object
     */
    public static function HandleForm($objectOrReflection, array $newValues = null){
        try {
            if(is_null($newValues)) $newValues = \Req::Receive(null,"POST");
            $objectOrReflection = self::Update($objectOrReflection, $newValues);
            $c = count($objectOrReflection);
            if($c < 1) return Html::Warning("Here is no unsaved change!");
            else $c = self::Write($objectOrReflection);
            if($c < 1) return Html::Warning("Here is not anythings to update!");
            else return Html::Success("'$c' field(s) of data updated successfully!");
        } catch(\Exception $ex) { return Html::Error($ex); }
    }
}

class Reflected extends \ArrayObject{
    public string|null $Type = null;
    /**
     * The programmistic type
     * @var string|null
     */
    public string|null $Structure = null;
    /**
     * If used @field: the main input type
     * @var string|null
     */
    public string|null $Field = null;
    /**
     * {bool, int, float, string, array<datatype>, etc.}: to indicate the variable or constant type. other useful type can be:
	enum-string: to indicate the legal string name for a variable
	class-string: to indicate the exist class name
	interface-string: to indicate the exist interface name
	lowercase-string, non-empty-string, non-empty-lowercase-string: to indicate a non empty string, lowercased or both at once
     * @var string
     */
    public string|null $Var = null;
    /**
     * {bool, int, float, string, array<datatype>, etc.}: to indicate the variable or constant type. other useful type can be:
	enum-string: to indicate the legal string name for a variable
	class-string: to indicate the exist class name
	interface-string: to indicate the exist interface name
	lowercase-string, non-empty-string, non-empty-lowercase-string: to indicate a non empty string, lowercased or both at once
     * @var array
     */
    public array $Vars = [];
    /**
     * The default name of object
     * @var string|null
     */
    public string|null $Name = null;
    /**
     * If used @title: to specify a readable title for UI views
     * @var string|null
     */
    public string|null $Title = null;
    /**
     * If used @description: to specify a readable description for UI views
     * @var string|null
     */
    public string|null $Description = null;
    /**
     * If used @small, @medium, @large: to indicate the size of input box
     * @var float
     */
    public float $Size = 0;
    /**
     * If used @category categoryname: to specify a category to organize the documented element's package into
     * @var string|null
     */
    public string|null $Category = null;
    /**
     * If used @internal: to indicate the property should not be visible in the front-end it will be false, otherwise will be true
     * @var bool
     */
    public bool $Visible = true;
    /**
     * If used @access {public, private, protected}: to indicate access control documentation for an element, for example @access private prevents documentation of the following element (if enabled)
     * @var string|null
     */
    public string|null $Access = null;
    /**
     * If used @version versionstring [unspecified format]: to indicate the version of any element, including a page-level block
     * @var string|null
     */
    public string|null $Version = null;
    /**
     * If used @example /path/to/example.php [description]: to include an external example file with syntax highlighting
     * @var string|null
     */
    public string|null $Example = null;
    /**
     * If used @link URL [linktext]: to display a hyperlink to a URL in the documentation
     * @var string|null
     */
    public string|null $Link = null;
    /**
     * If used @see {file.ext, elementname, class::methodname(), class::$variablename, functionname(), function functionname}: to display a link to the documentation for an element, there can be unlimited number of values separated by commas
     * @var string|null
     */
    public string|null $See = null;
    /**
     * If used @author authorname: to indicate the author name of everythings. By default the authorname of everything are  Mohammad Fathi
     * @var string|null
     */
    public string|null $Author = null;
    /**
     * If used @author authorname: to indicate the author name of everythings. By default the authorname of everything are  Mohammad Fathi
     * @var array
     */
    public array $Authors = [];
    /**
     * If used @copyright copyright [information]: to document the copyright information of any element that can be documented. The default copyrights of everything are  for MiMFa Development Group
     * @var string|null
     */
    public string|null $Copyright = null;
    /**
     * If used @license URL [licensename]: to display a hyperlink to a URL for a license
     * @var string|null
     */
    public string|null $License = null;

    public array $Modifires = [];
    public mixed $DefaultValue = null;
    public mixed $Value = null;
    public string|null $Comment = null;
    public mixed $Path = null;

    public object|null $Object = null;

    public function __construct($reflection=null, $object = null){
        $this->Set($reflection, $object);
    }

    public function Set($reflection, $object = null){
        $this->Object = $object;
        if(!is_null($object) && is_null($reflection))
            if(is_subclass_of($object, "\Base")) $reflection = new \ReflectionClass($object);
            elseif(is_object($object)) $reflection = new \ReflectionObject($object);
        if(!is_null($reflection))
            if($reflection instanceof \ReflectionClass)
                $this->SetClass($reflection, $object);
            elseif($reflection instanceof \ReflectionObject)
                $this->SetObject($reflection, $object);
            elseif($reflection instanceof \ReflectionMethod)
                $this->SetMethod($reflection, $object);
            elseif($reflection instanceof \ReflectionFunction)
                $this->SetFunction($reflection, $object);
            elseif($reflection instanceof \ReflectionProperty)
                $this->SetProperty($reflection, $object);
            elseif($reflection instanceof \ReflectionAttribute)
                $this->SetAttribute($reflection, $object);
            elseif($reflection instanceof \ReflectionGenerator)
                $this->SetGenerator($reflection, $object);
            else $this->SetObject($reflection, $object);
        return $this;
    }
    public function SetClass(\ReflectionClass $reflection, $object = null){
        $this->Field = $this->Structure = "class";
        $this->Name = $reflection->getName();
        $this->Path = $reflection->getFileName();
        $parent = $reflection->getParentClass();
        $this->Type = $this->Var = $parent?($parent instanceof \ReflectionClass? $parent->getName():$parent.""):null;
        $this->Load($reflection->getDocComment());
        foreach ($reflection->getProperties() as $value){
            $prop = new Reflected($value, $object);
            if(
                $prop->Visible &&
                !in_array("readonly", $prop->Modifires) &&
                !in_array("private", $prop->Modifires) &&
                !in_array("protected", $prop->Modifires)
                ) $this[$value->getName()] = $prop;
        }
        if($parent) return $this->UpdateByClass($parent);
        else return $this;
    }
    public function UpdateByClass(\ReflectionClass $reflection, $object = null){
        $this->Load($reflection->getDocComment());
        foreach ($reflection->getProperties() as $value){
            if(isset($this[$value->getName()])){
                $prop = $this[$value->getName()];
                if(
                    $prop->Visible &&
                    !in_array("readonly", $prop->Modifires) &&
                    !in_array("private", $prop->Modifires) &&
                    !in_array("protected", $prop->Modifires)
                    ) $this[$value->getName()]->Load($value->getDocComment());
            }
        }
        $parent = $reflection->getParentClass();
        if($parent) return $this->UpdateByClass($parent);
        else return $this;
    }
    public function SetObject(\ReflectionObject $reflection, $object = null){
        $this->Field = $this->Structure = "object";
        $this->Name = $reflection->getName();
        $this->Path = $reflection->getFileName();
        $parent = $reflection->getParentClass();
        $this->Type = $this->Var = $parent?($parent instanceof \ReflectionClass? $parent->getName():$parent.""):null;
        $this->Load($reflection->getDocComment());
        foreach ($reflection->getProperties() as $value){
            $prop = new Reflected($value, $object);
            if(
                $prop->Visible &&
                !in_array("readonly", $prop->Modifires) &&
                !in_array("private", $prop->Modifires) &&
                !in_array("protected", $prop->Modifires)
                ) $this[$value->getName()] = $prop;
        }
        if($parent) return $this->UpdateByClass($parent);
        else return $this;
    }
    public function SetProperty(\ReflectionProperty $reflection, $object = null){
        $this->Structure = "property";
        $this->Name = $reflection->getName();
        $this->DefaultValue = $reflection->getDefaultValue();
        $this->Modifires = [];
        if($reflection->isPublic()) $this->Modifires[] = "public";
        if($reflection->isPrivate()) $this->Modifires[] = "private";
        if($reflection->isProtected()) $this->Modifires[] = "protected";
        if($reflection->isReadOnly()) $this->Modifires[] = "readonly";
        if($reflection->isStatic()) $this->Modifires[] = "static";
        if(!is_null($object)) {
            if(is_object($object)) $this->Value = $reflection->getValue($object);
            else $this->Value = $object;
            $this->Vars[] = $this->Type = $reflection->getType()."";
            if(!is_null($this->Value??$this->DefaultValue)){
                $t = gettype($this->Value??$this->DefaultValue);
                if(isEmpty($this->Type) || $this->Type === "mixed") $this->Type = $t;
                $this->Vars[] = $t;
            }
        }
        $this->Load($reflection->getDocComment());
        return $this;
    }
    public function SetMethod(\ReflectionMethod $reflection, $object = null){
        $this->Type = $this->Field = $this->Structure = "method";
        $this->Name = $reflection->getName();
        $this->Modifires = [];
        if($reflection->isPublic()) $this->Modifires[] = "public";
        if($reflection->isPrivate()) $this->Modifires[] = "private";
        if($reflection->isProtected()) $this->Modifires[] = "protected";
        if($reflection->isStatic()) $this->Modifires[] = "static";
        $this->Load($reflection->getDocComment());
        return $this;
    }
    public function SetFunction(\ReflectionFunction $reflection, $object = null){
        $this->Type = $this->Field = $this->Structure = "function";
        $this->Name = $reflection->getName();
        $this->Load($reflection->getDocComment());
        return $this;
    }
    public function SetGenerator(\ReflectionGenerator $reflection, $object = null){
        $this->Type = $this->Field = $this->Structure = "generator";
        $this->Name = $reflection->getFunction()->getName();
        $this->Load($reflection->getFunction()->getDocComment());
        return $this;
    }
    public function SetAttribute(\ReflectionAttribute $reflection, $object = null){
        $this->Type = $this->Field = $this->Structure = "attribute";
        $this->Name = $reflection->getName();
        $this->Load($reflection->getArguments());
        return $this;
    }

    public function Load($comment){
        $this->Comment = $comment;
        $comments = Reflect::GetCommentParameters($comment);
        $splt = "\t \r\n\f\v";
        if(isEmpty($this->Title)) $this->Title = get($comments, "title" )??Convert::ToTitle($this->Name);
        $val = get($comments, "description" )??get($comments, "abstract");
        if(!isEmpty($val)) $this->Description = $val.(isEmpty($this->Description) || $val == $this->Description?null:(PHP_EOL.$this->Description));
        if(isEmpty($this->Var)){
            $this->Var = get($comments, "var");
            if($this->Var==="mixed") $this->Var = null;
            $arr = preg_split("/\s*\|\s*/", $this->Var??"");
            if($arr) array_push($this->Vars, ...$arr);
        }
        $this->Vars = array_unique(array_filter($this->Vars, function($var){ return $var !== "mixed" && !isEmpty($var);}));
        if(isEmpty($this->Field)) $this->Field = getValid($comments, "field", null);
        if(isEmpty($this->Size)) {
            $size = get($comments, "size");
            switch ($size) {
                case "n":
                case "none":
                    $this->Size = 0;
                case "sm":
                case "small":
                    $this->Size = 0.1;
                    break;
                case "md":
                case "medium":
                    $this->Size = 0.5;
                    break;
                case "lg":
                case "large":
                    $this->Size = 1;
                    break;
                default:
                    $this->Size = (float)$size;
                    break;
            }
        }
        if(isEmpty($this->Category)) $this->Category = doValid(function($v)use($splt){ return explode($splt, $v)[0]; }, $comments, "category");
        $this->Visible = !isset($comments["internal"]);
        if(isEmpty($this->Access)) $this->Access = doValid(function($v)use($splt){ return explode($splt, $v)[0]; }, $comments, "access" );
        if(isEmpty($this->Version)) $this->Version = doValid(function($v)use($splt){ return explode($splt, $v)[0]; }, $comments, "version");
        $this->Example = get($comments, "example");
        $val = get($comments, "example");
        if(!isEmpty($val)) $this->Example = $val.(isEmpty($this->Example) || $val == $this->Example?null:(PHP_EOL.$this->Example));
        if(isEmpty($this->Link)) $this->Link = get($comments, "link");
        if(isEmpty($this->See)) $this->See = get($comments, "see");
        if(isEmpty($this->Author)) $this->Author = get($comments, "author");
        if(isValid($comments, "author")){
            array_push($this->Authors, ...preg_split("/\s*\;\s*/", get($comments, "author")));
            $this->Authors = array_unique(array_filter($this->Authors, function($var){ return !isEmpty($var);}));
        }
        if(isEmpty($this->Copyright)) $this->Copyright = get($comments, "copyright");
        if(isEmpty($this->License)) $this->License = get($comments, "license");
        if(!isEmpty($this->Vars) && (isEmpty($this->Type) || $this->Type === "mixed")) $this->Type = get($this->Vars,0);
        return $this;
    }
}
?>