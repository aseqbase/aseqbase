<?php
use \MiMFa\Library\Struct;
if (\_::IsRendered()) {
	\_::$Front->Append("head", Struct::Style(null, 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'));
	\_::$Front->Append("head", Struct::Script(null, 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'));
} else
	\_::$Front->Libraries[] =
		Struct::Style(null, 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css') .
		Struct::Script(null, 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js');