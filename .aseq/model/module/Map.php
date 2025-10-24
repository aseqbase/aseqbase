<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
use MiMFa\Library\Script;
use MiMFa\Library\Style;

/**
 * An open-source JavaScript library designed for creating interactive, mobile-friendly maps. It's lightweight, weighing only about 42KB of gzipped JavaScript and 4KB of gzipped CSS, making it suitable for various applications. Developers, even those without a GIS background, can use Leaflet to display tiled web maps effectively. Its open-source nature fosters community contributions and customization, resulting in a versatile tool for web mapping.
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Map extends Module
{
	public $Tag = "div";
	public $Class = "be";
	public static $StyleSource = "https://unpkg.com/leaflet/dist/leaflet.css";
	public static $AlternativeStyleSource = "/asset/style/leaflet.css";
	public static $ScriptSource = "https://unpkg.com/leaflet/dist/leaflet.js";
	public static $AlternativeScriptSource = "/asset/script/leaflet.js";
	public static $PluginsStyleSources = [];
	public static $PluginsScriptSources = [];
	public $OtherScripts = [];
	public $Printable = true;
	public bool $Fill = true;
	public bool|null $Invert = null;

	/**
	 * The default zoom level
	 * @var int
	 */
	public int $Zoom = 17;
	/**
	 * The default begin [latitude, longitude]
	 */
	public array|null $Location = null;	
	
	/**
	 * A Script Function to handle the user location
	 * default is (map, e, err)=>err?null:L.circle(e.latlng, e.accuracy).addTo(map)
	 * or a function name to fill an input
	 * or leave an empty function to only active locate without any other action
	 * or leave null to deactive it
	 * @var string|null
	 */
	public string|null $LocateScriptFunction = "(map, e, err)=>err?null:L.circle(e.latlng, e.accuracy).addTo(map)";
	public $LocatePopup = null;
	
	/**
	 * A Script Function to handle user selected location
	 * for example (location, err)=>err?alert(err):alert('Selected Location: lat='+location[0]+',lng='+location[1])
	 * or a function name to fill an input
	 * or leave an empty function to only active selection without any other action
	 * or leave null to deactive it (default)
	 * @var string|null
	 */
	public string|null $SelectedLocationScriptFunction = null;
	public $SelectedLocationPopup = null;
	/**
	 * A Script Function to handle user selected address
	 * for example (map, e, err)=>err?alert(err):alert('Selected address(lat='+e.latlng.lat+',lng='+e.latlng.lng+'):\n' + e.address)
	 * or a function name to fill an input
	 * or leave an empty function to only active selection without any other action
	 * or leave null to deactive it (default)
	 * @var string|null
	 */
	public string|null $SelectedAddressScriptFunction = null;
	public $SelectedAddressPopup = null;
	
	/**
	 * The default Items [
	 * 		["Type"=>"marker", "Location"=>[latitude1, longitude1], "Popup"=>"It is here...", "Icon"=>"map-marker"],
	 * 		["Type"=>"marker", "Location"=>[latitude2, longitude2], "Popup"=>"It is here...", "Icon"=>"map-marker"]
	 * ]
	 */
	public array $Items = [];
	/**
	 * The default marker ["Location"=>[latitude1, longitude1], "Popup"=>"It is here...", "Icon"=>"map-marker", "Active"=>true]
	 */
	public array|null $Item = null;

	/**
	 * An array or a json string of the base layers
	 * @example string {
	 * 	'Light Mode': L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 20}),
	 * 	'Dark Mode': L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png', {attribution: '© Stadia Maps, © OpenMapTiles, © OpenStreetMap contributors', maxZoom: 20}),
	 * 	'Default Mode': L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '© OpenStreetMap contributors', maxZoom: 19}),
	 * 	'Terrain Mode': L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {attribution: 'Map data © OpenStreetMap contributors, SRTM | Tiles © OpenTopoMap', maxZoom: 18}),
	 * 	'Satellite Mode': L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {attribution: 'Tiles © Esri — Source: USGS, NOAA', maxZoom: 18})
	 * }
	 */
	public array|string|null $BaseLayers = null;

	/**
	 * An array or a json string of overlay layers
	 * @example string {
	 *	'Blue Circle': L.circle([31.8974, 54.3569], { radius: 500, color: 'blue' }),
	 *	'Triangle Polygon': L.polygon([[31.898, 54.356], [31.899, 54.357], [31.897, 54.358]])
	 * }
	 */
	public array|string|null $OverlayLayers = null;

	/**
	 * Indicate the position or leave null to do not display
	 * @options
	 * 'topleft' (default),
	 * 'topright',
	 * 'bottomleft',
	 * 'bottomright',
	 * null // To does not show
	 */
	public string|null $ZoomPosition = "topleft";
	/**
	 * Indicate the position or leave null to do not display
	 * @options
	 * 'topleft' (default),
	 * 'topright',
	 * 'bottomleft',
	 * 'bottomright',
	 * null // To does not show
	 */
	public string|null $FullScreenPosition = "topleft";
	/**
	 * Indicate the position or leave null to do not display
	 * @options
	 * 'topleft',
	 * 'topright',
	 * 'bottomleft',
	 * 'bottomright',
	 * null (default) // To does not show
	 */
	public string|null $PrintPosition = null;
	/**
	 * Indicate the position or leave null to do not display
	 * @options
	 * 'topleft',
	 * 'topright' (default),
	 * 'bottomleft',
	 * 'bottomright',
	 * null // To does not show
	 */
	public string|null $LayersPosition = "topright";
	/**
	 * Indicate the position or leave null to do not display
	 * @options
	 * 'topleft',
	 * 'topright' (default),
	 * 'bottomleft',
	 * 'bottomright',
	 * null // To does not show
	 */
	public string|null $AdjustmentPosition = "topright";
	/**
	 * Indicate the position or leave null to do not display
	 * @options
	 * 'topleft',
	 * 'topright',
	 * 'bottomleft' (default),
	 * 'bottomright',
	 * null // To does not show
	 */
	public string|null $CompassPosition = "bottomleft";
	/**
	 * Indicate the position or leave null to do not display
	 * @options
	 * 'topleft',
	 * 'topright',
	 * 'bottomleft',
	 * 'bottomright',
	 * null (default) // To does not show
	 */
	public string|null $DrawPosition = null;
	/**
	 * Indicate the position or leave null to do not display
	 * @options
	 * 'topleft',
	 * 'topright',
	 * 'bottomleft',
	 * 'bottomright' (default),
	 * null // To does not show
	 */
	public string|null $LocatePosition = "bottomright";
	/**
	 * Indicate the position or leave null to do not display
	 * @options
	 * 'topleft',
	 * 'topright',
	 * 'bottomleft',
	 * 'bottomright' (default),
	 * null // To does not show
	 */
	public string|null $RecenterPosition = "bottomright";
	/**
	 * Indicate the position or leave null to do not display
	 * @options
	 * 'topleft',
	 * 'topright',
	 * 'bottomleft',
	 * 'bottomright',
	 * null (default) // To does not show
	 */
	public string|null $PinMarkerPosition = null;
	/**
	 * Indicate the position or leave null to do not display
	 * @options
	 * 'topleft',
	 * 'topright',
	 * 'bottomleft' (default),
	 * 'bottomright',
	 * null // To does not show
	 */
	public string|null $ScalePosition = "bottomleft";
	/**
	 * Indicate the position or leave null to do not display
	 * @options
	 * 'topleft',
	 * 'topright',
	 * 'bottomleft',
	 * 'bottomright',
	 * null (default) // To does not show
	 */
	public string|null $MeasurePosition = null;

	/**
	 * The default error if unable to retrieve the location.
	 */
	public $LocationError = "Unable to retrieve your location. Please allow location access.";
	/**
	 * The default error if tiles failed to load.
	 */
	public $TilesError = "Tiles failed to load: Please check your internet connection.";


	public function __construct()
	{
		parent::__construct();
		$this->Id = "_" . getId();
		$this->Invert = $this->Invert ?? \_::$Front->SwitchMode;
	}

	public function BeforeHandle()
	{
		$this->Location = $this->Location ?? get($this->Item = $this->Item ?? first($this->Items), "Location") ?? [0, 0];
		self::$PluginsStyleSources[] = "https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css";
		self::$PluginsScriptSources[] = "https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js";
		if ($this->DrawPosition) {
			self::$PluginsStyleSources[] = "https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css";
			self::$PluginsScriptSources[] = "https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js";
		}
		if ($this->LocatePosition) {
			self::$PluginsStyleSources[] = "https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css";
			self::$PluginsScriptSources[] = "https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js";
		}
		if ($this->FullScreenPosition) {
			self::$PluginsStyleSources[] = "https://unpkg.com/leaflet.fullscreen/Control.FullScreen.css";
			self::$PluginsScriptSources[] = "https://unpkg.com/leaflet.fullscreen/Control.FullScreen.js";
		}
		if ($this->PrintPosition) {
			self::$PluginsScriptSources[] = "https://unpkg.com/leaflet.browser.print/dist/leaflet.browser.print.min.js";
		}
		if ($this->CompassPosition) {
			self::$PluginsStyleSources[] = "https://unpkg.com/leaflet-compass/dist/leaflet-compass.css";
			self::$PluginsScriptSources[] = "https://unpkg.com/leaflet-compass/dist/leaflet-compass.min.js";
		}
		if ($this->MeasurePosition) {
			self::$PluginsStyleSources[] = "https://unpkg.com/leaflet-measure/dist/leaflet-measure.css";
			self::$PluginsScriptSources[] = "https://unpkg.com/leaflet-measure/dist/leaflet-measure.js";
		}
		$res = (self::$StyleSource ? Html::Style(null, self::$StyleSource) : "") .
			(self::$ScriptSource ? Html::Script(null, self::$ScriptSource) : "") .
			(self::$AlternativeStyleSource ? Html::Style(null, self::$AlternativeStyleSource) : "") .
			(self::$AlternativeScriptSource ? Html::Script(null, self::$AlternativeScriptSource) : "") .
			(self::$PluginsStyleSources ? join(PHP_EOL, loop(self::$PluginsStyleSources, fn($v) => Html::Style(null, $v))) : "") .
			(self::$PluginsScriptSources ? join(PHP_EOL, loop(self::$PluginsScriptSources, fn($v) => Html::Script(null, $v))) : "");

		self::$StyleSource = null;
		self::$ScriptSource = null;
		self::$AlternativeStyleSource = null;
		self::$AlternativeScriptSource = null;
		self::$PluginsStyleSources = [];
		self::$PluginsScriptSources = [];

		return $res;
	}

	public function GetStyle() {
		return parent::GetStyle() . Html::Style(
			($this->Fill ? "
				*:has(.{$this->Name}){
				    display: flex;
    				flex-direction: column;
					padding: 0px;
					margin: 0px;
				}
				.{$this->Name}{
					position: relative;
					flex-grow: 1;
				}
			" : "") .
			".{$this->Name}{
				background-color: var(--back-color-input);
				color: var(--fore-color-input);
				display: flex;
				align-content: center;
				justify-content: center;
				align-items: center;
				text-align: center;
			}

			.{$this->Name} .custom-icon{
				background-color: transparent;
				border: none;
				outline: none;
				font-size: var(--size-0);
			}

			.{$this->Name} :is(.leaflet-popup-content-wrapper, .leaflet-popup-tip){
				background-color: var(--back-color);
				color: var(--fore-color);
				font-family: var(--font), var(--font-input), var(--font-output);
				font-size: var(--size-1);
				text-align: start;
				border: var(--size-2) var(--fore-color-input);
				border-radius: var(--radius-1);
				box-shadow: var(--shadow-4);
			}
			.{$this->Name} .leaflet-control{
				border: none;
				outline: none;
				background-color: transparent;
				color: var(--fore-color);
				font-size: var(--size-0);
			}
			.{$this->Name} .leaflet-control h3{
				margin: 0px;
				font-size: var(--size-1);
			}
			.{$this->Name} .leaflet-control.leaflet-control-attribution{
				opacity: 0.25;
				background-color: transparent;
			}
			.{$this->Name} .leaflet-control:not(.leaflet-control-attribution) :is(div, section, ul, li){
				background-color: var(--back-color-input);
				color: var(--fore-color-input);
				border: none;
				outline: none;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .leaflet-control:not(.leaflet-control-attribution)>:is(button, [role=button], a){
				background-color: var(--back-color-output);
				color: var(--fore-color-output);
				border: var(--border-1) var(--fore-color-output);
				border-radius: var(--radius-5);
				box-shadow: var(--shadow-3);
				outline: none;
				line-height: var(--size-5);
				min-width: var(--size-5);
				min-height: var(--size-5);
				text-align: center;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .leaflet-control:not(.leaflet-control-attribution)>:is(button, [role=button], a):hover{
				background-color: var(--back-color-special-output);
				color: var(--fore-color-special-output);
				border-color: var(--fore-color-special-output);
				text-decoration: none;
				" . Style::UniversalProperty("transition", "var(--transition-1)") . "
			}
			.{$this->Name} .leaflet-control-layers-toggle{
				background-color: var(--back-color-output);
				color: var(--fore-color-output);
				border-color: var(--fore-color-special-output);
			}
			.{$this->Name} .leaflet-bar-part>.button-state{
				line-height: var(--size-5);
			}
			.{$this->Name} .leaflet-control-layers-list {
				box-shadow: var(--shadow-5);
				border-radius: var(--radius-1);
				border: var(--border-1) var(--fore-color-output);
				line-height: 150%;
				text-align: start;
				padding: var(--size-0);
			}
		"
		);
	}

	public function Get()
	{
		return Html::Division(parent::Get(), ["class" => "top"]) .
			Html::Division(
				Html::Division([
					($this->MyLocationButton ? Html::Button($this->MyLocationButton, "{$this->Name}.locate({ setView: true, maxZoom: $this->Zoom })", ["class" => "my-location"]) : ""),
				], attributes: ["class" => "control-box"]) .
				Html::Division("", ["class" => "message"])
				,
				["class" => "bottom"]
			);
	}

	public function GetScript()
	{
		return Html::Script(join(PHP_EOL, [
			$this->InitializeScript(),
			...$this->OtherScripts,
			$this->Items_InitializeScript(),
			$this->Events_InitializeScript(),
			$this->Controls_InitializeScript()
		]));
	}
	public function InitializeScript()
	{
		return "{$this->Name}_Items = new Map();
		const {$this->Name} = L.map('{$this->Id}', {zoomControl: false}).setView(" . Script::Convert($this->Location) . ", {$this->Zoom});
			defaultTailsFormat = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
			defaultTailsAttrs = {maxZoom: 19};
			L.tileLayer(defaultTailsFormat, defaultTailsAttrs).addTo({$this->Name});
		function {$this->Name}_MapColor(adjust=null,invert=null){
			adjustattach = 'adjust';
			invertattach = 'invert';
			mc = document.querySelector('.{$this->Name} .leaflet-pane .leaflet-tile-pane');
			if (!adjust && !invert) mc.classList.remove('be');
			else mc.classList.add('be');
			if (adjust) mc.classList.add(adjustattach);
			else mc.classList.remove(adjustattach);
			if (invert) mc.classList.add(invertattach);
			else mc.classList.remove(invertattach);
		}
		".($this->Invert?"{$this->Name}_MapColor(false, true);":"");
	}
	public function Events_InitializeScript()
	{
		return join(PHP_EOL, [
			($this->LocatePopup || $this->LocateScriptFunction? "{$this->Name}.on('locationfound', function (e, err) {
				".($this->LocateScriptFunction?"return ($this->LocateScriptFunction)({$this->Name}, e, null)":($this->LocatePopup?"L.circle(e.latlng, e.accuracy).addTo({$this->Name})":"")).
				($this->LocatePopup?"?.bindPopup(".Script::Convert(__($this->LocatePopup)).").openPopup();":";")."
			});":""),
			($this->SelectedLocationPopup || $this->SelectedAddressPopup || $this->SelectedLocationScriptFunction || $this->SelectedAddressScriptFunction? "
			let marker;
			{$this->Name}.on('click', function (e, err) {
				const {lat,lng} = e.latlng;
				if (marker) marker.setLatLng(e.latlng);
				else marker = L.marker(e.latlng).addTo({$this->Name});
				".($this->SelectedLocationPopup?"marker.bindPopup(".Script::Convert(__($this->SelectedLocationPopup)).").openPopup();":"")."
				".($this->SelectedLocationScriptFunction?"($this->SelectedLocationScriptFunction)({$this->Name}, e, err);":"")."
				".($this->SelectedAddressPopup || $this->SelectedAddressScriptFunction?"fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=\${lat}&lon=\${lng}".(\_::$Back->AllowTranslate?"&accept-language=".\_::$Back->Translate->Language:"")."`)
				.then(response => response.json())
				.then(data => {
					err = null;
                    e.address = null;
					if(data.display_name) e.address = data.display_name;
					else err = 'Address not found';
					e.metadata = data;
					".($this->SelectedAddressPopup?"marker.bindPopup(".Script::Convert(__($this->SelectedAddressPopup)).").openPopup();":"")."
					".($this->SelectedAddressScriptFunction?"($this->SelectedAddressScriptFunction)({$this->Name}, e, err);":"")."
				})
				.catch(err => {
					".($this->SelectedAddressScriptFunction?"($this->SelectedAddressScriptFunction)({$this->Name}, e, err);":"")."
				});":"")."

			});":""),
			($this->LocationError ? "{$this->Name}.on('locationerror', function () {" . $this->ErrorScript($this->LocationError) . "});" : ""),
			($this->TilesError ? "{$this->Name}.on('tileerror', function () {" . $this->ErrorScript($this->TilesError) . "});" : "")
		]);
	}
	public function Controls_InitializeScript()
	{
		return join(PHP_EOL, [
			($this->CompassPosition ? "L.control.compass({ position:" . Script::Convert($this->CompassPosition) . ", autoActive: true}).addTo({$this->Name});" : ""),
			($this->RecenterPosition ? "
			L.easyButton('fa-home', function(btn, map){
					map.setView(" . Script::Convert($this->Location) . ", $this->Zoom);
				}, {position:" . Script::Convert($this->RecenterPosition) . "}
			).addTo({$this->Name});
			" : ";"),
			($this->LocatePosition ? "L.control.locate({
				position: " . (Script::Convert($this->LocatePosition)) . ",
				drawCircle: false,
				follow: true,
				setView: true,
				keepCurrentZoomLevel: false,
				icon: 'icon fa fa-crosshairs',
				iconLoading: 'icon fa fa-spinner fa-spin',
				// strings: {
				// 	title: 'Show me where I am',
				// 	popup: 'You are within {distance} {unit} from this point'
				// },
				// locateOptions: {
				// 	enableHighAccuracy: true,
				// 	maxZoom: $this->Zoom
				// }
			}).addTo({$this->Name});" : ""),
			($this->FullScreenPosition ? "L.control.fullscreen({ content: '<i class=\"fas fa-expand fa-lg\"></i>', position:" . Script::Convert($this->FullScreenPosition) . "}).addTo({$this->Name});" : ""),
			($this->ZoomPosition ? "L.control.zoom({ position:" . Script::Convert($this->ZoomPosition) . "}).addTo({$this->Name});" : ""),
			($this->LayersPosition ? "L.control.layers(" . (is_null($this->BaseLayers) ? "{
					'Default Mode': L.tileLayer(defaultTailsFormat, defaultTailsAttrs),
					" . ($this->Invert ? "
						'Light Mode': L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png', {attribution: '© Stadia Maps, © OpenMapTiles, © OpenStreetMap contributors', maxZoom: 20}),
						'Dark Mode': L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '© OpenStreetMap contributors', maxZoom: 20}),
					" : "
						'Dark Mode': L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png', {attribution: '© Stadia Maps, © OpenMapTiles, © OpenStreetMap contributors', maxZoom: 20}),
						'Light Mode': L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '© OpenStreetMap contributors', maxZoom: 20}),
					") . "'Terrain Mode': L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {attribution: 'Map data © OpenStreetMap contributors, SRTM | Tiles © OpenTopoMap', maxZoom: 18}),
					'Satellite Mode': L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {attribution: 'Tiles © Esri — Source: USGS, NOAA', maxZoom: 18})
				}" : (is_string($this->BaseLayers) ? $this->BaseLayers : Script::Convert($this->BaseLayers))) . ",
				" . (is_string($this->OverlayLayers) ? $this->OverlayLayers : Script::Convert($this->OverlayLayers)) . ",
				{ icon: 'fa-eye', position:" . Script::Convert($this->LayersPosition) . "}).addTo({$this->Name});" : ""),
			($this->AdjustmentPosition ? "L.easyButton('fa-adjust', function(btn, map){
					mc = document.querySelector('.{$this->Name} .leaflet-pane .leaflet-tile-pane');
					{$this->Name}_MapColor(false, !mc.classList.contains('invert'));
				}, {position:" . Script::Convert($this->AdjustmentPosition) . "}).addTo({$this->Name});" : ""),
			($this->MeasurePosition ? "L.control.measure({position:" . Script::Convert($this->MeasurePosition) . "}).addTo({$this->Name});" : ""),
			($this->PinMarkerPosition ? "L.easyButton('fa-map-pin', function(btn, map){L.marker(map.getCenter()).addTo({$this->Name})}, {position:" . Script::Convert($this->PinMarkerPosition) . "}).addTo({$this->Name});" : ""),
			($this->PrintPosition ? "L.control.browserPrint({position:" . Script::Convert($this->PrintPosition) . "}).addTo({$this->Name});" : ""),
			($this->DrawPosition ? "{$this->Name}.addControl(new L.Control.Draw({position:" . Script::Convert($this->DrawPosition) . "}));" : ""),
			($this->ScalePosition ? "L.control.scale({ position:" . Script::Convert($this->ScalePosition) . "}).addTo({$this->Name});" : "")
		]);
	}
	public function Items_InitializeScript()
	{
		return join(PHP_EOL, [
			$this->Item ? $this->AddItemScript($this->Item["Type"] ?? "marker", $this->Item["Location"] ?? $this->Location, $this->Item["Popup"] ?? null, $this->Item["Active"] ?? true, $this->Item["Attributes"]??[]) : "",
			...loop(
				$this->Items,
				fn($v, $k) => isset($v["Location"])?$this->AddItemScript($v["Type"]??"marker", $v["Location"], $v["Popup"] ?? null, $v["Active"] ?? false, $v["Attributes"]??[]):null
			)
		]);
	}

	public static function AttributesScript(string|array $attributes){
		return (is_array($attributes)? 
			("{".join(", ", loop($attributes, fn($v, $k) => strToCamel($k).":" . Script::Convert($v)))."}")
		: Script::Convert($attributes?:"{}"));
	}

	/**
	 * To locate map to a location
	 * @param array|string|null $location The source latitude and longitude of the thing [latitude, longitude]
	 * @param bool $animate Leave true to fly to the location or false otherwise
	 * @return string
	 */
	public function Locate($location = null, $zoom = null, $animate = true)
	{
		return $this->OtherScripts[] = $this->LocateScript($location, $zoom, $animate).";";
	}
	/**
	 * Get script to locate map to a location
	 * @param array|string|null $location The source latitude and longitude of the thing [latitude, longitude]
	 * @param bool $animate Leave true to fly to the location or false otherwise
	 * @return string
	 */
	public function LocateScript($location = null, $zoom = null, $animate = true)
	{
		return is_null($location)?"{$this->Name}.locate(".($zoom??$this->Zoom).")":"{$this->Name}.".($animate?"flyTo":"setView")."(". Script::Convert($location) . ", ".($zoom??$this->Zoom).")";
	}

	/**
	 * To add a Control Plugin on the map
	 * @param string $name The name of plugin (draw, locate, etc.)
	 * @param string $position Indicate the position of plugin to locate
	 * 'topleft' (default),
	 * 'topright',
	 * 'bottomleft',
	 * 'bottomright'
	 * @param array|string $attributes
	 * @return string
	 */
	public function AddPlugin($name, $position = "topleft", $attributes = [], $stylesLibrary = null, $scriptsLibrary = null)
	{
		return $this->OtherScripts[] = $this->AddPluginScript(
			name: $name,
			position: $position,
			attributes: $attributes,
			stylesLibrary: $stylesLibrary,
			scriptsLibrary: $scriptsLibrary
		).";";
	}
	/**
	 * To get a suitable script to add a Control Plugin on the map
	 * @param string $name The name of plugin (draw, locate, etc.)
	 * @param string $position Indicate the position of plugin to locate
	 * 'topleft' (default),
	 * 'topright',
	 * 'bottomleft',
	 * 'bottomright'
	 * @param array|string $attributes
	 * @return string
	 */
	public function AddPluginScript($name, $position = "topleft", $attributes = [], $stylesLibrary = null, $scriptsLibrary = null)
	{
		$attributes["position"] = Script::Convert($position);
		if ($stylesLibrary)
			array_push(self::$PluginsStyleSources, ...(is_array($stylesLibrary) ? $stylesLibrary : [$stylesLibrary]));
		if ($scriptsLibrary)
			array_push(self::$PluginsScriptSources, ...(is_array($scriptsLibrary) ? $scriptsLibrary : [$scriptsLibrary]));
		return "L.control.$name(" . self::AttributesScript($attributes) . ").addTo({$this->Name})";
	}
	/**
	 * To add a button on the map
	 * @param string $icon The font-awesome icon
	 * @param string|callable|mixed $action Indicate the (btn,map)=>{} template action to execute when the button click
	 * @param string $position Indicate the position
	 * 'topleft' (default),
	 * 'topright',
	 * 'bottomleft',
	 * 'bottomright'
	 * @param array|string $attributes
	 * @return string
	 */
	public function AddButton($icon, $action, $position = "topleft", $attributes = [])
	{
		return $this->OtherScripts[] = $this->AddButtonScript(
			icon: $icon,
			action: $action,
			position: $position,
			attributes: $attributes
		).";";
	}
	/**
	 * To get a suitable script to add a button on the map
	 * @param string $icon The font-awesome icon
	 * @param string|callable|mixed $action Indicate the (btn,map)=>{} template action to execute when the button click
	 * @param string $position Indicate the position
	 * 'topleft' (default),
	 * 'topright',
	 * 'bottomleft',
	 * 'bottomright'
	 * @param array $attributes
	 * @return string
	 */
	public function AddButtonScript($icon, $action, $position = "topleft", $attributes = [])
	{
		$attributes["position"] = $position;
		return "L.easyButton(" . Script::Convert($icon) . ", " . (is_string($action) ? $action : Script::Convert($action)) . ", " . self::AttributesScript($attributes) . ").addTo({$this->Name})";
	}
	/**
	 * To register a new icon for the map
	 * @param string $name The icon variable name
	 * @param string $icon The font-awesome icon
	 * @param string|callable|mixed|null $action Indicate the action to execute when the icon click
	 * @param array|string $attributes
	 * @return string
	 */
	public function DefineIcon($name, $icon = "map-marker", $action = null, $attributes = [])
	{
		return $this->OtherScripts[] = $this->DefineIconScript(
			name: $name,
			icon: $icon,
			action: $action,
			attributes: $attributes
		).";";
	}
	/**
	 * To get a suitable script to Define an icon for the map
	 * @param string $name The icon variable name
	 * @param string $icon The font-awesome icon
	 * @param string|callable|mixed|null $action Indicate the action to execute when the icon click
	 * @param array|string $attributes
	 * @return string
	 */
	public function DefineIconScript($name, $icon = "map-marker", $action=null, $attributes = [])
	{
		return "const $name = L.divIcon({
			html: ".(Script::Convert(Html::Icon($icon, $action, ["class"=>"fa-2x"], $attributes))).",
			className: 'custom-icon'
		})";
	}
	
	/**
	 * To get a suitable key for a layer
	 * @param string $type The type of Item to pin for example (marker, circle, polygon, etc.)
	 * @param array|string|null $location The source latitude and longitude of the thing [latitude, longitude]
	 * @return string
	 */
	public function CreateKey($type = "marker", $location = null)
	{
		return "$type:".(is_array($location)?"{$location[0]},{$location[1]}":$location);
	}
	/**
	 * To get a suitable key for a layer
	 * @param string $type The type of Item to pin for example (marker, circle, polygon, etc.)
	 * @param array|string|null $location The source latitude and longitude of the thing [latitude, longitude]
	 * @return string
	 */
	public function CreateKeyScript($type = "marker", $location = null)
	{
		return Script::Convert("$type:".(is_array($location)?"{$location[0]},{$location[1]}":$location));
	}
	
	/**
	 * To get something from the map
	 * @param string $type The type of pinned Item for example (marker, circle, polygon, etc.)
	 * @param array|string|null $location The source latitude and longitude of the thing [latitude, longitude]
	 * @return string
	 */
	public function GetItem($type = "marker", $location = null)
	{
		$key = $this->CreateKey($type, $location);
		return take($this->Items, function($v) use($key){ return $key === $this->CreateKey($v["Type"]??"marker", $v["Location"]);});
	}
	/**
	 * To get a suitable script get something from the map
	 * @param string $type The type of pinned Item for example (marker, circle, polygon, etc.)
	 * @param array|string|null $location The source latitude and longitude of the thing [latitude, longitude]
	 * @return string
	 */
	public function GetItemScript($type = "marker", $location = null)
	{
		return "{$this->Name}_Items.get(".$this->CreateKeyScript($type, $location).")";
	}
	/**
	 * To set something to the map
	 * @param string $type The type of Item to pin for example (marker, circle, polygon, etc.)
	 * @param array|string|null $location The source latitude and longitude of the thing [latitude, longitude]
	 * @param mixed $popup
	 * @param bool|string|null $active
	 * @param array|string $attributes
	 */
	public function SetItem($type = "marker", $location = null, $popup = null, $active = false, $attributes = [])
	{
		$key = $this->CreateKey($type, $location);
		filter($this->Items, function($v) use($key){ return $key === $this->CreateKey($v["Type"]??"marker", $v["Location"]);});
		return $this->Items[] = ["Type"=>$type??"marker", "Location"=>$location, "Popup"=>$popup, "Active"=>$active, "Attributes"=>$attributes];
	}
	/**
	 * To get a suitable script to set something to the map
	 * @param string $type The type of Item to pin for example (marker, circle, polygon, etc.)
	 * @param array|string|null $location The source latitude and longitude of the thing [latitude, longitude]
	 * @param mixed $popup
	 * @param bool|string|null $active
	 * @param array|string $attributes
	 * @return string
	 */
	public function SetItemScript($type = "marker", $location = null, $popup = null, $active = false, $attributes = [])
	{
		return "{
			let key = ".$this->CreateKeyScript($type, $location).";
			let layer = {$this->Name}_Items.get(key);
			if(layer) {
				{$this->Name}.removeLayer(layer);
				{$this->Name}_Items.delete(key);
			} else layer = L.$type(".(is_null($location) ? "{$this->Name}.getCenter()" : Script::Convert($location)) . ", " . self::AttributesScript($attributes) . ");
			let la = layer.addTo({$this->Name});
			let popup = ".Script::Convert(__($popup)).";
			if(popup) {
				let pp = la.bindPopup(popup);
				let activePopup = ".Script::Convert($active).";
				if(activePopup) pp.openPopup();
			}
			{$this->Name}_Items.set(key, layer);
		}";
	}
	/**
	 * To pin something to the map
	 * @param string $type The type of Item to pin for example (marker, circle, polygon, etc.)
	 * @param array|string|null $location The source latitude and longitude of the thing [latitude, longitude]
	 * @param mixed $popup
	 * @param bool|string|null $active
	 * @param array|string $attributes
	 */
	public function AddItem($type = "marker", $location = null, $popup = null, $active = false, $attributes = [])
	{
		return $this->Items[] = ["Type"=>$type??"marker", "Location"=>$location, "Popup"=>$popup, "Active"=>$active, "Attributes"=>$attributes];
	}
	/**
	 * To get a suitable script to pin something to the map
	 * @param string $type The type of Item to pin for example (marker, circle, polygon, etc.)
	 * @param array|string|null $location The source latitude and longitude of the thing [latitude, longitude]
	 * @param mixed $popup
	 * @param bool|string|null $active
	 * @param array|string $attributes
	 * @return string
	 */
	public function AddItemScript($type = "marker", $location = null, $popup = null, $active = false, $attributes = [])
	{
		return "{
			let key = ".$this->CreateKeyScript($type, $location).";
			let layer = {$this->Name}_Items.get(key);
			if(layer) {
				{$this->Name}.removeLayer(layer);
				{$this->Name}_Items.delete(key);
			}
			layer = L.$type(".(is_null($location) ? "{$this->Name}.getCenter()" : Script::Convert($location)) . ", " . self::AttributesScript($attributes) . ");
			let la = layer.addTo({$this->Name});
			let popup = ".Script::Convert(__($popup)).";
			if(popup) {
				let pp = la.bindPopup(popup);
				let activePopup = ".Script::Convert($active).";
				if(activePopup) pp.openPopup();
			}
			{$this->Name}_Items.set(key, layer);
		}";
	}
	/**
	 * To delete something from the map
	 * @param string $type The type of pinned Item for example (marker, circle, polygon, etc.)
	 * @param array|string|null $location The source latitude and longitude of the thing [latitude, longitude]
	 * @return string
	 */
	public function RemoveItem($type = "marker", $location = null)
	{
		$key = $this->CreateKey($type, $location);
		filter($this->Items, function($v) use($key){ return $key === $this->CreateKey($v["Type"]??"marker", $v["Location"]);});
		return $this->OtherScripts[] = $this->RemoveItemScript(
			type: $type,
			location: $location
		);
	}
	/**
	 * To get a suitable script to delete something from the map
	 * @param string $type The type of pinned Item for example (marker, circle, polygon, etc.)
	 * @param array|string|null $location The source latitude and longitude of the thing [latitude, longitude]
	 * @return string
	 */
	public function RemoveItemScript($type = "marker", $location = null)
	{
		return "{
			let key = ".$this->CreateKeyScript($type, $location).";
			let layer = {$this->Name}_Items.get(key);
			if(layer) {
				{$this->Name}.removeLayer(layer);
				{$this->Name}_Items.delete(key);
			}
		}";
	}

	public function MessageScript($message = null)
	{
		return "document.querySelector('.{$this->Name} .message').innerHTML = Html.message(" . Script::Convert(__($message)) . ");";
	}
	public function SuccessScript($message = null)
	{
		return "document.querySelector('.{$this->Name} .message').innerHTML = Html.success(" . Script::Convert(__($message)) . ");";
	}
	public function ErrorScript($message = null)
	{
		return "document.querySelector('.{$this->Name} .message').innerHTML = Html.error(" . Script::Convert(__($message)) . ");";
	}
}