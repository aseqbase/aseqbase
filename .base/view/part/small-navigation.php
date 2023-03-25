<?php MODULE("Navigation");
$module = new \MiMFa\Module\Navigation();
$module->BackLink = \_::$INFO->HomePath;
$module->Draw();
?>