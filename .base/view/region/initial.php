<!DOCTYPE html>
<html lang="<?php echo \_::$Front->Translate->Language; ?>" dir="<?php echo \_::$Front->Translate->Direction; ?>">
	<head>
		<meta charset="<?php echo \_::$Config->Encoding; ?>">
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo \_::$Config->Encoding; ?>" />
		<!--[if lt IE 9]>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
		<![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta name="copyright" content="Copyright (C) 2004-2025 The MiMFa Group Software Foundation and contributors">
		<meta name="producer" content="http://www.MiMFa.net">
		<meta name="product" content="http://www.aseqbase.ir">
		<meta name="owner" content="<?php echo \_::$Info->FullOwner; ?>">
		<meta name="keywords" content="<?php echo implode(", ",\_::$Info->KeyWords); ?>">
		<meta name="abstract" content="<?php echo \_::$Info->FullDescription; ?>">
		<meta name="description" content="<?php echo \_::$Info->FullDescription; ?>">
		<meta name="twitter:Description" content="<?php echo \_::$Info->Description; ?>">
		<meta name="twitter:Image" content="<?php echo \MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath); ?>">
		<!-- LICENCE PART: Don`t remove this part -->
		<meta name="framework" content="aseqbase">
		<!-- LICENCE PART -->
		<?php echo join(PHP_EOL, \_::$Front->Libraries) . \_::$Front->GetInitial(); ?>