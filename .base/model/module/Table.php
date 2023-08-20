<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
use MiMFa\Library\Convert;
use MiMFa\Library\Style;
/**
 * To show a table of items
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules/Table See the Documentation
 */
class Table extends Module{
	public $Capturable = true;
	/**
     * An array of items, or a Key-Value based array of features
     * @var null|array<array<enum-string,mixed>>
     */
	public $Items = null;
	/**
     * An array of column Keys that not show in the table
     * @var null|array<mixed>
     */
	public $ExcludeColumnKeys = null;
	/**
     * An array of column Keys that not show in the table
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
	public $StartColumnNumber = false;
	/**
     * An array of row Keys that not show in the table
     * @var null|array<mixed>
     */
	public $ExcludeRowKeys = null;
	/**
     * An array of row Keys that not show in the table
     * @var null|array<mixed>
     */
	public $IncludeRowKeys = null;
	/**
	 * To use the row keys as the row labels
     * @var null Auto detection
     * @var true To use
     * @var false To unuse
	 */
	public $RowKeysAsLabels = true;
	/**
     * The column keys in data to use for row labels
     * @var array Auto detection
     */
	public $RowLabelsKeys = [0];
	/**
     * Add numbering to the table rows
     * The first number of rows
	 * @var mixed
	 */
	public $StartRowNumber = 1;
	public $BorderSize = 1;
	public $DataCompression = 50;
	public $Changeable = false;
	public $ChangePath = null;
	public $AllowOptions = true;
	public $Options = "{
					paging: true,
					searching: true,
					ordering:  true,
					select: true,
					scrollX: true,
					scrollY: true,
					scrollCollapse: true,
					autoWidth: false,
					fixedHeader: true,
					responsive: true
	}";
	public $TextWrap = false;
	public $MediaWidth = "50px";
	public $MediaHeight = "50px";
	public $Tag = "table";

	/**
     * Create the module
     * @param array|null $items The module source items
     */
	public function __construct($items =  null){
        parent::__construct();
		$this->Set($items);
    }
	/**
     * Set the main properties of module
     * @param array|null $items The module source items
     */
	public function Set($items =  null){
		$this->Items = $items;
		return $this;
    }

	public function GetDefaultAttributes(){
		return parent::GetDefaultAttributes().$this->GetAttribute(" border",$this->BorderSize);
	}

	public function GetStyle(){
		return HTML::Style("
		.{$this->Name} tr th{
			font-weight: bold;
		}
		.{$this->Name} tr td{
			".Style::DoProperty("text-wrap",($this->TextWrap===true?"pretty":($this->TextWrap===false?"nowrap":$this->TextWrap)))."
		}
		.{$this->Name} tr td .media{
			".Style::DoProperty("width",$this->MediaWidth)."
			".Style::DoProperty("height",$this->MediaHeight)."
		}
		");
	}

	public function Get(){
		if($this->Changeable) MODULE("Field");
		$clks = $this->ColumnLabelsKeys;
		$rlks = $this->RowLabelsKeys;
		$rkls = $this->RowKeysAsLabels;
		$ckls = $this->ColumnKeysAsLabels;
		$icks = $this->IncludeColumnKeys;
		$ecks = $this->ExcludeColumnKeys;
		$ick = !is_null($icks);
		$eck = !is_null($ecks);
		$irks = $this->IncludeRowKeys;
		$erks = $this->ExcludeRowKeys;
		$irk = !is_null($irks);
		$erk = !is_null($erks);
		$srn = $this->StartRowNumber;
		$hrn = !is_null($srn);
		$scn = $this->StartColumnNumber;
		$hcn = !is_null($scn);
		if(!isEmpty($this->Items)){
            $cells = [];
            foreach ($this->Items as $rkey=>$row)
                if((!$irk || in_array($rkey, $irks)) && (!$erk || !in_array($rkey, $erks)))
                {
                    $isrk = in_array($rkey,$clks);
                    if($rkls) array_unshift($row,is_integer($rkey)?($hrn?$rkey+$srn:""):$rkey);
                    if($ckls && $isrk){
                        $cells[] = "<thead>";
                        $cells[] = "<tr>";
                        foreach($row as $ckey=>$cel)
                            if((!$ick || in_array($ckey, $icks)) && (!$eck || !in_array($ckey, $ecks)))
								$cells[] = $this->GetCell(is_integer($ckey)?($hcn?$ckey+$scn:""):$ckey, true);
                        $cells[] = "</tr>";
                        $cells[] = "</thead>";
						$isrk = false;
                    }
                    $cells[] = "<tr>";
                    foreach($row as $ckey=>$cel)
                        if((!$ick || in_array($ckey, $icks)) && (!$eck || !in_array($ckey, $ecks))){
                            if($isrk) $cells[] = $this->GetCell(is_integer($rkey)?($hrn?$rkey+$srn:""):$cel, true);
							elseif(in_array($ckey, $rlks)) $cells[] = $this->GetCell(is_integer($ckey)?($hcn?$ckey+$scn:""):$cel, true);
                            else $cells[] = $this->GetCell($cel, false);
                        }
                    $cells[] = "</tr>";
                }
            return join(PHP_EOL, $cells);
        }
		return parent::Get();
	}

	public function GetScript(){
		if($this->AllowOptions)
			return HTML::Script("$(document).ready(()=>{
				$('.{$this->Name}').DataTable(".Convert::ToString($this->Options).");
			})");
		return parent::GetScript();
    }

	public function GetCell($cel, $isHead = false){
		$cel = Convert::ToString($cel);
		if($this->Changeable && !$isHead){
			$cel = new Field(value: $cel);
			$cel->MinWidth = $this->MediaWidth;
			$cel->MaxHeight = $this->MediaHeight;
            return "<td>".Convert::ToString($cel)."</td>";
        }
        if(isFile($cel)){
            if($isHead) return "<th>".HTML::Media($cel)."</th>";
            else return "<td>".HTML::Media($cel)."</td>";
        }
        if($isHead) return "<th>".__($cel, translation:true, styling:false)."</th>";
		$cel = __($cel, translation:false, styling:false);
        if(!$this->TextWrap) return "<td>".Convert::ToExcerpt($cel, 0, $this->DataCompression, "...".HTML::Tooltip($cel))."</td>";
        return "<td>$cel</td>";
    }
}
?>