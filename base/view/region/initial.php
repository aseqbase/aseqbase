<!DOCTYPE html>
<html>
	<head>
		<meta charset="<?php echo \_::$CONFIG->Encoding; ?>">
		<!--[if lt IE 9]>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
		<![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" >
		<meta name="copyright" content="Copyright (C) 1996-2023 The MiMFa Group Software Foundation and contributors">
		<meta name="producer" content="http://www.MiMFa.net">
		<meta name="product" content="http://www.aseqbase.ir">
		<meta name="owner" content="<?php echo \_::$INFO->FullOwner; ?>">
		<link rel='stylesheet' href='<?php echo forceUrl('/view/style/reset.css'); ?>'>
		<link rel='stylesheet' href='<?php echo forceUrl('/view/style/general.css'); ?>'>
		<script src='<?php echo forceUrl('/view/script/general.js'); ?>'></script>
		<?php echo \_::$TEMPLATE->BasePack; ?>
		<link rel='stylesheet' href='<?php echo forceUrl('/view/style/view.css'); ?>'>
		<script src='https://unpkg.com/@ungap/custom-elements-builtin'></script>
		<?php
		echo \_::$TEMPLATE->GetInitial();
		echo \_::$TEMPLATE->CustomPack;
		?>