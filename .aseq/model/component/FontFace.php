<?php
use \MiMFa\Library\Struct;
$fonts = [];
foreach (\_::$Front->FontPalette as $fp)
	foreach (preg_find_all("/(?<=['\"])[\w\- ]+(?=['\"])/", $fp) ?? [] as $key => $value) 
		if($value) $fonts[strtolower($value)] = $value;

$fdir = \_::$Address->AssetDirectory."font".DIRECTORY_SEPARATOR;
\_::$Front->Libraries[] = Struct::Style(
	join(
		PHP_EOL,
		loop($fonts, function ($v) use($fdir) {
			$srcs = [];
			$lv = strtolower($v);
			if($f = asset($fdir, $v, ".woff")) $srcs[] = "url('$f') format('woff')";
			elseif($f = asset($fdir, $lv, ".woff")) $srcs[] = "url('$f') format('woff')";
			if($f = asset($fdir, $v, ".woff2")) $srcs[] = "url('$f') format('woff2')";
			elseif($f = asset($fdir, $lv, ".woff2")) $srcs[] = "url('$f') format('woff2')";
			if($f = asset($fdir, $v, ".ttf")) $srcs[] = "url('$f') format('ttf')";
			elseif($f = asset($fdir, $lv, ".ttf")) $srcs[] = "url('$f') format('truetype')";
			if($srcs) return "@font-face {font-family: '$v'; src: ".join(", ",$srcs)."; font-weight: normal; font-style: normal;}";
		})
	)
);