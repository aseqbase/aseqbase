<?php
use \MiMFa\Library\Html;
use \MiMFa\Library\User;

module("RingTabs");
$module = new \MiMFa\Module\RingTabs();
$module->Image = \_::$Back->User->Image??\_::$Info->LogoPath;
$module->Items = \_::$Info->Services;
//swap($module, $data);
render(
	Html::Style("
		.page-home {
			padding: 10px 10px 50px;
		}
		.page-home .sign {
			padding: 10vh;
		}
	") .
	Html::Page(
		$module->Handle() .
		(!\_::$Config->AllowSigning || auth(\_::$Config->UserAccess) ? "" :
			Html::Center(
				Html::SmallSlot(
					Html::Button("Sign In", User::$InHandlerPath, ["class"=>"main"]) .
					Html::Button("Sign up", User::$UpHandlerPath)
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