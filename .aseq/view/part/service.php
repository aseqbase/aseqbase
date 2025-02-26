<?php
module("RingSlide");
$module = new \MiMFa\Module\RingSlide();
$module->Image = \_::$Info->FullLogoPath;
$module->Items = \_::$Info->Services;
$module->Render();
?>