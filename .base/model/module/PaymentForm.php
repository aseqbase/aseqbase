<?php namespace MiMFa\Module;
      use MiMFa\Library\HTML;
MODULE("Form");
MODULE("QRCodeBox");
class PaymentForm extends Form{
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

	public function EchoStyle(){
		parent::EchoStyle();?>
		<style>
		</style>
		<?php
	}

	public function EchoFields(){
        MODULE("Field");
		$mod = new Field();
		($mod->Set("float","Value", $this->Value, $this->Unit." ".$this->Network, null, [...(is_null($this->MaximumValue)?[]:["maximum"=>$this->MaximumValue]),...(is_null($this->MinimumValue)?[]:["minimum"=>$this->MinimumValue])], true, !is_null($this->Value)))->Draw();
		($mod->Set("text","Transaction", $this->Transaction, null, null, null, true, !is_null($this->Transaction)))->ReDraw();
	}
	public function EchoContent($attr = null){
		parent::EchoContent();
		if($this->ExternalLink && $this->QRCodeBox != null && isValid($this->Path)){
			$this->QRCodeBox->Content = $this->Path;
			$this->QRCodeBox->Draw();
		}
    }
}
?>