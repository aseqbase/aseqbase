<?php
module("Copyright");
$module = new \MiMFa\Module\Copyright();
$module->Content = \_::$Info->CopyRight??$module->Content;
dip($module, $data);
$module->Render();
?>