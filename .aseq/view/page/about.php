<?php

use MiMFa\Library\Convert;
use MiMFa\Library\Html;
use MiMFa\Library\Style;

module("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Image = \_::$Info->FullLogoPath;
//$module->Title = \_::$Info->FullName??"About Us";
$module->ContentTag = "h4";
$module->Content = __(\_::$Info->Slogan);
pod($module, $data);
$ops = \_::$Info->OwnerDescription?Convert::ToParagraphs(__(\_::$Info->OwnerDescription)):[];
$dps = \_::$Info->FullDescription?Convert::ToParagraphs(__(\_::$Info->FullDescription)):[];
$opsc = count($ops);
$dpsc = count($dps);
response(
	Html::Style("
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
	Html::Page(
		Html::Container(
			[
				[$module->ToString()],
				[Html::Paragraph(\_::$Info->Description)],
				loop($opsc, function($v,$k,$i) use($ops,$opsc) {return ($i+2)%2==0? array_slice($ops, $i, min(2, $opsc-$i)):null;}, false),
				loop($dpsc, function($v,$k,$i) use($dps,$dpsc) {return ($i+2)%2==0? array_slice($dps, $i, min(2, $dpsc-$i)):null;}, false),
				(\_::$Info->FullOwnerDescription?Convert::ToParagraphs(__(\_::$Info->FullOwnerDescription)):[]),
				[Html::$BreakLine],
				[Html::Paragraph(__(\_::$Info->FullSlogan), null,["class"=>"be center"])]
			]
		)
	)
);