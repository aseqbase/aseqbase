<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Contact;
use MiMFa\Library\Cryptograph;
use MiMFa\Library\SpecialCrypt;
module("Form");
module("QRCodeBox");
library("SpecialCrypt");
class PaymentForm extends Form{
	/**
     * Leave null for dynamic key
     * @var string
     */
	public $ValidationRequest = "Code";
	public $ValidationKey = null;
	/**
	 * Leave null for dynamic code
	 * @var string
	 */
	public $ValidationCode = null;
	public $ValidationSeconds = 240;
	public QRCodeBox|null $QRCodeBox = null;
	public $ExternalLink = true;
	public $Types = array();
	public Cryptograph $Cryptograph;
	public Transaction $Transaction;
	public $Contact = null;
	public $SubmitLabel = "Pay";
	public $Method = "POST";
	public $SuccessSubject = "Transaction";
	public $SuccessContent = "<h1>A Successful Transaction</h1>";
	public $BlockTimeout = 0;
	public $ResponseView = null;

	/**
	 * New payment form
     * @param array<Transaction> $types
	 */
	public function __construct(Transaction ...$types) {
        parent::__construct();
		$this->UseAjax = false;
		$this->Cryptograph = new SpecialCrypt();
		$this->QRCodeBox = new QRCodeBox();
		$this->QRCodeBox->Height = "30vmin";
        $this->SetTypes(...$types);
    }

	public function SetTypes(Transaction ...$types) {
		$this->Types = [];
		foreach ($types as $v) $this->Types[$v->DestinationPath] = $v;
		$this->Transaction = first($types)??new Transaction(path:\Req::Receive("destinationpath" , $this->Method, \Req::Receive("DestinationContent" , $this->Method, null)), content:\Req::Receive("DestinationContent" , $this->Method, null), unit:\Req::Receive("Unit" , $this->Method, null), network:\Req::Receive("Network" , $this->Method, null));

		$this->Transaction->Source = \Req::Receive("Source" , $this->Method, $this->Transaction->Source)??(is_null(\_::$Back->User)?null:\_::$Back->User->Name);
		$this->Transaction->SourceContent = \Req::Receive("SourceContent" , $this->Method, null);
		$this->Transaction->SourcePath = \Req::Receive("SourcePath" , $this->Method, null);
		$this->Transaction->SourceEmail = \Req::Receive("Email", $this->Method, (is_null(\_::$Back->User)?null:\_::$Back->User->Email));
		$this->Transaction->Others = $this->Contact = \Req::Receive("Contact", $this->Method, (is_null(\_::$Back->User)?null:\_::$Back->User->GetValue("Contact")));

		$this->Transaction->Unit = between($this->Transaction->Unit,\Req::Receive("Unit" , $this->Method, null));
		$this->Transaction->Value = between($this->Transaction->Value,\Req::Receive("Value" , $this->Method, null));
		$this->Transaction->Transaction = between($this->Transaction->Transaction,\Req::Receive("Transaction" , $this->Method, null));
		$this->Transaction->Identifier = between($this->Transaction->Identifier,\Req::Receive("Identifier" , $this->Method, null));
		$this->Transaction->Network = between($this->Transaction->Network,\Req::Receive("Network" , $this->Method, null));

		$this->Transaction->Destination = between($this->Transaction->Destination,\Req::Receive("Destination" , $this->Method, \_::$Info->FullOwner));
		$this->Transaction->DestinationContent = between($this->Transaction->DestinationContent,\Req::Receive("DestinationContent" , $this->Method, null));
		$this->Transaction->DestinationPath = between($this->Transaction->DestinationPath,\Req::Receive("destinationpath" , $this->Method, null));
		$this->Transaction->DestinationEmail = between($this->Transaction->DestinationEmail, \_::$Info->ReceiverEmail);

		$this->Title = $this->Transaction->Destination??"Details";
		if(!is_null($this->Transaction->Value))
			$this->Transaction->MaximumValue = $this->Transaction->MinimumValue = $this->Transaction->Value;
    }

