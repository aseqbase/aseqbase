<?php

use MiMFa\Library\Convert;
use MiMFa\Library\Html;

module("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Image = \_::$Info->FullLogoPath;
//$module->Title = \_::$Info->FullName??"About Us";
$module->ContentTag = "h4";
$module->Content = __(\_::$Info->Slogan);
swap($module, $data);
$ops = \_::$Info->OwnerDescription?Convert::ToParagraphs(__(\_::$Info->OwnerDescription)):[];
$dps = \_::$Info->FullDescription?Convert::ToParagraphs(__(\_::$Info->FullDescription)):[];
$opsc = count($ops);
$dpsc = count($dps);
render(
	Html::Style("
		.{$module->Name} .content{
			text-align: center;
		}
		.{$module->Name} .image{
			max-height: 15vmax;
			padding: var(--size-5);
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