<?php
use \MiMFa\Library\Struct;
use MiMFa\Library\Script;
if (\_::$Front->AnimationSpeed) {
	$data["easing"] = get($data, "easing") ?? "ease-in-out-sine";
	$data["once"] = get($data, "once") ?? true;
	$data["duration"] = get($data, "duration") ?? \_::$Front->AnimationSpeed;
	$data["offset"] = get($data, "offset") ?? 0;
	\_::$Front->Initials[] =
		get($data, "CDN")?
		Struct::Style(null, 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css') .
		Struct::Script(null, 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js', ['crossorigin' => 'anonymous']):
		Struct::Style(null, asset(\_::$Address->GlobalStructDirectory,'Animate/Animate.css')) .
		Struct::Script(null, asset(\_::$Address->GlobalStructDirectory,'Animate/Animate.js'), ['crossorigin' => 'anonymous']);
	\_::$Front->Finals[] = Struct::Script("AOS.init(" . Script::Convert($data) . ");");
}