<?php
namespace MiMFa\Module;

use DateTime;
use MiMFa\Library\Html;
use MiMFa\Library\Convert;
use MiMFa\Library\Style;
use MiMFa\Library\Local;
use MiMFa\Library\DataTable;
module("Table");
/**
 * To show items in a profile view
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules/Profile See the Documentation
 */
class Profile extends Table{
	public $Image = null;
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
     * The database table key cell value, to get items automatically
     * @var null|string|int
     */
	public $KeyValue = null;
	/**
     * The database table key column name, to get items automatically
     * @var null|string
     */
	public $KeyColumn = "Id";
	/**
     * An array of column Keys which should show in the table
     * @var null|array<mixed>
     */
	public $ExcludeColumns = null;
	/**
     * An array of column Keys which should not show in the table
     * @var null|array<mixed>
     */
	public $IncludeColumns = null;

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

	public $MediaWidth = "var(--size-5)";
	public $MediaHeight = "var(--size-5)";
	public $AllowDecoration = true;
	public $TextWrap = false;

	public $SevereSecure = true;
	public $CryptPassword = true;

	public $OddEvenColumns = true;
	public $OddEvenRows = true;
	public $HoverableRows = true;
	public $HoverableCells = true;

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
	public $SelectQuery = null;
	public $SelectParameters = null;
	public $SelectCondition = null;

	public $AllowLabelTranslation = true;
	public $AllowDataTranslation = false;

	/**
     * Create the module
     * @param array|string|DataTable|null $itemsOrDataTable The module source items
     * or The name of source table
     * or The source table
     */
	public function __construct( $itemsOrDataTable =  null){
        parent::__construct($itemsOrDataTable);
    }
    
