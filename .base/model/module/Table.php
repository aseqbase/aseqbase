<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
use MiMFa\Library\Convert;
use MiMFa\Library\Style;
use MiMFa\Library\Local;
/**
 * To show a table of items
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules/Table See the Documentation
 */
class Table extends Module{
	public $Tag = "table";
	public $Capturable = true;
	/**
     * The database table name, to get items automatically
     * @var null|string
     */
	public $Table = null;
	/**
     * An array of items, or a Key-Value based array of features
     * @var null|array<array<enum-string,mixed>>
     */
	public $Items = null;
	/**
     * The database table key column name, to get items automatically
     * @var null|string
     */
	public $ColumnKey = "ID";
	/**
     * An array of column Keys which should show in the table
     * @var null|array<mixed>
     */
	public $ExcludeColumnKeys = null;
	/**
     * An array of column Keys which should not show in the table
     * @var null|array<mixed>
     */
	public $IncludeColumnKeys = null;
	/**
     * To use the column keys as the column labels
     * @var null Auto detection
     * @var true To use
     * @var false To unuse
     */
	public $ColumnKeysAsLabels = true;
	/**
     * The row keys in data to use for column labels
     * @var array Auto detection
     */
	public $ColumnLabelsKeys = [0];
	/**
     * Add numbering to the table columns
	 * The first number of columns
	 * @var mixed
	 */
	public $StartColumnNumber = null;
	/**
     * The database table key row name or index, to get items automatically
     * @var null|string
     */
	public $RowKey = -1;
	/**
     * An array of row IDs or Indexes which should show in the table
     * @var null|array<mixed>
     */
	public $IncludeRowKeys = null;
	/**
     * An array of row IDs or Indexes which should not show in the table
     * @var null|array<mixed>
     */
	public $ExcludeRowKeys = null;
	/**
	 * To use the row keys as the row labels
     * @var null Auto detection
     * @var true To use
     * @var false To unuse
	 */
	public $RowKeysAsLabels = false;
	/**
     * The column keys in data to use for row labels
     * @var array Auto detection
     */
	public $RowLabelsKeys = [0];
	/**
     * Add numbering to the table rows, leave null to dont it
     * The first number of rows
	 * @var mixed
	 */
	public $StartRowNumber = null;
	/**
     * An array of all key=>type columns in data to use for each cell type
     * @var array Auto detection
     */
	public $CellTypes = [];
	/**
     * An array of all key=>value columns in data to use for each cell values
     * @var array Auto detection
     */
	public $CellValues = [];
	public $BorderSize = 1;
	public $DataCompression = 50;
	public $SevereSecure = true;
	public $CryptPassword = true;

	public $IsAction = false;
	public $Controlable = true;
	public $Updatable = false;
	public $UpdateAction = null;
	public $UpdateMethod = "post";
	public $UpdateEncType = "multipart/form-data";
	public $UpdateAccess = 0;
	public $ViewAccess = 0;
	public $ViewCondition = null;
	public $ModifyAccess = 0;
	public $ModifyCondition = null;
	public $RemoveAccess = 0;
	public $RemoveCondition = null;
	public $AddAccess = 0;
	public $AddCondition = null;
	public $SelectQuery = null;
	public $SelectParameters = null;
	public $SelectCondition = null;
	public $AllowLabelTranslation = true;
	public $AllowDataTranslation = false;
	public $TextWrap = false;
	public $MediaWidth = "var(--Size-5)";
	public $MediaHeight = "var(--Size-5)";
	public $Modal = null;
	public $HasDecoration = true;
	public $Options = ["deferRender: false", "select: true"];
	public $AllowCache= true;
	public $AllowPaging= true;
	public $AllowSearching= true;
	public $AllowOrdering=  true;
	public $AllowProcessing= true;
	public $AllowServerSide= false;
	public $AllowScrollX= true;
	public $AllowScrollY= true;
	public $AllowScrollCollapse= false;
	public $AllowAutoWidth= false;
	public $AllowAutoHeight= null;
	public $AllowFixedHeader= true;
	public $AllowFixedColumns= false;
	public $AllowFixedRows= true;
	public $AllowResponsive= true;
	public $AllowEntriesInfo= true;

