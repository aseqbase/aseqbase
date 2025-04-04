<?php namespace MiMFa\Library;
require_once "DataBase.php";
require_once "User.php";
use MiMFa\Library\User;
use MiMFa\Library\DataBase;
/**
 * A query service library to access all database records and pages by various fast, flexible and secure methods
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#query See the Library Documentation
 */
class Query
{
    /**
     * An array of callable sources
     * @var array<callable>|null
     */
    public array|null $Sources = array();
    public DataBase $DataBase;

    public function __construct(DataBase $dataBase, ...$sources){
        $this->DataBase = $dataBase;
        $this->Sources = $sources??[];
    }

    public function Query(string|null $query, string|null $direction = null, string|null $type = null, string|null $tag = null, string|array|null $order = [], int $limit = -1, array|null $sources = null)
    {
        try {
            return $this->Search(query: $query, direction: $direction, type: $type, tag: $tag, order: $order, limit: $limit, sources: $sources)->current();
        } catch (\Exception $ex) {
            return null;
        }
    }
    public function Search(string|null $query, string|null $direction = null, string|null $type = null, string|null $tag = null, string|array|null $order = [], int $limit = -1, array|null $sources = null)
    {
        $sources = $sources ?? $this->Sources;
        if ($sources) {
            $q = $this->NormalizeForSearch($query);
            foreach ($sources as $source) foreach ($source($query, $direction, $type, $tag, $order, $limit) as $record) foreach ($record as $name => $value)
                        if (preg_match($q, $value ?? "")) {
                            if ($limit-- === 0)
                                return;
                            yield $record;
                            break;
                        }
        } else
            yield from $this->SearchContents(query: $query, direction: $direction, type: $type, tag: $tag, nest: -1, order: $order, limit: $limit);
    }

    public function SearchContents(string|null $query, string|null $direction = null, string|null $type = null, string|null $tag = null, int $nest = -1, string|array|null $order = [], int $limit = -1, string|null $table = null)
    {
        $params = array();
        $condit = User::GetAccessCondition();
        $ids = $this->FindCategoryIds($direction, nest:$nest);
        if (count($ids) > 0) {
            $condit .= " AND CategoryIds REGEXP '(\"" . join("\")|(\"", $ids) . "\")'";
        }
        $tid = $this->FindTagId($tag);
        if (!is_null($tid)) {
            $condit .= " AND TagIds REGEXP '\"$tid\"'";
        }
        if (isValid($type)) {
            $params[":Type"] = $type;
            $condit .= " AND `Type`=:Type";
        }
        if (isValid($query)) {
            $qs = $this->NormalizeForDataBaseSearch($query);
            $condit .= " AND (`Title` $qs OR `Name` $qs OR `Description` $qs OR `Content` $qs)";
        }

        $order = isValid($order)?
        (
            is_string($order)?
            $order:
            (
                count($order) > 0 ?
                    join(", ", loop($order,
                        function ($k, $v, $i) {
                    if (preg_match("/^\w*$/", $v ?? ""))
                        if ($k !== $i) return "`$k` $v";
                        else return $v;
                        })) :
                    "`Priority` DESC, `UpdateTime` DESC"
            )
        ):null;
        return table($table ?? "Content" , source:$this->DataBase)->DoSelect(
            "*",
            $condit . (isValid($order)?" ORDER BY $order":"") . ($limit < 0 ? "" : " LIMIT " . intval($limit)),
            $params
        );
    }

    public function FindContent(string|null $name, string|null $direction = null, string|null $type = null, string|null $tag = null, string|null $table = null)
    {
        $params = array();
        $condit = User::GetAccessCondition();
        if (isValid($name)) {
            $params[":Name"] = $name;
            $params[":Id"] = $name;
            $condit .= " AND (`Name`=:Name OR `Id`=:Id)";
        }
        if (isValid($type)) {
            $params[":Type"] = $type;
            $condit .= " AND `Type`=:Type";
        }
        $id = $this->FindCategoryId($direction);
        if (!is_null($id)) {
            $condit .= " AND CategoryIds REGEXP '\"$id\"'";
        }
        $tid = $this->FindTagId($tag);
        if (!is_null($tid)) {
            $condit .= " AND TagIds REGEXP '\"$tid\"'";
        }
        return table($table ?? "Content" , source:$this->DataBase)->DoSelectRow("*", $condit . " ORDER BY `Priority` DESC, `UpdateTime` DESC", $params);
    }

