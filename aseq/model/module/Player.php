<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
use MiMFa\Library\Convert;
use MiMFa\Library\Script;

class Player extends Module
{
	public $Content = null;
	public $Source = null;
	public $AllowZoom = true;
	public $AllowShare = true;
	public $AllowDownload = true;
	public $PrependControls = null;
	public $AppendControls = null;
	public $Width = "100%";
	public $Height = "100%";
	public $MinHeight = "10px";
	public $MinWidth = "10px";
	public $MaxHeight = "100vh";//"-webkit-fill-available";
	public $MaxWidth = "100vw";

	/**
	 * Create the module
	 * @param array|string|null $source The module source
	 */
	public function __construct($source = null)
	{
		parent::__construct();
		$this->Set($source);
	}
	/**
	 * Set the main properties of module
	 * @param string|null $source The module source
	 */
	public function Set($source = null)
	{
		$this->Source = $source;
		return $this;
	}

	public function GetStyle()
	{
		return Struct::Style("
			.{$this->MainClass}>.controls{
				opacity: 0;
				display: inline-flex;
				position: absolute;
				top: auto;
				right: auto;
				font-size: var(--size-1);
				color: #8888;
				z-index: 1;
				" . \MiMFa\Library\Style::UniversalProperty("text-stroke", "1px var(--back-color-special)") . "
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass}>.controls>.button {
				aspect-ratio: 1;
				text-align: center;
				display: inline;
				padding: 1vh;
				cursor: pointer;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass}>.controls>.button:hover {
				background-color: var(--back-color-output);
				color: var(--fore-color-output);
				" . \MiMFa\Library\Style::UniversalProperty("text-stroke", "0px") . "
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}

			.{$this->MainClass}:hover>.controls{
				opacity: 1;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}

			.{$this->MainClass}>.content {
				" . \MiMFa\Library\Style::DoProperty("min-width", $this->MinWidth) . "
				" . \MiMFa\Library\Style::DoProperty("min-height", $this->MinHeight) . "
				" . \MiMFa\Library\Style::DoProperty("max-width", $this->MaxWidth) . "
				" . \MiMFa\Library\Style::DoProperty("max-height", $this->MaxHeight) . "
				" . \MiMFa\Library\Style::DoProperty("width", $this->Width) . "
				" . \MiMFa\Library\Style::DoProperty("height", $this->Height) . "
				padding: 0px;
				position: relative;
				text-align: center;
				overflow:auto;
			}

			.{$this->MainClass}>.content::-webkit-scrollbar {
				background: var(--back-color-special);
				width: 0px;
				height: 0px;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass}:hover>.content::-webkit-scrollbar {
				width: 5px;
				height: 5px;
			}
			.{$this->MainClass}>.content::-webkit-scrollbar:hover {
				//background: var(--back-color-input);
				width: 10px;
				height: 10px;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass}>.content::-webkit-scrollbar-track {
				border-radius: 1px;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass}>.content::-webkit-scrollbar-track:hover {
				border-radius: 0px;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass}>.content::-webkit-scrollbar-thumb {
				background: var(--fore-color-special);
				border-radius: 5px;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass}>.content::-webkit-scrollbar-thumb:hover {
				background: var(--fore-color-input);
				" . ($this->AllowZoom ? "cursor: grab;" : "") . "
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->MainClass}>.content>:not(.page,html,head,body,style,script,link,meta,title) {
				min-width: auto;
				width: auto;
				height: 100%;
				text-align: center;
				display: inline-block;
				position: relative;
				left: auto;
				right: auto;
				top: auto;
				bottom: auto;
				z-index: 0;
			}
		");
	}

	public function GetInner()
	{
		return Convert::ToString($this->GetElements($this->Content, $this->Source));
	}

	public function GetElements($content = null, $source = null)
	{
		$this->Source = $source ?? $this->Source;
		$this->Content = $content ?? $this->Content;
		yield "<div class=\"controls\">";
		yield Convert::ToString($this->PrependControls);
		foreach ($this->GetControls() as $btn)
			yield $btn;
		yield Convert::ToString($this->AppendControls);
		yield "</div>";
		yield $this->ToElement($this->Source);
		yield $this->GetContents($this->Content);
	}
	public function ToElement($source)
	{
		return join("\r\n", iterator_to_array($this->ToElements($source)));
	}
	public function ToElements($source)
	{
		if (isValid($source)) {
			yield "<div class=\"content\">";
			if (is_array($source))
				foreach ($source as $value)
					yield from $this->ToElements($value);
			elseif (isFormat($source, ".mpg", ".mpeg", ".mp4", ".avi", ".mkv", ".mov", ".wmv", ".flv", ".webm"))
				yield Struct::Video(null, $source, "controls");
			elseif (isFormat($source, ".wav", ".mp3", ".aac", ".amr", ".ogg", ".flac", ".wma", ".m4a"))
				yield Struct::Audio($source, "controls");
			elseif (isFormat($source, ".png", ".jpg", ".jpeg", ".jiff", ".gif", ".tif", ".tiff", ".bmp", ".ico", ".svg", ".webp"))
				yield Struct::Image(null, $source);
			else
				yield Struct::Embed($source);
			yield "</div>";
		}
	}

	public function GetScript(){
		return Struct::Script(($this->AllowZoom ? "
			let {$this->MainClass}slider = null;
			let {$this->MainClass}mouseDown = false;
			let {$this->MainClass}startX, {$this->MainClass}scrollLeft;
			let {$this->MainClass}startY, {$this->MainClass}scrollTop;

			let {$this->MainClass}startDragging = function (e) {
				{$this->MainClass}mouseDown = true;
				{$this->MainClass}startX = e.pageX - {$this->MainClass}slider.offsetLeft;
				{$this->MainClass}startY = e.pageY - {$this->MainClass}slider.offsetTop;
				{$this->MainClass}scrollLeft = {$this->MainClass}slider.scrollLeft;
				{$this->MainClass}scrollTop = {$this->MainClass}slider.scrollTop;
			};
			let {$this->MainClass}stopDragging = function (event) {
				{$this->MainClass}slider.style.cursor = 'grab';
				{$this->MainClass}mouseDown = false;
			};
			_(document).ready(
				function(){
					{$this->MainClass}slider = document.querySelector('.{$this->MainClass}>.content');

					{$this->MainClass}slider.addEventListener('mousemove', (e) => {
						e.preventDefault();
						if(!{$this->MainClass}mouseDown) { return; }
						{$this->MainClass}slider.style.cursor = 'grabbing';
						const x = e.pageX - {$this->MainClass}slider.offsetLeft;
						const y = e.pageY - {$this->MainClass}slider.offsetTop;
						const scrollx = x - {$this->MainClass}startX;
						const scrolly = y - {$this->MainClass}startY;
						{$this->MainClass}slider.scrollLeft = {$this->MainClass}scrollLeft - scrollx;
						{$this->MainClass}slider.scrollTop = {$this->MainClass}scrollTop - scrolly;
					});

					// Add the event listeners
					{$this->MainClass}slider.addEventListener('mousedown', {$this->MainClass}startDragging, false);
					{$this->MainClass}slider.addEventListener('mouseup', {$this->MainClass}stopDragging, false);
					{$this->MainClass}slider.addEventListener('mouseleave', {$this->MainClass}stopDragging, false);

					{$this->MainClass}slider.addEventListener('dblclick', (e) => { {$this->MainClass}_Zoom(); });
				}
			);
		" : "") . "
			let {$this->MainClass}_Source = " . (isValid($this->Source) ? Script::Convert($this->Source) : "null") . "

			function {$this->MainClass}_Set(content, source = null){
				{$this->MainClass}_Source = source??{$this->MainClass}_Source??content;
				if(content !== null) _('.{$this->MainClass}>.content').html(content);
			}
			function {$this->MainClass}_Clear(){
				_('.{$this->MainClass}>.content').html('');
			}
			function {$this->MainClass}_Focus(){
				_('.{$this->MainClass}>.controls').toggle(" . \_::$Front->AnimationSpeed . ");
			}
			function {$this->MainClass}_Reset(){
				let box = document.querySelector('.{$this->MainClass}>.content>*');
				box.style.width = null;
				box.style.height = null;
				box.style.left = null;
				box.style.top = null;
				zoomX = 0;
			}
			let zoomX = 0;
			function {$this->MainClass}_Zoom(){
				if(zoomX > 0) {$this->MainClass}_Reset();
				else {$this->MainClass}_ZoomIn(2);
			}
			function {$this->MainClass}_ZoomIn(x = 1){
				let box = document.querySelector('.{$this->MainClass}>.content>*');
				let width = box.offsetWidth;
				let height = box.offsetHeight;
				box.style.width = (width*(x*1.2))+'px';
				box.style.height = (height*(x*1.2))+'px';
				box.parentElement.scrollLeft += width*1.1;
				box.parentElement.scrollTop += height*1.1;
				zoomX+=x;
			}
			function {$this->MainClass}_ZoomOut(x = 1){
				let box = document.querySelector('.{$this->MainClass}>.content>*');
				let width = box.offsetWidth;
				let height = box.offsetHeight;
				box.style.width = (width/(x*1.2))+'px';
				box.style.height = (height/(x*1.2))+'px';
				box.parentElement.scrollLeft -= width*1.1;
				box.parentElement.scrollTop -= height*1.1;
				zoomX-=x;
			}
			function {$this->MainClass}_Download(){
				open({$this->MainClass}_Source, '_blank');
			}
			function {$this->MainClass}_Share(){
				share({$this->MainClass}_Source);
			}
		");
	}

	public function GetContents($content)
	{
		if (isValid($content))
			return "<div class='content' ondblclick=\"" . $this->FocusScript() . "\">" .
				Convert::ToString($content) .
				"</div>";
		return null;
	}
	public function GetControls()
	{
		if ($this->AllowDownload)
			yield Struct::Icon("download", $this->DownloadScript(), ["class"=>"button"]);
		if ($this->AllowShare)
			yield Struct::Icon("share-alt", $this->ShareScript(), ["class"=>"button"]);
		if ($this->AllowZoom)
			yield Struct::Icon("minus", $this->ZoomOutScript(), ["class"=>"button"]);
		if ($this->AllowZoom)
			yield Struct::Icon("plus", $this->ZoomInScript(), ["class"=>"button"]);
		if ($this->AllowZoom)
			yield Struct::Icon("refresh", $this->ResetScript(), ["class"=>"button"]);
	}

	public function SetScript($content = "", $source = null)
	{
		return $this->MainClass . "_Set(" .
			Script::Convert($content ?? $this->Content) . ", " .
			Script::Convert($source ?? $this->Source) . ");";
	}
	public function ClearScript()
	{
		return $this->MainClass . "_Clear();";
	}
	public function FocusScript()
	{
		return $this->MainClass . "_Focus();";
	}
	public function ResetScript()
	{
		return $this->MainClass . "_Reset();";
	}
	public function ZoomScript()
	{
		return $this->MainClass . "_Zoom();";
	}
	public function ZoomInScript()
	{
		return $this->MainClass . "_ZoomIn();";
	}
	public function ZoomOutScript()
	{
		return $this->MainClass . "_ZoomOut();";
	}
	public function DownloadScript()
	{
		return $this->MainClass . "_Download();";
	}
	public function ShareScript()
	{
		return $this->MainClass . "_Share();";
	}
}
?>