<!DOCTYPE html>
<html lang="<?php echo \MiMFa\Library\Translate::$Language; ?>" dir="<?php echo \MiMFa\Library\Translate::$Direction; ?>">
	<head>
		<meta charset="<?php echo \_::$CONFIG->Encoding; ?>">
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo \_::$CONFIG->Encoding; ?>" />
		<!--[if lt IE 9]>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
		<![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" >
		<meta name="copyright" content="Copyright (C) 1996-2023 The MiMFa Group Software Foundation and contributors">
		<meta name="producer" content="http://www.MiMFa.net">
		<meta name="product" content="http://www.aseqbase.ir">
		<meta name="owner" content="<?php echo \_::$INFO->FullOwner; ?>">
		<meta name="keywords" content="<?php echo implode(", ",\_::$INFO->KeyWords); ?>">
		<meta name="abstract" content="<?php echo \_::$INFO->FullDescription; ?>">
		<meta name="description" content="<?php echo \_::$INFO->FullDescription; ?>">
		<meta name="twitter:description" content="<?php echo \_::$INFO->Description; ?>">
		<meta name="twitter:image" content="<?php echo forceFullUrl(\_::$INFO->LogoPath); ?>">
		<!-- LICENCE PART: Don`t remove this part -->
		<meta name="framwork" content="aseqbase">
		<!-- LICENCE PART -->
		<?php COMPONENT("JSONLD"); (new \MiMFa\Component\JSONLD())->Draw(); ?>
		<link rel='stylesheet' href='<?php echo forceFullUrl('/view/style/reset.css'); ?>'>
		<link rel='stylesheet' href='<?php echo forceFullUrl('/view/style/general.css'); ?>'>
		<script src='<?php echo forceFullUrl('/view/script/general.js'); ?>'></script>
		<!--<script src='<?php echo forceFullUrl('/view/script/Live.js'); ?>'></script>-->
		<script src='<?php echo forceFullUrl('/view/script/Math.js'); ?>'></script>
		<script src='<?php echo forceFullUrl('/view/script/Array.js'); ?>'></script>
		<script src='<?php echo forceFullUrl('/view/script/Evaluate.js'); ?>'></script>
		<script src='<?php echo forceFullUrl('/view/script/Html.js'); ?>'></script>
		<?php
		if(isValid(\_::$CONFIG->ReCaptchaSiteKey)) {
			LIBRARY("reCaptcha");
			echo \MiMFa\Library\reCaptcha::GetScript(\_::$CONFIG->ReCaptchaSiteKey);
        }?>
		<?php echo \_::$TEMPLATE->BasePack; ?>
		<link rel='stylesheet' href='<?php echo forceFullUrl('/view/style/view.css'); ?>'>
		<?php
		echo \_::$TEMPLATE->GetInitial();
		echo \_::$TEMPLATE->CustomPack;
        ?>