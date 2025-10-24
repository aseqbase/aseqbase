<?php
module("BarMenu");
$module = new \MiMFa\Module\BarMenu();
$module->Items = \_::$Info->Shortcuts;
dip($module, $data);
$module->Render();