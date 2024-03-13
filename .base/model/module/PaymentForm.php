<?php namespace MiMFa\Module;
      use MiMFa\Library\HTML;
MODULE("Form");
MODULE("QRCodeBox");
class PaymentForm extends Form{
	public $Capturable = true;
	public $ExternalLink = true;
	/**
	 * The value of payment
	 * @var float|null
     */
	public $Value = null;
	public $MinimumValue = null;
	public $MaximumValue = null;
	public $Unit = "USDT";
	public $Network = "TRC-20";
	/**
	 * Transaction reference
	 * @var mixed
	 */
	public $Transaction = null;
	public $SubmitLabel = "Pay";
	public $Method = "POST";
	public QRCodeBox|null $QRCodeBox = null;

	/**
     * Create the module
     * @param array|string|null $source The module source
     */
	public function __construct($source =  null){
        parent::__construct();
		$this->QRCodeBox = new QRCodeBox();
		$this->QRCodeBox->Height = "30vmin";
		$this->Set("Payment");
		$this->Path = $source;
    }

	public function GetFields(){
        MODULE("Field");
		$mod = new Field();
        yield (
			$mod->Set(
					type:"float",
					key:"Value",
					value:$this->Value,
					description:$this->Unit." ".$this->Network,
					options:null,
					attributes:[...(is_null($this->MaximumValue)?[]:["maximum"=>$this->MaximumValue]),...(is_null($this->MinimumValue)?[]:["minimum"=>$this->MinimumValue])],
					required:true,
					lock:!is_null($this->Value)
				)
			)->Capture();
		yield (
			$mod->Set(
				type:"text",
				key:"Transaction",
				value:$this->Transaction,
				description:null,
				options:null,
				attributes:null,
				required:true,
				lock:!is_null($this->Transaction)
			)
		)->ReCapture();
		yield from parent::GetFields();
	}
	public function GetContent($attr = null){
		if($this->ExternalLink && $this->QRCodeBox != null && isValid($this->Path)){
			$this->QRCodeBox->Content = $this->Path;
			return parent::GetContent().$this->QRCodeBox->Capture();
		}
		else return parent::GetContent();
    }
}
?>