<?php
namespace MiMFa\Module;

use DateTime;
use MiMFa\Library\Html;
use MiMFa\Library\Convert;
use MiMFa\Library\Style;
use MiMFa\Library\Local;
use MiMFa\Library\DataTable;
module("Navigation");
/**
 * To show a table of items
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules/Table See the Documentation
 */
class Table extends Module
{
    //public $Tag = "table";

    public $Modal = null;
    public Navigation|null $NavigationBar = null;
    public $TopNavigation = true;
    public $BottomNavigation = true;

    /**
     * The database table, to get items automatically
     * @var DataTable
     */
    public $DataTable = null;
    /**
     * An array of items, or a Key-Value based array of features
     * @var null|array<array<enum-string,mixed>>
     */
    public $Items = null;

    /**
     * The database table key column name, to get items automatically
     * @var null|string
     */
    public $KeyColumn = "Id";
    /**
     * The column keys in data to use for row labels
     * @var array Auto detection
     */
    public $KeyColumns = [];
    /**
     * An array of column Keys which should not show in the table
     * @var null|array<mixed>
     */
    public $ExcludeColumns = null;
    /**
     * An array of column Keys which should show in the table
     * @var null|array<mixed>
     */
    public $IncludeColumns = null;
    /**
     * To use the column keys as the column labels
     * @var null Auto detection
     * @var true To use
     * @var false To unuse
     */
    public $ColumnsKeysAsLabels = true;
    /**
     * Add numbering to the table columns
     * The first number of columns
     * @var mixed
     */
    public $ColumnsNumbersBegin = null;

    /**
     * The database table key row name or index, to get items automatically
     * @var null|string
     */
    public $KeyRow = -1;
    /**
     * The row keys in data to use for column labels
     * @var array Auto detection
     */
    public $KeysRows = [];
    /**
     * An array of row Ids or Indexes which should show in the table
     * @var null|array<mixed>
     */
    public $IncludeRows = null;
    /**
     * An array of row Ids or Indexes which should not show in the table
     * @var null|array<mixed>
     */
    public $ExcludeRows = null;
    /**
     * To use the row keys as the row labels
     * @var null Auto detection
     * @var true To use
     * @var false To unuse
     */
    public $RowsKeysAsLabels = false;
    /**
     * Add numbering to the table rows, leave null to dont it
     * The first number of rows
     * @var mixed
     */
    public $RowsNumbersBegin = null;

    /**
     * An array of all key=>type columns in data to use for each cell type
     * @var array Auto detection
     */
    public $CellsTypes = [];
    /**
     * An array of all key=>value columns in data to use for each cell values
     * @var array Auto detection
     */
    public $CellsValues = [];

