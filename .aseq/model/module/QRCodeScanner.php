<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
use MiMFa\Library\Script;
use MiMFa\Library\Style;

/**
 * A Real-time webcam-driven HTML5 QR code scanner module.
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class QRCodeScanner extends Module
{
	public $CameraIndex = 1;
	public $AllowMirrorCamera = false;
	public $AlternativeCameraIndex = 0;
	public $AllowMirrorAlternativeCamera = true;
	public $Local = true;
	public $ScriptSource = "https://rawgit.com/schmich/instascan-builds/master/instascan.min.js";

	public $ActiveButtonLabel = "<i class='fa fa-power-off'></i>";
	public $SwitchButtonLabel = "<i class='fa fa-camera-rotate'></i>";
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
	public $DeactiveDescription = "'Turn on' the 'Scanner'";
	public $CamerasNotFoundError = "No cameras found.";
	public $BrowserNotSupportError = "Your browser does not support the video tag.";
	public $Printable = false;


	public function __construct()
	{
		parent::__construct();

	}
	public function GetStyle()
	{
		return parent::GetStyle() . Struct::Style("
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
				background-color: var(--color-black);
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
				
			.{$this->Name} .controls{
				font-size: var(--size-1);
				position: absolute;
				bottom: 0;
				padding:0;
				display: flex;
				align-content: center;
				justify-content: center;
				align-items: center;
				gap:var(--size-0);
				z-index: 2;
			}
			.{$this->Name} .controls .button{
				background-color: #00000033;
				color: #ffffff88;
				padding:1vmin;
				margin:1vmin;
			}
			.{$this->Name} .controls .button:hover{
				background-color: #88888888;
				color: #ffffff;
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

	public function Get()
	{
		if ($this->Local)
			$this->ScriptSource = asset(\_::$Address->PackageDirectory, "Scanner/Scanner.js");
		return Struct::Script(null, $this->ScriptSource) .
			Struct::OpenTag("video", $this->GetDefaultAttributes()) .
			$this->BrowserNotSupportError .
			Struct::CloseTag("video") .
			($this->AllowMask ? Struct::Division($this->GetContent(), ["class" => "mask"]) : "") .
			Struct::Division(
				($this->SwitchButtonLabel ? Struct::Button($this->SwitchButtonLabel, $this->SwitchScript(), ["class" => "switchcamera be hide"]) : "") .
				($this->ActiveButtonLabel ? Struct::Button($this->ActiveButtonLabel, $this->ToggleScript(), ["class" => "activation be"]) : "")
				,
				["class" => "controls"]
			) .
			Struct::Division($this->GetTitle(["class" => $this->TitleClass]) . $this->GetDescription(["class" => $this->DescriptionClass]), ["class" => "message"]);
	}
	public function GetScript()
	{
		return Struct::Script("
		try{
			if(!Instascan.Scanner) Struct.script.load(null, '" . asset(\_::$Address->PackageDirectory, "Scanner/Scanner.js", optimize: true) . "');
			} catch{Struct.script.load(null, '" . asset(\_::$Address->PackageDirectory, "Scanner/Scanner.js", optimize: true) . "');}
			{$this->Name} = new Instascan.Scanner({video: document.querySelector('.{$this->Name} video')});
			{$this->Name}.addListener('scan', function (content) {
				" . ($this->AllowMask ? "
					document.querySelector('.{$this->Name} .mask').classList.remove('error');
					document.querySelector('.{$this->Name} .mask').innerHTML = '';wait(3000);
				" : "") . "
				" . ($this->TargetScriptFunction ? "({$this->TargetScriptFunction})(content);" : "") . "
				" . ($this->TargetId ? "document.getElementById(" . Script::Convert($this->TargetId) . ").value = content;" : "") . "
				" . ($this->TargetSelector ? "document.querySelector(" . Script::Convert($this->TargetSelector) . ").value = content;" : "") . "
				" . ($this->ActiveAtEnding ? "" : $this->DeactiveScript()) . "
				" . ($this->AllowMask ? "document.querySelector('.{$this->Name} .mask').classList.add('success');" : "") . "
			});
			{$this->Name}_selectedCamera = -1;
			function {$this->Name}_useCamera(cameras, index = null, mirror = false) {
				if(cameras.length > 1) {
					document.querySelector('.{$this->Name} .switchcamera')?.classList.remove('hide');
					document.querySelector('.{$this->Name} .activation')?.classList.add('hide');
				}
				else {
					document.querySelector('.{$this->Name} .switchcamera')?.classList.add('hide');
					document.querySelector('.{$this->Name} .activation')?.classList.remove('hide');
				}
				if(index===null || index < 0) {
					for(i=cameras.length-1;i>=0;i--)
						if(
							i !== {$this->CameraIndex} &&
							i !== {$this->AlternativeCameraIndex} &&
							{$this->Name}_useCamera(cameras, i, mirror)
						) return true;
				} else if (cameras.length > index && {$this->Name}_selectedCamera !== index) {
					if({$this->Name}) {
						if({$this->Name}?._camera?._stream) {$this->Name}.stop();
						{$this->Name}.start(cameras[index]);
						{$this->Name}.mirror = mirror;
						{$this->Name}_selectedCamera = index;
						document.querySelector('.{$this->Name} .message').innerHTML = " . Script::Convert(__($this->Description)) . ";
						document.querySelector('.{$this->Name} .message').classList.remove('hide');
						return true;
					}
				}
				return false;
			};" .
			($this->ActiveAtBegining ? $this->ActiveScript() : $this->DeactiveScript())
		);
	}
	public function Toggle()
	{
		\_::$Front->Append("body", $this->ToggleScript());
	}
	public function ToggleScript()
	{
		return "if({$this->Name} && {$this->Name}?._camera?._stream) {
			" . $this->DeactiveScript() . "
		}
		else {
			" . $this->ActiveScript() . "
		}";
	}

	public function Switch()
	{
		\_::$Front->Append("body", $this->SwitchScript());
	}
	public function SwitchScript()
	{
		return $this->DeactiveScript() . "
		if({$this->Name}_selectedCamera === $this->CameraIndex)
			Instascan.Camera.getCameras().then((cameras) => {$this->Name}_useCamera(cameras, " . ($this->AlternativeCameraIndex ?? "null") . "," . ($this->AllowMirrorAlternativeCamera ? 'true' : 'false') . ")).catch((e)=>console.log(e));
		else Instascan.Camera.getCameras().then((cameras) => {$this->Name}_useCamera(cameras, {$this->CameraIndex}," . ($this->AllowMirrorCamera ? 'true' : 'false') . ")).catch((e)=>console.log(e));";
	}

	public function Active()
	{
		$this->ActiveAtBegining = true;
		\_::$Front->Append("body", $this->ActiveScript());
		return $this;
	}
	public function ActiveScript()
	{
		return "
		if({$this->Name} && {$this->Name}?._camera && !{$this->Name}?._camera?._stream) {
			{$this->Name}.start();
			document.querySelector('.{$this->Name} .message').innerHTML = " . Script::Convert(__($this->Description)) . ";
			document.querySelector('.{$this->Name} .message').classList.remove('hide');
		}
		else Instascan.Camera.getCameras().then(function (cameras) {
			if(!{$this->Name}_useCamera(cameras, {$this->CameraIndex}," . ($this->AllowMirrorCamera ? 'true' : 'false') . "))
				if(!{$this->Name}_useCamera(cameras, {$this->AlternativeCameraIndex}," . ($this->AllowMirrorAlternativeCamera ? 'true' : 'false') . "))
					if(!{$this->Name}_useCamera(cameras))
						return console.error(" . Script::Convert($this->CamerasNotFoundError) . ");
			document.querySelector('.{$this->Name} .message').innerHTML = " . Script::Convert(__($this->Description)) . ";
			document.querySelector('.{$this->Name} .message').classList.remove('hide');
		}).catch((e)=>console.error(e));";
	}

	public function Deactive()
	{
		$this->ActiveAtBegining = false;
		\_::$Front->Append("body", $this->DeactiveScript());
		return $this;
	}
	public function DeactiveScript()
	{
		return "if({$this->Name} && {$this->Name}?._camera?._stream) {
			{$this->Name}.stop();
		}
		" . ($this->DeactiveDescription ?
			"document.querySelector('.{$this->Name} .message').innerHTML = " . Script::Convert(__($this->DeactiveDescription)) . ";" :
			"document.querySelector('.{$this->Name} .message').classList.add('hide');");
	}

	public function MessageScript($message = null)
	{
		return ($this->AllowMask ? "document.querySelector('.{$this->Name} .mask').classList.remove('error');document.querySelector('.{$this->Name} .mask').classList.remove('success');" : "") .
			"document.querySelector('.{$this->Name} .message').classList.remove('hide');
			document.querySelector('.{$this->Name} .message').innerHTML = Struct.division(" . Script::Convert(__($message)) . ");";
	}
	public function SuccessScript($message = null)
	{
		return ($this->AllowMask ? "document.querySelector('.{$this->Name} .mask').classList.remove('error');document.querySelector('.{$this->Name} .mask').classList.add('success');" : "") .
			"document.querySelector('.{$this->Name} .message').classList.remove('hide');
			document.querySelector('.{$this->Name} .message').innerHTML = Struct.division(" . Script::Convert(__($message)) . ", {CLASS:'be fore green'});";
	}
	public function ErrorScript($message = null)
	{
		return ($this->AllowMask ? "document.querySelector('.{$this->Name} .mask').classList.remove('success');document.querySelector('.{$this->Name} .mask').classList.add('error');" : "") .
			"document.querySelector('.{$this->Name} .message').classList.remove('hide');
			document.querySelector('.{$this->Name} .message').innerHTML = Struct.division(" . Script::Convert(__($message)) . ", {CLASS:'be fore red'});";
	}
}