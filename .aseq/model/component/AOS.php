<?php
use \MiMFa\Library\Html;
use MiMFa\Library\Script;
if (\_::$Front->AnimationSpeed) {
	$data["easing"] = get($data, "easing") ?? "ease-in-out-sine";
	$data["once"] = get($data, "once") ?? true;
	$data["duration"] = get($data, "duration") ?? \_::$Front->AnimationSpeed;
	$data["offset"] = get($data, "offset") ?? 0;
	\_::$Front->Initials[] =
		Html::Style(null, 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css') .
		Html::Script(null, 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js', ['crossorigin' => 'anonymous']);
	\_::$Front->Finals[] = Html::Script("AOS.init(" . Script::Convert($data) . ");");
}