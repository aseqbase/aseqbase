<?php
LIBRARY("Revise");
/**
*All the basic website template values and functions
*@copyright All rights are reserved for MiMFa Development Group
*@author Mohammad Fathi
*@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
*@link https://github.com/aseqbase/aseqbase/wiki/Structures See the Structures Documentation
*/
abstract class TemplateBase{
	public $AnimationSpeed = 250;
	public $DetectMode = true;
	public $DarkMode = null;

	/**
		* Default page head Packages
        * @field html
		* @var array
		*/
	public $Basics = ["
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js'></script>
		<script>window.jQuery || document.write(`<script src='https://code.jquery.com/jquery-3.7.1.min.js'><\/script>`)</script>
		<link rel='stylesheet' href='https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css'>
		<script src='https://cdn.datatables.net/2.0.3/js/dataTables.min.js'></script>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js'></script>
		<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'>
		<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script>
		<script src='https://kit.fontawesome.com/e557f8d9f4.js' crossorigin='anonymous'></script>
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css'>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js' crossorigin='anonymous'></script>
	"];
	/**
		* The custom head packages
		* @field html
		* @var array
		*/
	public $Initials = [];
	/**
		* The custom top of body's tags
		* @field html
		* @var array
		*/
	public $Mains = [];
	/**
		* The custom top of body's tags
		* @field html
		* @var array
		*/
	public $Finals = [];

	/**
		* Full Colors Palette
        * @field array<color>
		* @var mixed
		*/
	public $ColorPalette = array("#dd2222","#22dd22","#2222dd","#ccbb22","#22dddd","#dd22dd");
	/**
        * Fore Colors Palette
        * @field array<color>
        * @var mixed
        */
	public $ForeColorPalette = array("#030405","#010203","#010203","#040506","#3aa3e9","#fdfeff");
	/**
        * Back Colors Palette
        * @field array<color>
        * @var mixed
        */
	public $BackColorPalette = array("#fcfdfe","#fafbfc","#fafbfc","#fafcfd","#fdfeff","#3aa3e9");
	/**
        * Fonts Palette
        * @field array<font>
        * @var mixed
        */
	public $FontPalette = array("'dubai light', sans-serif","'dubai', sans-serif","'dubai', sans-serif","'Tahoma', sans-serif","'Tahoma', sans-serif","'Times new Romance', sans-serif");
	/**
        * Sizes Palette
        * @field array<size>
        * @var mixed
        */
	public $SizePalette = array("2.3vh","2.4vh","2.6vh","3vh","3.6vh","4.4vh","5.4vh");
	/**
        * Shadows Palette
        * @field array<{'size', 'size', 'size', 'color'}>
        * @field array<text>
        * @var mixed
        */
	public $ShadowPalette = array("none","4px 7px 20px #00000005","4px 7px 20px #00000015","4px 7px 20px #00000030","5px 10px 25px #00000030","5px 10px 25px #00000050","5px 10px 50px #00000050");
	/**
        * Borders Palette
        * @field array<{'size', ['solid','double','dotted','dashed']}>
        * @field array<text>
        * @var mixed
        */
	public $BorderPalette = array("0px","1px solid","2px solid","5px solid","10px solid","25px solid");
	/**
        * Radiuses Palette
        * @field array<size>
        * @var mixed
        */
	public $RadiusPalette = array("0px","3px","5px","50px","50%","100%");
	/**
        * Transitions Palette
        * @field array<text>
        * @var mixed
        */
	public $TransitionPalette = array("none","all .25s linear","all .5s linear","all .75s linear","all 1s linear","all 1.5s linear");
	/**
		* Overlays Palette
        * @field array<path>
        * @var mixed
        */
	public $OverlayPalette = array("/asset/overlay/glass.png","/asset/overlay/cotton.png","/asset/overlay/cloud.png","/asset/overlay/wings.svg","/asset/overlay/sands.png","/asset/overlay/dirty.png");
	/**
		* Patterns Palette
        * @field array<path>
        * @var mixed
        */
	public $PatternPalette = array("/asset/pattern/main.svg","/asset/pattern/doddle.png","/asset/pattern/doddle-fantasy.png","/asset/pattern/triangle.png","/asset/pattern/slicksline.png","/asset/pattern/doddle-mess.png");

	public static function LoopPalette($palette, int $ind = 0) { $ind %= count($palette); return $palette[$ind];}
	public static function LimitPalette($palette, int $ind = 0) { return $palette[$ind >= count($palette)?count($palette)-1:max(0,$ind)];}

	public function Color(int $ind = 0) { return self::LoopPalette($this->ColorPalette,$ind);}
	public function ForeColor(int $ind = 0) { return self::LoopPalette($this->ForeColorPalette,$ind);}
	public function BackColor(int $ind = 0) { return self::LoopPalette($this->BackColorPalette,$ind);}
	public function Font(int $ind = 0) { return self::LoopPalette($this->FontPalette,$ind);}
	public function Size(int $ind = 0) { return self::LimitPalette($this->SizePalette,$ind);}
	public function Shadow(int $ind = 0) { return self::LimitPalette($this->ShadowPalette,$ind);}
	public function Border(int $ind = 0) { return self::LimitPalette($this->BorderPalette,$ind);}
	public function Radius(int $ind = 0) { return self::LimitPalette($this->RadiusPalette,$ind);}
	public function Transition(int $ind = 0) { return self::LimitPalette($this->TransitionPalette,$ind);}
	public function Overlay(int $ind = 0) { return \MiMFa\Library\Local::GetUrl(self::LoopPalette($this->OverlayPalette,$ind));}
	public function Pattern(int $ind = 0) { return \MiMFa\Library\Local::GetUrl(self::LoopPalette($this->PatternPalette,$ind));}

	public function __construct(){
		\MiMFa\Library\Revise::Load($this);
		if($this->IsDark($this->BackColor(0))===true) $this->DarkMode = true;
		else $this->DarkMode = false;
		$lm = getValid($_REQUEST,"LightMode")? changeMemo("LightMode",getValid($_REQUEST,"LightMode", null)) : false;
		$dm = getValid($_REQUEST,"DarkMode")? changeMemo("DarkMode",getValid($_REQUEST,"DarkMode", null)) : false;
		if(
			$this->DetectMode && (
			($this->DarkMode && (!empty($lm) || getMemo("LightMode")))
			||
			(!$this->DarkMode && (!empty($dm) || getMemo("DarkMode")))
		))
		{
            $middle = $this->ForeColorPalette;
            $this->ForeColorPalette = $this->BackColorPalette;
            $this->BackColorPalette = $middle;
			$this->DarkMode = !$this->DarkMode;
        }
	}

	public function GetInitial():string|null{
		return "
		<script>
			const mailTo = function(url=null){
				open('mailto:'+(url??`".\_::$EMAIL."`), '_blank');
			};
			const transitData = function(
				methodName = 'POST',
				actionPath = null,
				requestData = null,
				selector = 'body :nth-child(1)',
				successHandler = null,
				errorHandler = null,
				readyHandler = null,
				processHandler = null,
				timeout = null) {

				successHandler = successHandler??function(data, selector){
					$(selector + ' .result').remove();
					//if(isEmpty(data)) load();
					//else {
					if(!isEmpty(data)) {
						data = ((typeof(data) == 'object')?data.statusText:data)??'".__("The form submitted successfully!")."';
						if(!isEmpty(data))
							$(selector).prepend(Html.success(data));
					}
				};
				errorHandler = errorHandler??function(data, selector){
					$(selector + ' .result').remove();
					//if(isEmpty(data)) load();
					//else {
					if(!isEmpty(data)) {
						data = ((typeof(data) == 'object')?data.statusText:data)??'".__("There a problem occured!")."';
						if(!isEmpty(data))
							$(selector).prepend(Html.error(data));
					}
				};
				readyHandler = readyHandler??function(data, selector){
					$(selector + ' .result').remove();
				};
				processHandler = processHandler??function(data, selector){
					if(!isEmpty(data)){
						data = typeof(data) == 'object'?data.statusText:data;
						if(!isEmpty(data)) $(selector).prepend(data);
					}
				};

				const btns = selector+' :is(button, .btn, .icon, input:is([type=button], [type=submit], [type=image], [type=reset]))';
				actionPath = actionPath??location.href;
				timeout = timeout??30000;
				contentType = false;
				switch(typeof(requestData)){
					case 'object':
						if(!requestData instanceof(FormData)){
							contentType = 'application/json; charset=utf-8';
							requestData = JSON.stringify(requestData);
						}
					break;
					default:
						contentType = 'application/x-www-form-urlencoded; charset=utf-8';
					break;
				}

				return $.ajax({
					type: methodName,
					url: actionPath,
					data: requestData,
					xhr: function () {
						var myXhr = $.ajaxSettings.xhr();
						if (myXhr.upload) myXhr.upload.addEventListener('progress', (data)=>processHandler(data, selector), false);
						return myXhr;
					},
					success: function (data) {
						successHandler(data,selector);
						$(btns).removeClass('hide');
						$(selector).css('opacity','1');
					},
					error: function (data) {
						errorHandler(data,selector);
						$(btns).removeClass('hide');
						$(selector).css('opacity','1');
					},
					beforeSend: function (data) {
						readyHandler(data,selector);
						$(btns).addClass('hide');
						$(selector).css('opacity','.5');
					},
					async: true,
					cache: false,
					contentType: contentType,
					processData: false,
					timeout: timeout
				});
			};
			const getData = function(actionPath = null, requestData = null, selector = 'body :nth-child(1)', successHandler = null, errorHandler = null, readyHandler = null, processHandler = null, timeout = null) {
					return transitData('GET', actionPath, requestData, selector, successHandler, errorHandler, readyHandler, processHandler, timeout);
			};
			const postData = function(actionPath = null, requestData = null, selector = 'body :nth-child(1)', successHandler = null, errorHandler = null, readyHandler = null, processHandler = null, timeout = null) {
					return transitData('POST', actionPath, requestData, selector, successHandler, errorHandler, readyHandler, processHandler, timeout);
			};
			const submitForm = function(selector = 'form', successHandler = null, errorHandler = null, readyHandler = null, processHandler = null, timeout = null){
				const actionPath = $(selector).attr('action');
				const methodName = $(selector).attr('method');
				const requestData = new FormData(selector);

				return transitData(methodName, actionPath, requestData, selector, successHandler, errorHandler, readyHandler, processHandler, timeout);
			};
			const handleForm = function(selector = 'form', successHandler = null, errorHandler = null, readyHandler = null, processHandler = null, timeout = null){
				$(selector).submit(function(e) {
					e.preventDefault();

					const form = $(this);
					const actionPath = form.attr('action');
					const methodName = form.attr('method');
					const requestData = new FormData(this);

					return transitData(methodName, actionPath, requestData, selector, successHandler, errorHandler, readyHandler, processHandler, timeout);
				});
			};
		</script>

		<style>
			:root{
				--Color-0: ".$this->Color(0).";
				--Color-1: ".$this->Color(1).";
				--Color-2: ".$this->Color(2).";
				--Color-3: ".$this->Color(3).";
				--Color-4: ".$this->Color(4).";
				--Color-5: ".$this->Color(5).";
				--ForeColor-0: ".$this->ForeColor(0).";
				--ForeColor-1: ".$this->ForeColor(1).";
				--ForeColor-2: ".$this->ForeColor(2).";
				--ForeColor-3: ".$this->ForeColor(3).";
				--ForeColor-4: ".$this->ForeColor(4).";
				--ForeColor-5: ".$this->ForeColor(5).";
				--BackColor-0: ".$this->BackColor(0).";
				--BackColor-1: ".$this->BackColor(1).";
				--BackColor-2: ".$this->BackColor(2).";
				--BackColor-3: ".$this->BackColor(3).";
				--BackColor-4: ".$this->BackColor(4).";
				--BackColor-5: ".$this->BackColor(5).";
				--Font-0: ".$this->Font(0).";
				--Font-1: ".$this->Font(1).";
				--Font-2: ".$this->Font(2).";
				--Font-3: ".$this->Font(3).";
				--Font-4: ".$this->Font(4).";
				--Font-5: ".$this->Font(5).";
				--Size-0: ".$this->Size(0).";
				--Size-1: ".$this->Size(1).";
				--Size-2: ".$this->Size(2).";
				--Size-3: ".$this->Size(3).";
				--Size-4: ".$this->Size(4).";
				--Size-5: ".$this->Size(5).";
				--Shadow-0: ".$this->Shadow(0).";
				--Shadow-1: ".$this->Shadow(1).";
				--Shadow-2: ".$this->Shadow(2).";
				--Shadow-3: ".$this->Shadow(3).";
				--Shadow-4: ".$this->Shadow(4).";
				--Shadow-5: ".$this->Shadow(5).";
				--Border-0: ".$this->Border(0).";
				--Border-1: ".$this->Border(1).";
				--Border-2: ".$this->Border(2).";
				--Border-3: ".$this->Border(3).";
				--Border-4: ".$this->Border(4).";
				--Border-5: ".$this->Border(5).";
				--Radius-0: ".$this->Radius(0).";
				--Radius-1: ".$this->Radius(1).";
				--Radius-2: ".$this->Radius(2).";
				--Radius-3: ".$this->Radius(3).";
				--Radius-4: ".$this->Radius(4).";
				--Radius-5: ".$this->Radius(5).";
				--Transition-0: ".$this->Transition(0).";
				--Transition-1: ".$this->Transition(1).";
				--Transition-2: ".$this->Transition(2).";
				--Transition-3: ".$this->Transition(3).";
				--Transition-4: ".$this->Transition(4).";
				--Transition-5: ".$this->Transition(5).";
				--Overlay-0: \"".$this->Overlay(0)."\";
				--Overlay-1: \"".$this->Overlay(1)."\";
				--Overlay-2: \"".$this->Overlay(2)."\";
				--Overlay-3: \"".$this->Overlay(3)."\";
				--Overlay-4: \"".$this->Overlay(4)."\";
				--Overlay-5: \"".$this->Overlay(5)."\";
				--Pattern-0: \"".$this->Pattern(0)."\";
				--Pattern-1: \"".$this->Pattern(1)."\";
				--Pattern-2: \"".$this->Pattern(2)."\";
				--Pattern-3: \"".$this->Pattern(3)."\";
				--Pattern-4: \"".$this->Pattern(4)."\";
				--Pattern-5: \"".$this->Pattern(5)."\";
				--Url-Overlay-0: URL(\"".$this->Overlay(0)."\");
				--Url-Overlay-1: URL(\"".$this->Overlay(1)."\");
				--Url-Overlay-2: URL(\"".$this->Overlay(2)."\");
				--Url-Overlay-3: URL(\"".$this->Overlay(3)."\");
				--Url-Overlay-4: URL(\"".$this->Overlay(4)."\");
				--Url-Overlay-5: URL(\"".$this->Overlay(5)."\");
				--Url-Pattern-0: URL(\"".$this->Pattern(0)."\");
				--Url-Pattern-1: URL(\"".$this->Pattern(1)."\");
				--Url-Pattern-2: URL(\"".$this->Pattern(2)."\");
				--Url-Pattern-3: URL(\"".$this->Pattern(3)."\");
				--Url-Pattern-4: URL(\"".$this->Pattern(4)."\");
				--Url-Pattern-5: URL(\"".$this->Pattern(5)."\");

				--Owner: \"".__(\_::$INFO->Owner,true,false)."\";
				--FullOwner: \"".__(\_::$INFO->FullOwner,true,false)."\";
				--OwnerDescription: \"".__(\_::$INFO->OwnerDescription,true,false)."\";
				--Product: \"".__(\_::$INFO->Product,true,false)."\";
				--FullProduct: \"".__(\_::$INFO->FullProduct,true,false)."\";
				--Name: \"".__(\_::$INFO->Name,true,false)."\";
				--FullName: \"".__(\_::$INFO->FullName,true,false)."\";
				--Slogan: \"".__(\_::$INFO->Slogan,true,false)."\";
				--FullSlogan: \"".__(\_::$INFO->FullSlogan,true,false)."\";
				--Description: \"".__(\_::$INFO->Description,true,false)."\";
				--FullDescription: \"".__(\_::$INFO->FullDescription,true,false)."\";

				--Path: \"".\_::$INFO->Path."\";
				--HomePath: \"".\_::$INFO->HomePath."\";
				--LogoPath: \"".\MiMFa\Library\Local::GetUrl(\_::$INFO->LogoPath)."\";
				--FullLogoPath: \"".\MiMFa\Library\Local::GetUrl(\_::$INFO->FullLogoPath)."\";
				--BannerPath: \"".\MiMFa\Library\Local::GetUrl(\_::$INFO->BannerPath)."\";
				--FullBannerPath: \"".\MiMFa\Library\Local::GetUrl(\_::$INFO->FullBannerPath)."\";
				--DownloadPath: \"".\MiMFa\Library\Local::GetUrl(\_::$INFO->DownloadPath)."\";
				--WaitSymbolPath: \"".\MiMFa\Library\Local::GetUrl(\_::$INFO->WaitSymbolPath)."\";
				--ProcessSymbolPath: \"".\MiMFa\Library\Local::GetUrl(\_::$INFO->ProcessSymbolPath)."\";
				--ErrorSymbolPath: \"".\MiMFa\Library\Local::GetUrl(\_::$INFO->ErrorSymbolPath)."\";

				--Url-Path: URL(\"".\_::$INFO->Path."\");
				--Url-HomePath: URL(\"".\_::$INFO->HomePath."\");
				--Url-LogoPath: URL(\"".\MiMFa\Library\Local::GetUrl(\_::$INFO->LogoPath)."\");
				--Url-FullLogoPath: URL(\"".\MiMFa\Library\Local::GetUrl(\_::$INFO->FullLogoPath)."\");
				--Url-BannerPath: URL(\"".\MiMFa\Library\Local::GetUrl(\_::$INFO->BannerPath)."\");
				--Url-FullBannerPath: URL(\"".\MiMFa\Library\Local::GetUrl(\_::$INFO->FullBannerPath)."\");
				--Url-DownloadPath: URL(\"".\MiMFa\Library\Local::GetUrl(\_::$INFO->DownloadPath)."\");
				--Url-WaitSymbolPath: URL(\"".\MiMFa\Library\Local::GetUrl(\_::$INFO->WaitSymbolPath)."\");
				--Url-ProcessSymbolPath: URL(\"".\MiMFa\Library\Local::GetUrl(\_::$INFO->ProcessSymbolPath)."\");
				--Url-ErrorSymbolPath: URL(\"".\MiMFa\Library\Local::GetUrl(\_::$INFO->ErrorSymbolPath)."\");
			}
		</style>".join(PHP_EOL, $this->Initials);
	}
	public function GetMain():string|null{
		return join(PHP_EOL, $this->Mains);
	}
	public function GetFinal():string|null{
		return "<script>
			AOS.init({
				easing: 'ease-in-out-sine'
			});
			$(function(){
				Evaluate.URL();
			})();
		</script>".join(PHP_EOL, $this->Finals);
	}


	public function IsDark($color = null):bool|null{
		if(!isValid($color)) return $this->IsDark($this->BackColor(0)) === false;
		$l = strlen($color);
		$rgba = preg_find_all($l>6?'/\w\w/':'/\w/', $color);
        $sc = hexdec(getValid($rgba, 0, 0))+hexdec(getValid($rgba, 1, 0))+hexdec(getValid($rgba, 2, 0));
        if($sc<127) return true;
        elseif($sc>382) return false;
		return null;
	}
}
?>