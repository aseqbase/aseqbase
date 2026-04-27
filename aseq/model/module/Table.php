<?php
namespace MiMFa\Module;

use DateTime;
use MiMFa\Library\Struct;
use MiMFa\Library\Convert;
use MiMFa\Library\Style;
use MiMFa\Library\Storage;
use MiMFa\Library\DataTable;
use MiMFa\Library\Script;

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
    public $Modal = null;
    public Navigation|null $NavigationBar = null;
    public $TopNavigation = false;
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
     * @var null|string|int
     */
    public $KeyColumn = "Id";
    /**
     * The database table key column main name, to get items automatically
     * leave null to set by $KeyColumn automatically
     * @var null|string
     */
    public $KeyColumnName = "Id";
    /**
     * The column keys in data to use for row labels
     * @var array Auto detection
     */
    public $KeyColumns = [];
    /**
     * An array of column Keys which should show in the table
     * @var null|array<mixed>
     */
    public $IncludeColumns = null;
    /**
     * An array of column Keys which should not show in the table
     * @var null|array<mixed>
     */
    public $ExcludeColumns = null;
    /**
     * An array of column Keys which could filter the table by each clicking on them cells
     * @var null|array<mixed>
     */
    public $FilterColumns = null;
    /**
     * To use the column keys as the column labels
     * null: Auto detection
     * true: To use
     * false: To unuse
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
     * @var null|string|int
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
     * null: Auto detection
     * true: To use
     * false: To unuse
     */
    public $RowsKeysAsLabels = false;
    /**
     * Add numbering to the table rows, leave null to dont it
     * The first number of rows
     * @var mixed
     */
    public $RowsNumbersBegin = null;

    /**
     * To show only cell types on the forms
     * @var 
     */
    public $FormFilter = false;

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

    public $Caption = false;
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
    public $RowsTagName = "tr";
    public $ColumnsTagName = "td";
    public $MediaWidth = "var(--size-max)";
    public $MediaHeight = "var(--size-max)";
    public $BorderSize = "1px";
    public $AllowDecoration = true;
    public $TextWrap = false;
    public $TextLength = 50;

    public $Quick = true;
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

    public $Method = "__TABLE";
    public $SecretRequestKey = "__TABLE__SECRET";
    public $MetaDataRequestKey = "__TABLE__METADATA";
    public $RowsRequestKey = "__TABLE__ROWS";
    public $ColumnsRequestKey = "__TABLE__COLUMNS";

    /**
     * A millisecond timeout for count down to refresh
     * @var int|null
     */
    public int|null $RefreshTimeout = null;
    public bool $AllowTooltip = false;

    public $Controlable = true;
    public $Updatable = false;
    public $UpdateAccess = 0;

    public string|null $Filter = null;
    /**
     * An array of column Keys which could filter the table by each clicking on them cells
     * @var array
     */
    public array $FilterGraph = [];
    public string|null $FilterPattern = null;

    public array|string|null $PrependToolsBar = null;
    public array|string|null $ToolsBar = null;
    public array|string|null $AppendToolsBar = null;
    /**
     * The Search control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $SearchHandler = null;
    /**
     * The Search Request control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $SearchRequestHandler = null;
    public $SearchAccess = 0;
    public $SearchIcon = "search";
    public $SearchCondition = null;
    public $SearchSecret;
    /**
     * The View control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $ViewHandler = null;
    /**
     * The View Form control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $ViewRequestHandler = null;
    public $ViewAccess = 0;
    public $ViewIcon = "eye";
    public $ViewCondition = null;
    public $ViewSecret;
    /**
     * The Import control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $ImportHandler = null;
    /**
     * The Import Form control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $ImportRequestHandler = null;
    public $ImportAccess = 0;
    public $ImportIcon = "download";
    public $ImportSecret;
    /**
     * The Export control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $ExportHandler = null;
    /**
     * The Export Form control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $ExportRequestHandler = null;
    public $ExportAccess = 0;
    public $ExportIcon = "upload";
    public $ExportSecret;
    /**
     * The Clear control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $ClearHandler = null;
    /**
     * The Clear Form control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $ClearRequestHandler = null;
    public $ClearAccess = false;
    public $ClearIcon = "broom";
    public $ClearSecret;
    /**
     * The Add control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $AddHandler = null;
    /**
     * The Add Request control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $AddRequestHandler = null;
    public $AddAccess = 0;
    public $AddIcon = "plus";
    public $AddSecret;
    /**
     * The Modify control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $ModifyHandler = null;
    /**
     * The Modify Request control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $ModifyRequestHandler = null;
    public $ModifyAccess = 0;
    public $ModifyIcon = "edit";
    public $ModifyCondition = null;
    public $ModifySecret;
    /**
     * The Remove control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $RemoveHandler = null;
    /**
     * The Remove Form control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $RemoveRequestHandler = null;
    public $RemoveAccess = 0;
    public $RemoveIcon = "trash-alt";
    public $RemoveCondition = null;
    public $RemoveSecret;
    /**
     * The Duplicate control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $DuplicateHandler = null;
    /**
     * The Duplicate Form control manager function
     * return null to do default action
     * @default fn($values)=>null
     */
    public $DuplicateRequestHandler = null;
    public $DuplicateAccess = 0;
    public $DuplicateIcon = "copy";
    public $DuplicateCondition = null;
    public $DuplicateSecret;

    public $SelectQuery = null;
    public $SelectParameters = null;
    public $SelectCondition = null;
    /**
     * To create Controls and Prepend them to the row management cell
     * @var mixed fn($id, $row)=>[control1, control2...]
     */
    public $PrependControls = null;
    /**
     * To create Controls and Append them to the row management cell
     * @var mixed fn($id, $row)=>[control1, control2...]
     */
    public $AppendControls = null;

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
        if ($this->DataTable) {
            $this->AllowScrollX =
                $this->AllowScrollY =
                $this->AllowPaging =
                $this->AllowSearching =
                $this->AllowEntriesInfo = true;
            $this->RowsNumbersBegin = 1;
        }
        $a = (new DateTime())->format("z");
        $this->ViewSecret = sha1("$a-View");
        $this->SearchSecret = sha1("$a-Search");
        $this->DuplicateSecret = sha1("$a-Duplicate");
        $this->ImportSecret = sha1("$a-Import");
        $this->ExportSecret = sha1("$a-Export");
        $this->ClearSecret = sha1("$a-Clear");
        $this->AddSecret = sha1("$a-Add");
        $this->RemoveSecret = sha1("$a-Remove");
        $this->ModifySecret = sha1("$a-Modify");

        $this->ViewRequestHandler =
            $this->SearchRequestHandler =
            $this->DuplicateRequestHandler =
            $this->ImportRequestHandler =
            $this->ExportRequestHandler =
            $this->ClearRequestHandler =
            $this->AddRequestHandler =
            $this->RemoveRequestHandler =
            $this->ModifyRequestHandler = fn($v) => null;
        $this->ViewHandler =
            $this->SearchHandler =
            $this->DuplicateHandler =
            $this->ImportHandler =
            $this->ExportHandler =
            $this->ClearHandler =
            $this->AddHandler =
            $this->RemoveHandler =
            $this->ModifyHandler = fn($v) => null;
        $this->Router->Set($this->Method)->Route(fn(&$router) => deliver($this->Exclusive()));
    }
    /**
     * Set the main properties of module
     * @param array|null $items The module source items
     */
    public function Set($itemsOrDataTable = null)
    {
        if (is_string($itemsOrDataTable))
            $itemsOrDataTable = table($itemsOrDataTable);
        if ($itemsOrDataTable instanceof DataTable)
            $this->DataTable = $itemsOrDataTable;
        else
            $this->Items = $itemsOrDataTable;
        return $this;
    }

    public function GetStyle()
    {
        return Struct::Style("
		.dataTables_wrapper :is(input, select, textarea) {
			backgroound-color: var(--back-color-input);
			color: var(--fore-color-input);
		}
		.{$this->MainClass} :is(.toolbar, .toolbar>*){
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: var(--size-0);
		}
		.{$this->MainClass} .toolbar .button{
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            gap: var(--size-0);
		}
		.{$this->MainClass} .table-items-clear{
			background-color: var(--color-white);
			color: var(--color-red);
			border-color: var(--color-red);
		}
		.{$this->MainClass} :is(tr, td, th){
			border-size: {$this->BorderSize};
            border-collapse:collapse;
		}
		.{$this->MainClass} tr th{
			font-weight: bold;
		}
		.{$this->MainClass} :is(thead, tfoot) tr :is(td, th){
            padding: 10px;
		}
		.{$this->MainClass} tbody tr :is(td,th){
            padding: 2px 10px !important;
		}
		.{$this->MainClass} tr :is(td,th){
            align-content: center;
            align-items: center;
			" . Style::DoProperty("text-wrap", ($this->TextWrap === true ? "pretty" : ($this->TextWrap === false ? "nowrap" : $this->TextWrap))) . "
		}
		.{$this->MainClass} tr :is(td,th):has(.media:not(.icon))>*{
            display: flex;
            align-items: center;
            justify-content: center;
            align-content: center;
            width: 100%;
            height: 100%;
		}
		.{$this->MainClass} tr :is(td,th) .media:not(.icon)>*{
			" . Style::DoProperty("max-width", $this->MediaWidth) . "
			" . Style::DoProperty("max-height", $this->MediaHeight) . "
		}
		.{$this->MainClass} .media.icon{
			cursor: pointer;
		}
		.{$this->MainClass} .media.icon:hover{
			border-color: transparent;
			" . Style::UniversalProperty("filter", "drop-shadow(var(--shadow-2))") . "
		}
		.{$this->MainClass} .field {
			width: 100%;
		}
        .{$this->MainClass} table.dataTable tbody :is(td, tr) {
            text-align: -webkit-auto;
        }
        .{$this->MainClass} table.dataTable thead :is(th, tr) {
            text-align: center;
        }
        .{$this->MainClass} table.dataTable tbody tr :is(th, td) span.number {
            margin: calc(var(--size-0) / 2);
        }
		" . ($this->OddEvenColumns ? "
            .{$this->MainClass} table.dataTable tbody tr:nth-child(even) :is(td, th):nth-child(odd) {
                background-color: #88888817;
            }
            .{$this->MainClass} table.dataTable tbody tr:nth-child(odd) :is(td, th):nth-child(odd) {
                background-color: #88888815;
            }
		" : "") . ($this->OddEvenRows ? "
            .{$this->MainClass} table.dataTable tbody tr:nth-child(odd) {
                background-color: #8881;
            }
		" : "") . ($this->HoverableRows ? "
            .{$this->MainClass} table.dataTable tbody tr:is(:nth-child(odd), :nth-child(even)):hover {
                background-color: #8882;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
            }
		" : "") . ($this->HoverableCells ? "
            .{$this->MainClass} table.dataTable tbody tr:is(:nth-child(odd), :nth-child(even)) td:hover {
                background-color: transparent;
                outline: 1px solid var(--color-yellow);
                border-radius: var(--radius-1);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
            }
            .{$this->MainClass} table.dataTable tbody tr:is(:nth-child(odd), :nth-child(even)) th:hover {
                background-color: transparent;
                outline: 1px solid var(--color-green);
                border-radius: var(--radius-1);
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
            }
            .{$this->MainClass} table.dataTable tfoot :is(th, td) {
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
            $this->Modal->MainClass = "InternalModal";
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

    public function GetInner()
    {
        if (receiveGet($this->SecretRequestKey) === $this->SearchSecret) {
            $r = receiveGet();
            $this->Filter = pop($r, $this->KeyColumn);
        }
        foreach ($this->FilterColumns?:[] as $key => $value)
            if (is_numeric($key)) {
                if (!isEmpty($v = popValid($r, $value)))
                    $this->FilterGraph[$value] = $v;
            } elseif (!isEmpty($v = popValid($r, $key)))
                $this->FilterGraph[$value] = $v;

        $isc = $this->Controlable;
        $isu = $isc && $this->Updatable && \_::$User->HasAccess($this->UpdateAccess);
        if ($isc)
            $this->CreateModal();
        if (isValid($this->DataTable) && isValid($this->KeyColumn)) {
            if ($this->Filter && !$this->FilterPattern) {
                $cond = \_::$Back->Query->ConvertToCondition($this->Filter);
                $this->FilterPattern = "CONCAT_WS(" .
                    $this->DataTable->DataBase->StartWrap .
                    join(
                        $this->DataTable->DataBase->EndWrap . ",' '," . $this->DataTable->DataBase->StartWrap,
                        $this->DataTable->DataBase->SelectColumn("INFORMATION_SCHEMA.COLUMNS", "COLUMN_NAME", "TABLE_NAME='{$this->DataTable->Name}' AND CHARACTER_MAXIMUM_LENGTH>5")
                    ) .
                    $this->DataTable->DataBase->EndWrap . ") " . $cond;
            }
            $issq = isValid($this->SelectQuery);
            if (!$issq && $this->FilterGraph)
                    foreach ($this->FilterGraph as $key => $value) {
                        if($this->FilterPattern) $this->FilterPattern .= " AND ";
                        else $this->FilterPattern .= "";
                        $this->FilterPattern .= "$key=:$key";
                        $this->SelectParameters[":$key"] = $value;
                    }
            if ($this->AllowServerSide) {
                if ($issq) {
                    $this->NavigationBar = new Navigation($this->SelectQuery, queryParameters: $this->SelectParameters, defaultItems: $this->Items);
                    $this->Items = $this->FilterItems($this->NavigationBar->GetItems());
                } else {
                    $this->NavigationBar = new Navigation($this->DataTable->SelectQuery(
                        isEmpty($this->IncludeColumns) ? "*" : (in_array($this->KeyColumn, $this->IncludeColumns) ? $this->IncludeColumns : [$this->KeyColumn, ...$this->IncludeColumns]),
                        [
                            isEmpty($this->IncludeRows) ? null : ("{$this->KeyColumn} IN('" . join("', '", $this->IncludeRows) . "')"),
                            ...($this->FilterPattern ? [$this->FilterPattern] : []),
                            $this->SelectCondition
                        ]
                    ), queryParameters: $this->SelectParameters, defaultItems: $this->Items);
                    $this->Items = $this->NavigationBar->GetItems();
                }
            } else {
                $this->NavigationBar = new Navigation(isValid($this->SelectQuery) ? $this->FilterItems($this->DataTable->DataBase->TryFetchRows($this->SelectQuery, $this->SelectParameters, $this->Items)) :
                    $this->DataTable->Select(
                        isEmpty($this->IncludeColumns) ? "*" : (in_array($this->KeyColumn, $this->IncludeColumns) ? $this->IncludeColumns : [$this->KeyColumn, ...$this->IncludeColumns]),
                        [
                            isEmpty($this->IncludeRows) ? null : ("{$this->KeyColumn} IN('" . join("', '", $this->IncludeRows) . "')"),
                            ...($this->FilterPattern ? [$this->FilterPattern] : []),
                            $this->SelectCondition
                        ],
                        $this->SelectParameters,
                        $this->Items

                    ));
                $this->Items = $this->NavigationBar->GetItems();
            }
        } else {
            if ($this->Items)
                $this->Items = $this->FilterItems($this->Items);
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

        $vaccess = \_::$User->HasAccess($this->ViewAccess);
        $saccess = $isc && \_::$User->HasAccess($this->SearchAccess);
        $aaccess = $isu && \_::$User->HasAccess($this->AddAccess);
        $iaccess = $isu && \_::$User->HasAccess($this->ImportAccess);
        $eaccess = $isc && \_::$User->HasAccess($this->ExportAccess);
        $caccess = $isu && \_::$User->HasAccess($this->ClearAccess);
        $daccess = $isu && \_::$User->HasAccess($this->DuplicateAccess);
        $maccess = $isu && \_::$User->HasAccess($this->ModifyAccess);
        $raccess = $isu && \_::$User->HasAccess($this->RemoveAccess);

        $prefix = uniqid("b_");
        $toolBar = fn($add = true, $import = false, $export = false, $clear = false, $search = false, $class = "toolbar", $default = false) =>
            $aaccess || $iaccess || $eaccess || $caccess || $saccess ? Struct::Division(
                Struct::Box(
                    ($default ? "" : Convert::ToString($this->PrependToolsBar)) . (!$default && $this->ToolsBar ? Convert::ToString($this->ToolsBar) :
                        ($add === false || !$aaccess ? "" :
                            Struct::Button(
                                __($add === true ? ("A new " . ($this->DataTable ? "'{$this->DataTable->MainName}' " : "") . "'item'") : $add)
                                . Struct::Icon($this->AddIcon),
                                "{$this->Modal->MainClass}_Create();",
                                ["class" => "table-item-create"],
                                $add ? [] : ["style" => "padding:calc(var(--size-0) / 2);border:none;", "Tooltip" => "Add another Item"]
                            )) .
                        ($import === false || !$iaccess ? "" :
                            Struct::Button(
                                __($import) .
                                Struct::Icon($this->ImportIcon),
                                "{$this->Modal->MainClass}_Import();",
                                ["class" => "table-items-import"],
                                $import ? [] : ["style" => "padding:calc(var(--size-0) / 2);border:none;", "Tooltip" => "Import Items"]
                            )) .
                        ($export === false || !$eaccess ? "" :
                            Struct::Button(
                                __($export) .
                                Struct::Icon($this->ExportIcon),
                                "{$this->Modal->MainClass}_Export();",
                                ["class" => "table-items-export"],
                                $export ? [] : ["style" => "padding:calc(var(--size-0) / 2);border:none;", "Tooltip" => "Export Items"]
                            )) .
                        ($clear === false || !$caccess ? "" :
                            Struct::Button(
                                __($clear) .
                                Struct::Icon($this->ClearIcon),
                                "{$this->Modal->MainClass}_Clear();",
                                ["class" => "table-items-clear"],
                                $clear ? [] : ["style" => "padding:calc(var(--size-0) / 2);border:none;", "Tooltip" => "Clear all Items"]
                            ))
                    ) . ($default ? "" : Convert::ToString($this->AppendToolsBar))
                    ,
                    ["class" => "be flex start"]
                ) . ($search === false || !$saccess || !$this->AllowSearching ? "" :
                    Struct::Box(
                        [
                            Struct::Box(
                                Struct::Field(
                                    "Search",
                                    $search,
                                    $this->Filter ?$this->Filter: ($this->FilterGraph?"":$this->FilterPattern),
                                    attributes: [
                                        "id" => "{$prefix}_search",
                                        "onchange" => "{$this->Modal->MainClass}_Search(this.value)",
                                        "style" => "margin-inline-start:var(--size-0);",
                                        "wrapper" => ["style" => "width: auto;"]
                                    ]
                                ) .
                                Struct::Icon("search", "{$this->Modal->MainClass}_Search(document.getElementById('{$prefix}_search').value)"),
                                ["class" => "be flex middle"]
                            ),
                            $this->FilterGraph ? Struct::Box(loop(
                                $this->FilterGraph,
                                fn($v, $k) => Struct::Span("$k: $v " . Struct::Icon("close", "{$this->Modal->MainClass}_Search(document.getElementById('{$prefix}_search').value, " . Script::Convert("$k=") . ")", ["class" => "be fore red"]))
                            ), ["class" => "be flex"]) : ""
                        ],
                        ["class" => "be flex end", "style" => "padding-inline-start: var(--size-0);"]
                    )),
                ["class" => $class]
            ) : null;

        if ($isc) {
            $uck = Struct::Division(($toolBar)(null, null, null, null, class: null, default: true) ?? Struct::Image(null, "tasks"), ["class" => "table-items-manage"]);
            if ($ick)
                array_unshift($icks, $uck);
        }
        $strow = "<{$this->RowsTagName}>";
        $etrow = "</{$this->RowsTagName}>";
        if (is_countable($this->Items) && (($this->NavigationBar != null && $this->NavigationBar->Count > 0) || count($this->Items) > 0)) {
            $cells = [];
            foreach ($this->Items as $rkey => $row)
                if (!isEmpty($row)) {
                    $rowid = getBetween($row, $this->KeyColumn, preg_find("/\w+$/i", $this->KeyColumn));
                    $strow = "<{$this->RowsTagName} data-key=\"" . (str_replace("\"", "-", "$rowid")) . "\">";
                    if (
                        (!$irk || in_array($rkey, $irks)) &&
                        (!$erk || !in_array($rkey, $erks)) &&
                        (!$hasid || (in_array($rowid, $irids) || !in_array($rowid, $erids)))
                    ) {
                        $isrk = ($rkey === $this->KeyRow) || in_array($rkey, $rks) || ($hasid && $rowid === $rkey);
                        if ($rkls)
                            array_unshift($row, is_int($rkey) ? ($hrn ? $rkey + $srn : "") : $rkey);
                        if ($isc) {
                            $row =
                                [
                                    $uck => Struct::Box([
                                        ...(is_null($rowid) ? [] : [Struct::Hidden($rowid)]),
                                        ...[($hrn ? Struct::Span($rn++, null, ['class' => 'number']) : "")],
                                        ...Convert::ToSequence(Convert::By($this->PrependControls, $rowid, $row) ?? []),
                                        ...(is_null($rowid) ? [] : [
                                            ...($vaccess ? [Struct::Icon($this->ViewIcon, "{$this->Modal->MainClass}_View(`$rowid`);", ["class" => "table-item-view", "tooltip" => "Show"])] : []),
                                            ...($maccess ? [Struct::Icon($this->ModifyIcon, "{$this->Modal->MainClass}_Modify(`$rowid`);", ["class" => "table-item-modify", "tooltip" => "Modify"])] : []),
                                            ...($daccess ? [Struct::Icon($this->DuplicateIcon, "{$this->Modal->MainClass}_Duplicate(`$rowid`);", ["class" => "table-item-duplicate", "tooltip" => "Duplicate Copy"])] : []),
                                            ...($raccess ? [Struct::Icon($this->RemoveIcon, "{$this->Modal->MainClass}_Delete(`$rowid`);", ["class" => "table-item-delete", "tooltip" => "Remove"])] : []),
                                        ]),
                                        ...Convert::ToSequence(Convert::By($this->AppendControls, $rowid, $row) ?? [])
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
                                        $cells[] = "<{$this->RowsTagName}>";
                                        foreach ($icks as $ci => $ckey) {
                                            $ci = (is_int($ci) ? $ckey : $ci);
                                            if (!$eck || !in_array($ci, $ecks))
                                                $cells[] = $this->GetCell($cn++, $ci, $row, true);
                                        }
                                        $cells[] = $etrow;
                                    }
                                    $cells[] = "<{$this->RowsTagName}>";
                                    foreach ($icks as $ci => $ckey) {
                                        $ci = (is_int($ci) ? $ckey : $ci);
                                        if (!$eck || !in_array($ci, $ecks))
                                            $cells[] = $this->GetCell(is_int($ckey) ? ($hcn ? $ckey + $scn : "") : $ci, $ckey, $row, true);
                                    }
                                    $cells[] = $etrow;
                                } else {
                                    if ($hcn) {
                                        $cells[] = "<{$this->RowsTagName}>";
                                        foreach ($row as $ckey => $cel)
                                            if (!$eck || !in_array($ckey, $ecks))
                                                $cells[] = $this->GetCell($cn++, $ckey, $row, true);
                                        $cells[] = $etrow;
                                    }
                                    $cells[] = "<{$this->RowsTagName}>";
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
                            foreach ($icks as $ci => $ckey) {
                                $ckey = is_int($ci) ? $ckey : $ci;
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
                        $cells[] = Struct::Cell("", $isc || $hrn ? ["Type" => "head", "class" => "view invisible"] : ["Type" => "head"]);
                    for ($i = 1; $i < $colCount; $i++)
                        $cells[] = Struct::Cell("", ["Type" => "head"]/*$ick&&isset($icks[$ckey])?$cks[$icks[$ckey]]:false*/);
                    $cells[] = "</tr></tfoot>";
                } else
                    $cells[] = Convert::ToString($this->Footer);
            return ($isc ? $this->HandleModal() : "") .
                $toolBar(true, "Import items", "Export items", "Clear items", "Search") .
                (($this->TopNavigation && !empty($this->NavigationBar)) ? $this->NavigationBar->ToString() : "") .
                Struct::Table(join(PHP_EOL, $cells), attributes:["caption"=>$this->Caption]) .
                (($this->BottomNavigation && !empty($this->NavigationBar)) ? $this->NavigationBar->ToString() : "");
        } elseif ($aaccess || $iaccess)
            return ($isc ? $this->HandleModal() : "") .
                $toolBar(true, "Import items", "Export items", "Clear items", "Search") .
                Struct::Table("", attributes:["caption"=>$this->Caption]);
        return ($isc ? $this->HandleModal() : "") . Struct::Table("", attributes:["caption"=>$this->Caption]);
    }
    public function FilterItems($items)
    {
        if ($this->Filter || $this->FilterGraph) {
            if ($this->Filter)
                $this->FilterPattern = \_::$Back->Query->ConvertToPattern($this->Filter);
            return filter(
                $items,
                fn($value) => (!$this->FilterGraph || graphAnd($value, $this->FilterGraph))
                && (!$this->Filter || take(
                    $value,
                    fn($val, $key) => is_string($val) && preg_match($this->FilterPattern, $val)
                )
                )
            );
        }
        return $items;
    }
    public function GetCell($value, $key, $record = [], bool $isHead = false)
    {
        if (!$isHead || $value !== $key)
            $value = Convert::ToString(Convert::By(get($this->CellsValues, $key), $value, $key, $record) ?? $value);
        if ($isHead) {
            $value = Convert::ToString($value);
            $st = "<th data-key=\"" . str_replace("\"", "-", $key) . "\">";
            if (isMedia($value))
                return $st . Struct::Media($value) . "</th>";
            else if (isAbsoluteUrl($value))
                return $st . Struct::Link(getUrlResource($value), $value) . "</th>";
            else
                return $st . __($value, translating: $this->AllowLabelTranslation) . "</th>";
        }
        $st = "<{$this->ColumnsTagName} data-key=\"" . str_replace("\"", "-", $key) . "\">";
        $et = "</{$this->ColumnsTagName}>";
        //if($this->Updatable && !$isHead && $key > 1){
        //    $value = new Field(key:$key, value: $value, lock: true, type:getValid($this->CellsTypes,$key, null));
        //    $value->MinWidth = $this->MediaWidth;
        //    $value->MaxHeight = $this->MediaHeight;
        //    return $st.Convert::ToString($value).$et;
        //}
        if (isMedia($value) || in_array($key, ["Audio", "Video", "Image", "Icon", "Media"]))
            return $st . Struct::Media($value) . $et;
        if (isUrl($value))
            return $st . Struct::Link(getUrlResource($value), $value) . "</td>";
        $value = __($value, translating: $this->AllowDataTranslation);
        if (!$this->TextWrap && !startsWith($value, "<"))
            return $st . Convert::ToExcerpt(Convert::ToText($value), 0, $this->TextLength, "..." . ($this->AllowTooltip ? Struct::Tooltip($value) : "")) . $et;
        return "$st$value$et";
    }
    public function GetScript()
    {
        $localPaging = is_null($this->NavigationBar);
        return Struct::Script(
            "_(document).ready(()=>{" .
            (!$this->AllowDecoration ? "" :
                "$('.{$this->MainClass} table').DataTable({" .
                join(", ", [
                    ...(is_null($this->AllowCache) ? [] : ["stateSave: " . ($this->AllowCache ? "true" : "false")]),
                    ...(is_null($this->AllowPaging) ? [] : ["paging: " . ($this->AllowPaging ? ($localPaging ? "true" : "false") : "false")]),
                    ...(is_null($this->AllowSearching) ? [] : ["searching: " . ((!$this->DataTable && $this->AllowSearching) ? "true" : "false")]),
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
                (is_null($this->Modal) ? "" : (\_::$User->HasAccess($this->ViewAccess) ? "
                    function {$this->Modal->MainClass}_View(key, path = null){
                        send('{$this->Method}', path, {{$this->SecretRequestKey}:'{$this->ViewSecret}','{$this->KeyColumn}':key}, `.{$this->MainClass}`,
                            (data, err)=>{
                                " . $this->Modal->InitializeScript(null, null, '${data}') . "
                            }
                        );
                    }" : "") . (\_::$User->HasAccess($this->SearchAccess) ? ($this->FilterColumns ?
                    join("\n", loop($this->FilterColumns, function ($v, $k) {
                        if(is_numeric($k)){
                            $k = $v;
                            $v = null;
                        }
                        $k = str_replace("\"", "-", $k);
                        return "
                            _(".Script::Convert(".{$this->MainClass} [data-key=\"$k\"]").").dblclick((e,b)=>{$this->Modal->MainClass}_Filter(" . Script::Convert($k) . ", b.innerText));";
                    })) : ""
                    ) . "
                    function {$this->Modal->MainClass}_Filter(key=null, value = null, path = null){
                        return {$this->Modal->MainClass}_Search(".Script::Convert($this->Filter).", encodeURIComponent(key)+'='+encodeURIComponent(value), path);
                    }
                    function {$this->Modal->MainClass}_Search(query=null, suffix = null, path = null){
                        load(path?path:(" . Script::Convert(\_::$Address->UrlBase . "?" . $this->SecretRequestKey . "=" .
                            urlencode($this->SearchSecret)) . "+(query?".Script::Convert("&" . $this->KeyColumn . "=")."+encodeURIComponent(query):'')+" .
                            ($this->FilterGraph ? Script::Convert("&" . join("&", loop($this->FilterGraph, fn($v, $k) => "$k=" . urlencode($v)))) : "''") .
                            "+(suffix?'&'+suffix:''))
                        );
                    }" : "") . ($this->Updatable ?
                    (\_::$User->HasAccess($this->ImportAccess) ? "
                    function {$this->Modal->MainClass}_Import(defaultValues = null, path = null){
                        send('{$this->Method}', path, {{$this->SecretRequestKey}:'{$this->ImportSecret}','{$this->KeyColumn}':'{$this->ImportSecret}'}, `.{$this->MainClass}`,
                            (data, err)=>{
                                " . $this->Modal->InitializeScript(null, null, '${data}') . "
                                if(defaultValues)
                                    for(x in defaultValues)try{
                                        document.querySelector('.{$this->Modal->MainClass} *[name=\"'+x+'\"]').value = defaultValues[x];
                                    }catch{}
                            }
                        );
                    }" : "") . (\_::$User->HasAccess($this->ExportAccess) ? "
                    function {$this->Modal->MainClass}_Export(defaultValues = null, path = null){
                        sendRequest('{$this->Method}', path, {{$this->SecretRequestKey}:'{$this->ExportSecret}','{$this->KeyColumn}':'{$this->ExportSecret}'}, `.{$this->MainClass}`,
                            (data, err)=>{
                                " . $this->Modal->InitializeScript(null, null, '${data}') . "
                                if(defaultValues)
                                    for(x in defaultValues)try{
                                        document.querySelector('.{$this->Modal->MainClass} *[name=\"'+x+'\"]').value = defaultValues[x];
                                    }catch{}
                            }
                        );
                    }" : "") . (\_::$User->HasAccess($this->ClearAccess) ? "
                    function {$this->Modal->MainClass}_Clear(defaultValues = null, path = null){
                        if(confirm(`" . __("Are you sure you want to clear all items?") . "`))
                            send('{$this->Method}', path, {{$this->SecretRequestKey}:'{$this->ClearSecret}','{$this->KeyColumn}':'{$this->ClearSecret}'}, `.{$this->MainClass}`,
                                (data, err)=>{
                                    if(err) console.error(err);
                                    else load();
                                });
                    }" : "") . (\_::$User->HasAccess($this->AddAccess) ? "
                    function {$this->Modal->MainClass}_Create(defaultValues = null, path = null){
                        send('{$this->Method}', path, {{$this->SecretRequestKey}:'{$this->AddSecret}','{$this->KeyColumn}':'{$this->AddSecret}'}, `.{$this->MainClass}`,
                            (data, err)=>{
                                " . $this->Modal->InitializeScript(null, null, '${data}') . "
                                if(defaultValues)
                                    for(x in defaultValues)try{
                                        document.querySelector('.{$this->Modal->MainClass} *[name=\"'+x+'\"]').value = defaultValues[x];
                                    }catch{}
                            }
                        );
                    }" : "") . (\_::$User->HasAccess($this->ModifyAccess) ? "
                    function {$this->Modal->MainClass}_Modify(key, path = null){
                        send('{$this->Method}', path, {{$this->SecretRequestKey}:'{$this->ModifySecret}','{$this->KeyColumn}':key}, `.{$this->MainClass}`,
                            (data, err)=>{
                                " . $this->Modal->InitializeScript(null, null, '${data}') . "
                            }
                        );
                    }" : "") . (\_::$User->HasAccess($this->DuplicateAccess) ? "
                    function {$this->Modal->MainClass}_Duplicate(key, path = null){
                        send('{$this->Method}', path, {{$this->SecretRequestKey}:'{$this->DuplicateSecret}','{$this->KeyColumn}':key}, `.{$this->MainClass}`,
                            (data, err)=>{
                                " . $this->Modal->InitializeScript(null, null, '${data}') . "
                            }
                        );
                    }" : "") . (\_::$User->HasAccess($this->RemoveAccess) ? "
                    function {$this->Modal->MainClass}_Delete(key, path = null){
                        " . ($this->SevereSecure ? "if(confirm(`" . __("Are you sure you want to remove this item?") . "`))" : "") . "
                            send('{$this->Method}', path, {{$this->SecretRequestKey}:'{$this->RemoveSecret}','{$this->KeyColumn}':key}, `.{$this->MainClass}`,
                            (data, err)=>{
                                if(err) console.error(err);
                                if(data) return _('.{$this->MainClass} tr:has(hidden[value=\"'+key+'\"])').remove();
                                //load();
                            });
                    }" : "") : "")
                ) : "")
        ) . ($this->RefreshTimeout ? (new (module("Counter"))(max(1, $this->RefreshTimeout / 1000), 0, "load()"))->GetScript() : "");
    }
    public function FilterScript($key, $value){
        return "{$this->Modal->MainClass}_Filter(".Script::Convert($key).",".Script::Convert($value).")";
    }
    public function SearchScript($query, $suffix = null){
        return "{$this->Modal->MainClass}_Search(".Script::Convert($query).",".Script::Convert($suffix).")";
    }

    public function Exclusive()
    {
        $values = receive($this->Method) ?? [];
        $value = get($values, $this->KeyColumn);
        $secret = pop($values, $this->SecretRequestKey);
        $metadata = Convert::FromJson(pop($values, $this->MetaDataRequestKey));
        $multipleRows = $metadata["Multiple"] ?? false;
        $receivedSingleRow = !$multipleRows && count($values) > 1;
        if (!$secret)
            return Struct::Error("Your request is not valid!");
        elseif ($secret === $this->ViewSecret)
            if ($receivedSingleRow)
                return ($this->ViewHandler)($values) ?? $this->ViewRow($values);
            else
                return ($this->ViewRequestHandler)($value) ?? $this->GetViewForm($value);
        elseif ($secret === $this->DuplicateSecret)
            if ($receivedSingleRow)
                return ($this->DuplicateHandler)($values) ?? $this->DuplicateRow($values);
            else
                return ($this->DuplicateRequestHandler)($value) ?? $this->GetDuplicateForm($value);
        elseif ($secret === $this->AddSecret)
            if ($receivedSingleRow)
                return ($this->AddHandler)($values) ?? $this->AddRow($values);
            else
                return ($this->AddRequestHandler)($value) ?? $this->GetAddForm($value);
        elseif ($secret === $this->ModifySecret)
            if ($receivedSingleRow)
                return ($this->ModifyHandler)($values) ?? $this->ModifyRow($values);
            else
                return ($this->ModifyRequestHandler)($value) ?? $this->GetModifyForm($value);
        elseif ($secret === $this->RemoveSecret)
            if ($receivedSingleRow)
                return ($this->RemoveHandler)($values) ?? $this->RemoveRow($values);
            else
                return ($this->RemoveRequestHandler)($value) ?? $this->GetRemoveForm($value);
        elseif ($secret === $this->ImportSecret)
            if ($receivedSingleRow)
                return ($this->ImportHandler)($values) ?? $this->ImportRows(Convert::ToFields(open(received($this->RowsRequestKey))), $values);
            else
                return ($this->ImportRequestHandler)($value) ?? $this->GetImportForm();
        elseif ($secret === $this->ExportSecret)
            if ($receivedSingleRow)
                return ($this->ExportHandler)($values) ?? $this->ExportRows(Convert::ToFields(open(received($this->RowsRequestKey))), $values);
            else
                return ($this->ExportRequestHandler)($value) ?? $this->GetExportForm();
        elseif ($secret === $this->ClearSecret)
            if ($receivedSingleRow)
                return ($this->ClearHandler)($values) ?? $this->ClearRows(Convert::ToFields(open(received($this->RowsRequestKey))), $values);
            else
                return ($this->ClearRequestHandler)($value) ?? $this->GetClearForm();

        // if ($receivedSingleRow)
        //     return ($this->ExportHandler)($values) ?? $this->ExportRows(Convert::ToFields(urldecode(pop($values, $this->RowsRequestKey))), $values);
        // else
        //     return ($this->ExportRequestHandler)($value) ?? $this->GetExportForm();
        else
            return Struct::Error("There is not any response for your request!");
    }

    public function GetForm(): Form|null
    {
        module("Form");
        if (!$this->Form) {
            $this->Form = new Form();
            $this->Form->Template = "b";
            $this->Form->Class = "container";
            $this->Form->ContentClass = "col-lg-8";
            $this->Form->CancelLabel = "Cancel";
            $this->Form->SuccessPath = \_::$Address->Url;
            $this->Form->BackPath = \_::$Address->Url;
            $this->Form->BackLabel = null;
            $this->form->AllowAnimate = false;
            //$form->AllowHeader = false;
        }
        $this->Form->Router->Get()->Switch();
        $this->Form->Method = $this->Method;
        if ($this->Modal) {
            $this->Form->CancelPath = $this->Modal->HideScript();
            $this->Form->CancelLabel = "Cancel";
        } else
            $this->Form->CancelLabel = null;
        return $this->Form;
    }

    public function GetImportForm()
    {
        if (!\_::$User->HasAccess($this->ImportAccess))
            return Struct::Error("You do not have enough access to 'Import'!");
        $record = [];
        foreach ($this->CellsTypes as $key => $val)
            $record[$key] = null;
        $form = $this->GetForm();
        $form->Image = $this->ImportIcon;
        $form->Set(
            title: "Import {$this->Title}",
            description: $this->Description,
            method: null,
            items: (function () use ($record) {
                $schemas = $this->DataTable->DataBase->TryFetchRows(
                    "SELECT COLUMN_NAME
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_NAME='{$this->DataTable->Name}' ORDER BY ORDINAL_POSITION ASC"
                );
                if (!$this->FormFilter)
                    foreach ($schemas as $schema)
                        $record[$schema["COLUMN_NAME"]] = null;

                yield Struct::Field("File", $this->RowsRequestKey, null, title: "Table", attributes: ["Accept" => ".csv"]);
                foreach ($record as $key => $val)
                    yield Struct::Field("Text", $key, $key, title: "\${{$key}}", options: $record);
                yield Struct::HiddenInput($this->SecretRequestKey, $this->ImportSecret);
            })()
        );
        return $form->Handle();
    }
    public function GetExportForm()
    {
        if (!\_::$User->HasAccess($this->ExportAccess))
            return Struct::Error("You do not have enough access to 'Export'!");
        $record = [];
        foreach ($this->CellsTypes as $key => $val)
            $record[$key] = null;
        $form = $this->GetForm();
        $form->Image = $this->ExportIcon;
        $form->Set(
            title: "Export {$this->Title}",
            description: $this->Description,
            method: null,
            items: (function () use ($record) {
                $schemas = $this->DataTable->DataBase->TryFetchRows(
                    "SELECT COLUMN_NAME
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_NAME='{$this->DataTable->Name}' ORDER BY ORDINAL_POSITION ASC"
                );
                if (!$this->FormFilter)
                    foreach ($schemas as $schema)
                        $record[$schema["COLUMN_NAME"]] = null;

                yield Struct::Field("Texts", $this->RowsRequestKey, null, title: "Selected Rows", attributes: ["Accept" => ".csv"]);
                foreach ($record as $key => $val)
                    yield Struct::Field("checkbox", $key, $key, title: "\${{$key}}", attributes: ["checked"]);
                yield Struct::HiddenInput($this->SecretRequestKey, $this->ExportSecret);
            })()
        );
        return $form->Handle();
    }
    public function GetClearForm()
    {
        return $this->ClearRows();
    }

    public function GetViewForm($value)
    {
        if (is_null($value))
            return null;
        if (!\_::$User->HasAccess($this->ViewAccess))
            return Struct::Error("You do not have enough access to see datails!");
        if (!$this->DataTable->AlternativeName && !$this->KeyColumnName)
            $this->KeyColumnName = $this->KeyColumn;
        $row = $this->DataTable->SelectRow("*", [$this->ViewCondition, "`{$this->KeyColumn}`=:{$this->KeyColumnName}"], [":{$this->KeyColumnName}" => $value]);
        if (isEmpty($row))
            return Struct::Error("You can not 'see' this item!");
        $record = [];
        if ($this->FormFilter)
            foreach ($this->CellsTypes as $key => $val)
                $record[$key] = null;
        else
            foreach ($row as $key => $val)
                $record[$key] = $val;
        unset($row);
        $form = $this->GetForm();
        $form->Set(
            title: getBetween($record, "Title", "Name"),
            description: get($record, "Description"),
            action: '#',
            method: "",
            items: (function () use ($record) {
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
                        yield Struct::Field(
                            type: (isEmpty($type) ? null : Convert::By($type, $type, $cell, $k, $record)),
                            key: $k,
                            value: $cell,
                            description: false,
                            attributes: ["disabled"]
                        );
                }
            })()
        );
        $form->Image = getValid($record, "Image", $this->ViewIcon);
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
    public function GetAddForm($value)
    {
        if (is_null($value))
            return null;
        if (!\_::$User->HasAccess($this->AddAccess))
            return Struct::Error("You do not have enough access to 'add'!");
        $record = [];
        $form = $this->GetForm();
        $form->Set(
            title: "Add {$this->Title}",
            description: $this->Description,
            method: null,
            items: (function () use (&$record, $value) {
                $schemas = $this->DataTable->DataBase->TryFetchRows(
                    "SELECT COLUMN_NAME, COLUMN_TYPE, DATA_TYPE, COLUMN_DEFAULT, COLUMN_COMMENT, IS_NULLABLE, EXTRA
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_NAME='{$this->DataTable->Name}' ORDER BY ORDINAL_POSITION ASC"
                );
                if ($this->FormFilter)
                    foreach ($this->CellsTypes as $key => $val)
                        $record[$key] = null;
                else
                    foreach ($schemas as $schema)
                        $record[$schema["COLUMN_NAME"]] = null;
                foreach ($record as $key => $val)
                    foreach ($schemas as $schema)
                        if ($schema["COLUMN_NAME"] === $key)
                        {
                            $val = ($key == $this->KeyColumn) ? $value : null;
                            $res = $this->PrepareDataToShow($val, $key, $record, $schema);
                            if (!isEmpty($res))
                                yield $res;
                        }
                yield Struct::HiddenInput($this->SecretRequestKey, $this->AddSecret);
            })()
        );
        $form->Image = getValid($record, "Image", $this->AddIcon);
        return $form->Handle();
    }
    public function GetDuplicateForm($value)
    {
        if (is_null($value))
            return null;
        if (!\_::$User->HasAccess($this->DuplicateAccess))
            return Struct::Error("You do not have enough access to 'duplicate'!");
        if (!$this->DataTable->AlternativeName && !$this->KeyColumnName)
            $this->KeyColumnName = $this->KeyColumn;
        $row = $this->DataTable->SelectRow("*", [$this->DuplicateCondition, "`{$this->KeyColumn}`=:{$this->KeyColumnName}"], [":{$this->KeyColumnName}" => $value]);
        if (isEmpty($row))
            return Struct::Error("You can not 'duplicate' this item!");
        $record = [];
        if ($this->FormFilter)
            foreach ($this->CellsTypes as $key => $val)
                $record[$key] = null;
        else
            foreach ($row as $key => $val)
                $record[$key] = $val;
        unset($row);
        unset($record[$this->KeyColumnName]);
        $form = $this->GetForm();
        $form->Set(
            title: "Add {$this->Title}",
            description: $this->Description,
            method: null,
            items: (function () use (&$record, $value) {
                $schemas = $this->DataTable->DataBase->TryFetchRows(
                    "SELECT COLUMN_NAME, COLUMN_TYPE, DATA_TYPE, COLUMN_DEFAULT, COLUMN_COMMENT, IS_NULLABLE, EXTRA
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_NAME='{$this->DataTable->Name}' ORDER BY ORDINAL_POSITION ASC"
                );
                yield Struct::HiddenInput($this->KeyColumnName, $this->DuplicateSecret);
                foreach ($record as $key => $val)
                    foreach ($schemas as $schema)
                        if ($schema["COLUMN_NAME"] === $key)
                        {
                            $res = $this->PrepareDataToShow($val, $key, $record, $schema);
                            if (!isEmpty($res))
                                yield $res;
                        }
                yield Struct::HiddenInput($this->SecretRequestKey, $this->DuplicateSecret);
            })()
        );
        $form->Image = getValid($record, "Image", $this->DuplicateIcon);
        return $form->Handle();
    }
    public function GetModifyForm($value)
    {
        if (is_null($value))
            return null;
        if (!$this->DataTable->AlternativeName && !$this->KeyColumnName)
            $this->KeyColumnName = $this->KeyColumn;
        if (!\_::$User->HasAccess($this->ModifyAccess))
            return Struct::Error("You do not have enough access to 'modify'!");
        $row = $this->DataTable->SelectRow("*", [$this->ModifyCondition, "{$this->KeyColumn}=:{$this->KeyColumnName}"], [":{$this->KeyColumnName}" => $value]);
        if (isEmpty($row))
            return Struct::Error("You can not 'modify' this item!");
        $record = [];
        if ($this->FormFilter)
            foreach ($this->CellsTypes as $key => $val)
                $record[$key] = null;
        else
            foreach ($row as $key => $val)
                $record[$key] = $val;
        unset($row);
        unset($record[$this->KeyColumnName]);
        $form = $this->GetForm();
        $form->Set(
            title: getBetween($record, "Title", "Name"),
            description: get($record, "Description"),
            method: null,
            items: (function () use ($record, $value) {
                $schemas = $this->DataTable->DataBase->TryFetchRows(
                    "SELECT COLUMN_NAME, COLUMN_TYPE, DATA_TYPE, COLUMN_DEFAULT, COLUMN_COMMENT, IS_NULLABLE, EXTRA
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_NAME='{$this->DataTable->Name}' ORDER BY ORDINAL_POSITION ASC"
                );
                yield Struct::HiddenInput($this->KeyColumnName, $value);
                foreach ($record as $key => $val)
                    foreach ($schemas as $schema)
                        if ($schema["COLUMN_NAME"] == $key)
                        {
                            $res = $this->PrepareDataToShow($val, $key, $record, $schema);
                            if (!isEmpty($res))
                                yield $res;
                            break;
                        }
                yield Struct::HiddenInput($this->SecretRequestKey, $this->ModifySecret);
            })()
        );
        $form->Image = getValid($record, "Image", $this->ModifyIcon);
        if(!$this->Quick) $form->Buttons = [Struct::Button("'Submit' and 'Close'", "submitForm(s='" . ($this->Form->Id ? ("#" . $this->Form->Id) : ("." . $this->Form->MainClass)) . " form', (d,e)=>{_(s).append(d??e);if(!e) reload();})", ["class" => "main"])];
        return $form->Handle();
    }
    public function GetRemoveForm($value)
    {
        return $this->RemoveRow([$this->KeyColumn => $value]);
    }

    public function ImportRows($rows, $cols = [])
    {
        if (!$this->DataTable || !\_::$User->HasAccess($this->ImportAccess))
            return Struct::Error("You do not have enough access to 'import'!");
        $this->DataTable->Reset();
        $s = 0;
        $e = 0;
        foreach ($cols ?: [] as $k => $v)
            if (isEmpty($v))
                unset($cols[$k]);
        foreach ($rows as $values) {
            $values = $this->NormalizeFormValues($values);
            if (!is_array($values))
                continue;
            if ($cols) {
                $cells = [];
                foreach ($cols as $k => $v)
                    if (isset($values[$v]) && !isEmpty($values[$v]))
                        $cells[$k] = $values[$v];
                if ($this->DataTable->Insert($cells))
                    $s++;
                else
                    $e++;
            } else {
                foreach ($values as $k => $v)
                    if (isEmpty($v))
                        unset($values[$k]);
                if ($this->DataTable->Insert($values))
                    $s++;
                else
                    $e++;
            }
        }
        if ($e == 0 && $s > 0)
            return deliverRedirect(Struct::Success("All $s items imported successfully!"));
        elseif ($s == 0)
            return Struct::Error("Could not import any item of " . count($rows) . " items!");
        else
            return Struct::Warning("$s items imported and $e items failed!");
    }
    public function ExportRows($rows, $cols = [])
    {
        if (!\_::$User->HasAccess($this->ExportAccess))
            return Struct::Error("You do not have enough access to 'export'!");
        foreach ($cols ?: [] as $k => $v)
            if (isEmpty($v))
                unset($cols[$k]);
        $this->Items = [];
        if ($this->DataTable) {
            if ($rows)
                $this->Items = [[], ...$this->DataTable->Select($cols, [array_key_first($rows) . " IN (" . join(", ", filter($rows, fn($v) => $v, [])) . ")", $this->SelectCondition])];
            else
                $this->Items = [[], ...$this->DataTable->Select($cols, $this->SelectCondition)];
            if ($cols)
                $this->Items[0] = $cols;
            else
                $this->Items[0] = $this->DataTable->DataBase->SelectColumn("INFORMATION_SCHEMA.COLUMNS", "COLUMN_NAME", "TABLE_NAME='{$this->DataTable->Name}'");
        } else if ($this->Items)
            if ($cols && count($cols) !== count(first($this->Items) ?? [])) {
                $fields = Convert::CellsToFields($this->Items);
                $rk = array_key_first($rows) ?? 0;
                $fields = $rows ?
                    loop($fields, fn($r, $ri) => in_array($rk, $r) && in_array($r[$rk], $rows) ? loop($r, fn($v, $k) => in_array($k, $cols) ? [$cols[$k], $v] : null, false, true) : null) :
                    loop($fields, fn($r, $ri) => loop($r, fn($v, $k) => in_array($k, $cols) ? [$cols[$k], $v] : null, false, true));
                $this->Items = Convert::FieldsToCells($fields);
            }
        return uploadContent(Convert::FromCells($this->Items), ($this->Title ?: \_::$Address->UrlResource) . ".csv");
    }
    public function ClearRows($rows = [], $cols = [])
    {
        if (!\_::$User->HasAccess($this->ClearAccess))
            return Struct::Error("You do not have enough access to 'clear'!");
        foreach ($cols ?: [] as $k => $v)
            if (isEmpty($v))
                unset($cols[$k]);
        $res = null;
        if ($this->DataTable) {
            if ($rows)
                if ($cols)
                    $res = $this->DataTable->Update(array_key_first($rows) . " IN (" . join(", ", filter($rows, fn($v) => $v, [])) . ")", $cols);
                else
                    $res = $this->DataTable->Delete(array_key_first($rows) . " IN (" . join(", ", filter($rows, fn($v) => $v, [])) . ")");
            else
                if ($cols)
                    $res = $this->DataTable->Update(null, $cols);
                else
                    $res = $this->DataTable->Delete();
        } elseif ($this->Items)
            $this->Items = [];
        if ($res)
            return deliverSuccess("All 'items' removed successfully!");
        else
            return Struct::Error("You can not 'remove' this item!");
    }

    public function ViewRow($values)
    {
        return null;
    }
    public function AddRow($values)
    {
        if (!\_::$User->HasAccess($this->AddAccess))
            return Struct::Error("You do not have enough access to modify!");
        unset($values[$this->KeyColumn]);
        $values = $this->NormalizeFormValues($values);
        if (!is_array($values))
            return $values;
        foreach ($values as $k => $v)
            if (isEmpty($v))
                unset($values[$k]);
        if ($this->DataTable->Reset()->Insert($values))
            return deliverRedirect(Struct::Success("The 'information' added successfully!"));
        return Struct::Error("You can not 'add' this item!");
    }
    public function DuplicateRow($values)
    {
        return $this->AddRow($values);
    }
    public function ModifyRow($values)
    {
        if (!\_::$User->HasAccess($this->ModifyAccess))
            return Struct::Error("You do not have enough access to modify!");
        if (!$this->DataTable->AlternativeName && !$this->KeyColumnName)
            $this->KeyColumnName = $this->KeyColumn;
        if (isValid($values, $this->KeyColumn)) {
            $values = $this->NormalizeFormValues($values);
            if (!is_array($values))
                return $values;
            if ($this->DataTable->Reset()->Update([$this->ModifyCondition, "{$this->KeyColumn}=:{$this->KeyColumnName}"], $values))
                if($this->Quick) return deliverRedirect(Struct::Success("The information updated successfully!"));
                else return deliverSuccess("The information updated successfully!");
            return Struct::Error("You can not 'update' this item!");
        }
    }
    public function RemoveRow($values)
    {
        $value = get($values, $this->KeyColumn);
        if (is_null($value))
            return null;
        if (!$this->DataTable->AlternativeName && !$this->KeyColumnName)
            $this->KeyColumnName = $this->KeyColumn;
        if (!\_::$User->HasAccess($this->RemoveAccess))
            return Struct::Error("You do not have enough access to 'delete'!");
        if ($this->DataTable->Reset()->Delete([$this->RemoveCondition, "`{$this->KeyColumn}`=:{$this->KeyColumnName}"], [":{$this->KeyColumnName}" => $value]))
            return deliverSuccess("The 'items' removed successfully!");
        return Struct::Error("You can not 'remove' this item!");
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
            return Struct::HiddenInput($key, $value);
        else {
            if (is_string($type))
                switch (strtolower($type)) {
                    case "pass":
                    case "password":
                        if ($this->CryptPassword)
                            $value = \_::$User->DecryptPassword($value);
                        break;
                    case "longtext":
                        if (endswith($key ?? "", "MetaData"))
                            $type = "json";
                        break;
                    case "datetime":
                        if (!$value) {
                            $value = Convert::ToDateTimeString();
                            $type = "calendar";
                        }
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
                return Struct::Field(
                    type: isEmpty($type) ? null : Convert::By($type, $type, $value, $key, $record),
                    key: $key,
                    value: $value,
                    description: $schema["COLUMN_COMMENT"],
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
                    if (isset($v["name"])) {
                        if (Storage::IsFileObject($v)) {
                            $values[$k] = $clearPrev ? $this->DataTable->SelectValue("`$k`", "`$this->KeyColumn`=:$this->KeyColumn", [":$this->KeyColumn" => $values[$this->KeyColumn]]) : null;
                            if (isValid($values[$k]) && $this->DataTable->Count("`$k`", "`$k`=:$k", [":$k" => $values[$k]]) <= 1)
                                Storage::DeleteFile($values[$k]);
                            $values[$k] = null;
                            $types = getValid($this->CellsTypes, $k, "");
                            if (is_string($types))
                                $types = \_::$Back->GetAcceptableFormats($types);
                            else
                                $types = \_::$Back->GetAcceptableFormats();
                            $values[$k] = Storage::GetUrl(Storage::StoreFile($v, extensions: $types));
                        }
                    } elseif (isEmpty($v))
                        unset($values[$k]);
            }
        } catch (\Exception $ex) {
            return Struct::Error($ex);
        }
        foreach ($values as $k => $v)
            if ($k == "submit")
                unset($values[$k]);
            elseif ($k !== $this->KeyColumn) {
                if ($v === '')
                    $values[$k] = null;
                $type = getValid($this->CellsTypes, $k, "");
                if (is_string($type)) {
                    switch (strtolower($type)) {
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