<?php
module("Copyright");
$module = new \MiMFa\Module\Copyright();
$module->Content = \_::$Front->CopyRight??$module->Content;
pod($module, $data);
$module->Render();
?>