    public function FindCategoryIds(string|null $direction, array $default = [], int $nest = -1, string|null $table = null)
    {
        $id = $this->FindCategoryId($direction, null);
        if (isEmpty($id))
            return $default;
        $condit = User::GetAccessCondition();
        $parentIds = [$id];
        $newparentIds = [$id];
        while (
            $nest-- !== 0 && !isEmpty($newparentIds =
                table($table ?? "Category", source:$this->DataBase)->DoSelectColumn(
                    "`Id`",
                    (count($newparentIds) > 0 ? " `ParentId` IN (" . join(",", $newparentIds) . ") AND " : "") . $condit,
                    null
                )
            )
        )
            $parentIds = array_merge($parentIds, $newparentIds);
        return $parentIds;
    }
    public function FindCategoryId(string|null $direction, int|null $default = null, string|null $table = null)
    {
        if (isEmpty($direction))
            return $default;
        $condit = User::GetAccessCondition();
        $paths = explode("/", trim($direction ?? "", "/\\"));
        $parentId = null;
        $id = null;
        foreach ($paths as $name) {
            $parentId = $id;
            $id = table($table ?? "Category", source:$this->DataBase)->DoSelectValue(
                "`Id`",
                "(`Id`=:Id OR `Name`=:Name) AND " . (is_null($parentId) ? "" : ("`ParentId`=" . $parentId . " AND ")) . $condit,
                [":Id" => $name, ":Name" => $name],
                null
            );
            if (is_null($id))
                return $parentId??$default;
        }
        return $id?? $default;
    }
    public function FindCategory(string|null $direction, array|null $default = null, string|null $table = null)
    {
        $id = $this->FindCategoryId($direction, null);
        if (!$id) return $default;
        return table($table ?? "Category", source:$this->DataBase)->DoSelectRow("*", "`Id`=:Id AND " . User::GetAccessCondition(), [":Id" => $id]);
    }

    public function FindTagId(string|null $tag, int|null $default = null, string|null $table = null)
    {
        if (isEmpty($tag))
            return $default;
        $id = table($table ?? "Tag", source:$this->DataBase)->DoSelectValue(
            "`Id`",
            "`Id`=:Id OR `Name`=:Name",
            [":Id" => $tag, ":Name" => $tag],
            null
        );
        return $id?? $default;
    }
    public function FindTag(string|null $tag, array|null $default = null, string|null $table = null)
    {
        $id = $this->FindTagId($tag, null);
        if (!$id) return $default;
        return table($table ?? "Tag", source:$this->DataBase)->DoSelectRow("*", "`Id`=:Id", [":Id" => $id]);
    }


    public function GetContentCategoryRoute(array|string $content, string|null $default = null, string|null $table = null)
    {
        return $this->GetCategoryRoute($this->GetContentCategoryId($content), $default, $table);
    }
    public function GetContentCategoryIds(array|string $content, array|null $default = [], string|null $table = null)
    {
        $content = is_array($content) ? $content : $this->FindContent($content, null, table: $table);
        if (isEmpty($content))
            return $default;
        return Convert::FromJson(takeValid($content, "CategoryIds" , "{}")) ?? $default;
    }
    public function GetContentCategoryId(array|string $content, string|null $default = null, string|null $table = null)
    {
        return first($this->GetContentCategoryIds($content, [], $table), default: $default);
    }
    public function GetContentCategory(array|string $content, string|null $default = null, string|null $table = null)
    {
        $id = $this->GetContentCategoryId($content, null, $table);
        if (isEmpty($id))
            return $default;
        return table($table ?? "Category", source:$this->DataBase)->DoSelectRow("*", "`Id`=:Id AND " . User::GetAccessCondition(), [":Id" => $id]);
    }

    public function GetContentTagIds(array|string $content, array|null $default = [], string|null $table = null)
    {
        $content = is_array($content) ? $content : $this->FindContent($content, null, table: $table);
        if (isEmpty($content))
            return $default;
        return Convert::FromJson(takeValid($content, "TagIds" , "{}")) ?? $default;
    }
    public function GetContentTagId(array|string $content, string|null $default = null, string|null $table = null)
    {
        return first($this->GetContentTagIds($content, [], $table), default: $default);
    }
    public function GetContentTag(array|string $content, string|null $default = null, string|null $table = null)
    {
        $id = $this->GetContentTagId($content, null, table: $table);
        if (isEmpty($id))
            return $default;
        return table($table ?? "Tag", source:$this->DataBase)->DoSelectRow("*", "`Id`=:Id", [":Id" => $id]);
    }

    private array $Cache_CategoryRoutes = [];
    public function GetCategoryRoute(array|string|int|null $category, string|null $default = null, string|null $table = null)
    {
        if (isEmpty($category))
            return $default;
        if (isset($this->Cache_CategoryRoutes[$category]))
            return $this->Cache_CategoryRoutes[$category];
        $cat = is_array($category) ? $category : table($table ?? "Category", source:$this->DataBase)->DoSelectRow("*", "(`Name`=:Name OR `Id`=:Id) AND " . User::GetAccessCondition(), [":Name" => $category, ":Id" => $category]);
        if (isEmpty($cat))
            return $default;
        return $this->Cache_CategoryRoutes[$category] = $this->GetCategoryRoute(takeValid($cat,  "ParentId" ), null) . "/" . takeBetween($cat, "Name" , "Id" );
    }


    public function NormalizeForSearch(string|null $query)
    {
        if (is_null($query))
            return "/\w*/i";
        return "/" . trim(preg_replace("/[\s\-\{\}\/\?\.\,\<\>\'\"\&\*\(\)\!\@\#\$\~\`\+\=\:\;\|]+/", "|", $query), "|") . "/i";
    }
    public function NormalizeForDataBaseSearch(string|null $query)
    {
        if (is_null($query))
            return "REGEXP '\w*'";
        return "REGEXP '" . trim(preg_replace("/[\s\-\{\}\/\?\.\,\<\>\'\"\&\*\(\)\!\@\#\$\~\`\+\=\:\;\|]+/", "|", $query), "|") . "'";
    }
}
?>