<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
use MiMFa\Library\SpecialCrypt;
use MiMFa\Library\Contact;
use MiMFa\Library\DataBase;
MODULE("Form");
MODULE("QRCodeBox");
class PaymentForm extends Form{
	public $Capturable = true;
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
	public Transaction $Transaction;
	public $Contact = null;
	public $SubmitLabel = "Pay";
	public $Method = "POST";
	public $SuccessSubject = "Transaction";
	public $SuccessContent = "<h1>A Successful Transaction</h1>";

	/**
	 * New payment form
     * @param array<Transaction> $types
	 */
	public function __construct(Transaction ...$types) {
        parent::__construct();
		$this->UseAJAX = false;
		$this->QRCodeBox = new QRCodeBox();
		$this->QRCodeBox->Height = "30vmin";
        $this->SetTypes(...$types);
    }

	public function SetTypes(Transaction ...$types) {
		$this->Types = [];
		foreach ($types as $v) $this->Types[$v->DestinationPath] = $v;
		$this->Transaction = first($types)??new Transaction(path:RECEIVE("DestinationPath", $this->Method, RECEIVE("DestinationContent", $this->Method, null)), content:RECEIVE("DestinationContent", $this->Method, null), unit:RECEIVE("Unit", $this->Method, null), network:RECEIVE("Network", $this->Method, null));

		$this->Transaction->Source = RECEIVE("Source", $this->Method, $this->Transaction->Source)??(is_null(\_::$INFO->User)?null:\_::$INFO->User->Name);
		$this->Transaction->SourceContent = RECEIVE("SourceContent", $this->Method, null);
		$this->Transaction->SourcePath = RECEIVE("SourcePath", $this->Method, null);
		$this->Transaction->SourceEmail = RECEIVE("Email", $this->Method, (is_null(\_::$INFO->User)?null:\_::$INFO->User->Email));
		$this->Transaction->Others = $this->Contact = RECEIVE("Contact", $this->Method, (is_null(\_::$INFO->User)?null:\_::$INFO->User->GetValue("Contact")));

		$this->Transaction->Unit = between($this->Transaction->Unit,RECEIVE("Unit", $this->Method, null));
		$this->Transaction->Value = between($this->Transaction->Value,RECEIVE("Value", $this->Method, null));
		$this->Transaction->Transaction = between($this->Transaction->Transaction,RECEIVE("Transaction", $this->Method, null));
		$this->Transaction->Identifier = between($this->Transaction->Identifier,RECEIVE("Identifier", $this->Method, null));
		$this->Transaction->Network = between($this->Transaction->Network,RECEIVE("Network", $this->Method, null));

		$this->Transaction->Destination = between($this->Transaction->Destination,RECEIVE("Destination", $this->Method, \_::$INFO->FullOwner));
		$this->Transaction->DestinationContent = between($this->Transaction->DestinationContent,RECEIVE("DestinationContent", $this->Method, null));
		$this->Transaction->DestinationPath = between($this->Transaction->DestinationPath,RECEIVE("DestinationPath", $this->Method, null));
		$this->Transaction->DestinationEmail = between($this->Transaction->DestinationEmail, \_::$EMAIL);

		$this->Title = $this->Transaction->Destination??"Details";
		if(!is_null($this->Transaction->Value))
			$this->Transaction->MaximumValue = $this->Transaction->MinimumValue = $this->Transaction->Value;
    }

