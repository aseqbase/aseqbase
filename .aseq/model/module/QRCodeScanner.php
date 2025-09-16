<?php namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Script;

/**
 * A Real-time webcam-driven HTML5 QR code scanner module.
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class QRCodeScanner extends Module{
	public $CameraIndex = 1;
	public $AllowMirrorCamera = false;
	public $AlternativeCameraIndex = 0;
	public $AllowMirrorAlternativeCamera = false;
	/**
	 * The target JS function name or a function like (content)=>//do process
	 */
	public $TargetScriptFunction = null;
	public $TargetId = null;
	public $TargetSelector = null;
	public $ActiveAtBegining = true;
	public $ActiveAtEnding = true;
	public $AllowMask = true;
	public $MaskSize = "75%";
	public $Title = null;
	public $TitleTag = "h3";
	public $TitleClass = "fa-fade";
	public $Description = "Scan Now...";
	public $DescriptionClass = "fa-fade";
	public $CamerasNotFoundError = "No cameras found.";
	public $BrowserNotSupportError = "Your browser does not support the video tag.";
    public $Printable = false;

	
	public function __construct(){
		parent::__construct();
	}
	public function GetStyle(){
		return parent::GetStyle().Html::Style("
			.{$this->Name}{
			    position: relative;
				background-color: black;
				object-fit: cover;
				display: inline-flex;
				align-content: center;
				justify-content: center;
				align-items: center;
				overflow: hidden;
			}
			.{$this->Name} video{
				background-color: black;
				object-fit: cover;
				width: 100%;
				height: 100%;
			}
			.{$this->Name} .message{
				color: white;
				padding:0px;
				margin:0px;
				position: absolute;
				z-index: 1;
				max-width: 100%;
				text-align:center;
				display: inline-flex;
				align-content: center;
				justify-content: center;
				align-items: center;
			}
			.{$this->Name} .message *{
				text-align:center;
				padding:0px;
				margin:0px;
			}
			.{$this->Name} .mask{
				position: absolute;
				border: 1px solid white;
				z-index: 1;
				width: $this->MaskSize;
				aspect-ratio: 1;
				box-shadow: 0px 0px 100vmax black;
			}
			.{$this->Name} .mask.error{
				border-color: red;
			}
			.{$this->Name} .mask.success{
				border-color: green;
			}
		");
	}

	public function Get(){
		//RenderScript(null, \_::$Address->ScriptRoute . "Instascan.js");
		return Html::Script(null,"https://rawgit.com/schmich/instascan-builds/master/instascan.min.js").
		Html::OpenTag("video", $this->GetDefaultAttributes()).
		Html::Script($this->ActiveAtBegining?$this->ActiveScript():$this->DeactiveScript()).
		$this->BrowserNotSupportError.
		Html::CloseTag("video").
		($this->AllowMask?Html::Division($this->GetContent(), ["class"=>"mask"]):"").
		Html::Division($this->GetTitle(["class"=>$this->TitleClass]).$this->GetDescription(["class"=>$this->DescriptionClass]), ["class"=>"message"]);
	}
	public function Toggle(){
		injectScript($this->ToggleScript());
	}
	public function ToggleScript(){
		return "qrscanner = document.querySelector('.{$this->Name} video');
		if(qrscanner.style.display =='none') {
			".$this->ActiveScript()."
		}
		else {
			".$this->DeactiveScript()."
		}";
	}

	public function Active(){
		$this->ActiveAtBegining = true;
		injectScript($this->ActiveScript());
		return $this;
	}
	public function ActiveScript(){
		return "
			document.querySelector('.{$this->Name} video').style.display = null;
			try{
				if(!Instascan.Scanner) Html.script.load(null, '" . asset(\_::$Address->ScriptDirectory, "Instascan.js", optimize: true) . "');
			}catch{Html.script.load(null, '" . asset(\_::$Address->ScriptDirectory, "Instascan.js", optimize: true) . "');}
			let {$this->Name} = new Instascan.Scanner({ video: document.querySelector('.{$this->Name} video') });
			{$this->Name}.addListener('scan', function (content) {
				".($this->AllowMask?"document.querySelector('.{$this->Name} .mask').classList.remove('error');document.querySelector('.{$this->Name} .mask').innerHTML = '';wait(3000);":"")."
				".($this->TargetScriptFunction?"({$this->TargetScriptFunction})(content);":"")."
				".($this->TargetId?"document.getElementById(".Script::Convert($this->TargetId).").value = content;":"")."
				".($this->TargetSelector?"document.querySelector(".Script::Convert($this->TargetSelector).").value = content;":"")."
				".($this->ActiveAtEnding?"":$this->DeactiveScript())."
				".($this->AllowMask?"document.querySelector('.{$this->Name} .mask').classList.add('success');":"")."
			});
			addcam = function (cameras, index, mirror) {
				if (cameras.length > index) try{
					{$this->Name}.start(cameras[index]);
					if({$this->Name}._camera) {$this->Name}._mirror = mirror??{$this->Name}._mirror;
					else return false;
					return true;
				}catch{}
				return false;
			};
			Instascan.Camera.getCameras().then(function (cameras) {
				if(!addcam(cameras, {$this->CameraIndex},".($this->AllowMirrorCamera?'true':'false')."))
					if(!addcam(cameras, {$this->AlternativeCameraIndex},".($this->AllowMirrorAlternativeCamera?'true':'false')."))
						if(!addcam(cameras, 0, null))
							console.error(".Script::Convert($this->CamerasNotFoundError).");
			}).catch(function (e) {
				console.error(e);
			});
		";
	}
	
	public function Deactive(){
		$this->ActiveAtBegining = false;
		injectScript($this->DeactiveScript());
		return $this;
	}
	public function DeactiveScript(){
		return "document.querySelector('.{$this->Name} video').style.display = 'none';";
	}

	public function MessageScript($message = null){
		return ($this->AllowMask?"document.querySelector('.{$this->Name} .mask').classList.remove('error');document.querySelector('.{$this->Name} .mask').classList.remove('success');":"").
		"document.querySelector('.{$this->Name} .message').innerHTML = Html.division(".Script::Convert(__($message)).");";
	}
	public function SuccessScript($message = null){
		return ($this->AllowMask?"document.querySelector('.{$this->Name} .mask').classList.remove('error');document.querySelector('.{$this->Name} .mask').classList.add('success');":"").
		"document.querySelector('.{$this->Name} .message').innerHTML = Html.division(".Script::Convert(__($message)).", {CLASS:'be fore green'});";
	}
	public function ErrorScript($message = null){
		return ($this->AllowMask?"document.querySelector('.{$this->Name} .mask').classList.remove('success');document.querySelector('.{$this->Name} .mask').classList.add('error');":"").
		"document.querySelector('.{$this->Name} .message').innerHTML = Html.division(".Script::Convert(__($message)).", {CLASS:'be fore red'});";
	}
}