<?php namespace MiMFa\Module;
class QRCodeBox extends Module{
	public $Capturable = true;
	public $ContentBox = null;
	public $AllowOriginal = false;
	public $ShowContent = false;
	public $Tag = null;
	public $Width = "100%";
	public $Height = "100%";

	public function Get(){
		$val = getValue($this->Content);
		MODULE("Image");
		$this->ContentBox = new Image();
		$this->ContentBox->Source = "https://api.qrserver.com/v1/create-qr-code/?data=".$val;
		$this->ContentBox->Style = new \MiMFa\Library\Style();
		$this->ContentBox->Style->Width = $this->Width;
		$this->ContentBox->Style->Height = $this->Height;
		$this->ContentBox->AllowOriginal = $this->AllowOriginal;
		return parent::GetTitle().parent::GetDescription().$this->ContentBox->Capture().
			($this->ShowContent? parent::GetContent():"");
    }
}
?>