	public function GetStyle() {
        return parent::GetStyle().Html::Style("
			.{$this->Name} .center{
				background-color: var(--back-color-1);
				padding: var(--size-5);
				margin: var(--size-0);
			}
			.{$this->Name} .center .path{
				margin: var(--size-0);
				overflow-wrap: break-word;
			}
			.{$this->Name} .center .path .link{
				font-size: var(--size-0);
			}
			.{$this->Name} .input{
				background-color: var(--back-color-0);
				margin: 0px var(--size-0);
			}
		");
    }
	public function GetFields() {
		$trans = $this->Transaction;
		$id = "_".getId();
        module("Field");
		$module = new Field();
        yield ($module->Set(
					type:"text",
					key:"SourceContent" ,
					title:"Source" ,
					value:$trans->SourceContent,
					required:!is_null($trans->SourceContent),
					lock:!is_null($trans->SourceContent)
				)
			)->ToString();
        yield (
			$module->Set(
					type:"float",
					key:"Value" ,
					value:$trans->Value??0,
					description:$trans->Unit,
					options:null,
					attributes:is_null($trans->Value)?[...(is_null($trans->MaximumValue)?[]:["max"=>$trans->MaximumValue]),...(is_null($trans->MinimumValue)?[]:["min"=>$trans->MinimumValue])]:["min"=>$trans->Value,"max"=>$trans->Value],
					required:true,
					lock:!is_null($trans->Value)
				)
			)->ToString();
		yield (
			$module->Set(
				type:"text",
				key:"Transaction" ,
				value:$trans->Transaction,
				description:$trans->Network,
				options:null,
				attributes:null,
				required:true,
				lock:!is_null($trans->Transaction)
			)
		)->ToString();
		yield Html::Break("More","document.getElementById('$id').style.display = document.getElementById('$id').computedStyleMap().get('display') == 'none'?'inherit':'none';");
        yield Html::Division(
			Html::Rack(
				Html::SmallSlot(
					$module->Set(
						type:"text",
						key:"Source" ,
						title:"From",
						value:$trans->Source,
						required:!is_null($trans->Source),
						lock:!is_null($trans->Source)
					)
					->ToString()).
				Html::SmallSlot(
					$module->Set(
						type:"text",
						key:"Destination" ,
						title:"To",
						value:$trans->Destination,
						required:false,
						lock:!is_null($trans->Destination)
					)
					->ToString())
				).
				$module->Set(
					type:"text",
					key:"Identifier" ,
					value:$trans->Identifier,
					required:!is_null($trans->Identifier),
					lock:!is_null($trans->Identifier)
				)
				->ToString().
				$module->Set(
					type:"Email",
					key:"Email",
					value:$trans->SourceEmail,
					required:!is_null($trans->SourceEmail),
					lock:!is_null($trans->SourceEmail)
				)
				->ToString().
				$module->Set(
					type:"text",
					key:"Contact",
					value:$this->Contact,
					required:!is_null($this->Contact),
					lock:!is_null($this->Contact)
				)
				->ToString().
				Html::HiddenInput(
					key:$this->ValidationRequest,
					value:$this->Cryptograph->Encrypt($this->ValidationCode??join("|",[randomString(),getClientIp(),randomString(),microtime(true),randomString()]), $this->ValidationKey, true),
					attributes:["Required"])
			,["Id" =>$id, "style"=>"display: none;"]);
		yield from parent::GetFields();
	}
	public function GetDescription($attrs = null) {
		if($this->ExternalLink && $this->QRCodeBox != null && isValid($this->Transaction->DestinationPath??$this->Transaction->DestinationContent)) {
			$this->QRCodeBox->Content = $this->Transaction->DestinationPath??$this->Transaction->DestinationContent;
			$content = $this->Transaction->DestinationContent??$this->Transaction->DestinationPath;
			return parent::GetDescription($attrs).Html::Center(
				$this->QRCodeBox->ToString().
				Html::Division(
					Html::Link($content, $this->Transaction->DestinationPath).
					Html::Panel(Html::Icon("copy", "copy('$content');").
					Html::Tooltip("Copy to clipboard"))
				,["class"=>"path" ])
			);
		}
		else return parent::GetDescription();
    }
	public function GetSuccess($msg, ...$attr){
		$doc = Html::Document(__($this->SuccessContent).$this->Transaction->ToHtml());
		$res = "";
		if(isValid($this->Transaction->DestinationEmail))
			if(Contact::SendHTMLEmail(\_::$Info->SenderEmail, $this->Transaction->DestinationEmail, __($this->SuccessSubject, styling:false)." - ".$this->Transaction->Id, $doc, $this->Transaction->SourceEmail,$this->Transaction->DestinationEmail == \_::$Info->ReceiverEmail?null:\_::$Info->ReceiverEmail))
                $res .= Html::Success("Your transaction received", $attr);
            else $res .= Html::Warning("We could not receive your transaction details, please notify us!", $attr);
        if(isValid($this->Transaction->SourceEmail))
			if(Contact::SendHTMLEmail(\_::$Info->SenderEmail, $this->Transaction->SourceEmail, __($this->SuccessSubject, styling:false)." - ".$this->Transaction->Id, $doc, $this->Transaction->DestinationEmail))
                $res .= Html::Success("A notification to '{$this->Transaction->SourceEmail}' has been sent!", $attr);
            else $res .= Html::Warning("Could not send a notification to '{$this->Transaction->SourceEmail}'!", $attr);
		if(table("Payment")->DoInsert(null, [
				"TId" =>$this->Transaction->Id,
				"Source" =>$this->Transaction->Source,
				"SourceContent" =>$this->Transaction->SourceContent,
				"SourcePath" =>$this->Transaction->SourcePath,
				"SourceEmail" =>$this->Transaction->SourceEmail,
				"Value" =>$this->Transaction->Value,
				"Unit" =>$this->Transaction->Unit,
				"Network" =>$this->Transaction->Network,
				"Transaction" =>$this->Transaction->Transaction,
				"Identifier" =>$this->Transaction->Identifier,
				"Destination" =>$this->Transaction->Destination,
				"DestinationContent" =>$this->Transaction->DestinationContent,
				"destinationpath" =>$this->Transaction->DestinationPath,
				"DestinationEmail" =>$this->Transaction->DestinationEmail,
				"Others" =>$this->Transaction->Others
			]))
			$res .= Html::Success("Your transaction recorded successfully", $attr);
        else $res .= Html::Error("We could not record your transaction details, please notify us!", $attr);
		return Html::Center(
			Html::Container(
				Html::Heading(Html::Bold(parent::GetSuccess($msg))).
				$this->Transaction->ToHtml().
				$res
				, ...$attr)
			);
    }

	public function Put(){
		return null;
	}

	public function File(){
		return null;
	}

	public function Patch(){
		return null;
	}

	public function Delete(){
		return null;
	}

	public function Handler($received = null) {
		if(($code = \Req::Receive($this->ValidationRequest, $this->Method)) && \Req::Receive("Value" , $this->Method, false) !== false)
			try{
				$code = $this->Cryptograph->Decrypt($code, $this->ValidationKey, true);
				if(is_null($this->ValidationCode)){
					$arr = preg_split("/\|/", $code);
					if(isEmpty($arr[1]))
						return self::GetError("Your connection is not valid!");
					if($arr[1] !== getClientIp())
						return self::GetError("Your connection is not secure!");
					$code = floatval($arr[3]);
					if(microtime(true)-$this->ValidationSeconds > $code)
						return self::GetError("Your time is out!");
					elseif(microtime(true) < $code)
						return self::GetError("A problem is occured!");
                }
                elseif($this->ValidationCode === $code)
					return self::GetError("Your request is manipulated!");

				//Process
                return self::GetSuccess("Transaction done successfully!");
			}catch(\Exception $ex){return self::GetError("Error in transaction!").Html::Error($ex);}
		return self::GetError("Fault in transaction!");
    }
}

class Transaction {
	public $Id = null;