	public function GetStyle(){
		return Html::Style("
		.dataTables_wrapper :is(input, select, textarea) {
			backgroound-color: var(--back-color-input);
			color: var(--fore-color-input);
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
			".Style::DoProperty("text-wrap",($this->TextWrap===true?"pretty":($this->TextWrap===false?"nowrap":$this->TextWrap)))."
		}
		.{$this->Name} tr :is(td,th):has(.media:not(.icon)){
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
		}
		.{$this->Name} tr :is(td,th) .media:not(.icon){
			".Style::DoProperty("width",$this->MediaWidth)."
			".Style::DoProperty("height",$this->MediaHeight)."
			display: block;
		}
		.{$this->Name} .media.icon{
			cursor: pointer;
		}
		.{$this->Name} .media.icon:hover{
			border-color: transparent;
			".Style::UniversalProperty("filter","drop-shadow(var(--shadow-2))")."
		}
		.{$this->Name} .field {
			width: 100%;
		}
		.{$this->Name} .input {
			width: 100%;
		}
        table.dataTable.{$this->Name} tbody :is(td, tr) {
            text-align: -webkit-auto;
        }
        table.dataTable.{$this->Name} thead :is(th, tr) {
            text-align: center;
        }
        table.dataTable.{$this->Name} tbody tr :is(th, td) span.number {
            margin: calc(var(--size-0) / 2);
        }
		".($this->OddEvenColumns?"
            table.dataTable.{$this->Name} tbody tr:nth-child(even) :is(td, th):nth-child(odd) {
                background-color: #88888817;
            }
            table.dataTable.{$this->Name} tbody tr:nth-child(odd) :is(td, th):nth-child(odd) {
                background-color: #88888815;
            }
		":"").($this->OddEvenRows?"
            table.dataTable.{$this->Name} tbody tr:nth-child(odd) {
                background-color: #8881;
            }
		":"").($this->HoverableRows?"
            table.dataTable.{$this->Name} tbody tr:is(:nth-child(odd), :nth-child(even)):hover {
                background-color: #8882;
				".Style::UniversalProperty("transition", "var(--transition-1)")."
            }
		":"").($this->HoverableCells?"
            table.dataTable.{$this->Name} tbody tr:is(:nth-child(odd), :nth-child(even)) td:hover {
                background-color: transparent;
                outline: 1px solid var(--color-yellow);
                border-radius: var(--radius-1);
				".Style::UniversalProperty("transition", "var(--transition-1)")."
            }
            table.dataTable.{$this->Name} tbody tr:is(:nth-child(odd), :nth-child(even)) th:hover {
                background-color: transparent;
                outline: 1px solid var(--color-green);
                border-radius: var(--radius-1);
				".Style::UniversalProperty("transition", "var(--transition-1)")."
            }
            table.dataTable.{$this->Name} tfoot :is(th, td) {
                text-align: center;
            }
        ":""));
	}

	public function Get(){
		$isc = $this->Controlable;
		$isu = $isc && $this->Updatable && \_::$User->GetAccess($this->UpdateAccess);
		if(isValid($this->DataTable) && isValid($this->KeyColumn)){
		    if(isValid($this->KeyValue)) $this->Items = $this->DataTable->SelectRow(count($this->CellsTypes)>0?array_keys($this->CellsTypes):"*", [$this->ViewCondition, "`{$this->KeyColumn}`=:{$this->KeyColumn}"], [":{$this->KeyColumn}"=>$this->KeyValue]);
            else $this->Items = isValid($this->SelectQuery)?$this->DataTable->DataBase->SelectRow($this->SelectQuery, $this->SelectParameters, $this->Items):
                    $this->DataTable->SelectRow(
                    isEmpty($this->IncludeColumns)?"*":(in_array($this->KeyColumn, $this->IncludeColumns)?$this->IncludeColumns:[$this->KeyColumn, ...$this->IncludeColumns]),
                    [$this->SelectCondition, isEmpty($this->KeyValue)?null:"{$this->KeyColumn}=:__{$this->KeyColumn}"],
                    [...($this->SelectParameters?$this->SelectParameters:[]),...(isEmpty($this->KeyValue)?[]:[":__{$this->KeyColumn}"=>$this->KeyValue])], $this->Items
                );
        }
		$key = $this->KeyValue??get($this->Items,$this->KeyColumn);
		$hasid = is_countable($this->Items) && isValid($key);
        $vaccess = \_::$User->GetAccess($this->ViewAccess);
        $aaccess = $isu && !is_null($this->AddAccess) && \_::$User->GetAccess($this->AddAccess);
        $maccess = $isu && !is_null($this->ModifyAccess) && \_::$User->GetAccess($this->ModifyAccess);
        $raccess = $isu && !is_null($this->RemoveAccess) && \_::$User->GetAccess($this->RemoveAccess);
		$isc = $isc && ($vaccess || $aaccess || $maccess || $raccess);
        $secret = getReceived("secret")??$this->ViewSecret;
        $res = Html::Division(
            ($maccess? Html::Icon("edit","{$this->Name}_Modify(`$key`);", ["class"=>"table-item-modify"]) : "").
            ($raccess? Html::Icon("trash","{$this->Name}_Delete(`$key`);", ["class"=>"table-item-delete"]) : "")
        );
        if($secret === $this->ViewSecret)
            $res .= $this->GetViewFields($key);
        elseif($secret === $this->AddSecret)
            $res .= $this->GetAddFields($key);
        elseif($secret === $this->ModifySecret)
            $res .= $this->GetModifyFields($key);
        return $res;
	}

	public function GetScript(){
		return Html::Script("$(document).ready(()=>{".
        ($this->Controlable?("
				function {$this->Name}_View(key){
					sendPatchRequest(null, 'secret={$this->ViewSecret}&{$this->KeyColumn}='+key, `.{$this->Name}`);
				}".($this->Updatable?(\_::$User->GetAccess($this->AddAccess)?"
				function {$this->Name}_Create(){
					sendPatchRequest(null, 'secret={$this->AddSecret}&{$this->KeyColumn}=$this->AddSecret', `.{$this->Name}`);
				}":"").(\_::$User->GetAccess($this->ModifyAccess)?"
				function {$this->Name}_Modify(key){
					sendPatchRequest(null, 'secret={$this->ModifySecret}&{$this->KeyColumn}='+key, `.{$this->Name}`);
				}":"").(\_::$User->GetAccess($this->RemoveAccess)?"
				function {$this->Name}_Delete(key){
					".($this->SevereSecure?"if(confirm(`".__("Are you sure you want to remove this item?")."`))":"")."
						sendDeleteRequest(null, `secret={$this->RemoveSecret}&{$this->KeyColumn}=`+key, `.{$this->Name}`,
						(data, err)=>{
							load();
						});
				}":""):"")
			):"")
		);
    }

	public function Handler($received = null){
        $key = get($received, $this->KeyColumn);
        $secret = pop($received,"secret")??$this->ViewSecret;
        return $this->Router
        ->if($secret === $this->ModifySecret)
        ->Patch(function() use($key) { return $this->GetModifyFields($key); })
        ->else($secret === $this->RemoveSecret)
        ->Delete(function() use($key) { return $this->DoRemoveHandle($key); })
        ->else($key === $this->AddSecret)
        ->Post(function() use($key) { return $this->DoAddHandle($key); })
        ->else($key !== null)
        ->Put(function() use($key) { return $this->DoModifyHandle($key); })
        ->Handle();
    }

	public function GetViewFields($value){
        if(is_null($value)) return null;
        if(!\_::$User->GetAccess($this->ViewAccess)) return Html::Error("You have not access to see datails!");
        if(isEmpty($this->Items)) return Html::Error("You can not see this item!");
        $form = $this->GetForm();
        $form->Set(
            title:getBetween($this->Items,"Title","Name")??$this->Title,
            description:get($this->Items,"Description")??$this->Description,
            action:'#',
            method:"",
            children:(function(){
                foreach ($this->Items as $k=>$cell){
                    $type = getValid($this->CellsTypes, $k, "");
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
                    if($type !== false && !isEmpty($cell)) yield Html::Field(
                        type:(isEmpty($type)?null:Convert::By($type, $type, $cell, $k)),
                        key:$k,
                        value:$cell,
                        description:false,
                        attributes:["disabled"]
                    );
                }
            })());
        $form->Image = getValid($this->Items,"Image" ,$this->Image??"eye");
        $form->SubmitLabel = null;
        $form->ResetLabel = null;
        return $form->Handle();
    }

	public function GetAddFields($value){
        if(is_null($value)) return null;
        if(!\_::$User->GetAccess($this->AddAccess)) return Html::Error("You have not access to add!");
        module("Form");
        $record = [];
        if(count($this->CellsTypes)>0)
            foreach ($this->CellsTypes as $key=>$val)
                $record[$key] = null;
        $form = $this->GetForm();
        $form->Set(
            title:"Add {$this->Title}",
            description:$this->Description,
            children:(function() use($record, $value){
                $schemas = $this->DataTable->DataBase->Select(
                    "SELECT COLUMN_NAME, COLUMN_TYPE, DATA_TYPE, COLUMN_DEFAULT, IS_NULLABLE, EXTRA
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_NAME='{$this->DataTable->Name}'");
                if(count($record)==0) foreach ($schemas as $schema) $record[$schema["COLUMN_NAME"]] = null;
                foreach ($record as $key=>$val)
                    foreach ($schemas as $schema)
                        if($schema["COLUMN_NAME"] == $key)
                        {
                            $val = $key == $this->KeyColumn? $value:null;
                            $res = $this->PrepareDataToShow($key, $val, $record, $schema);
                            if(!isEmpty($res)) yield $res;
                        }
            })());
        $form->Image = getValid($record,"Image" ,$this->Image??"plus");
        $form->SuccessPath = \_::$Address->Url;
        return $form->Handle();
    }
	public function DoAddHandle($value){
        if(is_null($value)) return null;
        $vals = $this->NormalizeValues(receivePost());
        if(!\_::$User->GetAccess($this->AddAccess)) return Html::Error("You have not access to modify!");
        unset($vals[$this->KeyColumn]);
        foreach ($vals as $k=>$v)
            if(isEmpty($v)) unset($vals[$k]);
        if($this->DataTable->Insert($vals))
            return Html::Success("The '".__("information")."' added successfully!");
        return Html::Error("You can not add this item!");
    }

	public function GetModifyFields($value){
        if(is_null($value)) return null;
        if(!\_::$User->GetAccess($this->ModifyAccess)) return Html::Error("You have not access to modify!");
        module("Form");
        if(isEmpty($this->Items)) return Html::Error("You can not modify this item!");
        $form = $this->GetForm();
        $form->Set(
            title:getBetween($this->Items,"Title","Name")??$this->Title,
            description:get($this->Items,"Description")??$this->Description,
            children:(function() use($value){
                $schemas = $this->DataTable->DataBase->Select(
                    "SELECT COLUMN_NAME, COLUMN_TYPE, DATA_TYPE, COLUMN_DEFAULT, IS_NULLABLE, EXTRA
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_NAME='{$this->DataTable->Name}'");
                foreach ($this->Items as $key=>$val)
                    foreach ($schemas as $schema)
                        if($schema["COLUMN_NAME"] == $key)
                        {
                            $res = $this->PrepareDataToShow($key, $val, $this->Items, $schema);
                            if(!isEmpty($res)) yield $res;
                            break;
                        }
            })());
        $form->Image = getValid($this->Items,"Image" ,$this->Image??"edit");
        return $form->Handle();
    }
	public function DoModifyHandle($value){
        if(is_null($value)) return null;
        $vals = $this->NormalizeValues(receivePut());
        if(!\_::$User->GetAccess($this->ModifyAccess)) return Html::Error("You have not access to modify!");
        if($this->DataTable->Update([$this->ModifyCondition, "`{$this->KeyColumn}`=:{$this->KeyColumn}"], $vals))
            return Html::Success("The information updated successfully!");
        return Html::Error("You can not update this item!");
    }

	public function DoRemoveHandle($value){
        if(is_null($value)) return null;
        if(!\_::$User->GetAccess($this->RemoveAccess)) return Html::Error("You have not access to delete!");
        if($this->DataTable->Delete([$this->RemoveCondition, "`{$this->KeyColumn}`=:{$this->KeyColumn}"], [":{$this->KeyColumn}"=>$value]))
            return Html::Success("The 'items' removed successfully!");
        return Html::Error("You can not remove this item!");
    }

	public function PrepareDataToShow(&$key, &$value, &$record, $schema){
        $type = getValid($this->CellsTypes, $key, $schema["DATA_TYPE"]);
        $options = null;
        $def = $schema["COLUMN_DEFAULT"]??"";
        if(is_null($value))
            switch (strtolower($def))
            {
            	case "null":
            	case "current_timestamp":
            	case "current_timestamp()":
            	case "{current_timestamp()}":
                    $value = null;
                    break;
            	default:
                    $value = trim($def,"\"'`");
                    break;
            }
        if($key == $this->KeyColumn && str_contains($schema["EXTRA"] ,'auto_increment'))
            return Html::HiddenInput($key, $value);
        else {
            if(is_string($type))
                switch (strtolower($type))
                {
                    case "pass":
                    case "password":
                        if($this->CryptPassword) $value = \_::$User->DecryptPassword($value);
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
                        $value = null;
                        break;
                    case "type":
                    case "types":
                    case "enum":
                    case "enums":
                        $options = [];
                        foreach (preg_find_all('/(?<=(\'|\"))[^\'\"\,]+(?=\1)/', $schema["COLUMN_TYPE"]) as $key2=>$val2){
                            $options[$val2]=$val2;
                        }
                        break;
                }
            if($type !== false)
                return Html::Field(
                        type:isEmpty($type)?null:Convert::By($type, $type, $value, $key, $record),
                        key:$key,
                        value:$value,
                        options:$options,
                        attributes:$schema["IS_NULLABLE"]=="NO"&&is_null($schema["COLUMN_DEFAULT"])?["Required"]:[]
                    );
            return null;
        }
    }

	public function NormalizeValues($values){
        try{
            foreach (receiveFile()??[] as $k=>$v)
                if(Local::IsFileObject($v)){
                    $type = getValid($this->CellsTypes, $k, "");
                    if(is_string($type)) $type = \_::$Config->GetAcceptableFormats($type);
                    else $type = \_::$Config->GetAcceptableFormats();
                    $values[$k] = Local::GetUrl(Local::StoreFile($v, extensions:$type));
                }
                else unset($values[$k]);
        }catch(\Exception $ex){ return Html::Error($ex); }
        foreach ($values as $k=>$v)
            if($k !== $this->KeyColumn){
                if($v === '') $values[$k] = null;
                $type = getValid($this->CellsTypes, $k, "");
                if(is_string($type)){
                    switch(strtolower($type)){
                        case "pass":
                        case "password":
                            if(isEmpty($v)) unset($values[$k]);
                            elseif($this->CryptPassword) $values[$k] = \_::$User->EncryptPassword($v);
                            break;
                    }
                }
                elseif($type === false) unset($values[$k]);
            }
        return $values;
    }
}
?>