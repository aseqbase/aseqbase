<?php namespace MiMFa\Library;
/**
 * A query service library to access all database records and pages by various fast, flexible and secure methods
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#query See the Library Documentation
*/
use MiMFa\Library\DataBase;
use MiMFa\Library\User;
class Query{
    /**
     * An array of callable sources
     * @var array<callable>|null
     */
    public static array|null $Sources = array();

	public static function Query(string|null $query, string|null $categoryDirection = null, string|null $type = null, array|null $sources = null){
        try{
            return self::Search($query, $categoryDirection, $type, $sources)->current();
        }catch(\Exception $ex){ return null; }
	}
	public static function Search(string|null $query, string|null $categoryDirection = null, string|null $type = null, array|null $sources = null){
        $sources = $sources??self::$Sources;
        if($sources && count($sources) > 0){
            $q = self::NormalizeForSearch($query);
            foreach ($sources as $source)
                foreach ($source($query, $categoryDirection, $type) as $record)
                    foreach ($record as $name=>$value)
                        if(preg_match($q, $value??"")){
                            yield $record;
                            break;
                        }
        }
        else yield from self::SearchContents($query, $categoryDirection, $type, -1);
	}

	public static function SearchContents(string|null $query, string|null $categoryDirection = null, string|null $type = null, int $nest = -1){
        $params = array();
        $condit = User::GetAccessCondition();
        $Ids = self::FindCategoryIDs($categoryDirection);
        if(!isEmpty($Ids)) {
            $condit .= " AND CategoryIDs REGEXP '(\"".join("\")|(\"",$Ids)."\")'";
        }
        if(isValid($type)){
            $params[":Type"] = $type;
            $condit .= " AND `Type`=:Type";
        }
        if(isValid($query)){
            $qs = self::NormalizeForDataBaseSearch($query);
            $condit .= " AND (`Title` $qs OR `Name` $qs OR `Description` $qs OR `Content` $qs)";
        }
        return DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Content","*", $condit." ORDER BY `Priority` DESC", $params);
	}

	public static function FindCategoryIDs(string|null $direction, array $default = [], int $nest = -1){
        $Id = self::FindCategoryID($direction, null);
        if(isEmpty($Id)) return $default;
        $condit = User::GetAccessCondition();
        $parentIDs = [$Id];
        $newparentIDs = [$Id];
        while ($nest-- !== 0 && $newparentIDs = DataBase::DoSelectColumn(
                \_::$CONFIG->DataBasePrefix."Category",
                "`ID`",
                "`ParentID` IN (".join(",",$newparentIDs).") AND ".$condit.";",
                null))
                $parentIDs = array_merge($parentIDs, $newparentIDs);
        return $parentIDs;
	}
	public static function FindCategoryID(string|null $direction, int|null $default = null){
        $condit = User::GetAccessCondition();
        $paths = explode("/",trim($direction??"","/\\"));
        $parentID = null;
        $len = count($paths);
        for ($i = 0; $i < $len && $parentID !== ""; $i++)
            $parentID = DataBase::DoSelectValue(
                \_::$CONFIG->DataBasePrefix."Category",
                "`ID`",
                "`Name`=:Name AND ".(is_null($parentID)?"":"`ParentID`=".$parentID." AND ").$condit." ORDER BY `ParentID` LIMIT 0,1;",
                array(":Name"=>$paths[$i]),
                ""
            );
        return (isEmpty($parentID))? $default : $parentID;
	}
	public static function FindCategory(string|null $direction, array|null $default = null){
        $Id = self::FindCategoryID($direction, null);
        if(isEmpty($Id)) return $default;
        return DataBase::DoSelectRow(\_::$CONFIG->DataBasePrefix."Category", "*","`ID`=:ID AND ".User::GetAccessCondition(),[":ID"=>$Id]);
    }

	public static function NormalizeForSearch(string|null $query){
        return "/".trim(preg_replace("/[\s\-\{\}\/\?\.\,\<\>\'\"\&\*\(\)\!\@\#\$\~\`\+\=\:\;]+/","|",$query), "|")."/i";
    }
	public static function NormalizeForDataBaseSearch(string|null $query){
        return "REGEXP '".trim(preg_replace("/[\s\-\{\}\/\?\.\,\<\>\'\"\&\*\(\)\!\@\#\$\~\`\+\=\:\;]+/","|",$query), "|")."'";
    }
}
?>