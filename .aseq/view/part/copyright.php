<?php
use \MiMFa\Library\Html;
module("Copyright");
$module = new \MiMFa\Module\Copyright();
$module->Description = \_::$Info->CopyRight??$module->Description;
swap($module, $data);
$module->Render();
?>