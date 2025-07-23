<?php
use \MiMFa\Library\Html;
$fonts = [];
foreach (\_::$Front->FontPalette as $fp)
	$fonts = array_merge($fonts, preg_find_all("/(?<=['\"])[^'\"]+(?=['\"])/", $fp) ?? []);
$fdir = \_::$Address->AssetDirectory."font".DIRECTORY_SEPARATOR;
\_::$Front->Libraries[] = Html::Style(
	join(
		PHP_EOL,
		loop($fonts, function ($v) use($fdir) {
			$srcs = [];
			if($f = asset($fdir, $v, ".woff")) $srcs[] = "url('$f') format('woff')";
			if($f = asset($fdir, $v, ".woff2")) $srcs[] = "url('$f') format('woff2')";
			if($srcs) return "@font-face {font-family: '$v'; src: ".join(", ",$srcs).";}";
		})
	)
);