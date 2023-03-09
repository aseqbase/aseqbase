<?php namespace MiMFa\Module;
class Table extends Module{
	public $Items = null;
	public $ColumnLabelsIndices = [0];
	public $RowLabelsIndices = [0];
	public $BorderSize = null;
	public $HasRowsNumber = false;
	public $StartRowNumber = 0;
	public $Tag = "table";

	public function GetDefaultAttributes(){
		return parent::GetDefaultAttributes().$this->GetAttribute(" border",$this->BorderSize);
	}

	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
		.<?php echo $this->Name; ?> tr th{
			font-weight: bold;
		}
		</style>
		<?php 
	}

	public function Echo(){
		parent::Echo();
		$count = count($this->Items);
		$cli = $this->ColumnLabelsIndices;
		$rli = $this->RowLabelsIndices;
		$hrn = $this->HasRowsNumber;
		$srn = $this->StartRowNumber;
		if($count > 0){
			for($i = 0; $i < $count; $i++){
				echo "<tr>";
				$row = $this->Items[$i];
				$celelem = in_array($i,$cli)?"th":"td";
				if($hrn) array_unshift($row,$srn++);
				$j = 0;
				foreach($row as $cel)
					if(in_array($j++,$rli)) echo "<th>".__($cel)."</th>";
					else echo "<$celelem>".__($cel)."</$celelem>";
				echo "</tr>";
			}
		}
	}
}
?>