	/**
     * The client|source name
     * @var string|null
     */
	public $Source = null;
	/**
     * The client|source email
     * @var string|null
     */
	public $SourceEmail = null;
	/**
     * Shown payment Source address
     * @var string|null
     */
	public $SourceContent = null;
	/**
     * Payment Source path
     * @var string|null
     */
	public $SourcePath = null;

	/**
     * The host|destination name
     * @var string|null
     */
	public $Destination = null;
	/**
     * The host|destination email
     * @var string|null
     */
	public $DestinationEmail = null;
	/**
     * Shown payment address
     * @var string|null
     */
	public $DestinationContent = null;
	/**
     * Payment path
     * @var string|null
     */
	public $DestinationPath = null;

	/**
     * The value of payment
     * @var float|null
     */
	public $Value = null;
	public $Rate = 1;
	public $MinimumValue = null;
	public $MaximumValue = null;
	/**
     * The payment Unit
     * @example "USDT"
     * @var string|null
     */
	public $Unit = null;
	/**
     * Selected network to transfer
     * @example "TRC-20"
     * @var string|null
     */
	public $Network = null;
	/**
     * Transaction reference
     * @var string|null
     */
	public $Transaction = null;
	/**
     * The transaction identifier
     * @var string|null
     */
	public $Identifier = null;

	public $Others = null;

	public function __construct($path = null, $value = null, $unit = null, $network = null, $identifier = null, $content = null, $source = null, $destination = null, $rate = 1, $transaction = null){
		$this->Id = randomString(5)."_".first(preg_split("/\./",microtime(true)));
		$this->DestinationPath = $path;
		$this->Value = $value;
		$this->Unit = $unit;
		$this->Network = $network;
		$this->Identifier = $identifier;
		$this->DestinationContent = $content;
		$this->Source = $source;
		$this->Destination = $destination;
		$this->Rate = $rate;
		$this->Transaction = $transaction;
    }

	public function ToHtml(){
        return Html::Heading(__("Traction Number", styling: false).": ".Html::Bold($this->Id)).
			Html::Table([
			[__("From",styling:false).":", "{$this->Source} {$this->SourceEmail} {$this->SourceContent}"],
			[__("To",styling:false).":", "{$this->Destination} {$this->DestinationEmail} {$this->DestinationContent}"],
			[__("Value" ,styling:false).":", $this->Value.$this->Unit],
			[__("Network" ,styling:false).":", $this->Network],
			[__("Transaction" ,styling:false).":", $this->Transaction],
			[__("Identifier" ,styling:false).":", $this->Identifier],
			[__("Others" ,styling:false).":", $this->Others]
		],[],[]);
    }
}
?>