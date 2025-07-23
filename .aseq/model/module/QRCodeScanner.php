<?php namespace MiMFa\Module;
use MiMFa\Library\Local;
use MiMFa\Library\Convert;
use MiMFa\Library\Html;
use MiMFa\Library\Script;
use MiMFa\Library\Style;

/**
 * A Real-time webcam-driven HTML5 QR code scanner module.
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class QRCodeScanner extends Module{
	public $CameraIndex = 0;
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
	public $Title = "Scan Now...";
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
				font-size: var(--size-2);
				position: absolute;
				z-index: 1;
				max-width: 100%;
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
		//\Res::Script(null, \_::$Address->ScriptRoute . "Instascan.js");
		return Html::Script(null,"https://rawgit.com/schmich/instascan-builds/master/instascan.min.js").
		Html::OpenTag("video", $this->GetDefaultAttributes()).
		Html::Script($this->ActiveAtBegining?$this->ActiveScript():$this->DeactiveScript()).
		$this->BrowserNotSupportError.
		Html::CloseTag("video").
		($this->AllowMask?Html::Division($this->GetContent(), ["class"=>"mask"]):"").
		Html::Division($this->GetTitle().$this->GetDescription(), ["class"=>"message"]);
	}
	public function Toggle(){
		\Res::Script($this->ToggleScript());
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
		\Res::Script($this->ActiveScript());
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
				".($this->AllowMask?"document.querySelector('.{$this->Name} .mask').classList.remove('error');document.querySelector('.{$this->Name} .mask').classList.add('success');":"")."
				".($this->TargetScriptFunction?"({$this->TargetScriptFunction})(content);":"")."
				".($this->TargetId?"document.getElementById(".Script::Convert($this->TargetId).").value = content;":"")."
				".($this->TargetSelector?"document.querySelector(".Script::Convert($this->TargetSelector).").value = content;":"")."
				".($this->ActiveAtEnding?"":$this->DeactiveScript())."
			});
			Instascan.Camera.getCameras().then(function (cameras) {
				if (cameras.length >= {$this->CameraIndex}) {$this->Name}.start(cameras[{$this->CameraIndex}]);
				else console.error(".Script::Convert($this->CamerasNotFoundError).");
			}).catch(function (e) {
				console.error(e);
			});
		";
	}
	
	public function Deactive(){
		$this->ActiveAtBegining = false;
		\Res::Script($this->DeactiveScript());
		return $this;
	}
	public function DeactiveScript(){
		return "document.querySelector('.{$this->Name} video').style.display = 'none';";
	}

	public function SuccessScript($message = null){
		return ($this->AllowMask?"document.querySelector('.{$this->Name} .mask').classList.remove('error');document.querySelector('.{$this->Name} .mask').classList.add('success');":"").
		"document.querySelector('.{$this->Name} .message').innerHTML = Html.success(".Script::Convert(__($message, styling:false)).");";
	}
	public function ErrorScript($message = null){
		return ($this->AllowMask?"document.querySelector('.{$this->Name} .mask').classList.remove('success');document.querySelector('.{$this->Name} .mask').classList.add('error');":"").
		"document.querySelector('.{$this->Name} .message').innerHTML = Html.error(".Script::Convert(__($message, styling:false)).");";
	}
}
?>
