<?php namespace MiMFa\Library;
/**
 * A query service library to access all database records and pages by various fast, flexible and secure methods
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#search See the Library Documentation
*/
use MiMFa\Library\DataBase;
class Query{
    /**
     * An array of callable sources
     * @var array<callable>|null
     */
    public static array|null $Sources = array();

	public static function Query(string|null $query, string|null $groupDirection = null, string|null $type = null, array|null $sources = null){
        try{
            return self::Search($query, $groupDirection, $type, $sources)->current();
        }catch(\Exception $ex){ return null; }
	}
	public static function Search(string|null $query, string|null $groupDirection = null, string|null $type = null, array|null $sources = null){
        $sources = $sources??self::$Sources;
        if($sources && count($sources) > 0){
            $q = self::NormalizeForSearch($query);
            foreach ($sources as $source)
                foreach ($source($query,$groupDirection, $type) as $record)
                    foreach ($record as $name=>$value)
                    if(preg_match($q,$value)){
                        yield $record;
                        break;
                    }
        }
        else yield from self::SearchContents($query,$groupDirection, $type);
	}

	public static function SearchContents(string|null $query, string|null $groupDirection = null, string|null $type = null){
        $acc = getAccess();
        $params = array();
        $condit = "`Access`<=$acc";
        $Id = self::FindGroupID($groupDirection, null);
        if(!isEmpty($Id)) {
            $condit .= " AND GroupIDs LIKE \"%|".$Id."%\"";
        }
        if(isValid($type)){
            $params[":Type"] = $type;
            $condit .= " AND `Type`=:Type";
        }
        if(isValid($query)){
            $qs = self::NormalizeForDataBaseSearch($query);
            $condit .= " AND (`Title` $qs OR `Description` $qs OR `Content` $qs)";
        }
        return DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Content","*", $condit, $params);
	}

	public static function FindGroupID(string|null $direction, int|null $default = null){
        $paths = explode("/",trim($direction??"","/\\"));
        $parentID = "%";
        $len = count($paths);
        $acc = getAccess();
        for ($i = 0; $i < $len && !isEmpty($parentID); $i++)
            $parentID = DataBase::DoSelectValue(
                \_::$CONFIG->DataBasePrefix."Group",
                "`ID`",
                "`Name`=:Name AND `ID` LIKE '".$parentID."%' AND `Access`<=".$acc." ORDER BY `ID` LIMIT 0,1;",
                array(":Name"=>$paths[$i]),
                $parentID
            );
        return (isEmpty($parentID) || $parentID === "%")? $default : $parentID;
	}
	public static function FindGroup(string|null $direction, array|null $default = null){
        $Id = self::FindGroupID($direction, null);
        if(isEmpty($Id)) return $default;
        $acc = getAccess();
        return DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Group", "*", "`Access`<=".$acc." AND `ID`=$Id");
    }

	public static function NormalizeForSearch(string|null $query){
        return preg_replace("/\W+/","|",$query);
    }
	public static function NormalizeForDataBaseSearch(string|null $query){
        return "REGEXP '".self::NormalizeForSearch($query)."'";
    }
}
?>