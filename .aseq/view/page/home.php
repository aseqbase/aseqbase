<?php
use \MiMFa\Library\Struct;


module("RingTabs");
$module = new \MiMFa\Module\RingTabs();
$module->Image = \_::$User->Image??\_::$Info->LogoPath;
$module->Items = \_::$Info->Services;
//pod($module, $data);
response(
	Struct::Style("
		.page-home {
			padding: 10px 10px 50px;
		}
		.page-home .sign {
			padding: 10vh;
		}
	") .
	Struct::Page(
		$module->Handle() .
		(!\_::$User->AllowSigning || \_::$User->GetAccess(\_::$User->UserAccess) ? "" :
			Struct::Center(
				Struct::SmallSlot(
					Struct::Button("Sign In", \_::$User->InHandlerPath, ["class"=>"main"]) .
					Struct::Button("Sign up", \_::$User->UpHandlerPath)
					,
					["data-aos" => "zoom-out", "data-aos-duration" => "600"]
				),
				["class" => "sign"]
			)
		)
		,
		["class" => "page-home"]
	)
);