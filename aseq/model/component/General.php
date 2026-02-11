<?php
namespace MiMFa\Component;
use MiMFa\Library\Struct;
use MiMFa\Library\Storage;

class GeneralStyle
{
	public static function Variables($rootSelector = ":root")
	{
		return "
		$rootSelector{
			--dir: " . (\_::$Front->Translate->Direction ?? \_::$Front->DefaultDirection) . ";
			--lang: " . (\_::$Front->Translate->Language ?? \_::$Front->DefaultLanguage) . ";
			--color-black: " . \_::$Front->Color(0) . ";
			--color-red: " . \_::$Front->Color(1) . ";
			--color-green: " . \_::$Front->Color(2) . ";
			--color-blue: " . \_::$Front->Color(3) . ";
			--color-yellow: " . \_::$Front->Color(4) . ";
			--color-cyan: " . \_::$Front->Color(5) . ";
			--color-magenta: " . \_::$Front->Color(6) . ";
			--color-white: " . \_::$Front->Color(7) . ";
			--color-gray: #888888;
			" .
			GeneralStyle::MakeVariables(\_::$Front->ColorPalette, "color", 8) . "
			--fore-color: " . \_::$Front->ForeColor(0) . ";
			--fore-color-input: " . \_::$Front->ForeColor(1) . ";
			--fore-color-output: " . \_::$Front->ForeColor(2) . ";
			--fore-color-special: " . \_::$Front->ForeColor(3) . ";
			--fore-color-special-input: " . \_::$Front->ForeColor(4) . ";
			--fore-color-special-output: " . \_::$Front->ForeColor(5) . ";
			--back-color: " . \_::$Front->BackColor(0) . ";
			--back-color-input: " . \_::$Front->BackColor(1) . ";
			--back-color-output: " . \_::$Front->BackColor(2) . ";
			--back-color-special: " . \_::$Front->BackColor(3) . ";
			--back-color-special-input: " . \_::$Front->BackColor(4) . ";
			--back-color-special-output: " . \_::$Front->BackColor(5) . ";
			" . (
			\_::$Front->SwitchMode ?
			GeneralStyle::MakeVariables(\_::$Front->BackColorPalette, "fore-color") .
			GeneralStyle::MakeVariables(\_::$Front->ForeColorPalette, "back-color")
			:
			GeneralStyle::MakeVariables(\_::$Front->ForeColorPalette, "fore-color") .
			GeneralStyle::MakeVariables(\_::$Front->BackColorPalette, "back-color")
		) . "
			--font: " . \_::$Front->Font(0) . ";
			--font-input: " . \_::$Front->Font(1) . ";
			--font-output: " . \_::$Front->Font(2) . ";
			--font-special: " . \_::$Front->Font(3) . ";
			--font-special-input: " . \_::$Front->Font(4) . ";
			--font-special-output: " . \_::$Front->Font(5) . ";
			" .
			GeneralStyle::MakeVariables(\_::$Front->SizePalette, "size") .
			GeneralStyle::MakeVariables(\_::$Front->ShadowPalette, "shadow") .
			GeneralStyle::MakeVariables(\_::$Front->BorderPalette, "border") .
			GeneralStyle::MakeVariables(\_::$Front->RadiusPalette, "radius") .
			GeneralStyle::MakeVariables(\_::$Front->TransitionPalette, "transition") . "
			--animation-speed: " . \_::$Front->AnimationSpeed . "ms;
			" .
			GeneralStyle::MakeVariables(\_::$Front->OverlayPalette, "overlay", handle: fn($v) => "\"" . Storage::GetUrl($v) . "\"") .
			GeneralStyle::MakeVariables(\_::$Front->PatternPalette, "pattern", handle: fn($v) => "\"" . Storage::GetUrl($v) . "\"") .
			GeneralStyle::MakeVariables(\_::$Front->OverlayPalette, "overlay-url", handle: fn($v) => "URL(\"" . Storage::GetUrl($v) . "\")") .
			GeneralStyle::MakeVariables(\_::$Front->PatternPalette, "pattern-url", handle: fn($v) => "URL(\"" . Storage::GetUrl($v) . "\")") . "
			--owner: \"" . __(\_::$Front->Owner, true, false) . "\";
			--name: \"" . __(\_::$Front->Name, true, false) . "\";
			--slogan: \"" . __(\_::$Front->Slogan, true, false) . "\";

			--path: \"" . \_::$Front->Path . "\";
			--home-path: \"" . \_::$Front->HomePath . "\";
			--logo-path: \"" . asset(\_::$Front->LogoPath) . "\";
			--full-logo-path: \"" . asset(\_::$Front->FullLogoPath) . "\";
			--banner-path: \"" . asset(\_::$Front->BannerPath) . "\";
			--full-banner-path: \"" . asset(\_::$Front->FullBannerPath) . "\";
			--wait-symbol-path: \"" . asset(\_::$Front->WaitSymbolPath) . "\";
			--process-symbol-path: \"" . asset(\_::$Front->ProcessSymbolPath) . "\";
			--error-symbol-path: \"" . asset(\_::$Front->ErrorSymbolPath) . "\";

			--logo-path-url: URL(\"" . asset(\_::$Front->LogoPath) . "\");
			--full-logo-path-url: URL(\"" . asset(\_::$Front->FullLogoPath) . "\");
			--banner-path-url: URL(\"" . asset(\_::$Front->BannerPath) . "\");
			--full-banner-path-url: URL(\"" . asset(\_::$Front->FullBannerPath) . "\");
			--wait-symbol-path-url: URL(\"" . asset(\_::$Front->WaitSymbolPath) . "\");
			--process-symbol-path-url: URL(\"" . asset(\_::$Front->ProcessSymbolPath) . "\");
			--error-symbol-path-url: URL(\"" . asset(\_::$Front->ErrorSymbolPath) . "\");
		}";
	}
	public static function MakeVariables($palette, $name, $minimum = 6, callable|null $handle = null)
	{
		$handle = $handle ?? fn($v) => $v;
		$count = count($palette);
		$sep = "\r\t\t\t";
		return
			$sep .
			"--$name-min: " . $handle(first($palette)) . ";$sep" .
			($minimum ? join("", loop($minimum, fn($v, $k, $i) => "--$name-$i: " . $handle(\_::$Front->LoopPalette($palette, $i)) . ";$sep")) : "") .
			($count > $minimum ? join("", loop(array_slice($palette, $minimum), fn($v, $k, $i) => is_int($k) ? ("--$name-" . ($k + $minimum) . ": " . $handle($v) . ";$sep") : ("--$name-$k: " . $handle($v) . ";$sep"))) : "") .
			"--$name-max: " . $handle(last($palette)) . ";$sep";
	}
	public static function SwitchVariables($rootSelector = ":root")
	{
		return "
		$rootSelector{
			--fore-color: " . \_::$Front->BackColor(0) . ";
			--fore-color-input: " . \_::$Front->BackColor(1) . ";
			--fore-color-output: " . \_::$Front->BackColor(2) . ";
			--fore-color-special: " . \_::$Front->BackColor(3) . ";
			--fore-color-special-input: " . \_::$Front->BackColor(4) . ";
			--fore-color-special-output: " . \_::$Front->BackColor(5) . ";
			--back-color: " . \_::$Front->ForeColor(0) . ";
			--back-color-input: " . \_::$Front->ForeColor(1) . ";
			--back-color-output: " . \_::$Front->ForeColor(2) . ";
			--back-color-special: " . \_::$Front->ForeColor(3) . ";
			--back-color-special-input: " . \_::$Front->ForeColor(4) . ";
			--back-color-special-output: " . \_::$Front->ForeColor(5) . ";
		}";
	}
}
\_::$Front->Libraries[] = Struct::Style(GeneralStyle::Variables());
\_::$Front->Libraries[] = Struct::Style(null, asset(\_::$Address->GlobalStyleDirectory, 'general.css', optimize: true));
\_::$Front->Libraries[] = Struct::Script(null, asset(\_::$Address->GlobalScriptDirectory, 'general.js', optimize: true));