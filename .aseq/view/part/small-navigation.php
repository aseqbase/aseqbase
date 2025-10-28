<?php module("Navigation");
$module = new \MiMFa\Module\Navigation();
$module->BackLink = \_::$Info->HomePath;
pod($module, $data);
$module->Render();
?>