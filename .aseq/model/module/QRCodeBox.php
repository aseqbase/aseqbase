<?php namespace MiMFa\Module;
module("Image");
class QRCodeBox extends Image{
	public $AllowOrigin = true;
	public $ShowContent = false;
	public $Width = "100%";
	public $Height = "100%";
    public $Root = "https://api.qrserver.com/v1/create-qr-code/?data=";

    /**
     * Create the module
     * @param array|string|null $source The module source
     */
    public function __construct($content = null)
    {
        parent::__construct();
        $this->Content = $content;
    }

	public function Get(){
		$this->Source = $this->Convert($this->Content);
		return parent::Get().($this->ShowContent? $this->GetContent() : "");
    }
    
	public function Convert($val){
		return $this->Root.urlencode(\MiMFa\Library\Convert::ToString($val));
    }
}
?>