    public $Header = true;
    /**
     * A custom Callback to execute when the header is loading
     * @template string function(footer, data, start, end, display) {}
     */
    public $HeaderCallback = null;
    public $Footer = false;
    /**
     * A custom Callback to execute when the footer is loading
     * @template string function(footer, data, start, end, display) {}
     */
    public $FooterCallback = "function (footer, data, start, end, display) {
                                if(footer == null) return;
                                let api = this.api();
                                let numberVal = (val) => isEmpty(val)? 0 : typeof val === 'string' ? Number(val.match(/(^[\+\-]?\d+\.?\d*$)|((?<=\>)\s*[\+\-]?\d+\.?\d*\s*(?=\<))/gmi)) : typeof val === 'number' ? val : 0;
                                let getTotal = function (i) {
                                    let dec = 0;
                                    let res = api.column(i).data().reduce(function (a, b) {
                                        a = numberVal(a);
                                        b = numberVal(b);
                                        dec = Math.maximum(Math.decimals(a),Math.decimals(b),dec);
                                        return a+b;
                                    }, 0);
                                    return Math.decimals(res,dec = Math.maximum(5,dec??0));
                                };
                                let setTotal = (i, total) => api.column(i).footer().innerHTML = isHollow(total)||total==0?'':(total.toString());
                                let c = 0;
                                for(const node of footer.children) setTotal(c, getTotal(c++));
                            }";
    public $MediaWidth = "var(--size-5)";
    public $MediaHeight = "var(--size-5)";
    public $BorderSize = 1;
    public $AllowDecoration = true;
    public $TextWrap = false;
    public $TextLength = 50;

    public $SevereSecure = true;
    public $CryptPassword = true;
    /**
     * The displayed form
     * @var Form|null
     */
    public $Form = null;

    public $OddEvenColumns = true;
    public $OddEvenRows = true;
    public $HoverableRows = true;
    public $HoverableCells = true;

    public $ExclusiveMethod = "TABLE";
    public $SecretKey = "_secret";
    
    /**
     * A millisecond timeout for count down to refresh
     * @var int|null
     */
    public int|null $RefreshTimeout = null;
    /**
     * A control manager function
     * return null to do default action
     * @default fn($values, $funcName)=>null
     */
    public $ControlHandler = null;
    public $Controlable = true;
    public $Updatable = false;
    public $UpdateAccess = 0;
    public $ViewAccess = 0;
    public $ViewCondition = null;
    public $ViewSecret;
    public $AddAccess = 0;
    public $AddSecret;
    public $ModifyAccess = 0;
    public $ModifyCondition = null;
    public $ModifySecret;
    public $RemoveAccess = 0;
    public $RemoveCondition = null;
    public $RemoveSecret;
    public $DuplicateAccess = 0;
    public $DuplicateCondition = null;
    public $DuplicateSecret;
    public $SelectQuery = null;
    public $SelectParameters = null;
    public $SelectCondition = null;
    /**
     * To create Controls and Prepend them to the row management cell
     * @var mixed fn($id, $row)=>[control1, control2...]
     */
    public $PrependControlsCreator = null;
    /**
     * To create Controls and Append them to the row management cell
     * @var mixed fn($id, $row)=>[control1, control2...]
     */
    public $AppendControlsCreator = null;

    public $Options = ["deferRender: false", "select: true"];

    public $AllowLabelTranslation = true;
    public $AllowDataTranslation = false;
    public $AllowCache = false;
    public $AllowPaging = false;
    public $AllowSearching = false;
    public $AllowOrdering = true;
    public $AllowProcessing = true;
    public $AllowServerSide = false;
    public $AllowScrollX = true;
    public $AllowScrollY = false;
    public $AllowScrollCollapse = false;
    public $AllowAutoWidth = false;
    public $AllowAutoHeight = null;
    public $AllowFixedHeader = false;
    public $AllowFixedColumns = false;
    public $AllowFixedRows = true;
    public $AllowResponsive = true;
    public $AllowEntriesInfo = false;

    /**
     * Create the module
     * @param array|null $items The module source items
     */
    public function __construct($itemsOrDataTable = null)
    {
        parent::__construct();
        $this->Set($itemsOrDataTable);
        $a = (new DateTime())->format("z");
        $this->ViewSecret = sha1("$a-View");
        $this->DuplicateSecret = sha1("$a-Duplicate");
        $this->AddSecret = sha1("$a-Add");
        $this->RemoveSecret = sha1("$a-Remove");
        $this->ModifySecret = sha1("$a-Modify");
        $this->Router->Set($this->ExclusiveMethod)->Route(fn(&$router) => deliver($this->Exclusive()));
    }
    /**
     * Set the main properties of module
     * @param array|null $items The module source items
     */
    public function Set($itemsOrDataTable = null)
    {
        if(is_string($itemsOrDataTable)) $itemsOrDataTable = table($itemsOrDataTable);
        if ($itemsOrDataTable instanceof DataTable) {
            $this->DataTable = $itemsOrDataTable;
            $this->AllowScrollX =
                $this->AllowScrollY =
                $this->AllowPaging =
                $this->AllowSearching =
                $this->AllowEntriesInfo = true;
            $this->RowsNumbersBegin = 1;
        } else
            $this->Items = $itemsOrDataTable;
        return $this;
    }

    public function GetStyle()
    {
        return Html::Style("
		.dataTables_wrapper :is(input, select, textarea) {
			backgroound-color: var(--back-color-input);
			color: var(--fore-color-input);
		}
		.{$this->Name} :is(tr, td, th){
			border-size: {$this->BorderSize};
            border-collapse:collapse;
		}
		.{$this->Name} tr th{
			font-weight: bold;
		}
		.{$this->Name} :is(thead, tfoot) tr :is(td, th){
            padding: 10px;
		}
		.{$this->Name} tbody tr :is(td,th){
            padding: 2px 10px !important;
		}
		.{$this->Name} tr :is(td,th){
            align-content: center;
            align-items: center;
			" . Style::DoProperty("text-wrap", ($this->TextWrap === true ? "pretty" : ($this->TextWrap === false ? "nowrap" : $this->TextWrap))) . "
		}
		.{$this->Name} tr :is(td,th):has(.media:not(.icon)){
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
		}
		.{$this->Name} tr :is(td,th) .media:not(.icon){
			" . Style::DoProperty("width", $this->MediaWidth) . "
			" . Style::DoProperty("height", $this->MediaHeight) . "
			display: block;
		}
		.{$this->Name} .media.icon{
			cursor: pointer;
		}
		.{$this->Name} .media.icon:hover{
			border-color: transparent;
			" . Style::UniversalProperty("filter", "drop-shadow(var(--shadow-2))") . "
		}
		.{$this->Name} .field {
			width: 100%;
		}
		.{$this->Name} :not(.field) .input {
            max-width: 100px;
		}
        .{$this->Name} table.dataTable tbody :is(td, tr) {
            text-align: -webkit-auto;
        }
        .{$this->Name} table.dataTable thead :is(th, tr) {
            text-align: center;
        }
        .{$this->Name} table.dataTable tbody tr :is(th, td) span.number {
            margin: calc(var(--size-0) / 2);
        }
		" . ($this->OddEvenColumns ? "
            .{$this->Name} table.dataTable tbody tr:nth-child(even) :is(td, th):nth-child(odd) {
                background-color: #88888817;
            }
            .{$this->Name} table.dataTable tbody tr:nth-child(odd) :is(td, th):nth-child(odd) {
                background-color: #88888815;
            }
		" : "") . ($this->OddEvenRows ? "
            .{$this->Name} table.dataTable tbody tr:nth-child(odd) {
                background-color: #8881;
            }
		" : "") . ($this->HoverableRows ? "
            .{$this->Name} table.dataTable tbody tr:is(:nth-child(odd), :nth-child(even)):hover {
                background-color: #8882;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
            }
		" : "") . ($this->HoverableCells ? "
            .{$this->Name} table.dataTable tbody tr:is(:nth-child(odd), :nth-child(even)) td:hover {
                background-color: transparent;
                outline: 1px solid var(--color-yellow);
                border-radius: var(--radius-1);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
            }
            .{$this->Name} table.dataTable tbody tr:is(:nth-child(odd), :nth-child(even)) th:hover {
                background-color: transparent;
                outline: 1px solid var(--color-green);
                border-radius: var(--radius-1);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
            }
            .{$this->Name} table.dataTable tfoot :is(th, td) {
                text-align: center;
            }
        " : ""));
    }

    public function CreateModal()
    {
        module("Modal");
        $b = false;
        if ($b = is_null($this->Modal)) {
            $this->Modal = new Modal();
            $this->Modal->Name = "InternalModal";
        }
        $this->Modal->AllowDownload =
            $this->Modal->AllowFocus =
            $this->Modal->AllowShare =
            $this->Modal->AllowZoom =
            false;
        $this->Modal->Style = new Style();
        $this->Modal->Style->TextAlign = "initial";
        $this->Modal->Style->BackgroundColor = "var(--back-color)";
        $this->Modal->Style->Color = "var(--fore-color)";
        return $b;
    }
    public function HandleModal($force = false)
    {
        if (is_null($this->Modal))
            $this->CreateModal();
        return $this->Modal->Handle();
    }

    public function Get()
    {
        $isc = $this->Controlable;
        $isu = $isc && $this->Updatable && \_::$User->GetAccess($this->UpdateAccess);
        if ($isc)
            $this->CreateModal();
        if (isValid($this->DataTable) && isValid($this->KeyColumn)) {
            if ($this->AllowServerSide) {
                $this->NavigationBar = isValid($this->SelectQuery) ? new Navigation($this->SelectQuery, queryParameters: $this->SelectParameters, defaultItems: $this->Items) :
                    new Navigation($this->DataTable->SelectQuery(
                        isEmpty($this->IncludeColumns) ? "*" : (in_array($this->KeyColumn, $this->IncludeColumns) ? $this->IncludeColumns : [$this->KeyColumn, ...$this->IncludeColumns]),
                        [$this->SelectCondition, isEmpty($this->IncludeRows) ? null : ("{$this->KeyColumn} IN('" . join("', '", $this->IncludeRows) . "')")]
                    ), defaultItems: $this->Items);
                $this->Items = $this->NavigationBar->GetItems();
            } else {
                $this->NavigationBar = new Navigation(isValid($this->SelectQuery) ? $this->DataTable->DataBase->TryFetchRows($this->SelectQuery, $this->SelectParameters, $this->Items) :
                    $this->DataTable->Select(
                        isEmpty($this->IncludeColumns) ? "*" : (in_array($this->KeyColumn, $this->IncludeColumns) ? $this->IncludeColumns : [$this->KeyColumn, ...$this->IncludeColumns]),
                        [$this->SelectCondition, isEmpty($this->IncludeRows) ? null : ("{$this->KeyColumn} IN('" . join("', '", $this->IncludeRows) . "')")],
                        $this->SelectParameters,
                        $this->Items
                    ));
                $this->Items = $this->NavigationBar->GetItems();
            }
        } else {
            if ($this->AllowServerSide) {
                $this->NavigationBar = new Navigation($this->Items);
                $this->Items = $this->NavigationBar->GetItems();
            }
            $isu = false;
        }
        $hasid = is_countable($this->Items) && !is_null($hasid = array_key_first($this->Items)) && isValid($this->Items[$hasid], $this->KeyColumn);
        $rks = $this->KeysRows;
        $cks = $this->KeyColumns;
        $rkls = $this->RowsKeysAsLabels;
        $ckls = $this->ColumnsKeysAsLabels;
        $ckl = $this->KeyRow < 0;
        $icks = $this->IncludeColumns;
        $ecks = $this->ExcludeColumns;
        $ick = !isEmpty($icks);
        $eck = !isEmpty(object: $ecks);
        $irks = $hasid ? [] : $this->IncludeRows;
        $erks = $hasid ? [] : $this->ExcludeRows;
        $irids = $hasid ? $this->IncludeRows : [];
        $erids = $hasid ? $this->ExcludeRows : [];
        $irk = !isEmpty($irks);
        $erk = !isEmpty($erks);
        $hasid = $hasid && isValid($this->KeyColumn) && (!isEmpty($irids) || !isEmpty($erids));
        $rn = $srn = $this->RowsNumbersBegin ?? 1;
        $hrn = !is_null($this->RowsNumbersBegin);
        $cn = $scn = $this->ColumnsNumbersBegin ?? 1;
        $hcn = !is_null($this->ColumnsNumbersBegin);
        $uck = "";
        $rowCount = 0;
        $colCount = $ick ? count($icks) : 0;
        if ($isu) {
            $uck = Html::Division(\_::$User->GetAccess($this->AddAccess) ? Html::Icon("plus", "{$this->Modal->Name}_Create();", ["class" => "table-item-create", "Tooltip"=>"Add another Item"]) : Html::Image(null, "tasks"));
            if ($ick)
                array_unshift($icks, $uck);
        }
        $strow = "<tr>";
        $etrow = "</tr>";
        $vaccess = \_::$User->GetAccess($this->ViewAccess);
        $aaccess = $isu && !is_null($this->AddAccess) && \_::$User->GetAccess($this->AddAccess);
        $daccess = $isu && !is_null($this->DuplicateAccess) && \_::$User->GetAccess($this->DuplicateAccess);
        $maccess = $isu && !is_null($this->ModifyAccess) && \_::$User->GetAccess($this->ModifyAccess);
        $raccess = $isu && !is_null($this->RemoveAccess) && \_::$User->GetAccess($this->RemoveAccess);
        $addbutton = fn($text="Add your first item") => Html::Center(Html::Button(__($text) . Html::Image(null, "plus"), "{$this->Modal->Name}_Create();", ["class" => "table-item-create"]));
        if (is_countable($this->Items) && (($this->NavigationBar != null && $this->NavigationBar->Count > 0) || count($this->Items) > 0)) {
            $cells = [];
            foreach ($this->Items as $rkey => $row)
                if (!isEmpty($row)) {
                    $rowid = getBetween($row, $this->KeyColumn, preg_find("/\w+$/i", $this->KeyColumn));
                    if (
                        (!$irk || in_array($rkey, $irks)) &&
                        (!$erk || !in_array($rkey, $erks)) &&
                        (!$hasid || (in_array($rowid, $irids) || !in_array($rowid, $erids)))
                    ) {
                        $isrk = ($rkey === $this->KeyRow) || in_array($rkey, $rks) || ($hasid && $rowid === $rkey);
                        if ($rkls)
                            array_unshift($row, is_int($rkey) ? ($hrn ? $rkey + $srn : "") : $rkey);
                        if ($isc) {
                            $row = is_null($rowid) ?
                                [$uck => ($hrn ? $rn++ : ""), ...$row] :
                                [
                                    $uck => Html::Division([
                                        ...[($hrn ? Html::Span($rn++, null, ['class' => 'number']) : "")],
                                        ...Convert::ToSequence(Convert::By($this->PrependControlsCreator, $rowid, $row)??[]),
                                        ...($vaccess ? [Html::Icon("eye", "{$this->Modal->Name}_View(`$rowid`);", ["class" => "table-item-view", "tooltip"=>"Show"])] : []),
                                        ...($maccess ? [Html::Icon("edit", "{$this->Modal->Name}_Modify(`$rowid`);", ["class" => "table-item-modify", "tooltip"=>"Modify"])] : []),
                                        ...($daccess ? [Html::Icon("copy", "{$this->Modal->Name}_Duplicate(`$rowid`);", ["class" => "table-item-duplicate", "tooltip"=>"Duplicate Copy"])] : []),
                                        ...($raccess ? [Html::Icon("trash", "{$this->Modal->Name}_Delete(`$rowid`);", ["class" => "table-item-delete", "tooltip"=>"Remove"])] : []),
                                        ...Convert::ToSequence(Convert::By($this->AppendControlsCreator,$rowid, $row)??[])
                                    ]),
                                    ...$row
                                ];
                        } elseif ($hrn)
                            $row = [$rn++, ...$row];

                        if ($ckls && ($isrk || $ckl) && !is_null($this->Header))
                            if (is_bool($this->Header)) {
                                $ckl = false;
                                if ($this->Header === true)
                                    $cells[] = "<thead>";
                                else
                                    $cells[] = "<thead style='display:none'>";
                                if ($ick) {
                                    if ($hcn) {
                                        $cells[] = $strow;
                                        foreach ($icks as $ci => $ckey){
                                            $ci = (is_int($ci)?$ckey:$ci);
                                            if (!$eck || !in_array($ci, $ecks))
                                                $cells[] = $this->GetCell($cn++, $ci, $row, true);
                                        }
                                        $cells[] = $etrow;
                                    }
                                    $cells[] = $strow;
                                    foreach ($icks as $ci => $ckey){
                                            $ci = (is_int($ci)?$ckey:$ci);
                                        if (!$eck || !in_array($ci, $ecks))
                                            $cells[] = $this->GetCell(is_int($ckey) ? ($hcn ? $ckey + $scn : "") : $ci, $ckey, $row, true);
                                    }
                                    $cells[] = $etrow;
                                } else {
                                    if ($hcn) {
                                        $cells[] = $strow;
                                        foreach ($row as $ckey => $cel)
                                            if (!$eck || !in_array($ckey, $ecks))
                                                $cells[] = $this->GetCell($cn++, $ckey, $row, true);
                                        $cells[] = $etrow;
                                    }
                                    $cells[] = $strow;
                                    foreach ($row as $ckey => $cel)
                                        if (!$eck || !in_array($ckey, $ecks))
                                            $cells[] = $this->GetCell(is_int($ckey) ? ($hcn ? $ckey + $scn : "") : $ckey, $ckey, $row, true);
                                    $cells[] = $etrow;
                                }
                                $cells[] = "</thead>";
                                $isrk = false;
                            } else
                                $cells[] = Convert::ToString($this->Header);
                        $cells[] = $strow;
                        if ($ick) {
                            $colCount = max($colCount, count($icks));
                            foreach ($icks as  $ci => $ckey){
                                $ckey = is_int($ci)?$ckey:$ci;
                                if (!$eck || !in_array($ckey, $ecks)) {
                                    $cel = isset($row[$ckey]) ? $row[$ckey] : null;
                                    if ($isrk)
                                        $cells[] = $this->GetCell(is_int($rkey) ? ($hrn ? $rkey + $srn : "") : $cel, $ckey, $row, true);
                                    elseif (in_array($ckey, $cks))
                                        $cells[] = $this->GetCell(is_int($ckey) ? ($hcn ? $ckey + $scn : "") : $cel, $ckey, $row, true);
                                    else
                                        $cells[] = $this->GetCell($cel, $ckey, $row, false);
                                }
                            }
                        } else {
                            $colCount = max($colCount, count($row));
                            foreach ($row as $ckey => $cel)
                                if (!$eck || !in_array($ckey, $ecks)) {
                                    if ($isrk)
                                        $cells[] = $this->GetCell(is_int($rkey) ? ($hrn ? $rkey + $srn : "") : $cel, $ckey, $row, true);
                                    elseif (in_array($ckey, $cks))
                                        $cells[] = $this->GetCell(is_int($ckey) ? ($hcn ? $ckey + $scn : "") : $cel, $ckey, $row, true);
                                    else
                                        $cells[] = $this->GetCell($cel, $ckey, $row, false);
                                }
                        }
                        $cells[] = $etrow;
                        $rowCount++;
                    }
                }
            $cells[] = $etrow;
            if ($this->Footer)
                if (is_bool($this->Footer)) {
                    $cells[] = "<tfoot><tr>";
                    if (0 < $colCount)
                        $cells[] = Html::Cell("", $isc || $hrn ? ["Type"=>"head", "class" => "view invisible"] : ["Type"=>"head"]);
                    for ($i = 1; $i < $colCount; $i++)
                        $cells[] = Html::Cell("", ["Type"=>"head"]/*$ick&&isset($icks[$ckey])?$cks[$icks[$ckey]]:false*/);
                    $cells[] = "</tr></tfoot>";
                } else
                    $cells[] = Convert::ToString($this->Footer);
            return ($isc ? $this->HandleModal() : "") . parent::GetOpenTag(). ($aaccess?$addbutton("Add another item"):"") . (!$this->TopNavigation || is_null($this->NavigationBar) ? "" : $this->NavigationBar->ToString()) .Html::Table(join(PHP_EOL, $cells)).parent::GetCloseTag();
        } elseif ($aaccess)
            return ($isc ? $this->HandleModal() : "") . parent::GetOpenTag() . $addbutton("Add your first item").Html::Table("").parent::GetCloseTag();
        return ($isc ? $this->HandleModal() : "") . parent::GetOpenTag().Html::Table("").parent::GetCloseTag();
    }

    public function GetCell($value, $key, $record = [], bool $isHead = false)
    {
        if (!$isHead || $value !== $key)
            $value = Convert::ToString(Convert::By(get($this->CellsValues, $key), $value, $key, $record) ?? $value);
        if ($isHead) {
            $value = Convert::ToString($value);
            if (isFile($value)) return "<th>" . Html::Media($value) . "</th>";
            else if (isAbsoluteUrl($value)) return "<th>" . Html::Link(getPage($value), $value) . "</th>";
            else return "<th>" . __($value, translating: $this->AllowLabelTranslation) . "</th>";
        }
        //if($this->Updatable && !$isHead && $key > 1){
        //    $value = new Field(key:$key, value: $value, lock: true, type:getValid($this->CellsTypes,$key, null));
        //    $value->MinWidth = $this->MediaWidth;
        //    $value->MaxHeight = $this->MediaHeight;
        //    return "<td>".Convert::ToString($value)."</td>";
        //}
        if (isFile($value)) return "<td>" . Html::Media($value) . "</td>";
        if (isAbsoluteUrl($value)) return "<td>" . Html::Link(getPage($value), $value) . "</td>";
        $value = __($value, translating: $this->AllowDataTranslation);
        if (!$this->TextWrap && !startsWith($value, "<"))
            return "<td>" . Convert::ToExcerpt(Convert::ToText($value), 0, $this->TextLength, "..." . Html::Tooltip($value)) . "</td>";
        return "<td>$value</td>";
    }
    public function GetScript()
    {
        $localPaging = is_null($this->NavigationBar);
        return Html::Script(
            "$(document).ready(()=>{" .
            (!$this->AllowDecoration ? "" :
                "$('.{$this->Name} table').DataTable({" .
                join(", ", [
                    ...(is_null($this->AllowCache) ? [] : ["stateSave: " . ($this->AllowCache ? "true" : "false")]),
                    ...(is_null($this->AllowPaging) ? [] : ["paging: " . ($this->AllowPaging ? ($localPaging ? "true" : "false") : "false")]),
                    ...(is_null($this->AllowSearching) ? [] : ["searching: " . ($this->AllowSearching ? "true" : "false")]),
                    ...(is_null($this->AllowOrdering) ? [] : ["ordering: " . ($this->AllowOrdering ? "true" : "false")]),
                    ...(is_null($this->AllowProcessing) ? [] : ["processing: " . ($this->AllowProcessing ? "true" : "false")]),
                    ...(is_null($this->AllowServerSide) ? [] : ["serverSide: " . ($this->AllowServerSide ? ($localPaging ? "true" : "false") : "false")]),
                    ...(is_null($this->AllowScrollX) ? [] : ["scrollX: " . ($this->AllowScrollX ? "true" : "false")]),
                    ...(is_null($this->AllowScrollY) ? [] : ["scrollY: " . ($this->AllowScrollY ? "true" : "false")]),
                    ...(is_null($this->AllowScrollCollapse) ? [] : ["scrollCollapse: " . ($this->AllowScrollCollapse ? "true" : "false")]),
                    ...(is_null($this->AllowAutoWidth) ? [] : ["autoWidth: " . ($this->AllowAutoWidth ? "true" : "false")]),
                    ...(is_null($this->AllowAutoHeight) ? [] : ["autoHeight: " . ($this->AllowAutoHeight ? "true" : "false")]),
                    ...(is_null($this->AllowFixedHeader) ? [] : ["fixedHeader: " . ($this->AllowFixedHeader ? "true" : "false")]),
                    ...(is_null($this->AllowFixedColumns) ? [] : ["fixedColumns: " . ($this->AllowFixedColumns ? "true" : "false")]),
                    ...(is_null($this->AllowFixedRows) ? [] : ["fixedRows: " . ($this->AllowFixedRows ? "true" : "false")]),
                    ...(is_null($this->AllowResponsive) ? [] : ["responsive: " . ($this->AllowResponsive ? "true" : "false")]),
                    ...(is_null($this->AllowEntriesInfo) ? [] : ["info: " . ($this->AllowEntriesInfo ? ($localPaging ? "true" : "false") : "false")]),
                    ...($this->HeaderCallback ? ["headerCallback: {$this->HeaderCallback}"] : []),
                    ...($this->FooterCallback ? ["footerCallback: {$this->FooterCallback}"] : []),
                    ...[
                        "language: {" .
                        "decimal: \"" . __("") . "\"," .
                        "emptyTable: \"" . __("No items available") . "\"," .
                        "info: \"" . __("Showing _START_ to _END_ of _TOTAL_ entries") . "\"," .
                        "infoEmpty: \"" . __("") . "\"," .
                        "infoFiltered: \"" . __("(filtered from _MAX_ total entries)") . "\"," .
                        "infoPostFix: \"" . __("") . "\"," .
                        "thousands: \"" . __(",") . "\"," .
                        "lengthMenu: \"" . __("Display _MENU_ items per page") . "\"," .
                        "loadingRecords: \"" . __("Loading...") . "\"," .
                        "processing: \"" . __("") . "\"," .
                        "search: \"" . __("Search: ") . "\"," .
                        "zeroRecords: \"" . __("No matching items found") . "\"," .
                        "paginate: {" .
                        "first: \"" . __("First") . "\"," .
                        "last: \"" . __("Last") . "\"," .
                        "next: \"" . __("Next") . "\"," .
                        "previous: \"" . __("Previous") . "\"" .
                        "}," .
                        "aria: {" .
                        "sortAscending: \"" . __(": activate to sort column ascending") . "\"," .
                        "sortDescending: \"" . __(": activate to sort column descending") . "\"" .
                        "}" .
                        "}"
                    ],
                    ...($this->Controlable || !is_null($this->RowsNumbersBegin) ? ["'columnDefs': [{ 'targets': 0, 'orderable': false }],order:[]"] : ["order:[]"]),
                    ...(isEmpty($this->Options) ? [] : (is_array($this->Options) ? $this->Options : [Convert::ToString($this->Options)]))
                ]) .
                "});
			});") . ($this->Controlable ?
                (is_null($this->Modal) ? "" : ("
				function {$this->Modal->Name}_View(key){
					send('{$this->ExclusiveMethod}', null, {{$this->SecretKey}:'{$this->ViewSecret}','{$this->KeyColumn}':key}, `.{$this->Name}`,
						(data, err)=>{
							" . $this->Modal->InitializeScript(null, null, '${data}') . "
						}
					);
				}" . ($this->Updatable ? (\_::$User->GetAccess($this->AddAccess) ? "
				function {$this->Modal->Name}_Create(defaultValues){
					send('{$this->ExclusiveMethod}', null, {{$this->SecretKey}:'{$this->AddSecret}','{$this->KeyColumn}':'{$this->AddSecret}'}, `.{$this->Name}`,
						(data, err)=>{
							" . $this->Modal->InitializeScript(null, null, '${data}') . "
                            if(defaultValues)
                            for(x in defaultValues)try{
                            	document.querySelector('.{$this->Modal->Name} *[name=\"'+x+'\"]').value = defaultValues[x];
                			}catch{}
						}
					);
				}" : "") . (\_::$User->GetAccess($this->ModifyAccess) ? "
				function {$this->Modal->Name}_Modify(key){
					send('{$this->ExclusiveMethod}', null, {{$this->SecretKey}:'{$this->ModifySecret}','{$this->KeyColumn}':key}, `.{$this->Name}`,
						(data, err)=>{
							" . $this->Modal->InitializeScript(null, null, '${data}') . "
						}
					);
				}" : "") . (\_::$User->GetAccess($this->DuplicateAccess) ? "
				function {$this->Modal->Name}_Duplicate(key){
					send('{$this->ExclusiveMethod}', null, {{$this->SecretKey}:'{$this->DuplicateSecret}','{$this->KeyColumn}':key}, `.{$this->Name}`,
						(data, err)=>{
							" . $this->Modal->InitializeScript(null, null, '${data}') . "
						}
					);
				}" : "") . (\_::$User->GetAccess($this->RemoveAccess) ? "
				function {$this->Modal->Name}_Delete(key){
					" . ($this->SevereSecure ? "if(confirm(`" . __("Are you sure you want to remove this item?") . "`))" : "") . "
						send('{$this->ExclusiveMethod}', null, {{$this->SecretKey}:'{$this->RemoveSecret}','{$this->KeyColumn}':key}, `.{$this->Name}`,
						(data, err)=>{
							load();
						});
				}" : "") : "")
                )) : "")
        ).($this->RefreshTimeout?(new (module("Counter"))(max(1, $this->RefreshTimeout/1000),0,"load()"))->GetScript():"");
    }

    public function AfterHandle()
    {
        if (!$this->BottomNavigation || is_null($this->NavigationBar))
            return parent::AfterHandle();
        else
            return parent::AfterHandle() . ($this->TopNavigation ? $this->NavigationBar->ToString() : $this->NavigationBar->ToString());
    }

    public function Exclusive()
    {
        $values = receive($this->ExclusiveMethod) ?? [];
        $value = get($values, $this->KeyColumn);
        $secret = pop($values, $this->SecretKey);
        $recievedData = count($values) > 1;
        if(!$this->ControlHandler) $this->ControlHandler = fn($v,$f)=>null;
        if (!$secret) return Html::Error("Your request is not valid!");
        elseif ($secret === $this->ViewSecret)
            return ($this->ControlHandler)($value, "GetViewForm") ?? $this->GetViewForm($value);
        elseif ($secret === $this->DuplicateSecret)
            return ($this->ControlHandler)($value, "GetDuplicateForm") ?? $this->GetDuplicateForm($value);
        elseif ($secret === $this->AddSecret)
            if ($recievedData)
                return ($this->ControlHandler)($values, "AddRow") ?? $this->AddRow($values);
            else
                return ($this->ControlHandler)($value, "GetAddForm") ?? $this->GetAddForm($value);
        elseif ($secret === $this->ModifySecret)
            if ($recievedData)
                return ($this->ControlHandler)($values, "ModifyRow") ?? $this->ModifyRow($values);
            else
                return ($this->ControlHandler)($value, "GetModifyForm") ?? $this->GetModifyForm($value);
        elseif ($secret === $this->RemoveSecret)
            return ($this->ControlHandler)($value, "RemoveRow") ?? $this->RemoveRow($value);
        else return Html::Error("There is not any response for your request!");
    }

    public function GetForm()
    {
        module("Form");
        if(!$this->Form) {
            $this->Form = new Form();
            $this->Form->Template = "s";
            $this->Form->Class = "container";
            $this->Form->ContentClass = "col-lg-8";
            $this->Form->CancelLabel = "Cancel";
            $this->Form->SuccessPath = \_::$Address->Url;
            $this->Form->BackPath = \_::$Address->Url;
            $this->Form->BackLabel = null;
            //$form->AllowHeader = false;
        }
        $this->Form->Router->Get()->Switch();
        $this->Form->Method = $this->ExclusiveMethod;
        if ($this->Modal) {
            $this->Form->CancelPath = $this->Modal->HideScript();
            $this->Form->CancelLabel = "Cancel";
        } else
            $this->Form->CancelLabel = null;
        return $this->Form;
    }
    public function GetViewForm($value)
    {
        if (is_null($value))
            return null;
        if (!\_::$User->GetAccess($this->ViewAccess))
            return Html::Error("You have not access to see datails!");
        $record = $this->DataTable->SelectRow(count($this->CellsTypes) > 0 ? array_keys($this->CellsTypes) : "*", [$this->ViewCondition, "`{$this->KeyColumn}`=:{$this->KeyColumn}"], [":{$this->KeyColumn}" => $value]);
        if (isEmpty($record))
            return Html::Error("You can not 'see' this item!");
        $form = $this->GetForm();
        $form->Set(
            title: getBetween($record, "Title", "Name"),
            description: get($record, "Description"),
            action: '#',
            method: "",
            children: (function () use ($record) {
                foreach ($record as $k => $cell) {
                    $type = getValid($this->CellsTypes, $k, "");
                    if (is_string($type)) {
                        $type = strtolower($type);
                        switch ($type) {
                            case "pass":
                            case "password":
                                $type = false;
                                break;
                            case "file":
                            case "files":
                            case "doc":
                            case "docs":
                            case "document":
                            case "documents":
                            case "image":
                            case "images":
                            case "video":
                            case "videos":
                            case "audio":
                            case "audios":
                                $type = false;
                                break;
                        }
                    }
                    if ($type !== false && !isEmpty($cell))
                        yield Html::Field(
                            type: (isEmpty($type) ? null : Convert::By($type, $type, $cell, $k, $record)),
                            key: $k,
                            value: $cell,
                            description: false,
                            attributes: ["disabled"]
                        );
                }
            })()
        );
        $form->Image = getValid($record, "Image", "eye");
        $form->Template = "b";
        $form->SubmitLabel = null;
        $form->ResetLabel = null;
        if ($this->Modal) {
            $form->CancelLabel = "Cancel";
            $form->CancelPath = $this->Modal->HideScript();
        } else
            $form->CancelLabel = null;
        return $form->Handle();
    }
    public function GetAddForm($value){
        if (is_null($value))
            return null;
        if (!\_::$User->GetAccess($this->AddAccess))
            return Html::Error("You have not access to add!");
        $record = [];
        if (count($this->CellsTypes) > 0)
            foreach ($this->CellsTypes as $key => $val)
                $record[$key] = null;
        $form = $this->GetForm();
        $form->Set(
            title: "Add {$this->Title}",
            description: $this->Description,
            method: null,
            children: (function () use ($record, $value) {
                $schemas = $this->DataTable->DataBase->TryFetchRows(
                    "SELECT COLUMN_NAME, COLUMN_TYPE, DATA_TYPE, COLUMN_DEFAULT, IS_NULLABLE, EXTRA
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_NAME='{$this->DataTable->Name}'"
                );
                if (count($record) == 0)
                    foreach ($schemas as $schema)
                        $record[$schema["COLUMN_NAME"]] = null;
                foreach ($record as $key => $val) foreach ($schemas as $schema)
                        if ($schema["COLUMN_NAME"] == $key)
                        {
                            $val = $key == $this->KeyColumn ? $value : null;
                            $res = $this->PrepareDataToShow($val, $key, $record, $schema);
                            if (!isEmpty($res))
                                yield $res;
                        }
                yield Html::HiddenInput($this->SecretKey, $this->AddSecret);
            })()
        );
        $form->Image = getValid($record, "Image", "plus");
        return $form->Handle();
    }
    public function GetDuplicateForm($value)
    {
        if (is_null($value))
            return null;
        if (!\_::$User->GetAccess($this->AddAccess))
            return Html::Error("You have not access to add!");
        $record = $this->DataTable->SelectRow(count($this->CellsTypes) > 0 ? array_keys($this->CellsTypes) : "*", [$this->DuplicateCondition, "`{$this->KeyColumn}`=:{$this->KeyColumn}"], [":{$this->KeyColumn}" => $value]);
        if (isEmpty($record))
            return Html::Error("You can not 'add' this item!");
        $form = $this->GetForm();
        $form->Set(
            title: "Add {$this->Title}",
            description: $this->Description,
            method: null,
            children: (function () use ($record, $value) {
                $schemas = $this->DataTable->DataBase->TryFetchRows(
                    "SELECT COLUMN_NAME, COLUMN_TYPE, DATA_TYPE, COLUMN_DEFAULT, IS_NULLABLE, EXTRA
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_NAME='{$this->DataTable->Name}'"
                );
                foreach ($record as $key => $val) foreach ($schemas as $schema)
                        if ($schema["COLUMN_NAME"] == $key)
                        {
                            $val = $key == $this->KeyColumn ? $this->AddSecret : $val;
                            $res = $this->PrepareDataToShow($val, $key, $record, $schema);
                            if (!isEmpty($res))
                                yield $res;
                        }
                yield Html::HiddenInput($this->SecretKey, $this->AddSecret);
            })()
        );
        $form->Image = getValid($record, "Image", "plus");
        return $form->Handle();
    }
    public function GetModifyForm($value)
    {
        if (is_null($value))
            return null;
        if (!\_::$User->GetAccess($this->ModifyAccess))
            return Html::Error("You have not access to modify!");
        $record = $this->DataTable->SelectRow(count($this->CellsTypes) > 0 ? array_keys($this->CellsTypes) : "*", [$this->ModifyCondition, "`{$this->KeyColumn}`=:{$this->KeyColumn}"], [":{$this->KeyColumn}" => $value]);
        if (isEmpty($record))
            return Html::Error("You can not 'modify' this item!");
        $form = $this->GetForm();
        $form->Set(
            title: getBetween($record, "Title", "Name"),
            description: get($record, "Description"),
            method: null,
            children: (function () use ($record, $value) {
                $schemas = $this->DataTable->DataBase->TryFetchRows(
                    "SELECT COLUMN_NAME, COLUMN_TYPE, DATA_TYPE, COLUMN_DEFAULT, IS_NULLABLE, EXTRA
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_NAME='{$this->DataTable->Name}'"
                );
                foreach ($record as $key => $val) foreach ($schemas as $schema)
                        if ($schema["COLUMN_NAME"] == $key)
                        {
                            $res = $this->PrepareDataToShow($val, $key, $record, $schema);
                            if (!isEmpty($res))
                                yield $res;
                            break;
                        }
                yield Html::HiddenInput($this->SecretKey, $this->ModifySecret);
            })()
        );
        $form->Image = getValid($record, "Image", "edit");
        return $form->Handle();
    }

    public function AddRow($values)
    {
        if (!\_::$User->GetAccess($this->AddAccess))
            return Html::Error("You have not access to modify!");
        unset($values[$this->KeyColumn]);
        $values = $this->NormalizeFormValues($values);
        if (!is_array($values))
            return $values;
        foreach ($values as $k => $v)
            if (isEmpty($v))
                unset($values[$k]);
        if ($this->DataTable->Insert($values))
            return deliverBreaker(Html::Success("The 'information' added successfully!"));
        return Html::Error("You can not 'add' this item!");
    }
    public function ModifyRow($values)
    {
        if (!\_::$User->GetAccess($this->ModifyAccess))
            return Html::Error("You have not access to modify!");
        if (isValid($values, $this->KeyColumn)) {
            $values = $this->NormalizeFormValues($values);
            if (!is_array($values))
                return $values;
            if ($this->DataTable->Update([$this->ModifyCondition, "{$this->KeyColumn}=:{$this->KeyColumn}"], $values))
                return deliverBreaker(Html::Success("The information updated successfully!"));
            return Html::Error("You can not 'update' this item!");
        }
    }
    public function RemoveRow($value)
    {
        if (is_null($value))
            return null;
        if (!\_::$User->GetAccess($this->RemoveAccess))
            return Html::Error("You have not access to delete!");
        if ($this->DataTable->Delete([$this->RemoveCondition, "`{$this->KeyColumn}`=:{$this->KeyColumn}"], [":{$this->KeyColumn}" => $value]))
            return deliverBreaker(Html::Success("The 'items' removed successfully!"));
        return Html::Error("You can not 'remove' this item!");
    }

    public function PrepareDataToShow(&$value, &$key, &$record, $schema)
    {
        $type = getValid($this->CellsTypes, $key, $schema["DATA_TYPE"]);
        $options = null;
        $def = $schema["COLUMN_DEFAULT"] ?? "";
        if (is_null($value))
            switch (strtolower($def)) {
                case "null":
                case "current_timestamp":
                case "current_timestamp()":
                case "{current_timestamp()}":
                    $value = null;
                    break;
                default:
                    $value = trim($def, "\"'`");
                    break;
            }
        if ($key == $this->KeyColumn && str_contains($schema["EXTRA"], 'auto_increment'))
            return Html::HiddenInput($key, $value);
        else {
            if (is_string($type))
                switch (strtolower($type)) {
                    case "pass":
                    case "password":
                        if ($this->CryptPassword)
                            $value = \_::$User->DecryptPassword($value);
                        break;
                    // case "file":
                    // case "files":
                    // case "doc":
                    // case "docs":
                    // case "document":
                    // case "documents":
                    // case "image":
                    // case "images":
                    // case "video":
                    // case "videos":
                    // case "audio":
                    // case "audios":
                    //     $value = null;
                    //break;
                    case "type":
                    case "types":
                    case "enum":
                    case "enums":
                        $options = [];
                        foreach (preg_find_all('/(?<=(\'|\"))[^\'\"\,]+(?=\1)/', $schema["COLUMN_TYPE"]) as $key2 => $val2) {
                            $options[$val2] = $val2;
                        }
                        break;
                }
            if ($type !== false)
                return Html::Field(
                    type: isEmpty($type) ? null : Convert::By($type, $type, $value, $key, $record),
                    key: $key,
                    value: $value,
                    options: $options,
                    attributes: $schema["IS_NULLABLE"] == "NO" && is_null($schema["COLUMN_DEFAULT"]) ? ["Required"] : []
                );
            return null;
        }
    }

    public function NormalizeFormValues($values)
    {
        try {
            $received = receiveFile();
            if ($received) {
                $clearPrev = isValid($values, $this->KeyColumn) && $this->DataTable;
                foreach ($received as $k => $v)
                    if (Local::IsFileObject($v)) {
                        $values[$k] = $clearPrev ? $this->DataTable->SelectValue("`$k`", "`$this->KeyColumn`=:$this->KeyColumn", [":$this->KeyColumn" => $values[$this->KeyColumn]]) : null;
                        if (isValid($values[$k]))
                            Local::DeleteFile($values[$k]);
                        unset($values[$k]);
                        $type = getValid($this->CellsTypes, $k, "");
                        if (is_string($type))
                            $type = \_::$Config->GetAcceptableFormats($type);
                        else
                            $type = \_::$Config->GetAcceptableFormats();
                        $values[$k] = Local::GetUrl(Local::StoreFile($v, extensions: $type));
                    } elseif (isEmpty($v))
                        unset($values[$k]);
            }
        } catch (\Exception $ex) {
            return Html::Error($ex);
        }
        foreach ($values as $k => $v)
            if ($k !== $this->KeyColumn) {
                if ($v === '')
                    $values[$k] = null;
                $type = getValid($this->CellsTypes, $k, "");
                if (is_string($type)) {
                    switch (strtolower($type)) {
                        case "pass":
                        case "password":
                            if (isEmpty($v))
                                unset($values[$k]);
                            elseif ($this->CryptPassword)
                                $values[$k] = \_::$User->EncryptPassword($v);
                            break;
                    }
                } elseif ($type === false)
                    unset($values[$k]);
            }
        return $values;
    }
}
?>