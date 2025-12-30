<?php module("Navigation");
$module = new \MiMFa\Module\Navigation();
$module->BackLink = \_::$Front->HomePath;
pod($module, $data);
$module->Render();
?>