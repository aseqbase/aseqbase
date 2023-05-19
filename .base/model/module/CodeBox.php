<?php namespace MiMFa\Module;
class CodeBox extends Module{
	public $ContentTag = "textarea";
	public $ContentId = null;
	public $CopyButtonLabel = "Copy";
	public $PasteButtonLabel = "Paste";
	public $Columns = 5;
	public $Rows = 5;
	public $ControlBox = null;
	
	public function EchoStyle(){
		parent::EchoStyle();
		?>
		<style>
            .<?php echo $this->Name." ".$this->ContentTag; ?> {
				background-Color: var(--BackColor-4);
				Color: var(--ForeColor-4);
				border-radius: var(--Radius-1);
				border: var(--Border-1) var(--ForeColor-2);
                min-width: 50%;
				resize: both;
				overflow: scroll;
            }
		</style>
		<?php
	}
	public function Echo(){
		$id = $this->ContentId??($this->Name."_".rand(1,99999));
		$this->EchoTitle();
		$this->EchoDescription();
		$this->EchoContent("id='".$id."' rows='".$this->Rows."' cols='".$this->Columns."'");
		if(isValid($this->CopyButtonLabel) || isValid($this->PasteButtonLabel)){
			MODULE("Panel");
			$this->ControlBox = new Panel();
			$this->ControlBox->Content = function() use($id){
				if(isValid($this->CopyButtonLabel)) echo "<button class='btn' onclick='copyFrom(\"".$id."\");'>".__($this->CopyButtonLabel)."</button>";
				if(isValid($this->PasteButtonLabel)) echo "<button class='btn' onclick='pasteInto(\"".$id."\");'>".__($this->PasteButtonLabel)."</button>";
            };
        }
		if(isValid($this->ControlBox)) $this->ControlBox->Draw();
		return true;
    }
}
?>
