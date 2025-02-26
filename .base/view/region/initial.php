<!DOCTYPE html>
<html lang="<?php echo \_::$Back->Translate->Language; ?>" dir="<?php echo \_::$Back->Translate->Direction; ?>">
	<head>
		<meta charset="<?php echo \_::$Config->Encoding; ?>">
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo \_::$Config->Encoding; ?>" />
		<!--[if lt IE 9]>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
		<![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" >
		<meta name="copyright" content="Copyright (C) 1996-2023 The MiMFa Group Software Foundation and contributors">
		<meta name="producer" content="http://www.MiMFa.net">
		<meta name="product" content="http://www.aseqbase.ir">
		<meta name="owner" content="<?php echo \_::$Info->FullOwner; ?>">
		<meta name="keywords" content="<?php echo implode(", ",\_::$Info->KeyWords); ?>">
		<meta name="abstract" content="<?php echo \_::$Info->FullDescription; ?>">
		<meta name="description" content="<?php echo \_::$Info->FullDescription; ?>">
		<meta name="twitter:Description" content="<?php echo \_::$Info->Description; ?>">
		<meta name="twitter:Image" content="<?php echo forceFullUrl(\_::$Info->LogoPath); ?>">
		<!-- LICENCE PART: Don`t remove this part -->
		<meta name="framwork" content="aseqbase">
		<!-- LICENCE PART -->
		<?php component("JsonLD"); \MiMFa\Component\JsonLD::Website(); ?>
		<link rel='stylesheet' href='<?php echo forceFullUrl('/view/style/reset.css'); ?>'>
		<link rel='stylesheet' href='<?php echo forceFullUrl('/view/style/general.css'); ?>'>
		<link rel='stylesheet' href='<?php echo forceFullUrl('/view/style/be.css'); ?>'>
		<script src='<?php echo forceFullUrl('/view/script/general.js'); ?>'></script>
		<!--<script src='<?php echo forceFullUrl('/view/script/Live.js'); ?>'></script>-->
		<script src='<?php echo forceFullUrl('/view/script/Math.js'); ?>'></script>
		<script src='<?php echo forceFullUrl('/view/script/Array.js'); ?>'></script>
		<script src='<?php echo forceFullUrl('/view/script/Evaluate.js'); ?>'></script>
		<script src='<?php echo forceFullUrl('/view/script/Html.js'); ?>'></script>
		<?php
		if(isValid(\_::$Config->ReCaptchaSiteKey)) {
			library("recaptcha");
			echo \MiMFa\Library\reCaptcha::GetScript(\_::$Config->ReCaptchaSiteKey);
        }?>
		<?php echo join(PHP_EOL, \_::$Front->Libraries); ?>
		<link rel='stylesheet' href='<?php echo forceFullUrl('/view/style/view.css'); ?>'>
		<?php echo \_::$Front->GetInitial(); ?>