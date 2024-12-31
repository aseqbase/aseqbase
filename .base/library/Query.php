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

	public static function Query(string|null $query, string|null $direction = null, string|null $type = null, string|null $tag = null, array $order = [], int $limit = -1, array|null $sources = null){
        try{
            return self::Search(query:$query, direction:$direction, type:$type, tag:$tag, order:$order, limit:$limit, sources:$sources)->current();
        }catch(\Exception $ex){ return null; }
	}
	public static function Search(string|null $query, string|null $direction = null, string|null $type = null, string|null $tag = null, array $order = [], int $limit = -1, array|null $sources = null){
        $sources = $sources??self::$Sources;
        if($sources){
            $q = self::NormalizeForSearch($query);
            foreach ($sources as $source)
                foreach ($source($query, $direction, $type, $tag, $order, $limit) as $record)
                    foreach ($record as $name=>$value)
                        if(preg_match($q, $value??"")){
                            if($limit-- === 0) return;
                            yield $record;
                            break;
                        }
        }
        else yield from self::SearchContents(query:$query, direction:$direction, type:$type, tag:$tag, nest:-1, order:$order, limit:$limit);
	}

	public static function SearchContents(string|null $query, string|null $direction = null, string|null $type = null, string|null $tag = null, int $nest = -1, array $order = [], int $limit = -1, string|null $table = null){
        $params = array();
        $condit = User::GetAccessCondition();
        $ids = self::FindCategoryIDs($direction);
        if(count($ids) > 0) {
            $condit .= " AND CategoryIDs REGEXP '(\"".join("\")|(\"",$ids)."\")'";
        }
        $tid = self::FindTagID($tag);
        if(!is_null($tid)) {
            $condit .= " AND TagIDs REGEXP '\"$tid\"'";
        }
        if(isValid($type)){
            $params[":Type"] = $type;
            $condit .= " AND `Type`=:Type";
        }
        if(isValid($query)){
            $qs = self::NormalizeForDataBaseSearch($query);
            $condit .= " AND (`Title` $qs OR `Name` $qs OR `Description` $qs OR `Content` $qs)";
        }
        //echo DataBase::MakeSelectQuery(\_::$CONFIG->DataBasePrefix."Content","*", $condit.(count($order)>0?" ORDER BY".join(", ", loop($order, function($k,$v){ if(preg_match("/^\w+$/",$k??"") && preg_match("/^\w*$/",$v??"")) return "`$k` $v";})):" ORDER BY `Priority` DESC, `UpdateTime` DESC").($limit < 0?"":" LIMIT ".intval($limit)), $params);
        return DataBase::DoSelect($table??\_::$CONFIG->DataBasePrefix."Content","*", $condit.(count($order)>0?" ORDER BY".join(", ", loop($order, function($k,$v){ if(preg_match("/^\w+$/",$k??"") && preg_match("/^\w*$/",$v??"")) return "`$k` $v";})):" ORDER BY `Priority` DESC, `UpdateTime` DESC").($limit < 0?"":" LIMIT ".intval($limit)), $params);
	}

	public static function FindContent(string|null $name, string|null $direction = null, string|null $type = null, string|null $tag = null, string|null $table = null){
        $params = array();
        $condit = User::GetAccessCondition();
        $id = self::FindCategoryID($direction);
        if(!is_null($id)) {
            $condit .= " AND CategoryIDs REGEXP '\"$id\"'";
        }
        $tid = self::FindTagID($tag);
        if(!is_null($tid)) {
            $condit .= " AND TagIDs REGEXP '\"$tid\"'";
        }
        if(isValid($type)){
            $params[":Type"] = $type;
            $condit .= " AND `Type`=:Type";
        }
        if(isValid($name)){
            $params[":Name"] = $name;
            $params[":ID"] = $name;
            $condit .= " AND (`Name`=:Name OR `ID`=:ID)";
        }
        return DataBase::DoSelectRow($table??\_::$CONFIG->DataBasePrefix."Content","*", $condit." ORDER BY `Priority` DESC, `UpdateTime` DESC", $params);
	}

	public static function FindCategoryIDs(string|null $direction, array $default = [], int $nest = -1, string|null $table = null){
        $id = self::FindCategoryID($direction, null);
        if(isEmpty($id)) return $default;
        $condit = User::GetAccessCondition();
        $parentIDs = [$id];
        $newparentIDs = [$id];
        while ($nest-- !== 0 && !isEmpty($newparentIDs = DataBase::DoSelectColumn(
                $table??\_::$CONFIG->DataBasePrefix."Category",
                "`ID`",
                (count($newparentIDs)>0?"`ParentID` IN (".join(",", $newparentIDs).") AND ":"").$condit,
                null)))
                $parentIDs = array_merge($parentIDs, $newparentIDs);
        return $parentIDs;
	}
	public static function FindCategoryID(string|null $direction, int|null $default = null, string|null $table = null){
        if(isEmpty($direction)) return $default;
        $condit = User::GetAccessCondition();
        $paths = explode("/",trim($direction??"", "/\\"));
        $parentID = null;
        $id = null;
        foreach ($paths as $name)
        {
            $parentID = $id;
            $id = DataBase::DoSelectValue(
                $table??\_::$CONFIG->DataBasePrefix."Category",
                "`ID`",
                "`Name`=:Name AND ".(is_null($parentID)?"":"`ParentID`=".$parentID." AND ").$condit,
                [":Name"=>$name],
                null
            );
            if(is_null($id)) return $default;
        }
        return is_null($id)? $default : $id;
	}
	public static function FindCategory(string|null $direction, array|null $default = null, string|null $table = null){
        $id = self::FindCategoryID($direction, null);
        if(isEmpty($id)) return $default;
        return DataBase::DoSelectRow($table??\_::$CONFIG->DataBasePrefix."Category", "*","`ID`=:ID AND ".User::GetAccessCondition(),[":ID"=>$id]);
    }

    public static function FindTagID(string|null $tag, int|null $default = null, string|null $table = null){
        if(isEmpty($tag)) return $default;
        $id = DataBase::DoSelectValue(
                $table??\_::$CONFIG->DataBasePrefix."Tag",
                "`ID`",
                "`Name`=:Name",
                [":Name"=>$tag],
                null
            );
        return is_null($id)? $default : $id;
	}
	public static function FindTag(string|null $tag, array|null $default = null, string|null $table = null){
        $id = self::FindTagID($tag, null);
        if(isEmpty($id)) return $default;
        return DataBase::DoSelectRow($table??\_::$CONFIG->DataBasePrefix."Tag", "*","`ID`=:ID",[":ID"=>$id]);
    }


    public static function GetContentCategoryDirection(array|string $content, string|null $default = null, string|null $table = null){
        return self::GetCategoryDirection(self::GetContentCategoryID($content), $default, $table);
    }
    public static function GetContentCategoryIDs(array|string $content, array|null $default = [], string|null $table = null){
        $content = is_array($content)?$content:self::FindContent($content, null, table:$table);
        if(isEmpty($content)) return $default;
        return Convert::FromJSON(getValid($content,"CategoryIDs","{}"))??$default;
    }
    public static function GetContentCategoryID(array|string $content, string|null $default = null, string|null $table = null){
        return first(self::GetContentCategoryIDs($content, [], $table), default:$default);
    }
    public static function GetContentCategory(array|string $content, string|null $default = null, string|null $table = null){
        $id = self::GetContentCategoryID($content, null, $table);
        if(isEmpty($id)) return $default;
        return DataBase::DoSelectRow(\_::$CONFIG->DataBasePrefix."Category", "*","`ID`=:ID AND ".User::GetAccessCondition(),[":ID"=>$id]);
    }

    public static function GetContentTagIDs(array|string $content, array|null $default = [], string|null $table = null){
        $content = is_array($content)?$content:self::FindContent($content, null, table:$table);
        if(isEmpty($content)) return $default;
        return Convert::FromJSON(getValid($content,"TagIDs","{}"))??$default;
    }
    public static function GetContentTagID(array|string $content, string|null $default = null, string|null $table = null){
        return first(self::GetContentTagIDs($content, [], $table), default:$default);
    }
    public static function GetContentTag(array|string $content, string|null $default = null, string|null $table = null){
        $id = self::GetContentTagID($content, null, table:$table);
        if(isEmpty($id)) return $default;
        return DataBase::DoSelectRow(\_::$CONFIG->DataBasePrefix."Tag", "*","`ID`=:ID",[":ID"=>$id]);
    }

    private static array $Cache_CategoryDirection = [];
    public static function GetCategoryDirection(array|string|int|null $category, string|null $default = null, string|null $table = null){
        if(isEmpty($category)) return $default;
        if(isset(self::$Cache_CategoryDirection[$category])) return self::$Cache_CategoryDirection[$category];
        $cat = is_array($category)?$category:DataBase::DoSelectRow(\_::$CONFIG->DataBasePrefix."Category", "*","(`Name`=:Name OR `ID`=:ID) AND ".User::GetAccessCondition(),[":Name"=>$category,":ID"=>$category]);
        if(isEmpty($cat)) return $default;
        return self::$Cache_CategoryDirection[$category] = self::GetCategoryDirection(getValid($cat,"ParentID"), null)."/".getBetween($cat,"Name","ID");
    }


	public static function NormalizeForSearch(string|null $query){
        if(is_null($query)) return "/\w*/i";
        return "/".trim(preg_replace("/[\s\-\{\}\/\?\.\,\<\>\'\"\&\*\(\)\!\@\#\$\~\`\+\=\:\;\|]+/","|",$query), "|")."/i";
    }
	public static function NormalizeForDataBaseSearch(string|null $query){
        if(is_null($query)) return "REGEXP '\w*'";
        return "REGEXP '".trim(preg_replace("/[\s\-\{\}\/\?\.\,\<\>\'\"\&\*\(\)\!\@\#\$\~\`\+\=\:\;\|]+/","|",$query), "|")."'";
    }
}
?>