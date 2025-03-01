<?php module("Navigation");
$module = new \MiMFa\Module\Navigation();
$module->BackLink = \_::$Info->HomePath;
swap($module, $data);
$module->Render();
?>