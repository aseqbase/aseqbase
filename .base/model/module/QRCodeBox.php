<?php namespace MiMFa\Module;
class QRCodeBox extends Module{
	public $ContentBox = null;
	public $AllowOriginal = false;
	public $ShowContent = false;
	public $Tag = null;
	
	public function Echo(){
		parent::EchoTitle();
		parent::EchoDescription();
		$val = getValue($this->Content);
		MODULE("Image");
		$this->ContentBox = new Image();
		$this->ContentBox->Source = "https://api.qrserver.com/v1/create-qr-code/?data=".$val;
		$this->ContentBox->Style = new \MiMFa\Library\Style();
		$this->ContentBox->Style->Width = "100%";
		$this->ContentBox->Style->Height = "100%";
		$this->ContentBox->AllowOriginal = $this->AllowOriginal;
		$this->ContentBox->Draw();
		if($this->ShowContent) parent::EchoDescription();
		return true;
    }
}
?>
