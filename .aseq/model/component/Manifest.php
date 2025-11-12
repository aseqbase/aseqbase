<?php namespace MiMFa\Component;
use MiMFa\Library\Convert;
use MiMFa\Library\Local;

class Manifest
{
	public static function Store($data)
	{
		$path = \_::$Address->ScriptDirectory . "manifest.json";
		if (!isEmpty($data)) Local::WriteText($path, Convert::ToJson($data), true);
		return $path;
	}
}

// \_::$Front->Libraries[] = Struct::Relation(
// 	"manifest",
// 	getFullUrl(Manifest::Store([
// 		'short_name' => 'Digikala',
// 		'name' => 'فروشگاه اینترنتی دیجی کالا',
// 		'icons' => [
// 			[
// 				'src' => 'favicon.ico',
// 				'sizes' => '96x96 32x32 16x16',
// 				'type' => 'image/x-icon'
// 			],
// 			[
// 				'src' => 'logo192.png',
// 				'type' => 'image/png',
// 				'sizes' => '192x192'
// 			],
// 			[
// 				'src' => 'logo512.png',
// 				'type' => 'image/png',
// 				'sizes' => '512x512'
// 			],
// 			[
// 				'src' => 'pwa-icon-180.png',
// 				'type' => 'image/png',
// 				'sizes' => '180x180',
// 				'purpose' => 'any maskable'
// 			]
// 		],
// 		'start_url' => '/',
// 		'display' => 'standalone',
// 		'theme_color' => '#000000',
// 		'background_color' => '#ffffff'
// 	]))
// );