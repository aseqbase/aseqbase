<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
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
		return Html::Style("
			.{$this->Name}>.controls{
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
			.{$this->Name}>.controls>.button {
				aspect-ratio: 1;
				text-align: center;
				display: inline;
				padding: 1vh;
				cursor: pointer;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name}>.controls>.button:hover {
				background-color: var(--back-color-output);
				color: var(--fore-color-output);
				" . \MiMFa\Library\Style::UniversalProperty("text-stroke", "0px") . "
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}

			.{$this->Name}:hover>.controls{
				opacity: 1;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}

			.{$this->Name}>.content {
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

			.{$this->Name}>.content::-webkit-scrollbar {
				background: var(--back-color-special);
				width: 0px;
				height: 0px;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name}:hover>.content::-webkit-scrollbar {
				width: 5px;
				height: 5px;
			}
			.{$this->Name}>.content::-webkit-scrollbar:hover {
				//background: var(--back-color-input);
				width: 10px;
				height: 10px;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name}>.content::-webkit-scrollbar-track {
				border-radius: 1px;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name}>.content::-webkit-scrollbar-track:hover {
				border-radius: 0px;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name}>.content::-webkit-scrollbar-thumb {
				background: var(--fore-color-special);
				border-radius: 5px;
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name}>.content::-webkit-scrollbar-thumb:hover {
				background: var(--fore-color-input);
				" . ($this->AllowZoom ? "cursor: grab;" : "") . "
				" . \MiMFa\Library\Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name}>.content>:not(.page,html,head,body,style,script,link,meta,title) {
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

	public function Get()
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
				yield Html::Video(null, $source, "controls");
			elseif (isFormat($source, ".wav", ".mp3", ".aac", ".amr", ".ogg", ".flac", ".wma", ".m4a"))
				yield Html::Audio($source, "controls");
			elseif (isFormat($source, ".png", ".jpg", ".jpeg", ".jiff", ".gif", ".tif", ".tiff", ".bmp", ".ico", ".svg", ".webp"))
				yield Html::Image(null, $source);
			else
				yield Html::Embed($source);
			yield "</div>";
		}
	}

	public function GetScript(){
		return Html::Script(($this->AllowZoom ? "
			let {$this->Name}slider = null;
			let {$this->Name}mouseDown = false;
			let {$this->Name}startX, {$this->Name}scrollLeft;
			let {$this->Name}startY, {$this->Name}scrollTop;

			let {$this->Name}startDragging = function (e) {
				{$this->Name}mouseDown = true;
				{$this->Name}startX = e.pageX - {$this->Name}slider.offsetLeft;
				{$this->Name}startY = e.pageY - {$this->Name}slider.offsetTop;
				{$this->Name}scrollLeft = {$this->Name}slider.scrollLeft;
				{$this->Name}scrollTop = {$this->Name}slider.scrollTop;
			};
			let {$this->Name}stopDragging = function (event) {
				{$this->Name}slider.style.cursor = 'grab';
				{$this->Name}mouseDown = false;
			};
			$(document).ready(
				function(){
					{$this->Name}slider = document.querySelector('.{$this->Name}>.content');

					{$this->Name}slider.addEventListener('mousemove', (e) => {
						e.preventDefault();
						if(!{$this->Name}mouseDown) { return; }
						{$this->Name}slider.style.cursor = 'grabbing';
						const x = e.pageX - {$this->Name}slider.offsetLeft;
						const y = e.pageY - {$this->Name}slider.offsetTop;
						const scrollx = x - {$this->Name}startX;
						const scrolly = y - {$this->Name}startY;
						{$this->Name}slider.scrollLeft = {$this->Name}scrollLeft - scrollx;
						{$this->Name}slider.scrollTop = {$this->Name}scrollTop - scrolly;
					});

					// Add the event listeners
					{$this->Name}slider.addEventListener('mousedown', {$this->Name}startDragging, false);
					{$this->Name}slider.addEventListener('mouseup', {$this->Name}stopDragging, false);
					{$this->Name}slider.addEventListener('mouseleave', {$this->Name}stopDragging, false);

					{$this->Name}slider.addEventListener('dblclick', (e) => { {$this->Name}_Zoom(); });
				}
			);
		" : "") . "
			let {$this->Name}_Source = " . (isValid($this->Source) ? Script::Convert($this->Source) : "null") . "

			function {$this->Name}_Set(content, source = null){
				{$this->Name}_Source = source??{$this->Name}_Source??content;
				if(content !== null) $('.{$this->Name}>.content').html(content);
			}
			function {$this->Name}_Clear(){
				$('.{$this->Name}>.content').html('');
			}
			function {$this->Name}_Focus(){
				$('.{$this->Name}>.controls').toggle(" . \_::$Front->AnimationSpeed . ");
			}
			function {$this->Name}_Reset(){
				let box = document.querySelector('.{$this->Name}>.content>*');
				box.style.width = null;
				box.style.height = null;
				box.style.left = null;
				box.style.top = null;
				zoomX = 0;
			}
			let zoomX = 0;
			function {$this->Name}_Zoom(){
				if(zoomX > 0) {$this->Name}_Reset();
				else {$this->Name}_ZoomIn(2);
			}
			function {$this->Name}_ZoomIn(x = 1){
				let box = document.querySelector('.{$this->Name}>.content>*');
				let width = box.offsetWidth;
				let height = box.offsetHeight;
				box.style.width = (width*(x*1.2))+'px';
				box.style.height = (height*(x*1.2))+'px';
				box.parentElement.scrollLeft += width*1.1;
				box.parentElement.scrollTop += height*1.1;
				zoomX+=x;
			}
			function {$this->Name}_ZoomOut(x = 1){
				let box = document.querySelector('.{$this->Name}>.content>*');
				let width = box.offsetWidth;
				let height = box.offsetHeight;
				box.style.width = (width/(x*1.2))+'px';
				box.style.height = (height/(x*1.2))+'px';
				box.parentElement.scrollLeft -= width*1.1;
				box.parentElement.scrollTop -= height*1.1;
				zoomX-=x;
			}
			function {$this->Name}_Download(){
				open({$this->Name}_Source, '_blank');
			}
			function {$this->Name}_Share(){
				share({$this->Name}_Source);
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
			yield '<div class="icon fa fa-download button" onclick="' . $this->DownloadScript() . '"></div>';
		if ($this->AllowShare)
			yield '<div class="icon fa fa-share-alt button" onclick="' . $this->ShareScript() . '"></div>';
		if ($this->AllowZoom)
			yield '<div class="icon fa fa-minus button" onclick="' . $this->ZoomOutScript() . '"></div>';
		if ($this->AllowZoom)
			yield '<div class="icon fa fa-plus button" onclick="' . $this->ZoomInScript() . '"></div>';
		if ($this->AllowZoom)
			yield '<div class="icon fa fa-refresh button" onclick="' . $this->ResetScript() . '"></div>';
	}

	public function SetScript($content = "", $source = null)
	{
		return $this->Name . "_Set(" .
			Script::Convert($content ?? $this->Content) . ", " .
			Script::Convert($source ?? $this->Source) . ");";
	}
	public function ClearScript()
	{
		return $this->Name . "_Clear();";
	}
	public function FocusScript()
	{
		return $this->Name . "_Focus();";
	}
	public function ResetScript()
	{
		return $this->Name . "_Reset();";
	}
	public function ZoomScript()
	{
		return $this->Name . "_Zoom();";
	}
	public function ZoomInScript()
	{
		return $this->Name . "_ZoomIn();";
	}
	public function ZoomOutScript()
	{
		return $this->Name . "_ZoomOut();";
	}
	public function DownloadScript()
	{
		return $this->Name . "_Download();";
	}
	public function ShareScript()
	{
		return $this->Name . "_Share();";
	}
}
?>