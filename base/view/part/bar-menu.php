<?php MODULE("BarMenu");
$module = new \MiMFa\Module\BarMenu();
$module->Items = \_::$INFO->Shortcuts;
$module->Draw();
?>