<?php

use MiMFa\Library\Convert;
use MiMFa\Library\Struct;
use MiMFa\Library\Style;

module("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Image = \_::$Front->FullLogoPath;
//$module->Title = \_::$Front->FullName??"About Us";
$module->ContentTag = "h4";
$module->Content = __(\_::$Front->Slogan);
pod($module, $data);
$ops = \_::$Front->OwnerDescription?Convert::ToParagraphs(__(\_::$Front->OwnerDescription)):[];
$dps = \_::$Front->FullDescription?Convert::ToParagraphs(__(\_::$Front->FullDescription)):[];
$opsc = count($ops);
$dpsc = count($dps);
response(
	Struct::Style("
		.{$module->Name} .content{
			text-align: center;
		}
		.{$module->Name} .image{
			padding: var(--size-5);
			".Style::UniversalProperty("filter", "drop-shadow(
					-8px -4px 0 var(--back-color-special)
				)")."
		}
		.{$module->Name} .image img{
			max-width: 25vmax;
		}
		.page *{
			line-height: 2em;
		}
	").
	Struct::Page(
		Struct::Container(
			[
				[$module->ToString()],
				[Struct::Paragraph(\_::$Front->Description)],
				loop($opsc, function($v,$k,$i) use($ops,$opsc) {return ($i+2)%2==0? array_slice($ops, $i, min(2, $opsc-$i)):null;}, false),
				loop($dpsc, function($v,$k,$i) use($dps,$dpsc) {return ($i+2)%2==0? array_slice($dps, $i, min(2, $dpsc-$i)):null;}, false),
				(\_::$Front->FullOwnerDescription?Convert::ToParagraphs(__(\_::$Front->FullOwnerDescription)):[]),
				[Struct::$BreakLine],
				[Struct::Paragraph(__(\_::$Front->FullSlogan), null,["class"=>"be center"])]
			]
		)
	)
);