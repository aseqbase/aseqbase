<?php
module("Copyright");
$module = new \MiMFa\Module\Copyright();
$module->Content = \_::$Info->CopyRight??$module->Content;
pod($module, $data);
$module->Render();
?>