	public function GetStyle() {
        return parent::GetStyle().HTML::Style("
			.{$this->Name} .center{
				background-color: var(--BackColor-1);
				padding: var(--Size-5);
				margin: var(--Size-0);
			}
			.{$this->Name} .center .path{
				margin: var(--Size-0);
				overflow-wrap: break-word;
			}
			.{$this->Name} .center .path .link{
				font-size: var(--Size-0);
			}
			.{$this->Name} .input{
				background-color: var(--BackColor-0);
				margin: 0px var(--Size-0);
			}
		");
    }

	public function GetFields() {
		$trans = $this->Transaction;
		$id = "_".getId();
        MODULE("Field");
		$mod = new Field();
        yield ($mod->Set(
					type:"text",
					key:"SourceContent",
					title:"Source",
					value:$trans->SourceContent,
					required:!is_null($trans->SourceContent),
					lock:!is_null($trans->SourceContent)
				)
			)->Capture();
        yield (
			$mod->Set(
					type:"float",
					key:"Value",
					value:$trans->Value??0,
					description:$trans->Unit,
					options:null,
					attributes:is_null($trans->Value)?[...(is_null($trans->MaximumValue)?[]:["max"=>$trans->MaximumValue]),...(is_null($trans->MinimumValue)?[]:["min"=>$trans->MinimumValue])]:["min"=>$trans->Value,"max"=>$trans->Value],
					required:true,
					lock:!is_null($trans->Value)
				)
			)->ReCapture();
		yield (
			$mod->Set(
				type:"text",
				key:"Transaction",
				value:$trans->Transaction,
				description:$trans->Network,
				options:null,
				attributes:null,
				required:true,
				lock:!is_null($trans->Transaction)
			)
		)->ReCapture();
		yield HTML::Break("More","document.getElementById('$id').style.display = document.getElementById('$id').computedStyleMap().get('display') == 'none'?'inherit':'none';");
        yield HTML::Division(
			HTML::Rack(
				HTML::SmallSlot(
					$mod->Set(
						type:"text",
						key:"Source",
						title:"From",
						value:$trans->Source,
						required:!is_null($trans->Source),
						lock:!is_null($trans->Source)
					)
					->ReCapture()).
				HTML::SmallSlot(
					$mod->Set(
						type:"text",
						key:"Destination",
						title:"To",
						value:$trans->Destination,
						required:false,
						lock:!is_null($trans->Destination)
					)
					->ReCapture())
				).
				$mod->Set(
					type:"text",
					key:"Identifier",
					value:$trans->Identifier,
					required:!is_null($trans->Identifier),
					lock:!is_null($trans->Identifier)
				)
				->ReCapture().
				$mod->Set(
					type:"email",
					key:"Email",
					value:$trans->SourceEmail,
					required:!is_null($trans->SourceEmail),
					lock:!is_null($trans->SourceEmail)
				)
				->ReCapture().
				$mod->Set(
					type:"text",
					key:"Contact",
					value:$this->Contact,
					required:!is_null($this->Contact),
					lock:!is_null($this->Contact)
				)
				->ReCapture().
				HTML::HiddenInput(
					key:$this->ValidationRequest,
					value:SpecialCrypt::Encrypt($this->ValidationCode??join("|",[randomString(),getClientIP(),randomString(),microtime(true),randomString()]), $this->ValidationKey, true),
					attributes:["required"])
			,["id"=>$id, "style"=>"display: none;"]);
		yield from parent::GetFields();
	}

	public function GetDescription($attrs = null) {
		if($this->ExternalLink && $this->QRCodeBox != null && isValid($this->Transaction->DestinationPath??$this->Transaction->DestinationContent)) {
			$this->QRCodeBox->Content = $this->Transaction->DestinationPath??$this->Transaction->DestinationContent;
			$content = $this->Transaction->DestinationContent??$this->Transaction->DestinationPath;
			return parent::GetDescription($attrs).HTML::Center(
				$this->QRCodeBox->Capture().
				HTML::Division(
					HTML::Link($content, $this->Transaction->DestinationPath).
					HTML::Panel(HTML::Icon("copy", "copy('$content');").
					HTML::Tooltip("Copy to clipboard"))
				,["class"=>"path"])
			);
		}
		else return parent::GetDescription();
    }

	public function GetAction() {
		if($code = RECEIVE($this->ValidationRequest, $this->Method))
			try{
				$code = SpecialCrypt::Decrypt($code, $this->ValidationKey, true);
				if(is_null($this->ValidationCode)){
					$arr = preg_split("/\|/", $code);
					if(isEmpty($arr[1]))
						return self::GetError("Your connection is not valid!");
					if($arr[1] !== getClientIP())
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
			}catch(\Exception $ex){return self::GetError("Error in transaction!").HTML::Error($ex);}
		return self::GetError("Fault in transaction!");
    }

	public function GetSuccess($msg, ...$attr){
		$doc = HTML::Document(__($this->SuccessContent).$this->Transaction->ToHTML());
		$res = "";
		if(isValid($this->Transaction->DestinationEmail))
			if(Contact::SendHTMLEmail(\_::$EMAIL, $this->Transaction->DestinationEmail, __($this->SuccessSubject, styling:false)." - ".$this->Transaction->ID, $doc, $this->Transaction->SourceEmail,$this->Transaction->DestinationEmail == \_::$EMAIL?null:\_::$EMAIL))
                $res .= HTML::Success("Your transaction received", $attr);
            else $res .= HTML::Warning("We could not receive your transaction details, please notify us!", $attr);
        if(isValid($this->Transaction->SourceEmail))
			if(Contact::SendHTMLEmail(\_::$EMAIL, $this->Transaction->SourceEmail, __($this->SuccessSubject, styling:false)." - ".$this->Transaction->ID, $doc, $this->Transaction->DestinationEmail))
                $res .= HTML::Success("A notification to '{$this->Transaction->SourceEmail}' has been sent!", $attr);
            else $res .= HTML::Warning("Could not send a notification to '{$this->Transaction->SourceEmail}'!", $attr);
		if(DataBase::DoInsert(\_::$CONFIG->DataBaseAddNameToPrefix."Payment", null, [
				"TID"=>$this->Transaction->ID,
				"Source"=>$this->Transaction->Source,
				"SourceContent"=>$this->Transaction->SourceContent,
				"SourcePath"=>$this->Transaction->SourcePath,
				"SourceEmail"=>$this->Transaction->SourceEmail,
				"Value"=>$this->Transaction->Value,
				"Unit"=>$this->Transaction->Unit,
				"Network"=>$this->Transaction->Network,
				"Transaction"=>$this->Transaction->Transaction,
				"Identifier"=>$this->Transaction->Identifier,
				"Destination"=>$this->Transaction->Destination,
				"DestinationContent"=>$this->Transaction->DestinationContent,
				"DestinationPath"=>$this->Transaction->DestinationPath,
				"DestinationEmail"=>$this->Transaction->DestinationEmail,
				"Others"=>$this->Transaction->Others
			]))
			$res .= HTML::Success("Your transaction recorded successfully", $attr);
        else $res .= HTML::Error("We could not record your transaction details, please notify us!", $attr);
		return HTML::Center(
			HTML::Container(
				HTML::Heading(HTML::Bold(parent::GetSuccess($msg))).
				$this->Transaction->ToHTML().
				$res
				, ...$attr)
			);
    }

	public function Capture(){
        if(RECEIVE($this->ValidationRequest, $this->Method) && RECEIVE("Value", $this->Method, false) !== false) return $this->Action();
		else return parent::Capture();
    }
}

class Transaction {
	public $ID = null;

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
		$this->ID = randomString(5)."_".first(preg_split("/\./",microtime(true)));
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

	public function ToHTML(){
        return HTML::Heading(__("Traction Number", styling: false).": ".HTML::Bold($this->ID)).
			HTML::Table([
			[__("From",styling:false).":", "{$this->Source} {$this->SourceEmail} {$this->SourceContent}"],
			[__("To",styling:false).":", "{$this->Destination} {$this->DestinationEmail} {$this->DestinationContent}"],
			[__("Value",styling:false).":", $this->Value.$this->Unit],
			[__("Network",styling:false).":", $this->Network],
			[__("Transaction",styling:false).":", $this->Transaction],
			[__("Identifier",styling:false).":", $this->Identifier],
			[__("Others",styling:false).":", $this->Others]
		],[],[]);
    }
}
?>