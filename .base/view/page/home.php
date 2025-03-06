<?php
use \MiMFa\Library\Html;
use \MiMFa\Library\User;
\_::$Front->Style("
	.page-home {
		padding: 10px 10px 50px;
	}
");

module("RingSlide");
$module = new \MiMFa\Module\RingSlide();
$module->Image = \_::$Info->LogoPath;
$module->Items = \_::$Info->Services;
swap($module, $data);
\Res::Render(
	Html::Page(
		part("small-header", print: false) .
		$module->Handle().
		(!\_::$Config->AllowSigning || auth(\_::$Config->UserAccess) ? "" :
			Html::Center(
				Html::SmallSlot(
					Html::Button("Sign In", User::$InHandlerPath) .
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
?>