	/**
     * Create the module
     * @param array|null $items The module source items
     */
	public function __construct($itemsOrTableName =  null){
        parent::__construct();
		$this->Set($itemsOrTableName);
    }
	/**
     * Set the main properties of module
     * @param array|null $items The module source items
     */
	public function Set($itemsOrTableName =  null){
		if(is_string($itemsOrTableName)) $this->Table = $itemsOrTableName;
		else $this->Items = $itemsOrTableName;
        $this->IsAction = RECEIVE(\_::$CONFIG->ViewHandlerKey, $this->UpdateMethod);
		return $this;
    }

	public function GetDefaultAttributes(){
		return parent::GetDefaultAttributes().$this->GetAttribute(" border",$this->BorderSize);
	}

	public function GetStyle(){
		return HTML::Style("
		.dataTables_wrapper :is(input, select, textarea) {
			backgroound-color: var(--BackColor-1);
			color: var(--ForeColor-1);
		}
		.{$this->Name} tr th{
			font-weight: bold;
		}
		.{$this->Name} tr :is(td,th){
			".Style::DoProperty("text-wrap",($this->TextWrap===true?"pretty":($this->TextWrap===false?"nowrap":$this->TextWrap)))."
		}
		.{$this->Name} tr :is(td,th) .media:not(.icon){
			".Style::DoProperty("width",$this->MediaWidth)."
			".Style::DoProperty("height",$this->MediaHeight)."
			display: inline-grid;
			align-items: center;
			text-align: center;
		}
		.{$this->Name} .media.icon{
			cursor: pointer;
		}
		.{$this->Name} .media.icon:hover{
			border-color: transparent;
			".Style::UniversalProperty("filter","drop-shadow(var(--Shadow-2))")."
		}
		.{$this->Name} .field {
			width: 100%;
		}
		.{$this->Name} .input {
			width: 100%;
		}
		");
	}

    public function CreateModal(){
        MODULE("Modal");
        $b = false;
        if($b = is_null($this->Modal)){
            $this->Modal = new Modal();
            $this->Modal->Name = "FormModal";
        }
        $this->Modal->AllowDownload =
        $this->Modal->AllowFocus =
        $this->Modal->AllowShare =
        $this->Modal->AllowZoom =
            false;
        $this->Modal->Style = new Style();
        $this->Modal->Style->TextAlign = "initial";
        $this->Modal->Style->BackgroundColor = "var(--BackColor-0)";
        $this->Modal->Style->Color = "var(--ForeColor-0)";
        return $b;
    }
    public function DrawModal($force = false){
        if(is_null($this->Modal)) $this->CreateModal();
        elseif(!$force) return false;
        if($force || !$this->IsAction) $this->Modal->Render();
        else return false;
        return true;
    }

	public function Capture(){
		if(
			isValid($this->Table)
			&& isValid($this->ColumnKey)
			&& $this->Controlable
		)
            if($this->CreateModal()){
                $res = $this->GetAction();
                if(!isEmpty($res)) return $res;
                return $this->Modal->Capture().parent::Capture();
            }
		return parent::Capture();
    }
	public function Get(){
		$isc = $this->Controlable;
		$isu = $isc && $this->Updatable && getAccess($this->UpdateAccess);
        if($isc){
            $this->CreateModal();
            $res = $this->GetAction();
            if($this->IsAction || !isEmpty($res)) return $res;
        }
		if(isValid($this->Table) && isValid($this->ColumnKey)){
            $this->Items = isValid($this->SelectQuery)?\MiMFa\Library\DataBase::TrySelect($this->SelectQuery, $this->SelectParameters, $this->Items):
				\MiMFa\Library\DataBase::DoSelect($this->Table,
					isEmpty($this->IncludeColumnKeys)?"*":(in_array($this->ColumnKey, $this->IncludeColumnKeys)?$this->IncludeColumnKeys:[$this->ColumnKey, ...$this->IncludeColumnKeys]),
					[$this->SelectCondition, isEmpty($this->IncludeRowKeys)?null:("{$this->ColumnKey} IN('".join("', '",$this->IncludeRowKeys)."')")],
					[], $this->Items
				);
        } else $isu = false;
		$hasid = is_countable($this->Items) && !is_null($hasid = array_key_first($this->Items)) && isValid($this->Items[$hasid],$this->ColumnKey);
		$clks = $this->ColumnLabelsKeys;
		$rlks = $this->RowLabelsKeys;
		$rkls = $this->RowKeysAsLabels;
		$ckls = $this->ColumnKeysAsLabels;
        $ckl = $this->RowKey < 0;
		$icks = $this->IncludeColumnKeys;
		$ecks = $this->ExcludeColumnKeys;
		$ick = !isEmpty($icks);
		$eck = !isEmpty($ecks);
		$irks = $hasid?[]:$this->IncludeRowKeys;
		$erks = $hasid?[]:$this->ExcludeRowKeys;
		$irids = $hasid?$this->IncludeRowKeys:[];
		$erids = $hasid?$this->ExcludeRowKeys:[];
		$irk = !isEmpty($irks);
		$erk = !isEmpty($erks);
		$hasid = $hasid && isValid($this->ColumnKey) && (!isEmpty($irids) || !isEmpty($erids));
		$srn = $this->StartRowNumber??1;
		$hrn = !is_null($this->StartRowNumber);
		$scn = $this->StartColumnNumber;
		$hcn = !is_null($scn);
		$uck = "";
		if($isu){
			$uck = HTML::Division(getAccess($this->AddAccess)? HTML::Icon("plus","{$this->Modal->Name}_Create();") : HTML::Image("tasks"));
			if($ick) array_unshift($icks, $uck);
        }
		$strow = "<tr>";
		$etrow = "</tr>";
		if(is_countable($this->Items) && count($this->Items) > 0){
            $cells = [];
            foreach ($this->Items as $rkey=>$row){
				$rowid = getValid($row, $this->ColumnKey, null);
                if(
					(!$irk || in_array($rkey, $irks)) &&
					(!$erk || !in_array($rkey, $erks)) &&
					(!$hasid || (in_array($rowid, $irids) || !in_array($rowid, $erids)))
				){
                    $isrk = ($rkey === $this->RowKey) || in_array($rkey,$clks) || ($hasid && $rowid === $rkey);
                    if($rkls) array_unshift($row,is_integer($rkey)?($hrn?$rkey+$srn:""):$rkey);
					if($isc){
                        $row = is_null($rowid)?[$uck=>"",...$row]:[
							$uck=>HTML::Division([
									...(getAccess($this->ViewAccess)? [HTML::Icon("eye","{$this->Modal->Name}_View(`$rowid`);")] : []),
									...($isu && getAccess($this->ModifyAccess)? [HTML::Icon("edit","{$this->Modal->Name}_Modify(`$rowid`);")] : []),
									...($isu &&getAccess($this->RemoveAccess)? [HTML::Icon("trash","{$this->Modal->Name}_Delete(`$rowid`);")] : [])
								]),
							...$row
							];
                    }
					if($ckls && ($isrk || $ckl)){
                        $ckl  = false;
                        $cells[] = "<thead><tr>";
                        if($ick){
                            foreach($icks as $ckey)
                                if(!$eck || !in_array($ckey, $ecks))
								    $cells[] = $this->GetCell(is_integer($ckey)?($hcn?$ckey+$scn:""):$ckey, $ckey, true, $row);
                        }
                        else{
                            foreach($row as $ckey=>$cel)
                                if(!$eck || !in_array($ckey, $ecks))
								    $cells[] = $this->GetCell(is_integer($ckey)?($hcn?$ckey+$scn:""):$ckey, $ckey, true, $row);
                        }
                        $cells[] = "</tr></thead>";
						$isrk = false;
                    }
                    $cells[] = $strow;
                    if($ick){
                        foreach($icks as $ckey)
                            if(!$eck || !in_array($ckey, $ecks)){
                                $cel = isset($row[$ckey])? $row[$ckey]:null;
                                if($isrk) $cells[] = $this->GetCell(is_integer($rkey)?($hrn?$rkey+$srn:""):$cel, $ckey, true, $row);
							    elseif(in_array($ckey, $rlks)) $cells[] = $this->GetCell(is_integer($ckey)?($hcn?$ckey+$scn:""):$cel, $ckey, true, $row);
                                else $cells[] = $this->GetCell($cel, $ckey, false, $row);
                            }
                    }
                    else{
                        foreach($row as $ckey=>$cel)
                            if(!$eck || !in_array($ckey, $ecks)){
                                if($isrk) $cells[] = $this->GetCell(is_integer($rkey)?($hrn?$rkey+$srn:""):$cel, $ckey, true, $row);
                                elseif(in_array($ckey, $rlks)) $cells[] = $this->GetCell(is_integer($ckey)?($hcn?$ckey+$scn:""):$cel, $ckey, true, $row);
                                else $cells[] = $this->GetCell($cel, $ckey, false, $row);
                            }
                    }
                    $cells[] = $etrow;
                }
            }
            return join(PHP_EOL, $cells);
        }
		elseif($isu && getAccess($this->AddAccess))
			return HTML::Center(HTML::Button("Add your first item ".HTML::Image("plus"),"{$this->Modal->Name}_Create();"));
		return parent::Get();
	}

	public function GetScript(){
        $updateMethod = strtolower($this->UpdateMethod);
		return HTML::Script("$(document).ready(()=>{".
			(!$this->HasDecoration?"":
				"$('.{$this->Name}').DataTable({".
					join(", ",[
						...(is_null($this->AllowCache)?[]:["stateSave: ".($this->AllowCache?"true":"false")]),
						...(is_null($this->AllowPaging)?[]:["paging: ".($this->AllowPaging?"true":"false")]),
						...(is_null($this->AllowSearching)?[]:["searching: ".($this->AllowSearching?"true":"false")]),
						...(is_null($this->AllowOrdering)?[]:["ordering: ".($this->AllowOrdering?"true":"false")]),
						...(is_null($this->AllowProcessing)?[]:["processing: ".($this->AllowProcessing?"true":"false")]),
						...(is_null($this->AllowServerSide)?[]:["serverSide: ".($this->AllowServerSide?"true":"false")]),
						...(is_null($this->AllowScrollX)?[]:["scrollX: ".($this->AllowScrollX?"true":"false")]),
						...(is_null($this->AllowScrollY)?[]:["scrollY: ".($this->AllowScrollY?"true":"false")]),
						...(is_null($this->AllowScrollCollapse)?[]:["scrollCollapse: ".($this->AllowScrollCollapse?"true":"false")]),
						...(is_null($this->AllowAutoWidth)?[]:["autoWidth: ".($this->AllowAutoWidth?"true":"false")]),
						...(is_null($this->AllowAutoHeight)?[]:["autoHeight: ".($this->AllowAutoHeight?"true":"false")]),
						...(is_null($this->AllowFixedHeader)?[]:["fixedHeader: ".($this->AllowFixedHeader?"true":"false")]),
						...(is_null($this->AllowFixedColumns)?[]:["fixedColumns: ".($this->AllowFixedColumns?"true":"false")]),
						...(is_null($this->AllowFixedRows)?[]:["fixedRows: ".($this->AllowFixedRows?"true":"false")]),
						...(is_null($this->AllowResponsive)?[]:["responsive: ".($this->AllowResponsive?"true":"false")]),
						...(is_null($this->AllowEntriesInfo)?[]:["info: ".($this->AllowEntriesInfo?"true":"false")]),
                        ...["language: {".
                                "decimal: \"".__("", styling:false)."\",".
                                "emptyTable: \"".__("No items available", styling:false)."\",".
                                "info: \"".__("Showing _START_ to _END_ of _TOTAL_ entries", styling:false)."\",".
                                "infoEmpty: \"".__("", styling:false)."\",".
                                "infoFiltered: \"".__("(filtered from _MAX_ total entries)", styling:false)."\",".
                                "infoPostFix: \"".__("", styling:false)."\",".
                                "thousands: \"".__(",", styling:false)."\",".
                                "lengthMenu: \"".__("Display _MENU_ items per page", styling:false)."\",".
                                "loadingRecords: \"".__("Loading...", styling:false)."\",".
                                "processing: \"".__("", styling:false)."\",".
                                "search: \"".__("Search: ", styling:false)."\",".
                                "zeroRecords: \"".__("No matching items found", styling:false)."\",".
                                "paginate: {".
                                    "first: \"".__("First", styling:false)."\",".
                                    "last: \"".__("Last", styling:false)."\",".
                                    "next: \"".__("Next", styling:false)."\",".
                                    "previous: \"".__("Previous", styling:false)."\"".
                                "},".
                                "aria: {".
                                    "sortAscending: \"".__(": activate to sort column ascending", styling:false)."\",".
                                    "sortDescending: \"".__(": activate to sort column descending", styling:false)."\"".
                                "}".
                            "}"
                        ],
						...($this->Controlable?["'columnDefs': [{ 'targets': 0, 'orderable': false }]"]:[]),
						...(isEmpty($this->Options)?[]:(is_array($this->Options)?$this->Options:[Convert::ToString($this->Options)]))
					]).
				"});
			});").($this->Controlable?
			(is_null($this->Modal)?"":("
				function {$this->Modal->Name}_View(key){
					{$updateMethod}Data(null, '".\_::$CONFIG->ViewHandlerKey."=value&action=view&{$this->ColumnKey}='+key, `.{$this->Name}`,
						(data,selector)=>{
							{$this->Modal->ShowScript("null","null","data")}
						}
					);
				}".($this->Updatable?(getAccess($this->AddAccess)?"
				function {$this->Modal->Name}_Create(){
					{$updateMethod}Data(null, '".\_::$CONFIG->ViewHandlerKey."=value&action=create&{$this->ColumnKey}=_table_add', `.{$this->Name}`,
						(data,selector)=>{
							{$this->Modal->ShowScript("null","null","data")}
						}
					);
				}":"").(getAccess($this->ModifyAccess)?"
				function {$this->Modal->Name}_Modify(key){
					{$updateMethod}Data(null, '".\_::$CONFIG->ViewHandlerKey."=value&action=modify&{$this->ColumnKey}='+key, `.{$this->Name}`,
						(data,selector)=>{
							{$this->Modal->ShowScript("null","null","data")}
						}
					);
				}":"").(getAccess($this->RemoveAccess)?"
				function {$this->Modal->Name}_Delete(key){
					".($this->SevereSecure?"if(confirm(`".__("Are you sure you want to remove this item?", styling:false)."`))":"")."
						{$updateMethod}Data(null, `".\_::$CONFIG->ViewHandlerKey."=value&action=delete&{$this->ColumnKey}=`+key, `.{$this->Name}`,
						(data,selector)=>{
							load();
						});
				}":""):"")
			)):"")
		);
    }

	public function GetCell($cel, $key, $isHead = false, $row = []){
        if(!$isHead || $cel !== $key) $cel = Convert::ToString(Convert::By(getValid($this->CellValues, $key), $cel, $key, $row)??$cel);
		if($isHead){
            $cel = Convert::ToString($cel);
			if(isFile($cel)) return "<th>".HTML::Media($cel)."</th>";
			else return "<th>".__($cel, translation:$this->AllowLabelTranslation, styling:false)."</th>";
        }
		//if($this->Updatable && !$isHead && $key > 1){
        //    $cel = new Field(key:$key, value: $cel, lock: true, type:getValid($this->CellTypes,$key, null));
        //    $cel->MinWidth = $this->MediaWidth;
        //    $cel->MaxHeight = $this->MediaHeight;
        //    return "<td>".Convert::ToString($cel)."</td>";
        //}
        if(isFile($cel)) return "<td>".HTML::Media($cel)."</td>";
		$cel = __($cel, translation:$this->AllowDataTranslation, styling:false);
        if(!$this->TextWrap && !startsWith($cel,"<")) return "<td>".Convert::ToExcerpt($cel, 0, $this->DataCompression, "...".HTML::Tooltip($cel))."</td>";
        return "<td>$cel</td>";
    }

	public function Action(){
		echo $this->GetAction();
    }
	public function GetAction(){
        if($this->IsAction = (GRAB(\_::$CONFIG->ViewHandlerKey, $this->UpdateMethod) || $this->IsAction))
		    return $this->DoAction(RECEIVE($this->ColumnKey, $this->UpdateMethod), GRAB("action", $this->UpdateMethod));
        else return null;
    }
	public function DoAction($value, $action = "view"){
        switch ($action) {
            case "view":
                return $this->ShowViewForm($value);
            case "modify":
                return $this->ShowModifyForm($value);
            case "create":
                return $this->ShowAddForm($value);
            case "delete":
                return $this->DoRemoveAction($value);
            default:
                switch ($value) {
                    case "_table_add":
                        return $this->DoAddAction($value);
                    default:
                        return $this->DoModifyAction($value);
                }
        }
    }

	public function ShowViewForm($value){
        if(is_null($value)) return null;
        if(!getAccess($this->ViewAccess)) return HTML::Error("You have not access to see datails!");
        MODULE("Form");
        $row = \MiMFa\Library\DataBase::DoSelect($this->Table,"*", [$this->ViewCondition, "`{$this->ColumnKey}`=:{$this->ColumnKey}"], [":{$this->ColumnKey}"=>$value]);
        if(count($row) > 0) $row = $row[0];
        else return HTML::Error("You can not see this item!");
        $form = new Form(
            title:getValid($row,"Title",null)??getValid($row,"Name",null),
            description:getValid($row,"Description",null),
            action:'#',
            method:"",
            children:(function() use($row){
                yield HTML::HiddenInput(\_::$CONFIG->ViewHandlerKey, "value");
                foreach ($row as $k=>$cell){
                    if($k != $this->ColumnKey) {
                        $type = getValid($this->CellTypes, $k, "");
                        if(is_string($type)){
                            $type = strtolower($type);
                            switch($type){
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
                        if($type !== false && !isEmpty($cell)) yield HTML::Field(
                            type:(isEmpty($type)?null:Convert::By($type, $type, $cell, $k, $row)),
                            key:$k,
                            value:$cell,
                            description:false,
                            attributes:["disabled"]
                        );
                    }
                }
            })());
        $form->Image = getValid($row,"Image","eye");
        $form->Template = "b";
        $form->SubmitLabel = null;
        $form->ResetLabel = null;
        if($this->Modal) {
            $form->CancelLabel = "Cancel";
            $form->CancelPath = $this->Modal->HideScript();
        } else $form->CancelLabel = null;
        $form->SuccessPath = \_::$URL;
        $form->BackPath = \_::$URL;
        $form->BackLabel = null;
        //$form->AllowHeader = false;
        return $form->Capture();
    }

	public function ShowModifyForm($value){
        if(is_null($value)) return null;
        if(!getAccess($this->ModifyAccess)) return HTML::Error("You have not access to modify!");
        MODULE("Form");
        $row = \MiMFa\Library\DataBase::DoSelect($this->Table,"*", [$this->ModifyCondition, "`{$this->ColumnKey}`=:{$this->ColumnKey}"], [":{$this->ColumnKey}"=>$value]);
        if(count($row) > 0) $row = $row[0];
        else return HTML::Error("You can not modify this item!");
        $form = new Form(
            title:getValid($row,"Title",null)??getValid($row,"Name",null),
            description:getValid($row,"Description",null),
            action:$this->UpdateAction,
            method:$this->UpdateMethod,
            children:(function() use($row){
                yield HTML::HiddenInput(\_::$CONFIG->ViewHandlerKey, "value");
                foreach ($row as $k=>$cell){
                    if($k == $this->ColumnKey) yield HTML::HiddenInput($k, $cell);
                    else {
                        $type = getValid($this->CellTypes, $k, "");
                        if(is_string($type)){
                            $type = strtolower($type);
                            switch($type){
                                case "pass":
                                case "password":
                                    if($this->CryptPassword) $cell = \_::$INFO->User->DecryptPassword($cell);
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
                                    $cell = null;
                                    break;
                            }
                        }
                        if($type !== false) yield HTML::Field(
                            type:(isEmpty($type)?null:Convert::By($type, $type, $cell, $k, $row)),
                            key:$k,
                            value:$cell
                        );
                    }
                }
            })());
        $form->Image = getValid($row,"Image","edit");
        $form->Template = "b";
        $form->CancelLabel = "Cancel";
        if($this->Modal){
            $form->CancelPath = $this->Modal->HideScript();
            $form->CancelLabel = "Cancel";
        } else $form->CancelLabel = null;
        $form->SuccessPath = \_::$URL;
        $form->BackPath = \_::$URL;
        $form->BackLabel = null;
        //$form->AllowHeader = false;
        return $form->Capture();
    }
	public function DoModifyAction($value){
        if(is_null($value)) return null;
        $vals = $this->GetFormValues();
        if(!getAccess($this->ModifyAccess)) return HTML::Error("You have not access to modify!");
        if(\MiMFa\Library\DataBase::DoUpdate($this->Table, [$this->ModifyCondition, "`{$this->ColumnKey}`=:{$this->ColumnKey}"], $vals))
            return HTML::Success("The information updated successfully!");
        return HTML::Error("You can not update this item!");
    }

	public function ShowAddForm($value){
        if(is_null($value)) return null;
        if(!getAccess($this->AddAccess)) return HTML::Error("You have not access to add!");
        MODULE("Form");
        $row = \MiMFa\Library\DataBase::TrySelect("SELECT COLUMN_NAME, DATA_TYPE, COLUMN_DEFAULT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='{$this->Table}'");
        $form = new Form(
            title:"Add {$this->Title}",
            description:getValid($row,"Description",null),
            action:$this->UpdateAction,
            method:$this->UpdateMethod,
            children:(function() use($row, $value){
                yield HTML::HiddenInput(\_::$CONFIG->ViewHandlerKey, "value");
                foreach ($row as $val){
                    $k = $val["COLUMN_NAME"];
                    if($k == $this->ColumnKey) yield HTML::HiddenInput($k, $value);
                    else {
                        $type = getValid($this->CellTypes, $k, $val["DATA_TYPE"]);
                        if($type !== false) yield HTML::Field(type:isEmpty($type)?null:Convert::By($type, $type, $val["COLUMN_DEFAULT"], $k, $row), key:$k);
                    }
                }
            })());
        $form->Image = getValid($row,"Image","plus");
        $form->Template = "b";
        $form->CancelLabel = "Cancel";
        if($this->Modal){
            $form->CancelPath = $this->Modal->HideScript();
            $form->CancelLabel = "Cancel";
        } else $form->CancelLabel = null;
        $form->SuccessPath = \_::$URL;
        $form->BackPath = \_::$URL;
        $form->BackLabel = null;
        //$form->AllowHeader = false;
        return $form->Capture();
    }
	public function DoAddAction($value){
        if(is_null($value)) return null;
        $vals = $this->GetFormValues();
        if(!getAccess($this->AddAccess)) return HTML::Error("You have not access to modify!");
        unset($vals[$this->ColumnKey]);
        foreach ($vals as $k=>$v)
            if(isEmpty($v)) unset($vals[$k]);
        if(\MiMFa\Library\DataBase::DoInsert($this->Table, $this->AddCondition, $vals))
            return HTML::Success("The information added successfully!");
        return HTML::Error("You can not add this item!");
    }

	public function DoRemoveAction($value){
        if(is_null($value)) return null;
        if(!getAccess($this->RemoveAccess)) return HTML::Error("You have not access to delete!");
        if(\MiMFa\Library\DataBase::DoDelete($this->Table, [$this->ModifyCondition, "`{$this->ColumnKey}`=:{$this->ColumnKey}"], [":{$this->ColumnKey}"=>$value]))
            return HTML::Success("The items removed successfully!");
        return HTML::Error("You can not remove this item!");
    }

	public function GetFormValues(){
        $vals = RECEIVE(null, $this->UpdateMethod);
        try{
            foreach (RECEIVE(null, $_FILES)??[] as $k=>$v)
                if(Local::IsFileObject($_FILES[$k])){
                    $type = getValid($this->CellTypes, $k, "");
                    if(is_string($type)) $type = \_::$CONFIG->GetAcceptableFormats($type);
                    else $type = \_::$CONFIG->GetAcceptableFormats();
                    $vals[$k] = Local::UploadFile($_FILES[$k], extensions:$type);
                }
                else unset($vals[$k]);
        }catch(\Exception $ex){ return HTML::Error($ex); }
        foreach ($vals as $k=>$v){
            $type = getValid($this->CellTypes, $k, "");
            if(is_string($type)){
                switch(strtolower($type)){
                    case "pass":
                    case "password":
                        if(isEmpty($v)) unset($vals[$k]);
                        elseif($this->CryptPassword) $vals[$k] = \_::$INFO->User->EncryptPassword($v);
                        break;
                }
            }
            else if($type === false) unset($vals[$k]);
        }
        return $vals;
    }
}
?>