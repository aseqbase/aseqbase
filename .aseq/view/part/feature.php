<?php
module("Gallery");
$module = new MiMFa\Module\Gallery();
$module->BlurSize = "1px";
$module->Items = table("Feature")->Select();
swap($module, $data);
$module->Render();
?>