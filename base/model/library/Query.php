<?php
namespace MiMFa\Library;
require_once "DataBase.php";
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
    public array|string|null $ColumnNames = null;
    public DataBase $DataBase;

    public function __construct(DataBase $dataBase, ...$sources)
    {
        $this->DataBase = $dataBase;
        $this->Sources = $sources ?? [];
    }

    public function Query(string|null $query, string|array|null $direction = null, string|null $type = null, string|null $tag = null, string|array|null $condition = [], string|array|null $order = [], int|string|null $limit = -1, array $params = [], array|null $sources = null)
    {
        try {
            return $this->Search(query: $query, direction: $direction, type: $type, tag: $tag, condition: $condition, order: $order, limit: $limit, params:$params, sources: $sources)->current();
        } catch (\Exception $ex) {
            return null;
        }
    }
    public function Search(string|null $query, string|array|null $direction = null, string|null $type = null, string|null $tag = null, string|array|null $condition = [], string|array|null $order = [], int|string|null $limit = -1, array $params = [], array|null $sources = null)
    {
        $sources = $sources ?? $this->Sources;
        if ($sources) {
            $q = $this->NormalizeForSearch($query);
            foreach ($sources as $source) foreach ($source($query, $direction, $type, $tag, $condition, $order, $limit, $params) as $record) foreach ($record as $name => $value)
                        if (preg_match($q, $value ?? "")) {
                            if ($limit-- === 0)
                                return;
                            yield $record;
                            break;
                        }
        } else
            yield from $this->SearchContents(query: $query, direction: $direction, type: $type, tag: $tag, nest: -1, condition: $condition, order: $order, limit: $limit, params:$params);
    }

    public function SearchContents(string|null $query, string|array|null $direction = null, string|null $type = null, string|null $tag = null, int $nest = -1, string|array|null $condition = [], string|array|null $order = [], int|string|null $limit = -1, array $params = [], DataTable|string|null $table = null)
    {
        $table = $table instanceof DataTable?$table:table($table ?? "Content", source: $this->DataBase);
        $condit = "";
        if (!isEmpty($condition))
            $condit = $this->DataBase->ConditionNormalization([$condition, authCondition(tableName:$table->Name)]);
        else
            $condit = $this->DataBase->ConditionNormalization(authCondition(tableName:$table->Name));
        $cids = $this->FindCategoryIds($direction, nest: $nest);
        if (count($cids) > 0) {
            $condit .= " AND $table->Name.CategoryIds REGEXP '\\\\D(" . join("|", $cids) . ")\\\\D'";
        }
        $tid = $this->FindTagId($tag);
        if (!is_null($tid)) {
            $condit .= " AND $table->Name.TagIds REGEXP '\\\\D$tid\\\\D'";
        }
        if (isValid($type)) {
            $params[":Type"] = $type;
            $condit .= " AND $table->Name.Type=:Type";
        }
        if (isValid($query)) {
            $qs = $this->NormalizeForDataBaseSearch($query);
            $condit .= " AND ($table->Name.Title $qs OR $table->Name.Name $qs OR $table->Name.Description $qs OR $table->Name.Content $qs)";
        }
        $order = $this->DataBase->OrderNormalization($order);
        return $table->Select(
            $this->ColumnNames,
            "$condit $order " . $this->DataBase->LimitNormalization($limit),
            $params
        );
    }

    public function FindContent(string|null $name, string|array|null $direction = null, string|null $type = null, string|null $tag = null, string|array|null $condition = [], array $params = [], DataTable|string|null $table = null)
    {
        $table = $table instanceof DataTable?$table:table($table ?? "Content", source: $this->DataBase);
        $condit = "";
        if (!isEmpty($condition))
            $condit = $this->DataBase->ConditionNormalization([$condition, authCondition(tableName:$table->Name)]);
        else
            $condit = $this->DataBase->ConditionNormalization(authCondition(tableName:$table->Name));
        if (isValid($name)) {
            $params[":Name"] = $name;
            $params[":Id"] = $name;
            $condit .= " AND ($table->Name.Name=:Name OR $table->Name.Id=:Id)";
        }
        if (isValid($type)) {
            $params[":Type"] = $type;
            $condit .= " AND $table->Name.Type=:Type";
        }
        $cid = $this->FindCategoryId($direction);
        if (!isEmpty($cid)) {
            $condit .= " AND $table->Name.CategoryIds REGEXP '\\\\D$cid\\\\D'";
        }
        $tid = $this->FindTagId($tag);
        if (!isEmpty($tid)) {
            $condit .= " AND $table->Name.TagIds REGEXP '\\\\D$tid\\\\D'";
        }
        return $table->SelectRow(
            $this->ColumnNames,
            $condit . " ORDER BY $table->Name.Priority DESC, $table->Name.UpdateTime DESC",
            $params);
    }

    public function FindCategoryIds(string|array|null $direction, array $default = [], int $nest = -1, DataTable|string|null $table = null)
    {
        $table = $table instanceof DataTable?$table:table($table ?? "Category", source: $this->DataBase);
        $id = $this->FindCategoryId($direction, null, table:$table);
        if (isEmpty($id))
            return $default;
        $condit = authCondition(tableName:$table->Name);
        $parentIds = $id?[$id]:[];
        $newparentIds = $id?[$id]:[];
        while (
            $nest-- !== 0 && !isEmpty(
                $newparentIds =
                $table->SelectColumn(
                    "$table->Name.Id",
                    ($newparentIds ? " $table->Name.ParentId IN (" . join(",", $newparentIds) . ") AND " : "") . $condit,
                    null
                )
            )
        )
            $parentIds = array_merge($parentIds, $newparentIds);
        return $parentIds;
    }
    public function FindCategoryId(string|array|null $direction, int|null $default = null, DataTable|string|null $table = null)
    {
        if (isEmpty($direction))
            return $default;
        $table = $table instanceof DataTable?$table:table($table ?? "Category", source: $this->DataBase);
        $condit = authCondition(tableName:$table->Name);
        $paths = is_array($direction) ? $direction : explode("/", trim($direction ?? "", "/\\"));
        $parentId = null;
        $id = null;
        foreach ($paths as $name) {
            $parentId = $id;
            $id = $table ->SelectValue(
                "$table->Name.Id",
                "($table->Name.Id=:Id OR $table->Name.Name=:Name) AND " . ($parentId?"$table->Name.ParentId=$parentId AND ":"") . $condit,
                [":Id" => $name, ":Name" => $name],
                null
            );
            if (is_null($id))
                return $default;
        }
        return $id ?? $default;
    }
    public function FindCategories(string|array|null $direction, array $default = [], int $nest = -1, DataTable|string|null $table = null)
    {
        $table = $table instanceof DataTable?$table:table($table ?? "Category", source: $this->DataBase);
        $ids = $this->FindCategoryIds($direction, [], $nest, $table);
        if (!$ids)
            return $default;
        return $table->Select("*", "$table->Name.Id IN (".join(",", $ids).") AND " . authCondition(tableName:$table->Name));
    }
    public function FindCategory(string|array|null $direction, array|null $default = null, DataTable|string|null $table = null)
    {
        $table = $table instanceof DataTable?$table:table($table ?? "Category", source: $this->DataBase);
        $id = $this->FindCategoryId($direction, null, table:$table);
        if (!$id)
            return $default;
        return $table->SelectRow("*", "$table->Name.Id=:Id AND " . authCondition(tableName:$table->Name), [":Id" => $id]);
    }

    public function FindTagId(string|null $tag, int|null $default = null, DataTable|string|null $table = null)
    {
        if (isEmpty($tag))
            return $default;
        $table = $table instanceof DataTable?$table:table($table ?? "Tag", source: $this->DataBase);
        return $table->SelectValue(
            "$table->Name.Id",
            "$table->Name.Id=:Id OR $table->Name.Name=:Name",
            [":Id" => $tag, ":Name" => $tag],
            null
        ) ?? $default;
    }
    public function FindTag(string|null $tag, array|null $default = null, DataTable|string|null $table = null)
    {
        $table = $table instanceof DataTable?$table:table($table ?? "Tag", source: $this->DataBase);
        $id = $this->FindTagId($tag, null, table:$table);
        if (!$id)
            return $default;
        return $table->SelectRow("*", "$table->Name.Id=:Id", [":Id" => $id]);
    }


    public function GetContentCategoryRoute(array|string $content, string|null $default = null, DataTable|string|null $table = null)
    {
        return $this->GetCategoryRoute($this->GetContentCategoryId($content, table: $table), default: $default);
    }
    public function GetContentCategoryIds(array|string $content, array|null $default = [], DataTable|string|null $table = null)
    {
        $content = is_array($content) ? $content : $this->FindContent($content, null, table: $table);
        if (isEmpty($content))
            return $default;
        return Convert::FromJson(takeValid($content, "CategoryIds", "{}")) ?? $default;
    }
    public function GetContentCategoryId(array|string $content, string|null $default = null, DataTable|string|null $table = null)
    {
        return first($this->GetContentCategoryIds($content, [], table: $table), default: $default);
    }
    public function GetContentCategory(array|string $content, string|null $default = null, DataTable|string|null $table = null)
    {
        $id = $this->GetContentCategoryId($content, null, $table);
        if (isEmpty($id))
            return $default;
        $table = table("Category", source: $this->DataBase);
        return $table->SelectRow("*", "$table->Name.Id=:Id AND " . authCondition(tableName:$table->Name), [":Id" => $id]);
    }

    public function GetContentTagIds(array|string $content, array|null $default = [], DataTable|string|null $table = null)
    {
        $content = is_array($content) ? $content : $this->FindContent($content, null, table: $table);
        if (isEmpty($content))
            return $default;
        return Convert::FromJson(takeValid($content, "TagIds", "{}")) ?? $default;
    }
    public function GetContentTagId(array|string $content, string|null $default = null, DataTable|string|null $table = null)
    {
        return first($this->GetContentTagIds($content, [], $table), default: $default);
    }
    public function GetContentTag(array|string $content, string|null $default = null, DataTable|string|null $table = null)
    {
        $id = $this->GetContentTagId($content, null, table: $table);
        if (isEmpty($id))
            return $default;
        $table = table("Tag", source: $this->DataBase);
        return $table->SelectRow("*", "$table->Name.Id=:Id", [":Id" => $id]);
    }

    private array $Cache_CategoryRoutes = [];
    public function GetCategoryRoute(array|string|int|null $category, string|null $default = null, DataTable|string|null $table = null)
    {
        if (isEmpty($category))
            return $default;
        if (isset($this->Cache_CategoryRoutes[$category]))
            return $this->Cache_CategoryRoutes[$category];
        $table = $table instanceof DataTable?$table:table($table ?? "Category", source: $this->DataBase);
        $cat = is_array(value: $category) ? $category : $table->SelectRow("*", "($table->Name.Name=:Name OR $table->Name.Id=:Id) AND " . authCondition(tableName:$table->Name), [":Name" => $category, ":Id" => $category]);
        if (isEmpty($cat))
            return $default;
        return $this->Cache_CategoryRoutes[$category] = $this->GetCategoryRoute(takeValid($cat, "ParentId"), null, table:$table) . "/" . takeBetween($cat, "Name", "Id");
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